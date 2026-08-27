<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyTicketsController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::with([
            'eventSession.event',
            'bankAccount',
        ])
        ->where('user_id', Auth::id())
        ->whereIn('status', ['pending_payment', 'waiting_verification', 'cancelled'])
        ->orderBy('created_at', 'desc')
        ->get();

        $tickets = Ticket::with([
            'order.eventSession.event.venue',
            'seatAvailability.seatMaster.seatCategory',
        ])
        ->whereHas('order', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);

        return view('tickets.index', compact('pendingOrders', 'tickets'));
    }

    public function show($ticketCode)
    {
        $ticket = Ticket::with([
            'order.eventSession.event.venue',
            'seatAvailability.seatMaster.seatCategory',
        ])
        ->where('ticket_code', $ticketCode)
        ->whereHas('order', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->firstOrFail();

        return view('tickets.show', compact('ticket'));
    }

    public function printMultiple(Request $request)
    {
        $codes = $request->input('codes');
        if (is_string($codes)) {
            $codes = explode(',', $codes);
        }
        $codes = array_filter((array) $codes);

        if (empty($codes)) {
            return redirect()->route('my-tickets.index')->with('error', 'Silakan pilih minimal 1 tiket yang ingin dicetak.');
        }

        $tickets = Ticket::with([
            'order.eventSession.event.venue',
            'seatAvailability.seatMaster.seatCategory',
        ])
        ->whereIn('ticket_code', $codes)
        ->whereHas('order', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->orderBy('created_at', 'asc')
        ->get();

        if ($tickets->isEmpty()) {
            return redirect()->route('my-tickets.index')->with('error', 'Tiket yang Anda pilih tidak ditemukan.');
        }

        return view('tickets.show-multiple', compact('tickets'));
    }
}
