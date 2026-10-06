<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_login();

$judul_halaman = 'Galeri Kelas';
$folder_upload = __DIR__ . '/../uploads/galeri/';
pastikan_folder($folder_upload);

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $pdo->prepare('SELECT gambar FROM galeri WHERE id = ?');
    $stmt->execute([$id]);
    $lama = $stmt->fetchColumn();
    if ($lama && file_exists($folder_upload . $lama)) {
        unlink($folder_upload . $lama);
    }
    $pdo->prepare('DELETE FROM galeri WHERE id = ?')->execute([$id]);
    $_SESSION['flash_success'] = 'Foto berhasil dihapus.';
    header('Location: galeri.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (empty($_FILES['gambar']['name'])) {
        $_SESSION['flash_error'] = 'Pilih foto yang ingin diunggah.';
        header('Location: galeri.php');
        exit;
    }

    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['flash_error'] = pesan_error_upload($_FILES['gambar']['error']);
        header('Location: galeri.php');
        exit;
    }

    $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $ekstensi_diizinkan, true)) {
        $_SESSION['flash_error'] = 'Format foto harus JPG, PNG, atau WEBP.';
        header('Location: galeri.php');
        exit;
    }

    $nama_file = 'galeri_' . time() . '_' . rand(100, 999) . '.' . $ext;
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $folder_upload . $nama_file)) {
        $stmt = $pdo->prepare('INSERT INTO galeri (judul, gambar, keterangan) VALUES (?,?,?)');
        $stmt->execute([$judul, $nama_file, $keterangan]);
        $_SESSION['flash_success'] = 'Foto berhasil ditambahkan ke galeri.';
    } else {
        $_SESSION['flash_error'] = 'Gagal menyimpan foto ke server. Pastikan folder uploads/galeri ada dan bisa ditulis.';
    }

    header('Location: galeri.php');
    exit;
}

$daftar_galeri = $pdo->query('SELECT * FROM galeri ORDER BY dibuat_pada DESC')->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Tambah Foto ke Galeri</h2></div>

    <form method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="form-row">
            <div class="form-group">
                <label>Judul Foto (opsional)</label>
                <input type="text" name="judul" class="form-control" placeholder="Contoh: Study Tour 2026">
            </div>
            <div class="form-group">
                <label>File Foto</label>
                <input type="file" name="gambar" class="form-control js-preview-input" accept="image/*" required data-preview-target="previewGaleri">
            </div>
        </div>

        <div class="form-group">
            <label>Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <img id="previewGaleri" src="" style="display:none; width:120px; border-radius:10px; margin-bottom:16px;">

        <button type="submit" class="btn btn-primary">Unggah Foto</button>
    </form>
</div>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Semua Foto (<?= count($daftar_galeri) ?>)</h2></div>

    <?php if (empty($daftar_galeri)): ?>
        <p style="color:var(--color-text-muted);">Belum ada foto di galeri.</p>
    <?php else: ?>
        <div class="grid grid-4">
            <?php foreach ($daftar_galeri as $g): ?>
                <div class="card" style="padding:12px;">
                    <img src="<?= foto_url('galeri', $g['gambar']) ?>" style="width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                    <p style="font-size:0.85rem; font-weight:600;"><?= h($g['judul'] ?: 'Tanpa judul') ?></p>
                    <a href="?hapus=<?= $g['id'] ?>" class="btn btn-danger btn-sm btn-block js-confirm-delete" data-confirm="Hapus foto ini?" style="margin-top:8px;">Hapus</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
