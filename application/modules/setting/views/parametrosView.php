<div class="modal-dialog">
    <div class="modal-content">
        <?php echo form_open($accion, 'role="form"') ?>
        <?php if ($accion != "setting/addModulo"): ?>
            <?php echo form_hidden('id', $parametro->id) ?>
        <?php endif; ?>
        <div class="modal-header info">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3><?php echo $titulo ?></h3>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <?php echo form_label('Nombre:'); ?>
                <?php echo form_input('nombre', $parametro->nombre, 'class="form-control input-sm" placeholder="Nombre del Modulo"') ?>
            </div>
            <div class="form-group">
                <?php echo form_label('Valor:'); ?>
                <?php echo form_input('valor', $parametro->valor, 'class="form-control input-sm" placeholder="si se require definir clase especifica"') ?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban fa-fw"></span>&nbsp;Cancelar
            </button>
            <button type="reset" class="btn btn-warning"><span class="fa fa-refresh fa-fw"></span>&nbsp;Limpiar
                Formulario
            </button>
            <button type="submit" class="btn btn-success"><span class="fa fa-plus-circle fa-fw"></span>&nbsp;Guardar
            </button>
        </div>
        <?php echo form_close() ?>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    <script>
        $(document).ready(function () {
            $("input:radio:checked").each(function () {
                $(this).parent().addClass('active');
            });
        })
    </script>