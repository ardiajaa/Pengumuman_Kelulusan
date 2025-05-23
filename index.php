<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$settings = getSettings($conn);
$tanggalKelulusan = $settings['tanggal_kelulusan'];
$timeLeft = getTimeLeft($tanggalKelulusan); // Pastikan fungsi ini mengembalikan detik tersisa

// Konversi ke format ISO untuk JavaScript
$graduationDateISO = date('c', strtotime($tanggalKelulusan));

// Debug: Tampilkan informasi waktu
// echo "Waktu Server: " . date('Y-m-d H:i:s') . "<br>";
// echo "Waktu Kelulusan: " . $tanggalKelulusan . "<br>";
// echo "Selisih Detik: " . $timeLeft . "<br>";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <link rel="schema.dcterms" href="http://purl.org/dc/terms/" />
    <meta name="dcterms.rightsHolder" content="<?= htmlspecialchars($settings['nama_sekolah']) ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengumuman Kelulusan <?= htmlspecialchars($settings['nama_sekolah']) ?>
        <?= htmlspecialchars($settings['tahun_kelulusan']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="assets/images/<?= htmlspecialchars($settings['logo']) ?>" />
    <style>
        @import "https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap";

        * {
            box-sizing: border-box;
            font-family: "Lato", serif;
        }

        html {
            width: 100%;
            height: 100%;
        }

        body {
            width: 100%;
            min-height: 100vh;
            /* Use min-height for better responsiveness */
            margin: 0;
            padding: 0;
            background-color: #0e0e0e;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            display: flex;
            /* Use flexbox for centering */
            justify-content: center;
            align-items: center;
        }

        .main {
            width: 100%;
            min-height: 100vh;
            /* Use min-height */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            /* Add padding for smaller screens */
        }

        .main-background {
            width: 100%;
            height: 100%;
            position: fixed;
            /* Use fixed to cover the whole viewport */
            top: 0;
            left: 0;
            z-index: 1;
            background-color: #0e0e0e;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            opacity: 0 !important;
            transition: opacity 5s ease;
        }

        .main-route {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 900px;
            /* Limit max width */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .index {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        /* Countdown Timer Styles */
        .index-timer {
            width: 100%;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            /* Added border-radius */
            overflow: hidden;
            /* Ensure children respect border-radius */
            background-color: rgba(0, 0, 0, 0.5);
            /* Added background */
            backdrop-filter: blur(10px);
            /* Added blur */
        }

        @media (min-width: 900px) {
            .index-timer {
                width: 650px;
                box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.5);
            }
        }

        .index-timer-timer {
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            /* Changed to space-around */
            align-items: flex-end;
            padding: 20px;
            background-image: linear-gradient(90deg, #0f4174, #006cbf);
            flex-wrap: wrap;
            /* Allow wrapping on small screens */
        }

        @media (min-width: 600px) {
            .index-timer-timer {
                padding: 30px;
            }
        }

        .index-timer-timer-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 5px;
            /* Added margin */
        }

        .index-timer-timer-cell-caption {
            font-weight: 300;
            color: #fff;
            letter-spacing: 1px;
            text-align: center;
            margin-bottom: 5px;
            font-size: 0.6rem;
        }

        @media (min-width: 360px) {
            .index-timer-timer-cell-caption {
                font-size: 0.7rem;
            }
        }

        @media (min-width: 400px) {
            .index-timer-timer-cell-caption {
                font-size: 0.8rem;
            }
        }

        @media (min-width: 600px) {
            .index-timer-timer-cell-caption {
                font-size: 0.9rem;
            }
        }

        .index-timer-timer-cell-value {
            font-size: 1.8rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            font-weight: 900;
            color: #fff;
        }

        @media (min-width: 360px) {
            .index-timer-timer-cell-value {
                font-size: 2.1rem;
            }
        }

        @media (min-width: 600px) {
            .index-timer-timer-cell-value {
                font-size: 2.5rem;
            }
        }

        @media (min-width: 900px) {
            .index-timer-timer-cell-value {
                font-size: 3.5rem;
            }
        }

        .index-timer-timer-separator {
            font-size: 1.8rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            font-weight: 900;
            color: #fff;
            margin: 0 5px;
            /* Added margin */
        }

        @media (min-width: 360px) {
            .index-timer-timer-separator {
                font-size: 2.1rem;
            }
        }

        @media (min-width: 600px) {
            .index-timer-timer-separator {
                font-size: 2.5rem;
                margin: 0 10px;
            }
        }

        @media (min-width: 900px) {
            .index-timer-timer-separator {
                font-size: 3.5rem;
            }
        }


        .index-timer-note {
            display: flex;
            padding: 20px;
            background-color: #fff;
            flex-direction: column;
            justify-content: flex-start;
            text-align: center;
            /* Centered text */
        }

        @media (min-width: 900px) {
            .index-timer-note {
                flex-direction: row;
                justify-content: space-between;
                text-align: left;
                /* Align left on larger screens */
            }
        }

        .index-timer-note-event {
            font-size: 0.9rem;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 10px;
            /* Added margin */
        }

        @media (min-width: 900px) {
            .index-timer-note-event {
                margin-bottom: 0;
            }
        }


        .index-timer-note-deadline {
            font-size: 0.9rem;
            letter-spacing: 1px;
            font-weight: 300;
        }

        /* Form Styles (Existing) */
        .index-form {
            width: 100%;
            max-width: 700px;
            background-color: rgba(0, 0, 0, 0.5);
            /* Adjusted background */
            backdrop-filter: blur(10px);
            /* Adjusted blur */
            border-radius: 10px;
            /* Added border-radius */
            padding: 20px;
            /* Added padding */
        }

        @media (min-width: 900px) {
            .index-form {
                box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.5);
            }
        }

        .index-form-content {
            padding: 0;
            /* Removed padding as it's on .index-form */
        }

        @media (min-width: 600px) {
            .index-form-content {
                padding: 0;
                /* Removed padding */
            }
        }

        .index-form-content-logo {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .index-form-content-logo-snmptn {
            /* Renamed from ltmpt/snmptn to be generic */
            margin-right: 20px;
            height: 40px;
            cursor: pointer;
            /* Added cursor */
        }

        @media (min-width: 600px) {
            .index-form-content-logo-snmptn {
                height: 60px;
                /* Adjusted height */
            }
        }

        .index-form-content-title {
            margin: 0 0 6px 0;
            font-weight: 900;
            letter-spacing: 1px;
            color: #fff;
            font-size: 1.9rem;
        }

        @media (min-width: 360px) {
            .index-form-content-title {
                font-size: 2rem;
            }
        }

        @media (min-width: 400px) {
            .index-form-content-title {
                font-size: 2.1rem;
            }
        }

        @media (min-width: 600px) {
            .index-form-content-title {
                font-size: 2.1rem;
            }
        }

        @media (min-width: 900px) {
            .index-form-content-title {
                font-size: 2.7rem;
            }
        }

        .index-form-content-subtitle {
            color: #999;
            margin-bottom: 20px;
            /* Adjusted margin */
            display: block;
        }

        .index-form-content-form-field-caption {
            display: block;
            font-size: 0.9rem;
            font-weight: 900;
            color: #88ccf0;
            margin-bottom: 10px;
            /* Adjusted margin */
        }

        .index-form-content-form-field-group {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 20px;
            /* Adjusted margin */
        }

        .index-form-content-form-field-group-input {
            background-color: rgba(250, 250, 250, 0.2);
            padding: 8px 8px;
            border: 0;
            border-radius: 5px;
            width: 70px;
            text-align: center;
            font-weight: 700;
            color: #fff;
            font-size: 0.9rem;
        }

        @media (min-width: 360px) {
            .index-form-content-form-field-group-input {
                font-size: 1rem;
            }
        }

        @media (min-width: 600px) {
            .index-form-content-form-field-group-input {
                width: 110px;
                padding: 15px 18px;
            }
        }

        .index-form-content-form-field-group-input::placeholder {
            color: #6c6c6c;
        }

        .index-form-content-form-field-group-separator {
            color: #999;
            font-size: 1rem;
            margin: 0 5px;
            font-weight: 700;
        }

        @media (min-width: 600px) {
            .index-form-content-form-field-group-separator {
                font-size: 1.5rem;
                margin: 0 10px;
            }
        }

        .index-form-content-form-field-input {
            background-color: rgba(250, 250, 250, 0.2);
            padding: 10px 12px;
            border: 0;
            border-radius: 5px;
            margin-bottom: 20px;
            /* Adjusted margin */
            width: 100%;
            font-weight: 700;
            color: #fff;
            font-size: 0.9rem;
        }

        @media (min-width: 360px) {
            .index-form-content-form-field-input {
                font-size: 1rem;
            }
        }

        @media (min-width: 600px) {
            .index-form-content-form-field-input {
                padding: 15px 18px;
            }
        }

        .index-form-content-form-field-input::placeholder {
            color: #6c6c6c;
        }

        .index-form-content-alert {
            color: #cd5c5c;
            font-style: italic;
            margin-top: 10px;
            /* Adjusted margin */
            margin-bottom: 10px;
            /* Adjusted margin */
            display: none;
        }

        .index-form-content-footer {
            display: flex;
            flex-direction: column-reverse;
            justify-content: flex-start;
            align-items: center;
            /* Centered items */
            margin-top: 20px;
            /* Adjusted margin */
            text-align: center;
            /* Centered text */
        }

        @media (min-width: 600px) {
            .index-form-content-footer {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                text-align: left;
                /* Align left on larger screens */
            }
        }

        .index-form-content-footer-submit {
            border-radius: 1000px;
            padding: 12px 18px;
            background-color: #008acf;
            color: #fff;
            letter-spacing: 1px;
            font-weight: 900;
            border: 0;
            font-size: 0.8rem;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            margin-bottom: 15px;
            /* Added margin */
        }

        @media (min-width: 600px) {
            .index-form-content-footer-submit {
                padding: 15px 22px;
                margin-bottom: 0;
            }
        }

        .index-form-content-footer-pdf {
            font-size: 0.8rem;
            letter-spacing: 1px;
            color: #008acf;
            text-decoration: none;
        }

        .index-form-border {
            background-color: #fff;
            height: 8px;
            display: none;
            /* Kept display none as it wasn't visible */
        }

        /* Result Pages Styles (Existing) */
        .index-accepted,
        .index-rejected {
            width: 100%;
            max-width: 1200px;
            background-color: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(30px);
            border-radius: 10px;
            /* Added border-radius */
            overflow: hidden;
            /* Ensure children respect border-radius */
        }

        @media (min-width: 900px) {

            .index-accepted,
            .index-rejected {
                box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.5);
            }
        }

        .index-accepted-header,
        .index-rejected-header {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 20px;
            background-image: linear-gradient(90deg, #083661, #006cbf);
        }

        @media (min-width: 600px) {

            .index-accepted-header,
            .index-rejected-header {
                padding: 30px;
                flex-direction: row-reverse;
                justify-content: space-between;
                align-items: center;
            }
        }

        .index-accepted-header-icon,
        .index-rejected-header-icon {
            height: 80px;
            margin-bottom: 20px;
        }

        @media (min-width: 600px) {

            .index-accepted-header-icon,
            .index-rejected-header-icon {
                margin-left: 30px;
                margin-bottom: 0;
            }
        }

        .index-accepted-header-title,
        .index-rejected-header-title {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .index-accepted-header-title-text,
        .index-rejected-header-title-text {
            font-weight: 900;
            color: #fff;
            letter-spacing: 1px;
            margin: 0;
            font-size: 1.2rem;
        }

        @media (min-width: 360px) {

            .index-accepted-header-title-text,
            .index-rejected-header-title-text {
                font-size: 1.2rem;
            }
        }

        @media (min-width: 400px) {

            .index-accepted-header-title-text,
            .index-rejected-header-title-text {
                font-size: 1.3rem;
            }
        }

        @media (min-width: 600px) {

            .index-accepted-header-title-text,
            .index-rejected-header-title-text {
                font-size: 1.3rem;
            }
        }

        @media (min-width: 900px) {

            .index-accepted-header-title-text,
            .index-rejected-header-title-text {
                font-size: 1.5rem;
            }
        }

        @media (min-width: 900px) {

            .index-accepted-header-title-text,
            .index-rejected-header-title-text {
                font-size: 1.7rem;
            }
        }


        .index-accepted-header-title-sub,
        .index-rejected-header-title-sub {
            letter-spacing: 1px;
            color: #fff;
            margin-top: 10px;
            display: block;
            font-size: 0.9rem;
        }

        @media (min-width: 360px) {

            .index-accepted-header-title-sub,
            .index-rejected-header-title-sub {
                font-size: 0.9rem;
            }
        }

        @media (min-width: 600px) {

            .index-accepted-header-title-sub,
            .index-rejected-header-title-sub {
                font-size: 0.9rem;
            }
        }

        @media (min-width: 900px) {

            .index-accepted-header-title-sub,
            .index-rejected-header-title-sub {
                font-size: 1.1rem;
            }
        }


        .index-accepted-content,
        .index-rejected-content {
            padding: 20px;
        }

        @media (min-width: 600px) {

            .index-accepted-content,
            .index-rejected-content {
                padding: 30px;
            }
        }

        .index-accepted-content-upper,
        .index-rejected-content-upper {
            display: flex;
            flex-direction: column-reverse;
            justify-content: flex-start;
            align-items: stretch;
            margin-bottom: 30px;
        }

        @media (min-width: 900px) {

            .index-accepted-content-upper,
            .index-rejected-content-upper {
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-start;
            }
        }

        .index-accepted-content-upper-bio-nisn,
        .index-rejected-content-upper-bio-nisn {
            display: block;
            font-size: 0.9rem;
            font-weight: 900;
            color: #88ccf0;
            margin-bottom: 5px;
        }

        .index-accepted-content-upper-bio-name,
        .index-rejected-content-upper-bio-name {
            font-size: 1.9rem;
            /* Adjusted base font size */
            color: #fff;
            letter-spacing: 2px;
            display: block;
            margin-bottom: 5px;
            font-weight: 900;
        }

        @media (min-width: 360px) {

            .index-accepted-content-upper-bio-name,
            .index-rejected-content-upper-bio-name {
                font-size: 2rem;
            }
        }

        @media (min-width: 600px) {

            .index-accepted-content-upper-bio-name,
            .index-rejected-content-upper-bio-name {
                font-size: 2rem;
            }
        }

        @media (min-width: 900px) {

            .index-accepted-content-upper-bio-name,
            .index-rejected-content-upper-bio-name {
                font-size: 2.2rem;
            }
        }

        @media (min-width: 900px) {

            .index-accepted-content-upper-bio-name,
            .index-rejected-content-upper-bio-name {
                font-size: 2.5rem;
            }
        }


        .index-accepted-content-upper-bio-program,
        .index-accepted-content-upper-bio-university,
        .index-rejected-content-upper-bio-program,
        .index-rejected-content-upper-bio-university {
            color: #fff;
            font-weight: 300;
            letter-spacing: 1px;
            display: block;
            font-size: 1rem;
        }

        @media (min-width: 360px) {

            .index-accepted-content-upper-bio-program,
            .index-accepted-content-upper-bio-university,
            .index-rejected-content-upper-bio-program,
            .index-rejected-content-upper-bio-university {
                font-size: 1.1rem;
            }
        }

        @media (min-width: 600px) {

            .index-accepted-content-upper-bio-program,
            .index-accepted-content-upper-bio-university,
            .index-rejected-content-upper-bio-program,
            .index-rejected-content-upper-bio-university {
                font-size: 1.2rem;
            }
        }

        @media (min-width: 900px) {

            .index-accepted-content-upper-bio-program,
            .index-accepted-content-upper-bio-university,
            .index-rejected-content-upper-bio-program,
            .index-rejected-content-upper-bio-university {
                font-size: 1.3rem;
            }
        }


        .index-accepted-content-upper-qr,
        .index-rejected-content-upper-qr {
            width: 120px;
            margin-bottom: 15px;
        }

        @media (min-width: 900px) {

            .index-accepted-content-upper-qr,
            .index-rejected-content-upper-qr {
                margin-bottom: 0;
            }
        }

        .index-accepted-content-lower,
        .index-rejected-content-lower {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: stretch;
        }

        @media (min-width: 900px) {

            .index-accepted-content-lower,
            .index-rejected-content-lower {
                flex-direction: row;
                justify-content: flex-start;
                align-items: flex-start;
            }
        }

        @media (min-width: 900px) {

            .index-accepted-content-lower-column,
            .index-rejected-content-lower-column {
                padding: 0 15px;
            }
        }

        .index-accepted-content-lower-column:first-child,
        .index-rejected-content-lower-column:first-child {
            padding-left: 0;
        }

        .index-accepted-content-lower-column:last-child,
        .index-rejected-content-lower-column:last-child {
            padding-right: 0;
        }

        .index-accepted-content-lower-column-25,
        .index-rejected-content-lower-column-25 {
            width: 100%;
            flex-grow: 1;
            flex-shrink: 0;
        }

        @media (min-width: 900px) {

            .index-accepted-content-lower-column-25,
            .index-rejected-content-lower-column-25 {
                width: 25%;
            }
        }

        .index-accepted-content-lower-column-50,
        .index-rejected-content-lower-column-50 {
            width: 100%;
            flex-grow: 1;
        }

        @media (min-width: 900px) {

            .index-accepted-content-lower-column-50,
            .index-rejected-content-lower-column-50 {
                width: 50%;
            }
        }

        .index-accepted-content-lower-column-field,
        .index-rejected-content-lower-column-field {
            margin-bottom: 20px;
            /* Adjusted margin */
        }

        @media (min-width: 900px) {

            .index-accepted-content-lower-column-field:last-child,
            .index-rejected-content-lower-column-field:last-child {
                margin-bottom: 0;
            }
        }

        .index-accepted-content-lower-column-field-caption,
        .index-rejected-content-lower-column-field-caption {
            display: block;
            font-size: 0.9rem;
            font-weight: 900;
            color: #88ccf0;
            margin-bottom: 5px;
        }

        .index-accepted-content-lower-column-field-value,
        .index-rejected-content-lower-column-field-value {
            font-size: 1.1rem;
            color: #fff;
            font-weight: 900;
        }

        .index-accepted-content-lower-column-note,
        .index-rejected-content-lower-column-note {
            background-color: #fafafa;
            padding: 15px;
            align-self: center;
            width: 100%;
            /* Make note full width on small screens */
            margin-top: 20px;
            /* Added margin */
        }

        @media (min-width: 900px) {

            .index-accepted-content-lower-column-note,
            .index-rejected-content-lower-column-note {
                width: auto;
                /* Auto width on larger screens */
                margin-top: 0;
            }
        }


        .index-accepted-content-lower-column-note-title,
        .index-rejected-content-lower-column-note-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #2d2d2d;
            display: block;
        }

        .index-accepted-content-lower-column-note-subtitle,
        .index-rejected-content-lower-column-note-subtitle {
            color: #2d2d2d;
            margin-bottom: 10px;
            font-weight: 300;
            font-size: 0.9rem;
            display: block;
        }

        .index-accepted-content-lower-column-note-link,
        .index-rejected-content-lower-column-note-link {
            color: #008acf;
            text-decoration: none;
            font-weight: 900;
            font-size: 0.9rem;
        }

        @media (min-width: 360px) {

            .index-accepted-content-lower-column-note-link,
            .index-rejected-content-lower-column-note-link {
                font-size: 0.9rem;
            }
        }

        @media (min-width: 600px) {

            .index-accepted-content-lower-column-note-link,
            .index-rejected-content-lower-column-note-link {
                font-size: 1rem;
            }
        }

        @media (min-width: 900px) {

            .index-accepted-content-lower-column-note-link,
            .index-rejected-content-lower-column-note-link {
                font-size: 1.2rem;
            }
        }


        .index-accepted-footer,
        .index-rejected-footer {
            padding: 0 20px 20px 20px;
        }

        @media (min-width: 900px) {

            .index-accepted-footer,
            .index-rejected-footer {
                padding: 0 30px 30px 30px;
            }
        }

        .index-accepted-footer-paragraph,
        .index-rejected-footer-paragraph {
            font-weight: 300;
            letter-spacing: 1px;
            font-size: 0.9rem;
            color: #999;
            text-align: justify;
        }

        .index-accepted-footer-paragraph:last-child,
        .index-rejected-footer-paragraph:last-child {
            margin-bottom: 0;
        }

        .index-rejected {
            max-width: 900px;
        }

        .index-rejected-header {
            background-image: linear-gradient(90deg, #bf0a0a, #e82d33);
        }

        /* Loading Screen Styles */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent dark background */
            z-index: 9999; /* Ensure it's on top */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 1.5rem;
            text-align: center;
            opacity: 0; /* Start hidden */
            visibility: hidden; /* Start hidden */
            transition: opacity 0.5s ease, visibility 0.5s ease; /* Smooth transition */
        }

        #loading-screen.visible {
            opacity: 1;
            visibility: visible;
        }

        #loading-screen i {
            font-size: 3rem; /* Size of the spinner icon */
            margin-bottom: 20px;
            color: #88ccf0; /* Color matching the theme */
            animation: spin 1.5s linear infinite; /* Add spin animation */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #loading-screen p {
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Style untuk mobile */
        @media (max-width: 600px) {
            .index-form-content-footer {
                flex-direction: column;
                gap: 10px;
                padding: 10px;
            }

            .index-form-content-footer-submit {
                width: 100%;
                padding: 12px;
                font-size: 0.9rem;
            }

            .index-form-content-footer-pdf {
                font-size: 0.8rem;
                text-align: center;
            }
        }
    </style>
</head>
<body id="body"
    style="background-image: url('assets/images/bg.jpg'); background-size: cover; background-position: center;">
    <div id="main" class="main">
        <div id="main-background" class="main-background" style="opacity: 1; background: none;"></div>
        <div id="main-route" class="main-route">

            <?php if ($timeLeft > 0): ?>
                <!-- Countdown Timer -->
                <div id="countdown-timer" class="index-timer animate__animated animate__fadeInUp"
                    data-target="<?= $graduationDateISO ?>">
                    <div class="index-timer-timer">
                        <div class="index-timer-timer-cell">
                            <span class="index-timer-timer-cell-caption">HARI</span>
                            <span class="index-timer-timer-cell-value"
                                id="days"><?= str_pad(floor($timeLeft / (60 * 60 * 24)), 2, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <span class="index-timer-timer-separator">:</span>
                        <div class="index-timer-timer-cell">
                            <span class="index-timer-timer-cell-caption">JAM</span>
                            <span class="index-timer-timer-cell-value"
                                id="hours"><?= str_pad(floor(($timeLeft % (60 * 60 * 24)) / (60 * 60)), 2, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <span class="index-timer-timer-separator">:</span>
                        <div class="index-timer-timer-cell">
                            <span class="index-timer-timer-cell-caption">MENIT</span>
                            <span class="index-timer-timer-cell-value"
                                id="minutes"><?= str_pad(floor(($timeLeft % (60 * 60)) / 60), 2, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <span class="index-timer-timer-separator">:</span>
                        <div class="index-timer-timer-cell">
                            <span class="index-timer-timer-cell-caption">DETIK</span>
                            <span class="index-timer-timer-cell-value"
                                id="seconds"><?= str_pad($timeLeft % 60, 2, '0', STR_PAD_LEFT) ?></span>
                        </div>
                    </div>
                    <div class="index-timer-note">
                        <span class="index-timer-note-event">PENGUMUMAN KELULUSAN
                            <?= htmlspecialchars($settings['nama_sekolah']) ?>
                            <?= htmlspecialchars($settings['tahun_kelulusan']) ?></span>
                        <span class="index-timer-note-deadline">Akan diumumkan pada:
                            <?= date('d F Y H:i', strtotime($tanggalKelulusan)) ?> WIB</span>
                    </div>
                </div>
            <?php else: ?>
                <!-- Form Kelulusan -->
                <div id="index-form" class="index-form animate__animated animate__fadeInUp">
                    <form id="graduation-form" class="index-form-content" action="cek_kelulusan.php" method="POST">
                        <div class="index-form-content-logo">
                            <img src="assets/images/<?= htmlspecialchars($settings['logo']) ?>"
                                class="index-form-content-logo-snmptn" alt="Logo Sekolah"
                                onclick="window.location.reload()" />
                        </div>
                        <h1 class="index-form-content-title">
                            PENGUMUMAN KELULUSAN <?= htmlspecialchars($settings['nama_sekolah']) ?> <span
                                class="tahun"><?= htmlspecialchars($settings['tahun_kelulusan']) ?></span>
                        </h1>
                        <span class="index-form-content-subtitle">
                            Masukkan NISN untuk melihat status kelulusan
                        </span>

                        <div class="index-form-content-form">
                            <div class="index-form-content-form-field">
                                <span class="index-form-content-form-field-caption">NISN</span>
                                <input class="index-form-content-form-field-input" id="index-form-registration-number"
                                    name="nisn" type="text" placeholder="Masukkan NISN" required />
                            </div>
                        </div>

                        <span class="index-form-content-alert" id="index-form-alert"></span>

                        <div class="index-form-content-footer">
                            <input type="submit" class="index-form-content-footer-submit" id="index-form-submit"
                                value="CEK STATUS KELULUSAN" />
                            <a href="https://smkn1cermegresik.sch.id/" class="index-form-content-footer-pdf">
                                © 2025 | SMK NEGERI 1 CERME GRESIK
                            </a>
                        </div>
                    </form>
                    <div class="index-form-border"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Loading Screen HTML -->
    <div id="loading-screen">
        <i class="fas fa-spinner fa-spin"></i>
        <p>Memuat data kelulusan...</p>
    </div>

    <script>
        // Fungsi update countdown yang diperbaiki
        function updateCountdown() {
            const countdown = document.getElementById('countdown-timer');
            if (!countdown) return;

            const targetDate = new Date(countdown.dataset.target);
            const now = new Date();
            const diff = (targetDate - now) / 1000; // Selisih dalam detik

            if (diff <= 0) {
                // Waktu habis, tampilkan form tanpa refresh
                const timerElement = document.getElementById('countdown-timer');
                const formElement = document.getElementById('index-form');
                if (timerElement) timerElement.style.display = 'none';
                if (formElement) formElement.style.display = 'block';
                clearInterval(window.countdownInterval);
                return;
            }

            const days = Math.floor(diff / (60 * 60 * 24));
            const hours = Math.floor((diff % (60 * 60 * 24)) / (60 * 60));
            const minutes = Math.floor((diff % (60 * 60)) / 60);
            const seconds = Math.floor(diff % 60);

            const daysElement = document.getElementById('days');
            const hoursElement = document.getElementById('hours');
            const minutesElement = document.getElementById('minutes');
            const secondsElement = document.getElementById('seconds');

            if (daysElement) daysElement.textContent = days.toString().padStart(2, '0');
            if (hoursElement) hoursElement.textContent = hours.toString().padStart(2, '0');
            if (minutesElement) minutesElement.textContent = minutes.toString().padStart(2, '0');
            if (secondsElement) secondsElement.textContent = seconds.toString().padStart(2, '0');
        }

        // Inisialisasi
        document.addEventListener('DOMContentLoaded', function () {
            // Jalankan segera
            updateCountdown();

            // Atur interval hanya jika countdown masih ada
            if (document.getElementById('countdown-timer')) {
                window.countdownInterval = setInterval(updateCountdown, 1000);
            }

            // Tambahkan event listener untuk form submission
            const graduationForm = document.getElementById('graduation-form');
            const loadingScreen = document.getElementById('loading-screen');

            if (graduationForm && loadingScreen) {
                graduationForm.addEventListener('submit', function(event) {
                    // Prevent default form submission
                    event.preventDefault();

                    // Show loading screen with animation
                    loadingScreen.classList.add('visible');

                    // Store the form reference
                    const form = this;

                    // Submit the form after a 5-second delay
                    setTimeout(function() {
                        form.submit();
                    }, 5000); // Delay in milliseconds (5000ms = 5 seconds)
                });
            }
        });
    </script>
</body>

</html>