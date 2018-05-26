<?php
/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 29/04/2016
 * Time: 10:31 PM
 */
?>

<div class="section">
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title ">Articulos</h4>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i> Buscar</button>
                            <?php echo anchor ( 'stock/articulos/nuevo', '<i class="fa fa-plus-circle"></i> Nuevo', 'class="btn btn-info"' ) ?>
                        </span>
                    </div><!-- /input-group -->
                </div>
                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Rubro</th>
                            <th>Subrubro</th>
                            <th>Descripcion</th>
                            <th>Precio</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody class="table-content">
                        <?php foreach ( $articulos as $articulo ): ?>
                            <tr>
                                <td><?= $articulo->ID_ARTICULO; ?></td>
                                <td><?= $articulo->DESCRIPCION_RUBRO; ?></td>
                                <td><?= $articulo->DESCRIPCION_SUBRUBRO; ?></td>
                                <td><?= $articulo->DESCRIPCION_ARTICULO; ?></td>
                                <td><?= $articulo->PRECIO_ARTICULO; ?></td>
                                <td>
                                    <span class="label label-<?= ( $articulo->ESTADO_ARTICULO == 1 ) ? "success" : "danger" ?>">
                                        <?= ( $articulo->ESTADO_ARTICULO == 1 ) ? "Activo" : "Suspendido"; ?></span>
                                    <?php echo anchor ( 'stock/articulos/editar/'.$articulo->ID_ARTICULO, '<i class="fa fa-pencil-square-o"></i>', 'class="btn btn-xs btn-info"' ) ?>
                                    <?php echo anchor ( 'stock/articulos/borrar/'.$articulo->ID_ARTICULO, '<i class="fa fa-trash-o"></i>', 'class="btn btn-danger btn-xs" aria-describedby="Borrar"' ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <?php echo $paginacion ?>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Operaciones Masivas</div>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <?php echo anchor ( 'stock/articulos/importar', 'Lista de Precios', 'class="list-group-item list-group-item-success"' ); ?>
                    </div>
                </div>
                <div class="panel-footer"></div>
            </div>
        </div>
    </div>
</div>
