<?php
/**
 * Vista del index para el controlador de carteles
 */
?>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 ">
                <h3><i class="fa fa-money "></i>&nbsp;Carteles Precios</h3>
                <div class="list-group">
                    <?php foreach ($Menu[0] as $menu): ?>
                        <?php echo anchor($menu['link'], $menu['nombre'], 'class="list-group-item"') ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <h3><i class="fa fa-tasks"></i>&nbsp;Carteles de Ofertas</h3>
                <div class="list-group">
                    <?php foreach ($Menu[1] as $menu): ?>
                        <?php echo anchor($menu['link'], $menu['nombre'], 'class="list-group-item"') ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <h3><i class="fa fa-usd"></i>&nbsp;Listas de Precios </h3>
                <div class="list-group">
                    <?php foreach ($Menu[2] as $menu): ?>
                        <?php echo anchor($menu['link'], $menu['nombre'], 'class="list-group-item"') ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.section -->
