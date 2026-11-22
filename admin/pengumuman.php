<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_login();

$judul_halaman = 'Pengumuman';

if (isset($_GET['hapus'])) {
    $pdo->prepare('DELETE FROM pengumuman WHERE id = ?')->execute([(int) $_GET['hapus']]);
    $_SESSION['flash_success'] = 'Pengumuman berhasil dihapus.';
    header('Location: pengumuman.php');
    exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM pengumuman WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit_data = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $isi = trim($_POST['isi'] ?? '');
    $penulis = trim($_POST['penulis'] ?? '');
    $tanggal = $_POST['tanggal'] ?: date('Y-m-d');

    if ($judul === '' || $isi === '') {
        $_SESSION['flash_error'] = 'Judul dan isi pengumuman wajib diisi.';
        header('Location: pengumuman.php');
        exit;
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE pengumuman SET judul=?, isi=?, penulis=?, tanggal=? WHERE id=?');
        $stmt->execute([$judul, $isi, $penulis, $tanggal, $id]);
        $_SESSION['flash_success'] = 'Pengumuman berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO pengumuman (judul, isi, penulis, tanggal) VALUES (?,?,?,?)');
        $stmt->execute([$judul, $isi, $penulis, $tanggal]);
        $_SESSION['flash_success'] = 'Pengumuman baru berhasil ditambahkan.';
    }

    header('Location: pengumuman.php');
    exit;
}

$daftar_pengumuman = $pdo->query('SELECT * FROM pengumuman ORDER BY tanggal DESC, id DESC')->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2><?= $edit_data ? 'Ubah Pengumuman' : 'Tambah Pengumuman' ?></h2>
        <?php if ($edit_data): ?><a href="pengumuman.php" class="btn btn-light btn-sm">Batal Ubah</a><?php endif; ?>
    </div>

    <form method="post" autocomplete="off">
        <input type="hidden" name="id" value="<?= (int) ($edit_data['id'] ?? 0) ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" required value="<?= h($edit_data['judul'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= h($edit_data['tanggal'] ?? date('Y-m-d')) ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Penulis (opsional)</label>
            <input type="text" name="penulis" class="form-control" placeholder="Contoh: Ketua Kelas" value="<?= h($edit_data['penulis'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Isi Pengumuman</label>
            <textarea name="isi" class="form-control" rows="5" required><?= h($edit_data['isi'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Simpan Perubahan' : 'Tambah Pengumuman' ?></button>
    </form>
</div>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Daftar Pengumuman (<?= count($daftar_pengumuman) ?>)</h2></div>

    <?php if (empty($daftar_pengumuman)): ?>
        <p style="color:var(--color-text-muted);">Belum ada pengumuman.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Tanggal</th><th>Judul</th><th>Penulis</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($daftar_pengumuman as $p): ?>
                        <tr>
                            <td><?= tanggal_indo($p['tanggal']) ?></td>
                            <td><?= h($p['judul']) ?></td>
                            <td><?= h($p['penulis'] ?: '-') ?></td>
                            <td class="table-actions">
                                <a href="?edit=<?= $p['id'] ?>" class="btn btn-light btn-sm">Ubah</a>
                                <a href="?hapus=<?= $p['id'] ?>" class="btn btn-danger btn-sm js-confirm-delete" data-confirm="Hapus pengumuman ini?">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
