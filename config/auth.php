<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function login($email, $password) {
    $valid_email = 'admin@admin.com';
    $valid_password = 'mahameru';
    
    if ($email === $valid_email && $password === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}

function logout() {
    session_unset();
    session_destroy();
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}
?>