<?php
/**
 * Header untuk semua halaman di dalam folder admin/.
 * Wajib panggil wajib_login() SEBELUM include file ini di setiap halaman admin.
 */
$halaman_admin = basename($_SERVER['PHP_SELF']);
$judul_halaman = $judul_halaman ?? 'Panel Admin';

if (!isset($info)) {
    $info = $pdo->query('SELECT * FROM info_kelas ORDER BY id DESC LIMIT 1')->fetch();
}
$nama_kelas = $info['nama_kelas'] ?? 'Website Kelas';

$menu_admin = [
    'index.php'       => ['label' => 'Dashboard',    'icon' => '🏠'],
    'siswa.php'        => ['label' => 'Data Siswa',   'icon' => '👨‍🎓'],
    'pengumuman.php'   => ['label' => 'Pengumuman',   'icon' => '📢'],
    'jadwal.php'        => ['label' => 'Jadwal',      'icon' => '📅'],
    'materi.php'        => ['label' => 'Materi & Tugas', 'icon' => '📚'],
    'galeri.php'        => ['label' => 'Galeri',      'icon' => '🖼️'],
    'pengaturan.php'    => ['label' => 'Pengaturan',  'icon' => '⚙️'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($judul_halaman) ?> | Admin <?= h($nama_kelas) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="<?= BASE_URL ?>/assets/img/favicon.jpg">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<div class="mobile-topbar">
    <strong><?= h($nama_kelas) ?> · Admin</strong>
    <button id="sidebarToggle" class="btn btn-outline btn-sm" type="button" aria-label="Buka menu" aria-controls="adminSidebar" aria-expanded="false" style="color:#fff; border-color:rgba(255,255,255,0.5);">☰</button>
</div>
<button class="admin-sidebar-backdrop" id="sidebarBackdrop" type="button" aria-label="Tutup menu"></button>

<div class="admin-layout">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand">
            <?php if (logo_ada($info['logo'] ?? null)): ?>
                <img class="brand-mark brand-mark-img" src="<?= BASE_URL ?>/uploads/logo/<?= h($info['logo']) ?>" alt="Logo <?= h($nama_kelas) ?>">
            <?php else: ?>
                <span class="brand-mark"><?= h(mb_substr($nama_kelas, 0, 1)) ?></span>
            <?php endif; ?>
            <span class="brand-name"><?= h($nama_kelas) ?></span>
        </div>

        <nav>
            <?php foreach ($menu_admin as $file => $item): ?>
                <a href="<?= $file ?>" class="<?= $halaman_admin === $file ? 'active' : '' ?>">
                    <span><?= $item['icon'] ?></span> <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>

            <div class="divider"></div>

            <a href="<?= BASE_URL ?>/index.php"><span>🌐</span> Lihat Website</a>
            <a href="logout.php"><span>🚪</span> Keluar</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-topbar">
            <h1><?= h($judul_halaman) ?></h1>
            <span style="color:var(--color-text-muted); font-size:0.9rem;">Halo, <?= h($_SESSION['admin_nama'] ?? 'Admin') ?> 👋</span>
        </div>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success" data-autohide><?= h($_SESSION['flash_success']) ?></div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger" data-autohide><?= h($_SESSION['flash_error']) ?></div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
