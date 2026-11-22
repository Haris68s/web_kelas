<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../vendor/autoload.php';
wajib_login();


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->safeload();

$judul_halaman = 'Galeri Kelas';
$folder_upload = __DIR__ . '/../uploads/galeri/';
pastikan_folder($folder_upload);

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $pdo->prepare('SELECT gambar FROM galeri WHERE id = ?');
    $stmt->execute([$id]);
    $lama = $stmt->fetchColumn();
    
    $pdo->prepare('DELETE FROM galeri WHERE id = ?')->execute([$id]);
    $_SESSION['flash_success'] = 'Foto berhasil dihapus.';
    header('Location: galeri.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $API_IMGBB = $_ENV['API_IMGBB'] ?? '';

    $cloud_name = $_ENV['CLAUDINARY_CLOUD_NAME'] ?? '';
    $upload_preset = $_ENV['CLAUDINARY_UPLOAD_PRESET'] ?? '';
 
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
    $ekstensi_gambar = ['jpg', 'jpeg', 'png', 'webp'];
    $extensi_video = ['mp4', 'webm', 'mov', 'avi', 'mkv'];

    if (in_array($ext, $ekstensi_gambar, true)) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
    $nama_file = base64_encode(file_get_contents($file_tmp));

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,'https://api.imgbb.com/1/upload?key=' . $API_IMGBB);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,['image'=> $nama_file]);
    $result = curl_exec($ch);
    curl_close($ch);

    $respon = json_decode($result,true);

    if(isset($respon['success']) && $respon['success'] === true){
        $url_gambar = str_replace('https://i.ibb.co/','https://i.ibb.co.com/',$respon['data']['url']);
        $url_tampilan = str_replace('https://i.ibb.co/','https://i.ibb.co.com/',$respon['data']['display_url']);
        $delete_url = str_replace('https://i.ibb.co/','https://i.ibb.co.com/',$respon['data']['delete_url']);
        $stmt = $pdo->prepare('INSERT INTO galeri (judul,gambar,url_tampilan,delete_url,keterangan) VALUES(?,?,?,?,?)');
        $stmt->execute([$judul,$url_gambar,$url_tampilan,$delete_url,$keterangan]);
        $_SESSION['flash_success'] = 'foto berhasil di upload';

    }else{
        echo "gagal mengunggah gambar ke imgbb. Pesan: " . ($respon['error']['message'] ?? 'Tidak ada pesan error.');
        exit;
    }
    }else

     if(in_array($ext,$extensi_video,true)){
    $file_tmp = $_FILES['gambar']['tmp_name'];
    $mime = $_FILES['gambar']['type'];
    $file_name = $_FILES['gambar']['name'];

    $cfile = new CURLFile($file_tmp, $mime, $file_name);

    $url_cloudinary = "https://api.cloudinary.com/v1_1/" . $cloud_name . "/video/upload";

     $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_cloudinary);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file'          => $cfile,
            'upload_preset' => $upload_preset
        ]);

        $result = curl_exec($ch);
        curl_close($ch);
        $respon = json_decode($result,true);
        

        if (isset($respon['secure_url'])) {
            $url_video = $respon['secure_url'];
            $public_id = $respon['public_id'];

            // Simpan data video ke database
            $stmt = $pdo->prepare('INSERT INTO galeri (judul, tipe_media, gambar, url_tampilan, public_id, keterangan) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$judul, 'video', $url_video, $url_video, $public_id, $keterangan]);

            $_SESSION['flash_success'] = 'Video berhasil diunggah ke galeri!';
        } else {
            $_SESSION['flash_error'] = 'Gagal upload video: ' . ($respon['error']['message'] ?? 'Unknown Error');
        }

        

    }else{
        $_SESSION['flash_error'] = 'Format file tidak didukung. Harap unggah file gambar (JPG, PNG, WEBP) atau video (MP4, WEBM, MOV, AVI, MKV).';
        header('Location: galeri.php');
        exit;
    }
   
    

    header('Location: galeri.php');
    exit;
}

$daftar_galeri = $pdo->query('SELECT * FROM galeri ORDER BY dibuat_pada DESC')->fetchAll();

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-panel">
    <div class="admin-panel-head"><h2>Tambah Foto ke Galeri</h2></div>

    <form method="post" enctype="multipart/form-data" autocomplete="off" >
        <div class="form-row">
            <div class="form-group">
                <label>Judul Foto (opsional)</label>
                <input type="text" name="judul" class="form-control" placeholder="Contoh: Study Tour 2026">
            </div>
            <div class="form-group">
                <label>File Foto</label>
                <input type="file" name="gambar" class="form-control js-preview-input" accept="image/*,video/mp4/webm,video/quicktime" required data-preview-target="previewGaleri">
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
                    <?php if ($g['tipe_media'] === 'video'): ?>
                        <video controls style="width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                            <source src="<?= $g['gambar'] ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <?php else:?>
                    <img src="<?=  $g['gambar']?>" style="width:100%; aspect-ratio:4/3; object-fit:cover; border-radius:8px; margin-bottom:10px;">
                    <?php endif;?>
                    <p style="font-size:0.85rem; font-weight:600;"><?= h($g['judul'] ?: 'Tanpa judul') ?></p>
                    <?php $tombol_hapus = !empty($g['delete_url']) ? str_replace('https://i.ibb.co/','https://i.ibb.co.com/',$g['delete_url']) : ''; ?>
                    <a href="?hapus=<?= $g['id'] ?>" onclick="return deleteFoto(event,'<?= $tombol_hapus ?>','?hapus=<?= $g['id'] ?>')" class="btn btn-danger btn-sm btn-block js-confirm-delete" data-confirm="Hapus foto ini?" style="margin-top:8px;">Hapus</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
