<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

// Simulate order creation
$timeoutHours = 24;
$expiredAt = now()->addHours($timeoutHours);
echo "now(): " . now() . "\n";
echo "expiredAt: " . $expiredAt . "\n";

$isExpired = $expiredAt < now();
echo "isExpired: " . ($isExpired ? 'true' : 'false') . "\n";

$isExpiredStr = '2026-09-01 14:48:00' < now();
echo "isExpiredStr: " . ($isExpiredStr ? 'true' : 'false') . "\n";
