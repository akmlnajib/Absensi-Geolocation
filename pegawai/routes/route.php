<?php
$halaman = htmlspecialchars($_GET['route'] ?? ''); // Pastikan nilai default adalah string

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
    case 'rekapHarian':
        include '../views/home.php';
        break;
    case 'rekapBulanan':
        include '../views/home.php';
        break;
    case 'kH':
        include '../views/home.php';
        break;
    case 'logout':
        include '../../auth/logout.php';
        break;
    default:
        include '404.php';
}