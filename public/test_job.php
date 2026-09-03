<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$eventSession = \App\Models\EventSession::with('event')->first();
$event = $eventSession->event;

$timeoutHours = (int) $event->payment_verification_timeout_hours;
if ($timeoutHours <= 0) {
    $timeoutHours = 24;
}

$order = \App\Models\Order::create([
    'order_code' => 'TEST-' . time(),
    'user_id' => 1,
    'event_session_id' => $eventSession->id,
    'bank_account_id' => 1,
    'total_amount' => 100000,
    'unique_code' => 0,
    'final_amount' => 100000,
    'status' => 'pending_payment',
    'expired_at' => now()->addHours($timeoutHours),
]);

echo "Order created. Expired At: " . $order->expired_at . "\n";
echo "Now: " . now() . "\n";
echo "Difference in hours: " . $order->expired_at->diffInHours(now()) . "\n";

$job = new \App\Jobs\CancelExpiredOrdersJob();
$job->handle();

$order->refresh();
echo "Order Status after Job: " . $order->status . "\n";
