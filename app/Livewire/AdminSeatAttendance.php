<?php

namespace App\Livewire;

use App\Models\EventSession;
use App\Models\SeatAvailability;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminSeatAttendance extends Component
{
    public ?int $sessionId = null;
    public ?array $selectedSeatDetails = null;
    public string $searchQuery = '';
    public string $filterStatus = 'all'; // 'all', 'attended', 'pending', 'available'

    public function mount(?int $sessionId = null)
    {
        if ($sessionId) {
            $this->sessionId = $sessionId;
        } else {
            // Default to first active session
            $firstSession = EventSession::orderBy('session_date', 'asc')->first();
            $this->sessionId = $firstSession?->id;
        }
    }

    public function selectSession(int $id)
    {
        $this->sessionId = $id;
        $this->selectedSeatDetails = null;
    }

    public function resetAllSeatsInSession()
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            session()->flash('error', 'Akses ditolak. Hanya Super Admin yang dapat mereset seluruh status kursi.');
            return;
        }

        if (!$this->sessionId) {
            return;
        }

        DB::transaction(function () {
            $seatAvails = SeatAvailability::where('event_session_id', $this->sessionId)->get();
            $seatAvailIds = $seatAvails->pluck('id');

            // Cancel tickets for this session
            Ticket::whereIn('seat_availability_id', $seatAvailIds)->update([
                'status' => 'cancelled'
            ]);

            // Reset seat availability status to available
            SeatAvailability::where('event_session_id', $this->sessionId)->update([
                'status' => 'available',
                'order_id' => null,
                'locked_until' => null,
            ]);

            // Clear transient session locks
            session()->forget('user_locked_seat_ids_' . $this->sessionId);
        });

        $this->selectedSeatDetails = null;
        session()->flash('success', '🎉 Seluruh kursi pada sesi ini telah BERHASIL DI-RESET menjadi KOSONG (TERSEDIA) kembali!');
    }

    public function showSeatDetail(int $seatAvailabilityId)
    {
        $seat = SeatAvailability::with([
            'seatMaster.seatCategory',
            'ticket.order.user',
            'ticket.scanner',
        ])
        ->where('id', $seatAvailabilityId)
        ->first();

        if (!$seat) return;

        $ticket = $seat->ticket;

        $this->selectedSeatDetails = [
            'id' => $seat->id,
            'seat_code' => $seat->seatMaster->seat_code,
            'row_num' => $seat->seatMaster->row_num,
            'col_num' => $seat->seatMaster->col_num,
            'category_name' => $seat->seatMaster->seatCategory?->name ?? 'Reguler',
            'category_color' => $seat->seatMaster->seatCategory?->color_code ?? '#00D4E6',
            'price' => $seat->seatMaster->seatCategory?->price ?? 0,
            'seat_status' => $seat->status,
            'has_ticket' => (bool)$ticket,
            'ticket_code' => $ticket?->ticket_code,
            'ticket_status' => $ticket?->status,
            'is_attended' => $ticket && $ticket->status === 'used',
            'is_pending' => $ticket && $ticket->status === 'valid',
            'user_name' => $ticket?->order?->user?->name,
            'user_email' => $ticket?->order?->user?->email,
            'scanned_at' => $ticket?->scanned_at ? $ticket->scanned_at->translatedFormat('d M Y, H:i:s') : null,
            'scanned_by' => $ticket?->scanner?->name,
        ];
    }

    public function closeSeatDetail()
    {
        $this->selectedSeatDetails = null;
    }

    public function render()
    {
        $sessions = EventSession::with('event')->orderBy('session_date', 'asc')->get();

        $activeSession = EventSession::with('event.venue')->find($this->sessionId);

        $seatAvailabilities = collect();
        $totalCapacity = 0;
        $totalSold = 0;
        $totalAttended = 0;
        $totalPending = 0;
        $totalAvailable = 0;
        $attendancePercentage = 0;

        if ($activeSession) {
            $seatsQuery = SeatAvailability::with(['seatMaster.seatCategory', 'ticket.order.user', 'ticket.scanner'])
                ->where('event_session_id', $this->sessionId)
                ->get();

            $totalCapacity = $seatsQuery->filter(fn($s) => $s->seatMaster->is_active)->count();
            
            foreach ($seatsQuery as $s) {
                if (!$s->seatMaster->is_active) continue;

                $t = $s->ticket;
                if ($t && $t->status === 'used') {
                    $totalAttended++;
                    $totalSold++;
                } elseif ($t && $t->status === 'valid') {
                    $totalPending++;
                    $totalSold++;
                } elseif ($s->status === 'sold') {
                    $totalSold++;
                } else {
                    $totalAvailable++;
                }
            }

            $attendancePercentage = $totalSold > 0 ? round(($totalAttended / $totalSold) * 100, 1) : 0;

            $groupedSeatsByRow = [];
            $rowLetters = ['A','B','C','D','E','F','G','H','J','K','L','M','N','P','R','S','T'];
            foreach ($rowLetters as $rl) {
                 $groupedSeatsByRow[$rl] = ['L' => [], 'C' => [], 'R' => []];
            }

            foreach ($seatsQuery as $avail) {
                if (!$avail->seatMaster->is_active) continue;

                $code = $avail->seatMaster->seat_code;
                if (preg_match('/^([LCR])-([A-Z]+)(\d+)$/', $code, $matches)) {
                    $zone = $matches[1];
                    $rowLetter = $matches[2];
                    $groupedSeatsByRow[$rowLetter][$zone][] = $avail;
                }
            }

            foreach ($groupedSeatsByRow as $row => &$zones) {
                foreach (['L', 'C', 'R'] as $z) {
                    usort($zones[$z], function($a, $b) {
                        return $b->seatMaster->col_num <=> $a->seatMaster->col_num;
                    });
                }
            }

            foreach ($groupedSeatsByRow as $row => $zones) {
                if (empty($zones['L']) && empty($zones['C']) && empty($zones['R'])) {
                    unset($groupedSeatsByRow[$row]);
                }
            }
        }

        return view('livewire.admin-seat-attendance', [
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'groupedSeatsByRow' => $groupedSeatsByRow ?? [],
            'totalCapacity' => $totalCapacity,
            'totalSold' => $totalSold,
            'totalAttended' => $totalAttended,
            'totalPending' => $totalPending,
            'totalAvailable' => $totalAvailable,
            'attendancePercentage' => $attendancePercentage,
        ])->layout('layouts.app');
    }
}
