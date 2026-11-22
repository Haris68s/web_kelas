<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Jadwal Pelajaran';
$skeleton_type = 'jadwal';

$hari_urut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$angka_hari = (int) date('N'); // 1 = Senin ... 7 = Minggu
$hari_ini_nama = $angka_hari <= 6 ? $hari_urut[$angka_hari - 1] : 'Senin';
$hari_dipilih = $_GET['hari'] ?? $hari_ini_nama;
if (!in_array($hari_dipilih, $hari_urut, true)) {
    $hari_dipilih = 'Senin';
}

$stmt = $pdo->prepare('SELECT * FROM jadwal WHERE hari = ? ORDER BY jam_mulai ASC');
$stmt->execute([$hari_dipilih]);
$jadwal_hari_ini = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="section section-tight">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Setiap Minggu</span>
                <h2>Jadwal Pelajaran</h2>
                <p>Pilih hari untuk melihat jadwal mata pelajaran.</p>
            </div>
        </div>

        <div class="schedule-tabs">
            <?php foreach ($hari_urut as $h): ?>
                <a href="?hari=<?= urlencode($h) ?>" class="<?= $h === $hari_dipilih ? 'active' : '' ?>"><?= $h ?></a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($jadwal_hari_ini)): ?>
            <div class="empty-state">
                <h3>Tidak ada jadwal</h3>
                <p>Belum ada mata pelajaran yang dijadwalkan pada hari <?= h($hari_dipilih) ?>.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal_hari_ini as $j): ?>
                            <tr>
                                <td><?= jam_singkat($j['jam_mulai']) ?> - <?= jam_singkat($j['jam_selesai']) ?></td>
                                <td><strong><?= h($j['mata_pelajaran']) ?></strong></td>
                                <td><?= h($j['guru'] ?: '-') ?></td>
                                <td><?= h($j['ruangan'] ?: '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
