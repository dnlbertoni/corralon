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
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Subrubros">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i> Buscar</button>
                            <?php echo anchor ( 'stock/subrubros/nuevo', '<i class="fa fa-plus-circle"></i> Nuevo', 'class="btn btn-info"' ) ?>
                        </span>
                    </div><!-- /input-group -->
                </div>
                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Rubro</th>
                            <th>Descripcion</th>
                            <th>CantxBultos</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody class="table-content">
                        <?php foreach ( $subrubros as $rubro ): ?>
                            <tr>
                                <td><?= $rubro->ID_SUBRUBRO; ?></td>
                                <td><?= $rubro->ID_RUBRO; ?></td>
                                <td><?= $rubro->DESCRIPCION_SUBRUBRO; ?></td>
                                <td><?= $rubro->CANTXBULTO_SUBRUBRO; ?></td>
                                <td><span
                                        class="label label-<?= ( $rubro->ESTADO_SUBRUBRO == 1 ) ? "success" : "danger" ?>"><?= ( $rubro->ESTADO_SUBRUBRO == 1 ) ? "Activo" : "Suspendido"; ?></span>
                                </td>
                                <td>
                                    <?php echo anchor ( 'stock/subrubros/edit/' . $rubro->ID_SUBRUBRO, '<i class="fa fa-pencil-square-o"></i>', ' class="btn btn-info btn-xs"' ) ?>
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

<script>
    $(document).ready(function () {
        $("#btn-nuevoRubro").click(function () {

        });
    });
</script>