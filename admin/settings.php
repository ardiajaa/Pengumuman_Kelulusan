<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_sekolah = mysqli_real_escape_string($conn, $_POST['nama_sekolah']);
    $tahun_kelulusan = mysqli_real_escape_string($conn, $_POST['tahun_kelulusan']);
    $tanggal_kelulusan = $_POST['tanggal_kelulusan'] . ':00'; // Tambahkan detik
    $link_sekolah = mysqli_real_escape_string($conn, $_POST['link_sekolah']);
    
    // Handle logo upload
    $logo = $_POST['existing_logo'];
    if (!empty($_FILES['logo']['name'])) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES['logo']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES['logo']['tmp_name']);
        if ($check !== false) {
            // Delete old logo if not default
            if ($logo != 'logo.png') {
                unlink($target_dir . $logo);
            }
            
            // Upload new logo
            $new_logo_name = 'logo-' . time() . '.' . $imageFileType;
            move_uploaded_file($_FILES['logo']['tmp_name'], $target_dir . $new_logo_name);
            $logo = $new_logo_name;
        }
    }
    
    $sql = "UPDATE settings SET 
            nama_sekolah = '$nama_sekolah', 
            logo = '$logo', 
            tahun_kelulusan = '$tahun_kelulusan', 
            tanggal_kelulusan = '$tanggal_kelulusan',
            link_sekolah = '$link_sekolah' 
            WHERE id = 1";
    mysqli_query($conn, $sql);
    
    header("Location: settings.php?success=1");
    exit();
}

$settings = getSettings($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Pengaturan</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
    <link rel="stylesheet href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .logo-upload:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="fixed top-0 left-0 w-full z-50">
        <?php include 'includes/admin_header.php'; ?>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 py-8 mt-32 animate__animated animate__fadeInUp">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-4 md:mb-0">
                    <i class="fas fa-cog text-blue-500 mr-3"></i>
                    Pengaturan Website
                </h1>
                <a href="dashboard.php" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition-all flex items-center">
                    <i class="fas fa-home mr-2"></i> Kembali ke Dashboard
                </a>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <i class="fas fa-check-circle mr-2"></i>
                    Pengaturan berhasil diperbarui!
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Nama Sekolah -->
                <div class="space-y-2">
                    <label for="nama_sekolah" class="block text-sm font-medium text-gray-700">Nama Sekolah</label>
                    <input type="text" id="nama_sekolah" name="nama_sekolah" value="<?= $settings['nama_sekolah'] ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                </div>

                <!-- Logo Sekolah -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Logo Sekolah</label>
                    <div class="logo-upload p-4 bg-gray-50 rounded-lg transition-all">
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                            <div class="w-32 h-32 rounded-lg shadow-md flex items-center justify-center bg-white p-2">
                                <img src="../assets/images/<?= $settings['logo'] ?>" alt="Logo Sekolah" 
                                     class="max-w-full max-h-full object-contain" id="logoPreview">
                            </div>
                            <div class="flex-1">
                                <input type="file" id="logo" name="logo" accept="image/*" 
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <input type="hidden" name="existing_logo" value="<?= $settings['logo'] ?>">
                                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tahun Kelulusan -->
                <div class="space-y-2">
                    <label for="tahun_kelulusan" class="block text-sm font-medium text-gray-700">Tahun Kelulusan</label>
                    <input type="text" id="tahun_kelulusan" name="tahun_kelulusan" value="<?= $settings['tahun_kelulusan'] ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                </div>

                <!-- Tanggal Pengumuman -->
                <div class="space-y-2">
                    <label for="tanggal_kelulusan" class="block text-sm font-medium text-gray-700">Tanggal & Jam Pengumuman</label>
                    <input type="datetime-local" id="tanggal_kelulusan" name="tanggal_kelulusan" 
                           value="<?= date('Y-m-d\TH:i', strtotime($settings['tanggal_kelulusan'])) ?>" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                </div>

                <!-- Link Sekolah -->
                <div class="space-y-2">
                    <label for="link_sekolah" class="block text-sm font-medium text-gray-700">Link Website Sekolah</label>
                    <input type="url" id="link_sekolah" name="link_sekolah" 
                           value="<?= htmlspecialchars($settings['link_sekolah'] ?? '') ?>" 
                           placeholder="https://smkn1cermegresik.sch.id/" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                </div>

                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit" 
                            class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>