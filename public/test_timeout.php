<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$events = \App\Models\Event::all();
foreach ($events as $event) {
    echo "Event: {$event->title}, Timeout Hours: {$event->payment_verification_timeout_hours}\n";
}
