<?php

if (isset($_POST['create_masuk'])) {
    $latitude_pegawai = $_POST['latitude_pegawai_masuk'];
    $longitude_pegawai = $_POST['longitude_pegawai_masuk'];
    $latitude_masuk = $_POST['latitude_masuk'];
    $longitude_masuk = $_POST['longitude_masuk'];
    $radius_masuk = $_POST['radius_masuk'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jam_masuk = $_POST['jam_masuk'];
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
$m = $earthRadius * $c;
?>

<div class="card"><?= "Jarak: " . $m . " meter";?></div>
