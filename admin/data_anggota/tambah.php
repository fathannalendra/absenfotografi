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

$judul = "Tambah Anggota";
include ('../layout/header.php');
require_once ('../../config.php');

if (isset($_POST['submit'])) {
    $nisn = htmlspecialchars($_POST['nisn']);
    $nama = htmlspecialchars($_POST['nama']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $username = htmlspecialchars($_POST['username']);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $lokasi_presensi = htmlspecialchars($_POST['lokasi_presensi']);

    $password = $_POST['password'];
    $ulangi_password = $_POST['ulangi_password'];

    if (isset($_FILES['foto'])) {
        $file = $_FILES['foto'];
        $nama_file = $file['name'];
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direktori = "../../assets/img/foto_anggota/" . $nama_file;

        $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $ekstensi_diizinkan = ["jpg", "jpeg", "png", "HEIC"];
        $max_ukuran_file = 10 * 1024 * 1024;

        move_uploaded_file($file_tmp, $file_direktori);
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
        if (empty($password)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password wajib diisi";
        } else {
            // Hash password only if it is set
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        }
        if (empty($ulangi_password)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ulangi Password wajib diisi";
        }
        if ($password !== $ulangi_password) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password tidak cocok";
        }
        if (empty($role)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Role wajib diisi";
        }
        if (empty($status)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Status wajib diisi";
        }
        if (empty($lokasi_presensi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Lokasi Presensi wajib diisi";
        }

        if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file jpg, jpeg, png, heic yang diperbolehkan";
        }

        if ($ukuran_file > $max_ukuran_file) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10 MB";
        }

        // Cek username
        $username_check_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($username_check_query) > 0) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Username sudah ada, silakan pilih username lain";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $anggota = mysqli_query($conn, "INSERT INTO anggota (nisn, nama,kelas, jenis_kelamin, alamat, no_handphone, lokasi_presensi, foto)
            VALUES('$nisn', '$nama','$kelas','$jenis_kelamin', '$alamat', '$no_handphone', '$lokasi_presensi', '$nama_file')");

            $id_anggota = mysqli_insert_id($conn);
            $user = mysqli_query($conn, "INSERT INTO users(id_anggota, username, password, status, role)
            VALUES('$id_anggota', '$username', '$password_hashed','$status','$role')");

            $_SESSION['berhasil'] = 'Data berhasil disimpan';
            header("Location: anggota.php");
            exit();
        }
    }
}
?>

<div class="page-body">
    <div class="container-xl">
        <form action="<?php echo base_url('admin/data_anggota/tambah.php') ?>" method="POST"
            enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="card ">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="">NISN</label>
                                <input type="text" class="form-control" name="nisn"
                                    value="<?php if (isset($_POST['nisn']))
                                        echo $_POST['nisn'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control" name="nama"
                                        value="<?php if (isset($_POST['nama']))
                                        echo $_POST['nama'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="">Kelas</label>
                                    <input type="text" class="form-control" name="kelas"
                                        value="<?php if (isset($_POST['kelas']))
                                        echo $_POST['kelas'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="">--Pilih Jenis Kelamin--</option>
                                        <option <?php if (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Laki-laki') {
                                        echo 'selected';
                                    } ?> value="Laki-laki">
                                        Laki-laki</option>
                                    <option <?php if (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Perempuan') {
                                        echo 'selected';
                                    } ?> value="Perempuan">
                                        Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Alamat</label>
                                <input type="text" class="form-control" name="alamat"
                                    value="<?php if (isset($_POST['alamat']))
                                        echo $_POST['alamat'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="">No Handphone</label>
                                    <input type="text" class="form-control" name="no_handphone"
                                        value="<?php if (isset($_POST['no_handphone']))
                                        echo $_POST['no_handphone'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">--Pilih Status--</option>
                                        <option <?php if (isset($_POST['status']) && $_POST['status'] == 'Aktif') {
                                        echo 'selected';
                                    } ?> value="Aktif">Aktif</option>
                                    <option <?php if (isset($_POST['status']) && $_POST['status'] == 'Tidak Aktif') {
                                        echo 'selected';
                                    } ?> value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card ">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="">Username</label>
                                <input type="text" class="form-control" name="username"
                                    value="<?php if (isset($_POST['username']))
                                        echo $_POST['username'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" id="password">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('password', 'toggle_password')">
                                            <i class="fa fa-eye" id="toggle_password"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="ulangi_password">Ulangi Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="ulangi_password"
                                            id="ulangi_password">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="togglePassword('ulangi_password', 'toggle_ulangi_password')">
                                            <i class="fa fa-eye" id="toggle_ulangi_password"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="">Role</label>
                                    <select name="role" class="form-control">
                                        <option value="">--Pilih Role--</option>
                                        <option <?php if (isset($_POST['role']) && $_POST['role'] == 'Admin') {
                                        echo 'selected';
                                    } ?> value="Admin">Admin</option>
                                    <option <?php if (isset($_POST['role']) && $_POST['role'] == 'Anggota') {
                                        echo 'selected';
                                    } ?> value="Anggota">Anggota</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Lokasi Presensi</label>
                                <select name="lokasi_presensi" class="form-control">
                                    <option value="">--Pilih Lokasi Presensi--</option>
                                    <?php
                                    $ambil_lok_presensi = mysqli_query($conn, "SELECT * FROM lokasi_presensi ORDER BY nama_lokasi ASC");
                                    while ($lokasi = mysqli_fetch_assoc($ambil_lok_presensi)) {
                                        $nama_lokasi = $lokasi['nama_lokasi'];
                                        if (isset($_POST['lokasi_presensi']) && $_POST['lokasi_presensi'] == $nama_lokasi) {
                                            echo '<option value="' . $nama_lokasi . '" selected="selected">' . $nama_lokasi . '</option>';
                                        } else {
                                            echo '<option value="' . $nama_lokasi . '">' . $nama_lokasi . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Foto</label>
                                <input type="file" class="form-control" name="foto">
                            </div>
                            <button class="btn btn-primary" name="submit" type="submit">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function togglePassword(fieldId, iconId) {
        var passwordField = document.getElementById(fieldId);
        var toggleIcon = document.getElementById(iconId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }
</script>
<?php include ('../layout/footer.php'); ?>