<?php

use App\Models\SeatAvailability;

// Mencari kursi yang berstatus 'locked' tetapi tidak memiliki user_id dan order_id
$unknownLockedSeats = SeatAvailability::where('status', 'locked')
    ->whereNull('user_id')
    ->whereNull('order_id')
    ->get();

$count = $unknownLockedSeats->count();

if ($count > 0) {
    // Kembalikan ke status available (Tersedia)
    SeatAvailability::where('status', 'locked')
        ->whereNull('user_id')
        ->whereNull('order_id')
        ->update([
            'status' => 'available',
            'locked_until' => null,
        ]);
    
    echo "✅ Berhasil mereset $count kursi yang terkunci tanpa identitas (Unknown).\n";
} else {
    echo "✅ Aman! Tidak ada kursi terkunci tanpa identitas (Unknown) yang ditemukan.\n";
}
