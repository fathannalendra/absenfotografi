<?php

session_start();
require_once ('../../config.php');

$id = $_GET['id'];

$result = mysqli_query($conn, "DELETE FROM anggota WHERE id=$id");


$_SESSION['berhasil'] = 'Data berhasil dihapus';
header("Location: anggota.php");
exit;

?>