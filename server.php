<?php

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Trik untuk menggantikan storage:link di Railway Nixpacks (Custom Folder Structure)
// Jika request mengarah ke /public/storage/app/..., cari file aslinya di system/storage/app/public/...
if (strpos($uri, '/public/storage/app/') === 0) {
    $storagePath = substr($uri, strlen('/public/storage/app/'));
    $realPath = __DIR__ . '/system/storage/app/public/' . $storagePath;
    
    if (file_exists($realPath)) {
        // Serve the file directly with appropriate mime type
        $mime = mime_content_type($realPath);
        header("Content-Type: $mime");
        readfile($realPath);
        exit;
    }
}

// Jika URI bukan '/' dan merupakan path file statis yang valid di root
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Biarkan PHP built-in server melayani file statis ini langsung
}

require_once __DIR__.'/index.php';
