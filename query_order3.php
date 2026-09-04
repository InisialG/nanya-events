<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$paidNoTickets = \App\Models\Order::where('status', 'paid')
    ->doesntHave('tickets')
    ->get();

$noSeatLinked = 0;
foreach ($paidNoTickets as $o) {
    $seatCount = \App\Models\SeatAvailability::where('order_id', $o->id)->count();
    if ($seatCount == 0) {
        $noSeatLinked++;
    }
}
echo "Paid Orders without tickets: " . $paidNoTickets->count() . "\n";
echo "Paid Orders with NO linked seats: " . $noSeatLinked . "\n";
