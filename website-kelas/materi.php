<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Materi & Tugas';
$skeleton_type = 'materi';

$kategori = $_GET['kategori'] ?? 'Semua';
if (in_array($kategori, ['Materi', 'Tugas'], true)) {
    $stmt = $pdo->prepare('SELECT * FROM materi WHERE kategori = ? ORDER BY dibuat_pada DESC');
    $stmt->execute([$kategori]);
} else {
    $stmt = $pdo->query('SELECT * FROM materi ORDER BY dibuat_pada DESC');
}
$daftar_materi = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section section-tight">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Belajar Bersama</span>
                <h2>Materi &amp; Tugas</h2>
                <p>Unduh materi pelajaran dan pantau tugas yang diberikan guru.</p>
            </div>
        </div>

        <div class="schedule-tabs">
            <a href="?kategori=Semua" class="<?= $kategori === 'Semua' ? 'active' : '' ?>">Semua</a>
            <a href="?kategori=Materi" class="<?= $kategori === 'Materi' ? 'active' : '' ?>">Materi</a>
            <a href="?kategori=Tugas" class="<?= $kategori === 'Tugas' ? 'active' : '' ?>">Tugas</a>
        </div>

        <?php if (empty($daftar_materi)): ?>
            <div class="empty-state">
                <h3>Belum ada materi atau tugas</h3>
                <p>Konten yang diunggah admin akan muncul di sini.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($daftar_materi as $m): ?>
                    <div class="card material-card">
                        <div class="material-top">
                            <span class="badge <?= $m['kategori'] === 'Tugas' ? 'badge-accent' : '' ?>"><?= h($m['kategori']) ?></span>
                            <?php if ($m['kategori'] === 'Tugas' && !empty($m['batas_waktu'])): ?>
                                <span class="meta">Batas: <?= tanggal_indo($m['batas_waktu']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3><?= h($m['judul']) ?></h3>
                        <p class="meta"><?= h($m['mata_pelajaran'] ?: 'Umum') ?></p>
                        <p style="color:var(--color-text-muted); font-size:0.9rem;"><?= h(ringkas($m['deskripsi'], 100)) ?></p>

                        <?php if (!empty($m['file']) && file_exists(__DIR__ . '/uploads/materi/' . $m['file'])): ?>
                            <a href="<?= BASE_URL ?>/uploads/materi/<?= h($m['file']) ?>" class="btn btn-light btn-sm btn-file" download>⬇ Unduh File</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
