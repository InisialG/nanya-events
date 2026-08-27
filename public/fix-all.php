<?php
/**
 * ALL-IN-ONE FIXER v3 untuk Nanya Events
 * 
 * MASALAH: Seluruh filesystem project READ-ONLY untuk PHP.
 * STRATEGI: Cari lokasi writable (/tmp, /dev/shm, dll),
 *           lalu redirect compiled views & cache ke sana.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>Nanya Events - Fix v3</title>";
echo "<style>body{font-family:monospace;background:#1a1a2e;color:#e0e0e0;padding:20px;max-width:900px;margin:0 auto;} ";
echo ".ok{color:#00ff88;font-weight:bold;} .err{color:#ff4444;font-weight:bold;} .warn{color:#ffaa00;font-weight:bold;} ";
echo "h2{color:#00aaff;border-bottom:1px solid #333;padding-bottom:8px;} ";
echo "pre{background:#0d0d1a;padding:12px;border-radius:6px;overflow-x:auto;} ";
echo "code{background:#0d0d1a;padding:2px 6px;border-radius:3px;color:#ffaa00;} ";
echo ".box{background:#0d0d1a;border:1px solid #333;border-radius:8px;padding:16px;margin:12px 0;} ";
echo "a{color:#00aaff;}</style></head><body>";
echo "<h1>🛠️ Nanya Events — Fix v3 (Find Writable Location)</h1>";

$baseDir = dirname(__DIR__);

// ============================================================
// STEP 1: Cari lokasi writable
// ============================================================
echo "<h2>1️⃣ Mencari Lokasi yang Dapat Ditulis (Writable)</h2>";

$testLocations = [
    '/tmp',
    '/var/tmp',
    '/dev/shm',
    sys_get_temp_dir(),
    '/home/nanya/tmp',
    '/home/nanya/.tmp',
    $baseDir . '/storage/framework/views',
    $baseDir . '/storage',
    $baseDir . '/bootstrap/cache',
];

// Hapus duplikat
$testLocations = array_unique($testLocations);

$writableDir = null;
$writeMethod = null; // 'php' or 'exec'

foreach ($testLocations as $dir) {
    if (!is_dir($dir)) {
        // Coba buat folder
        @mkdir($dir, 0755, true);
        @exec("mkdir -p " . escapeshellarg($dir) . " 2>&1");
    }
    
    if (!is_dir($dir)) {
        echo "<p>❌ <code>$dir</code> — folder tidak ada & gagal dibuat</p>";
        continue;
    }
    
    $testFile = $dir . '/.nanya_write_test_' . uniqid();
    
    // Method 1: PHP native file_put_contents
    $phpWritable = false;
    if (@file_put_contents($testFile, 'test_content_php')) {
        $phpWritable = true;
        @unlink($testFile);
    }
    
    // Method 2: exec shell redirection
    $execWritable = false;
    $testFile2 = $dir . '/.nanya_exec_test_' . uniqid();
    @exec("echo 'test_content_exec' > " . escapeshellarg($testFile2) . " 2>&1", $out, $code);
    if (file_exists($testFile2)) {
        $content = @file_get_contents($testFile2);
        if (!empty($content)) {
            $execWritable = true;
        }
        @unlink($testFile2);
        @exec("rm -f " . escapeshellarg($testFile2) . " 2>&1");
    }
    
    // Method 3: fopen + fwrite
    $fopenWritable = false;
    $testFile3 = $dir . '/.nanya_fopen_test_' . uniqid();
    $fh = @fopen($testFile3, 'w');
    if ($fh) {
        if (@fwrite($fh, 'test_content_fopen') !== false) {
            $fopenWritable = true;
        }
        @fclose($fh);
        @unlink($testFile3);
    }
    
    $status = [];
    if ($phpWritable) $status[] = "<span class='ok'>PHP ✓</span>";
    else $status[] = "<span class='err'>PHP ✗</span>";
    
    if ($execWritable) $status[] = "<span class='ok'>exec ✓</span>";
    else $status[] = "<span class='err'>exec ✗</span>";
    
    if ($fopenWritable) $status[] = "<span class='ok'>fopen ✓</span>";
    else $status[] = "<span class='err'>fopen ✗</span>";
    
    $anyWritable = $phpWritable || $execWritable || $fopenWritable;
    $icon = $anyWritable ? '✅' : '❌';
    
    echo "<p>$icon <code>$dir</code> — " . implode(' | ', $status) . "</p>";
    
    if ($anyWritable && $writableDir === null) {
        $writableDir = $dir;
        if ($phpWritable) $writeMethod = 'php';
        elseif ($fopenWritable) $writeMethod = 'fopen';
        else $writeMethod = 'exec';
    }
}

// ============================================================
// STEP 2: Hasil & Instruksi
// ============================================================
echo "<h2>2️⃣ Hasil Diagnosa</h2>";

if ($writableDir !== null) {
    echo "<div class='box'>";
    echo "<p class='ok'>🎉 DITEMUKAN LOKASI WRITABLE!</p>";
    echo "<p>Path: <code>$writableDir</code></p>";
    echo "<p>Method: <code>$writeMethod</code></p>";
    echo "</div>";
    
    // Buat subfolder untuk Laravel
    $viewsPath = $writableDir . '/nanya-views';
    $cachePath = $writableDir . '/nanya-cache';
    $sessionsPath = $writableDir . '/nanya-sessions';
    $logsPath = $writableDir . '/nanya-logs';
    
    foreach ([$viewsPath, $cachePath, $sessionsPath, $logsPath] as $d) {
        if (!is_dir($d)) {
            if ($writeMethod === 'php') {
                @mkdir($d, 0755, true);
            } else {
                @exec("mkdir -p " . escapeshellarg($d) . " 2>&1");
            }
        }
        $exists = is_dir($d) ? '✅' : '❌';
        $shortName = str_replace($writableDir . '/', '', $d);
        echo "<p>$exists Subfolder: <code>$shortName</code></p>";
    }
    
    // ============================================================
    // STEP 3: Update .env untuk menggunakan writable location
    // ============================================================
    echo "<h2>3️⃣ Update .env untuk Lokasi Writable</h2>";
    
    $envFile = $baseDir . '/.env';
    $envContent = file_get_contents($envFile);
    
    // Cek apakah VIEW_COMPILED_PATH sudah ada
    $newEnvLines = [
        'VIEW_COMPILED_PATH' => $viewsPath,
    ];
    
    $envChanged = false;
    foreach ($newEnvLines as $key => $val) {
        if (preg_match("/^{$key}=.*/m", $envContent)) {
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $envContent);
        } else {
            $envContent .= "\n{$key}={$val}\n";
        }
        $envChanged = true;
    }
    
    // Pastikan session=cookie, cache=array, log=errorlog
    $ensureValues = [
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'false',
        'SESSION_DRIVER' => 'cookie',
        'CACHE_STORE' => 'array',
        'LOG_CHANNEL' => 'errorlog',
    ];
    
    foreach ($ensureValues as $key => $val) {
        if (preg_match("/^{$key}=(.*)$/m", $envContent, $m)) {
            if (trim($m[1]) !== $val) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $envContent);
                $envChanged = true;
            }
        }
    }
    
    if ($envChanged) {
        // Coba tulis via method yang berhasil
        $writeOk = false;
        if ($writeMethod === 'php') {
            $writeOk = @file_put_contents($envFile, $envContent) !== false;
        } elseif ($writeMethod === 'fopen') {
            $fh = @fopen($envFile, 'w');
            if ($fh) {
                $writeOk = @fwrite($fh, $envContent) !== false;
                @fclose($fh);
            }
        }
        
        if (!$writeOk) {
            // Coba via exec
            $tmpEnv = $writableDir . '/.env_tmp_' . uniqid();
            @exec("echo " . escapeshellarg($envContent) . " > " . escapeshellarg($tmpEnv) . " && cp " . escapeshellarg($tmpEnv) . " " . escapeshellarg($envFile) . " && rm -f " . escapeshellarg($tmpEnv) . " 2>&1", $out, $code);
            $writeOk = ($code === 0);
        }
        
        if ($writeOk) {
            echo "<p class='ok'>✅ .env berhasil diperbarui otomatis!</p>";
        } else {
            echo "<p class='err'>❌ Tidak bisa menulis .env otomatis. Edit MANUAL via File Manager:</p>";
            echo "<div class='box'>";
            echo "<p>Buka <code>/home/nanya/nanya-events/.env</code> di File Manager, tambahkan/ubah baris berikut:</p>";
            echo "<pre>";
            echo "VIEW_COMPILED_PATH={$viewsPath}\n";
            echo "SESSION_DRIVER=cookie\n";
            echo "CACHE_STORE=array\n";
            echo "LOG_CHANNEL=errorlog\n";
            echo "APP_DEBUG=false\n";
            echo "APP_ENV=production\n";
            echo "</pre>";
            echo "</div>";
        }
    }
    
    // ============================================================
    // STEP 4: Pre-compile views ke lokasi writable
    // ============================================================
    echo "<h2>4️⃣ Pre-compile Views ke Lokasi Writable</h2>";
    
    // Cek apakah .env sudah memiliki VIEW_COMPILED_PATH
    clearstatcache();
    $currentEnv = file_get_contents($envFile);
    
    if (strpos($currentEnv, "VIEW_COMPILED_PATH={$viewsPath}") !== false) {
        echo "<p class='ok'>✅ .env sudah memiliki VIEW_COMPILED_PATH={$viewsPath}</p>";
        
        // Coba compile views via artisan
        $output = [];
        $code = 0;
        @exec("cd " . escapeshellarg($baseDir) . " && php artisan view:cache 2>&1", $output, $code);
        $outputStr = implode("\n", $output);
        
        if ($code === 0 && strpos($outputStr, 'ERROR') === false && strpos($outputStr, 'ValueError') === false) {
            echo "<p class='ok'>✅ php artisan view:cache BERHASIL!</p>";
            echo "<pre>" . htmlspecialchars($outputStr) . "</pre>";
        } else {
            echo "<p class='warn'>⚠️ php artisan view:cache gagal. Output:</p>";
            echo "<pre>" . htmlspecialchars(substr($outputStr, 0, 500)) . "</pre>";
            echo "<p>Mencoba compile manual via Blade compiler...</p>";
            
            // Manual compilation
            manualCompileViews($baseDir, $viewsPath, $writeMethod);
        }
        
        // Count compiled views
        $compiledCount = count(glob($viewsPath . '/*.php'));
        echo "<p class='ok'>📁 Jumlah compiled views: <strong>$compiledCount files</strong></p>";
        
    } else {
        echo "<p class='err'>❌ VIEW_COMPILED_PATH belum ada di .env. ";
        echo "Tambahkan via File Manager terlebih dahulu, lalu akses fix-all.php lagi.</p>";
    }
    
    // ============================================================
    // STEP 5: Test final
    // ============================================================
    echo "<h2>5️⃣ Test Akhir</h2>";
    
    // Verify env
    $finalEnv = file_get_contents($envFile);
    $checks = [
        ['VIEW_COMPILED_PATH', $viewsPath],
        ['SESSION_DRIVER', 'cookie'],
        ['CACHE_STORE', 'array'],
        ['LOG_CHANNEL', 'errorlog'],
        ['APP_DEBUG', 'false'],
    ];
    
    echo "<table border='1' cellpadding='6' cellspacing='0' style='border-color:#333;width:100%;'>";
    echo "<tr><th>Setting</th><th>Expected</th><th>Status</th></tr>";
    
    foreach ($checks as [$key, $expected]) {
        if (preg_match("/^{$key}=(.*)$/m", $finalEnv, $m)) {
            $actual = trim($m[1]);
            $ok = ($actual === $expected);
            echo "<tr><td>{$key}</td><td><code>{$expected}</code></td>";
            echo "<td class='" . ($ok ? 'ok' : 'err') . "'>" . ($ok ? '✅ OK' : "❌ Actual: {$actual}") . "</td></tr>";
        } else {
            echo "<tr><td>{$key}</td><td><code>{$expected}</code></td>";
            echo "<td class='err'>❌ Tidak ada di .env</td></tr>";
        }
    }
    
    // Count compiled views
    $compiledCount = is_dir($viewsPath) ? count(glob($viewsPath . '/*.php')) : 0;
    echo "<tr><td>Compiled views</td><td>≥ 1 file</td>";
    echo "<td class='" . ($compiledCount > 0 ? 'ok' : 'err') . "'>" . ($compiledCount > 0 ? "✅ {$compiledCount} files" : '❌ 0 files') . "</td></tr>";
    
    // Filesystem.php patch
    $fsContent = file_get_contents($baseDir . '/vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php');
    $patched = strpos($fsContent, '@tempnam') !== false;
    echo "<tr><td>Filesystem.php @tempnam</td><td>Patched</td>";
    echo "<td class='" . ($patched ? 'ok' : 'err') . "'>" . ($patched ? '✅ Patched' : '❌ Not patched') . "</td></tr>";
    
    echo "</table>";
    
    echo "<br>";
    if ($compiledCount > 0 && $patched) {
        echo "<p style='font-size:20px;' class='ok'>🎉 SEHARUSNYA SUDAH SIAP! Coba buka → <a href='/'>https://events.nanyang.sch.id/</a></p>";
    }
    
} else {
    // TIDAK ADA LOKASI WRITABLE SAMA SEKALI
    echo "<div class='box' style='border-color:#ff4444;'>";
    echo "<p class='err'>🚨 TIDAK ADA LOKASI WRITABLE DITEMUKAN!</p>";
    echo "<p>Seluruh filesystem di server ini <strong>100% READ-ONLY</strong> untuk PHP.</p>";
    echo "<p>Ini adalah masalah <strong>di level server/hosting</strong> yang tidak bisa diperbaiki dari sisi kode aplikasi.</p>";
    echo "</div>";
    
    echo "<h2>🆘 Yang Harus Anda Lakukan</h2>";
    echo "<div class='box'>";
    echo "<p><strong>Hubungi admin hosting / support CWP Nanyang</strong> dan minta mereka untuk:</p>";
    echo "<ol>";
    echo "<li><strong>Remount filesystem sebagai read-write</strong>:<br>";
    echo "<code>mount -o remount,rw /home</code><br>";
    echo "(Filesystem mungkin di-mount read-only karena disk error atau konfigurasi keamanan)</li>";
    echo "<li><strong>Atau berikan akses write</strong> pada folder-folder berikut:<br>";
    echo "<code>/home/nanya/nanya-events/storage/</code><br>";
    echo "<code>/home/nanya/nanya-events/bootstrap/cache/</code><br>";
    echo "<code>/home/nanya/tmp/</code></li>";
    echo "<li><strong>Atau aktifkan CageFS virtmp</strong> (jika menggunakan CloudLinux):<br>";
    echo "<code>cagefsctl --set-min-uid 500</code><br>";
    echo "<code>cagefsctl --remount-all</code></li>";
    echo "<li><strong>Periksa apakah disk penuh atau ada error</strong>:<br>";
    echo "<code>df -h</code> dan <code>dmesg | grep -i 'read-only\\|error\\|ext4'</code></li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<h2>📧 Template Pesan untuk Admin Hosting</h2>";
    echo "<div class='box' style='border-color:#00aaff;'>";
    echo "<pre style='white-space:pre-wrap;'>";
    echo "Yth. Admin Hosting,\n\n";
    echo "Saya mengalami masalah pada akun hosting user 'nanya' (domain events.nanyang.sch.id).\n\n";
    echo "PHP tidak dapat menulis file ke manapun di server, termasuk:\n";
    echo "- /home/nanya/nanya-events/storage/\n";
    echo "- /home/nanya/nanya-events/bootstrap/cache/\n";
    echo "- /home/nanya/tmp/\n";
    echo "- /tmp/\n\n";
    echo "Error yang muncul: \"Read-only file system\"\n\n";
    echo "Kemungkinan penyebab:\n";
    echo "1. Filesystem /home di-mount sebagai read-only\n";
    echo "2. Disk error menyebabkan auto-remount read-only\n";
    echo "3. Konfigurasi keamanan yang terlalu ketat\n\n";
    echo "Mohon bantuan untuk:\n";
    echo "- Remount filesystem sebagai read-write: mount -o remount,rw /home\n";
    echo "- Pastikan user 'nanya' memiliki akses tulis ke home directory-nya\n";
    echo "- Pastikan /tmp writable untuk PHP-FPM\n\n";
    echo "Terima kasih.\n";
    echo "</pre>";
    echo "</div>";
}

