<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php

if (isset($_POST['create_masuk'])) {
    $latitude_pegawai_masuk = $_POST['latitude_pegawai_masuk'];
    $longitude_pegawai_masuk = $_POST['longitude_pegawai_masuk'];
    $latitude_kantor = $_POST['latitude_kantor'];
    $longitude_kantor = $_POST['longitude_kantor'];
    $radius = $_POST['radius'];
    $jam_masuk = $_POST['jam_masuk'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
}


$earth_radius = 6378000; // Radius bumi dalam meter

$lat1 = deg2rad($latitude_pegawai_masuk);
$lon1 = deg2rad($longitude_pegawai_masuk);
$lat2 = deg2rad($latitude_kantor);
$lon2 = deg2rad($longitude_kantor);

$dlat = $lat2 - $lat1;
$dlon = $lon2 - $lon1;
$b = sin($dlat / 2);

$a = $b * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

$m = $earth_radius * $c; // Jarak dalam meter

if ($m > $radius) {
    $_SESSION['error'] = "Jarak anda melebihi batas radius!";
    header("Location: ./?route=home");
    exit;
} else {
    ?>

    <!-- Page header -->
    <div class="page-header d-print-none text-white">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Kehadiran Masuk</h2>
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
                                        <input type="hidden" id="id" value="<?= $_SESSION['id'] ?>">
                                        <input type="hidden" id="tanggal_masuk" value="<?= $tanggal_masuk ?>">
                                        <input type="hidden" id="jam_masuk" value="<?= $jam_masuk ?>">
                                        <div id="my_camera" class="mb-2" style="width:320px; height:240px;"></div>
                                        <div id="my_result" class="mb-2"></div>
                                        <button class="btn btn-dark" id="take">Masuk</button>
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
    <script language="JavaScript">
        Webcam.attach('#my_camera');
        
        let id = document.getElementById('id').value;
        let tanggal_masuk = document.getElementById('tanggal_masuk').value;
        let jam_masuk = document.getElementById('jam_masuk').value;

        document.getElementById('take').addEventListener('click', function(){
            Webcam.snap(function (data_uri) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {    
                    document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                    window.location.href = './?route=home';
                    }
                };
                xhttp.open("POST", "./?route=absensiAksiIn", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(
                    'photo=' + encodeURIComponent(data_uri) +
                    '&id=' + id +
                    '&tanggal_masuk=' + tanggal_masuk +
                    '&jam_masuk=' + jam_masuk
                );
            });
        });
    </script>
<?php } ?>