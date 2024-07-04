<?php
session_start();
require_once ('../config.php');

if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $password_baru = $_POST['password_baru'];
    $ulangi_password_baru = $_POST['ulangi_password_baru'];

    $pesan_kesalahan = [];

    // Validasi input
    if (empty($username)) {
        $pesan_kesalahan[] = "Username wajib diisi";
    }
    if (empty($password_baru)) {
        $pesan_kesalahan[] = "Password baru wajib diisi";
    }
    if (empty($ulangi_password_baru)) {
        $pesan_kesalahan[] = "Ulangi password baru wajib diisi";
    }
    if ($password_baru !== $ulangi_password_baru) {
        $pesan_kesalahan[] = "Password tidak cocok";
    }

    if (!empty($pesan_kesalahan)) {
        $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    } else {
        // Cek keberadaan username di database
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

        if (mysqli_num_rows($result) > 0) {
            // Username ditemukan, lanjutkan dengan update password
            $password_baru_hashed = password_hash($password_baru, PASSWORD_DEFAULT);

            // Update password di database
            $update = mysqli_query($conn, "UPDATE users SET password = '$password_baru_hashed' WHERE username = '$username'");

            if ($update) {
                $_SESSION['berhasil'] = 'Password berhasil diubah';
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['gagal'] = 'Gagal mengubah password. Silakan coba lagi.';
            }
        } else {
            // Username tidak ditemukan
            $_SESSION['gagal'] = 'Username tidak ditemukan';
        }
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Forgot password</title>
    <link href="<?php echo base_url('assets/css/tabler.min.css?1692870487') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/tabler-vendors.min.css?1692870487') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/demo.min.css?1692870487') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class="d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
        <div class="container container-tight py-4">
            <form class="card card-md" action="" method="POST" autocomplete="off" novalidate>
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Forgot password</h2>
                    <?php
                    if (isset($_SESSION['validasi'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['validasi'] . '</div>';
                        unset($_SESSION['validasi']);
                    }
                    if (isset($_SESSION['gagal'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['gagal'] . '</div>';
                        unset($_SESSION['gagal']);
                    }
                    ?>
                    <div class="mb-3">
                        <label for="username">Username</label>
                        <input type="text" value="<?php if (isset($_POST['username']))
                            echo $_POST['username'] ?>"
                                name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_baru">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_baru" class="form-control" id="password_baru"
                                    required>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePassword('password_baru', 'toggle_password_baru')">
                                    <i class="fa fa-eye" id="toggle_password_baru"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ulangi_password_baru">Ulangi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="ulangi_password_baru" class="form-control"
                                    id="ulangi_password_baru" required>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePassword('ulangi_password_baru', 'toggle_ulangi_password_baru')">
                                    <i class="fa fa-eye" id="toggle_ulangi_password_baru"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                    </div>
                </form>
                <div class="text-center text-secondary mt-3">
                    <a href="login.php">Login</a>
                </div>
            </div>
        </div>
        <script src="./dist/js/tabler.min.js?1692870487" defer></script>
        <script src="./dist/js/demo.min.js?1692870487" defer></script>
    </body>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- toggle password -->
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




    <!-- sweet alert berhasil -->
<?php if (isset($_SESSION['berhasil'])): ?>
    <script>
        const Berhasil = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Berhasil.fire({
            icon: "success",
            title: "<?php echo $_SESSION['berhasil'] ?>"
        });
    </script>
    <?php unset($_SESSION['berhasil']); ?>
<?php endif; ?>

</html>