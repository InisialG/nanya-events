<?php
/**
 * Storage File Server — pengganti symlink storage:link
 * untuk hosting dengan filesystem read-only.
 * 
 * Melayani file dari storage/app/public/ seolah-olah ada di public/storage/
 */

$baseDir = dirname(__DIR__);
$storagePath = $baseDir . '/storage/app/public';

// Ambil path file yang diminta
$requestedFile = $_GET['file'] ?? '';

// Sanitasi: hapus path traversal
$requestedFile = str_replace(['../', '..\\', "\0"], '', $requestedFile);
$requestedFile = ltrim($requestedFile, '/\\');

if (empty($requestedFile)) {
    http_response_code(404);
    exit('File not found.');
}

$fullPath = $storagePath . '/' . $requestedFile;
$tmpFallbackPath = '/tmp/nanya-uploads/' . $requestedFile;

if (file_exists($tmpFallbackPath)) {
    $realFilePath = realpath($tmpFallbackPath);
} else {
    $realStoragePath = realpath($storagePath);
    $realFilePath = realpath($fullPath);

    if ($realFilePath === false || !str_starts_with($realFilePath, $realStoragePath)) {
        http_response_code(404);
        exit('File not found.');
    }
}

if (!$realFilePath || !is_file($realFilePath)) {
    http_response_code(404);
    exit('File not found.');
}

// Tentukan MIME type
$mimeTypes = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
    'svg'  => 'image/svg+xml',
    'pdf'  => 'application/pdf',
    'mp4'  => 'video/mp4',
    'css'  => 'text/css',
    'js'   => 'application/javascript',
    'json' => 'application/json',
    'ico'  => 'image/x-icon',
    'woff' => 'font/woff',
    'woff2'=> 'font/woff2',
    'ttf'  => 'font/ttf',
];

$ext = strtolower(pathinfo($realFilePath, PATHINFO_EXTENSION));
$mime = $mimeTypes[$ext] ?? (function_exists('mime_content_type') ? mime_content_type($realFilePath) : 'application/octet-stream');

// Headers
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($realFilePath));
header('Cache-Control: public, max-age=604800'); // Cache 7 hari
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 604800) . ' GMT');

// Serve file
readfile($realFilePath);
exit;
