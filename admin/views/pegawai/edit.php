<?php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Id pegawai tidak ditemukan!";
    header("Location: ./?route=pegawai");
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.* 
          FROM users 
          JOIN pegawai ON users.id_pegawai = pegawai.id 
          WHERE pegawai.id = ?";
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Query error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    $_SESSION['error'] = "Data pegawai tidak ditemukan!";
    header("Location: ./?route=pegawai");
    exit;
}

mysqli_stmt_close($stmt);

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

    if (empty($nip) || empty($nama) || empty($jenis_kelamin) || empty($alamat) || empty($no_hp) || empty($jabatan) || empty($username) || empty($role) || empty($status)) {
        $_SESSION['error'] = "Semua kolom wajib diisi.";
        header("Location: ./?route=ubahPegawai&id=$id");
        exit;
    }

    if (!empty($password)) {
        if ($password !== $ulangi_password) {
            $_SESSION['error'] = "Password dan konfirmasi password tidak cocok.";
            header("Location: ./?route=ubahPegawai&id=$id");
            exit;
        }
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $password_hash = $data['password'];
    }

    // **Handle Foto**
    $nama_file = $data['foto'];
    $upload_dir = __DIR__ . "/../../../assets/img/foto/";

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $nama_file_baru = time() . 'K3' . basename($file['name']);
        $file_tmp = $file['tmp_name'];
        $file_direktori = $upload_dir . $nama_file_baru;

        $ekstensi = strtolower(pathinfo($nama_file_baru, PATHINFO_EXTENSION));
        $format_valid = ['jpg', 'png', 'jpeg'];
        $max_size = 2 * 1024 * 1024;

        if (!in_array($ekstensi, $format_valid)) {
            $_SESSION['error'] = "Format file tidak valid. Gunakan JPG, PNG, atau JPEG.";
            header("Location: ./?route=ubahPegawai&id=$id");
            exit;
        }

        if ($file['size'] > $max_size) {
            $_SESSION['error'] = "Ukuran file terlalu besar (maksimal 2MB).";
            header("Location: ./?route=ubahPegawai&id=$id");
            exit;
        }

        if (move_uploaded_file($file_tmp, $file_direktori)) {
            // Hapus foto lama jika ada
            if (!empty($data['foto']) && file_exists($upload_dir . $data['foto'])) {
                unlink($upload_dir . $data['foto']);
            }
            $nama_file = $nama_file_baru;
        } else {
            $_SESSION['error'] = "Gagal mengunggah foto.";
            header("Location: ./?route=ubahPegawai&id=$id");
            exit;
        }
    }

    // **UPDATE Pegawai**
    $query_pegawai = "UPDATE pegawai SET nip = ?, nama = ?, jenis_kelamin = ?, alamat = ?, no_hp = ?, jabatan = ?, foto = ? WHERE id = ?";
    $stmt_pegawai = mysqli_prepare($conn, $query_pegawai);
    if (!$stmt_pegawai) {
        die("Query error: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt_pegawai, "sssssssi", $nip, $nama, $jenis_kelamin, $alamat, $no_hp, $jabatan, $nama_file, $id);

    if (mysqli_stmt_execute($stmt_pegawai)) {
        // **UPDATE Users**
        $query_update_user = "UPDATE users SET username = ?, password = ?, role = ?, status = ? WHERE id_pegawai = ?";
        $stmt_update_user = mysqli_prepare($conn, $query_update_user);
        if (!$stmt_update_user) {
            die("Query error: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_update_user, "ssssi", $username, $password_hash, $role, $status, $id);

        if (mysqli_stmt_execute($stmt_update_user)) {
            $_SESSION['success'] = "Data pegawai dan pengguna berhasil diperbarui!";
            header("Location: ./?route=pegawai");
            exit;
        } else {
            $_SESSION['error'] = "Gagal memperbarui user: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt_update_user);
    } else {
        $_SESSION['error'] = "Gagal memperbarui pegawai: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt_pegawai);
    header("Location: ./?route=ubahPegawai&id=$id");
    exit;
}
?>


<!-- Page header -->
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                    Ubah Data Pegawai
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
                            <input type="hidden" name="id" value="<?= $data['id']; ?>">
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">NIP</label>
                                    <input type="text" class="form-control" name="nip"
                                        value="<?= htmlspecialchars($data['nip']); ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" class="form-control" name="nama"
                                        value="<?= htmlspecialchars($data['nama']); ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-control" name="jenis_kelamin" required>
                                        <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-Laki
                                        </option>
                                        <option value="Perempuan" <?= $data['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" class="form-control" name="alamat"
                                        value="<?= htmlspecialchars($data['alamat']) ?>">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">No Handphone</label>
                                    <input type="text" class="form-control" name="no_hp"
                                        value="<?= htmlspecialchars($data['no_hp']); ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Jabatan</label>
                                    <select name="jabatan" class="form-control form-select">
                                        <option value="<?= htmlspecialchars($data['jabatan']); ?>">
                                            <?= htmlspecialchars($data['jabatan']); ?></option>
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
                                    <select class="form-control" name="status" required>
                                        <option value="Aktif" <?= $data['status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif
                                        </option>
                                        <option value="Non-Aktif" <?= $data['status'] == 'Non-Aktif' ? 'selected' : ''; ?>>
                                            Non-Aktif</option>
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
                                    <?php if (!empty($data['foto'])): ?>
                                        <img src="../../assets/img/foto/<?= $data['foto']; ?>"
                                            width="100" alt="Foto Pegawai">
                                        
                                    <?php endif; ?>
                                    <input type="file" class="form-control mt-2" name="foto">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username"
                                        value="<?= htmlspecialchars($data['username']); ?>" required>
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
                                    <select class="form-control" name="role" required>
                                        <option value="Admin" <?= $data['role'] == 'Admin' ? 'selected' : ''; ?>>Admin
                                        </option>
                                        <option value="Pegawai" <?= $data['role'] == 'Pegawai' ? 'selected' : ''; ?>>
                                            Pegawai</option>
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