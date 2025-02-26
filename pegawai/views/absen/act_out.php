<?php 
$id_presensi = $_POST['id'];
$id_pegawai = $_POST['id_pegawai'];
$file_foto = $_POST['photo'];
$tanggal_keluar = $_POST['tanggal_keluar'];
$jam_keluar = $_POST['jam_keluar'];

// Pastikan direktori penyimpanan gambar ada
$directory = 'foto/';
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
}

// Proses penyimpanan foto
$foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
$foto = str_replace(' ', '+', $foto);
$data = base64_decode($foto);

$nama_file = $directory . 'keluar' . $id_pegawai . '_' . date('Y-m-d_H-i-s') .'.png'; // Tambahkan waktu untuk menghindari duplikasi
$file = 'keluar' . $id_pegawai . '_' . date('Y-m-d_H-i-s') . '.png';

if (file_put_contents($nama_file, $data) === false) {
    $_SESSION['error'] = "Gagal menyimpan foto!";
    header("Location: ./?route=home");
    exit;
}

// Simpan ke database dengan prepared statement untuk keamanan
$sql = "UPDATE presensi SET tanggal_keluar = ?, jam_keluar = ?, foto_keluar = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssi", $tanggal_keluar, $jam_keluar, $file, $id_presensi);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success'] = "Absensi keluar berhasil dicatat";
    header("Location: ./?route=home");
    exit;
} else {
    $_SESSION['error'] = "Absensi keluar gagal dicatat: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

// Redirect kembali
header("Location: ./?route=home");
exit;
