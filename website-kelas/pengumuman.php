<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Pengumuman';
$skeleton_type = 'pengumuman';
$daftar_pengumuman = $pdo->query('SELECT * FROM pengumuman ORDER BY tanggal DESC, id DESC')->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section section-tight">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Info Kelas</span>
                <h2>Pengumuman</h2>
                <p>Informasi resmi dari ketua kelas dan wali kelas.</p>
            </div>
        </div>

        <?php if (empty($daftar_pengumuman)): ?>
            <div class="empty-state">
                <h3>Belum ada pengumuman</h3>
                <p>Pantau terus halaman ini, ya.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-2">
                <?php foreach ($daftar_pengumuman as $p): ?>
                    <div class="card announce-card">
                        <div class="announce-date">
                            <?= tanggal_indo($p['tanggal'], true) ?>
                            <?php if (!empty($p['penulis'])): ?> &middot; oleh <?= h($p['penulis']) ?><?php endif; ?>
                        </div>
                        <h3><?= h($p['judul']) ?></h3>
                        <p style="white-space:pre-line;"><?= h($p['isi']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
