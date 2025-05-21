<?php
function getSettings($conn) {
    $result = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
    if (!$result || mysqli_num_rows($result) == 0) {
        // Return default settings if none found
        return [
            'nama_sekolah' => 'SMKN 1 CERME',
            'logo' => 'logo.png',
            'tahun_kelulusan' => '2025/2026',
            'tanggal_kelulusan' => '2026-05-20 08:00:00'
        ];
    }
    return mysqli_fetch_assoc($result);
}

function getSiswaByNISN($conn, $nisn) {
    $sql = "SELECT * FROM siswa WHERE nisn = '$nisn' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function getTimeLeft($targetDate) {
    // Pastikan timezone Jakarta
    date_default_timezone_set('Asia/Jakarta');
    
    // Tanggal sekarang
    $now = time();
    
    // Parse tanggal target
    $target = strtotime($targetDate);
    
    // Jika format tanggal salah
    if ($target === false) {
        return ['is_passed' => true];
    }
    
    // Hitung selisih (dalam detik)
    $diff = $target - $now;
    
    // Jika sudah lewat
    if ($diff <= 0) {
        return ['is_passed' => true];
    }
    
    // Hitung komponen waktu
    $days = floor($diff / (60 * 60 * 24));
    $hours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
    $minutes = floor(($diff % (60 * 60)) / 60);
    $seconds = floor($diff % 60);
    
    return [
        'is_passed' => false,
        'days' => $days,
        'hours' => $hours,
        'minutes' => $minutes,
        'seconds' => $seconds,
        'total_seconds' => $diff
    ];
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>