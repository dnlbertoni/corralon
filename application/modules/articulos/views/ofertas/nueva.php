<div class="post">
    <?php echo form_open() ?>
    <?php echo form_input('descripcion'); ?>
    <?php echo form_input('inicio'); ?>
    <?php echo form_input('final'); ?>
    <?php echo form_close() ?>
    <div id="guardar">Guardar</div>
</div>

<script>
    $(document).ready(function () {
        $("#guardar").button({icons: {primary: 'ui-icon-disk'}});
    });
</script>