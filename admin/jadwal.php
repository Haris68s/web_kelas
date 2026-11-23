<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_login();

$judul_halaman = 'Jadwal Pelajaran';
$hari_urut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

if (isset($_GET['hapus'])) {
    $pdo->prepare('DELETE FROM jadwal WHERE id = ?')->execute([(int) $_GET['hapus']]);
    $_SESSION['flash_success'] = 'Jadwal berhasil dihapus.';
    header('Location: jadwal.php');
    exit;
}

// Kosongkan semua jadwal di satu hari (biar gampang input ulang dari nol)
if (isset($_GET['hapus_hari']) && in_array($_GET['hapus_hari'], $hari_urut, true)) {
    $stmt = $pdo->prepare('DELETE FROM jadwal WHERE hari = ?');
    $stmt->execute([$_GET['hapus_hari']]);
    $_SESSION['flash_success'] = 'Semua jadwal hari ' . $_GET['hapus_hari'] . ' berhasil dikosongkan.';
    header('Location: jadwal.php');
    exit;
}

// ------- SIMPAN BANYAK BARIS SEKALIGUS UNTUK SATU HARI -------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'bulk_tambah') {
    $hari_terpilih = $_POST['hari_bulk'] ?? 'Senin';
    $daftar_mulai = $_POST['b_jam_mulai'] ?? [];
    $daftar_selesai = $_POST['b_jam_selesai'] ?? [];
    $daftar_mapel = $_POST['b_mapel'] ?? [];
    $daftar_guru = $_POST['b_guru'] ?? [];
    $daftar_ruangan = $_POST['b_ruangan'] ?? [];

    $stmt = $pdo->prepare('INSERT INTO jadwal (hari, jam_mulai, jam_selesai, mata_pelajaran, guru, ruangan) VALUES (?,?,?,?,?,?)');
    $jumlah_masuk = 0;

    foreach ($daftar_mapel as $i => $mapel) {
        $mapel = trim($mapel);
        $jm = $daftar_mulai[$i] ?? '';
        $js = $daftar_selesai[$i] ?? '';
        // Baris kosong (belum diisi) dilewati saja, tidak dianggap error
        if ($mapel === '' && $jm === '' && $js === '') {
            continue;
        }
        if ($mapel === '' || $jm === '' || $js === '') {
            continue; // baris tidak lengkap, dilewati diam-diam
        }
        $stmt->execute([$hari_terpilih, $jm, $js, $mapel, trim($daftar_guru[$i] ?? ''), trim($daftar_ruangan[$i] ?? '')]);
        $jumlah_masuk++;
    }

    if ($jumlah_masuk > 0) {
        $_SESSION['flash_success'] = $jumlah_masuk . ' jadwal hari ' . $hari_terpilih . ' berhasil ditambahkan sekaligus.';
    } else {
        $_SESSION['flash_error'] = 'Tidak ada baris yang lengkap untuk disimpan. Isi minimal jam mulai, jam selesai, dan nama mapel/kegiatan.';
    }

    header('Location: jadwal.php');
    exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM jadwal WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit_data = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $hari = $_POST['hari'] ?? 'Senin';
    $jam_mulai = $_POST['jam_mulai'] ?? '';
    $jam_selesai = $_POST['jam_selesai'] ?? '';
    $mapel = trim($_POST['mata_pelajaran'] ?? '');
    $guru = trim($_POST['guru'] ?? '');
    $ruangan = trim($_POST['ruangan'] ?? '');

    if ($mapel === '' || $jam_mulai === '' || $jam_selesai === '') {
        $_SESSION['flash_error'] = 'Mata pelajaran dan jam wajib diisi.';
        header('Location: jadwal.php');
        exit;
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE jadwal SET hari=?, jam_mulai=?, jam_selesai=?, mata_pelajaran=?, guru=?, ruangan=? WHERE id=?');
        $stmt->execute([$hari, $jam_mulai, $jam_selesai, $mapel, $guru, $ruangan, $id]);
        $_SESSION['flash_success'] = 'Jadwal berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO jadwal (hari, jam_mulai, jam_selesai, mata_pelajaran, guru, ruangan) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$hari, $jam_mulai, $jam_selesai, $mapel, $guru, $ruangan]);
        $_SESSION['flash_success'] = 'Jadwal baru berhasil ditambahkan.';
    }

    header('Location: jadwal.php');
    exit;
}