echo "<h2>📋 Info Sistem</h2>";
echo "<pre>";
echo "PHP:          " . phpversion() . "\n";
echo "SAPI:         " . php_sapi_name() . "\n";
echo "User:         " . get_current_user() . "\n";
echo "Project:      " . $baseDir . "\n";
echo "sys_temp_dir: " . sys_get_temp_dir() . "\n";
echo "Disk Free:    " . round(@disk_free_space('/') / 1024 / 1024, 2) . " MB\n";

// Check mount info
echo "\n--- Mount Info ---\n";
@exec("mount 2>&1 | grep -E '/home|/tmp'", $mountInfo);
echo implode("\n", $mountInfo) . "\n";

// Check df
echo "\n--- Disk Usage ---\n";
@exec("df -h /home/nanya 2>&1", $dfInfo);
echo implode("\n", $dfInfo) . "\n";

// Check dmesg for read-only errors  
echo "\n--- Recent Kernel Messages (disk errors) ---\n";
@exec("dmesg 2>&1 | tail -5", $dmesgInfo);
echo implode("\n", $dmesgInfo) . "\n";

echo "</pre>";

echo "<p class='err' style='margin-top:20px;'><strong>⚠️ HAPUS FILE fix-all.php INI SETELAH WEBSITE BERHASIL!</strong></p>";
echo "</body></html>";

