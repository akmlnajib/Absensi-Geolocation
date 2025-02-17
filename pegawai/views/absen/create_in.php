<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php
if (isset($_POST['create_masuk'])) {
    // Validasi lokasi harus dipilih
    if (empty(trim($_POST['nama_lokasi']))) {
        $_SESSION['gagal'] = "Pilih lokasi terlebih dahulu!";
        header("Location: ./?route=home");
        exit;
    }

    // Ambil data dari form
    $latitude_pegawai = $_POST['latitude_pegawai_masuk'] ?? null;
    $longitude_pegawai = $_POST['longitude_pegawai_masuk'] ?? null;
    $latitude_masuk = $_POST['latitude_masuk'] ?? null;
    $longitude_masuk = $_POST['longitude_masuk'] ?? null;
    $radius_masuk = $_POST['radius_masuk'] ?? null;
    $tanggal_masuk = $_POST['tanggal_masuk'] ?? null;
    $jam_masuk = $_POST['jam_masuk'] ?? null;
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
} else {

    ?>
<!-- Page header -->
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                    Absensi Masuk
                </h2>
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
                        <div class="col-sm-2 col-lg-4">
                            <div class="card">
                                <div class="card-body" style="margin: auto;">
                                    <script src="webcam.js"></script>
                                    <div id="my_camera" style="width:320px; height:240px;"></div>
                                    <div class="mt-2" id="my_result"></div>
                                    <button class="btn btn-dark mt-2" id="take-foto"> Absen</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-lg-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script language="JavaScript">
        Webcam.attach('#my_camera');

        document.getElementById('take-foto').addEventListener('click', function() {
            Webcam.snap(function (data_uri) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';
                    if (this.readyState == 4 && this.status == 200) {
                    window.location.href = './?route=home';
                    }
                };
                xhttp.open("POST", "./?route=absensiAksi", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(
                    'photo=' + encodeURIComponent(data_uri)
                );
            });
        });
    </script>
<?php } ?>