<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'smk_kelulusan';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Buat tabel jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(10) NOT NULL,
    absen INT NOT NULL,
    status ENUM('Lulus', 'Tidak Lulus') NOT NULL
)";

$sql2 = "CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(100) NOT NULL,
    logo VARCHAR(100) NOT NULL,
    tahun_kelulusan VARCHAR(10) NOT NULL,
    tanggal_kelulusan DATETIME NOT NULL
)";

mysqli_query($conn, $sql);
mysqli_query($conn, $sql2);

// Insert default settings if empty
$check = mysqli_query($conn, "SELECT COUNT(*) as total FROM settings");
$row = mysqli_fetch_assoc($check);
if ($row['total'] == 0) {
    $default = "INSERT INTO settings (nama_sekolah, logo, tahun_kelulusan, tanggal_kelulusan) 
                VALUES ('SMKN 1 CERME', 'logo.png', '2025/2026', '2026-05-20 08:00:00')";
    mysqli_query($conn, $default);
}
?>