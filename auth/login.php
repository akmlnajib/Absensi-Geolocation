<?php
session_start();

require_once('../config.php');
if (!isset($_SESSION["gagal"])) {
    $_SESSION["gagal"] = null;
}

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row["password"])) {
            if ($row['status'] == 'Aktif') {
                $_SESSION["login"] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['nip'] = $row['nip'];
                $_SESSION['jabatan'] = $row['jabatan'];
                $_SESSION['lokasi_absensi'] = $row['lokasi_absensi'];

                if ($row['role'] === 'Admin') {
                    header("Location: ../admin/views/index.php?pesan=berhasil");
                    exit();
                }else {
                    header("Location: ../pegawai/views/index.php?pesan=berhasil");
                }
            }else {
                $_SESSION["gagal"] = "Akun anda belum aktif, Silakan coba lagi";
            }
        } else {
            $_SESSION["gagal"] = "Password salah, Silakan coba lagi";
        }
    } else {
        $_SESSION["gagal"] = "Username salah, Silakan coba lagi";
    }
}

?>
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0
* @link https://tabler.io
* Copyright 2018-2025 The Tabler Authors
* Copyright 2018-2025 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login - Absensi Karyawan - Geolocation</title>
    <!-- CSS files -->
    <link href="<?= base_url('/assets/css/tabler.min.css?1738096685') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/tabler-vendors.min.css?1738096685') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/demo.min.css?1738096685') ?>" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');
    </style>
</head>

<body class=" d-flex flex-column">
    <script src="<?= base_url('assets/js/demo-theme.min.js?1738096685') ?>"></script>
    <div class="page">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <img src="<?= base_url('assets/img/logo.png') ?>" width="110" height="40" alt="">
                </a>
            </div>

            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == 'belum_login') {
                    $_SESSION['gagal'] = "Login terlebih dahulu";
                }else if($_GET['pesan'] == 'tolak_akses') {
                    $_SESSION['gagal'] = "403 Halaman Tidak dapat diakses";
                }
            }
            ?>

            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Masuk ke akun Anda</h2>
                    <form action="" method="POST" autocomplete="off" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Username atau Alamat Email</label>
                            <input type="text" class="form-control" name="username" placeholder="Masukkan username atau email anda" autocomplete="off">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">
                                Kata Sandi
                                <span class="form-label-description">
                                    <a href="./forgot-password.html">I forgot password</a>
                                </span>
                            </label>
                            <div class="input-group input-group-flat">
                                <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi anda"
                                    autocomplete="off">
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                                        <!-- Download SVG icon from http://tabler.io/icons/icon/eye -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="form-footer">
                            <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1738096685') ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/jsvectormap.min.js?1738096685') ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1738096685') ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1738096685') ?>" defer></script>
    <!-- Tabler Core -->
    <script src="<?= base_url('assets/js/tabler.min.js?1738096685') ?>" defer></script>
    <script src="<?= base_url('assets/js/demo.min.js?1738096685') ?>" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (isset($_SESSION["gagal"])) { ?>
    <script>
        Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "<?= $_SESSION["gagal"]; ?>"
        });
    </script>
    
    <?php unset($_SESSION["gagal"]); ?>
<?php } ?>

</body>

</html>