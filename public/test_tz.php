<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$order = \App\Models\Order::latest()->first();
if ($order) {
    echo "Now: " . now()->toDateTimeString() . "\n";
    echo "Order Expired At: " . $order->expired_at->toDateTimeString() . "\n";
    echo "Expired At Raw: " . $order->getRawOriginal('expired_at') . "\n";
    echo "Timezone: " . config('app.timezone') . "\n";
    echo "DB Timezone: " . \Illuminate\Support\Facades\DB::select('SELECT @@session.time_zone')[0]->{'@@session.time_zone'} . "\n";
    
    $isExpired = $order->expired_at < now();
    echo "Is Expired according to PHP: " . ($isExpired ? 'YES' : 'NO') . "\n";
    
    $isExpiredDB = \App\Models\Order::where('id', $order->id)->where('expired_at', '<', now())->exists();
    echo "Is Expired according to DB Query: " . ($isExpiredDB ? 'YES' : 'NO') . "\n";
} else {
    echo "No orders found.";
}
