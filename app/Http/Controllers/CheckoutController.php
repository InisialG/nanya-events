<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Order;
use App\Models\Payment;
use App\Models\SeatAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    public function showCheckout(Request $request)
    {
        $seatIds = session('checkout_seat_ids', []);
        $sessionId = session('checkout_session_id');

        if (empty($seatIds) || !$sessionId) {
            return redirect('/')->with('error', 'Sesi pemesanan telah berakhir. Silakan pilih kursi kembali.');
        }

        if (count($seatIds) < 2) {
            return redirect('/')->with('error', 'Minimal pemesanan adalah 2 kursi per transaksi.');
        }

        $session = EventSession::with(['event.venue'])->findOrFail($sessionId);
        $event = $session->event;

        $seats = SeatAvailability::with(['seatMaster.seatCategory'])
            ->whereIn('id', $seatIds)
            ->get();

        $bankAccounts = BankAccount::where('is_active', true)->get();

        $totalAmount = 0;
        foreach ($seats as $seat) {
            $totalAmount += $seat->seatMaster->seatCategory?->price ?? 0;
        }

        // Tanpa kode unik (0)
        $uniqueCode = 0;
        $finalAmount = $totalAmount;

        session([
            'checkout_unique_code' => $uniqueCode,
            'checkout_final_amount' => $finalAmount,
        ]);

        return view('checkout.index', compact('event', 'session', 'seats', 'bankAccounts', 'totalAmount', 'uniqueCode', 'finalAmount'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
        ]);

        $seatIds = session('checkout_seat_ids', []);
        $sessionId = session('checkout_session_id');
        $uniqueCode = 0;

        if (empty($seatIds) || !$sessionId) {
            return redirect('/')->with('error', 'Sesi pemesanan telah berakhir.');
        }

        if (count($seatIds) < 2) {
            return redirect('/')->with('error', 'Minimal pemesanan adalah 2 kursi per transaksi.');
        }

        $eventSession = EventSession::with('event')->findOrFail($sessionId);
        $event = $eventSession->event;
        $timeoutHours = (int) $event->payment_verification_timeout_hours;
        if ($timeoutHours <= 0) {
            $timeoutHours = 24;
        }

        $orderCode = 'NYA-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        try {
            $order = DB::transaction(function () use ($request, $orderCode, $eventSession, $uniqueCode, $timeoutHours, $seatIds) {
                // 1. Ambil kursi dari DB dan KUNCI barisnya agar tidak bisa dibeli berbarengan (Race Condition / Pessimistic Locking)
                $seats = SeatAvailability::with('seatMaster.seatCategory')
                    ->whereIn('id', $seatIds)
                    ->lockForUpdate()
                    ->get();

                // 2. Validasi Kursi Ditemukan & Jumlah Sesuai
                if ($seats->isEmpty() || $seats->count() !== count($seatIds)) {
                    throw new \Exception('Beberapa kursi tidak valid atau sudah dihapus dari sistem.');
                }

                $totalAmount = 0;
                foreach ($seats as $seat) {
                    // 3. Validasi Kursi Belum Dibeli Orang Lain
                    if ($seat->status === 'sold' || $seat->order_id !== null) {
                        throw new \Exception('Maaf, kursi ' . ($seat->seatMaster->seat_code ?? '') . ' baru saja di-checkout oleh penonton lain.');
                    }
                    
                    $totalAmount += $seat->seatMaster->seatCategory?->price ?? 0;
                }

                // 4. Validasi Harga (Mencegah Order Rp 0 / Exploit)
                if ($totalAmount <= 0) {
                    throw new \Exception('Terjadi kesalahan perhitungan harga (Total Rp 0).');
                }

                $finalAmount = $totalAmount;

                $order = Order::create([
                    'order_code' => $orderCode,
                    'user_id' => Auth::id(),
                    'event_session_id' => $eventSession->id,
                    'bank_account_id' => $request->bank_account_id,
                    'total_amount' => $totalAmount,
                    'unique_code' => $uniqueCode,
                    'final_amount' => $finalAmount,
                    'status' => 'pending_payment',
                    'expired_at' => now()->addHours($timeoutHours),
                ]);

                // Assign order_id and set status = locked
                foreach ($seats as $seat) {
                    $seat->update([
                        'order_id' => $order->id,
                        'status' => 'locked',
                        'locked_until' => $order->expired_at,
                    ]);
                }

                return $order;
            });
        } catch (\Exception $e) {
            // Bersihkan session jika gagal agar user tidak terus-terusan error
            session()->forget([
                'checkout_seat_ids', 
                'checkout_session_id', 
                'checkout_event_id', 
                'checkout_unique_code', 
                'checkout_final_amount',
                'user_locked_seat_ids_' . $eventSession->id
            ]);
            
            return redirect('/')->with('error', $e->getMessage());
        }

        // Clear checkout transient sessions
        session()->forget([
            'checkout_seat_ids', 
            'checkout_session_id', 
            'checkout_event_id', 
            'checkout_unique_code', 
            'checkout_final_amount',
            'user_locked_seat_ids_' . $eventSession->id
        ]);

        return redirect()->route('checkout.instructions', $order->order_code);
    }

    public function showPaymentInstructions($orderCode)
    {
        $order = Order::with(['eventSession.event.venue', 'bankAccount', 'seatAvailabilities.seatMaster.seatCategory', 'payment'])
            ->where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if expired
        if (in_array($order->status, ['pending_payment', 'waiting_verification']) && $order->expired_at < now()) {
            DB::transaction(function () use ($order) {
                $order->update(['status' => 'cancelled']);
                SeatAvailability::where('order_id', $order->id)
                    ->update([
                        'status' => 'available',
                        'order_id' => null,
                        'locked_until' => null,
                    ]);
            });
        }

        // Kita tidak akan me-redirect pengguna ke halaman depan, 
        // melainkan membiarkan view merender UI khusus "Pesanan Dibatalkan".
        // if ($order->status === 'cancelled') {
        //     return redirect()->route('events.index')->with('error', 'Pesanan ' . $order->order_code . ' telah dibatalkan karena melewati batas waktu (expired).');
        // }

        if ($order->status === 'paid') {
            return redirect()->route('my-tickets.index')->with('success', 'Pembayaran untuk pesanan ' . $order->order_code . ' telah disetujui! E-Tiket Anda telah diterbitkan.');
        }

        return view('checkout.instructions', compact('order'));
    }

    public function uploadProof(Request $request, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status === 'cancelled' || $order->expired_at < now()) {
            return redirect()->route('events.index')->with('error', 'Waktu pembayaran telah habis (expired).');
        }

        $request->validate([
            'proof_file' => ['required', 'file', 'image', 'max:10240'],
            'sender_bank' => ['required', 'string'],
            'sender_name' => ['required', 'string'],
        ], [
            'proof_file.required' => 'File bukti transfer wajib dipilih.',
            'proof_file.image' => 'File harus berupa gambar (JPG, PNG).',
            'proof_file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $file = $request->file('proof_file');
            
            // Upload file fisik langsung ke Cloudinary REST API
            $uploadResult = \App\Services\CloudinaryService::upload($file, 'nanya-payment-proofs');

            if (!$uploadResult['success'] || !$uploadResult['url']) {
                $errorMsg = $uploadResult['error'] ?? 'Gagal mengunggah file ke Cloudinary.';
                throw new \Exception($errorMsg);
            }

            // Hapus file temporary di server secepatnya
            if (file_exists($file->getRealPath())) {
                @unlink($file->getRealPath());
            }

            $cloudinaryUrl = $uploadResult['url'];

            DB::transaction(function () use ($order, $cloudinaryUrl, $request) {
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'proof_path' => $cloudinaryUrl,
                        'sender_bank' => $request->input('sender_bank'),
                        'sender_name' => $request->input('sender_name'),
                        'transfer_amount' => $order->final_amount,
                        'uploaded_at' => now(),
                    ]
                );

                \Illuminate\Support\Facades\DB::table('orders')->where('id', $order->id)->update([
                    'status' => 'waiting_verification',
                    'expired_at' => \Illuminate\Support\Facades\DB::raw('DATE_ADD(created_at, INTERVAL 24 HOUR)'),
                    'updated_at' => now(),
                ]);
            });

            return redirect()->route('checkout.success', $order->order_code)->with('success', 'Bukti transfer berhasil diunggah!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors([
                'proof_file' => 'Gagal upload: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate Cloudinary upload signature untuk direct browser upload.
     * Browser akan upload langsung ke Cloudinary tanpa lewat PHP server.
     */
    public function cloudinarySignature(Request $request, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status === 'cancelled' || $order->expired_at < now()) {
            return response()->json(['success' => false, 'error' => 'Waktu pembayaran telah habis (expired).'], 403);
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            return response()->json(['success' => false, 'error' => 'Cloudinary credentials missing'], 500);
        }

        $timestamp = time();
        $folder = 'nanya-payment-proofs';
        $stringToSign = "folder={$folder}&timestamp={$timestamp}" . $apiSecret;
        $signature = sha1($stringToSign);

        return response()->json([
            'success' => true,
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder' => $folder,
        ]);
    }

    /**
     * Generate Cloudinary upload signature untuk direct browser upload umum (Poster / Payment Proof).
     */
    public function generalCloudinarySignature(Request $request)
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'ixfxenut');
        $apiKey = env('CLOUDINARY_API_KEY', '538827258143667');
        $apiSecret = env('CLOUDINARY_API_SECRET', 'qKuLHTTObKPTvM-ZXHc5Rt5nVMs');

        $timestamp = time();
        $folder = $request->query('folder', 'nanya-event-posters');
        $stringToSign = "folder={$folder}&timestamp={$timestamp}" . $apiSecret;
        $signature = sha1($stringToSign);

        return response()->json([
            'success' => true,
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder' => $folder,
        ]);
    }

    /**
     * Simpan URL Cloudinary hasil upload langsung dari browser.
     */
    public function saveProofUrl(Request $request, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status === 'cancelled' || $order->expired_at < now()) {
            return response()->json(['success' => false, 'message' => 'Waktu pembayaran telah habis (expired).'], 403);
        }

        $cloudinaryUrl = $request->input('cloudinary_url');
        $senderBank = $request->input('sender_bank');
        $senderName = $request->input('sender_name');

        if (empty($cloudinaryUrl) || !str_contains($cloudinaryUrl, 'cloudinary')) {
            return response()->json(['success' => false, 'message' => 'URL Cloudinary tidak valid.'], 422);
        }

        try {
            DB::transaction(function () use ($order, $cloudinaryUrl, $senderBank, $senderName) {
                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'proof_path' => $cloudinaryUrl,
                        'sender_bank' => $senderBank,
                        'sender_name' => $senderName,
                        'transfer_amount' => $order->final_amount,
                        'uploaded_at' => now(),
                    ]
                );

                \Illuminate\Support\Facades\DB::table('orders')->where('id', $order->id)->update([
                    'status' => 'waiting_verification',
                    'expired_at' => \Illuminate\Support\Facades\DB::raw('DATE_ADD(created_at, INTERVAL 24 HOUR)'),
                    'updated_at' => now(),
                ]);
            });

            return response()->json([
                'success' => true,
                'redirect_url' => route('checkout.success', $order->order_code)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showSuccess($orderCode)
    {
        $order = Order::with(['eventSession.event.venue', 'bankAccount', 'seatAvailabilities.seatMaster.seatCategory', 'payment'])
            ->where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status === 'paid') {
            return redirect()->route('my-tickets.index')->with('success', 'Pembayaran untuk pesanan ' . $order->order_code . ' telah disetujui! E-Tiket Anda telah diterbitkan.');
        }

        return view('checkout.success', compact('order'));
    }
}
