# Aplikasi Kelulusan Siswa

Aplikasi web komprehensif untuk mengelola dan menampilkan status kelulusan siswa dengan antarmuka admin yang lengkap dan sistem keamanan terintegrasi.

## Preview Aplikasi

![Tampilan Utama](https://github.com/ardiajaa/kelulusan/blob/main/1.png)
*Gambar 1: Tampilan utama cek kelulusan*

![Hasil Kelulusan](https://github.com/ardiajaa/kelulusan/blob/main/2.png)  
*Gambar 2: Tampilan hasil kelulusan*

![Dashboard Admin](https://github.com/ardiajaa/kelulusan/blob/main/3.png)  
*Gambar 3: Dashboard admin*

![Countdown Timer](https://github.com/ardiajaa/kelulusan/blob/main/4.png)  
*Gambar 4: Countdown timer pengumuman kelulusan*

## Fitur Utama

### Panel Admin
* **Autentikasi & Keamanan**
  - Sistem login/logout dengan session management
  - Enkripsi password menggunakan password_hash()
  - Redirect otomatis jika belum login
  - Log riwayat login dengan detail:
    * Waktu login
    * IP Address
    * User Agent
    * Lokasi geografis menggunakan API ipinfo.io
    * Jenis perangkat
  - Validasi input dan proteksi SQL injection

* **Manajemen Siswa**
  - CRUD data siswa (NISN, Nama, Kelas, Absen, Status)
  - Import data siswa dari file Excel/CSV
  - Validasi data sebelum import
  - Tampilan tabel siswa dengan fitur:
    * Pencarian
    * Filter
    * Pagination
    * Export data

* **Pengaturan Sekolah**
  - Konfigurasi informasi sekolah:
    * Nama sekolah
    * Logo sekolah (upload gambar)
    * Tahun kelulusan
    * Tanggal kelulusan
    * Link website sekolah
    * Background website (upload gambar)
    * Background music (audio upload)
      - Support for MP3 and WAV formats
      - Real-time audio preview
      - Automatic file management
      - Background music control in public interface
  - Validasi file upload (format, ukuran)
  - Preview gambar sebelum upload
  - Preview audio sebelum upload

* **Profil Admin**
  - Update profil (nama, email)
  - Ubah password dengan validasi:
    * Verifikasi password lama
    * Konfirmasi password baru
    * Validasi kekuatan password
  - Riwayat login terakhir
  - Daftar 10 riwayat login terakhir

* **Dashboard**
  - Statistik kelulusan siswa
  - Grafik persentase kelulusan
  - Card informasi:
    * Total siswa
    * Jumlah lulus
    * Jumlah tidak lulus
  - Navigasi cepat ke fitur utama

### Tampilan Publik
* Cek status kelulusan berdasarkan NISN
* Animasi modern dengan Animate.css
* Desain responsif dengan Tailwind CSS
* Background dinamis dari pengaturan admin
* Informasi sekolah otomatis dari database
* Validasi input NISN
* Tampilan hasil kelulusan dengan animasi
* Kontrol musik latar belakang dengan user preference storage

## Struktur File Penting

### Core System
* `config/`
  - `database.php`: Konfigurasi koneksi database
  - `auth.php`: Sistem autentikasi dan keamanan (termasuk integrasi API ipinfo.io)
* `includes/`
  - `functions.php`: Fungsi-fungsi utilitas
  - `admin_header.php`: Header panel admin
  - `header.php`: Header tampilan publik

### Admin Panel
* `admin/`
  - `dashboard.php`: Dashboard admin
  - `siswa.php`: Manajemen data siswa
  - `import.php`: Import data siswa
  - `settings.php`: Pengaturan sekolah
  - `profile.php`: Profil admin

### Public Interface
* `index.php`: Halaman utama cek kelulusan
* `cek_kelulusan.php`: Proses cek kelulusan
* `login.php`: Halaman login admin

### Assets
* `assets/`
  - `css/`: Stylesheet (Tailwind, custom)
  - `js/`: JavaScript (Particles.js, custom)
  - `images/`: Gambar (logo, background)
  - `mp3/`: Audio files (background music)
  - `uploads/`: File upload (logo, background)

## Teknologi yang Digunakan

### Backend
* PHP 7.4+ (Native)
* MySQL 5.7+
* Session Management
* Password Hashing
* IP Geolocation API (ipinfo.io)
* User Agent Parsing
* File Upload Handling
* Audio File Management

### Frontend
* HTML5
* Tailwind CSS 2.2+
* Animate.css 4.1+
* Font Awesome 6.4+
* JavaScript (Native)
* Particles.js
* Chart.js
* Modern UI/UX Design
* Responsive Layout
* Interactive Animations
* Audio Player Integration

## Instalasi & Konfigurasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/ardiajaa/kelulusan
   cd kelulusan
   ```

2. **Persyaratan Sistem**
   - PHP 7.4 atau lebih baru
   - MySQL 5.7 atau lebih baru
   - Web server (Apache/Nginx)
   - Composer (untuk dependensi)
   - API Key ipinfo.io (gratis di https://ipinfo.io/)

3. **Setup Database**
   - Buat database baru (contoh: `smk_kelulusan`)
   - Import file SQL: `/config/smk_kelulusan.sql`
   - Tabel yang akan dibuat:
     * `siswa`: Data siswa
     * `settings`: Pengaturan sekolah
     * `admin`: Data admin
     * `riwayat_login`: Log aktivitas login

4. **Konfigurasi Aplikasi**
   - Buka `config/database.php`
   - Sesuaikan parameter koneksi:
     ```php
     $host = 'localhost';
     $username = 'root';
     $password = '';
     $database = 'smk_kelulusan';
     ```
   - Buka `config/auth.php`
   - Tambahkan API Key dari ipinfo.io:
     ```php
     $api_key = 'YOUR_API_KEY_HERE';
     ```
   - Atur BASE_URL jika diperlukan

5. **Deploy Aplikasi**
   - Letakkan folder aplikasi di root web server
   - Set permission folder `assets/uploads` ke 755
   - Pastikan PHP extension berikut aktif:
     * mysqli
     * pdo_mysql
     * fileinfo
     * session

6. **Akses Aplikasi**
   - Tampilan publik: `http://localhost/kelulusan/`
   - Panel admin: `http://localhost/kelulusan/admin/`
   - Login admin default:
     * Email: admin@admin.com
     * Password: mahameru

## Keamanan Sistem

* Enkripsi password dengan password_hash()
* Proteksi SQL injection menggunakan prepared statements
* Validasi input data
* Session management dengan timeout
* Log aktivitas login dengan deteksi lokasi menggunakan API ipinfo.io
* Deteksi lokasi dan perangkat
* Validasi file upload
* CSRF protection
* XSS prevention
* Kontrol musik latar belakang dengan user preference storage
