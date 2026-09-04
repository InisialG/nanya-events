<?php

namespace App\Livewire;

use App\Models\BankAccount;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SeatAvailability;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class SeatSelection extends Component
{
    public string $slug;
    public int $sessionId;

    public $event;
    public $eventSession;
    public $venue;
    public $seatCategories;
    
    public array $selectedSeatIds = [];
    public float $totalPrice = 0.0;
    public int $seatLockMinutes = 10;

    public function mount($slug, $sessionId)
    {
        $this->slug = $slug;
        $this->sessionId = $sessionId;

        $this->loadData();
        $this->cleanupExpiredLocks();
        $this->restoreUserSessionLocks();
    }

    public function loadData()
    {
        $query = Event::with('venue.seatCategories')->where('slug', $this->slug);

        if (!Auth::check() || (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin())) {
            $query->whereIn('status', ['registration', 'ongoing', 'published']);
        }

        $this->event = $query->firstOrFail();

        $this->eventSession = EventSession::where('id', $this->sessionId)
            ->where('event_id', $this->event->id)
            ->firstOrFail();

        $this->venue = $this->event->venue;
        $this->seatCategories = $this->venue->seatCategories->keyBy('id');
    }

    public function restoreUserSessionLocks()
    {
        $sessionKey = 'user_locked_seat_ids_' . $this->sessionId;
        $savedSeatIds = session($sessionKey, []);

        if (empty($savedSeatIds)) {
            return;
        }

        // Verify seats are still validly locked and not expired or sold
        $validSeats = SeatAvailability::whereIn('id', $savedSeatIds)
            ->where('event_session_id', $this->sessionId)
            ->where('status', 'locked')
            ->whereNull('order_id')
            ->where('locked_until', '>', now())
            ->pluck('id')
            ->toArray();

        $this->selectedSeatIds = array_values($validSeats);
        session([$sessionKey => $this->selectedSeatIds]);
        $this->calculateTotalPrice();
    }

    public function cleanupExpiredLocks()
    {
        // Auto-release locked seats whose locked_until has passed
        SeatAvailability::where('event_session_id', $this->sessionId)
            ->where('status', 'locked')
            ->whereNull('order_id')
            ->where('locked_until', '<', now())
            ->update([
                'status' => 'available',
                'order_id' => null,
                'locked_until' => null,
            ]);
    }

    public function clearAllSelectedSeats()
    {
        if (empty($this->selectedSeatIds)) {
            return;
        }

        SeatAvailability::whereIn('id', $this->selectedSeatIds)
            ->where('event_session_id', $this->sessionId)
            ->where('status', 'locked')
            ->whereNull('order_id')
            ->update([
                'status' => 'available',
                'locked_until' => null,
            ]);

        $this->selectedSeatIds = [];
        $this->totalPrice = 0;
        session()->forget('user_locked_seat_ids_' . $this->sessionId);

        session()->flash('success', 'Pilihan kursi telah dibatalkan.');
    }

    public function resetAllSeatsInSession()
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Akses ditolak. Hanya Super Admin yang dapat mereset seluruh status kursi.');
            return;
        }

        DB::transaction(function () {
            $seatAvails = SeatAvailability::where('event_session_id', $this->sessionId)->get();
            $seatAvailIds = $seatAvails->pluck('id');

            Ticket::whereIn('seat_availability_id', $seatAvailIds)->update([
                'status' => 'cancelled'
            ]);

            SeatAvailability::where('event_session_id', $this->sessionId)->update([
                'status' => 'available',
                'order_id' => null,
                'locked_until' => null,
            ]);

            session()->forget('user_locked_seat_ids_' . $this->sessionId);
        });

        $this->selectedSeatIds = [];
        $this->totalPrice = 0;
        session()->flash('success', '🎉 Seluruh kursi pada sesi ini telah BERHASIL DI-RESET menjadi KOSONG (TERSEDIA) kembali!');
    }

    public function toggleSeat(int $seatAvailabilityId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk mengunci kursi.');
        }

        if (in_array($this->event->status, ['ongoing', 'finished', 'coming_soon'])) {
            if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
                session()->flash('error', 'Pemesanan tiket sedang ditutup.');
                return;
            }
        }

        $seatAvail = SeatAvailability::select('id', 'event_session_id', 'seat_master_id', 'status', 'locked_until')
            ->where('id', $seatAvailabilityId)
            ->where('event_session_id', $this->sessionId)
            ->first();

        if (!$seatAvail) {
            return;
        }

        if ($seatAvail->status === 'sold') {
            $this->selectedSeatIds = array_values(array_diff($this->selectedSeatIds, [$seatAvailabilityId]));
            session()->flash('error', 'Kursi tersebut sudah terjual.');
            return;
        }

        $sessionKey = 'user_locked_seat_ids_' . $this->sessionId;
        $userLockedIds = session($sessionKey, []);

        $isCurrentlyLockedByMe = in_array($seatAvailabilityId, $userLockedIds);

        if ($seatAvail->status === 'locked') {
            if ($isCurrentlyLockedByMe) {
                // SAYA YANG KUNCI -> BUKA KUNCI (UNSELECT)
                // (Atomic Update) TOCTOU Race Condition Blocker: Hanya lepas jika order_id masih kosong!
                $updatedCount = SeatAvailability::where('id', $seatAvail->id)
                    ->whereNull('order_id')
                    ->update([
                        'status' => 'available',
                        'order_id' => null,
                        'locked_until' => null,
                    ]);

                // Jika gagal di-update (artinya order_id sudah diisi oleh proses Checkout yang bersamaan/lebih cepat)
                if ($updatedCount === 0) {
                    session()->flash('error', 'Kursi ini sudah masuk ke dalam pesanan/checkout Anda. Selesaikan atau batalkan pesanan Anda terlebih dahulu.');
                    return;
                }

                $this->selectedSeatIds = array_values(array_diff($this->selectedSeatIds, [$seatAvailabilityId]));
                session([$sessionKey => array_values($this->selectedSeatIds)]);
            } else {
                // ORANG LAIN YANG KUNCI -> DITOLAK & REVERT HIJAU KE MERAH!
                $this->selectedSeatIds = array_values(array_diff($this->selectedSeatIds, [$seatAvailabilityId]));
                session([$sessionKey => array_values($this->selectedSeatIds)]);

                session()->flash('error', 'Maaf, kursi tersebut baru saja dikunci oleh penonton lain!');
                return;
            }
        } else {
            // TERSEDIA -> KUNCI UNTUK SAYA
            DB::transaction(function () use ($seatAvail) {
                $seatAvail->update([
                    'status' => 'locked',
                    'order_id' => null,
                    'locked_until' => now()->addMinutes($this->seatLockMinutes),
                ]);
            });

            if (!in_array($seatAvailabilityId, $this->selectedSeatIds)) {
                $this->selectedSeatIds[] = $seatAvailabilityId;
            }
            session([$sessionKey => array_values($this->selectedSeatIds)]);
        }

        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        $this->totalPrice = 0;

        if (empty($this->selectedSeatIds)) {
            return;
        }

        $this->totalPrice = (float) DB::table('seat_availabilities')
            ->join('seat_masters', 'seat_availabilities.seat_master_id', '=', 'seat_masters.id')
            ->join('seat_categories', 'seat_masters.seat_category_id', '=', 'seat_categories.id')
            ->whereIn('seat_availabilities.id', $this->selectedSeatIds)
            ->sum('seat_categories.price');
    }

    public function proceedToCheckout()
    {
        if (count($this->selectedSeatIds) < 2) {
            session()->flash('error', 'Minimal pemesanan adalah 2 kursi per transaksi.');
            return;
        }

        if (count($this->selectedSeatIds) % 2 !== 0) {
            session()->flash('error', 'Pemesanan kursi harus genap (2, 4, 6, dst).');
            return;
        }

        session([
            'checkout_event_id' => $this->event->id,
            'checkout_session_id' => $this->eventSession->id,
            'checkout_seat_ids' => $this->selectedSeatIds,
            'checkout_total_amount' => $this->totalPrice,
        ]);

        return redirect()->route('checkout');
    }

    public function reserveVvipSeats()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            session()->flash('error', 'Akses ditolak. Fitur ini hanya untuk Admin/Super Admin.');
            return;
        }

        if (empty($this->selectedSeatIds)) {
            session()->flash('error', 'Pilih minimal 1 kursi untuk direservasi sebagai VVIP.');
            return;
        }

        $seats = SeatAvailability::with('seatMaster')
            ->whereIn('id', $this->selectedSeatIds)
            ->get();

        // Check if any selected seat is already sold
        foreach ($seats as $seat) {
            if ($seat->status === 'sold') {
                session()->flash('error', 'Kursi ' . $seat->seatMaster->seat_code . ' sudah terjual.');
                return;
            }
        }

        $createdTicketCodes = [];

        DB::transaction(function () use ($seats, &$createdTicketCodes) {
            $orderCode = 'NYA-VVIP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            $bankAccount = BankAccount::where('is_active', true)->first();

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => Auth::id(),
                'event_session_id' => $this->eventSession->id,
                'bank_account_id' => $bankAccount?->id,
                'total_amount' => 0,
                'unique_code' => 0,
                'final_amount' => 0,
                'status' => 'paid',
                'expired_at' => now()->addYears(1),
                'notes' => 'Reservasi VVIP / Complimentary oleh Admin: ' . Auth::user()->name,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'proof_path' => 'vvip_complimentary',
                'sender_bank' => 'VVIP',
                'sender_name' => Auth::user()->name,
                'transfer_amount' => 0,
                'uploaded_at' => now(),
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            foreach ($seats as $seat) {
                $seat->update([
                    'order_id' => $order->id,
                    'status' => 'sold',
                    'locked_until' => null,
                ]);

                $ticketCode = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                $qrHash = hash('sha256', $ticketCode . '-' . $seat->id . '-' . Str::random(12));

                Ticket::create([
                    'ticket_code' => $ticketCode,
                    'qr_code_hash' => $qrHash,
                    'order_id' => $order->id,
                    'seat_availability_id' => $seat->id,
                    'status' => 'valid',
                ]);

                $createdTicketCodes[] = $ticketCode;
            }
        });

        $this->selectedSeatIds = [];
        $this->totalPrice = 0;
        session()->forget('user_locked_seat_ids_' . $this->sessionId);

        session()->flash('success', '🎉 Berhasil mereservasi ' . count($createdTicketCodes) . ' Kursi VVIP (Bebas Bayar)! Tiket telah diterbitkan.');

        return redirect()->route('my-tickets.print-multiple', [
            'codes' => implode(',', $createdTicketCodes)
        ]);
    }

    public function render()
    {
        $seatAvailabilities = SeatAvailability::with('seatMaster.seatCategory')
            ->where('event_session_id', $this->sessionId)
            ->get();

        $groupedSeatsByRow = [];
        $rowLetters = ['A','B','C','D','E','F','G','H','J','K','L','M','N','P','R','S','T'];
        foreach ($rowLetters as $rl) {
             $groupedSeatsByRow[$rl] = ['L' => [], 'C' => [], 'R' => []];
        }

        foreach ($seatAvailabilities as $avail) {
            $code = $avail->seatMaster->seat_code;
            if (preg_match('/^([LCR])-([A-Z]+)(\d+)$/', $code, $matches)) {
                $zone = $matches[1];
                $rowLetter = $matches[2];
                $groupedSeatsByRow[$rowLetter][$zone][] = $avail;
            }
        }

        foreach ($groupedSeatsByRow as $row => $zones) {
            foreach (['L', 'C', 'R'] as $z) {
                usort($groupedSeatsByRow[$row][$z], function($a, $b) {
                    return $b->seatMaster->col_num <=> $a->seatMaster->col_num;
                });
            }
        }

        // Hapus baris yang kosong di semua zona agar tidak render ruang kosong
        foreach ($groupedSeatsByRow as $row => $zones) {
            if (empty($zones['L']) && empty($zones['C']) && empty($zones['R'])) {
                unset($groupedSeatsByRow[$row]);
            }
        }

        return view('livewire.seat-selection', [
            'seatAvailabilities' => $seatAvailabilities,
            'groupedSeatsByRow' => $groupedSeatsByRow,
        ])->layout('layouts.app');
    }
}
