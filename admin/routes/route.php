<?php
$halaman = htmlspecialchars($_GET['route'] ?? ''); // Pastikan nilai default adalah string

switch ($halaman) {
    case '':
        include '../views/home.php';
        break;
    case 'home':
        include '../views/home.php';
        break;
    case 'petugas':
        include '../views/home.php';
        break;
    case 'jabatan':
        include '../views/jabatan/index.php';
        break;
    case 'tambahJabatan':
        include '../views/jabatan/create.php';
        break;
    case 'ubahJabatan':
        include '../views/jabatan/edit.php';
        break;
    case 'hapusJabatan':
        include '../views/jabatan/delete.php';
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