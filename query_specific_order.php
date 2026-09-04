<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::with(['payment', 'user', 'tickets.seatAvailability.seatMaster'])
    ->where('order_code', 'NYA-20260902-HE717T')
    ->first();

if (!$order) {
    echo "Order NYA-20260902-HE717T tidak ditemukan.\n";
    exit;
}

echo "Order Code: " . $order->order_code . "\n";
echo "User: " . ($order->user->name ?? 'N/A') . "\n";
echo "Status: " . $order->status . "\n";
echo "Expired At: " . ($order->expired_at ? $order->expired_at : 'null') . "\n";
echo "Payment Proof: " . ($order->payment ? "Yes (Uploaded at " . $order->payment->uploaded_at . ")" : "No") . "\n";
echo "Tickets Count: " . $order->tickets->count() . "\n";
foreach ($order->tickets as $ticket) {
    echo "- Ticket: " . $ticket->ticket_code . " (Seat: " . ($ticket->seatAvailability->seatMaster->seat_code ?? '?') . ")\n";
}

$seatAvails = \App\Models\SeatAvailability::with('seatMaster')->where('order_id', $order->id)->get();
echo "Seat Availabilities linked to this order: " . $seatAvails->count() . "\n";
foreach ($seatAvails as $seat) {
    echo "- Seat: " . ($seat->seatMaster->seat_code ?? '?') . " (Status: " . $seat->status . ")\n";
}
