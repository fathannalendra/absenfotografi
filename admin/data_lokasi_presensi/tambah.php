<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = "Tambah Lokasi Presensi";
include ('../layout/header.php');
require_once ('../../config.php');

if (isset($_POST['submit'])) {
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
            $result = mysqli_query($conn, "INSERT INTO lokasi_presensi(nama_lokasi, alamat_lokasi, latitude, longitude, radius, jam_masuk)
            VALUES('$nama_lokasi', '$alamat_lokasi', '$latitude', '$longitude', '$radius', '$jam_masuk')");
        
            $_SESSION['berhasil'] = 'Data berhasil disimpan';
            header("Location: lokasi_presensi.php");
            exit;
        }
    }

   
}

?>

<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?php echo base_url('admin/data_lokasi_presensi/tambah.php') ?>" method="POST">
                    <div class="mb-3">
                        <label for="">Nama Lokasi</label>
                        <input type="text" class="form-control" name="nama_lokasi" value="<?php if(isset($_POST['nama_lokasi']))
                        echo $_POST['nama_lokasi'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Alamat Lokasi</label>
                        <input type="text" class="form-control" name="alamat_lokasi" value="<?php if(isset($_POST['alamat_lokasi']))
                        echo $_POST['alamat_lokasi'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Latitude</label>
                        <input type="text" class="form-control" name="latitude" value="<?php if(isset($_POST['latitude']))
                        echo $_POST['latitude'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Longitude</label>
                        <input type="text" class="form-control" name="longitude" value="<?php if(isset($_POST['longitude']))
                        echo $_POST['longitude'] ?>" >
                    </div>
                    <div class="mb-3">
                        <label for="">Radius</label>
                        <input type="number" class="form-control" name="radius" value="<?php if(isset($_POST['radius']))
                        echo $_POST['radius'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="">Jam Masuk</label>
                        <input type="time" class="form-control" name="jam_masuk" value="<?php if(isset($_POST['jam_masuk']))
                        echo $_POST['jam_masuk'] ?>">
                    </div>

                    <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ('../layout/footer.php'); ?>