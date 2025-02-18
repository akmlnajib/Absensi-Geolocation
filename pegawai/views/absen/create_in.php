<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_masuk'])) {
    // Validasi lokasi harus dipilih
    $nama_lokasi = trim($_POST['nama_lokasi'] ?? '');
    if (empty($nama_lokasi)) {
        $_SESSION['gagal'] = "Pilih lokasi terlebih dahulu!";
        header("Location: ./?route=home");
        exit;
    }

    // Ambil data dengan validasi
    $latitude_pegawai = filter_input(INPUT_POST, 'latitude_pegawai_masuk', FILTER_VALIDATE_FLOAT) ?? null;
    $longitude_pegawai = filter_input(INPUT_POST, 'longitude_pegawai_masuk', FILTER_VALIDATE_FLOAT) ?? null;
    $latitude_masuk = filter_input(INPUT_POST, 'latitude_masuk', FILTER_VALIDATE_FLOAT) ?? null;
    $longitude_masuk = filter_input(INPUT_POST, 'longitude_masuk', FILTER_VALIDATE_FLOAT) ?? null;
    $radius_masuk = filter_input(INPUT_POST, 'radius_masuk', FILTER_VALIDATE_FLOAT) ?? null;
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? null;
    $jam_masuk = $_POST['jam_masuk'] ?? null;

    // Cek jika koordinat tidak tersedia
    if (is_null($latitude_pegawai) || is_null($longitude_pegawai) || is_null($latitude_masuk) || is_null($longitude_masuk) || is_null($radius_masuk)) {
        $_SESSION['gagal'] = "Data lokasi tidak valid.";
        header("Location: ./?route=home");
        exit;
    }

    // Konversi derajat ke radian
    $latPegawaiRad = deg2rad($latitude_pegawai);
    $lonPegawaiRad = deg2rad($longitude_pegawai);
    $latMasukRad = deg2rad($latitude_masuk);
    $lonMasukRad = deg2rad($longitude_masuk);

    // Perbedaan koordinat
    $deltaLat = $latMasukRad - $latPegawaiRad;
    $deltaLon = $lonMasukRad - $lonPegawaiRad;

    // Rumus Haversine
    $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
        cos($latPegawaiRad) * cos($latMasukRad) *
        sin($deltaLon / 2) * sin($deltaLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Radius bumi dalam meter
    $earthRadius = 6371000;
    $m = round($earthRadius * $c);

    if ($m > $radius_masuk) {
        $_SESSION['gagal'] = "Lokasi tidak sesuai";
        header("Location: ./?route=home");
        exit;
    }
}
?>

<!-- Page header -->
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Absensi Masuk</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="row row-cards">
                    <div class="col-sm-2 col-lg-4"></div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card">
                            <div class="card-body text-center">   
                                <center>
                                <div id="my_camera" style="width:320px; height:240px;"></div>
                                <div class="mt-2" id="my_result"></div>
                                <input type="hidden" id="id_pegawai" value="<?= htmlspecialchars($_SESSION['id'] ?? ''); ?>">
                                <input type="hidden" id="nama_lokasi" value="<?= htmlspecialchars($nama_lokasi ?? ''); ?>">
                                <input type="hidden" id="tanggal_masuk" value="<?= htmlspecialchars($tanggal_masuk ?? ''); ?>">
                                <input type="hidden" id="jam_masuk" value="<?= htmlspecialchars($jam_masuk ?? ''); ?>">
                                <button class="btn btn-dark mt-2" id="take-foto">Absen</button> 
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-lg-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    Webcam.attach('#my_camera');

    document.getElementById('take-foto').addEventListener('click', function() {
        let id_pegawai = document.getElementById('id_pegawai').value;
        let nama_lokasi = document.getElementById('nama_lokasi').value;
        let tanggal_masuk = document.getElementById('tanggal_masuk').value;
        let jam_masuk = document.getElementById('jam_masuk').value;

        Webcam.snap(function (data_uri) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    window.location.href = './?route=home';
                }
            };
            xhttp.open("POST", "./?route=absensiAksi", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(
                'photo=' + encodeURIComponent(data_uri) +
                '&id_pegawai=' + encodeURIComponent(id_pegawai) +
                '&nama_lokasi=' + encodeURIComponent(nama_lokasi) +
                '&tanggal_masuk=' + encodeURIComponent(tanggal_masuk) +
                '&jam_masuk=' + encodeURIComponent(jam_masuk)
            );
        });
    });
</script>


