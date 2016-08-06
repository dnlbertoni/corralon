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
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2>Prespuestos Pendientes para Facturar al dia <?= $fecha->format ( "d/m/Y" ); ?></h2>
            </div>
            <div class="panel-body">
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
                                    <?php echo anchor ( 'caja/anular/' . $p->id, '<i class="fa fa-ban"></i>', 'class="btn btn-xs btn-danger btn-anular"' ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                cantidad
            </div>
        </div>
        <div class="row">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h2>Prespuestos Facturados del dia <?= $fecha->format ( "d/m/Y" ); ?></h2>
                </div>
                <div class="panel-body">
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
                            <?php foreach ( $facturados as $p ): ?>
                                <tr>
                                    <td><?= $p->fecha ?></td>
                                    <td><?= $p->comprobante ?></td>
                                    <td><?= $p->cliente ?></td>
                                    <td><?= $p->vendedor ?></td>
                                    <td><?= $p->importe ?></td>
                                    <td>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    cantidad
                </div>
            </div>
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
        $(".btn-anular").click(function (e) {
            url = $(this).attr('href');
            e.preventDefault();
            BootstrapDialog.confirm({
                message: "Dese anular el Presupuesto",
                type: BootstrapDialog.TYPE_WARNINGR,
                size: BootstrapDialog.SIZE_WIDE,
                closable: true, // <-- Default value is false
                draggable: true, // <-- Default value is false
                btnCancelLabel: 'No Anular', // <-- Default value is 'Cancel',
                btnOKLabel: 'Continuar con la Anulacion', // <-- Default value is 'OK',
                btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will
                btnCancelClass: 'btn-default', // <-- If you didn't specify it, dialog type will
                callback: function (respuesta) {
                    if (respuesta) {
                        $.post(url, {}, function () {
                            location.reload();
                        });
                    } else {
                        dialogItself.close();
                    }
                }
            });
        });
    });
</script>