<?php
$id_pegawai = $_SESSION['id'];
$query = mysqli_query($conn, "SELECT DATE_FORMAT(tanggal_masuk, '%m') AS bulan, COUNT(*) AS total 
                              FROM presensi 
                              WHERE MONTH(tanggal_masuk) = MONTH(CURDATE()) 
                              AND YEAR(tanggal_masuk) = YEAR(CURDATE()) 
                              AND id_pegawai = $id_pegawai
                              GROUP BY bulan 
                              ORDER BY bulan ASC");

$lokasi_presensi = $_SESSION['lokasi_absen'];
$result = mysqli_query($conn, "SELECT * FROM tb_lokasi WHERE nama_lokasi = '$lokasi_presensi'");

while ($lokasi = mysqli_fetch_array($result)) {
    $latitude_kantor = $lokasi['latitude'];
    $longitude_kantor = $lokasi['longitude'];
    $radius = $lokasi['radius'];
    $zona_waktu = $lokasi['zona_waktu'];
    $jam_pulang = $lokasi['jam_pulang'];
}
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js">
</script>
<script type="text/javascript">
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Bulan', 'Total'], // Header tabel
            <?php
            $data = [];
            while ($row = mysqli_fetch_array($query)) {
                $bulan = date("F", mktime(0, 0, 0, $row["bulan"], 1)); // Ubah angka bulan menjadi nama bulan
                $data[] = "['" . $bulan . "', " . $row["total"] . "]";
            }
            echo implode(",", $data); // Menggabungkan elemen array dengan koma
            ?>
        ]);

        var options = {
            title: 'Kehadiran Pegawai Bulan Ini',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>

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
        <div class="row">
            <?php

            $id_pegawai = $_SESSION['id'];
            date_default_timezone_set('Asia/Jakarta');
            $jamSekarang = date('H:i:s');
            $tanggal_hari_ini = date("Y-m-d");
            $waktu_sekarang = date('H:i:s');

            $lokasi = mysqli_query($conn, "SELECT * FROM tb_lokasi WHERE nama_lokasi = '$lokasi_presensi'");
            while ($data = mysqli_fetch_array($lokasi)):
                $jam_kantor = date('H:i:s', strtotime($data['jam_absen']));
            endwhile;
            $jam_mulai = $jam_kantor;
            $jam_selesai = "24:00:00";

            $cek = mysqli_query($conn, "SELECT * FROM presensi WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tanggal_hari_ini' ");

            if (mysqli_num_rows($cek) === 0) {
                if ($jamSekarang >= $jam_mulai && $jamSekarang <= $jam_selesai) { ?>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="card-title">Absensi Masuk</h3>
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
                                <form action="./?route=absensiMasuk" method="POST">
                                    <input type="hidden" id="latitude_pegawai_masuk" name="latitude_pegawai_masuk">
                                    <input type="hidden" id="longitude_pegawai_masuk" name="longitude_pegawai_masuk">
                                    <input type="hidden" value="<?= $latitude_kantor ?>" name="latitude_kantor">
                                    <input type="hidden" value="<?= $longitude_kantor ?>" name="longitude_kantor">
                                    <input type="hidden" value="<?= $radius ?>" name="radius">
                                    <input type="hidden" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
                                    <input type="hidden" name="jam_masuk" value="<?= $jamSekarang ?>">
                                    <button type="submit" name="create_masuk" class="btn btn-dark">Absen Masuk</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>

            <?php
            if (mysqli_num_rows($cek) > 0 && strtotime($waktu_sekarang) <= strtotime($jam_pulang)) { ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fa-solid fa-ban fa-4x me-2 text-danger"></i>
                            <h4 class="my-4">Belum waktunya pulang</h4>
                        </div>
                    </div>
                </div>
            <?php } elseif (strtotime($waktu_sekarang) >= strtotime($jam_pulang) && mysqli_num_rows($cek) === 0) {
                // Tidak ada aksi khusus jika kondisi ini terpenuhi.
            } else {
                while ($cek_out = mysqli_fetch_array($cek)) {
                    if ($cek_out['tanggal_masuk'] && is_null($cek_out['tanggal_keluar'])) { ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h3 class="card-title">Absensi Keluar</h3>
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
                                    <form action="./?route=absensiKeluar" method="POST">
                                        <input type="hidden" name="id" value="<?= $cek_out['id'] ?>">
                                        <input type="hidden" id="latitude_pegawai_masuk" name="latitude_pegawai_masuk">
                                        <input type="hidden" id="longitude_pegawai_masuk" name="longitude_pegawai_masuk">
                                        <input type="hidden" value="<?= $latitude_kantor ?>" name="latitude_kantor">
                                        <input type="hidden" value="<?= $longitude_kantor ?>" name="longitude_kantor">
                                        <input type="hidden" value="<?= $radius ?>" name="radius">
                                        <input type="hidden" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
                                        <input type="hidden" name="jam_keluar" value="<?= $jamSekarang ?>">
                                        <button type="submit" name="create_keluar" class="btn btn-dark">Absen Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fa-solid fa-clipboard-check fa-4x me-2 text-success"></i>
                                    <h4 class="my-4">Kehadiran sudah dicatat</h4>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
            } ?>

            <!-- Pie Chart -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div id="piechart"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>

    window.setTimeout("waktuMasuk()", 1000);
    const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    function waktuMasuk() {
        const waktu = new Date();
        setTimeout("waktuMasuk()", 1000);
        document.getElementById("tanggal_masuk").textContent = waktu.getDate();
        document.getElementById("bulan_masuk").textContent = namaBulan[waktu.getMonth()];
        document.getElementById("tahun_masuk").textContent = waktu.getFullYear();
        document.getElementById("jam_masuk").textContent = waktu.getHours();
        document.getElementById("menit_masuk").textContent = waktu.getMinutes();
        document.getElementById("detik_masuk").textContent = waktu.getSeconds();

        setTimeout(waktuMasuk, 1000);
    }

    window.setTimeout("waktuKeluar()", 1000);
    function waktuKeluar() {
        const waktu = new Date();
        setTimeout("waktuKeluar()", 1000);
        document.getElementById("tanggal_keluar").textContent = waktu.getDate();
        document.getElementById("bulan_keluar").textContent = namaBulan[waktu.getMonth()];
        document.getElementById("tahun_keluar").textContent = waktu.getFullYear();
        document.getElementById("jam_keluar").textContent = waktu.getHours();
        document.getElementById("menit_keluar").textContent = waktu.getMinutes();
        document.getElementById("detik_keluar").textContent = waktu.getSeconds();

        setTimeout(waktuKeluar, 1000);
    }

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
    }
</script>