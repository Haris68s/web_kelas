<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_login();

$judul_halaman = 'Materi & Tugas';
$folder_upload = __DIR__ . '/../uploads/materi/';
pastikan_folder($folder_upload);

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $pdo->prepare('SELECT file FROM materi WHERE id = ?');
    $stmt->execute([$id]);
    $lama = $stmt->fetchColumn();
    if ($lama && file_exists($folder_upload . $lama)) {
        unlink($folder_upload . $lama);
    }
    $pdo->prepare('DELETE FROM materi WHERE id = ?')->execute([$id]);
    $_SESSION['flash_success'] = 'Materi/tugas berhasil dihapus.';
    header('Location: materi.php');
    exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM materi WHERE id = ?');
    $stmt->execute([(int) $_GET['edit']]);
    $edit_data = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $judul = trim($_POST['judul'] ?? '');
    $kategori = $_POST['kategori'] ?? 'Materi';
    $mapel = trim($_POST['mata_pelajaran'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $batas_waktu = $_POST['batas_waktu'] ?: null;

    if ($judul === '') {
        $_SESSION['flash_error'] = 'Judul wajib diisi.';
        header('Location: materi.php');
        exit;
    }

    $nama_file = $_POST['file_lama'] ?? null;

    if (!empty($_FILES['file']['name'])) {
        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = pesan_error_upload($_FILES['file']['error']);
            header('Location: materi.php');
            exit;
        }

        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $ekstensi_diizinkan = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'zip', 'jpg', 'jpeg', 'png'];

        if (!in_array($ext, $ekstensi_diizinkan, true)) {
            $_SESSION['flash_error'] = 'Format file tidak didukung.';
            header('Location: materi.php');
            exit;
        }

        $nama_file_baru = 'materi_' . time() . '_' . rand(100, 999) . '.' . $ext;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $folder_upload . $nama_file_baru)) {
            if (!empty($nama_file) && file_exists($folder_upload . $nama_file)) {
                unlink($folder_upload . $nama_file);
            }
            $nama_file = $nama_file_baru;
        } else {
            $_SESSION['flash_error'] = 'Gagal menyimpan file ke server. Pastikan folder uploads/materi ada dan bisa ditulis.';
            header('Location: materi.php');
            exit;
        }
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE materi SET judul=?, kategori=?, mata_pelajaran=?, deskripsi=?, file=?, batas_waktu=? WHERE id=?');
        $stmt->execute([$judul, $kategori, $mapel, $deskripsi, $nama_file, $batas_waktu, $id]);
        $_SESSION['flash_success'] = 'Materi/tugas berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO materi (judul, kategori, mata_pelajaran, deskripsi, file, batas_waktu) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$judul, $kategori, $mapel, $deskripsi, $nama_file, $batas_waktu]);
        $_SESSION['flash_success'] = 'Materi/tugas baru berhasil ditambahkan.';
    }

    header('Location: materi.php');
    exit;
}

$daftar_materi = $pdo->query('SELECT * FROM materi ORDER BY dibuat_pada DESC')->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="admin-panel-head">
        <h2><?= $edit_data ? 'Ubah Materi/Tugas' : 'Tambah Materi/Tugas' ?></h2>
        <?php if ($edit_data): ?><a href="materi.php" class="btn btn-light btn-sm">Batal Ubah</a><?php endif; ?>
    </div>

    <form method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="id" value="<?= (int) ($edit_data['id'] ?? 0) ?>">
        <input type="hidden" name="file_lama" value="<?= h($edit_data['file'] ?? '') ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" required value="<?= h($edit_data['judul'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" class="form-control">
                    <option value="Materi" <?= ($edit_data['kategori'] ?? '') === 'Materi' ? 'selected' : '' ?>>Materi</option>
                    <option value="Tugas" <?= ($edit_data['kategori'] ?? '') === 'Tugas' ? 'selected' : '' ?>>Tugas</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="form-control" value="<?= h($edit_data['mata_pelajaran'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Batas Waktu (khusus Tugas)</label>
                <input type="date" name="batas_waktu" class="form-control" value="<?= h($edit_data['batas_waktu'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"><?= h($edit_data['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>File (PDF/Word/PPT/Excel/ZIP/Gambar)</label>
            <input type="file" name="file" class="form-control">
            <?php if (!empty($edit_data['file'])): ?>
                <p style="font-size:0.82rem; color:var(--color-text-muted); margin-top:6px;">File saat ini: <?= h($edit_data['file']) ?></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary"><?= $edit_data ? 'Simpan Perubahan' : 'Tambah' ?></button>
    </form>
</div>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Daftar Materi & Tugas (<?= count($daftar_materi) ?>)</h2></div>

    <?php if (empty($daftar_materi)): ?>
        <p style="color:var(--color-text-muted);">Belum ada materi atau tugas.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Judul</th><th>Kategori</th><th>Mapel</th><th>File</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($daftar_materi as $m): ?>
                        <tr>
                            <td><?= h($m['judul']) ?></td>
                            <td><span class="badge <?= $m['kategori'] === 'Tugas' ? 'badge-accent' : '' ?>"><?= h($m['kategori']) ?></span></td>
                            <td><?= h($m['mata_pelajaran'] ?: '-') ?></td>
                            <td><?= $m['file'] ? '✅' : '—' ?></td>
                            <td class="table-actions">
                                <a href="?edit=<?= $m['id'] ?>" class="btn btn-light btn-sm">Ubah</a>
                                <a href="?hapus=<?= $m['id'] ?>" class="btn btn-danger btn-sm js-confirm-delete" data-confirm="Hapus item ini?">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
