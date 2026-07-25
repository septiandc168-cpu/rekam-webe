<?php

$uri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($uri, PHP_URL_PATH);

// Trik untuk URL Encode dan urldecode yang agresif karena nama file bawaan user terkadang aneh (memiliki + atau koma)
$decodedUri = urldecode($parsedUrl);
// Jika server merubah + menjadi spasi (seperti urldecode bawaan PHP), kembalikan jika file asli pakai +
// Atau kita biarkan file_exists mengecek 2 versi nama (dengan spasi dan dengan +)
$uriVersions = [
    $decodedUri,
    str_replace(' ', '+', $decodedUri)
];

foreach ($uriVersions as $checkUri) {
    if (strpos($checkUri, '/public/storage/app/') === 0) {
        $storagePath = substr($checkUri, strlen('/public/storage/app/'));
        $possiblePaths = [
            __DIR__ . '/system/public/storage/app/' . $storagePath,
            __DIR__ . '/public/storage/app/' . $storagePath,
            __DIR__ . '/system/storage/app/public/' . $storagePath,
            __DIR__ . '/storage/app/public/' . $storagePath,
        ];
    
        foreach ($possiblePaths as $realPath) {
            if (file_exists($realPath)) {
                $mime = mime_content_type($realPath) ?: 'application/pdf';
                header("Content-Type: $mime");
                // Memaksa browser menampilkan PDF di tab baru, bukan hanya mendownloadnya
                header('Content-Disposition: inline; filename="' . basename($realPath) . '"');
                readfile($realPath);
                exit;
            }
        }
    }
}

// Jika URI bukan '/' dan merupakan path file statis yang valid di root
if ($decodedUri !== '/' && file_exists(__DIR__ . $decodedUri)) {
    return false;
}

require_once __DIR__.'/index.php';
