<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$venue = \App\Models\Venue::first();
$cat = \App\Models\SeatCategory::where('name', 'DIAMOND')->first();

if (!\App\Models\SeatMaster::where('seat_code', 'C-R07')->exists()) {
    $seat = \App\Models\SeatMaster::create([
        'venue_id' => $venue->id,
        'seat_category_id' => $cat->id,
        'seat_code' => 'C-R07',
        'row_num' => 15,
        'col_num' => 7,
        'is_active' => true,
    ]);

    $sessions = \App\Models\EventSession::all();
    foreach($sessions as $session) {
        \App\Models\SeatAvailability::create([
            'event_session_id' => $session->id,
            'seat_master_id' => $seat->id,
            'status' => 'available',
        ]);
    }
    echo "Inserted C-R07 successfully.\n";
} else {
    echo "C-R07 already exists.\n";
}
