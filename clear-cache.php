<?php
// Script untuk menghapus cache Laravel di Shared Hosting tanpa Terminal
require __DIR__.'/../nanya-app/vendor/autoload.php';
$app = require_once __DIR__.'/../nanya-app/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Eksekusi artisan commands
echo "<pre>";
echo "Membersihkan Cache...\n\n";

\Artisan::call('route:clear');
echo "Route Cache: " . \Artisan::output();

\Artisan::call('config:clear');
echo "Config Cache: " . \Artisan::output();

\Artisan::call('cache:clear');
echo "Application Cache: " . \Artisan::output();

echo "\nSemua cache berhasil dibersihkan! ✅\n";
echo "Silakan HAPUS file ini demi keamanan.";
echo "</pre>";
