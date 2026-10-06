<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (is_admin_login()) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM admin WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama_lengkap'];
            header('Location: ' . BASE_URL . '/admin/index.php');
            exit;
        }
        $error = 'Username atau password salah.';
    }
}

$judul_halaman = 'Login Admin';
$skeleton_type = 'login';
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-box">
        <?php if (logo_ada($info['logo'] ?? null)): ?>
            <img class="brand-mark brand-mark-img" style="width:52px; height:52px;" src="<?= BASE_URL ?>/uploads/logo/<?= h($info['logo']) ?>" alt="Logo <?= h($nama_kelas) ?>">
        <?php else: ?>
            <span class="brand-mark" style="width:52px; height:52px; font-size:1.3rem;"><?= h(mb_substr($nama_kelas, 0, 1)) ?></span>
        <?php endif; ?>
        <h1>Login Admin</h1>
        <p class="sub">Masuk untuk mengelola konten website kelas.</p>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="post" novalidate autocomplete="off">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?= h($_POST['username'] ?? '') ?>" required autofocus autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </form>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
