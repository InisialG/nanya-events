<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = \App\Models\Order::withCount('seatAvailabilities')->get();
foreach($orders as $order) {
    if ($order->seat_availabilities_count > 0 && $order->seat_availabilities_count != ($order->total_amount / 150000)) { // rough heuristic, let's just dump all
        echo $order->order_code . ' | Amt: ' . $order->total_amount . ' | Seats: ' . $order->seat_availabilities_count . ' | Status: ' . $order->status . "\n";
    }
}
