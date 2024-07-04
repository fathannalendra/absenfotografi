<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
    exit();
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
    exit();
}

$judul = "Ubah Profile";
include ('../layout/header.php');
require_once ('../../config.php');

if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $nisn = htmlspecialchars($_POST['nisn']);
    $nama = htmlspecialchars($_POST['nama']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $username = htmlspecialchars($_POST['username']);



    $password = $_POST['password'];
    $ulangi_password = $_POST['ulangi_password'];

    if (empty($password)) {
        $password_hash = $_POST['password_lama'];
    } else {
        // Hash password only if it is set
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    if ($_FILES['foto_baru']['error'] === 4) {
        $nama_file = $_POST['foto_lama'];
    } else {
        if (isset($_FILES['foto_baru'])) {
            $file = $_FILES['foto_baru'];
            $nama_file = $file['name'];
            $file_tmp = $file['tmp_name'];
            $ukuran_file = $file['size'];
            $file_direktori = "../../assets/img/foto_anggota/" . $nama_file;

            $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
            $ekstensi_diizinkan = ["jpg", "jpeg", "png", "HEIC"];
            $max_ukuran_file = 10 * 1024 * 1024;

            move_uploaded_file($file_tmp, $file_direktori);
        }
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pesan_kesalahan = [];

        if (empty($nisn)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> NISN wajib diisi";
        }
        if (empty($nama)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Nama wajib diisi";
        }
        if (empty($kelas)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Kelas wajib diisi";
        }
        if (empty($jenis_kelamin)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Jenis Kelamin wajib diisi";
        }
        if (empty($alamat)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Alamat wajib diisi";
        }
        if (empty($no_handphone)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> No Handphone wajib diisi";
        }
        if (empty($username)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Username wajib diisi";
        }
        if ($password !== $ulangi_password) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password tidak cocok";
        }



        if ($_FILES['foto_baru']['error'] !== 4) {
            if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
                $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file jpg, jpeg, png, heic yang diperbolehkan";
            }

            if ($ukuran_file > $max_ukuran_file) {
                $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10 MB";
            }

        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $anggota = mysqli_query($conn, "UPDATE anggota SET 
                nisn = '$nisn',
                nama = '$nama',
                kelas = '$kelas',
                jenis_kelamin = '$jenis_kelamin',
                alamat = '$alamat',
                no_handphone = '$no_handphone',
                foto = '$nama_file'

                WHERE id = '$id'");



            $user = mysqli_query($conn, "UPDATE users SET
                username = '$username'
                WHERE id = '$id'");



            $_SESSION['berhasil'] = 'Data berhasil disimpan';
            header("Location: profile.php");
            exit();
        }
    }
}


$id = $_SESSION['id'];
$result = mysqli_query($conn, "SELECT users.id_anggota, users.username, users.password, users.status, users.role,
anggota.* FROM users JOIN anggota ON users.id_anggota = anggota.id WHERE anggota.id = $id");

while ($anggota = mysqli_fetch_array($result)) {
    $nisn = $anggota['nisn'];
    $nama = $anggota['nama'];
    $kelas = $anggota['kelas'];
    $jenis_kelamin = $anggota['jenis_kelamin'];
    $alamat = $anggota['alamat'];
    $no_handphone = $anggota['no_handphone'];
    $username = $anggota['username'];
    $foto = $anggota['foto'];
}

?>

<div class="page-body">
    <div class="container-xl">
        <form action="<?php echo base_url('admin/fitur_lainnya/ubah_profile.php') ?>" method="POST"
            enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="card ">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="">NISN</label>
                                <input type="text" class="form-control" name="nisn" value="<?php echo $nisn ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="nama" value="<?php echo $nama ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Kelas</label>
                                <input type="text" class="form-control" name="kelas" value="<?php echo $kelas ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">--Pilih Jenis Kelamin--</option>
                                    <option <?php if ($jenis_kelamin == 'Laki-laki') {
                                        echo 'selected';
                                    } ?>
                                        value="Laki-laki">Laki-laki</option>
                                    <option <?php if ($jenis_kelamin == 'Perempuan') {
                                        echo 'selected';
                                    } ?>
                                        value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Alamat</label>
                                <input type="text" class="form-control" name="alamat" value="<?php echo $alamat ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">No Handphone</label>
                                <input type="text" class="form-control" name="no_handphone"
                                    value="<?php echo $no_handphone ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Username</label>
                                <input type="text" class="form-control" name="username" value="<?php echo $username ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Foto</label>
                                <input type="hidden" value="<?php echo $foto ?>" name="foto_lama">
                                <input type="file" class="form-control" name="foto_baru">
                            </div>

                            <input type="hidden" value="<?php echo $id ?>" name="id">

                            <button class="btn btn-primary" name="edit" type="submit">Update</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>

            </div>
        </form>
    </div>
</div>

<?php include ('../layout/footer.php'); ?>