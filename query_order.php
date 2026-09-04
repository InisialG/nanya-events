<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::where('order_code', 'NYA-20260902-TFUFQT')->with('tickets')->first();
if ($order) {
    print_r($order->toArray());
} else {
    echo "Order not found\n";
}
