<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pengumuman Kelulusan <?= $settings['nama_sekolah'] ?> <?= $settings['tahun_kelulusan'] ?></title>
    <link rel="icon" href="assets/images/<?= $settings['logo'] ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glow {
            0% {
                box-shadow: 0 0 5px rgba(32, 201, 151, 0.3);
            }

            50% {
                box-shadow: 0 0 20px rgba(32, 201, 151, 0.5);
            }

            100% {
                box-shadow: 0 0 5px rgba(32, 201, 151, 0.3);
            }
        }

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
            background: linear-gradient(90deg, #20c997, #17a2b8);
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            transform: translateY(-3px);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .active-nav {
            background: linear-gradient(90deg, rgba(32, 201, 151, 0.1), rgba(23, 162, 184, 0.1));
            border-radius: 0.5rem;
            animation: glow 2s infinite;
        }

        .mobile-menu {
            animation: slideIn 0.3s ease-out;
        }

        .logo-hover {
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo-hover:hover {
            transform: rotate(-10deg) scale(1.1);
            box-shadow: 0 0 20px rgba(32, 201, 151, 0.5);
        }

        /* Custom Modal Styles */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.6);
            transition: opacity 0.3s ease-out;
        }

        .modal-content {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-gray-100">
    <nav class="bg-white/95 backdrop-blur-md shadow-xl fixed w-full z-50" style="animation: slideIn 0.5s ease-out">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <div class="flex items-center space-x-4">
                    <div class="p-2 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl shadow-md transform transition-transform duration-300 hover:scale-105 hover:shadow-lg active:scale-95 active:shadow-inner cursor-pointer"
                        onclick="window.location.reload()">
                        <img src="../assets/images/<?= getSettings($conn)['logo'] ?? 'default_logo.png' ?>"
                            alt="Logo Sekolah" class="rounded-lg" style="max-height: 60px; width: auto;">
                    </div>
                    <div>
                        <a href="dashboard.php"
                            class="text-2xl font-bold text-gray-800 bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent hover:opacity-80 transition-opacity">
                            Admin Panel
                        </a>
                        <p class="text-sm text-gray-500 mt-1">
                            <?= getSettings($conn)['nama_sekolah'] ?? 'Nama Sekolah' ?>
                        </p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="dashboard.php" class="nav-link <?= $current_page == 'dashboard.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2">
                        <i class="fas fa-tachometer-alt text-lg"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="siswa.php" class="nav-link <?= $current_page == 'siswa.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2">
                        <i class="fas fa-users text-lg"></i>
                        <span>Data Siswa</span>
                    </a>
                    <a href="import.php" class="nav-link <?= $current_page == 'import.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2">
                        <i class="fas fa-file-import text-lg"></i>
                        <span>Import Data</span>
                    </a>
                    <a href="settings.php" class="nav-link <?= $current_page == 'settings.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2">
                        <i class="fas fa-cog text-lg"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="profile.php" class="nav-link <?= $current_page == 'profile.php' ? 'active-nav text-teal-700' : 'text-gray-700 hover:text-teal-600' ?>
                    px-5 py-3 text-sm font-medium flex items-center space-x-2">
                        <i class="fas fa-user-circle text-lg"></i>
                        <span>Profil</span>
                    </a>
                    <a href="../logout.php" class="logout-link flex items-center px-5 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all duration-300
                    hover:shadow-[0_0_15px_rgba(239,68,68,0.3)] space-x-2">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span>Logout</span>
                    </a>
                </div>

                <div class="flex items-center md:hidden">
                    <button type="button" class="mobile-menu-button p-3 rounded-lg text-gray-500 hover:text-gray-700 focus:outline-none
                    transition duration-300 hover:bg-gray-100">
                        <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mobile-menu hidden md:hidden bg-white/95 backdrop-blur-md shadow-xl">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="dashboard.php"
                    class="<?= $current_page == 'dashboard.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2">
                    <i class="fas fa-tachometer-alt text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="siswa.php"
                    class="<?= $current_page == 'siswa.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2">
                    <i class="fas fa-users text-lg"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="import.php"
                    class="<?= $current_page == 'import.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2">
                    <i class="fas fa-file-import text-lg"></i>
                    <span>Import Data</span>
                </a>
                <a href="settings.php"
                    class="<?= $current_page == 'settings.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2">
                    <i class="fas fa-cog text-lg"></i>
                    <span>Pengaturan</span>
                </a>
                <a href="profile.php"
                    class="<?= $current_page == 'profile.php' ? 'bg-teal-100 text-teal-700' : 'text-gray-700 hover:bg-gray-100' ?>
                block px-4 py-3 rounded-lg text-base font-medium transition duration-300 transform hover:translate-x-2 flex items-center space-x-2">
                    <i class="fas fa-user-circle text-lg"></i>
                    <span>Profil</span>
                </a>
                <a href="../logout.php"
                    class="logout-link block px-4 py-3 rounded-lg text-base font-medium text-red-600 hover:bg-red-50 transition duration-300 transform hover:translate-x-2 flex items-center space-x-2">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <div id="logoutModal"
        class="modal-overlay fixed inset-0 hidden items-center justify-center z-[100] backdrop-blur-sm bg-black/30">
        <div class="modal-content bg-white rounded-xl shadow-2xl p-8 max-w-md mx-auto w-full animate__animated">
            <div class="text-center">
                <div class="mx-auto mb-6 w-16 h-16 rounded-full bg-red-50 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-3xl text-red-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Konfirmasi Logout</h3>
                <p class="text-gray-600 mb-8">Anda akan keluar dari panel admin. Pastikan semua perubahan telah
                    disimpan.</p>
            </div>
            <div class="flex justify-center space-x-4">
                <button id="cancelLogout"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-medium transition-all duration-300 hover:-translate-y-1 hover:shadow-md flex items-center space-x-2">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </button>
                <button id="confirmLogout"
                    class="bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-8 py-3 rounded-lg font-medium transition-all duration-300 hover:-translate-y-1 hover:shadow-md flex items-center space-x-2">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Ya, Logout</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        const logoutLinks = document.querySelectorAll('.logout-link');
        const logoutModal = document.getElementById('logoutModal');
        const confirmLogoutButton = document.getElementById('confirmLogout');
        const cancelLogoutButton = document.getElementById('cancelLogout');
        let targetLogoutUrl = '';

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        logoutLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                targetLogoutUrl = this.href;
                logoutModal.classList.remove('hidden');
                logoutModal.classList.add('flex');
            });
        });

        confirmLogoutButton.addEventListener('click', () => {
            logoutModal.classList.add('hidden');
            logoutModal.classList.remove('flex');
            if (targetLogoutUrl) {
                window.location.href = targetLogoutUrl;
            }
        });

        cancelLogoutButton.addEventListener('click', () => {
            logoutModal.classList.add('hidden');
            logoutModal.classList.remove('flex');
            targetLogoutUrl = '';
        });

        logoutModal.addEventListener('click', (event) => {
            if (event.target === logoutModal) {
                logoutModal.classList.add('hidden');
                logoutModal.classList.remove('flex');
                targetLogoutUrl = '';
            }
        });
    </script>

    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>

</html>