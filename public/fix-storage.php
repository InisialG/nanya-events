<?php

/**
 * Helper script to automatically create missing storage directories
 * and set proper permissions on Shared Hosting.
 */

$baseDir = dirname(__DIR__);

$directories = [
    $baseDir . '/storage',
    $baseDir . '/storage/app',
    $baseDir . '/storage/app/public',
    $baseDir . '/storage/framework',
    $baseDir . '/storage/framework/views',
    $baseDir . '/storage/framework/cache',
    $baseDir . '/storage/framework/cache/data',
    $baseDir . '/storage/framework/sessions',
    $baseDir . '/storage/logs',
    $baseDir . '/bootstrap/cache',
];

$results = [];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        $created = @mkdir($dir, 0775, true);
        $results[] = ($created ? "✅ Dibuat: " : "❌ Gagal Buat: ") . $dir;
    } else {
        $results[] = "ℹ️ Sudah Ada: " . $dir;
    }
    @chmod($dir, 0775);
}

// Clear compiled views if any
$viewsDir = $baseDir . '/storage/framework/views';
if (is_dir($viewsDir)) {
    $files = glob($viewsDir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            @unlink($file);
        }
    }
    $results[] = "🧹 Cache Tampilan Blade Berhasil Dibersihkan!";
}

echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><title>Perbaikan Folder Storage Nanya Events</title>";
echo "<style>body{font-family:sans-serif;padding:30px;background:#f8fafc;color:#1e293b}h2{color:#f37032}ul{background:#fff;padding:20px 40px;border-radius:12px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)}li{margin-bottom:8px;font-family:monospace;font-size:14px}.btn{display:inline-block;padding:12px 24px;background:#f37032;color:#fff;text-decoration:none;font-weight:bold;border-radius:8px;margin-top:15px}</style></head><body>";
echo "<h2>🔧 Perbaikan Folder Storage & Blade Views Nanya Events</h2>";
echo "<p>Script ini otomatis membuat folder <code>storage/framework/views</code> yang hilang di shared hosting Anda.</p>";
echo "<ul>";
foreach ($results as $res) {
    echo "<li>" . htmlspecialchars($res) . "</li>";
}
echo "</ul>";
echo "<p><strong>Langkah Selanjutnya:</strong> Silakan buka kembali halaman utama website Anda!</p>";
echo "<a href='/' class='btn'>Buka Website Nanya Events ➔</a>";
echo "</body></html>";
