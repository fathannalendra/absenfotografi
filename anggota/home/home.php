<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Anggota') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}


$judul = 'Home';
include ('../layout/header.php');
include_once('../../config.php');

$lokasi_presensi = $_SESSION['lokasi_presensi'];
$result = mysqli_query($conn, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");

while($lokasi = mysqli_fetch_array($result)){
    $latitude_kantor = $lokasi['latitude'];
    $longitude_kantor = $lokasi['longitude'];
    $radius = $lokasi['radius'];
}

date_default_timezone_set('Asia/Jakarta');

?>

<style>
    .parent_date{
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 20px;
        text-align: center;
        justify-content: center;
    }

    .parent_clock{
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 30px;
        text-align: center;
        font-weight: bold;
        justify-content: center;
    }
</style>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
                <div class="card text-center">
                    <div class="card-header">Presensi Masuk</div>
                    <div class="card-body">

                        <?php 
                        $id_anggota = $_SESSION['id'];
                        $tanggal_hari_ini = date('Y-m-d');

                        $cek_presensi_masuk = mysqli_query($conn, "SELECT * FROM presensi WHERE id_anggota
                        = '$id_anggota' AND tanggal_masuk = '$tanggal_hari_ini'") ;
                      
                        ?>

                        <?php if(mysqli_num_rows($cek_presensi_masuk) === 0) {?>
                        <div class="parent_date">
                            <div id="tanggal_masuk"></div>
                            <div class="ms-2"></div>
                            <div id="bulan_masuk"></div>
                            <div class="ms-2"></div>
                            <div id="tahun_masuk"></div>
                        </div>

                        <div class="parent_clock">
                            <div id="jam_masuk"></div>
                            <div>:</div>
                            <div id="menit_masuk"></div>
                            <div>:</div>
                            <div id="detik_masuk"></div>
                        </div>
                        
                    <form action="<?php echo base_url('anggota/presensi/presensi_masuk.php')?>" method="POST">
                    <input type="hidden" name="latitude_anggota" id="latitude_anggota">    
                    <input type="hidden" name="longitude_anggota" id="longitude_anggota">    
                    <input type="hidden" value="<?php echo $latitude_kantor ?>" name="latitude_kantor">    
                    <input type="hidden" value="<?php echo $longitude_kantor ?>" name="longitude_kantor">    
                    <input type="hidden" value="<?php echo $radius ?>" name="radius">  
                    <input type="hidden" value="<?php echo date('Y-m-d') ?>" name="tanggal_masuk">  
                    <input type="hidden" value="<?php echo date('H:i:s') ?>" name="jam_masuk">  
                    
                    <button class="btn btn-primary mt-3" type="submit" name="tombol_masuk">Masuk</button>
                    </form>
                    <?php } else {?>
                        <i class="fa-regular fa-circle-check fa-4x text-success"></i>
                        <h4 class="my-3">Anda telah melakukan <br>presensi masuk</h4>
                    <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2"></div>
        </div>
    </div>
</div>

<script>
    // set waktu di card presensi masuk

    window.setTimeout("waktuMasuk()", 1000);
    namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    function waktuMasuk() {
        const waktu = new Date();
        setTimeout("waktuMasuk()", 1000);

        document.getElementById("tanggal_masuk").innerHTML = waktu.getDate();
        document.getElementById("bulan_masuk").innerHTML = namaBulan[waktu.getMonth()];
        document.getElementById("tahun_masuk").innerHTML = waktu.getFullYear();
        document.getElementById("jam_masuk").innerHTML = waktu.getHours();
        document.getElementById("menit_masuk").innerHTML = waktu.getMinutes();
        document.getElementById("detik_masuk").innerHTML = waktu.getSeconds();
    }

    getLocation();
    function getLocation(){
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(showPosition);
        }else{
            alert("Browser Anda tidak mendukung")
        }
    }

    function showPosition(position){
        $('#latitude_anggota').val(position.coords.latitude);
        $('#longitude_anggota').val(position.coords.longitude);
    }
</script>


<?php include ('../layout/footer.php'); ?>