$semua_jadwal = $pdo->query("
    SELECT * FROM jadwal 
    ORDER BY CASE hari 
        WHEN 'Senin' THEN 1 
        WHEN 'Selasa' THEN 2 
        WHEN 'Rabu' THEN 3 
        WHEN 'Kamis' THEN 4 
        WHEN 'Jumat' THEN 5 
        WHEN 'Sabtu' THEN 6 
        ELSE 7 
    END, jam_mulai ASC
")->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2>Tambah Jadwal Sekaligus per Hari</h2>
    </div>
    <p style="color:var(--color-text-muted); font-size:0.85rem; margin-bottom:18px;">
        Pilih harinya, lalu isi semua jam pelajaran hari itu sekaligus (boleh selang-seling sama jam istirahat — cukup tulis "Istirahat" di kolom Mapel/Kegiatan, kolom guru &amp; ruangan boleh dikosongkan). Klik "+ Tambah Baris" kalau kurang.
    </p>

    <form method="post" autocomplete="off" id="formBulkJadwal">
        <input type="hidden" name="aksi" value="bulk_tambah">

        <div class="form-group" style="max-width:240px;">
            <label>Hari</label>
            <select name="hari_bulk" class="form-control">
                <?php foreach ($hari_urut as $h): ?>
                    <option value="<?= $h ?>"><?= $h ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="table-wrap" style="margin-bottom:14px;">
            <table id="tabelBulkJadwal">
                <thead>
                    <tr>
                        <th style="width:110px;">Jam Mulai</th>
                        <th style="width:110px;">Jam Selesai</th>
                        <th>Mapel / Kegiatan</th>
                        <th>Guru</th>
                        <th>Ruangan</th>
                        <th style="width:40px;"></th>
                    </tr>
                </thead>
                <tbody id="tbodyBulkJadwal">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                    <tr>
                        <td><input type="time" name="b_jam_mulai[]" class="form-control"></td>
                        <td><input type="time" name="b_jam_selesai[]" class="form-control"></td>
                        <td><input type="text" name="b_mapel[]" class="form-control" placeholder="Contoh: Matematika / Istirahat"></td>
                        <td><input type="text" name="b_guru[]" class="form-control"></td>
                        <td><input type="text" name="b_ruangan[]" class="form-control"></td>
                        <td><button type="button" class="btn btn-light btn-sm js-hapus-baris" title="Hapus baris">✕</button></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button type="button" class="btn btn-light btn-sm" id="btnTambahBaris">+ Tambah Baris</button>
            <button type="submit" class="btn btn-primary">Simpan Semua Jadwal Hari Ini</button>
        </div>
    </form>
</div>

<script>
document.getElementById('btnTambahBaris').addEventListener('click', function () {
    var tbody = document.getElementById('tbodyBulkJadwal');
    var barisBaru = tbody.rows[0].cloneNode(true);
    barisBaru.querySelectorAll('input').forEach(function (input) { input.value = ''; });
    tbody.appendChild(barisBaru);
});

document.getElementById('tbodyBulkJadwal').addEventListener('click', function (e) {
    if (e.target.classList.contains('js-hapus-baris')) {
        var tbody = document.getElementById('tbodyBulkJadwal');
        if (tbody.rows.length > 1) {
            e.target.closest('tr').remove();
        } else {
            e.target.closest('tr').querySelectorAll('input').forEach(function (input) { input.value = ''; });
        }
    }
});
</script>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2><?= $edit_data ? 'Ubah Satu Jadwal' : 'Atau Tambah Satu Jadwal Saja' ?></h2>
        <?php if ($edit_data): ?><a href="jadwal.php" class="btn btn-light btn-sm">Batal Ubah</a><?php endif; ?>
    </div>

    <form method="post" autocomplete="off">
        <input type="hidden" name="id" value="<?= (int) ($edit_data['id'] ?? 0) ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Hari</label>
                <select name="hari" class="form-control">
                    <?php foreach ($hari_urut as $h): ?>
                        <option value="<?= $h ?>" <?= ($edit_data['hari'] ?? '') === $h ? 'selected' : '' ?>><?= $h ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mata Pelajaran / Kegiatan</label>
                <input type="text" name="mata_pelajaran" class="form-control" required value="<?= h($edit_data['mata_pelajaran'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control" required value="<?= h($edit_data['jam_mulai'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control" required value="<?= h($edit_data['jam_selesai'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nama Guru</label>
                <input type="text" name="guru" class="form-control" value="<?= h($edit_data['guru'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Ruangan</label>
                <input type="text" name="ruangan" class="form-control" value="<?= h($edit_data['ruangan'] ?? '') ?>">
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Simpan Perubahan' : 'Tambah Jadwal' ?></button>
    </form>
</div>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Semua Jadwal (<?= count($semua_jadwal) ?>)</h2></div>

    <?php if (empty($semua_jadwal)): ?>
        <p style="color:var(--color-text-muted);">Belum ada jadwal.</p>
    <?php else: ?>
        <?php
        $per_hari = [];
        foreach ($semua_jadwal as $j) { $per_hari[$j['hari']][] = $j; }
        ?>
        <?php foreach ($hari_urut as $h): ?>
            <?php if (empty($per_hari[$h])) continue; ?>
            <div style="margin-bottom:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <h3 style="font-size:0.95rem;"><?= $h ?> (<?= count($per_hari[$h]) ?>)</h3>
                    <a href="?hapus_hari=<?= $h ?>" class="btn btn-light btn-sm js-confirm-delete" data-confirm="Kosongkan semua jadwal hari <?= $h ?>?">Kosongkan Hari Ini</a>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Jam</th><th>Mapel / Kegiatan</th><th>Guru</th><th>Ruangan</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach ($per_hari[$h] as $j): ?>
                                <tr>
                                    <td><?= jam_singkat($j['jam_mulai']) ?> - <?= jam_singkat($j['jam_selesai']) ?></td>
                                    <td><?= h($j['mata_pelajaran']) ?></td>
                                    <td><?= h($j['guru'] ?: '-') ?></td>
                                    <td><?= h($j['ruangan'] ?: '-') ?></td>
                                    <td class="table-actions">
                                        <a href="?edit=<?= $j['id'] ?>" class="btn btn-light btn-sm">Ubah</a>
                                        <a href="?hapus=<?= $j['id'] ?>" class="btn btn-danger btn-sm js-confirm-delete" data-confirm="Hapus jadwal ini?">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
