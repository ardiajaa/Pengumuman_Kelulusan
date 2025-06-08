<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$settings = getSettings($conn);
$tanggalKelulusan = $settings['tanggal_kelulusan'];
$timeLeft = getTimeLeft($tanggalKelulusan);

$graduationDateISO = date('c', strtotime($tanggalKelulusan));
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
        <?= htmlspecialchars($settings['tahun_kelulusan']) ?>
    </title>
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
            margin: 0;
            padding: 0;
            background-color: #0e0e0e;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .main-background {
            width: 100%;
            height: 100%;
            position: fixed;
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

        .index-timer {
            width: 100%;
            display: flex;
            flex-direction: column;
            border-radius: 10px;
            overflow: hidden;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
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
            align-items: flex-end;
            padding: 20px;
            background-image: linear-gradient(90deg, #0f4174, #006cbf);
            flex-wrap: wrap;
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
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #loading-screen.visible {
            opacity: 1;
            visibility: visible;
        }

        .loading-content {
            text-align: center;
            color: #fff;
            padding: 30px;
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .loading-content i {
            font-size: 3rem;
            color: #88ccf0;
            margin-bottom: 15px;
            animation: spin 1.5s linear infinite;
        }

        .loading-content p {
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 1px;
            margin: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0);
            backdrop-filter: blur(0px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modal.visible {
            opacity: 1;
            visibility: visible;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.7);
            background-color: #fff;
            width: 90%;
            max-width: 400px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal.visible .modal-content {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .modal-icon {
            background: linear-gradient(135deg, #008acf, #00a3ff);
            padding: 30px 0;
            text-align: center;
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s;
        }

        .modal.visible .modal-icon {
            transform: scale(1);
            opacity: 1;
        }

        .modal-icon i {
            font-size: 4rem;
            color: #fff;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .modal-body {
            padding: 25px;
            text-align: center;
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s;
        }

        .modal.visible .modal-body {
            transform: scale(1);
            opacity: 1;
        }

        .modal-body h3 {
            color: #008acf;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .modal-body p {
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
            margin: 0;
        }

        .modal-footer {
            padding: 20px 25px 25px;
            text-align: center;
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s;
        }

        .modal.visible .modal-footer {
            transform: scale(1);
            opacity: 1;
        }

        .modal-button {
            background: linear-gradient(135deg, #008acf, #00a3ff);
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 138, 207, 0.3);
        }

        .modal-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 138, 207, 0.4);
            background: linear-gradient(135deg, #0077b3, #008acf);
        }

        .modal-button:active {
            transform: translateY(0);
        }

        .modal-button i {
            font-size: 1.1rem;
        }

        /* Responsive Styles */
        @media (max-width: 480px) {
            .modal-content {
                width: 85%;
            }

            .modal-icon {
                padding: 25px 0;
            }

            .modal-icon i {
                font-size: 3.5rem;
            }

            .modal-body {
                padding: 20px;
            }

            .modal-body h3 {
                font-size: 1.3rem;
            }

            .modal-body p {
                font-size: 0.95rem;
            }

            .modal-footer {
                padding: 15px 20px 20px;
            }

            .modal-button {
                padding: 10px 25px;
                font-size: 0.95rem;
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 360px) {
            .modal-content {
                width: 90%;
            }

            .modal-icon i {
                font-size: 3rem;
            }

            .modal-body h3 {
                font-size: 1.2rem;
            }
        }

        /* Modal Musik Styles */
        #musicModal .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            max-width: 400px;
            width: 90%;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        #musicModal .modal-icon {
            background: linear-gradient(135deg, #008acf, #00a3ff);
            padding: 30px 0;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }

        #musicModal .modal-icon i {
            font-size: 3.5rem;
            color: #fff;
            animation: pulse 2s infinite;
        }

        #musicModal .modal-body {
            padding: 25px;
            text-align: center;
        }

        #musicModal .modal-body h3 {
            color: #008acf;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        #musicModal .modal-body p {
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
            margin: 0;
        }

        #musicModal .modal-footer {
            padding: 20px 25px 25px;
            text-align: center;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        #musicModal .modal-button {
            background: linear-gradient(135deg, #008acf, #00a3ff);
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 138, 207, 0.3);
            min-width: 140px;
            justify-content: center;
        }

        #musicModal .modal-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 138, 207, 0.4);
        }

        #musicModal .modal-button:active {
            transform: translateY(0);
        }

        #musicModal .modal-button i {
            font-size: 1.1rem;
        }

        #musicModal .modal-button.danger {
            background: linear-gradient(135deg, #e82d33, #ff4d4d);
            box-shadow: 0 4px 15px rgba(232, 45, 51, 0.3);
        }

        #musicModal .modal-button.danger:hover {
            box-shadow: 0 6px 20px rgba(232, 45, 51, 0.4);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Responsive Styles */
        @media (max-width: 480px) {
            #musicModal .modal-content {
                width: 85%;
            }

            #musicModal .modal-icon {
                padding: 25px 0;
            }

            #musicModal .modal-icon i {
                font-size: 3rem;
            }

            #musicModal .modal-body {
                padding: 20px;
            }

            #musicModal .modal-body h3 {
                font-size: 1.3rem;
            }

            #musicModal .modal-body p {
                font-size: 0.95rem;
            }

            #musicModal .modal-footer {
                padding: 15px 20px 20px;
                flex-direction: column;
            }

            #musicModal .modal-button {
                width: 100%;
                padding: 10px 20px;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 360px) {
            #musicModal .modal-content {
                width: 90%;
            }

            #musicModal .modal-icon i {
                font-size: 2.5rem;
            }

            #musicModal .modal-body h3 {
                font-size: 1.2rem;
            }
        }

        /* Animasi Pengumuman */
        .announcement-banner {
            position: fixed;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            color: #008acf;
            padding: 25px 45px;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2),
                        0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            align-items: center;
            gap: 20px;
            max-width: 95%;
            border: 2px solid rgba(0, 138, 207, 0.15);
            overflow: hidden;
        }

        .announcement-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 138, 207, 0.1), rgba(0, 163, 255, 0.1));
            z-index: -1;
        }

        .announcement-banner.visible {
            opacity: 1;
            visibility: visible;
            top: 30px;
        }

        .announcement-banner-content {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
        }

        .announcement-banner-icon {
            background: linear-gradient(135deg, #008acf, #00a3ff);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
            position: relative;
            flex-shrink: 0;
        }

        .announcement-banner-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #008acf, #00a3ff);
            animation: ripple 2s infinite;
            z-index: -1;
        }

        .announcement-banner-icon i {
            color: white;
            font-size: 1.8rem;
            animation: bellRing 2s infinite;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .announcement-banner-text {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .announcement-banner-title {
            font-weight: 800;
            font-size: 1.4rem;
            background: linear-gradient(135deg, #008acf, #00a3ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            letter-spacing: 0.5px;
        }

        .announcement-banner-subtitle {
            font-size: 1rem;
            color: #555;
            font-weight: 500;
            line-height: 1.4;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 138, 207, 0.6);
            }
            70% {
                box-shadow: 0 0 0 20px rgba(0, 138, 207, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 138, 207, 0);
            }
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 0.4;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        @keyframes bellRing {
            0% { transform: rotate(0); }
            1% { transform: rotate(30deg); }
            3% { transform: rotate(-28deg); }
            5% { transform: rotate(34deg); }
            7% { transform: rotate(-32deg); }
            9% { transform: rotate(30deg); }
            11% { transform: rotate(-28deg); }
            13% { transform: rotate(26deg); }
            15% { transform: rotate(-24deg); }
            17% { transform: rotate(22deg); }
            19% { transform: rotate(-20deg); }
            21% { transform: rotate(18deg); }
            23% { transform: rotate(-16deg); }
            25% { transform: rotate(14deg); }
            27% { transform: rotate(-12deg); }
            29% { transform: rotate(10deg); }
            31% { transform: rotate(-8deg); }
            33% { transform: rotate(6deg); }
            35% { transform: rotate(-4deg); }
            37% { transform: rotate(2deg); }
            39% { transform: rotate(-1deg); }
            41% { transform: rotate(1deg); }
            43% { transform: rotate(0); }
            100% { transform: rotate(0); }
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .announcement-banner {
                padding: 20px 30px;
                width: 90%;
            }

            .announcement-banner-icon {
                width: 50px;
                height: 50px;
            }

            .announcement-banner-icon i {
                font-size: 1.5rem;
            }

            .announcement-banner-title {
                font-size: 1.2rem;
            }

            .announcement-banner-subtitle {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .announcement-banner {
                padding: 15px 20px;
                width: 85%;
            }

            .announcement-banner-content {
                gap: 15px;
            }

            .announcement-banner-icon {
                width: 45px;
                height: 45px;
            }

            .announcement-banner-icon i {
                font-size: 1.3rem;
            }

            .announcement-banner-title {
                font-size: 1.1rem;
            }

            .announcement-banner-subtitle {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 360px) {
            .announcement-banner {
                padding: 12px 15px;
                width: 90%;
            }

            .announcement-banner-icon {
                width: 40px;
                height: 40px;
            }

            .announcement-banner-icon i {
                font-size: 1.2rem;
            }

            .announcement-banner-title {
                font-size: 1rem;
            }

            .announcement-banner-subtitle {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body id="body">
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background-image: url('assets/images/backgrounds/<?= htmlspecialchars($settings['background_image'] ?? 'default-bg.jpg') ?>');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                filter: blur(2px);
                -webkit-filter: blur(2px);
                z-index: -1;">
    </div>

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
                    <div class="index-timer-note" style="display: flex; flex-direction: column; align-items: center;">
                        <span class="index-timer-note-event" style="margin-bottom: 10px;">PENGUMUMAN KELULUSAN
                            <?= htmlspecialchars($settings['nama_sekolah']) ?>
                            <?= htmlspecialchars($settings['tahun_kelulusan']) ?></span>
                        <span class="index-timer-note-deadline">Akan diumumkan pada:
                            <?= date('d F Y H:i', strtotime($tanggalKelulusan)) ?> WIB</span>
                    </div>
                </div>
            <?php else: ?>
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
                                    name="nisn" type="number" pattern="[0-9]*" inputmode="numeric"
                                    placeholder="Masukkan NISN" required />
                            </div>
                        </div>

                        <span class="index-form-content-alert" id="index-form-alert"></span>

                        <div class="index-form-content-footer">
                            <input type="submit" class="index-form-content-footer-submit" id="index-form-submit"
                                value="CEK STATUS KELULUSAN" />
                            <a href="<?= $settings['link_sekolah'] ?? 'https://smkn1cermegresik.sch.id/' ?>"
                                class="index-form-content-footer-pdf">
                                © <?= date('Y') ?> | <?= $settings['nama_sekolah'] ?? 'SMK NEGERI 1 CERME GRESIK' ?>
                            </a>
                        </div>
                    </form>
                    <div class="index-form-border"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="loading-screen">
        <div class="loading-content">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Memeriksa NISN...</p>
        </div>
    </div>

    <!-- Modal Notifikasi -->
    <div id="notificationModal" class="modal">
        <div class="modal-content animate__animated animate__zoomIn">
            <div class="modal-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="modal-body">
                <h3>NISN Tidak Ditemukan</h3>
                <p>NISN yang Anda masukkan tidak terdaftar dalam sistem kami.</p>
            </div>
            <div class="modal-footer">
                <button onclick="closeNotificationModal()" class="modal-button">
                    <i class="fas fa-redo-alt"></i>
                    <span>Coba Lagi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Musik -->
    <div id="musicModal" class="modal">
        <div class="modal-content animate__animated animate__zoomIn">
            <div class="modal-icon">
                <i class="fas fa-music"></i>
            </div>
            <div class="modal-body">
                <h3>Putar Musik Latar?</h3>
                <p>Apakah Anda ingin memutar musik latar saat melihat pengumuman kelulusan?</p>
            </div>
            <div class="modal-footer">
                <button onclick="playMusic(true)" class="modal-button">
                    <i class="fas fa-play"></i>
                    <span>Ya, Putar</span>
                </button>
                <button onclick="playMusic(false)" class="modal-button danger">
                    <i class="fas fa-times"></i>
                    <span>Tidak</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Audio Element -->
    <audio id="background-music" loop>
        <source src="/assets/mp3/<?= htmlspecialchars($settings['background_sound'] ?? 'sound.mp3') ?>" type="audio/mpeg">
        Browser Anda tidak mendukung elemen audio.
    </audio>

    <!-- Tambahkan div untuk banner pengumuman -->
    <div class="announcement-banner" id="announcementBanner">
        <div class="announcement-banner-content">
            <div class="announcement-banner-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="announcement-banner-text">
                <div class="announcement-banner-title">Pengumuman Kelulusan Telah Dibuka!</div>
                <div class="announcement-banner-subtitle">Silakan masukkan NISN Anda untuk melihat hasil kelulusan</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        function updateCountdown() {
            const countdown = document.getElementById('countdown-timer');
            if (!countdown) return;

            const targetDate = new Date(countdown.dataset.target);
            const now = new Date();
            const diff = (targetDate - now) / 1000;

            if (diff <= 0) {
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

        function showNotificationModal() {
            const modal = document.getElementById('notificationModal');
            modal.style.display = 'block';
            // Trigger reflow
            modal.offsetHeight;
            modal.classList.add('visible');
        }

        function closeNotificationModal() {
            const modal = document.getElementById('notificationModal');
            modal.classList.remove('visible');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        function showLoadingScreen() {
            // Cek apakah ini adalah navigasi back
            if (sessionStorage.getItem('isBackNavigation')) {
                return; // Jangan tampilkan loading screen jika ini adalah navigasi back
            }
            const loadingScreen = document.getElementById('loading-screen');
            loadingScreen.classList.add('visible');
        }

        function hideLoadingScreen() {
            const loadingScreen = document.getElementById('loading-screen');
            loadingScreen.classList.remove('visible');
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateCountdown();

            if (document.getElementById('countdown-timer')) {
                window.countdownInterval = setInterval(updateCountdown, 1000);
            }

            // Set flag untuk navigasi back
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    sessionStorage.setItem('isBackNavigation', 'true');
                    hideLoadingScreen();
                } else {
                    sessionStorage.removeItem('isBackNavigation');
                    // Tampilkan loading screen hanya jika bukan navigasi back
                    setTimeout(() => {
                        hideLoadingScreen();
                    }, 3000);
                }
            });

            const graduationForm = document.getElementById('graduation-form');
            const loadingScreen = document.getElementById('loading-screen');

            if (graduationForm && loadingScreen) {
                graduationForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const nisn = document.getElementById('index-form-registration-number').value;
                    
                    // Tampilkan loading screen
                    showLoadingScreen();
                    
                    // Kirim request AJAX untuk cek NISN
                    fetch('cek_kelulusan.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'nisn=' + encodeURIComponent(nisn)
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Cek apakah response mengandung indikasi NISN tidak ditemukan
                        if (html.includes('Data Tidak Ditemukan')) {
                            // Tunggu 1 detik sebelum menutup loading screen
                            setTimeout(() => {
                                hideLoadingScreen();
                                // Tunggu 300ms sebelum menampilkan modal
                                setTimeout(() => {
                                    showNotificationModal();
                                }, 300);
                            }, 1000);
                        } else {
                            // Jika NISN ditemukan, submit form setelah 1 detik
                            setTimeout(() => {
                                graduationForm.submit();
                            }, 1000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        hideLoadingScreen();
                    });
                });
            }

            // Cek apakah pengumuman sudah dibuka
            const countdownTimer = document.getElementById('countdown-timer');
            if (!countdownTimer) {
                showConfetti();
                showAnnouncementBanner();
            }
        });

        // Tutup modal ketika klik di luar modal
        window.onclick = function(event) {
            const modal = document.getElementById('notificationModal');
            if (event.target == modal) {
                closeNotificationModal();
            }
        }

        // Fungsi untuk menampilkan konfeti
        function showConfetti() {
            const duration = 5 * 1000;
            const animationEnd = Date.now() + duration;
            const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 50 * (timeLeft / duration);
                
                // Konfeti dari kiri
                confetti(Object.assign({}, defaults, { 
                    particleCount, 
                    origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } 
                }));
                
                // Konfeti dari kanan
                confetti(Object.assign({}, defaults, { 
                    particleCount, 
                    origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } 
                }));
            }, 250);
        }

        // Fungsi untuk menampilkan banner pengumuman
        function showAnnouncementBanner() {
            const banner = document.getElementById('announcementBanner');
            setTimeout(() => {
                banner.classList.add('visible');
                // Tambahkan timeout untuk menghilangkan banner setelah 5 detik
                setTimeout(() => {
                    banner.classList.remove('visible');
                }, 5000);
            }, 1000);
        }

        // Fungsi untuk menutup modal musik
        function closeMusicModal() {
            const modal = document.getElementById('musicModal');
            modal.classList.remove('visible');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        // Fungsi untuk mengelola pemutaran musik
        function playMusic(shouldPlay) {
            const audio = document.getElementById('background-music');
            
            if (shouldPlay) {
                audio.play();
                localStorage.setItem('musicEnabled', 'true');
                // Simpan posisi musik saat ini
                setInterval(() => {
                    if (!audio.paused) {
                        localStorage.setItem('musicPosition', audio.currentTime);
                    }
                }, 1000);
            } else {
                audio.pause();
                localStorage.setItem('musicEnabled', 'false');
                localStorage.removeItem('musicPosition');
            }
            
            closeMusicModal();
        }

        // Cek status musik saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const musicEnabled = localStorage.getItem('musicEnabled');
            const audio = document.getElementById('background-music');
            
            // Cek apakah ini kunjungan pertama
            const hasVisited = localStorage.getItem('hasVisited');
            
            // Cek apakah waktu pengumuman sudah dibuka
            const countdownTimer = document.getElementById('countdown-timer');
            const isAnnouncementOpen = !countdownTimer || countdownTimer.style.display === 'none';
            
            if (!hasVisited && isAnnouncementOpen) {
                // Jika ini kunjungan pertama dan waktu pengumuman sudah dibuka
                localStorage.setItem('hasVisited', 'true');
                showMusicModal();
            } else if (musicEnabled === 'true' && isAnnouncementOpen) {
                // Jika bukan kunjungan pertama, musik diaktifkan, dan waktu pengumuman sudah dibuka
                const savedPosition = localStorage.getItem('musicPosition');
                if (savedPosition) {
                    audio.currentTime = parseFloat(savedPosition);
                }
                audio.play();
                
                // Update posisi musik setiap detik
                setInterval(() => {
                    if (!audio.paused) {
                        localStorage.setItem('musicPosition', audio.currentTime);
                    }
                }, 1000);
            }
        });
    </script>
</body>

</html>