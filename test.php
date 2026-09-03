<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "SeatMaster: " . \App\Models\SeatMaster::count() . "\n";
echo "SeatAvailability: " . \App\Models\SeatAvailability::count() . "\n";
