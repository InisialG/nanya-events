<?php

$seat = \App\Models\SeatMaster::firstOrCreate(
    ['seat_code' => 'C-T26'],
    [
        'venue_id' => 1,
        'seat_category_id' => 1, // Kategori Diamond
        'row_num' => 17,
        'col_num' => 26,
        'is_active' => true,
    ]
);

$sessions = \App\Models\EventSession::all();
foreach ($sessions as $session) {
    \App\Models\SeatAvailability::firstOrCreate([
        'event_session_id' => $session->id,
        'seat_master_id' => $seat->id,
    ], ['status' => 'available']);
}

echo "Berhasil! Kursi C-T26 telah ditambahkan ke SeatMaster dan SeatAvailability.\n";
