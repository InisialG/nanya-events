<?php

use App\Models\SeatAvailability;

echo "Memulai pembersihan kursi GHOST...\n";

// Hanya bersihkan kursi yang berstatus locked tapi BENAR-BENAR tidak punya user_id dan order_id (Ghost murni)
$updatedUnknown = SeatAvailability::where('status', 'locked')
    ->whereNull('user_id')
    ->whereNull('order_id')
    ->update([
        'status' => 'available',
        'locked_until' => null
    ]);

echo "- $updatedUnknown kursi ghost (tanpa identitas) berhasil dibersihkan.\n";
echo "\nSelesai!\n";
