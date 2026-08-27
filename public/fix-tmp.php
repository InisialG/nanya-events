<?php

/**
 * Diagnostic File System & TMP Directory
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$appDir = dirname(__DIR__);
if (file_exists('/home/nanya/nanya-events')) {
    $appDir = '/home/nanya/nanya-events';
}

$storageDir = $appDir . '/storage';
$tmpDir = $storageDir . '/tmp';
$publicDir = __DIR__;

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Diagnostic File System</title>";
echo "<style>body{font-family:sans-serif;padding:30px;background:#f8fafc;color:#1e293b;max-width:800px;margin:0 auto}";
echo ".ok{color:#10b981;font-weight:bold}.err{color:#ef4444;font-weight:bold}.warn{color:#f59e0b;font-weight:bold}";
echo "code{background:#e2e8f0;padding:2px 6px;border-radius:4px;font-size:13px}";
echo ".card{background:#fff;padding:20px;border-radius:12px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);margin:15px 0}";
echo "</style></head><body>";

echo "<h2>🔧 Diagnosa Akses Tulis File System</h2>";

// Test 1: Tulis ke folder public (tempat index.php berada)
echo "<div class='card'>";
echo "<h3>1. Test Tulis ke Folder Public (<code>$publicDir</code>)</h3>";
$testFilePublic = $publicDir . '/test_write.txt';
if (@file_put_contents($testFilePublic, 'test-write-ok') !== false) {
    @unlink($testFilePublic);
    echo "<p class='ok'>✅ Berhasil menulis ke folder public!</p>";
} else {
    $err = error_get_last();
    echo "<p class='err'>❌ Gagal menulis ke folder public: " . ($err['message'] ?? 'Unknown Error') . "</p>";
}
echo "</div>";

// Test 2: Tulis ke folder storage
echo "<div class='card'>";
echo "<h3>2. Test Tulis ke Folder Storage (<code>$storageDir</code>)</h3>";
$testFileStorage = $storageDir . '/test_write.txt';
if (@file_put_contents($testFileStorage, 'test-write-ok') !== false) {
    @unlink($testFileStorage);
    echo "<p class='ok'>✅ Berhasil menulis ke folder storage!</p>";
} else {
    $err = error_get_last();
    echo "<p class='err'>❌ Gagal menulis ke folder storage: " . ($err['message'] ?? 'Unknown Error') . "</p>";
}
echo "</div>";

// Test 3: Tulis ke folder storage/tmp
echo "<div class='card'>";
echo "<h3>3. Test Tulis ke Folder storage/tmp (<code>$tmpDir</code>)</h3>";
if (!is_dir($tmpDir)) {
    @mkdir($tmpDir, 0777, true);
}
$testFileTmp = $tmpDir . '/test_write.txt';
if (@file_put_contents($testFileTmp, 'test-write-ok') !== false) {
    @unlink($testFileTmp);
    echo "<p class='ok'>✅ Berhasil menulis ke folder storage/tmp!</p>";
} else {
    $err = error_get_last();
    echo "<p class='err'>❌ Gagal menulis ke folder storage/tmp: " . ($err['message'] ?? 'Unknown Error') . "</p>";
}
echo "</div>";

// Test 4: Cek Real Path & Mounting Status
echo "<div class='card'>";
echo "<h3>4. Cek Realpath</h3>";
echo "<ul>";
echo "<li>Document Root: <code>" . $_SERVER['DOCUMENT_ROOT'] . "</code></li>";
echo "<li>Realpath Public: <code>" . realpath($publicDir) . "</code></li>";
echo "<li>Realpath Storage: <code>" . realpath($storageDir) . "</code> (Jika kosong/false, folder tidak bisa diakses/jail chroot)</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
