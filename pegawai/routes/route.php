<?php
$halaman = htmlspecialchars($_GET['route'] ?? '');

switch ($halaman) {
    case '':
        include '../views/home.php';
        break;
    case 'home':
        include '../views/home.php';
        break;
    case 'absensiMasuk':
        include '../views/absen/create_in.php';
        break;
    case 'absensiKeluar':
        include '../views/absen/create_out.php';
        break;
    case 'absensiAksiIn':
        include '../views/absen/act_in.php';
        break;
    case 'absensiAksiOut':
        include '../views/absen/act_out.php';
    case 'rekap':
        include '../views/rekap/day.php';
        break;
    case 'bulanan':
        include '../views/rekap/day.php';
        break;
    case 'error':
        include '../views/404.php';
        break;
    case 'logout':
        include '../../auth/logout.php';
        break;
    default:
        include '../404.php';
}