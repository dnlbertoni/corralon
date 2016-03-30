<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3>Ultimo Inventario</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <?php echo anchor('stock/inventario/open', '<span class="fa fa-play-circle-o"></span> Iniciar Inventario', 'class="list-group-item list-group-item-success"'); ?>
                            <?php if (isset($last->estado)): ?>
                                <?php if ($last->estado == 1): ?>
                                    <?php echo anchor('stock/inventario/conteo/dpc1/' . $last->id, '<span class="fa fa-barcode"></span> Inventario Depostio 1', 'class="list-group-item list-group-item-info"'); ?>
                                    <?php echo anchor('stock/inventario/conteo/dpc2/' . $last->id, '<span class="fa fa-barcode"></span> Inventario Depostio 2', 'class="list-group-item list-group-item-info"'); ?>
                                    <?php echo anchor('stock/inventario/conteo/dpc3/' . $last->id, '<span class="fa fa-barcode"></span> Inventario Depostio 3', 'class="list-group-item list-group-item-info"'); ?>
                                    <?php echo anchor('stock/inventario/conteo/salon/' . $last->id, '<span class="fa fa-barcode"></span> Inventario Salon Ventas', 'class="list-group-item list-group-item-info"'); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php echo anchor('stock/inventario/close', '<span class="fa fa-stop"></span> Cerrar Inventario', 'class="list-group-item list-group-item-danger"'); ?>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <p><?php echo $estadoUltimo ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3>Inventarios</h3>
                    </div>
                    <div class="panel-body">
                        <p>lista de inventarios</p>
                    </div>
                    <div class="panel-footer">
                        <p>cuento la cantidad de inventarios hechos</p>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.section -->