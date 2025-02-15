<?php
require_once('../../config.php');

if (isset($_POST['update'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $jabatan = htmlspecialchars(trim($_POST['jabatan']));

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($jabatan)) {
            $_SESSION['error'] = "Nama jabatan wajib diisi";
        } elseif ($id <= 0) {
            $_SESSION['error'] = "ID jabatan tidak valid.";
        } else {
            // Menggunakan prepared statement untuk update
            $stmt = mysqli_prepare($conn, "UPDATE jabatan SET jabatan = ? WHERE id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $jabatan, $id);
                $execute = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                if ($execute) {
                    $_SESSION['success'] = "Berhasil memperbarui data";
                    header("Location: ./?route=jabatan");
                    exit();
                } else {
                    $_SESSION['error'] = "Gagal memperbarui data: " . mysqli_error($conn);
                }
            } else {
                $_SESSION['error'] = "Kesalahan dalam persiapan query.";
            }
        }
    }
}

// Mengambil data jabatan berdasarkan ID dari GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nama_jabatan = "";

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT jabatan FROM jabatan WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nama_jabatan);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!-- Page header -->
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                    Ubah Data
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
                <form action="" method="POST" class="card">
                    <div class="card-body">
                        <div class="row row-cards">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Jabatan</label>
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="text" name="jabatan" class="form-control"
                                        placeholder="Masukkan nama jabatan" value="<?= htmlspecialchars($nama_jabatan) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-dark" name="update">
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
                            Simpan
                        </button>
                        <a href="./?route=jabatan" class="btn btn-dark">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
