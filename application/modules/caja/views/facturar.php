<?php
/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 02/04/2016
 * Time: 08:57 PM
 */
?>

<div class="section">
    <div class="row">
        <h2>Prespuestos Pendientes para Facturar</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-content table-striped">
                <thead class="table-icons">
                <tr>
                    <th>Fecha</th>
                    <th>Presupuesto</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Importe</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ( $presupuestos as $p ): ?>
                    <tr>
                        <td><?= $p->fecha ?></td>
                        <td><?= $p->comprobante ?></td>
                        <td><?= $p->cliente ?></td>
                        <td><?= $p->vendedor ?></td>
                        <td><?= $p->importe ?></td>
                        <td>
                            <?php echo anchor ( 'caja/imprimir/controlador/' . $p->id, '<i class="fa fa-print"></i> Facturar', 'class="btn btn-xs btn-success"' ) ?>
                            <?php echo anchor ( 'caja/imprimir/pdf/' . $p->id, '<i class="fa fa-file-pdf-o"></i>', 'class="btn btn-xs btn-info"' ) ?>
                            <?php echo anchor ( 'caja/anular/' . $p->id, '<i class="fa fa-ban"></i>', 'class="btn btn-xs btn-danger"' ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


