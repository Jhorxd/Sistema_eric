<footer class="main-footer">
<strong>Sistema Inventario</strong>
</footer>

</div>

<script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>

</body>
</html>
<script>
$(document).ready(function() {
    // Sidebar Toggle Manual
    $('.sidebar-toggle').on('click', function() {
        $('body').toggleClass('sidebar-open');
    });
});
</script>
