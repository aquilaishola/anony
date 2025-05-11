<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if (!empty($success)) : ?>
        Swal.fire({
            icon: "success",
            title: "Success!",
            text: "<?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>",
            confirmButtonColor: "#3085d6",
            timer: 4000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = "<?php echo htmlspecialchars($url); ?>";
        });
    <?php endif; ?>
    
    <?php if (!empty($error)) : ?>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "<?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>",
            confirmButtonColor: "#3085d6",
        });
    <?php endif; ?>
});
</script>