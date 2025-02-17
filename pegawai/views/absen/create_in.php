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


if ($m > $radius_masuk){
    $_SESSION['gagal'] = "Lokasi tidak sesuai";
    header("Location: ./?route=home");
    exit;
}
?>

<div class="card"><?= "Jarak: " . $m . " meter";?></div>
