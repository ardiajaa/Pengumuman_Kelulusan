<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
redirectIfNotLoggedIn();

$admin = getCurrentAdmin($conn);
$login_history = getLoginHistory($conn, $admin['id']);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        
        $query = "UPDATE admin SET nama = '$nama', email = '$email' WHERE id = {$admin['id']}";
        if (mysqli_query($conn, $query)) {
            $_SESSION['admin_nama'] = $nama;
            $_SESSION['admin_email'] = $email;
            $success = 'Profil berhasil diperbarui!';
            $admin = getCurrentAdmin($conn);
        } else {
            $error = 'Gagal memperbarui profil: ' . mysqli_error($conn);
        }
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (password_verify($current_password, $admin['password'])) {
            if ($new_password === $confirm_password) {
                if (strlen($new_password) >= 8) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $query = "UPDATE admin SET password = '$hashed_password' WHERE id = {$admin['id']}";
                    
                    if (mysqli_query($conn, $query)) {
                        $success = 'Password berhasil diubah!';
                    } else {
                        $error = 'Gagal mengubah password: ' . mysqli_error($conn);
                    }
                } else {
                    $error = 'Password baru harus minimal 8 karakter!';
                }
            } else {
                $error = 'Password baru dan konfirmasi password tidak cocok!';
            }
        } else {
            $error = 'Password saat ini salah!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Profil Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .profile-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
        
        .history-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .history-item:hover {
            transform: translateX(5px);
            border-left-color: #3b82f6;
        }
        
        .device-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #eef2ff;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="fixed top-0 left-0 w-full z-50">
        <?php include 'includes/admin_header.php'; ?>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8 mt-32 animate__animated animate__fadeInUp">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-1/3">
                <div class="profile-card p-6 shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 rounded-full bg-blue-100 mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-user text-blue-500 text-3xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($admin['nama']) ?></h2>
                        <p class="text-gray-600"><?= htmlspecialchars($admin['email']) ?></p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Terakhir Login</h3>
                            <p class="text-gray-800">
                                <?= $admin['terakhir_login'] ? date('d M Y H:i', strtotime($admin['terakhir_login'])) : 'Belum pernah login' ?>
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Akun Dibuat</h3>
                            <p class="text-gray-800">
                                <?= date('d M Y', strtotime($admin['dibuat_pada'])) ?>
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Sesi Saat Ini</h3>
                            <p class="text-gray-800">
                                <?= getDeviceInfo($_SERVER['HTTP_USER_AGENT']) ?>
                            </p>
                            <p class="text-sm text-gray-600">
                                IP: <?= $_SERVER['REMOTE_ADDR'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Edit Profil Section -->
            <div class="w-full md:w-2/3 space-y-6">
                <?php if ($error): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= $success ?>
                    </div>
                <?php endif; ?>
                
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-edit text-blue-500 mr-2"></i>
                        Edit Profil
                    </h2>
                    
                    <form method="POST">
                        <div class="space-y-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($admin['nama']) ?>"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit" name="update_profile"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-lock text-blue-500 mr-2"></i>
                        Ganti Password
                    </h2>
                    
                    <form method="POST">
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input type="password" id="new_password" name="new_password" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                            </div>
                            
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                <input type="password" id="confirm_password" name="confirm_password" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit" name="change_password"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                    <i class="fas fa-key mr-2"></i> Ganti Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-history text-blue-500 mr-2"></i>
                        Riwayat Login
                    </h2>
                    
                    <div class="space-y-3">
                        <?php foreach ($login_history as $history): ?>
                            <div class="history-item p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <div class="device-icon">
                                        <?php if (strpos($history['perangkat'], 'Mobile') !== false): ?>
                                            <i class="fas fa-mobile-alt text-blue-500"></i>
                                        <?php elseif (strpos($history['perangkat'], 'Tablet') !== false): ?>
                                            <i class="fas fa-tablet-alt text-blue-500"></i>
                                        <?php else: ?>
                                            <i class="fas fa-desktop text-blue-500"></i>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-medium text-gray-800">
                                                <?= htmlspecialchars($history['perangkat']) ?>
                                            </h3>
                                            <span class="text-xs text-gray-500">
                                                <?= date('d M Y H:i', strtotime($history['waktu_login'])) ?>
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <?= htmlspecialchars($history['lokasi']) ?>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            IP: <?= htmlspecialchars($history['ip_address']) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($login_history)): ?>
                            <p class="text-gray-500 text-center py-4">Belum ada riwayat login</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>