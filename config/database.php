<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'smk_kelulusan';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Tabel siswa
$sql = "CREATE TABLE IF NOT EXISTS siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(10) NOT NULL,
    absen INT NOT NULL,
    tanggal_lahir DATE,
    status ENUM('Lulus', 'Tidak Lulus') NOT NULL,
    foto VARCHAR(255)
)";

// Tabel settings
$sql2 = "CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(100) NOT NULL,
    logo VARCHAR(100) NOT NULL,
    tahun_kelulusan VARCHAR(10) NOT NULL,
    tanggal_kelulusan DATETIME NOT NULL,
    link_sekolah VARCHAR(255),
    background_image VARCHAR(255)
)";

// Tabel admin (baru)
$sql3 = "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    terakhir_login DATETIME,
    dibuat_pada DATETIME DEFAULT CURRENT_TIMESTAMP
)";

// Tabel riwayat_login (baru)
$sql4 = "CREATE TABLE IF NOT EXISTS riwayat_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    waktu_login DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    lokasi TEXT,
    perangkat TEXT,
    FOREIGN KEY (admin_id) REFERENCES admin(id)
)";

mysqli_query($conn, $sql);
mysqli_query($conn, $sql2);
mysqli_query($conn, $sql3);
mysqli_query($conn, $sql4);

// Cek apakah tabel settings kosong
$check = mysqli_query($conn, "SELECT COUNT(*) as total FROM settings");
$row = mysqli_fetch_assoc($check);
if ($row['total'] == 0) {
    $default = "INSERT INTO settings (nama_sekolah, logo, tahun_kelulusan, tanggal_kelulusan, link_sekolah, background_image) 
                VALUES ('SMKN 1 CERME', 'logo.png', '2025/2026', '2026-05-20 08:00:00', 'https://smkn1cermegresik.sch.id/', 'default-bg.jpg')";
    mysqli_query($conn, $default);
}

// Cek apakah ada admin
$check_admin = mysqli_query($conn, "SELECT COUNT(*) as total FROM admin");
$row_admin = mysqli_fetch_assoc($check_admin);
if ($row_admin['total'] == 0) {
    // Buat admin default jika tidak ada
    $nama = 'Administrator';
    $email = 'admin@admin.com';
    $password = password_hash('mahameru', PASSWORD_DEFAULT);
    
    $default_admin = "INSERT INTO admin (nama, email, password) 
                     VALUES ('$nama', '$email', '$password')";
    mysqli_query($conn, $default_admin);
}
?>