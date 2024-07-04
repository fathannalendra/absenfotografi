<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Anggota') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}


$judul = 'Rekap Presensi';
include ('../layout/header.php');
include_once ('../../config.php');

$id = $_SESSION['id'];

if (empty($_GET['filter_bulan'])) {
    $bulan_sekarang = date('Y-m');
    $result = mysqli_query($conn, "SELECT presensi.*, anggota.nama, anggota.kelas FROM presensi JOIN anggota ON presensi.id_anggota = anggota.id WHERE DATE_FORMAT(tanggal_masuk, '%Y-%m') = '$bulan_sekarang'  AND presensi.id_anggota = '$id'  ORDER BY tanggal_masuk DESC");
} else {
    $filter_tahun_bulan = $_GET['filter_tahun'] . '-' . $_GET['filter_bulan'];
    $result = mysqli_query($conn, "SELECT presensi.*, anggota.nama, anggota.kelas FROM presensi JOIN anggota ON presensi.id_anggota = anggota.id WHERE DATE_FORMAT(tanggal_masuk, '%Y-%m') = '$filter_tahun_bulan' AND presensi.id_anggota = '$id' ORDER BY tanggal_masuk DESC");
}

if (empty($_GET['filter_bulan'])) {
    $bulan = date('Y-m');
} else {
    $bulan = $_GET['filter_tahun'] . '-' . $_GET['filter_bulan'];
}


?>
<div class="page-body">
    <div class="container-xl">

        <div class="row">
            <div class="col-md-2">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Export Excel
                </button>
            </div>

            <div class="col-md-10">
                <form method="GET">
                    <div class="input-group">
                        <select name="filter_bulan" class="form-control">
                            <option value="">--Pilih Bulan--</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>

                        <select name="filter_tahun" class="form-control">
                            <option value="">--Pilih Tahun--</option>
                            <?php
                            $tahunSekarang = date("Y");
                            for ($i = 0; $i <= 5; $i++) {
                                $tahun = $tahunSekarang + $i;
                                echo "<option value=\"$tahun\">$tahun</option>";
                            } ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <span>Rekap Presensi Bulan: <?php echo date('F Y', strtotime($bulan)) ?> </span>
        <div class="table-container mt-2">
            <table class="responsive-table">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                </tr>

                <?php if (mysqli_num_rows($result) === 0) { ?>
                    <tr>
                        <td colspan="5">Data rekap presensi masih kosong</td>
                    </tr>
                <?php } else { ?>
                    <?php $no = 1;
                    while ($rekap = mysqli_fetch_array($result)): ?>


                        <tr>
                            <td class="text-center"><?php echo $no++ ?></td>
                            <td class="text-center"><?php echo $rekap['nama'] ?></td>
                            <td class="text-center"><?php echo $rekap['kelas'] ?></td>
                            <td class="text-center"><?php echo date('d F Y', strtotime($rekap['tanggal_masuk'])) ?></td>
                            <td class="text-center"><?php echo $rekap['jam_masuk'] ?></td>
                        </tr>

                    <?php endwhile; ?>
                <?php } ?>
            </table>
        </div>
    </div>
</div>


<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel Rekap Presensi Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo base_url('anggota/presensi/rekap_excel.php') ?>">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="">Bulan</label>
                        <select name="filter_bulan" class="form-control">
                            <option value="">--Pilih Bulan--</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Tahun</label>
                        <select name="filter_tahun" class="form-control">
                            <option value="">--Pilih Tahun--</option>
                            <?php
                            $tahunSekarang = date("Y");
                            for ($i = 0; $i <= 5; $i++) {
                                $tahun = $tahunSekarang + $i;
                                echo "<option value=\"$tahun\">$tahun</option>";
                            } ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../layout/footer.php' ?>