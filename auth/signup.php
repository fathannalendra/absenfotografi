<?php
session_start();

require_once ('../config.php');


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
  $foto_default = htmlspecialchars($_POST['foto_default']);

  $password = $_POST['password'];
  $ulangi_password = $_POST['ulangi_password'];


  if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['foto'];
    $nama_file = $file['name'];
    $file_tmp = $file['tmp_name'];
    $ukuran_file = $file['size'];
    $file_direktori = "../../assets/img/foto_anggota/" . $nama_file;

    $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
    $ekstensi_diizinkan = ["jpg", "jpeg", "png", "HEIC"];
    $max_ukuran_file = 10 * 1024 * 1024;

    if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
      $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file jpg, jpeg, png, heic yang diperbolehkan";
    }

    if ($ukuran_file > $max_ukuran_file) {
      $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10 MB";
    }

    if (empty($pesan_kesalahan)) {
      move_uploaded_file($file_tmp, $file_direktori);
      $foto = $nama_file;
    } else {
      $foto = basename($foto_default);  // use default photo if errors
    }
  } else {
    // Use default photo if no file is uploaded
    $foto = basename($foto_default);
  }
  // if (isset($_FILES['foto'])) {
  //     $file = $_FILES['foto'];
  //     $nama_file = $file['name'];
  //     $file_tmp = $file['tmp_name'];
  //     $ukuran_file = $file['size'];
  //     $file_direktori = "../../assets/img/foto_anggota/" . $nama_file;

  //     $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
  //     $ekstensi_diizinkan = ["jpg", "jpeg", "png", "HEIC"];
  //     $max_ukuran_file = 10 * 1024 * 1024;

  //     move_uploaded_file($file_tmp, $file_direktori);
  // }

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

    // if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
    //     $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file jpg, jpeg, png, heic yang diperbolehkan";
    // }

    // if ($ukuran_file > $max_ukuran_file) {
    //     $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ukuran file melebihi 10 MB";
    // }

    // Cek username
    $username_check_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($username_check_query) > 0) {
      $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Username sudah ada, silakan pilih username lain";
    }

    if (!empty($pesan_kesalahan)) {
      $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
    } else {
      $anggota = mysqli_query($conn, "INSERT INTO anggota (nisn, nama,kelas, jenis_kelamin, alamat, no_handphone, lokasi_presensi, foto)
          VALUES('$nisn', '$nama','$kelas','$jenis_kelamin', '$alamat', '$no_handphone', '$lokasi_presensi', '$foto')");

      $id_anggota = mysqli_insert_id($conn);
      $user = mysqli_query($conn, "INSERT INTO users(id_anggota, username, password, status, role)
          VALUES('$id_anggota', '$username', '$password_hashed','$status','$role')");

      $_SESSION['berhasil'] = 'Data berhasil disimpan';
      header("Location: login.php");
      exit();
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
  <title>Sign up</title>
  <!-- CSS files -->
  <link href="<?php echo base_url('assets/css/tabler.min.css?1692870487') ?>" rel="stylesheet" />
  <link href="<?php echo base_url('assets/css/tabler-vendors.min.css?1692870487') ?>" rel="stylesheet" />
  <link href="<?php echo base_url('assets/css/demo.min.css?1692870487') ?>" rel="stylesheet" />
  
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
      background: url('../assets/img/coverlogin.jpg');
      background-size: cover;
      font-feature-settings: "cv03", "cv04", "cv11";
    }
  </style>
</head>

<body class="d-flex flex-column">
  <div class="page page-center">
    <div class="container container-tight py-4">

      <form class="card card-md" action="<?php echo base_url('auth/signup.php') ?>" method="post" autocomplete="off"
        enctype="multipart/form-data" novalidate>
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Register</h2>
          <input type="hidden" value="Aktif" name="status">
          <input type="hidden" value="Anggota" name="role">
          <input type="hidden" value="SMAN 32 JAKARTA" name="lokasi_presensi">
          <input type="hidden" class="form-control" name="foto_default"
            value="<?php echo base_url('assets/img/foto_anggota/avatar.jpg') ?>">
          <div class="mb-3">
            <label for="">NISN</label>
            <input type="text" class="form-control" name="nisn" value="<?php if (isset($_POST['nisn']))
              echo $_POST['nisn'] ?>">
            </div>
            <div class="mb-3">
              <label for="">Nama</label>
              <input type="text" class="form-control" name="nama" value="<?php if (isset($_POST['nama']))
              echo $_POST['nama'] ?>">
            </div>
            <div class="mb-3">
              <label for="">Kelas</label>
              <input type="text" class="form-control" name="kelas" value="<?php if (isset($_POST['kelas']))
              echo $_POST['kelas'] ?>">
            </div>
            <div class="mb-3">
              <label for="">Jenis Kelamin</label>
              <select name="jenis_kelamin" class="form-control">
                <option value="">--Pilih Jenis Kelamin--</option>
                <option <?php if (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Laki-laki') {
              echo 'selected';
            } ?> value="Laki-laki">Laki-laki</option>
              <option <?php if (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Perempuan') {
                echo 'selected';
              } ?> value="Perempuan">Perempuan</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="">Alamat</label>
            <input type="text" class="form-control" name="alamat" value="<?php if (isset($_POST['alamat']))
              echo $_POST['alamat'] ?>">
            </div>
            <div class="mb-3">
              <label for="">No Handphone</label>
              <input type="text" class="form-control" name="no_handphone" value="<?php if (isset($_POST['no_handphone']))
              echo $_POST['no_handphone'] ?>">
            </div>
            <div class="mb-3">
              <label for="">Username</label>
              <input type="text" class="form-control" name="username" value="<?php if (isset($_POST['username']))
              echo $_POST['username'] ?>">
            </div>
            <div class="mb-3">
              <label for="password">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" required>
                <button type="button" class="btn btn-outline-secondary"
                  onclick="togglePassword('password', 'toggle_password')">
                  <i class="fa fa-eye" id="toggle_password"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <label for="ulangi_password">Ulangi Password</label>
              <div class="input-group">
                <input type="password" class="form-control" name="ulangi_password" id="ulangi_password" required>
                <button type="button" class="btn btn-outline-secondary"
                  onclick="togglePassword('ulangi_password', 'toggle_ulangi_password')">
                  <i class="fa fa-eye" id="toggle_ulangi_password"></i>
                </button>
              </div>
            </div>
            <div class="form-footer">
              <button type="submit" name="submit" class="btn btn-primary w-100">Create new account</button>
            </div>
            <div class="text-center text-muted mt-3">
              Already have account? <a href="login.php">Login</a>
            </div>
          </div>
        </form>

      </div>
    </div>
  </body>

  <!-- Libs JS -->
  <script src="<?php echo base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1692870487') ?>" defer></script>
<script src="<?php echo base_url('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487') ?>" defer></script>
<script src="<?php echo base_url('assets/libs/jsvectormap/dist/maps/world.js?1692870487') ?>" defer></script>
<script src="<?php echo base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1692870487') ?>" defer></script>
<!-- Tabler Core -->
<script src="<?php echo base_url('assets/js/tabler.min.js?1692870487') ?>" defer></script>
<script src="<?php echo base_url('assets/js/demo.min.js?1692870487') ?>" defer></script>

<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- sweet alert validasi -->
<?php if (isset($_SESSION['validasi'])): ?>
  <script>
    const Toast = Swal.mixin({
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
    Toast.fire({
      icon: "error",
      title: "<?php echo $_SESSION['validasi'] ?>"
    });
  </script>
  <?php unset($_SESSION['validasi']); ?>
<?php endif; ?>



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

</html>