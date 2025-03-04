<?php
session_start();
ob_start();
if (!isset($_SESSION["login"])) {
	header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] != 'Admin') {
	header("Location: ../../pegawai/views/index.php?pesan=tolak_akses_admin");
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

			if (isset($_GET['pesan'])) {
				if ($_GET['pesan'] == 'tolak_akses') {
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
	 <script src="script.js"></script>
	<script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1738096685') ?>" defer></script>
	<script src="<?= base_url('assets/libs/jsvectormap/dist/jsvectormap.min.js?1738096685') ?>" defer></script>
	<script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1738096685') ?>" defer></script>
	<script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1738096685') ?>" defer></script>
	<!-- Tabler Core -->
	<script src="<?= base_url('assets/js/tabler.min.js?1738096685') ?>" defer></script>
	<script src="<?= base_url('assets/js/demo.min.js?1738096685') ?>" defer></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<?php
		include "./layouts/script.php";
	?>
</body>

</html>