// ============================================================
// Helper: Manual Blade Compilation
// ============================================================
function manualCompileViews($baseDir, $viewsPath, $writeMethod) {
    $viewsDir = $baseDir . '/resources/views';
    if (!is_dir($viewsDir)) {
        echo "<p class='err'>❌ Folder resources/views tidak ditemukan</p>";
        return;
    }
    
    // Find all blade files
    $bladeFiles = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
    foreach ($iterator as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
            $bladeFiles[] = $file->getPathname();
        }
    }
    
    echo "<p>📄 Ditemukan " . count($bladeFiles) . " Blade template files</p>";
    
    // Try to bootstrap Laravel and compile
    try {
        $app = require $baseDir . '/bootstrap/app.php';
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        $compiler = $app->make('blade.compiler');
        $compiled = 0;
        $errors = 0;
        
        foreach ($bladeFiles as $bf) {
            try {
                $compiledPath = $viewsPath . '/' . hash('xxh128', $bf) . '.php';
                $content = $compiler->compileString(file_get_contents($bf));
                
                if ($writeMethod === 'php') {
                    @file_put_contents($compiledPath, $content);
                } elseif ($writeMethod === 'fopen') {
                    $fh = @fopen($compiledPath, 'w');
                    if ($fh) { @fwrite($fh, $content); @fclose($fh); }
                } else {
                    $tmpFile = $viewsPath . '/.tmp_' . uniqid();
                    @exec("cat > " . escapeshellarg($compiledPath) . " << 'BLADE_EOF'\n" . $content . "\nBLADE_EOF", $o, $c);
                }
                
                if (file_exists($compiledPath)) {
                    $compiled++;
                } else {
                    $errors++;
                }
            } catch (\Throwable $e) {
                $errors++;
            }
        }
        
        echo "<p class='ok'>✅ Manual compile: $compiled berhasil, $errors gagal</p>";
    } catch (\Throwable $e) {
        echo "<p class='err'>❌ Gagal bootstrap Laravel: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
