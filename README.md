# Aplikasi Kelulusan Siswa

Aplikasi web sederhana untuk mengelola dan menampilkan status kelulusan siswa. Dilengkapi dengan panel admin untuk manajemen data siswa dan pengaturan sekolah.

## Fitur

*   **Panel Admin:**
    *   Login dan Logout admin.
    *   Manajemen data siswa (Tambah, Edit, Hapus).
    *   Pengaturan informasi sekolah (Nama, Logo, Tahun Kelulusan, Tanggal Kelulusan, Link Sekolah, Background).
    *   Profil Admin dan Riwayat Login.
    *   Ubah Password Admin.
*   **Tampilan Publik:**
    *   Menampilkan status kelulusan siswa berdasarkan NISN.

## Teknologi yang Digunakan

*   PHP
*   MySQL
*   HTML, CSS, JavaScript (Frontend)

## Instalasi

1.  **Clone Repository:**
    ```bash
    git clone <https://github.com/ardiajaa/kelulusan>
    cd <kelulusan>
    ```

2.  **Setup Database:**
    *   Buat database MySQL baru (misalnya `smk_kelulusan`).
    *   Impor skema database. Anda bisa menjalankan script `config/database.php` melalui browser atau menjalankan perintah SQL dari file tersebut secara manual. Script ini akan membuat tabel `siswa`, `settings`, `admin`, dan `riwayat_login`, serta mengisi data default untuk `settings` dan `admin`.

3.  **Konfigurasi Database:**
    *   Buka file `config/database.php`.
    *   Sesuaikan detail koneksi database (`$host`, `$username`, `$password`, `$database`) jika diperlukan.

4.  **Konfigurasi Web Server:**
    *   Tempatkan folder project di direktori root web server Anda (misalnya `htdocs` untuk XAMPP, `www` untuk WAMP, atau `/var/www/html` untuk Apache di Linux).
    *   Pastikan PHP dan MySQL sudah terinstal dan berjalan.

5.  **Akses Aplikasi:**
    *   Akses aplikasi melalui browser: `http://localhost/<kelulusan>/`
    *   Akses panel admin: `http://localhost/<kelulusan>/admin/`

## Kredensial Admin Default

Setelah setup database, admin default akan dibuat dengan kredensial berikut:

*   **Email:** `admin@admin.com`
*   **Password:** `mahameru`

Anda dapat mengubah kredensial ini melalui halaman profil admin setelah login.

## Struktur Folder Penting

*   `admin/`: Berisi file-file untuk panel admin.
*   `config/`: Berisi file konfigurasi (database, autentikasi).
*   `includes/`: Berisi file-file yang di-include (header, footer, functions).
*   `assets/`: Berisi aset statis (CSS, JS, gambar, upload).
*   `index.php`: Halaman utama untuk cek kelulusan publik.
*   `login.php`: Halaman login admin.
