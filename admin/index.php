<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
is_admin_login();
wajib_login();

$judul_halaman = 'Dashboard';

$jumlah_siswa = $pdo->query('SELECT COUNT(*) FROM siswa')->fetchColumn();
$jumlah_pengumuman = $pdo->query('SELECT COUNT(*) FROM pengumuman')->fetchColumn();
$jumlah_jadwal = $pdo->query('SELECT COUNT(*) FROM jadwal')->fetchColumn();
$jumlah_materi = $pdo->query('SELECT COUNT(*) FROM materi')->fetchColumn();
$jumlah_galeri = $pdo->query('SELECT COUNT(*) FROM galeri')->fetchColumn();

$pengumuman_terbaru = $pdo->query('SELECT * FROM pengumuman ORDER BY dibuat_pada DESC LIMIT 5')->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="grid grid-4" style="margin-bottom:28px;">
    <div class="stat-card">
        <div class="stat-label">👨‍🎓 Total Siswa</div>
        <div class="stat-value"><?= (int) $jumlah_siswa ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">📢 Pengumuman</div>
        <div class="stat-value"><?= (int) $jumlah_pengumuman ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">📚 Materi & Tugas</div>
        <div class="stat-value"><?= (int) $jumlah_materi ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">🖼️ Foto Galeri</div>
        <div class="stat-value"><?= (int) $jumlah_galeri ?></div>
    </div>
</div>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2>Pengumuman Terbaru</h2>
        <a href="pengumuman.php" class="btn btn-primary btn-sm">+ Tambah Pengumuman</a>
    </div>

    <?php if (empty($pengumuman_terbaru)): ?>
        <p style="color:var(--color-text-muted);">Belum ada pengumuman. Yuk tambahkan yang pertama.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Tanggal</th><th>Judul</th><th>Penulis</th></tr></thead>
                <tbody>
                    <?php foreach ($pengumuman_terbaru as $p): ?>
                        <tr>
                            <td><?= tanggal_indo($p['tanggal']) ?></td>
                            <td><?= h($p['judul']) ?></td>
                            <td><?= h($p['penulis'] ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="grid grid-3">
    <a href="siswa.php" class="card" style="padding:22px;">
        <div style="font-size:1.6rem; margin-bottom:8px;">👨‍🎓</div>
        <h3 style="font-size:1rem; margin-bottom:4px;">Kelola Data Siswa</h3>
        <p style="color:var(--color-text-muted); font-size:0.85rem;">Tambah, ubah, atau hapus data siswa dan foto profil.</p>
    </a>
    <a href="jadwal.php" class="card" style="padding:22px;">
        <div style="font-size:1.6rem; margin-bottom:8px;">📅</div>
        <h3 style="font-size:1rem; margin-bottom:4px;">Kelola Jadwal</h3>
        <p style="color:var(--color-text-muted); font-size:0.85rem;">Atur jadwal pelajaran per hari.</p>
    </a>
    <a href="galeri.php" class="card" style="padding:22px;">
        <div style="font-size:1.6rem; margin-bottom:8px;">🖼️</div>
        <h3 style="font-size:1rem; margin-bottom:4px;">Kelola Galeri</h3>
        <p style="color:var(--color-text-muted); font-size:0.85rem;">Unggah foto kegiatan dan momen kelas.</p>
    </a>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
