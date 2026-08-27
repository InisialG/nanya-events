<?php

// Gunakan /tmp langsung (paling reliable di shared hosting CWP)
// JANGAN pakai sys_get_temp_dir() karena bisa di-override dan hasilnya tidak pasti
$compiledViewPath = '/tmp/nanya-compiled-views';

if (!is_dir($compiledViewPath)) {
    @mkdir($compiledViewPath, 0777, true);
}
@chmod($compiledViewPath, 0777);

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    */

    'compiled' => env('VIEW_COMPILED_PATH', $compiledViewPath),

];
