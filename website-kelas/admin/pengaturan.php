<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
wajib_login();

$judul_halaman = 'Pengaturan';
$folder_upload_logo = __DIR__ . '/../uploads/logo/';
pastikan_folder($folder_upload_logo);

$info = $pdo->query('SELECT * FROM info_kelas ORDER BY id DESC LIMIT 1')->fetch();

// ------- SIMPAN INFO KELAS -------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'info_kelas') {
    $nama_kelas_baru = trim($_POST['nama_kelas'] ?? '');
    $tahun_ajaran = trim($_POST['tahun_ajaran'] ?? '');
    $wali_kelas = trim($_POST['wali_kelas'] ?? '');
    $motto = trim($_POST['motto'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($nama_kelas_baru === '') {
        $_SESSION['flash_error'] = 'Nama kelas wajib diisi.';
        header('Location: pengaturan.php');
        exit;
    }

    $nama_file_logo = $info['logo'] ?? null;

    if (!empty($_FILES['logo']['name'])) {
        if ($_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = pesan_error_upload($_FILES['logo']['error']);
            header('Location: pengaturan.php');
            exit;
        }

        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

        if (!in_array($ext, $ekstensi_diizinkan, true)) {
            $_SESSION['flash_error'] = 'Format logo harus JPG, PNG, WEBP, atau SVG.';
            header('Location: pengaturan.php');
            exit;
        }

        $nama_baru = 'logo_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $folder_upload_logo . $nama_baru)) {
            if (!empty($nama_file_logo) && file_exists($folder_upload_logo . $nama_file_logo)) {
                unlink($folder_upload_logo . $nama_file_logo);
            }
            $nama_file_logo = $nama_baru;
        } else {
            $_SESSION['flash_error'] = 'Gagal menyimpan logo ke server. Pastikan folder uploads/logo ada dan bisa ditulis.';
            header('Location: pengaturan.php');
            exit;
        }
    }

    if (isset($_POST['hapus_logo']) && $_POST['hapus_logo'] === '1') {
        if (!empty($nama_file_logo) && file_exists($folder_upload_logo . $nama_file_logo)) {
            unlink($folder_upload_logo . $nama_file_logo);
        }
        $nama_file_logo = null;
    }

    if ($info) {
        $stmt = $pdo->prepare('UPDATE info_kelas SET nama_kelas=?, tahun_ajaran=?, wali_kelas=?, motto=?, deskripsi=?, logo=? WHERE id=?');
        $stmt->execute([$nama_kelas_baru, $tahun_ajaran, $wali_kelas, $motto, $deskripsi, $nama_file_logo, $info['id']]);
        $_SESSION['flash_success'] = 'Info kelas berhasil diperbarui.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO info_kelas (nama_kelas, tahun_ajaran, wali_kelas, motto, deskripsi, logo) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$nama_kelas_baru, $tahun_ajaran, $wali_kelas, $motto, $deskripsi, $nama_file_logo]);
        $_SESSION['flash_success'] = 'Info kelas berhasil disimpan.';
    }

    header('Location: pengaturan.php');
    exit;
}

// ------- GANTI PASSWORD -------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'ganti_password') {
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $password_konfirmasi = $_POST['password_konfirmasi'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM admin WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password_lama, $admin['password'])) {
        $_SESSION['flash_error'] = 'Password lama salah.';
    } elseif (strlen($password_baru) < 6) {
        $_SESSION['flash_error'] = 'Password baru minimal 6 karakter.';
    } elseif ($password_baru !== $password_konfirmasi) {
        $_SESSION['flash_error'] = 'Konfirmasi password baru tidak cocok.';
    } else {
        $hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);
        $pdo->prepare('UPDATE admin SET password = ? WHERE id = ?')->execute([$hash_baru, $admin['id']]);
        $_SESSION['flash_success'] = 'Password berhasil diganti.';
    }

    header('Location: pengaturan.php');
    exit;
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Info Kelas</h2></div>
    <p style="color:var(--color-text-muted); margin-bottom:18px; font-size:0.88rem;">Data ini tampil di halaman Beranda website.</p>

    <form method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="aksi" value="info_kelas">

        <div class="form-group">
            <label>Logo Kelas</label>
            <?php if (logo_ada($info['logo'] ?? null)): ?>
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:10px;">
                    <img src="<?= BASE_URL ?>/uploads/logo/<?= h($info['logo']) ?>" alt="Logo saat ini" style="width:64px; height:64px; object-fit:contain; border:1px solid var(--color-border); border-radius:10px; padding:4px; background:#fff;">
                    <label style="font-weight:400; display:flex; align-items:center; gap:6px; font-size:0.85rem; color:var(--color-text-muted);">
                        <input type="checkbox" name="hapus_logo" value="1"> Hapus logo ini (kembali ke huruf inisial)
                    </label>
                </div>
            <?php else: ?>
                <p style="font-size:0.85rem; color:var(--color-text-muted); margin-bottom:10px;">Belum ada logo diunggah. Saat ini yang tampil adalah huruf inisial nama kelas.</p>
            <?php endif; ?>
            <input type="file" name="logo" class="form-control" accept="image/*,.svg">
            <p style="font-size:0.78rem; color:var(--color-text-muted); margin-top:6px;">Format JPG/PNG/WEBP/SVG. Sebaiknya gambar persegi (misal 200x200px) biar tidak gepeng.</p>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nama Kelas</label>
                <input type="text" name="nama_kelas" class="form-control" required value="<?= h($info['nama_kelas'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" class="form-control" placeholder="2025/2026" value="<?= h($info['tahun_ajaran'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Wali Kelas</label>
            <input type="text" name="wali_kelas" class="form-control" value="<?= h($info['wali_kelas'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Motto Kelas</label>
            <input type="text" name="motto" class="form-control" value="<?= h($info['motto'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Deskripsi Singkat</label>
            <textarea name="deskripsi" class="form-control"><?= h($info['deskripsi'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Info Kelas</button>
    </form>
</div>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Ganti Password Admin</h2></div>

    <form method="post" style="max-width:420px;" autocomplete="off">
        <input type="hidden" name="aksi" value="ganti_password">

        <div class="form-group">
            <label>Password Lama</label>
            <input type="password" name="password_lama" class="form-control" required autocomplete="off">
        </div>
        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="password_baru" class="form-control" required minlength="6" autocomplete="new-password">
        </div>
        <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_konfirmasi" class="form-control" required minlength="6" autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary">Ganti Password</button>
    </form>
</div>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Kustomisasi Warna Website</h2></div>
    <p style="color:var(--color-text-muted); font-size:0.88rem; line-height:1.7;">
        Warna website (header, tombol, aksen) diatur lewat variabel CSS di file
        <code>assets/css/style.css</code>, di bagian paling atas (<code>:root { ... }</code>).<br>
        Ubah nilai HEX pada <code>--color-primary</code>, <code>--color-accent</code>, dsb, lalu simpan filenya —
        seluruh halaman akan otomatis mengikuti warna baru tanpa perlu mengedit file lain.
    </p>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
