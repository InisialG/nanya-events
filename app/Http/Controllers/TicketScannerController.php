<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketScannerController extends Controller
{
    public function index()
    {
        $todayScannedCount = Ticket::where('status', 'used')
            ->whereDate('scanned_at', today())
            ->count();

        return view('scanner.index', compact('todayScannedCount'));
    }

    public function validateTicket(Request $request)
    {
        $request->validate([
            'qr_code' => ['required', 'string'],
        ]);

        $queryStr = trim($request->qr_code);

        $ticket = Ticket::with([
            'order.user',
            'order.eventSession.event.venue',
            'seatAvailability.seatMaster.seatCategory',
            'scanner',
        ])
        ->where('qr_code_hash', $queryStr)
        ->orWhere('ticket_code', $queryStr)
        ->first();

        // 1. Tiket tidak ditemukan
        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => '❌ TIKET TIDAK DITEMUKAN / KODE QR INVALID!',
            ], 404);
        }

        // 2. Tiket dibatalkan / order tidak lunas
        if ($ticket->status === 'cancelled' || $ticket->order->status !== 'paid') {
            return response()->json([
                'status' => 'error',
                'message' => '❌ TIKET BATAL ATAU STATUS PEMBAYARAN BELUM LUNAS!',
                'ticket_code' => $ticket->ticket_code,
            ], 422);
        }

        // 3. Tiket sudah pernah dipindai sebelumnya (Idempotency Anti-Duplikasi)
        if ($ticket->status === 'used') {
            return response()->json([
                'status' => 'already_used',
                'message' => '⚠️ TIKET SUDAH PERNAH DIPINDAI pada ' . ($ticket->scanned_at ? $ticket->scanned_at->format('d M Y, H:i:s') : '-') . ' oleh ' . ($ticket->scanner?->name ?? 'Petugas') . '!',
                'ticket' => [
                    'ticket_code' => $ticket->ticket_code,
                    'user_name' => $ticket->order->user->name,
                    'event_title' => $ticket->order->eventSession->event->title,
                    'seat_code' => $ticket->seatAvailability->seatMaster->seat_code,
                    'category' => $ticket->seatAvailability->seatMaster->seatCategory?->name ?? 'Reguler',
                    'scanned_at' => $ticket->scanned_at ? $ticket->scanned_at->format('d M Y, H:i:s') : '-',
                    'scanned_by' => $ticket->scanner?->name ?? 'Petugas',
                ],
            ], 409);
        }

        // 4. Tiket Valid ➔ Tandai Pemindaian Sukses
        DB::transaction(function () use ($ticket) {
            $ticket->update([
                'status' => 'used',
                'scanned_at' => now(),
                'scanned_by' => Auth::id(),
            ]);
        });

        $todayScannedCount = Ticket::where('status', 'used')
            ->whereDate('scanned_at', today())
            ->count();

        return response()->json([
            'status' => 'success',
            'message' => '✅ TIKET VALID & BERHASIL DIPINDAI!',
            'today_scanned_count' => $todayScannedCount,
            'ticket' => [
                'ticket_code' => $ticket->ticket_code,
                'user_name' => $ticket->order->user->name,
                'user_email' => $ticket->order->user->email,
                'event_title' => $ticket->order->eventSession->event->title,
                'venue_name' => $ticket->order->eventSession->event->venue->name,
                'session_date' => $ticket->order->eventSession->session_date,
                'seat_code' => $ticket->seatAvailability->seatMaster->seat_code,
                'category' => $ticket->seatAvailability->seatMaster->seatCategory?->name ?? 'Reguler',
            ],
        ]);
    }

    public function resetTicketStatus(Request $request)
    {
        $request->validate([
            'ticket_code' => ['required', 'string'],
        ]);

        $ticket = Ticket::where('ticket_code', $request->ticket_code)->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket tidak ditemukan.',
            ], 404);
        }

        $ticket->update([
            'status' => 'valid',
            'scanned_at' => null,
            'scanned_by' => null,
        ]);

        $todayScannedCount = Ticket::where('status', 'used')
            ->whereDate('scanned_at', today())
            ->count();

        return response()->json([
            'status' => 'success',
            'message' => '✅ Status tiket ' . $ticket->ticket_code . ' berhasil di-reset menjadi TERSEDIA / VALID kembali!',
            'today_scanned_count' => $todayScannedCount,
        ]);
    }
}
