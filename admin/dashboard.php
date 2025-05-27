
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
        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        .info-section {
             background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
             border-radius: 12px;
             box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
             color: #ffffff;
        }
        .info-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(5px);
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .text-primary-color {
            color: #1e40af;
        }
         .text-secondary-color {
            color: #3b82f6;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <?php include 'includes/admin_header.php'; ?>
        <main class="flex-1 p-4 md:p-6 mt-28 md:mt-24 max-w-7xl mx-auto w-full">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6 md:mb-8 animate__animated animate__fadeIn">
                <i class="fas fa-tachometer-alt mr-3 text-primary-color"></i>Dashboard Admin
            </h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="card p-5 flex items-center gap-5">
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        <i class="fas fa-users text-2xl md:text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-md md:text-lg font-semibold text-gray-600">Total Siswa</h3>
                        <p class="text-3xl md:text-4xl font-bold text-blue-700"><?= $totalSiswa ?></p>
                    </div>
                </div>

                <div class="card p-5 flex items-center gap-5">
                    <div class="p-3 bg-green-100 rounded-full text-green-600">
                        <i class="fas fa-graduation-cap text-2xl md:text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-md md:text-lg font-semibold text-gray-600">Lulus</h3>
                        <p class="text-3xl md:text-4xl font-bold text-green-700"><?= $totalLulus ?></p>
                    </div>
                </div>

                <div class="card p-5 flex items-center gap-5">
                    <div class="p-3 bg-red-100 rounded-full text-red-600">
                        <i class="fas fa-times-circle text-2xl md:text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-md md:text-lg font-semibold text-gray-600">Tidak Lulus</h3>
                        <p class="text-3xl md:text-4xl font-bold text-red-700"><?= $totalTidakLulus ?></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
                 <div class="card p-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Persentase Kelulusan</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between text-sm font-medium text-gray-600 mb-1">
                                <span class="flex items-center"><i class="fas fa-graduation-cap text-green-600 mr-2"></i> Lulus</span>
                                <span><?= number_format($lulusPercentage, 1) ?>%</span>
                            </div>
                            <div class="w-full bg-green-100 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: <?= $lulusPercentage ?>%"></div>
                            </div>
                        </div>
                        <div>
                             <div class="flex items-center justify-between text-sm font-medium text-gray-600 mb-1">
                                <span class="flex items-center"><i class="fas fa-times-circle text-red-600 mr-2"></i> Tidak Lulus</span>
                                <span><?= number_format($tidakLulusPercentage, 1) ?>%</span>
                            </div>
                            <div class="w-full bg-red-100 rounded-full h-2.5">
                                <div class="bg-red-600 h-2.5 rounded-full" style="width: <?= $tidakLulusPercentage ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-50 rounded-full">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Siswa: <?= $totalSiswa ?></p>
                                <p class="text-xs text-gray-500">Data terakhir diperbarui</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center space-x-3">
                            <div class="p-2 bg-blue-50 rounded-full">
                                <i class="fas fa-info-circle text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Persentase berdasarkan data valid</p>
                                <p class="text-xs text-gray-500">Sistem terintegrasi</p>
                            </div>
                        </div>
                    </div>
                 </div>

                 <div class="card p-6 flex flex-col items-center justify-center">
                    <div class="chart-container">
                         <canvas id="kelulusanChart"></canvas>
                    </div>
                 </div>
            </div>

            <div class="info-section p-6">
                <h3 class="text-xl md:text-2xl font-bold mb-6 flex items-center text-blue-200">
                    <i class="fas fa-info-circle mr-3 text-blue-300"></i>Informasi Pengumuman
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <div class="info-item">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-school text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-200">Nama Sekolah</p>
                            <p class="text-md md:text-lg font-semibold"><?= htmlspecialchars($settings['nama_sekolah'] ?? '-') ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-calendar-alt text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-200">Tahun Kelulusan</p>
                            <p class="text-md md:text-lg font-semibold"><?= htmlspecialchars($settings['tahun_kelulusan'] ?? '-') ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="p-3 bg-white/20 rounded-lg">
                            <i class="fas fa-clock text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-200">Tanggal Pengumuman</p>
                            <p class="text-md md:text-lg font-semibold">
                                <?php
                                    if (!empty($settings['tanggal_kelulusan'])) {
                                        echo date('d F Y H:i', strtotime($settings['tanggal_kelulusan']));
                                    } else {
                                        echo '-';
                                    }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <script>
        const ctx = document.getElementById('kelulusanChart');
        if (ctx) {
             const kelulusanChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Lulus', 'Tidak Lulus'],
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: [<?= $totalLulus ?>, <?= $totalTidakLulus ?>],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.9)',
                            'rgba(239, 68, 68, 0.9)'
                        ],
                        borderColor: [
                            'rgba(255, 255, 255, 1)',
                            'rgba(255, 255, 255, 1)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                },
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((sum, current) => sum + current, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} siswa (${percentage}%)`;
                                }
                            },
                             bodyFont: {
                                size: 12
                            },
                            padding: 10
                        },
                         title: {
                            display: true,
                            text: 'Distribusi Kelulusan Siswa',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            },
                            color: '#4b5563'
                        }
                    },
                    layout: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            left: 0,
                            right: 0
                        }
                    }
                },
            });
        }
    </script>
</body>
</html>
