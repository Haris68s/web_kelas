<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$info = $pdo->query('SELECT * FROM info_kelas ORDER BY id DESC LIMIT 1')->fetch();
$judul_halaman = 'Beranda';
$skeleton_type = 'beranda';

$jumlah_siswa = $pdo->query('SELECT COUNT(*) FROM siswa')->fetchColumn();
$jumlah_mapel = $pdo->query('SELECT COUNT(DISTINCT mata_pelajaran) FROM jadwal')->fetchColumn();

$pengumuman_terbaru = $pdo->query('SELECT * FROM pengumuman ORDER BY tanggal DESC, id DESC LIMIT 3')->fetchAll();
$siswa_terbaru = $pdo->query('SELECT * FROM siswa ORDER BY id ASC LIMIT 4')->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-inner">
        <div>
            <span class="hero-eyebrow">Website Resmi Kelas</span>
            <h1><?= h($info['nama_kelas'] ?? 'Website Kelas') ?></h1>
            <p><?= h($info['deskripsi'] ?? 'Website informasi kelas untuk siswa dan wali kelas.') ?></p>

            <div class="hero-stats">
                <div>
                    <strong><?= (int) $jumlah_siswa ?></strong>
                    <span>Siswa</span>
                </div>
                <div>
                    <strong><?= (int) $jumlah_mapel ?></strong>
                    <span>Mata Pelajaran</span>
                </div>
                <div>
                    <strong><?= h($info['tahun_ajaran'] ?? '-') ?></strong>
                    <span>Tahun Ajaran</span>
                </div>
            </div>

            <div style="margin-top:28px; display:flex; gap:12px; flex-wrap:wrap;">
                <a href="<?= BASE_URL ?>/siswa.php" class="btn btn-outline">Lihat Data Siswa</a>
                <a href="<?= BASE_URL ?>/jadwal.php" class="btn btn-outline">Lihat Jadwal</a>
            </div>
        </div>

        <div class="hero-card">
            <h3>Wali Kelas</h3>
            <p style="font-size:1.15rem; font-weight:600; margin-bottom:18px;"><?= h($info['wali_kelas'] ?? '-') ?></p>
            <?php if (!empty($info['motto'])): ?>
                <h3>Motto Kelas</h3>
                <p class="motto">&ldquo;<?= h($info['motto']) ?>&rdquo;</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Info Terbaru</span>
                <h2>Pengumuman</h2>
            </div>
            <a href="<?= BASE_URL ?>/pengumuman.php" class="btn btn-light btn-sm">Lihat Semua</a>
        </div>

        <?php if (empty($pengumuman_terbaru)): ?>
            <div class="empty-state">
                <h3>Belum ada pengumuman</h3>
                <p>Pengumuman terbaru dari wali kelas akan muncul di sini.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-3">
                <?php foreach ($pengumuman_terbaru as $p): ?>
                    <div class="card announce-card">
                        <div class="announce-date"><?= tanggal_indo($p['tanggal']) ?></div>
                        <h3><?= h($p['judul']) ?></h3>
                        <p><?= h(ringkas($p['isi'], 110)) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section section-tight" style="background:var(--color-surface); border-top:1px solid var(--color-border); border-bottom:1px solid var(--color-border);">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Kenali Teman Sekelas</span>
                <h2>Siswa</h2>
            </div>
            <a href="<?= BASE_URL ?>/siswa.php" class="btn btn-light btn-sm">Lihat Semua</a>
        </div>

        <?php if (empty($siswa_terbaru)): ?>
            <div class="empty-state">
                <h3>Belum ada data siswa</h3>
                <p>Data siswa yang ditambahkan admin akan tampil di sini.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-4">
                <?php foreach ($siswa_terbaru as $s): ?>
                    <div class="card student-card">
                        <img class="student-photo" src="<?= foto_url('siswa', $s['foto']) ?>" alt="Foto <?= h($s['nama']) ?>">
                        <h3><?= h($s['nama']) ?></h3>
                        <p style="color:var(--color-text-muted); font-size:0.85rem;">NIS: <?= h($s['nis'] ?: '-') ?></p>
                        <?php if (!empty($s['jabatan'])): ?>
                            <span class="badge"><?= h($s['jabatan']) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
