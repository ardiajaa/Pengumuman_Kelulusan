<?php
if (!function_exists('getSettings')) {
    function getSettings($conn)
    {
        $result = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
        $settings = mysqli_fetch_assoc($result);
        
        // Set nilai default jika tidak ada
        if (!$settings) {
            $settings = [
                'nama_sekolah' => 'SMKN 1 CERME',
                'logo' => 'logo.png',
                'tahun_kelulusan' => date('Y'),
                'tanggal_kelulusan' => date('Y-m-d H:i:s', strtotime('+1 day')),
                'link_sekolah' => 'https://smkn1cermegresik.sch.id/'
            ];
        } elseif (!isset($settings['link_sekolah'])) {
            $settings['link_sekolah'] = 'https://smkn1cermegresik.sch.id/';
        }
        
        return $settings;
    }
}

function getSiswaByNISN($conn, $nisn)
{
    $sql = "SELECT * FROM siswa WHERE nisn = '$nisn' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function getTimeLeft($targetDate)
{
    // Set timezone ke Asia/Jakarta
    date_default_timezone_set('Asia/Jakarta');
    
    $now = time();
    $target = strtotime($targetDate);
    
    if ($target === false) {
        return 0; // Jika format tanggal salah, anggap sudah waktunya
    }
    
    $diff = $target - $now;
    
    // Kembalikan 0 jika waktu sudah lewat
    return $diff > 0 ? $diff : 0;
}

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

function validateImage($file)
{
    // Cek apakah file adalah gambar
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ["error" => "File bukan gambar"];
    }

    // Cek ukuran file (maks 5MB)
    if ($file["size"] > 5242880) {
        return ["error" => "Ukuran file terlalu besar (maks 5MB)"];
    }

    // Cek ekstensi file
    $validExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $validExtensions)) {
        return ["error" => "Hanya format JPG, JPEG, PNG yang diizinkan"];
    }

    return ["success" => true];
}

function validateStudentData($data)
{
    $errors = [];

    if (empty($data['nisn'])) {
        $errors[] = "NISN tidak boleh kosong";
    }

    if (empty($data['nama'])) {
        $errors[] = "Nama tidak boleh kosong";
    }

    if (empty($data['kelas'])) {
        $errors[] = "Kelas tidak boleh kosong";
    }

    // Check if 'absen' key exists before checking if it's numeric
    if (!isset($data['absen']) || !is_numeric($data['absen'])) {
        $errors[] = "Absen harus angka";
    }

    // Check if 'tanggal_lahir' key exists before checking date format
    if (!empty($data['tanggal_lahir']) && !strtotime($data['tanggal_lahir'])) {
        $errors[] = "Format tanggal tidak valid";
    }

    // Check if 'status' key exists before checking its value
    if (!isset($data['status']) || !in_array(strtolower($data['status']), ['lulus', 'tidak lulus'])) {
        $errors[] = "Status harus 'Lulus' atau 'Tidak Lulus'";
    }

    return $errors;
}
