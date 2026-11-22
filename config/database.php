<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

/**
 * Konfigurasi koneksi database.
 * Ubah 4 nilai di bawah ini sesuai pengaturan MySQL di komputer/hosting kamu.
 */
define('DB_HOST', $_ENV['DATABASE_HOST'] );
define('DB_NAME', $_ENV['DATABASE_NAME'] );
define('DB_USER', $_ENV['DATABASE_USERNAME'] );
define('DB_PASS', $_ENV['DATABASE_PASSWORD'] );

try {
    $pdo = new PDO(
        'pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . $_ENV['DATABASE_PORT'] . ';sslmode=require',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal. Pastikan MySQL sudah berjalan dan database "website_kelas" sudah diimpor. Detail: ' . $e->getMessage());
}

// Tentukan URL dasar dari lokasi script agar tetap benar saat folder project
// dipindah atau diakses melalui subfolder (misalnya /web-kelas/website-kelas-11-sija/website-kelas).
$script_directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$script_directory = preg_replace('~/admin$~', '', $script_directory);
define('BASE_URL', rtrim($script_directory, '/'));
