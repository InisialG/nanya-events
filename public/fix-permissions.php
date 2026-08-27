<?php

/**
 * Script Otomatis Perbaikan Permission & Clear Cache untuk Nanya Events
 */

$target_dir = '/home/nanya/nanya-events';

if (!file_exists($target_dir)) {
    $target_dir = dirname(__DIR__);
}

if (!file_exists($target_dir)) {
    die("❌ Folder $target_dir tidak ditemukan.");
}

// 0. AUTO-CREATE public/.user.ini (KRITIS untuk upload file & POST data)
$userIniPath = $target_dir . '/public/.user.ini';
$userIniContent = "; === PHP-FPM Config untuk Nanya Events ===
; File ini HARUS di folder public/ (document root)

; Temporary Directory - KRITIS untuk upload file
upload_tmp_dir = /tmp
sys_temp_dir = /tmp

; Upload & POST Limits
post_max_size = 20M
upload_max_filesize = 20M
max_file_uploads = 10

; Session
session.save_path = /tmp
";

@file_put_contents($userIniPath, $userIniContent);
@chmod($userIniPath, 0644);

// 1. HAPUS SEMUA CACHE BOOTSTRAP LAMA YANG TERKUNCI (config.php, routes.php, dll)
$bootstrapCacheDir = $target_dir . '/bootstrap/cache';
if (is_dir($bootstrapCacheDir)) {
    $files = glob($bootstrapCacheDir . '/*.php');
    foreach ($files as $file) {
        if (is_file($file)) {
            @unlink($file);
        }
    }
}

// 2. HAPUS SEMUA CACHE VIEWS LAMA (storage/framework/views)
$viewsDir = $target_dir . '/storage/framework/views';
if (is_dir($viewsDir)) {
    $files = glob($viewsDir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            @unlink($file);
        }
    }
}

// 3. ATUR PERMISSION FOLDER STORAGE & CACHE MENJADI 0777
$essentialDirs = [
    $target_dir . '/storage/tmp',
    $target_dir . '/storage/tmp/nanya-views',
    $target_dir . '/storage',
    $target_dir . '/storage/app',
    $target_dir . '/storage/app/public',
    $target_dir . '/storage/app/public/payment-proofs',
    $target_dir . '/storage/app/public/event-posters',
    $target_dir . '/storage/framework',
    $target_dir . '/storage/framework/views',
    $target_dir . '/storage/framework/cache',
    $target_dir . '/storage/framework/cache/data',
    $target_dir . '/storage/framework/sessions',
    $target_dir . '/storage/logs',
    $target_dir . '/bootstrap/cache',
];

foreach ($essentialDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    @chmod($dir, 0777);
}

// 4. ATUR PERMISSION SELURUH FILE LAINNYA REKURSIF (Folder 755, File 644)
try {
    @chmod($target_dir, 0755);

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($target_dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    $countFolders = 0;
    $countFiles = 0;

    foreach ($iterator as $item) {
        if ($item->isDir()) {
            @chmod($item->getRealPath(), 0755);
            $countFolders++;
        } else {
            @chmod($item->getRealPath(), 0644);
            $countFiles++;
        }
    }

    // Terapkan ulang 0777 khusus folder storage
    foreach ($essentialDirs as $dir) {
        @chmod($dir, 0777);
    }

    echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><title>Sukses Reset Cache & Permission Nanya Events</title>";
    echo "<style>body{font-family:sans-serif;padding:30px;background:#f8fafc;color:#1e293b}h2{color:#10b981}ul{background:#fff;padding:20px 40px;border-radius:12px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)}li{margin-bottom:8px;font-family:monospace;font-size:14px}.btn{display:inline-block;padding:12px 24px;background:#f37032;color:#fff;text-decoration:none;font-weight:bold;border-radius:8px;margin-top:15px}</style></head><body>";
    echo "<h2>✅ Reset Cache Config & Perbaikan Storage Berhasil!</h2>";
    echo "<p>Seluruh cache `bootstrap/cache/config.php` lama dan cache views telah <b>DIBERSIHKAN TOTAL</b>.</p>";
    echo "<p>Permission storage telah diset ke <code>777</code> agar Laravel dapat menulis tanpa hambatan.</p>";
    echo "<a href='/' class='btn'>Buka Website Nanya Events Sekarang ➔</a>";
    echo "</body></html>";

} catch (Exception $e) {
    echo "<h2 style='color: #ef4444;'>❌ Gagal:</h2> " . $e->getMessage();
}
