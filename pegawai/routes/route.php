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
    case 'absensiAksiIn':
        include '../views/absen/act_in.php';
        break;
    case 'absensiAksiOut':
        include '../views/absen/act_out.php';
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
}