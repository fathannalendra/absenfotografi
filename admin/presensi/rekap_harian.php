<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = 'Rekap Presensi Harian';
include ('../layout/header.php');
include_once ('../../config.php');

if (empty($_GET['tanggal_dari'])) {
    $tanggal_hari_ini = date('Y-m-d');
    $result = mysqli_query($conn, "SELECT presensi.*, anggota.nama, anggota.kelas FROM presensi JOIN anggota ON presensi.id_anggota = anggota.id WHERE tanggal_masuk = '$tanggal_hari_ini' ORDER BY tanggal_masuk DESC");
} else {
    $tanggal_dari = $_GET['tanggal_dari'];
    $tanggal_sampai = $_GET['tanggal_sampai'];
    $result = mysqli_query($conn, "SELECT presensi.*, anggota.nama, anggota.kelas FROM presensi JOIN anggota ON presensi.id_anggota = anggota.id WHERE tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai' ORDER BY tanggal_masuk DESC");
}

if (empty($_GET['tanggal_dari'])) {
    $tanggal = date('Y-m-d');
} else {
    $tanggal = $_GET['tanggal_dari'] . '-' . $_GET['tanggal_sampai'];
}
?>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-2">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Export Excel
                </button>
            </div>
            <div class="col-md-10">
                <form method="GET">
                    <div class="input-group">
                        <input type="date" class="form-control" name="tanggal_dari">
                        <input type="date" class="form-control" name="tanggal_sampai">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($_GET['tanggal_dari'])): ?>
            <span>Rekap Presensi Tanggal: <?php echo date('d F Y') ?></span>
        <?php else: ?>
            <span>Rekap Presensi Tanggal: <?php echo date('d F Y', strtotime($_GET['tanggal_dari'])) . ' - ' . date('d F Y', strtotime($_GET['tanggal_sampai'])) ?></span>
        <?php endif ?>

        <div class="table-container mt-2">
            <table class="responsive-table">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Foto Masuk</th>
                </tr>

                <?php if (mysqli_num_rows($result) === 0) { ?>
                    <tr>
                        <td colspan="6">Data rekap presensi masih kosong</td>
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
                            <td class="text-center">
                                <img src="<?php echo base_url('uploads/' . $rekap['foto_masuk']) ?>" alt="" style="width:100px; border-radius:10px">
                            </td>
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
                <h5 class="modal-title">Export Excel Rekap Presensi Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="<?php echo base_url('admin/presensi/rekap_harian_excel.php') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tanggal_dari">
                    </div>
                    <div class="mb-3">
                        <label for="">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_sampai">
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
