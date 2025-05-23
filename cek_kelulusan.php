<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nisn'])) {
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn']);
    $siswa = getSiswaByNISN($conn, $nisn);
    $settings = getSettings($conn);
    $timeLeft = getTimeLeft($settings['tanggal_kelulusan'] ?? '');
}

// Inisialisasi variabel settings jika belum ada
$settings = $settings ?? [];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <?php include 'includes/header.php'; ?>
    <title>Pengumuman Kelulusan - <?= $settings['nama_sekolah'] ?? 'Sekolah' ?></title>
    <meta name="description"
        content="Pengumuman kelulusan resmi <?= $settings['nama_sekolah'] ?? 'Sekolah' ?> tahun <?= $settings['tahun_kelulusan'] ?? date('Y') ?>">
    <meta name="keywords" content="pengumuman kelulusan, cek kelulusan, <?= $settings['nama_sekolah'] ?? 'Sekolah' ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="assets/images/<?= $settings['logo'] ?? 'default-logo.png' ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/kelulusan.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body id="body">
    <!-- Audio untuk memutar musik latar -->
    <audio id="background-music" autoplay loop>
        <source src="/assets/mp3/17.mp3" type="audio/mpeg">
        Browser Anda tidak mendukung elemen audio.
    </audio>

    <div id="main" class="main">
        <div id="main-background" class="main-background" style="opacity: 1;"></div>
        <div id="main-route" class="main-route">
            <div id="index" class="index">
                <?php if (isset($timeLeft['is_passed']) && !$timeLeft['is_passed']): ?>
                    <div class="not-available">
                        <h3>Pengumuman kelulusan belum dimulai</h3>
                        <p>Silakan coba lagi setelah tanggal
                            <?= date('d F Y H:i', strtotime($settings['tanggal_kelulusan'] ?? '')) ?></p>
                        <a href="/" class="btn-back">Kembali ke Beranda</a>
                    </div>
                <?php elseif (isset($siswa) && $siswa): ?>
                    <div id="index-accepted" class="index-accepted">
                        <div class="index-accepted-header">
                            <img src="assets/images/<?= $settings['logo'] ?? 'default-logo.png' ?>" alt="Logo Sekolah"
                                class="index-accepted-header-icon">
                            <div class="index-accepted-header-title">
                                <h1 class="index-accepted-header-title-text">SELAMAT! ANDA DINYATAKAN LULUS</h1>
                            </div>
                        </div>
                        <div class="index-accepted-content">
                            <div class="index-accepted-content-upper">
                                <div class="index-accepted-content-upper-bio">
                                    <span class="index-accepted-content-upper-bio-nisn">NISN:
                                        <?= $siswa['nisn'] ?? '' ?></span>
                                    <span class="index-accepted-content-upper-bio-name"><?= $siswa['nama'] ?? '' ?></span>
                                    <span class="index-accepted-content-upper-bio-program">Kelas:
                                        <?= $siswa['kelas'] ?? '' ?></span>
                                    <span class="index-accepted-content-upper-bio-university">No. Absen:
                                        <?= $siswa['absen'] ?? '' ?></span>
                                </div>
                                <?php if (!empty($siswa['foto'])): ?>
                                    <img class="index-accepted-content-upper-qr" alt="Foto Profil"
                                        src="assets/uploads/<?= htmlspecialchars($siswa['foto']) ?>">
                                <?php else: ?>
                                    <img class="index-accepted-content-upper-qr" alt="Foto Default"
                                        src="assets/images/siswa/default-profile.png">
                                <?php endif; ?>
                            </div>
                            <div class="index-accepted-content-lower">
                                <div class="index-accepted-content-lower-column index-accepted-content-lower-column-25">
                                    <div class="index-accepted-content-lower-column-field">
                                        <span class="index-accepted-content-lower-column-field-caption">Tanggal Lahir</span>
                                        <span
                                            class="index-accepted-content-lower-column-field-value"><?= date('d F Y', strtotime($siswa['tanggal_lahir'] ?? '')) ?></span>
                                    </div>
                                </div>
                                <div class="index-accepted-content-lower-column index-accepted-content-lower-column-50">
                                    <div class="index-accepted-content-lower-column-note">
                                        <span class="index-accepted-content-lower-column-note-title">Informasi
                                            Kelulusan</span>
                                        <span class="index-accepted-content-lower-column-note-subtitle">Untuk informasi
                                            lebih lanjut, silakan kunjungi website resmi sekolah:</span>
                                        <a href="https://smkn1cermegresik.sch.id/" target="_blank"
                                            class="index-accepted-content-lower-column-note-link">
                                            https://smkn1cermegresik.sch.id
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="index-accepted-footer">
                            <p class="index-accepted-footer-paragraph">Status kelulusan Anda telah diverifikasi oleh pihak
                                sekolah. Selamat atas kelulusan Anda!</p>
                            <p class="index-accepted-footer-paragraph">Untuk informasi lebih lanjut mengenai kegiatan wisuda
                                dan lainnya, silakan tunggu info selanjutnya.</p>
                            <a href="/" class="index-form-content-footer-submit" style="color: #3b82f6; text-decoration: none; border: none; display: block; margin-top: 20px; background: none; padding: 0;">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="index-form animate__animated animate__fadeInUp" style="max-width: 600px; margin: 0 auto;">
                        <div class="index-form-content">
                            <div class="index-form-content-header">
                                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #e82d33; margin-bottom: 20px;"></i>
                                <h3 class="index-form-content-title" style="color: #e82d33;">Data Tidak Ditemukan</h3>
                                <span class="index-form-content-subtitle">NISN yang Anda masukkan tidak terdaftar dalam sistem kami.</span>
                            </div>
                            <div class="index-form-content-form">
                                <div class="index-form-content-form-field">
                                    <a href="/" class="index-form-content-footer-submit" style="background-color: #e82d33; border-color: #e82d33; text-decoration: none;">
                                        <i class="fas fa-redo-alt"></i> Coba Lagi
                                    </a>
                                </div>
                            </div>
                            <div class="index-form-content-footer">
                                <a href="https://smkn1cermegresik.sch.id/" class="index-form-content-footer-pdf">
                                    © 2025 | SMK NEGERI 1 CERME GRESIK
                                </a>
                            </div>
                        </div>
                        <div class="index-form-border"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <script>
        // Script untuk memastikan musik terus berputar
        document.addEventListener('DOMContentLoaded', function() {
            var audio = document.getElementById('background-music');
            audio.play();
        });
    </script>
</body>

</html>