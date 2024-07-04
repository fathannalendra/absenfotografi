<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = "Data Ketidakhadiran";
include ('../layout/header.php');
require_once ('../../config.php');


if (empty($_GET['filter_bulan'])) {
    $bulan_sekarang = date('Y-m');
    $result = mysqli_query($conn, "SELECT ketidakhadiran.*, anggota.kelas FROM ketidakhadiran JOIN anggota ON ketidakhadiran.id_anggota = anggota.id WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan_sekarang' ORDER BY tanggal DESC ");
} else {
    $filter_tahun_bulan = $_GET['filter_tahun'] . '-' . $_GET['filter_bulan'];
    $result = mysqli_query($conn, "SELECT ketidakhadiran.*, anggota.kelas FROM ketidakhadiran JOIN anggota ON ketidakhadiran.id_anggota = anggota.id WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$filter_tahun_bulan' ORDER BY tanggal DESC");
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

        <span>Rekap Presensi Bulan: <?php echo date('F Y', strtotime($bulan)) ?> </span></table>
        <div class="table-container mt-2">
            <table class="responsive-table">
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Deskripsi</th>
                </tr>

                <?php if (mysqli_num_rows($result) === 0) { ?>
                    <tr>
                        <td colspan="6">Data ketidakhadiran masih kosong</td>
                    </tr>
                <?php } else { ?>
                    <?php $no = 1;
                    while ($data = mysqli_fetch_array($result)): ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $data['nama'] ?></td>
                            <td><?php echo $data['kelas'] ?></td>
                            <td><?php echo date('d F Y', strtotime($data['tanggal'])) ?></td>
                            <td><?php echo $data['keterangan'] ?></td>
                            <td><?php echo $data['deskripsi'] ?></td>
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
                <h5 class="modal-title">Export Excel Rekap Ketidakhadiran Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo base_url('admin/data_ketidakhadiran/ketidakhadiran_excel.php') ?>">
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


<?php include ('../layout/footer.php'); ?>