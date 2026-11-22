<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_login();

$judul_halaman = 'Data Siswa';
$folder_upload = __DIR__ . '/../uploads/siswa/';
pastikan_folder($folder_upload);

// ------- HAPUS -------
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $pdo->prepare('SELECT foto FROM siswa WHERE id = ?');
    $stmt->execute([$id]);
    $lama = $stmt->fetchColumn();
    if ($lama && file_exists($folder_upload . $lama)) {
        unlink($folder_upload . $lama);
    }
    $pdo->prepare('DELETE FROM siswa WHERE id = ?')->execute([$id]);
    $_SESSION['flash_success'] = 'Data siswa berhasil dihapus.';
    header('Location: siswa.php');
    exit;
}

// ------- TAMBAH / EDIT -------
$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM siswa WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit_data = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $nis = trim($_POST['nis'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? 'L';
    $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?: null;
    $alamat = trim($_POST['alamat'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');

    if ($nama === '') {
        $_SESSION['flash_error'] = 'Nama siswa wajib diisi.';
        header('Location: siswa.php');
        exit;
    }

    $nama_file_foto = $_POST['foto_lama'] ?? null;

    if (!empty($_FILES['foto']['name'])) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = pesan_error_upload($_FILES['foto']['error']);
            header('Location: siswa.php');
            exit;
        }

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $ekstensi_diizinkan, true)) {
            $_SESSION['flash_error'] = 'Format foto harus JPG, PNG, atau WEBP.';
            header('Location: siswa.php');
            exit;
        }

        $nama_file_baru = 'siswa_' . time() . '_' . rand(100, 999) . '.' . $ext;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $folder_upload . $nama_file_baru)) {
            if (!empty($nama_file_foto) && file_exists($folder_upload . $nama_file_foto)) {
                unlink($folder_upload . $nama_file_foto);
            }
            $nama_file_foto = $nama_file_baru;
        } else {
            $_SESSION['flash_error'] = 'Gagal menyimpan foto ke server. Pastikan folder uploads/siswa ada dan bisa ditulis.';
            header('Location: siswa.php');
            exit;
        }
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE siswa SET nama=?, nis=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?, alamat=?, no_hp=?, jabatan=?, foto=? WHERE id=?');
        $stmt->execute([$nama, $nis, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $no_hp, $jabatan, $nama_file_foto, $id]);
        $_SESSION['flash_success'] = 'Data siswa berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO siswa (nama, nis, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, no_hp, jabatan, foto) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->execute([$nama, $nis, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $alamat, $no_hp, $jabatan, $nama_file_foto]);
        $_SESSION['flash_success'] = 'Siswa baru berhasil ditambahkan.';
    }

    header('Location: siswa.php');
    exit;
}

$daftar_siswa = $pdo->query('SELECT * FROM siswa ORDER BY nama ASC')->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2><?= $edit_data ? 'Ubah Data Siswa' : 'Tambah Siswa Baru' ?></h2>
        <?php if ($edit_data): ?>
            <a href="siswa.php" class="btn btn-light btn-sm">Batal Ubah</a>
        <?php endif; ?>
    </div>

    <form method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="id" value="<?= (int) ($edit_data['id'] ?? 0) ?>">
        <input type="hidden" name="foto_lama" value="<?= h($edit_data['foto'] ?? '') ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required value="<?= h($edit_data['nama'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" class="form-control" value="<?= h($edit_data['nis'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control">
                    <option value="L" <?= ($edit_data['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= ($edit_data['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Jabatan di Kelas (opsional)</label>
                <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Ketua Kelas" value="<?= h($edit_data['jabatan'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="<?= h($edit_data['tempat_lahir'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="<?= h($edit_data['tanggal_lahir'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= h($edit_data['no_hp'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Foto Profil</label>
                <input type="file" name="foto" class="form-control js-preview-input" accept="image/*" data-preview-target="previewFoto">
            </div>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control"><?= h($edit_data['alamat'] ?? '') ?></textarea>
        </div>

        <?php if (!empty($edit_data['foto'])): ?>
            <div class="form-group">
                <label>Foto Saat Ini</label>
                <img id="previewFoto" src="<?= foto_url('siswa', $edit_data['foto']) ?>" style="width:80px; height:80px; border-radius:50%; object-fit:cover;">
            </div>
        <?php else: ?>
            <img id="previewFoto" src="" style="display:none;">
        <?php endif; ?>

        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Simpan Perubahan' : 'Tambah Siswa' ?></button>
    </form>
</div>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2>Daftar Siswa (<?= count($daftar_siswa) ?>)</h2>
    </div>

    <?php if (empty($daftar_siswa)): ?>
        <p style="color:var(--color-text-muted);">Belum ada data siswa.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Foto</th><th>Nama</th><th>NIS</th><th>Jabatan</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($daftar_siswa as $s): ?>
                        <tr>
                            <td><img src="<?= foto_url('siswa', $s['foto']) ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover;"></td>
                            <td><?= h($s['nama']) ?></td>
                            <td><?= h($s['nis'] ?: '-') ?></td>
                            <td><?= h($s['jabatan'] ?: '-') ?></td>
                            <td class="table-actions">
                                <a href="?edit=<?= $s['id'] ?>" class="btn btn-light btn-sm">Ubah</a>
                                <a href="?hapus=<?= $s['id'] ?>" class="btn btn-danger btn-sm js-confirm-delete" data-confirm="Hapus data <?= h($s['nama']) ?>?">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
