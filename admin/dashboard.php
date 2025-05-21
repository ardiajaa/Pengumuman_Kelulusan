<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
redirectIfNotLoggedIn();

$settings = getSettings($conn);
$totalSiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa"))['total'];
$totalLulus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa WHERE status = 'Lulus'"))['total'];
$totalTidakLulus = $totalSiswa > 0 ? $totalSiswa - $totalLulus : 0;
$lulusPercentage = $totalSiswa > 0 ? ($totalLulus/$totalSiswa)*100 : 0;
$tidakLulusPercentage = $totalSiswa > 0 ? ($totalTidakLulus/$totalSiswa)*100 : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .stat-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #9333ea);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .text-gradient {
            background: linear-gradient(135deg, #1e3a8a, #1e40af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="min-h-screen flex flex-col">
        <?php include 'includes/admin_header.php'; ?>
        
        <div class="flex-1 p-8 mt-24 max-w-7xl mx-auto w-full">
            <h1 class="text-4xl font-bold text-gradient mb-8 animate__animated animate__fadeIn">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard Admin
            </h1>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="stat-card p-8">
                    <div class="flex items-center space-x-6">
                        <div class="p-4 bg-blue-50 rounded-xl">
                            <i class="fas fa-users text-blue-600 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Total Siswa</h3>
                            <p class="text-5xl font-bold text-blue-600"><?= $totalSiswa ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 bg-blue-100 rounded-full">
                            <div class="h-2 bg-blue-500 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card p-8">
                    <div class="flex items-center space-x-6">
                        <div class="p-4 bg-green-50 rounded-xl">
                            <i class="fas fa-graduation-cap text-green-600 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Lulus</h3>
                            <p class="text-5xl font-bold text-green-600"><?= $totalLulus ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 bg-green-100 rounded-full">
                            <div class="h-2 bg-green-500 rounded-full" style="width: <?= $lulusPercentage ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="stat-card p-8">
                    <div class="flex items-center space-x-6">
                        <div class="p-4 bg-red-50 rounded-xl">
                            <i class="fas fa-times-circle text-red-600 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Lulus</h3>
                            <p class="text-5xl font-bold text-red-600"><?= $totalTidakLulus ?></p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 bg-red-100 rounded-full">
                            <div class="h-2 bg-red-500 rounded-full" style="width: <?= $tidakLulusPercentage ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="gradient-bg p-8 text-white">
                <h3 class="text-3xl font-bold mb-6 flex items-center text-blue-200">
                    <i class="fas fa-info-circle mr-3 text-blue-300"></i>Informasi Pengumuman
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="glass-effect p-6 rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-white/10 rounded-lg">
                                <i class="fas fa-school text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Nama Sekolah</p>
                                <p class="text-lg font-semibold"><?= $settings['nama_sekolah'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-effect p-6 rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-white/10 rounded-lg">
                                <i class="fas fa-calendar-alt text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Tahun Kelulusan</p>
                                <p class="text-lg font-semibold"><?= $settings['tahun_kelulusan'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-effect p-6 rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-white/10 rounded-lg">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">Tanggal Pengumuman</p>
                                <p class="text-lg font-semibold"><?= date('d F Y H:i', strtotime($settings['tanggal_kelulusan'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>