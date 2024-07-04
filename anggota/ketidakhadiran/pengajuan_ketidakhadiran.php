<?php
ob_start();
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Anggota') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}


$judul = 'Pengajuan Ketidakhadiran';
include ('../layout/header.php');
include_once ('../../config.php');

if(isset($_POST['submit'])){
    $id = $_POST['id_anggota'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pesan_kesalahan = [];

        if (empty($keterangan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Keterangan wajib diisi";
        }
        if (empty($tanggal)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Tanggal wajib diisi";
        }
        if (empty($deskripsi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Deskripsi wajib diisi";
        }
      
   
        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $result = mysqli_query($conn, "INSERT INTO ketidakhadiran (id_anggota,nama, keterangan, deskripsi, tanggal)
            VALUES('$id','$nama', '$keterangan','$deskripsi', '$tanggal')");


            $_SESSION['berhasil'] = 'Data berhasil disimpan';
            header("Location: ketidakhadiran.php");
            exit();
        }
    }
}

$id = $_SESSION['id'];
$result = mysqli_query($conn, "SELECT * FROM ketidakhadiran WHERE id_anggota = '$id' ORDER BY id DESC");


?>

<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" value="<?php echo $_SESSION['id'] ?>" name="id_anggota">
                    <input type="hidden" value="<?php echo $_SESSION['nama'] ?>" name="nama">
                    <div class="mb-3">
                        <label for="">Keterangan</label>
                        <select name="keterangan" class="form-control">
                            <option value="">--Pilih Keterangan--</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Sakit') {
                                echo 'selected';
                            } ?> value="Sakit">Sakit</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Izin') {
                                echo 'selected';
                            } ?> value="Izin">Izin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" cols="30" rows="10" id=""></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Ajukan</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include ('../layout/footer.php'); ?>