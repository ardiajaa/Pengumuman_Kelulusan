<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nisn'])) {
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn']);
    $siswa = getSiswaByNISN($conn, $nisn);
    $settings = getSettings($conn);
    $timeLeft = getTimeLeft($settings['tanggal_kelulusan']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include 'includes/header.php'; ?>
    <title>Pengumuman Kelulusan - <?= $settings['nama_sekolah'] ?></title>
    <meta name="description" content="Pengumuman kelulusan resmi <?= $settings['nama_sekolah'] ?> tahun <?= $settings['tahun_kelulusan'] ?>">
    <meta name="keywords" content="pengumuman kelulusan, cek kelulusan, <?= $settings['nama_sekolah'] ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="assets/images/<?= $settings['logo'] ?>" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/kelulusan.css">
</head>
<body id="body">
    <div id="main" class="main">
        <div id="main-background" class="main-background" style="opacity: 1;"></div>
        <div id="main-route" class="main-route">
            <div id="index" class="index">
                <?php if (!$timeLeft['is_passed']): ?>
                    <div class="not-available">
                        <h3>Pengumuman kelulusan belum dimulai</h3>
                        <p>Silakan coba lagi setelah tanggal <?= date('d F Y H:i', strtotime($settings['tanggal_kelulusan'])) ?></p>
                        <a href="index.php" class="btn-back">Kembali ke Beranda</a>
                    </div>
                <?php elseif ($siswa): ?>
                    <div id="index-accepted" class="index-accepted">
                        <div class="index-accepted-header">
                            <img src="assets/images/<?= $settings['logo'] ?>" alt="Logo Sekolah" class="index-accepted-header-icon">
                            <div class="index-accepted-header-title">
                                <h1 class="index-accepted-header-title-text">SELAMAT! ANDA DINYATAKAN LULUS</h1>
                            </div>
                        </div>
                        <div class="index-accepted-content">
                            <div class="index-accepted-content-upper">
                                <div class="index-accepted-content-upper-bio">
                                    <span class="index-accepted-content-upper-bio-nisn">NISN: <?= $siswa['nisn'] ?></span>
                                    <span class="index-accepted-content-upper-bio-name"><?= $siswa['nama'] ?></span>
                                    <span class="index-accepted-content-upper-bio-program">Kelas: <?= $siswa['kelas'] ?></span>
                                    <span class="index-accepted-content-upper-bio-university">No. Absen: <?= $siswa['absen'] ?></span>
                                </div>
                                <img class="index-accepted-content-upper-qr" alt="QR" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQarFxrSttWFj2voq5bF21aoqyUNj_6Wpeovw&s">
                            </div>
                            <div class="index-accepted-content-lower">
                                <!-- <div class="index-accepted-content-lower-column index-accepted-content-lower-column-25">
                                    <div class="index-accepted-content-lower-column-field">
                                        <span class="index-accepted-content-lower-column-field-caption">Status Kelulusan</span>
                                        <span class="index-accepted-content-lower-column-field-value <?= strtolower($siswa['status']) ?>"><?= $siswa['status'] ?></span>
                                    </div>
                                </div> -->
                                <div class="index-accepted-content-lower-column index-accepted-content-lower-column-50">
                                    <div class="index-accepted-content-lower-column-note">
                                        <span class="index-accepted-content-lower-column-note-title">Informasi Kelulusan</span>
                                        <span class="index-accepted-content-lower-column-note-subtitle">Untuk informasi lebih lanjut, silakan kunjungi website resmi sekolah:</span>
                                        <a href="https://smkn1cermegresik.sch.id/" target="_blank" class="index-accepted-content-lower-column-note-link">
                                            https://smkn1cermegresik.sch.id
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="index-accepted-footer">
                            <p class="index-accepted-footer-paragraph">Status kelulusan Anda telah diverifikasi oleh pihak sekolah. Selamat atas kelulusan Anda!</p>
                            <p class="index-accepted-footer-paragraph">Untuk informasi lebih lanjut mengenai kegiatan wisuda dan lainnya, silakan tunggu info selanjutnya.</p>
                            <a href="." class="btn-back">Kembali ke Beranda</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="not-found">
                        <h3>Data tidak ditemukan</h3>
                        <p>NISN yang Anda masukkan tidak terdaftar dalam sistem kami.</p>
                        <a href="index.php" class="btn-back">Coba Lagi</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- <?php include 'includes/footer.php'; ?> -->
    <script src="assets/js/script.js"></script>
</body>
</html>