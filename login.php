<?php
require_once 'config/auth.php';

if (isLoggedIn()) {
    header("Location: admin/dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($email, $password)) {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        $error = 'Email atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include 'includes/header.php'; ?>
    <title>Login Admin</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login Admin</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            <div class="back-link">
                <a href="index.php">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>