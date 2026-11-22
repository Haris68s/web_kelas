<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Data Siswa';
$skeleton_type = 'siswa';

$kata_kunci = trim($_GET['q'] ?? '');
if ($kata_kunci !== '') {
    $stmt = $pdo->prepare('SELECT * FROM siswa WHERE nama LIKE ? OR nis LIKE ? ORDER BY nama ASC');
    $like = '%' . $kata_kunci . '%';
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query('SELECT * FROM siswa ORDER BY nama ASC');
}
$daftar_siswa = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section section-tight">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Anggota Kelas</span>
                <h2>Data Siswa</h2>
                <p>Total <?= count($daftar_siswa) ?> siswa terdaftar.</p>
            </div>

            <form method="get" style="display:flex; gap:8px;">
                <input type="text" name="q" class="form-control" placeholder="Cari nama atau NIS..." value="<?= h($kata_kunci) ?>" style="width:220px;">
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            </form>
        </div>

        <?php if (empty($daftar_siswa)): ?>
            <div class="empty-state">
                <h3>Data tidak ditemukan</h3>
                <p>Coba kata kunci lain, atau tunggu admin menambahkan data siswa.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-4">
                <?php foreach ($daftar_siswa as $s): ?>
                    <div class="card student-card">
                        <img class="student-photo" src="<?= foto_url('siswa', $s['foto']) ?>" alt="Foto <?= h($s['nama']) ?>">
                        <h3><?= h($s['nama']) ?></h3>
                        <p style="color:var(--color-text-muted); font-size:0.85rem;">
                          &middot; <?= $s['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                        </p>
                        <?php if (!empty($s['jabatan'])): ?>
                            <div class="badge" style="margin-top:10px;"><?= h($s['jabatan']) ?></div>
                        <?php endif; ?>

                        <?php if (!empty($s['tempat_lahir']) || !empty($s['no_hp'])): ?>
                            <div style="margin-top:14px; text-align:left; font-size:0.82rem; color:var(--color-text-muted); border-top:1px solid var(--color-border); padding-top:12px;">
                                <?php if (!empty($s['tempat_lahir'])): ?>
                                    <p>📍 <?= h($s['tempat_lahir']) ?><?= $s['tanggal_lahir'] ? ', ' . tanggal_indo($s['tanggal_lahir']) : '' ?></p>
                                <?php endif; ?>
                                <?php if (!empty($s['no_hp'])): ?>
                                    <p>📞 <?= h($s['no_hp']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
