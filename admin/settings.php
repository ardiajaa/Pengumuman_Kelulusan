<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
redirectIfNotLoggedIn();

$settings = getSettings($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_sekolah = mysqli_real_escape_string($conn, $_POST['nama_sekolah']);
    $tahun_kelulusan = mysqli_real_escape_string($conn, $_POST['tahun_kelulusan']);
    $tanggal_kelulusan_input = $_POST['tanggal_kelulusan'];
    $tanggal_kelulusan = date('Y-m-d H:i:s', strtotime($tanggal_kelulusan_input));

    $link_sekolah = mysqli_real_escape_string($conn, $_POST['link_sekolah']);

    $logo = $settings['logo'] ?? 'logo.png';

    if (!empty($_FILES['logo']['name'])) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES['logo']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES['logo']['tmp_name']);
        if ($check !== false) {
            if ($logo != 'logo.png' && file_exists($target_dir . $logo)) {
                unlink($target_dir . $logo);
            }

            $new_logo_name = 'logo-' . time() . '.' . $imageFileType;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_dir . $new_logo_name)) {
                $logo = $new_logo_name; // Update variabel $logo hanya jika upload berhasil
            } else {
            }
        } else {
        }
    }


    $background = $settings['background_image'] ?? 'default-bg.jpg';
    $background_sound = $settings['background_sound'] ?? 'sound.mp3';

    if (!empty($_FILES['background_image']['name'])) {
        $target_dir = "../assets/images/backgrounds/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES['background_image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($_FILES['background_image']['tmp_name']) !== false) {
            if (!empty($background) && $background != 'default-bg.jpg' && file_exists($target_dir . $background)) {
                unlink($target_dir . $background);
            }

            $new_bg_name = 'bg-' . time() . '.' . $imageFileType;
            if (move_uploaded_file($_FILES['background_image']['tmp_name'], $target_dir . $new_bg_name)) {
                $background = $new_bg_name;
            } else {
            }
        } else {
        }
    }

    if (!empty($_FILES['background_sound']['name'])) {
        $target_dir = "../assets/mp3/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES['background_sound']['name']);
        $audioFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file adalah audio
        if ($audioFileType == "mp3" || $audioFileType == "wav") {
            if (!empty($background_sound) && $background_sound != 'sound.mp3' && file_exists($target_dir . $background_sound)) {
                unlink($target_dir . $background_sound);
            }

            $new_sound_name = 'sound-' . time() . '.' . $audioFileType;
            if (move_uploaded_file($_FILES['background_sound']['tmp_name'], $target_dir . $new_sound_name)) {
                $background_sound = $new_sound_name;
            }
        }
    }

    $sql = "UPDATE settings SET 
            nama_sekolah = ?, 
            logo = ?, 
            tahun_kelulusan = ?, 
            tanggal_kelulusan = ?,
            link_sekolah = ?,
            background_image = ?,
            background_sound = ? 
            WHERE id = 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $nama_sekolah, $logo, $tahun_kelulusan, $tanggal_kelulusan, $link_sekolah, $background, $background_sound);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: settings.php?success=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $settings = getSettings($conn);
}

