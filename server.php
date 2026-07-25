<?php

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rawurldecode($path);

// Reject traversal attempts before resolving any local filename.
if (str_contains($path, "\0") || preg_match('#(?:^|/)\.\.(?:/|$)#', $path)) {
    http_response_code(404);
    exit;
}

/**
 * Serve uploads which may live in a Railway Volume, the root web directory,
 * or the legacy system/public directory used before deployment was corrected.
 */
function serveUpload(string $relativePath): bool
{
    $relativePath = ltrim($relativePath, '/');
    $uploadRoot = rtrim(getenv('UPLOADS_PATH') ?: __DIR__ . '/public/storage/app', '/\\');

    $roots = [
        $uploadRoot,
        __DIR__ . '/public/storage/app',           // current deployment layout
        __DIR__ . '/system/public/storage/app',    // legacy upload layout
    ];

    foreach (array_unique($roots) as $root) {
        $candidate = $root . DIRECTORY_SEPARATOR . $relativePath;
        if (!is_file($candidate) || !is_readable($candidate)) {
            continue;
        }

        $mime = mime_content_type($candidate) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($candidate));
        header('Content-Disposition: inline; filename="' . addcslashes(basename($candidate), "\\\"") . '"');
        header('X-Content-Type-Options: nosniff');
        readfile($candidate);
        return true;
    }

    return false;
}

// Canonical URL for all activity uploads.
if (str_starts_with($path, '/public/storage/app/')) {
    if (serveUpload(substr($path, strlen('/public/storage/app/')))) {
        exit;
    }
}

// Backward-compatible URLs used by older profile/report records.
if (str_starts_with($path, '/public/storage/')) {
    $legacyRelativePath = substr($path, strlen('/public/storage/'));
    $legacyRoots = [__DIR__ . '/system/public/storage', __DIR__ . '/public/storage'];
    foreach ($legacyRoots as $root) {
        $candidate = $root . DIRECTORY_SEPARATOR . $legacyRelativePath;
        if (is_file($candidate) && is_readable($candidate)) {
            header('Content-Type: ' . (mime_content_type($candidate) ?: 'application/octet-stream'));
            header('Content-Length: ' . filesize($candidate));
            header('Content-Disposition: inline; filename="' . addcslashes(basename($candidate), "\\\"") . '"');
            header('X-Content-Type-Options: nosniff');
            readfile($candidate);
            exit;
        }
    }
}

// Let PHP's built-in server serve all static files below the repository root.
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}

require __DIR__ . '/index.php';
