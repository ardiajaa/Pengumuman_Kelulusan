<?php
$current_page = basename($_SERVER['PHP_SELF']);
// Pastikan koneksi database ($conn) tersedia di sini jika getSettings membutuhkannya
// require_once 'path/to/your/db_connection.php'; // Contoh
// require_once 'path/to/your/settings_function.php'; // Contoh fungsi getSettings
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title> <!-- Tambahkan judul -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Animasi */
        @keyframes slideIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(32, 201, 151, 0.3); } /* Warna glow baru */
            50% { box-shadow: 0 0 20px rgba(32, 201, 151, 0.5); } /* Warna glow baru */
            100% { box-shadow: 0 0 5px rgba(32, 201, 151, 0.3); } /* Warna glow baru */
        }

        /* Gaya Navigasi Desktop */
        .nav-link {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #20c997, #17a2b8); /* Warna underline baru */
            transition: width 0.3s ease;
        }
        .nav-link:hover {
            transform: translateY(-3px);
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .active-nav {
            background: linear-gradient(90deg, rgba(32, 201, 151, 0.1), rgba(23, 162, 184, 0.1)); /* Warna background aktif baru */
            border-radius: 0.5rem;
            animation: glow 2s infinite;
        }

        /* Gaya Menu Mobile */
        .mobile-menu {
            animation: slideIn 0.3s ease-out;
        }

        /* Gaya Logo */
        .logo-hover {
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logo-hover:hover {
            transform: rotate(-10deg) scale(1.1);
            box-shadow: 0 0 20px rgba(32, 201, 151, 0.5); /* Warna shadow hover logo baru */
        }
    </style>
</head>
<body class="bg-gray-100"> <!-- Tambahkan background body -->
<nav class="bg-white/95 backdrop-blur-md shadow-xl fixed w-full z-50" style="animation: slideIn 0.5s ease-out">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24 items-center">
            <!-- Logo Section -->
            <div class="flex items-center space-x-4">
                <div class="p-2 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl shadow-md"> <!-- Warna background logo baru -->
                    <img src="../assets/images/<?= getSettings($conn)['logo'] ?? 'default_logo.png' ?>" alt="Logo Sekolah"
                        class="rounded-lg logo-hover" style="max-height: 60px; width: auto;">
                </div>
                <div>
                    <a href="dashboard.php" class="text-2xl font-bold text-gray-800 bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent hover:opacity-80 transition-opacity">
                        Admin Panel
                    </a>
                    <p class="text-sm text-gray-500 mt-1"><?= getSettings($conn)['nama_sekolah'] ?? 'Nama Sekolah' ?></p> <!-- Tambahkan fallback -->
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="dashboard.php"
                    class="nav-link <?= $current_page == 'dashboard.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2"> <!-- Warna teks aktif/hover baru -->
                    <i class="fas fa-tachometer-alt text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="siswa.php"
                    class="nav-link <?= $current_page == 'siswa.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2"> <!-- Warna teks aktif/hover baru -->
                    <i class="fas fa-users text-lg"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="import.php"
                    class="nav-link <?= $current_page == 'import.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2"> <!-- Warna teks aktif/hover baru -->
                    <i class="fas fa-file-import text-lg"></i>
                    <span>Import Data</span>
                </a>
                <a href="settings.php"
                    class="nav-link <?= $current_page == 'settings.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2"> <!-- Warna teks aktif/hover baru -->
                    <i class="fas fa-cog text-lg"></i>
                    <span>Pengaturan</span>
                </a>
                <a href="../logout.php"
                    class="flex items-center px-5 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all duration-300
                    hover:shadow-[0_0_15px_rgba(239,68,68,0.3)] space-x-2">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                    <span>Logout</span>
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center md:hidden">
                <button type="button" class="mobile-menu-button p-3 rounded-lg text-gray-500 hover:text-gray-700 focus:outline-none
                    transition duration-300 hover:bg-gray-100">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu hidden md:hidden bg-white/95 backdrop-blur-md shadow-xl">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="dashboard.php"
                class="<?= $current_page == 'dashboard.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2"> <!-- Warna background/teks aktif mobile baru -->
                <i class="fas fa-tachometer-alt text-lg"></i>
                <span>Dashboard</span>
            </a>
            <a href="siswa.php"
                class="<?= $current_page == 'siswa.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2"> <!-- Warna background/teks aktif mobile baru -->
                <i class="fas fa-users text-lg"></i>
                <span>Data Siswa</span>
            </a>
            <a href="import.php"
                class="<?= $current_page == 'import.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2"> <!-- Warna background/teks aktif mobile baru -->
                <i class="fas fa-file-import text-lg"></i>
                <span>Import Data</span>
            </a>
            <a href="settings.php"
                class="<?= $current_page == 'settings.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2"> <!-- Warna background/teks aktif mobile baru -->
                <i class="fas fa-cog text-lg"></i>
                <span>Pengaturan</span>
            </a>
            <a href="../logout.php"
                class="block px-4 py-3 rounded-lg text-base font-medium text-red-600 hover:bg-red-50 transition duration-300 transform hover:translate-x-2 flex items-center space-x-2">
                <i class="fas fa-sign-out-alt text-lg"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>

<!-- Font Awesome Icons -->
<!-- Pastikan mengganti 'your-fontawesome-kit.js' dengan kit Anda -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>