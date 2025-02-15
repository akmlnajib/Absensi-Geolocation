<?php
$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM tb_lokasi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();

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
                        <iframe src="<?= $row['url_lokasi'] ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>