<?php
session_start();
ob_start();
if (!isset($_SESSION["login"])) {
	header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'Admin') {
	header("Location: ../../pegawai/views/?route=error");
}

require_once('../../config.php');

if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil') {
	$id = $_SESSION['id'] ?? null;

	if ($id) {
		$stmt = $conn->prepare("SELECT nama, jabatan FROM pegawai WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$pegawai = $result->fetch_assoc();

		if ($pegawai) {
			$_SESSION['berhasil'] = "Selamat datang, " . htmlspecialchars($pegawai['nama']);
		}

		$stmt->close();
	}
}
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('assets/fav/apple-icon-57x57.png') ?>">
	<link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('assets/fav/apple-icon-60x60.png') ?>">
	<link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('assets/fav/apple-icon-72x72.png') ?>">
	<link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/fav/apple-icon-76x76.png') ?>">
	<link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('assets/fav/apple-icon-114x114.png') ?>">
	<link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('assets/fav/apple-icon-120x120.png ') ?>">
	<link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('assets/fav/apple-icon-144x144.png ') ?>">
	<link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets/fav/apple-icon-152x152.png ') ?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/fav/apple-icon-180x180.png ') ?>">
	<link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('assets/favandroid-icon-192x192.png') ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/fav/favicon-32x32.png') ?>">
	<link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/fav/favicon-96x96.png') ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/fav/favicon-16x16.png') ?>">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<title>Absensi Karyawan - Geolocation</title>
	<!-- CSS files -->
	<link href="<?= base_url('/assets/css/tabler.min.css?1738096685') ?>" rel="stylesheet" />
	<link href="<?= base_url('assets/css/tabler-vendors.min.css?1738096685') ?>" rel="stylesheet" />
	<link href="<?= base_url('assets/css/demo.min.css?1738096685') ?>" rel="stylesheet" />
	<style>
		@import url('https://rsms.me/inter/inter.css');
	</style>
</head>

<body onload="hide_loading();">
	<div class="loading overpower">
		<div class="loader"></div>
	</div>

	<script src="<?= base_url('assets/js/demo-theme.min.js?1738096685') ?>"></script>
	<div class="page">
		<?php
		include "./layouts/header.php";
		?>
		<div class="page-wrapper">
			<?php
			include "../routes/route.php";
			?>

			<?php
			include "./layouts/footer.php";
			?>
		</div>
	</div>
	<!-- Libs JS -->
	<script src="script.js"></script>
	<script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1738096685') ?>" defer></script>
	<script src="<?= base_url('assets/libs/jsvectormap/dist/jsvectormap.min.js?1738096685') ?>" defer></script>
	<script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1738096685') ?>" defer></script>
	<script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1738096685') ?>" defer></script>
	<!-- Tabler Core -->
	<script src="<?= base_url('assets/js/tabler.min.js?1738096685') ?>" defer></script>
	<script src="<?= base_url('assets/js/demo.min.js?1738096685') ?>" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js"
		integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<?php
	include "./layouts/script.php";
	?>
</body>

</html>