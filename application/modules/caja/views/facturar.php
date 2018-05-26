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
                <h2>Prespuestos Pendientes para Facturar al dia  <?= $fecha;?></h2>
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
                                    <?php echo anchor ( 'caja/imprimir/pdf/' . $p->id, '<i class="fa fa-file-pdf-o"></i>', 'class="btn btn-xs btn-info btn-pdf" target="_blank"' ) ?>
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
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h2>Prespuestos Facturados del dia <?= $fecha;?></h2>
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
    <div class="col-md-3">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h2>Resumen</h2>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-content table-striped">
                        <thead>
                            <tr>
                                <th>Facturacion</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tr>
                            <th>Diaria</th>
                            <td class="text-right"><?php echo $diaria?>&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Semanal</th>
                            <td class="text-right"><?php echo $semanal?>&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Mensual</th>
                            <td class="text-right"><?php echo $mensual?></td>
                        </tr>
                        <tr>
                            <th> Fiscal</th>
                            <td class="text-right"><?php echo $afip?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                cantidad
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="nominal" tabindex="-1" role="dialog" aria-labelledby="nominal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="nominalLabel">
                    Nombre de la Factura
                </h4>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <?php echo form_open('caja/nominal', 'id=form-nominal')?>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nombre" >Nombre a facturar</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre a facturar"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="domicilio" >Domicilio</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="domicilio" id="domicilio" placeholder="Direccion"/>
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="documento" >Documento</label>
                        <div class="col-sm-9">
                            <input type="text" name="documento" class="form-control" id="documento" placeholder="Numero Documento"/>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="" name="idencab"  id="idencab"/>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="btn-nominal-yes"><i class="fa fa-save"></i> Guardar </button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Cerrar </button>
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
        /*
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
        });*/
        $(".btn-pdf").click(function () {
            location.reload();
        });
        $(".btn-anular").click(function(e){
            url=$(this).attr('href');
            e.preventDefault();
            BootstrapDialog.confirm({
                message:"Dese anular el Presupuesto",
                type: BootstrapDialog.TYPE_WARNINGR,
                size: BootstrapDialog.SIZE_WIDE,
                closable: true, // <-- Default value is false
                draggable: true, // <-- Default value is false
                btnCancelLabel: 'No Anular', // <-- Default value is 'Cancel',
                btnOKLabel: 'Continuar con la Anulacion', // <-- Default value is 'OK',
                btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will
                btnCancelClass: 'btn-default', // <-- If you didn't specify it, dialog type will
                callback: function(respuesta){
                    if(respuesta){
                        $.post(url, {},function(){
                            location.reload();
                        });
                    }else{
                        dialogItself.close();
                    }
                }
            });
        });
        $(".btn-nominal").click(function (e) {
            e.preventDefault();
            url = this.href;
            url_aux= url.split("_");
            idEncab = url_aux[1];
            $("#idencab").val(idEncab);
            url = url_aux[0];
            $("#nominal").modal("show");
        })
        $("#btn-nominal-yes").click(function () {
            $("#form-nominal").submit();
        })
    });
</script>