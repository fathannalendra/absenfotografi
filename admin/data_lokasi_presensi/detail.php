<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = "Detail Lokasi Presensi";
include ('../layout/header.php');
require_once ('../../config.php');

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM lokasi_presensi WHERE id =$id")

    ?>

<?php while ($lokasi = mysqli_fetch_array($result)): ?>

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <td>Nama Lokasi</td>
                                    <td>: <?php echo $lokasi['nama_lokasi'] ?></td>
                                </tr>
                                <tr>
                                    <td>Alamat Lokasi</td>
                                    <td>: <?php echo $lokasi['alamat_lokasi'] ?></td>
                                </tr>
                                <tr>
                                    <td>Latitude</td>
                                    <td>: <?php echo $lokasi['latitude'] ?></td>
                                </tr>
                                <tr>
                                    <td>Longitude</td>
                                    <td>: <?php echo $lokasi['longitude'] ?></td>
                                </tr>
                                <tr>
                                    <td>Radius</td>
                                    <td>: <?php echo $lokasi['radius'] ?></td>
                                </tr>
                                <tr>
                                    <td>Jam Masuk</td>
                                    <td>: <?php echo $lokasi['jam_masuk'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7932.480006618618!2d<?php echo $lokasi['longitude']?>!3d<?php echo $lokasi['latitude']?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f126f0725a55%3A0x2766738f64260ac5!2sState%20Senior%20High%20School%2032%20of%20Jakarta!5e0!3m2!1sen!2sid!4v1718693097657!5m2!1sen!2sid"
                                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endwhile ?>

<?php include ('../layout/footer.php'); ?>