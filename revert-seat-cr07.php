<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Revert C-R07
    $seat = \App\Models\SeatMaster::where('seat_code', 'C-R07')->first();
    if ($seat) {
        \App\Models\SeatAvailability::where('seat_master_id', $seat->id)->delete();
        $seat->delete();
    }

    echo "=============================================\n";
    echo "✅ SUKSES! Kursi C-R07 telah dihapus kembali.\n";
    echo "Total Kapasitas Kursi Kembali Normal: " . \App\Models\SeatMaster::count() . "\n";
    echo "=============================================\n";

} catch (\Exception $e) {
    echo "Gagal: " . $e->getMessage() . "\n";
}
