<?php
/**
 * Header untuk halaman publik.
 * Variabel $judul_halaman bisa di-set di halaman sebelum include file ini.
 */
if (!isset($info)) {
    $stmt = $pdo->query('SELECT * FROM info_kelas ORDER BY id DESC LIMIT 1');
    $info = $stmt->fetch();
}
$nama_kelas = $info['nama_kelas'] ?? 'Website Kelas';
$judul_halaman = $judul_halaman ?? $nama_kelas;
$halaman_ini = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($judul_halaman) ?> | <?= h($nama_kelas) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="<?= BASE_URL ?>/assets/img/favicon.jpg">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>">
</head>
<body class="is-loading">

<div id="skeletonOverlay" aria-hidden="true">
    <div class="skeleton-wrap">
        <?php
        // Tampilan skeleton menyesuaikan jenis halaman. Set $skeleton_type
        // di halaman PHP sebelum include header.php.
        $tipe_skeleton = $skeleton_type ?? 'grid';
        ?>

        <?php if ($tipe_skeleton === 'login'): ?>
            <div style="max-width:380px; margin:60px auto 0;">
                <div class="skeleton-card" style="text-align:center; padding:36px 32px;">
                    <div class="skeleton-circle" style="margin:0 auto 18px;"></div>
                    <div class="skeleton-line" style="width:60%; height:20px; margin:0 auto 8px;"></div>
                    <div class="skeleton-line short" style="height:12px; margin:0 auto 28px;"></div>
                    <div class="skeleton-line" style="height:42px; border-radius:8px; margin-bottom:14px;"></div>
                    <div class="skeleton-line" style="height:42px; border-radius:8px; margin-bottom:20px;"></div>
                    <div class="skeleton-line" style="height:44px; border-radius:8px; margin-bottom:0;"></div>
                </div>
            </div>

        <?php elseif ($tipe_skeleton === 'beranda'): ?>
            <div class="skeleton-hero"></div>
            <div class="skeleton-head"></div>
            <div class="skeleton-grid">
                <div class="skeleton-card no-avatar"><div class="skeleton-line medium"></div><div class="skeleton-line"></div><div class="skeleton-line short"></div></div>
                <div class="skeleton-card no-avatar"><div class="skeleton-line medium"></div><div class="skeleton-line"></div><div class="skeleton-line short"></div></div>
                <div class="skeleton-card no-avatar"><div class="skeleton-line medium"></div><div class="skeleton-line"></div><div class="skeleton-line short"></div></div>
            </div>

        <?php elseif ($tipe_skeleton === 'siswa'): ?>
            <div class="skeleton-head"></div>
            <div class="skeleton-grid">
                <?php for ($i = 0; $i < 8; $i++): ?>
                    <div class="skeleton-card"><div class="skeleton-circle"></div><div class="skeleton-line short"></div><div class="skeleton-line medium"></div></div>
                <?php endfor; ?>
            </div>

        <?php elseif ($tipe_skeleton === 'jadwal'): ?>
            <div class="skeleton-head"></div>
            <div class="skeleton-tabs">
                <?php for ($i = 0; $i < 6; $i++): ?><div class="skeleton-tab"></div><?php endfor; ?>
            </div>
            <div class="skeleton-table">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="skeleton-row">
                        <div class="skeleton-line"></div><div class="skeleton-line"></div><div class="skeleton-line"></div><div class="skeleton-line"></div>
                    </div>
                <?php endfor; ?>
            </div>

        <?php elseif ($tipe_skeleton === 'galeri'): ?>
            <div class="skeleton-head"></div>
            <div class="skeleton-grid">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <div class="skeleton-box"></div>
                <?php endfor; ?>
            </div>

        <?php else: /* pengumuman & materi: kartu teks tanpa foto bulat */ ?>
            <div class="skeleton-head"></div>
            <div class="skeleton-grid">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <div class="skeleton-card no-avatar"><div class="skeleton-line short"></div><div class="skeleton-line medium"></div><div class="skeleton-line"></div><div class="skeleton-line" style="width:60%"></div></div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<header class="site-header">
    <div class="container header-inner">
       
            <a href="<?= BASE_URL ?>/index.php" class="brand">
            <?php if (logo_ada($info['logo'] ?? null)): ?>
                <img class="brand-mark brand-mark-img" src="<?= BASE_URL ?>/uploads/logo/<?= h($info['logo']) ?>" alt="Logo <?= h($nama_kelas) ?>">
            <?php else: ?>
                <span class="brand-mark"><?= h(mb_substr($nama_kelas, 0, 1)) ?></span>
            <?php endif; ?>
            <span class="brand-name"><?= h($nama_kelas) ?></span>
            </a>
        
        <button class="nav-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="site-nav" id="siteNav">

            <span class="nav-indicator" id="navIndicator" aria-hidden="true"></span>
            <a href="<?= BASE_URL ?>/index.php" class="<?= $halaman_ini === 'index.php' ? 'active' : '' ?>">Beranda</a>
            <a href="<?= BASE_URL ?>/siswa.php" class="<?= $halaman_ini === 'siswa.php' ? 'active' : '' ?>">Data Siswa</a>
            <a href="<?= BASE_URL ?>/pengumuman.php" class="<?= $halaman_ini === 'pengumuman.php' ? 'active' : '' ?>">Pengumuman</a>
            <a href="<?= BASE_URL ?>/jadwal.php" class="<?= $halaman_ini === 'jadwal.php' ? 'active' : '' ?>">Jadwal</a>
            <a href="<?= BASE_URL ?>/materi.php" class="<?= $halaman_ini === 'materi.php' ? 'active' : '' ?>">Materi &amp; Tugas</a>
            <a href="<?= BASE_URL ?>/galeri.php" class="<?= $halaman_ini === 'galeri.php' ? 'active' : '' ?>">Galeri</a>
            <a href="<?= BASE_URL ?>/<?= is_admin_login() ? 'admin/index.php' : 'login.php' ?>" class="nav-cta">
                <?= is_admin_login() ? 'Dashboard Admin' : 'Login Admin' ?>
            </a>
        </nav>
        
    </div>
</header>

<main class="fade-target">
