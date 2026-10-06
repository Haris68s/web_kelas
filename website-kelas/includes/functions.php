<?php
/**
 * Kumpulan fungsi bantuan yang dipakai di berbagai halaman.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Amankan output teks dari HTML/script berbahaya (mencegah XSS)
function h($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Ubah tanggal format Y-m-d menjadi format Indonesia, misal: 27 Agustus 2026
function tanggal_indo($tanggal, $tampilkan_hari = false)
{
    if (empty($tanggal)) {
        return '-';
    }
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $hari_list = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
    ];
    $ts = strtotime($tanggal);
    $hasil = date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    if ($tampilkan_hari) {
        $hasil = $hari_list[date('l', $ts)] . ', ' . $hasil;
    }
    return $hasil;
}

// Format HH:MM:SS menjadi HH:MM
function jam_singkat($waktu)
{
    if (empty($waktu)) {
        return '-';
    }
    return substr($waktu, 0, 5);
}

// Potong teks panjang untuk ditampilkan sebagai ringkasan
function ringkas($teks, $panjang = 150)
{
    $teks = strip_tags($teks ?? '');
    if (mb_strlen($teks) <= $panjang) {
        return $teks;
    }
    return mb_substr($teks, 0, $panjang) . '...';
}

// Cek apakah admin sedang login
function is_admin_login()
{
    return isset($_SESSION['admin_id']);
}

// Wajibkan login admin, kalau belum login lempar ke halaman login
function wajib_login()
{
    if (!is_admin_login()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

// Path gambar dengan fallback ke gambar placeholder jika file tidak ada
function foto_url($folder, $namafile, $placeholder = 'placeholder.png')
{
    if (!empty($namafile) && file_exists(__DIR__ . '/../uploads/' . $folder . '/' . $namafile)) {
        return BASE_URL . '/uploads/' . $folder . '/' . $namafile;
    }
    return BASE_URL . '/assets/img/' . $placeholder;
}

// Cek apakah file logo kelas ada di server (dipakai untuk fallback ke huruf inisial)
function logo_ada($namafile)
{
    return !empty($namafile) && file_exists(__DIR__ . '/../uploads/logo/' . $namafile);
}

// Pastikan folder upload ada; kalau belum ada, buat otomatis.
// Ini mencegah upload gagal diam-diam kalau folder uploads/xxx belum tercipta di server.
function pastikan_folder($path)
{
    if (!is_dir($path)) {
        @mkdir($path, 0775, true);
    }
}

// Terjemahkan kode error upload PHP jadi pesan yang bisa dipahami admin
function pesan_error_upload($kode_error)
{
    switch ($kode_error) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'Ukuran file terlalu besar (melebihi batas upload server). Coba kompres/perkecil dulu fotonya, atau naikkan upload_max_filesize & post_max_size di php.ini.';
        case UPLOAD_ERR_PARTIAL:
            return 'File hanya terupload sebagian. Coba upload ulang.';
        case UPLOAD_ERR_NO_FILE:
            return 'Tidak ada file yang dipilih.';
        case UPLOAD_ERR_NO_TMP_DIR:
        case UPLOAD_ERR_CANT_WRITE:
            return 'Server gagal menyimpan file sementara. Coba lagi atau hubungi admin server.';
        default:
            return 'Gagal upload file (kode error ' . $kode_error . ').';
    }
}
