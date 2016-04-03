<div class="modal-dialog">
    <div class="modal-content">
        <?php echo form_open($accion, 'role="form"') ?>
        <?php if ($accion != "setting/addModulo"): ?>
            <?php echo form_hidden('id', $menu->id) ?>
        <?php endif; ?>
        <div class="modal-header info">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3><?php echo $titulo ?></h3>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <?php echo form_label('Nombre:'); ?>
                <?php echo form_input('nombre', $menu->nombre, 'class="form-control input-sm" placeholder="Nombre del Menu"') ?>
            </div>
            <div class="form-group">
                <?php echo form_label('Clase:'); ?>
                <?php echo form_input('clase', $menu->clase, 'class="form-control input-sm" placeholder="si se require definir clase especifica"') ?>
            </div>
            <div class="form-group">
                <?php echo form_label('Link:'); ?>
                <?php echo form_input('link', $menu->link, 'class="form-control input-sm" placeholder="controller/method"') ?>
            </div>
            <div class="form-group">
                <?php echo form_label('Target:'); ?>
                <?php echo form_input('target', $menu->target, 'class="form-control input-sm" placeholder="_self"') ?>
            </div>
            <div class="form-group">
                <?php echo form_label ( 'Orden:' ); ?>
                <?php echo form_input ( 'orden', $menu->orden, 'class="form-control input-sm" placeholder="Orden en el menu"' ) ?>
            </div>
            <div class="form-group">
                <?php echo form_label('Modulo:'); ?>
                <?php echo form_dropdown('id_modulo', $modulosSel, $menu->id_modulo, "class='form-control'"); ?>
            </div>
            <div class="form-group">
                <label for="estado" class="control-label input-group">Estado</label>

                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default">
                        <?php echo form_radio('estado', ACTIVO, ($menu->estado == ACTIVO)); ?>Activo
                    </label>
                    <label class="btn btn-default">
                        <?php echo form_radio('estado', SUSPENDIDO, ($menu->estado == SUSPENDIDO)); ?>Suspendido
                    </label>
                </div>
            </div
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
    </div>
    <!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
    $(document).ready(function () {
        $("input:radio:checked").each(function () {
            $(this).parent().addClass('active');
        });
    })
</script>