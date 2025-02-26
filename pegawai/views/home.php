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
    .diagram {
        display: grid;
        grid-template-columns: auto auto auto auto;
        font-size: 20px;
        text-align: center;
        font-weight: bold;
        justify-content: center;
    }
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js">
</script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
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
            pieHole: 0.4 // Untuk tampilan donat (opsional)
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
        <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Absensi Masuk</h3>
                        <?php
                        $id_pegawai = $_SESSION['id'];
                        $tanggal_hari_ini = date("Y-m-d");
                        $cek = mysqli_query($conn, "SELECT * FROM presensi WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tanggal_hari_ini' ");

                        if (mysqli_num_rows($cek) === 0) {
                            ?>
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
                                            <input type="hidden" id="latitude_pegawai_masuk" name="latitude_pegawai_masuk">
                                            <input type="hidden" id="longitude_pegawai_masuk" name="longitude_pegawai_masuk">
                                            <input type="hidden" value="<?= $latitude_kantor ?>" name="latitude_kantor">
                                            <input type="hidden" value="<?= $longitude_kantor ?>" name="longitude_kantor">
                                            <input type="hidden" value="<?= $radius ?> " name="radius">
                                            <input type="hidden" name="tanggal_masuk" value="<?= date('Y-m-d') ?>">
                                            <?php
                                            date_default_timezone_set('Asia/Jakarta');
                                            $jamSekarang = date('H:i:s');
                                            ?>
                                            <input type="hidden" name="jam_masuk" value="<?= $jamSekarang ?>">

                                        </div>
                                    </div>
                                    <button type="submit" name="create_masuk" class="btn btn-dark">Absen
                                        Masuk</button>
                                </form>
                            </center>
                            </p>
                        <?php } else { ?>
                            <h4> Anda telah absen</h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Absensi Keluar</h3>
                        <?php
                        date_default_timezone_set('Asia/Jakarta');
                        $jamSekarang = date('H:i:s');
                        $waktu_sekarang = date('H:i:s');

                        if (strtotime($waktu_sekarang) <= strtotime($jam_pulang)) {
                            ?>
                            Belum waktunya pulang
                        <?php } elseif (strtotime($waktu_sekarang) >= strtotime($jam_pulang) && mysqli_num_rows($cek) === 0) { ?>
                            Silakan melakukan absensi masuk terlebih dahulu
                        <?php } else {
                            
                            while ($cek_out = mysqli_fetch_array($cek)) {
                                if ($cek_out['tanggal_masuk'] && is_null($cek_out['tanggal_keluar']))
                                { ?>
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
                                        <form action="./?route=absensiKeluar" method="POST">
                                            <div class="col-sm-6 col-md-4">
                                                <div class="mb-3">
                                            <input type="hidden" name="id" value="<?= $cek_out['id'] ?>" >
                                                    <input type="hidden" id="latitude_pegawai_masuk" name="latitude_pegawai_masuk">
                                                    <input type="hidden" id="longitude_pegawai_masuk"
                                                        name="longitude_pegawai_masuk">
                                                    <input type="hidden" value="<?= $latitude_kantor ?>" name="latitude_kantor">
                                                    <input type="hidden" value="<?= $longitude_kantor ?>" name="longitude_kantor">
                                                    <input type="hidden" value="<?= $radius ?> " name="radius">
                                                    <input type="hidden" name="tanggal_keluar" value="<?= date('Y-m-d') ?>">
                                                    <input type="hidden" name="jam_keluar" value="<?= $jamSekarang ?>">

                                                </div>
                                            </div>
                                            <button type="submit" name="create_keluar" class="btn btn-dark">Absen
                                                Keluar</button>
                                        </form>
                                    </center>
                                    </p>
                                    <?php
                                } else {
                                    ?>
                                    Anda Telah melakukan absen
                                <?php }
                            }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body" class="chart-sparkline chart-sparkline-square">
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