<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-push-3">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3>Cierre de Inventario</h3>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open('stock/inventario/closeDo'); ?>
                        <?php if ($last->estado == 1): ?>
                            <div class="alert alert-success text-center">
                                <table class="table">
                                    <?php foreach ($last as $key => $value): ?>
                                        <tr>
                                            <td><?php echo $key ?></td>
                                            <td><?php echo $value ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                                <br/>
                                <button type="submit" class="btn btn-success">Cerrar</button>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-danger text-center">
                                No se puede Cerrar un Inventario YA cerrado.
                            </div>
                        <?php endif; ?>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="panel-footer text-center">
                        <button type="button" class="btn btn-info" id="btn-Back">Volver</button>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.section -->
