<?php
require_once('../../config.php');

if (isset($_POST['submit'])) {
    $jabatan = htmlspecialchars(trim($_POST['jabatan']));

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($jabatan)) {
            $_SESSION['error'] = "Nama jabatan wajib diisi";
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO jabatan(jabatan) VALUES(?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $jabatan);
                $execute = mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                if ($execute) {
                    $_SESSION['success'] = "Berhasil menyimpan data";
                    header("Location: ./?route=jabatan");
                    exit();
                } else {
                    $_SESSION['error'] = "Gagal menyimpan data: " . mysqli_error($conn);
                }
            } else {
                $_SESSION['error'] = "Kesalahan dalam persiapan query.";
            }
        }
    }
}
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
                    Tambah Data
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
                                    <input type="text" name="jabatan" class="form-control"
                                        placeholder="Masukkan nama jabatan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-green" name="submit">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
