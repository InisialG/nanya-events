<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class SafeFilesystem extends Filesystem
{
    /**
     * Write the contents of a file, replacing it atomically if possible.
     * Overridden to bypass tempnam() restrictions on CWP / shared hosting servers.
     *
     * @param string $path
     * @param string $content
     * @param int|null $mode
     * @return void
     */
    public function replace($path, $content, $mode = null)
    {
        // 1. Coba cara atomic standar bawaan jika path tidak kosong
        $tempPath = @tempnam(dirname($path), 'blade_');

        if ($tempPath !== false && !empty($tempPath)) {
            @file_put_contents($tempPath, $content);
            @rename($tempPath, $path);
            return;
        }

        // 2. Fallback 100% Aman untuk Server CWP/Chroot: Tulis langsung ke file tujuan tanpa tempnam()
        @file_put_contents($path, $content);
        if ($mode !== null) {
            @chmod($path, $mode);
        }
    }
}

class SafeFilesystemServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('files', function () {
            return new SafeFilesystem;
        });
    }
}
