<?php

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Jika URI bukan '/' dan merupakan path file statis yang valid di root atau public
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Biarkan PHP built-in server melayani file statis ini langsung
}

require_once __DIR__.'/index.php';
