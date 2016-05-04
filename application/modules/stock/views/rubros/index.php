<?php
/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 20/04/2016
 * Time: 11:17 PM
 */
?>
<div class="section">
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-md-8">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4 class="panel-title ">Rubros</h4>
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
                        <?php foreach ( $rubros as $rubro ): ?>
                            <tr>
                                <td><?= $rubro->ID_RUBRO; ?></td>
                                <td><?= $rubro->DESCRIPCION_RUBRO; ?></td>
                                <td><?= $rubro->UNIDAD_RUBRO; ?></td>
                                <td><span
                                        class="label label-<?= ( $rubro->ESTADO_RUBRO == 1 ) ? "success" : "danger" ?>"><?= ( $rubro->ESTADO_RUBRO == 1 ) ? "Activo" : "Suspendido"; ?></span>
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
