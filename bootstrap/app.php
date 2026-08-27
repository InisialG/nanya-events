<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ============================================================
// SHARED HOSTING FIX: Hapus config cache agar tidak stale
// Ini mencegah error "Class view does not exist"
// ============================================================
$bootstrapCache = dirname(__DIR__) . '/bootstrap/cache';
foreach (['config.php', 'routes-v7.php', 'services.php'] as $cacheFile) {
    $cacheFilePath = $bootstrapCache . '/' . $cacheFile;
    if (file_exists($cacheFilePath)) {
        @unlink($cacheFilePath);
    }
}

// Pastikan folder compiled views di /tmp ada SEBELUM Laravel boot
$compiledViewDir = '/tmp/nanya-compiled-views';
if (!is_dir($compiledViewDir)) {
    @mkdir($compiledViewDir, 0777, true);
}
@chmod($compiledViewDir, 0777);

// Paksa PHP & Laravel menggunakan folder storage/tmp internal project sebagai TEMPORARY DIRECTORY
$projectTmpDir = dirname(__DIR__) . '/storage/tmp';
if (!is_dir($projectTmpDir)) {
    @mkdir($projectTmpDir, 0777, true);
}
@chmod($projectTmpDir, 0777);

putenv("TMPDIR=/tmp");
putenv("TMP=/tmp");
putenv("TEMP=/tmp");
ini_set('upload_tmp_dir', '/tmp');
ini_set('sys_temp_dir', '/tmp');

// Auto-create essential Laravel storage directories if missing on shared hosting
$autoStoragePaths = [
    $projectTmpDir,
    dirname(__DIR__) . '/storage/framework/views',
    dirname(__DIR__) . '/storage/framework/cache/data',
    dirname(__DIR__) . '/storage/framework/sessions',
    dirname(__DIR__) . '/storage/logs',
    $bootstrapCache,
];

foreach ($autoStoragePaths as $path) {
    if (!is_dir($path)) {
        @mkdir($path, 0777, true);
    }
    @chmod($path, 0777);
}

// Suppress tempnam() warnings on shared hosting (open_basedir restriction)
// Using a narrowly-scoped handler that restores itself after Laravel boots
$previousHandler = set_error_handler(function ($errno, $errstr) use (&$previousHandler) {
    if (strpos($errstr, 'tempnam()') !== false) {
        return true; // Suppress only tempnam warnings
    }
    // Delegate all other errors to PHP's default handler (don't block logging)
    if ($previousHandler) {
        return call_user_func($previousHandler, $errno, $errstr);
    }
    return false;
}, E_WARNING);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'checkout/upload-proof/*',
            'checkout/save-proof-url/*',
            'api/checkout-simulation',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
