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
                            <button class="btn btn-default" type="button">Buscar</button>
                        </span>
                    </div><!-- /input-group -->
                    <button class="btn btn-info btn-xs"><i class="fa fa-plus-circle"></i>Nuevo</button>
                </div>
                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripcion</th>
                            <th>Unidad</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody class="table-content">
                        <?php foreach ( $articulos as $articulo ): ?>
                            <tr>
                                <td><?= $articulo->ID_ARTICULO; ?></td>
                                <td><?= $articulo->DESCRIPCION_ARTICULO; ?></td>
                                <td></td>
                                <td><span
                                        class="label label-<?= ( $articulo->ESTADO_ARTICULO == 1 ) ? "success" : "danger" ?>"><?= ( $articulo->ESTADO_ARTICULO == 1 ) ? "Activo" : "Suspendido"; ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"></i></button>
                                    <button class="btn btn-warning btn-xs"><i class="fa fa-ban"></i></button>
                                    <button class="btn btn-danger btn-xs" aria-describedby="Borrar"><i
                                            class="fa fa-trash-o"></i></button>
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
    </div>
</div>
