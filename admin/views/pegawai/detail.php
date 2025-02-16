<?php
$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.* FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE pegawai.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();

?>

<!-- Page header -->
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-square-rounded-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                        <path d="M15 12h-6" />
                        <path d="M12 9v6" />
                    </svg>
                    Detail Data Pegawai
                </h2>
            </div>
        </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-body">
                        <a href="./?route=pegawai" class="btn btn-dark">Kembali</a>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <tbody>
                                    <tr>
                                        <td>NIP</td>
                                        <td>: <?= $row['nip'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td>: <?= $row['nama'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td>: <?= $row['jenis_kelamin'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>: <?= $row['alamat'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>No Handphone</td>
                                        <td>: <?= $row['no_hp'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>: <?= $row['jabatan'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Username</td>
                                        <td>: <?= $row['username'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Role</td>
                                        <td>: <?= $row['role'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:
                                            <?php if ($row['status'] === "Aktif") { ?>
                                                <span class="badge bg-success me-1"></span> Aktif
                                            <?php } elseif ($row['status'] === "Non-Aktif") {
                                            ?>
                                                <span class="badge bg-danger me-1"></span> Non-Aktif
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <?php
                $foto_path = "../../assets/img/foto/" . $row['foto'];

                if (!empty($row['foto']) && file_exists($foto_path)): ?>
                    <img src="<?= htmlspecialchars($foto_path); ?>" style="width: 500px; border-radius: 15px;"
                        alt="Foto Pegawai">
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">

                            <img src="https://www.magnolia-cms.com/dam/jcr:46c84a0f-54ce-456c-a93b-e3a8cb2d3d0f/User-avatar.png"
                                style="width: 500px; border-radius: 15px;" alt="Foto Default">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>