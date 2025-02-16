<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = trim($_POST['nip']);
    $nama = trim($_POST['nama']);
    $jenis_kelamin = trim($_POST['jenis_kelamin']);
    $alamat = trim($_POST['alamat']);
    $no_hp = trim($_POST['no_hp']);
    $jabatan = trim($_POST['jabatan']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $ulangi_password = $_POST['ulangi_password'];
    $role = trim($_POST['role']);
    $status = trim($_POST['status']);

    // Validasi input tidak boleh kosong
    if (empty($nip) || empty($nama) || empty($jenis_kelamin) || empty($alamat) || empty($no_hp) || empty($jabatan) || empty($username) || empty($role) || empty($status) || empty($password) || empty($ulangi_password)) {
        $_SESSION['error'] = "Semua kolom wajib diisi.";
        header("Location: ./?route=tambahPegawai");
        exit;
    }

    // Validasi password sama
    if ($password !== $ulangi_password) {
        $_SESSION['error'] = "Password dan konfirmasi password tidak cocok.";
        header("Location: ./?route=tambahPegawai");
        exit;
    }

    // Enkripsi password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Proses upload foto
    $nama_file = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $nama_file = time() . '_' . basename($file['name']);
        $file_tmp = $file['tmp_name'];
        $file_direktori = __DIR__ . "/../../../assets/img/foto/" . $nama_file;

        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $format_valid = ['jpg', 'png', 'jpeg'];
        $max_size = 2 * 1024 * 1024;

        if (!in_array(strtolower($ekstensi), $format_valid)) {
            $_SESSION['error'] = "Format file tidak valid. Gunakan JPG, PNG, atau JPEG.";
            header("Location: ./?route=tambahPegawai");
            exit;
        }

        if ($file['size'] > $max_size) {
            $_SESSION['error'] = "Ukuran file terlalu besar (maksimal 2MB).";
            header("Location: ./?route=tambahPegawai");
            exit;
        }

        if (!move_uploaded_file($file_tmp, $file_direktori)) {
            $_SESSION['error'] = "Gagal mengunggah foto.";
            header("Location: ./?route=tambahPegawai");
            exit;
        }
    }

    // Insert ke tabel pegawai
    $query_pegawai = "INSERT INTO pegawai (nip, nama, jenis_kelamin, alamat, no_hp, jabatan, foto) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_pegawai = mysqli_prepare($conn, $query_pegawai);
    mysqli_stmt_bind_param($stmt_pegawai, "sssssss", $nip, $nama, $jenis_kelamin, $alamat, $no_hp, $jabatan, $nama_file);

    if (mysqli_stmt_execute($stmt_pegawai)) {
        // Ambil ID pegawai yang baru dibuat
        $id_pegawai = mysqli_insert_id($conn);

        // Insert ke tabel users
        $query_users = "INSERT INTO users (id_pegawai, username, password, role, status) 
        VALUES (?, ?, ?, ?, ?)";
        $stmt_users = mysqli_prepare($conn, $query_users);
        mysqli_stmt_bind_param($stmt_users, "issss", $id_pegawai, $username, $password_hash, $role, $status);

        if (mysqli_stmt_execute($stmt_users)) {
            $_SESSION['success'] = "Data pegawai berhasil ditambahkan!";
            header("Location: ./?route=pegawai");
            exit;
        } else {
            $_SESSION['error'] = "Gagal menambahkan user: " . mysqli_error($conn);
            header("Location: ./?route=tambahPegawai");
            exit;
        }
    } else {
        $_SESSION['error'] = "Gagal menambahkan pegawai: " . mysqli_error($conn);
        header("Location: ./?route=tambahPegawai");
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
                    Tambah Data Pegawai
                </h2>
            </div>
        </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <form action="" method="POST" class="card" enctype="multipart/form-data">
                <div class="col-12">
                    <div class="card-body">
                        <div class="row row-cards">
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">NIP</label>
                                    <input type="number" class="form-control" name="nip" placeholder="1234567">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" name="nama" placeholder="POS 1">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control form-select">
                                        <option>Pilih tipe pegawai</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input type="text-area" class="form-control" name="alamat"
                                        placeholder="Jl. Nangka 1">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">No Handphone</label>
                                    <input type="number" class="form-control" name="no_hp">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jabatan</label>
                                    <select name="jabatan" class="form-control form-select">
                                        <option>Pilih Jabatan</option>
                                        <?php
                                        $get_query = mysqli_query($conn, "SELECT * FROM jabatan ORDER BY jabatan ASC");
                                        while ($row = mysqli_fetch_assoc($get_query)) {
                                            $nama_jabatan = $row['jabatan']; ?>
                                            <option value="<?= $nama_jabatan ?>"><?= $nama_jabatan ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control form-select">
                                        <option>Pilih Status</option>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Non-Aktif">Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card-body">
                        <div class="row row-cards">
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Foto</label>
                                    <input type="file" class="form-control" name="foto">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" placeholder="1234567">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="POS 1">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ulangi Password</label>
                                    <input type="password" class="form-control" name="ulangi_password"
                                        placeholder="POS 1">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-control form-select">
                                        <option>Pilih tipe pegawai</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Pegawai">Pegawai</option>
                                    </select>
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
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                            Simpan</button>
                        <a href="./?route=pegawai" class="btn btn-dark">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>