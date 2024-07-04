<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Anggota') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = "";
include ('../layout/header.php');
require_once ('../../config.php');

$id = $_SESSION['id'];
$result = mysqli_query($conn, "SELECT users.id_anggota, users.username, users.status, users.role,
anggota.* FROM users JOIN anggota ON users.id_anggota = anggota.id WHERE anggota.id = $id");

?>

<?php while ($anggota = mysqli_fetch_array($result)): ?>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <center>
                            <img style="border-radius: 100%;width:50%" src="<?php echo base_url('assets/img/foto_anggota/'.$anggota['foto'])?>" alt="">
                       
                            </center>
                           </div>
                        <table class="table mt-3">
                            <tr>
                                <td>Nama</td>
                                <td>: <?php echo $anggota['nama']?></td>
                            </tr>
                            <tr>
                                <td>NISN</td>
                                <td>: <?php echo $anggota['nisn']?></td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>: <?php echo $anggota['kelas']?></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>: <?php echo $anggota['jenis_kelamin']?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: <?php echo $anggota['alamat']?></td>
                            </tr>
                            <tr>
                                <td>No. Handphone</td>
                                <td>: <?php echo $anggota['no_handphone']?></td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td>: <?php echo $anggota['username']?></td>
                            </tr>
                            <tr>
                                <td>Role</td>
                                <td>: <?php echo $anggota['role']?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: <?php echo $anggota['status']?></td>
                            </tr>
                            <tr>
                                <td><a href="<?php echo base_url('anggota/fitur_lainnya/ubah_profile.php?id=' . $anggota['id']) ?>"
                                    class="badge badge-pill bg-primary">Ubah Profile</a>
                                </td>
                            </tr>
                        </table>
                        
                    </div>
                </div>

                <div class="col-md-4"></div>
            </div>
        </div>
    </div>

<?php endwhile ?>

<?php include ('../layout/footer.php'); ?>