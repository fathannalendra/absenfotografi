<?php
ob_start();
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Anggota') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = "Ubah Password";
include ('../layout/header.php');
require_once ('../../config.php');

$id = $_SESSION['id'];
if (isset($_POST['update'])) {
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
    $ulangi_password_baru = password_hash($_POST['ulangi_password_baru'], PASSWORD_DEFAULT);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pesan_kesalahan = [];

        if (empty($_POST['password_baru'])) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password baru wajib diisi";
        }
        if (empty($_POST['ulangi_password_baru'])) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ulangi password baru wajib diisi";
        }
        if ($_POST['password_baru'] !== $_POST['ulangi_password_baru']) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password tidak cocok";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $anggota = mysqli_query($conn, "UPDATE users SET 
                password = '$password_baru'
                WHERE id_anggota = $id");


            $_SESSION['berhasil'] = 'Password berhasil diubah';
            header("Location: ../home/home.php");
            exit();
        }
    }
}
?>

<div class="page-body">
    <div class="container-xl">
        <form action="" method="POST">
            <div class="card col-md-6">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="password_baru">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_baru" class="form-control" id="password_baru">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_baru', 'toggle_password_baru')">
                                <i class="fa fa-eye" id="toggle_password_baru"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ulangi_password_baru">Ulangi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="ulangi_password_baru" class="form-control" id="ulangi_password_baru">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('ulangi_password_baru', 'toggle_ulangi_password_baru')">
                                <i class="fa fa-eye" id="toggle_ulangi_password_baru"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_SESSION['id'] ?>">
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
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