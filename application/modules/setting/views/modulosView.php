<div class="modal-dialog">
    <div class="modal-content">
        <?php echo form_open($accion, 'role="form"') ?>
        <?php if ($accion != "setting/addModulo"): ?>
            <?php echo form_hidden('id', $modulo->id) ?>
        <?php endif; ?>
        <div class="modal-header info">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3><?php echo $titulo ?></h3>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <?php echo form_label('Nombre:'); ?>
                <?php echo form_input('nombre', $modulo->nombre, 'class="form-control input-sm" placeholder="Nombre del Modulo"') ?>
            </div>
            <div class="form-group">
                <?php echo form_label('Clase:'); ?>
                <?php echo form_input('clase', $modulo->clase, 'class="form-control input-sm" placeholder="si se require definir clase especifica"') ?>
            </div>
            <div class="form-group">
                <label for="modo_texto" class="control-label input-group">Modo Texto</label>

                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default">
                        <?php echo form_radio('modo_texto', 1, ($modulo->modo_texto == 1)); ?>Incluye el texto
                    </label>
                    <label class="btn btn-default">
                        <?php echo form_radio('modo_texto', 0, ($modulo->modo_texto == 0)); ?>Solo Icono
                    </label>
                </div>
                <div class="form-group">
                    <label for="estado" class="control-label input-group">Estado</label>

                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default">
                            <?php echo form_radio('estado', ACTIVO, ($modulo->estado == ACTIVO)); ?>Activo
                        </label>
                        <label class="btn btn-default">
                            <?php echo form_radio('estado', SUSPENDIDO, ($modulo->estado == SUSPENDIDO)); ?>Suspendido
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
    </div>
    <!-- /.modal-dialog -->
    <script>
        $(document).ready(function () {
            $("input:radio:checked").each(function () {
                $(this).parent().addClass('active');
            });
        })
    </script>