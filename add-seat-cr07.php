<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $venue = \App\Models\Venue::first();
    if (!$venue) {
        echo "Error: Venue belum disetup!\n";
        exit(1);
    }

    $cat = \App\Models\SeatCategory::where('name', 'DIAMOND')->first();
    if (!$cat) {
        echo "Error: Kategori DIAMOND belum disetup!\n";
        exit(1);
    }

    $seat = \App\Models\SeatMaster::firstOrCreate(
        [
            'venue_id' => $venue->id, 
            'seat_category_id' => $cat->id, 
            'seat_code' => 'C-R07'
        ],
        [
            'row_num' => 15, 
            'col_num' => 7, 
            'is_active' => true
        ]
    );

    $session = \App\Models\EventSession::first();
    if ($session) {
        \App\Models\SeatAvailability::firstOrCreate(
            [
                'event_session_id' => $session->id, 
                'seat_master_id' => $seat->id
            ],
            [
                'status' => 'available'
            ]
        );
    }

    echo "=============================================\n";
    echo "✅ SUKSES! Kursi C-R07 Berhasil Ditambahkan!\n";
    echo "Total Kapasitas Kursi Sekarang: " . \App\Models\SeatMaster::count() . "\n";
    echo "=============================================\n";

} catch (\Exception $e) {
    echo "Gagal: " . $e->getMessage() . "\n";
}
