<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Id lokasi tidak ditemukan!";
    header("Location: ./?route=lokasi");
    exit;
}

$id = $_GET['id'];

$query = "SELECT * FROM tb_lokasi WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    $_SESSION['error'] = "Data lokasi tidak ditemukan!";
    header("Location: ./?route=lokasi");
    exit;
}

mysqli_stmt_close($stmt);

// Jika form disubmit untuk update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lokasi = trim($_POST['nama_lokasi']);
    $alamat_lokasi = trim($_POST['alamat_lokasi']);
    $tipe_lokasi = trim($_POST['tipe_lokasi']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $radius = trim($_POST['radius']);
    $zona_waktu = "WIB";
    $jam_masuk = trim($_POST['jam_masuk']);
    $jam_pulang = trim($_POST['jam_pulang']);

    // Validasi input tidak boleh kosong
    if (empty($nama_lokasi) || empty($alamat_lokasi) || empty($tipe_lokasi) || empty($latitude) || empty($longitude) || empty($radius) || empty($jam_masuk) || empty($jam_pulang)) {
        $_SESSION['error'] = "Semua kolom wajib diisi.";
        header("Location: ./?route=lokasiUbah&id=$id_lokasi");
        exit;
    }

    // Query update dengan prepared statement
    $query = "UPDATE tb_lokasi 
              SET nama_lokasi = ?, alamat_lokasi = ?, tipe_lokasi = ?, latitude = ?, longitude = ?, radius = ?, zona_waktu = ?, jam_masuk = ?, jam_pulang = ? 
              WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssi", $nama_lokasi, $alamat_lokasi, $tipe_lokasi, $latitude, $longitude, $radius, $zona_waktu, $jam_masuk, $jam_pulang, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data lokasi berhasil diperbarui!";
        header("Location: ./?route=lokasi");
        exit;
    } else {
        $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($conn);
        header("Location: ./?route=lokasiUbah&id=$id");
        exit;
    }
}
?>

<!-- Page header -->
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                Ubah Data Lokasi</h2>
            </div>
        </div>
    </div>
</div>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <form action="" method="POST" class="card">
                    <div class="card-body">
                        <div class="row row-cards">
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lokasi</label>
                                    <input type="text" class="form-control" name="nama_lokasi" value="<?= htmlspecialchars($data['nama_lokasi']) ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lokasi</label>
                                    <input type="text" class="form-control" name="alamat_lokasi" value="<?= htmlspecialchars($data['alamat_lokasi']) ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tipe Lokasi</label>
                                    <select name="tipe_lokasi" class="form-control form-select" required>
                                        <option value="Kantor" <?= ($data['tipe_lokasi'] == "Kantor") ? "selected" : "" ?>>Kantor</option>
                                        <option value="POS Jaga" <?= ($data['tipe_lokasi'] == "POS Jaga") ? "selected" : "" ?>>POS Jaga</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" class="form-control" name="latitude" value="<?= htmlspecialchars($data['latitude']) ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" class="form-control" name="longitude" value="<?= htmlspecialchars($data['longitude']) ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Radius</label>
                                    <input type="number" class="form-control" name="radius" value="<?= htmlspecialchars($data['radius']) ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Zona Waktu</label>
                                    <input type="text" class="form-control" name="zona_waktu" value="<?= htmlspecialchars($data['zona_waktu']) ?>" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jam Masuk</label>
                                    <input type="time" class="form-control" name="jam_masuk" value="<?= htmlspecialchars($data['jam_masuk']) ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jam Pulang</label>
                                    <input type="time" class="form-control" name="jam_pulang" value="<?= htmlspecialchars($data['jam_pulang']) ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" name="update" class="btn btn-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>    
                        Simpan</button>
                        <a href="./?route=lokasi" class="btn btn-dark">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
