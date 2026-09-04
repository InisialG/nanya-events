<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::where('order_code', 'NYA-20260902-TFUFQT')->first();
if ($order) {
    echo "Order Status: " . $order->status . "\n";
    $seats = \App\Models\SeatAvailability::where('order_id', $order->id)->get();
    echo "Seats Count: " . $seats->count() . "\n";
    foreach ($seats as $seat) {
        echo "Seat ID: {$seat->id}, Status: {$seat->status}\n";
    }
} else {
    echo "Order not found\n";
}

// Find all paid orders without tickets
$paidNoTickets = \App\Models\Order::where('status', 'paid')
    ->doesntHave('tickets')
    ->get();
echo "Paid Orders without tickets: " . $paidNoTickets->count() . "\n";
foreach ($paidNoTickets as $o) {
    echo "- Order: {$o->order_code}\n";
}

// Find all orders that might be in a weird state
$weirdOrders = \App\Models\Order::where('status', 'cancelled')
    ->whereHas('payment', function($q) {
        $q->whereNotNull('verified_at');
    })
    ->get();
echo "Cancelled Orders but payment verified: " . $weirdOrders->count() . "\n";

