<?php

session_start();
require_once ('../../config.php');

$id = $_GET['id'];

$result = mysqli_query($conn, "DELETE FROM lokasi_presensi WHERE id=$id");


$_SESSION['berhasil'] = 'Data berhasil dihapus';
header("Location: lokasi_presensi.php");
exit;

?>