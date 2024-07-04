<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'Admin') {
    header("location: ../../auth/login.php?pesan=akses_ditolak");
}

$judul = "Data Lokasi Presensi";
include ('../layout/header.php');
require_once ('../../config.php');
$result = mysqli_query($conn, "SELECT * FROM lokasi_presensi ORDER BY id DESC")

    ?>


<div class="page-body">
    <div class="container-xl">
        <a href="<?php echo base_url('admin/data_lokasi_presensi/tambah.php') ?>" class="btn btn-primary">
            <span class="text">
                <i class="fa-solid fa-circle-plus"></i>
                Tambah Data
            </span>
        </a>
        <div class="table-container mt-2">
            <table class="responsive-table">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Lokasi</th>
                    <th>Alamat Lokasi</th>
                    <th>Latitude/Longitude</th>
                    <th>Radius</th>
                    <th>Aksi</th>
                </tr>

                <?php if (mysqli_num_rows($result) === 0) { ?>
                    <tr>
                        <td colspan="6">Data kosong, silahkan tambahkan data baru</td>
                    </tr>

                <?php } else { ?>
                    <?php $no = 1;
                    while ($lokasi = mysqli_fetch_array($result)):
                        ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $lokasi['nama_lokasi'] ?></td>
                            <td><?php echo $lokasi['alamat_lokasi'] ?></td>
                            <td><?php echo $lokasi['latitude'] . '/' . $lokasi['longitude'] ?></td>
                            <td><?php echo $lokasi['radius'] ?></td>
                            <td class="text-center">
                                <a href="<?php echo base_url('admin/data_lokasi_presensi/detail.php?id=' . $lokasi['id']) ?>"
                                    class="badge badge-pill bg-primary mt-2"> Detail</a>
                                <a href="<?php echo base_url('admin/data_lokasi_presensi/edit.php?id=' . $lokasi['id']) ?>"
                                    class="badge badge-pill bg-primary mt-2">Edit</a>
                                <a href="<?php echo base_url('admin/data_lokasi_presensi/hapus.php?id=' . $lokasi['id']) ?>"
                                    class="badge badge-pill bg-danger mt-2 tombol-hapus"
                                    data-id="<?php echo $anggota['id'] ?>">Hapus</a>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.tombol-hapus').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                var anggotaId = this.getAttribute('data-id');

                Swal.fire({
                    title: "Apakah Anda yakin ingin menghapus?",
                    text: "Data yang telah dihapus tidak dapat dikembalikan",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Data telah dihapus",
                            icon: "success"
                        }).then(() => {
                            window.location.href = "<?php echo base_url('admin/data_lokasi_presensi/hapus.php?id='); ?>" + anggotaId;
                        });
                    }
                });
            });
        });
    });
</script>

<?php include ('../layout/footer.php'); ?>