<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$seat = \App\Models\SeatAvailability::where('status', 'available')->first();
$order = \App\Models\Order::latest()->first();

$seat->update([
    'order_id' => $order->id,
    'status' => 'locked',
    'locked_until' => $order->expired_at,
]);

echo "Seat order_id: " . $seat->order_id . "\n";
echo "Seat locked_until: " . $seat->locked_until . "\n";

$job = new \App\Jobs\ReleaseExpiredSeatsJob();
$job->handle();

$seat->refresh();
echo "Seat status after job: " . $seat->status . "\n";
echo "Seat order_id after job: " . $seat->order_id . "\n";
