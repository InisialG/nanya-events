<?php

use App\Models\SeatAvailability;
use Carbon\Carbon;

echo "Memulai pembersihan paksa kursi GHOST secara menyeluruh...\n";

// 1. Bersihkan kursi yang spesifik C-F20 dan C-F21
$seatMasters = \App\Models\SeatMaster::whereIn('seat_code', ['C-F20', 'C-F21'])->pluck('id');
$updatedSpecific = SeatAvailability::whereIn('seat_master_id', $seatMasters)
    ->where('status', 'locked')
    ->update([
        'status' => 'available',
        'order_id' => null,
        'user_id' => null,
        'locked_until' => null
    ]);
echo "- $updatedSpecific kursi spesifik (C-F20/C-F21) berhasil dibuka paksa.\n";

// 2. Bersihkan semua kursi yang berstatus locked tapi tidak punya user_id dan order_id (Sisa bug lama)
$updatedUnknown = SeatAvailability::where('status', 'locked')
    ->whereNull('user_id')
    ->whereNull('order_id')
    ->update([
        'status' => 'available',
        'order_id' => null,
        'user_id' => null,
        'locked_until' => null
    ]);
echo "- $updatedUnknown kursi ghost (tanpa identitas) berhasil dibersihkan.\n";

// 3. Bersihkan kursi yang sudah melewati batas waktu (expired lock)
$updatedExpired = SeatAvailability::where('status', 'locked')
    ->where('locked_until', '<', Carbon::now())
    ->update([
        'status' => 'available',
        'order_id' => null,
        'user_id' => null,
        'locked_until' => null
    ]);
echo "- $updatedExpired kursi expired (kadaluarsa) berhasil dilepas.\n";

echo "\nSelesai! Seluruh kursi ghost telah diatasi. Silakan refresh dasbor Admin Anda.\n";
