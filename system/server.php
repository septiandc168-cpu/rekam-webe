<?php

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Trik untuk menggantikan storage:link di Railway Nixpacks (Custom Folder Structure)
// Tangkap semua URL yang diawali dengan /public/storage/app/
if (strpos($uri, '/public/storage/app/') === 0) {
    $storagePath = substr($uri, strlen('/public/storage/app/'));
    
    // Cek SEMUA kemungkinan lokasi file karena struktur folder yang sangat tidak standar
    $possiblePaths = [
        __DIR__ . '/system/public/storage/app/' . $storagePath, // Tempat public_path() framework
        __DIR__ . '/public/storage/app/' . $storagePath,        // Folder statis luar
        __DIR__ . '/system/storage/app/public/' . $storagePath, // Standard Laravel fallback
        __DIR__ . '/storage/app/public/' . $storagePath,        // Standard Laravel fallback 2
    ];

    foreach ($possiblePaths as $realPath) {
        if (file_exists($realPath)) {
            $mime = mime_content_type($realPath) ?: 'application/octet-stream';
            header("Content-Type: $mime");
            readfile($realPath);
            exit;
        }
    }
}

// Trik tambahan jika dipanggil lewat /storage/...
if (strpos($uri, '/storage/') === 0) {
    $storagePath = substr($uri, strlen('/storage/'));
    $possiblePaths = [
        __DIR__ . '/system/public/storage/app/' . $storagePath,
        __DIR__ . '/storage/app/public/' . $storagePath,
        __DIR__ . '/public/storage/app/' . $storagePath,
        __DIR__ . '/system/storage/app/public/' . $storagePath,
    ];

    foreach ($possiblePaths as $realPath) {
        if (file_exists($realPath)) {
            $mime = mime_content_type($realPath) ?: 'application/octet-stream';
            header("Content-Type: $mime");
            readfile($realPath);
            exit;
        }
    }
}

// Jika URI bukan '/' dan merupakan path file statis yang valid di root
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

require_once __DIR__.'/index.php';
