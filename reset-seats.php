<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

App\Models\SeatAvailability::query()->update([
    'status' => 'available',
    'order_id' => null,
    'locked_until' => null,
]);

App\Models\Ticket::truncate();
App\Models\Payment::truncate();
App\Models\Order::query()->delete();

echo "Seats reset successfully.\n";
