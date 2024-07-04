<?php 
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = 'Rekap Ketidakhadiran';
include_once ('../../config.php');

require('../../assets/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$filter_tahun_bulan = $_POST['filter_tahun']. '-' . $_POST['filter_bulan'];
$filter_bulan = $_POST['filter_bulan'];
$filter_tahun = $_POST['filter_tahun'];
$result = mysqli_query($conn, "SELECT ketidakhadiran.*, anggota.kelas FROM ketidakhadiran JOIN anggota ON ketidakhadiran.id_anggota = anggota.id WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$filter_tahun_bulan' ORDER BY tanggal DESC");


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'REKAP KETIDAKHADIRAN');
$sheet->setCellValue('A2', 'Bulan');
$sheet->setCellValue('A3', 'Tahun');
$sheet->setCellValue('C2', $_POST['filter_bulan']);
$sheet->setCellValue('C3', $_POST['filter_tahun']);
$sheet->setCellValue('A5', 'NO');
$sheet->setCellValue('B5', 'NAMA');
$sheet->setCellValue('C5', 'KELAS');
$sheet->setCellValue('D5', 'TANGGAL');
$sheet->setCellValue('E5', 'KETERANGAN');
$sheet->setCellValue('F5', 'DESKRIPSI');

$sheet-> mergeCells('A1:F1');
$sheet-> mergeCells('A2:B2');
$sheet-> mergeCells('A3:B3');


$no = 1;
$row = 6;

while($data = mysqli_fetch_array($result)){
    $sheet->setCellValue('A'. $row, $no);
    $sheet->setCellValue('B'. $row, $data['nama']);
    $sheet->setCellValue('C'. $row, $data['kelas']);
    $sheet->setCellValue('D'. $row, $data['tanggal']);
    $sheet->setCellValue('E'. $row, $data['keterangan']);
    $sheet->setCellValue('F'. $row, $data['deskripsi']);

    $no++;
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Ketidakhadiran.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');



?>