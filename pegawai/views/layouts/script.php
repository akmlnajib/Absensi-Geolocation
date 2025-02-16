<?php if (isset($_SESSION["berhasil"])): ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "Anda berhasil login",
            text: "<?= htmlspecialchars($_SESSION["berhasil"]) ?>"
        });
    </script>
    <?php unset($_SESSION["berhasil"]); ?>
<?php endif; ?>

<?php if (isset($_SESSION["gagal"])): ?>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "<?= htmlspecialchars($_SESSION["gagal"]) ?>"
        });
    </script>
    <?php unset($_SESSION["gagal"]); ?>
<?php endif; ?>

<?php if (isset($_SESSION["success"])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
                icon: "success",
                title: "<?= htmlspecialchars($_SESSION["success"], ENT_QUOTES, 'UTF-8') ?>"
            });
        });
    </script>
    <?php unset($_SESSION["success"]); ?>
<?php endif; ?>

<?php if (isset($_SESSION["error"])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
                title: "<?= htmlspecialchars($_SESSION["error"], ENT_QUOTES, 'UTF-8') ?>"
            });
        });
    </script>
    <?php unset($_SESSION["error"]); ?>
<?php endif; ?>

<script>
    $('.btn-delete').on('click', function () {
        var getLink = $(this).attr('href');
        Swal.fire({
        title: "Apakah anda yakin menghapus data ini ?",
        text: "Data yang dihapus tidak dapat dikembalikan",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
        if (result.isConfirmed) {
            window.location.href =getLink
        }
        })
        return false;
    });
</script>
