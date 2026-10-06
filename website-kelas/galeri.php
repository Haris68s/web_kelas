<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Galeri Kelas';
$skeleton_type = 'galeri';
$daftar_galeri = $pdo->query('SELECT * FROM galeri ORDER BY dibuat_pada DESC')->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section section-tight">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Dokumentasi</span>
                <h2>Galeri Kelas</h2>
                <p>Momen-momen kebersamaan kelas kita.</p>
            </div>
        </div>

        <?php if (empty($daftar_galeri)): ?>
            <div class="empty-state">
                <h3>Belum ada foto</h3>
                <p>Foto kegiatan kelas yang diunggah admin akan tampil di sini.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($daftar_galeri as $g): ?>
                    <div class="gallery-item">
                        <img src="<?= foto_url('galeri', $g['gambar']) ?>" alt="<?= h($g['judul'] ?: 'Foto galeri') ?>" loading="lazy">
                        <?php if (!empty($g['judul'])): ?>
                            <div class="gallery-caption"><?= h($g['judul']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
