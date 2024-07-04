<footer class="footer footer-transparent d-print-none">
    <div class="container-xl">
        <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item">
                
                        <a href="." class="link-secondary">PPTZ 32</a>.
                             
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>


<!-- Libs JS -->
<script src="<?php echo base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1692870487') ?>" defer></script>
<script src="<?php echo base_url('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487') ?>" defer></script>
<script src="<?php echo base_url('assets/libs/jsvectormap/dist/maps/world.js?1692870487') ?>" defer></script>
<script src="<?php echo base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1692870487') ?>" defer></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- Tabler Core -->
<script src="<?php echo base_url('assets/js/tabler.min.js?1692870487') ?>" defer></script>
<script src="<?php echo base_url('assets/js/demo.min.js?1692870487') ?>" defer></script>
   <!-- Sweet Alert -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_SESSION['gagal'])){
    ?>
<script>
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "<?php echo $_SESSION['gagal']?>",
    });
</script>

<?php unset($_SESSION['gagal']); ?>
<?php } ?>


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

</body>

</html>