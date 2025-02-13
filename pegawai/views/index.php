<?php

session_start();
if (!isset($_SESSION["login"])) {
	header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'Pegawai') {
	header("Location: ../../admin/views/index.php?pesan=tolak_akses");
}

require_once('../../config.php');

?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>Absensi Karyawan - Geolocation</title>
	<!-- CSS files -->
	<link href="<?= base_url('/assets/css/tabler.min.css?1738096685') ?>" rel="stylesheet" />
	<link href="<?= base_url('assets/css/tabler-vendors.min.css?1738096685') ?>" rel="stylesheet" />
	<link href="<?= base_url('assets/css/demo.min.css?1738096685') ?>" rel="stylesheet" />
	<style>
		@import url('https://rsms.me/inter/inter.css');
	</style>
</head>

<body>
	<script src="<?= base_url('assets/js/demo-theme.min.js?1738096685') ?>"></script>
	<div class="page">
		<?php
		include "./layouts/header.php";
		?>
		<div class="page-wrapper">

			<?php
			include "../routes/route.php";

			if (isset($_GET['pesan'])) {
				if ($_GET['pesan'] == 'berhasil') {
					$_SESSION['berhasil'] = "Anda berhasil login";
				}
			}
			
			if (isset($_GET['pesan'])) {
				if ($_GET['pesan'] == 'tolak_akses_admin') {
					$_SESSION['gagal'] = "403 Halaman Tidak dapat diakses";
				}
			}
			?>
			<?php
			include "./layouts/footer.php";
			?>
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
	<?php if (isset($_SESSION["berhasil"])) { ?>
		<script>
			Swal.fire({
				icon: "success",
				title: "Selamat!",
				text: "<?= $_SESSION["berhasil"]; ?>"
			});
		</script>

		<?php unset($_SESSION["berhasil"]); ?>
	<?php } ?>

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