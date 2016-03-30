<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-push-3">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3>Inicio de Inventario</h3>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open('stock/inventario/openDo'); ?>
                        <?php if ($permitido): ?>
                            <div class="alert alert-success text-center">
                                <p>Estan todas la condicones para iniciar un inventario</p>
                                <br/>
                                <button type="submit" class="btn btn-success">Iniciar</button>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-danger text-center">
                                Ya existe un Inventario inicializado, solo se puede tener un inventario abierto.
                            </div>
                        <?php endif; ?>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="panel-footer text-center">
                        <button type="button" class="btn btn-info" id="BACK">Volver</button>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.section -->
