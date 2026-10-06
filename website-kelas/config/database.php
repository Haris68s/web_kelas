<?php
/**
 * Konfigurasi koneksi database.
 * Ubah 4 nilai di bawah ini sesuai pengaturan MySQL di komputer/hosting kamu.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'website_kelas');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
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

// Base URL project, dipakai untuk link & path file (foto, dokumen, dll).
// Sesuaikan jika nama folder project kamu berbeda dari "website-kelas".
define('BASE_URL', '/website-kelas');
