<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = "Edit Lokasi Presensi";
include ('../layout/header.php');
require_once ('../../config.php');

if(isset($_POST['update'])){
    $id= $_POST['id'];
    $nama_lokasi = htmlspecialchars($_POST['nama_lokasi']);
    $alamat_lokasi = htmlspecialchars($_POST['alamat_lokasi']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $radius = htmlspecialchars($_POST['radius']);
    $jam_masuk = htmlspecialchars($_POST['jam_masuk']);

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty($nama_lokasi)){
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Nama lokasi wajib diisi";
        }
        if(empty($alamat_lokasi)){
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Alamat lokasi wajib diisi";
        }
        if(empty($latitude)){
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Latitude wajib diisi";
        }
        if(empty($longitude)){
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Longtiude wajib diisi";
        }
        if(empty($radius)){
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Radius wajib diisi";
        }
        if(empty($jam_masuk)){
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>Jam masuk wajib diisi";
        }

        if(!empty($pesan_kesalahan)){
            $_SESSION['validasi'] = implode("<br>",$pesan_kesalahan);
        }else{
            $result = mysqli_query($conn, "UPDATE lokasi_presensi SET 
                nama_lokasi='$nama_lokasi',
                alamat_lokasi='$alamat_lokasi',
                latitude='$latitude',
                longitude='$longitude',
                radius='$radius',
                jam_masuk='$jam_masuk'            
            WHERE id=$id ");
            $_SESSION['berhasil'] = 'Data berhasil diupdate';
            header("Location: lokasi_presensi.php");
            exit;
        }
    }

}

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = mysqli_query($conn, "SELECT * FROM lokasi_presensi WHERE id=$id");

while($lokasi = mysqli_fetch_array($result)){
    $nama_lokasi = $lokasi['nama_lokasi'];
    $alamat_lokasi = $lokasi['alamat_lokasi'];
    $latitude = $lokasi['latitude'];
    $longitude= $lokasi['longitude'];
    $radius = $lokasi['radius'];
    $jam_masuk = $lokasi['jam_masuk'];
}
?>

<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?php echo base_url('admin/data_lokasi_presensi/edit.php')?>" method="POST">
                    <div class="mb-3">
                        <label for="">Nama Lokasi</label>
                        <input type="text" class="form-control" name="nama_lokasi" value="<?php echo $nama_lokasi ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Alamat Lokasi</label>
                        <input type="text" class="form-control" name="alamat_lokasi" value="<?php echo $alamat_lokasi ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="<?php echo $latitude ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="<?php echo $longitude ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Radius</label>
                        <input type="text" class="form-control" name="radius" value="<?php echo $radius ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Jam Masuk</label>
                        <input type="text" class="form-control" name="jam_masuk" value="<?php echo $jam_masuk ?>">
                    </div>

                    <input type="hidden" value="<?php echo $id ?>" name="id">
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include ('../layout/footer.php'); ?>

