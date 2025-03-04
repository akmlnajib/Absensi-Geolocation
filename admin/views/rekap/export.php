<?php
session_start();
ob_start();
require '../../../config.php';
require '../../../assets/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$date_from = $_POST['date_from'];
$date_to = $_POST['date_to'];
$query = mysqli_query($conn, "SELECT presensi.*, pegawai.nama, pegawai.nip, pegawai.lokasi_absen FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE tanggal_masuk BETWEEN '$date_from' AND '$date_to' ORDER BY tanggal_masuk ASC ");

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

$activeWorksheet->setCellValue('A1', 'REKAP ABSENSI');
$activeWorksheet->setCellValue('A2', 'Tanggal Awal');
$activeWorksheet->setCellValue('A3', 'Tanggal Akhir');
$activeWorksheet->setCellValue('C2', date('d F Y',strtotime($date_from)));
$activeWorksheet->setCellValue('C3', date('d F Y',strtotime($date_to)));
$activeWorksheet->setCellValue('A5', 'NO');
$activeWorksheet->setCellValue('B5', 'NAMA PEGAWAI');
$activeWorksheet->setCellValue('C5', 'NIP');
$activeWorksheet->setCellValue('D5', 'TANGGAL MASUK');
$activeWorksheet->setCellValue('E5', 'JAM MASUK');
$activeWorksheet->setCellValue('F5', 'TANGGAL KELUAR');
$activeWorksheet->setCellValue('G5', 'JAM KELUAR');
$activeWorksheet->setCellValue('H5', 'TOTAL JAM KERJA');
$activeWorksheet->setCellValue('I5', 'TERLAMBAT');


$activeWorksheet->mergeCells('A1:F1');
$activeWorksheet->mergeCells('A2:B2');
$activeWorksheet->mergeCells('A3:B3');

$no = 1;
$row = 6;

while($data = mysqli_fetch_array($query)){
    
$lokasi_absen = $_SESSION['lokasi_absen'];
$lokasi = mysqli_query($conn, "SELECT * FROM tb_lokasi WHERE nama_lokasi = '$lokasi_absen'");
while ($result = mysqli_fetch_array($lokasi)):
$jam_kantor = date('H:i:s', strtotime($result['jam_masuk']));
endwhile;
if (!empty($data['tanggal_keluar']) && !empty($data['jam_keluar'])) {
    $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($data['tanggal_masuk'] . ' ' . $jam_kantor));
    $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($data['tanggal_keluar'] . ' ' . $data['jam_keluar']));

    $timestamp_masuk = strtotime($jam_tanggal_masuk);
    $timestamp_keluar = strtotime($jam_tanggal_keluar);

    $hitung = $timestamp_keluar - $timestamp_masuk;
    $jam_kerja = floor($hitung / 3600);
    $hitung -= $jam_kerja * 3600;
    $menit_kerja = floor($hitung / 60);
}

    $jam_masuk = !empty($data['jam_masuk']) ? date('H:i:s', strtotime($data['jam_masuk'])) : null;
    $timestamp_jam_pegawai = $jam_masuk ? strtotime($jam_masuk) : null;
    $timestamp_jam_kantor = !empty($jam_kantor) ? strtotime($jam_kantor) : null;

    $terlambat = $timestamp_jam_pegawai - $timestamp_jam_kantor;
    $jam_terlambat = floor($terlambat / 3600);
    $terlambat -= $jam_terlambat * 3600;
    $menit_terlambat = floor($terlambat / 60);

    $activeWorksheet->setCellValue('A'. $row, $no);
    $activeWorksheet->setCellValue('B'. $row, $data['nama']);
    $activeWorksheet->setCellValue('C'. $row, $data['nip']);
    $activeWorksheet->setCellValue('D'. $row, $data['tanggal_masuk']);
    $activeWorksheet->setCellValue('E'. $row, $data['jam_masuk']);
    $activeWorksheet->setCellValue('F'. $row, $data['tanggal_keluar']);
    $activeWorksheet->setCellValue('G'. $row, $data['jam_keluar']);
    $activeWorksheet->setCellValue('H'. $row, $jam_kerja . ' Jam ' . $menit_kerja . ' Menit');
    if ($jam_terlambat < 0 || $menit_terlambat < 0 || ($jam_terlambat == 0 && $menit_terlambat == 0)) {
        $activeWorksheet->setCellValue('I' . $row, '00:00:00');
    } else {
        $activeWorksheet->setCellValue('I' . $row, $jam_terlambat . ' Jam ' . $menit_terlambat . ' Menit');
    }    
    

    $no++;
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Absensi.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