$settings['nama_sekolah'] = $settings['nama_sekolah'] ?? '';
$settings['logo'] = $settings['logo'] ?? 'logo.png';
$settings['tahun_kelulusan'] = $settings['tahun_kelulusan'] ?? '';
$settings['tanggal_kelulusan'] = date('Y-m-d\TH:i', strtotime($settings['tanggal_kelulusan'] ?? date('Y-m-d H:i:s', strtotime('+1 day'))));
$settings['link_sekolah'] = $settings['link_sekolah'] ?? '';
$settings['background_image'] = $settings['background_image'] ?? 'default-bg.jpg';
$settings['background_sound'] = $settings['background_sound'] ?? 'sound.mp3';


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <?php include '../includes/header.php'; ?>
    <title>Pengaturan Website</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
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

        .bg-preview {
            width: 100%;
            max-width: 500px;
            height: 200px;
            background-size: cover;
            background-position: center;
            border: 2px dashed #ddd;
            margin-bottom: 15px;
            background-repeat: no-repeat;
            display: block;
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
                <a href="dashboard.php"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition-all flex items-center">
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
                <div class="space-y-2">
                    <label for="nama_sekolah" class="block text-sm font-medium text-gray-700">Nama Sekolah</label>
                    <input type="text" id="nama_sekolah" name="nama_sekolah"
                        value="<?= htmlspecialchars($settings['nama_sekolah']) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Logo Sekolah</label>
                    <div class="logo-upload p-4 bg-gray-50 rounded-lg transition-all">
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                            <div class="w-32 h-32 rounded-lg shadow-md flex items-center justify-center bg-white p-2">
                                <img src="../assets/images/<?= htmlspecialchars($settings['logo']) ?>"
                                    alt="Logo Sekolah" class="max-w-full max-h-full object-contain" id="logoPreview">
                            </div>
                            <div class="flex-1">
                                <input type="file" id="logo" name="logo" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <input type="hidden" name="existing_logo"
                                    value="<?= htmlspecialchars($settings['logo']) ?>">
                                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Background Website</label>
                    <div class="background-upload p-4 bg-gray-50 rounded-lg transition-all">
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                            <div
                                class="w-48 h-32 rounded-lg shadow-md flex items-center justify-center bg-white overflow-hidden border border-gray-200">
                                <div class="bg-preview w-full h-full bg-cover bg-center"
                                    style="background-image: url('../assets/images/backgrounds/<?= htmlspecialchars($settings['background_image']) ?>')"
                                    id="bgPreview">
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 mb-2">Pilih gambar background baru:</p>
                                <input type="file" id="background_image" name="background_image" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <input type="hidden" name="existing_background"
                                    value="<?= htmlspecialchars($settings['background_image']) ?>">
                                <p class="text-xs text-gray-500 mt-2">Ukuran rekomendasi: 1920x1080 px (format JPG/PNG).
                                    Maksimal 5MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Background Sound</label>
                    <div class="sound-upload p-4 bg-gray-50 rounded-lg transition-all">
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                            <div class="w-full md:w-1/2">
                                <audio controls class="w-full">
                                    <source src="../assets/mp3/<?= htmlspecialchars($settings['background_sound']) ?>" type="audio/mpeg">
                                    Browser Anda tidak mendukung elemen audio.
                                </audio>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700 mb-2">Pilih file audio baru:</p>
                                <input type="file" id="background_sound" name="background_sound" accept="audio/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <input type="hidden" name="existing_sound" value="<?= htmlspecialchars($settings['background_sound']) ?>">
                                <p class="text-xs text-gray-500 mt-2">Format: MP3, WAV. Maksimal 5MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="tahun_kelulusan" class="block text-sm font-medium text-gray-700">Tahun Kelulusan</label>
                    <input type="text" id="tahun_kelulusan" name="tahun_kelulusan"
                        value="<?= htmlspecialchars($settings['tahun_kelulusan']) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required>
                </div>

                <div class="space-y-2">
                    <label for="tanggal_kelulusan" class="block text-sm font-medium text-gray-700">Tanggal & Jam
                        Pengumuman</label>
                    <input type="datetime-local" id="tanggal_kelulusan" name="tanggal_kelulusan"
                        value="<?= htmlspecialchars($settings['tanggal_kelulusan']) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required>
                </div>

                <div class="space-y-2 mb-1">
                    <label for="link_sekolah" class="block text-sm font-medium text-gray-700">Link Website
                        Sekolah</label>
                    <input type="url" id="link_sekolah" name="link_sekolah"
                        value="<?= htmlspecialchars($settings['link_sekolah']) ?>"
                        placeholder="https://smkn1cermegresik.sch.id/"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required>
                </div>

                <div class="pt-1">
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
    <script>
        document.getElementById('background_image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    document.getElementById('bgPreview').style.backgroundImage = `url(${event.target.result})`;
                }
                reader.readAsDataURL(file);
            } else {
            }
        });

        document.getElementById('logo').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    document.getElementById('logoPreview').src = event.target.result;
                }
                reader.readAsDataURL(file);
            } else {
            }
        });

        // Tambahkan event listener untuk background sound
        document.getElementById('background_sound').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const audio = document.querySelector('.sound-upload audio');
                const source = audio.querySelector('source');
                const url = URL.createObjectURL(file);
                source.src = url;
                audio.load(); // Reload audio element
            }
        });
    </script>
</body>

</html>