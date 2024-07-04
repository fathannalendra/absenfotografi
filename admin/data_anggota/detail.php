<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = "Detail Anggota";
include ('../layout/header.php');
require_once ('../../config.php');


$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT users.id_anggota, users.username, users.password, users.status, users.role,
anggota.* FROM users JOIN anggota ON users.id_anggota = anggota.id WHERE anggota.id=$id ");

?>

<?php while($anggota = mysqli_fetch_array($result)): ?>

<div class="page-body">
    <div class="container-xl">
        <div class="row">

        <div class="col-md-6 mb-2">
                <center> <img src="<?php echo base_url('assets/img/foto_anggota/'. $anggota['foto'])?>" alt="" style="width:250px; border-radius:10px">
                </center>
                 </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        
                    <table class="table">
                        <tr>
                            <td>NISN</td>
                            <td>: <?php echo $anggota['nisn'] ?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>: <?php echo $anggota['nama'] ?></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>: <?php echo $anggota['kelas'] ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>: <?php echo $anggota['jenis_kelamin'] ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: <?php echo $anggota['alamat'] ?></td>
                        </tr>
                        <tr>
                            <td>No Handhone</td>
                            <td>: <?php echo $anggota['no_handphone'] ?></td>
                        </tr>
                        <tr>
                            <td>Lokasi Presensi</td>
                            <td>: <?php echo $anggota['lokasi_presensi'] ?></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>: <?php echo $anggota['username'] ?></td>
                        </tr>
                        <tr>
                            <td>Role</td>
                            <td>: <?php echo $anggota['role'] ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>: <?php echo $anggota['status'] ?></td>
                        </tr>
                    </table>
                    </div>
                </div>
            </div>

            

        </div>
    </div>
</div>

<?php endwhile ?>

<?php include ('../layout/footer.php'); ?>