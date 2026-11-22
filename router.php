<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// Jika akses root (/) atau folder admin (/admin atau /admin/)
if ($uri === '') {
    $file = __DIR__ . '/index.php';
} elseif ($uri === 'admin' || $uri === 'admin/') {
    $file = __DIR__ . '/admin/index.php';
} else {
    $file = __DIR__ . '/' . $uri;
}

// Jalankan file PHP jika ditemukan dan tidak berada di folder sensitif
if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    chdir(dirname($file)); // Menjaga agar path include/require relatif tetap jalan
    require $file;
    exit;
}

http_response_code(404);
echo "404 Not Found";
