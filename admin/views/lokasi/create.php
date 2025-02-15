<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lokasi = trim($_POST['nama_lokasi']);
    $alamat_lokasi = trim($_POST['alamat_lokasi']);
    $tipe_lokasi = trim($_POST['tipe_lokasi']);
    $latitude = trim($_POST['lantitude']);
    $longitude = trim($_POST['longitude']);
    $url_lokasi = trim($_POST['url_lokasi']);
    $radius = trim($_POST['radius']);
    $zona_waktu = "WIB";
    $jam_masuk = "09:00:00";
    $jam_pulang = "17:00:00";

    // Validasi input tidak boleh kosong
    if (empty($nama_lokasi) || empty($alamat_lokasi) || empty($tipe_lokasi) || empty($latitude) || empty($longitude) || empty($url_lokasi) || empty($radius) || empty($jam_masuk) || empty($jam_pulang)) {
        $_SESSION['error'] = "Semua kolom wajib diisi.";
        header("Location: ./?route=lokasiTambah");
        exit;
    }

    // Query dengan prepared statement
    $query = "INSERT INTO tb_lokasi (nama_lokasi, alamat_lokasi, tipe_lokasi, latitude, longitude, url_lokasi, radius, zona_waktu, jam_masuk, jam_pulang) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssssss", $nama_lokasi, $alamat_lokasi, $tipe_lokasi, $latitude, $longitude, $url_lokasi, $radius, $zona_waktu, $jam_masuk, $jam_pulang);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Data lokasi berhasil ditambahkan!";
        header("Location: ./?route=lokasi");
        exit;
    } else {
        $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($conn);
        header("Location: ./?route=lokasiTambah");
        exit;
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
                    Tambah Data Lokasi
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
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lokasi</label>
                                    <input type="text" class="form-control" name="nama_lokasi" placeholder="POS 1">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lokasi</label>
                                    <input type="text-area" class="form-control" name="alamat_lokasi" placeholder="Jl. Nangka 1">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tipe Lokasi</label>
                                    <select name="tipe_lokasi" class="form-control form-select">
										<option>Pilih tipe lokasi</option>
                                        <option value="Kantor">Kantor</option>
                                        <option value="POS Jaga">POS Jaga</option>
									</select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Langtitude</label>
                                    <input type="text" class="form-control" name="lantitude" placeholder="6.29123023">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" class="form-control" name="longitude" placeholder="102.29123023">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">URL Lokasi</label>
                                    <input type="text" class="form-control" name="url_lokasi" placeholder="102.29123023">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Radius</label>
                                    <input type="number" class="form-control" name="radius" placeholder="100">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jam Masuk</label>
                                    <input type="time" class="form-control" name="jam_masuk" value="09:00:00" disabled="">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jam Pulang</label>
                                    <input type="time" class="form-control" name="jam_pulang" value="17:00:00" disabled="">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Zona Waktu</label>
                                    <input type="number" class="form-control" name="zona_waktu" disabled="" placeholder="WIB"
                                    value="WIB">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
						<button type="submit" name="submit" class="btn btn-dark">
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