<?php 
$file_foto = $_POST['photo'];
$id_pegawai = $_POST['id'];
$tanggal_masuk = $_POST['tanggal_masuk'];
$jam_masuk = $_POST['jam_masuk'];

// Pastikan direktori penyimpanan gambar ada
$directory = 'foto/';
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
}

// Proses penyimpanan foto
$foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
$foto = str_replace(' ', '+', $foto);
$data = base64_decode($foto);

$nama_file = $directory . 'masuk' . $id_pegawai . '_' . date('Y-m-d_H-i-s') .'.png'; // Tambahkan waktu untuk menghindari duplikasi
$file = 'masuk' . $id_pegawai . '_' . date('Y-m-d_H-i-s') . '.png';

if (file_put_contents($nama_file, $data) === false) {
    $_SESSION['error'] = "Gagal menyimpan foto!";
    header("Location: ./?route=home");
    exit;
}

// Simpan ke database dengan prepared statement untuk keamanan
$stmt = mysqli_prepare($conn, "INSERT INTO presensi (id_pegawai, tanggal_masuk, jam_masuk, foto_masuk) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "isss", $id_pegawai, $tanggal_masuk, $jam_masuk, $file);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Kehadiran berhasil dicatat";
    header("Location: ./?route=home");
    exit;
} else {
    $_SESSION['error'] = "Kehadiran gagal dicatat: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

// Redirect kembali
header("Location: ./?route=home");
exit;
