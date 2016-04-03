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
                        <td><?= $p->importe ?></td>
                        <td>acciones</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


