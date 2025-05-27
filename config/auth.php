<?php
session_start();

function isLoggedIn()
{
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function login($email, $password, $conn)
{
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM admin WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama'];
            $_SESSION['admin_email'] = $admin['email'];

            $now = date('Y-m-d H:i:s');
            mysqli_query($conn, "UPDATE admin SET terakhir_login = '$now' WHERE id = {$admin['id']}");

            $ip = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $lokasi = getLocationInfo($ip);
            $perangkat = getDeviceInfo($user_agent);

            $riwayat_query = "INSERT INTO riwayat_login (admin_id, ip_address, user_agent, lokasi, perangkat) 
                             VALUES ({$admin['id']}, '$ip', '$user_agent', '$lokasi', '$perangkat')";
            mysqli_query($conn, $riwayat_query);

            return true;
        }
    }
    return false;
}

function logout()
{
    session_unset();
    session_destroy();
}

function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

function getCurrentAdmin($conn)
{
    if (isLoggedIn()) {
        $admin_id = $_SESSION['admin_id'];
        $query = "SELECT * FROM admin WHERE id = $admin_id LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
    }
    return null;
}

function getLoginHistory($conn, $admin_id, $limit = 10)
{
    $query = "SELECT * FROM riwayat_login 
              WHERE admin_id = $admin_id 
              ORDER BY waktu_login DESC 
              LIMIT $limit";
    $result = mysqli_query($conn, $query);

    $history = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $history[] = $row;
    }
    return $history;
}

function getLocationInfo($ip)
{
    // Dapatkan API key gratis dari https://ipinfo.io/
    $api_key = 'YOUR_API_KEY_HERE'; // Ganti dengan API key Anda

    $context = stream_context_create([
        'http' => [
            'timeout' => 2
        ]
    ]);

    try {
        $response = @file_get_contents("https://ipinfo.io/{$ip}?token={$api_key}", false, $context);
        if ($response === FALSE) {
            throw new Exception('Gagal mengakses API');
        }

        $details = json_decode($response);

        if ($details && !isset($details->error)) {
            $location = $details->city ?? 'Unknown City';
            $region = $details->region ?? 'Unknown Region';
            $country = $details->country ?? 'Unknown Country';
            return "{$location}, {$region}, {$country}";
        }
    } catch (Exception $e) {
        return 'Lokasi tidak diketahui';
    }

    return 'Lokasi tidak diketahui';
}

function getDeviceInfo($user_agent)
{
    $ua_parser = parse_user_agent($user_agent);
    $device = 'Desktop';

    if (stripos($user_agent, 'mobile') !== false) {
        $device = 'Mobile';
    } elseif (stripos($user_agent, 'tablet') !== false) {
        $device = 'Tablet';
    }

    return $device . ' - ' . $ua_parser['browser'] . ' ' . $ua_parser['version'] . ' on ' . $ua_parser['platform'];
}

function parse_user_agent($user_agent)
{
    $platform = 'Unknown';
    $browser = 'Unknown';
    $version = '';

    if (preg_match('/linux/i', $user_agent)) {
        $platform = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
        $platform = 'Mac';
    } elseif (preg_match('/windows|win32/i', $user_agent)) {
        $platform = 'Windows';
    }

    if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
        $browser = 'Internet Explorer';
        $ub = 'MSIE';
    } elseif (preg_match('/Firefox/i', $user_agent)) {
        $browser = 'Mozilla Firefox';
        $ub = 'Firefox';
    } elseif (preg_match('/Chrome/i', $user_agent)) {
        $browser = 'Google Chrome';
        $ub = 'Chrome';
    } elseif (preg_match('/Safari/i', $user_agent)) {
        $browser = 'Apple Safari';
        $ub = 'Safari';
    } elseif (preg_match('/Opera/i', $user_agent)) {
        $browser = 'Opera';
        $ub = 'Opera';
    } elseif (preg_match('/Netscape/i', $user_agent)) {
        $browser = 'Netscape';
        $ub = 'Netscape';
    }

    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA.Z.]*)#';

    if (!preg_match_all($pattern, $user_agent, $matches)) {
    }

    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($user_agent, 'Version') < strripos($user_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

    return array(
        'platform' => $platform,
        'browser' => $browser,
        'version' => $version
    );
}
?>