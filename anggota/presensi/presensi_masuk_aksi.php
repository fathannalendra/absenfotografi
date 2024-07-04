<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Anggota') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

date_default_timezone_set('Asia/Jakarta');
include_once ('../../config.php');

$file_foto = $_POST['photo'];
$id_anggota = $_POST['id'];
$tanggal_masuk = $_POST['tanggal_masuk'];
$jam_masuk = $_POST['jam_masuk'];

$foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
$foto = str_replace(' ', '+', $foto);
$data = base64_decode($foto);

$folder_uploads = '../../uploads/';
if (!file_exists($folder_uploads)) {
    mkdir($folder_uploads, 0777, true);
}

$nama_file = 'masuk_' . date('Y-m-d_H-i-s') . '.png';
$file_path = $folder_uploads . $nama_file;
file_put_contents($file_path, $data);

$result = mysqli_query($conn, "INSERT INTO presensi(id_anggota, tanggal_masuk, jam_masuk, foto_masuk) 
VALUES ('$id_anggota', '$tanggal_masuk', '$jam_masuk', '$nama_file')");

if($result) {
    $_SESSION['berhasil']= "Presensi masuk berhasil";
} else {
    $_SESSION['gagal'] = "Presensi masuk gagal";
}
?>
