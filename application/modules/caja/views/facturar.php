<?php
/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 02/04/2016
 * Time: 08:57 PM
 */
?>
<link href="/assets/css/bootstrap-dialog.css" stylesheet" type="text/css" />
<script src="/assets/js/bootstrap-dialog.js"></script>

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
                            <?php echo anchor ( 'caja/imprimir/controlador/' . $p->id, '<i class="fa fa-print"></i> Facturar', 'class="btn btn-xs btn-success btn-cf"' ) ?>
                            <?php echo anchor ( 'caja/imprimir/pdf/' . $p->id, '<i class="fa fa-file-pdf-o"></i>', 'class="btn btn-xs btn-info btn-pdf"' ) ?>
                            <?php echo anchor ( 'caja/anular/' . $p->id, '<i class="fa fa-ban"></i>', 'class="btn btn-xs btn-danger"' ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".btn-cf").click(function (e) {
            e.preventDefault();
            url = $(this).attr('href');
            BootstrapDialog.show({
                message: $('<div></div>').load(url)
            });
        });
        $(".btn-pdf").click(function (e) {
            e.preventDefault();
            url = $(this).attr('href');
            tipo = BootstrapDialog.TYPE_WARNING;
            BootstrapDialog.show({
                type: tipo,
                title: "Impresion",
                message: function (dialog) {
                    var $message = $('<div></div>');
                    var pageToLoad = dialog.getData('pageToLoad');
                    $message.load(pageToLoad);

                    return $message;
                },
                data: {
                    'pageToLoad': url
                }
            });
        });
    });
</script>