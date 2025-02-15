<?php
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM tb_lokasi WHERE id=$id")
    ?>
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                    Detail Lokasi
                </h2>
            </div>
        </div>
    </div>
</div>
<?php while ($row = mysqli_fetch_array($query)): ?>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <tbody>
                                        <tr>
                                            <td>Nama Lokasi</td>
                                            <td>: <?= $row['nama_lokasi'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>: <?= $row['alamat_lokasi'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Type</td>
                                            <td>: <?= $row['tipe_lokasi'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Lantitude</td>
                                            <td>: <?= $row['latitude'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Longitude</td>
                                            <td>: <?= $row['longitude'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Radius</td>
                                            <td>: <?= $row['radius'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jam Masuk</td>
                                            <td>: <?= $row['jam_masuk'] . " " . $row['zona_waktu']?></td>
                                        </tr>
                                        <tr>
                                            <td>Jam Pulang</td>
                                            <td>: <?= $row['jam_pulang'] . " " . $row['zona_waktu'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d1409.1746783801655!2d<?= $row['longitude'] ?>!3d<?= $row['latitude'] ?>!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1739597629843!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>