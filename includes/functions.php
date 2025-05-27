<?php
if (!function_exists('getSettings')) {
    function getSettings($conn)
    {
        $result = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
        $settings = mysqli_fetch_assoc($result);

        if (!$settings) {
            $settings = [
                'nama_sekolah' => 'SMKN 1 CERME',
                'logo' => 'logo.png',
                'tahun_kelulusan' => date('Y'),
                'tanggal_kelulusan' => date('Y-m-d H:i:s', strtotime('+1 day')),
                'link_sekolah' => 'https://smkn1cermegresik.sch.id/',
                'background_image' => 'default-bg.jpg'
            ];
        } else {
            if (!isset($settings['link_sekolah'])) {
                $settings['link_sekolah'] = 'https://smkn1cermegresik.sch.id/';
            }
            if (!isset($settings['background_image'])) {
                $settings['background_image'] = 'default-bg.jpg';
            }
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
    date_default_timezone_set('Asia/Jakarta');

    $now = time();
    $target = strtotime($targetDate);

    if ($target === false) {
        return 0;
    }

    $diff = $target - $now;

    return $diff > 0 ? $diff : 0;
}

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

function validateImage($file)
{
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ["error" => "File bukan gambar"];
    }

    if ($file["size"] > 5242880) {
        return ["error" => "Ukuran file terlalu besar (maks 5MB)"];
    }

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

    if (!isset($data['absen']) || !is_numeric($data['absen'])) {
        $errors[] = "Absen harus angka";
    }

    if (!empty($data['tanggal_lahir']) && !strtotime($data['tanggal_lahir'])) {
        $errors[] = "Format tanggal tidak valid";
    }

    if (!isset($data['status']) || !in_array(strtolower($data['status']), ['lulus', 'tidak lulus'])) {
        $errors[] = "Status harus 'Lulus' atau 'Tidak Lulus'";
    }

    return $errors;
}
