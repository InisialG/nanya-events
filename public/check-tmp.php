<?php
/**
 * Diagnostic + Auto-Fix: Buat .user.ini dan cek temp directory
 * Akses via browser: https://events.nanyang.sch.id/check-tmp.php
 */

// ============================================================
// AUTO-FIX: Buat .user.ini di folder ini (public/) jika belum ada
// ============================================================
$iniPath = __DIR__ . '/.user.ini';
$iniContent = "; === PHP-FPM Config untuk Nanya Events ===
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

$iniCreated = false;
if (!file_exists($iniPath)) {
    $iniCreated = @file_put_contents($iniPath, $iniContent);
    @chmod($iniPath, 0644);
}

echo "<html><head><title>PHP Temp Dir Check</title>";
echo "<style>body{font-family:sans-serif;padding:30px;background:#0f172a;color:#e2e8f0}
.ok{color:#22c55e;font-weight:bold}.err{color:#ef4444;font-weight:bold}
.box{background:#1e293b;padding:20px;border-radius:12px;margin:10px 0;border:1px solid #334155}
h1{color:#f97316}code{background:#334155;padding:2px 8px;border-radius:4px;font-size:14px}
</style></head><body>";

echo "<h1>🔍 PHP Temp Directory Diagnostic</h1>";

// 1. Check upload_tmp_dir
$uploadTmp = ini_get('upload_tmp_dir');
echo "<div class='box'>";
echo "<strong>upload_tmp_dir:</strong> <code>" . ($uploadTmp ?: '(empty - using system default)') . "</code><br>";
if ($uploadTmp && is_dir($uploadTmp) && is_writable($uploadTmp)) {
    echo "<span class='ok'>✅ Directory exists and is writable</span>";
} elseif ($uploadTmp && is_dir($uploadTmp)) {
    echo "<span class='err'>❌ Directory exists but NOT writable</span>";
} elseif ($uploadTmp) {
    echo "<span class='err'>❌ Directory does NOT exist</span>";
} else {
    echo "<span class='err'>⚠️ Not set - PHP uses system default which may be read-only</span>";
}
echo "</div>";

// 2. Check sys_temp_dir
$sysTmp = ini_get('sys_temp_dir');
echo "<div class='box'>";
echo "<strong>sys_temp_dir:</strong> <code>" . ($sysTmp ?: '(empty)') . "</code><br>";
if ($sysTmp && is_dir($sysTmp) && is_writable($sysTmp)) {
    echo "<span class='ok'>✅ Directory exists and is writable</span>";
} elseif ($sysTmp) {
    echo "<span class='err'>❌ Problem with directory</span>";
}
echo "</div>";

// 3. Check sys_get_temp_dir()
$phpTmp = sys_get_temp_dir();
echo "<div class='box'>";
echo "<strong>sys_get_temp_dir():</strong> <code>{$phpTmp}</code><br>";
if (is_dir($phpTmp) && is_writable($phpTmp)) {
    echo "<span class='ok'>✅ Writable</span>";
} else {
    echo "<span class='err'>❌ NOT writable - THIS IS THE ROOT CAUSE</span>";
}
echo "</div>";

// 4. Check /tmp directly
echo "<div class='box'>";
echo "<strong>/tmp:</strong><br>";
if (is_dir('/tmp') && is_writable('/tmp')) {
    echo "<span class='ok'>✅ /tmp is writable</span>";
} else {
    echo "<span class='err'>❌ /tmp is NOT writable</span>";
}
echo "</div>";

// 5. Test actual tempnam()
echo "<div class='box'>";
echo "<strong>tempnam() test:</strong><br>";
$testTmp = @tempnam('/tmp', 'nanya_test_');
if ($testTmp) {
    echo "<span class='ok'>✅ tempnam() works: {$testTmp}</span>";
    @unlink($testTmp);
} else {
    echo "<span class='err'>❌ tempnam() FAILED</span>";
}
echo "</div>";

// 6. Test actual file_put_contents
echo "<div class='box'>";
echo "<strong>file_put_contents() test:</strong><br>";
$testFile = '/tmp/nanya_write_test_' . time() . '.txt';
$result = @file_put_contents($testFile, 'test');
if ($result !== false) {
    echo "<span class='ok'>✅ Can write to /tmp: {$testFile}</span>";
    @unlink($testFile);
} else {
    echo "<span class='err'>❌ Cannot write to /tmp</span>";
}
echo "</div>";

// 7. Check compiled views dir
$compiledDir = '/tmp/nanya-compiled-views';
echo "<div class='box'>";
echo "<strong>Compiled views dir ({$compiledDir}):</strong><br>";
if (!is_dir($compiledDir)) {
    @mkdir($compiledDir, 0777, true);
}
if (is_dir($compiledDir) && is_writable($compiledDir)) {
    echo "<span class='ok'>✅ Exists and writable</span>";
} else {
    echo "<span class='err'>❌ Problem</span>";
}
echo "</div>";

// 8. Check if .user.ini is in public/
echo "<div class='box'>";
echo "<strong>.user.ini location check:</strong><br>";
$publicIni = __DIR__ . '/.user.ini';
$rootIni = dirname(__DIR__) . '/.user.ini';
echo "public/.user.ini: " . (file_exists($publicIni) ? "<span class='ok'>✅ EXISTS</span>" : "<span class='err'>❌ MISSING</span>") . "<br>";
echo "root/.user.ini: " . (file_exists($rootIni) ? "<span class='ok'>✅ EXISTS</span>" : "<span class='err'>❌ MISSING</span>");
echo "</div>";

// 9. Session save path
echo "<div class='box'>";
echo "<strong>session.save_path:</strong> <code>" . ini_get('session.save_path') . "</code>";
echo "</div>";

echo "<br><p style='color:#94a3b8;font-size:12px'>⏰ Generated: " . date('Y-m-d H:i:s') . " | PHP " . PHP_VERSION . "</p>";

// Show auto-fix status
if ($iniCreated) {
    echo "<div class='box' style='border-color:#22c55e'>";
    echo "<span class='ok'>🔧 AUTO-FIX: .user.ini berhasil dibuat di public/!</span><br>";
    echo "<span style='color:#94a3b8;font-size:13px'>PHP-FPM akan membaca file ini dalam ~5 menit. Refresh halaman ini setelah 5 menit untuk verifikasi.</span>";
    echo "</div>";
} elseif (file_exists($iniPath)) {
    echo "<div class='box' style='border-color:#22c55e'>";
    echo "<span class='ok'>✅ .user.ini sudah ada di public/</span><br>";
    $uploadTmp = ini_get('upload_tmp_dir');
    if (empty($uploadTmp)) {
        echo "<span style='color:#f59e0b'>⏳ PHP-FPM belum membaca .user.ini. Tunggu ~5 menit lalu refresh.</span>";
    } else {
        echo "<span class='ok'>✅ PHP-FPM sudah membaca .user.ini!</span>";
    }
    echo "</div>";
}

echo "</body></html>";

