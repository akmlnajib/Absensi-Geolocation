<?php

?>
<style>
    .parent_date {
        display: grid;
        grid-template-columns: auto auto auto auto;
        font-size: 20px;
        text-align: center;
        font-weight: bold;
        justify-content: center;
    }

    .parent_time {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 20px;
        text-align: center;
        justify-content: center;
    }
</style>
<!-- Page header -->
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                    Dashboard
                </h2>
            </div>
        </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="row row-cards">
                    <div class="col-sm-2 col-lg-3">
                    </div>
                    <div class="col-sm-2 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Absensi</h3>

                                <p class="text-secondary">
                                <div class="parent_date">
                                    <div id="tanggal_masuk" class="me-2"></div>
                                    <div id="bulan_masuk" class="me-2"></div>
                                    <div id="tahun_masuk" class="me-2"></div>
                                </div>
                                <div class="parent_time">
                                    <div id="jam_masuk"></div>
                                    <div>:</div>
                                    <div id="menit_masuk"></div>
                                    <div>:</div>
                                    <div id="detik_masuk"></div>
                                </div>
                                <center>
                                    <form action="./?route=absensiMasuk" method="POST">
                                        <div class="col-sm-6 col-md-4">
                                            <div class="mb-3">
                                                <select id="lokasi_select_masuk" name="nama_lokasi"
                                                    class="form-control form-select">
                                                    <option value="">Pilih Lokasi Absensi</option>
                                                    <?php
                                                    $get_query = mysqli_query($conn, "SELECT * FROM tb_lokasi");
                                                    $lokasi_data = [];
                                                    while ($row = mysqli_fetch_assoc($get_query)) {
                                                        $lokasi_data[$row['nama_lokasi']] = [
                                                            'latitude' => $row['latitude'],
                                                            'longitude' => $row['longitude'],
                                                            'radius' => $row['radius'],
                                                        ];
                                                        ?>
                                                        <option value="<?= $row['nama_lokasi']; ?>">
                                                            <?= $row['nama_lokasi']; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <input type="text" id="latitude_pegawai_masuk"
                                                    name="latitude_pegawai_masuk">
                                                <input type="text" id="longitude_pegawai_masuk"
                                                    name="longitude_pegawai_masuk">
                                                <input type="text" id="latitude_masuk" name="latitude_masuk">
                                                <input type="text" id="longitude_masuk" name="longitude_masuk">
                                                <input type="text" id="radius_masuk" name="radius_masuk">
                                                <input type="text" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
                                                <?php
                                                date_default_timezone_set('Asia/Jakarta');
                                                $jamSekarang = date('H:i:s');
                                                ?>
                                                <input type="text" name="jam_masuk" value="<?= $jamSekarang ?>">

                                            </div>
                                        </div>
                                        <button type="submit" name="create_masuk" class="btn btn-dark">Absen Masuk</button>
                                    </form>
                                </center>
                                </p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Absensi</h3>

                                <p class="text-secondary">
                                <div class="parent_date">
                                    <div id="tanggal_keluar" class="me-2"></div>
                                    <div id="bulan_keluar" class="me-2"></div>
                                    <div id="tahun_keluar" class="me-2"></div>
                                </div>
                                <div class="parent_time">
                                    <div id="jam_keluar"></div>
                                    <div>:</div>
                                    <div id="menit_keluar"></div>
                                    <div>:</div>
                                    <div id="detik_keluar"></div>
                                </div>
                                <center>
                                    <form action="./?absensiKeluar" method="POST">
                                        <div class="col-sm-6 col-md-4">
                                            <div class="mb-3">
                                                <select id="lokasi_select_keluar" name="nama_lokasi"
                                                    class="form-control form-select">
                                                    <option value="">Pilih Lokasi Absensi</option>
                                                    <?php
                                                    $get_query = mysqli_query($conn, "SELECT * FROM tb_lokasi");
                                                    $lokasi_data = [];
                                                    while ($row = mysqli_fetch_assoc($get_query)) {
                                                        $lokasi_data[$row['nama_lokasi']] = [
                                                            'latitude' => $row['latitude'],
                                                            'longitude' => $row['longitude'],
                                                            'radius' => $row['radius'],
                                                            'zona_waktu' => $row['zona_waktu']
                                                        ];
                                                        ?>
                                                        <option value="<?= $row['nama_lokasi']; ?>">
                                                            <?= $row['nama_lokasi']; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <input type="text" id="latitude_pegawai_keluar"
                                                    name="latitude_pegawai_keluar">
                                                <input type="text" id="longitude_pegawai_keluar"
                                                    name="longitude_pegawai_keluar">
                                                <input type="text" id="latitude_keluar" name="latitude">
                                                <input type="text" id="longitude_keluar" name="longitude">
                                                <input type="text" id="radius_keluar" name="radius">
                                                <input type="text" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
                                                <?php
                                                date_default_timezone_set('Asia/Jakarta');
                                                $jamSekarang = date('H:i:s');
                                                ?>
                                                <input type="text" name="jam_masuk" value="<?= $jamSekarang ?>">
                                            </div>
                                        </div>
                                        <a href="#" class="btn btn-dark">Absen Keluar</a>
                                    </form>
                                </center>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-lg-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    function waktuMasuk() {
        const waktu = new Date();

        document.getElementById("tanggal_masuk").textContent = waktu.getDate();
        document.getElementById("bulan_masuk").textContent = namaBulan[waktu.getMonth()];
        document.getElementById("tahun_masuk").textContent = waktu.getFullYear();
        document.getElementById("jam_masuk").textContent = waktu.getHours().toString().padStart(2, '0');
        document.getElementById("menit_masuk").textContent = waktu.getMinutes().toString().padStart(2, '0');
        document.getElementById("detik_masuk").textContent = waktu.getSeconds().toString().padStart(2, '0');

        setTimeout(waktuMasuk, 1000);
    }

    waktuMasuk();

    function waktuKeluar() {
        const waktu = new Date();

        document.getElementById("tanggal_keluar").textContent = waktu.getDate();
        document.getElementById("bulan_keluar").textContent = namaBulan[waktu.getMonth()];
        document.getElementById("tahun_keluar").textContent = waktu.getFullYear();
        document.getElementById("jam_keluar").textContent = waktu.getHours().toString().padStart(2, '0');
        document.getElementById("menit_keluar").textContent = waktu.getMinutes().toString().padStart(2, '0');
        document.getElementById("detik_keluar").textContent = waktu.getSeconds().toString().padStart(2, '0');

        setTimeout(waktuKeluar, 1000);
    }

    waktuKeluar();

    geoLocation();
    function geoLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Browser tidak mendukung geolocation");
        }
    }

    function showPosition(position) {
        $('#latitude_pegawai_masuk').val(position.coords.latitude);
        $('#longitude_pegawai_masuk').val(position.coords.longitude);
        $('#latitude_pegawai_keluar').val(position.coords.latitude);
        $('#longitude_pegawai_keluar').val(position.coords.longitude);
    }
</script>
<script>
    // Data lokasi dalam bentuk JavaScript Object
    const lokasiData = <?= json_encode($lokasi_data) ?>;

    // Event listener untuk perubahan pada select lokasi
    document.getElementById('lokasi_select_masuk').addEventListener('change', function () {
        const lokasi = this.value;

        if (lokasi in lokasiData) {
            document.getElementById('latitude_masuk').value = lokasiData[lokasi].latitude;
            document.getElementById('longitude_masuk').value = lokasiData[lokasi].longitude;
            document.getElementById('radius_masuk').value = lokasiData[lokasi].radius;
        }
    });
    document.getElementById('lokasi_select_keluar').addEventListener('change', function () {
        const lokasi = this.value;

        if (lokasi in lokasiData) {
            document.getElementById('latitude_keluar').value = lokasiData[lokasi].latitude;
            document.getElementById('longitude_keluar').value = lokasiData[lokasi].longitude;
            document.getElementById('radius_keluar').value = lokasiData[lokasi].radius;
        }
    });
</script>