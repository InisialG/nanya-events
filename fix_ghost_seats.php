<?php

use App\Models\SeatAvailability;
use App\Models\SeatMaster;

$seatMasters = SeatMaster::whereIn('seat_code', ['C-F20', 'C-F21'])->pluck('id');

$updated = SeatAvailability::whereIn('seat_master_id', $seatMasters)->update([
    'status' => 'available',
    'order_id' => null,
    'user_id' => null,
    'locked_until' => null
]);

echo "Berhasil membuka paksa $updated kursi ghost (C-F20 dan C-F21).\n";
