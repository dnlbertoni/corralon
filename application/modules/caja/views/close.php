<?php
/**
 * @author DnL
 */
?>
<div class="section">
    <div class="row">
        <div class="col-md-3 clearfix"></div>
        <?php if ( !$caja ): ?>
            <?php echo form_open ( 'caja/openDo', 'id="form-open-caja"' ) ?>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Apertura de Caja</div>
                    <div class="panel-body">
                        <div class="table-content">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Usuario:</th>
                                    <td><?php echo form_input ( 'usuario', 'cajero1' ) ?></td>
                                </tr>
                                <tr>
                                    <th>Fecha:</th>
                                    <td><?php echo form_input ( 'fecha', $fecha ) ?></td>
                                </tr>
                                <tr>
                                    <th>Efectivo Inicial:</th>
                                    <td><?php echo form_input ( 'efectivo', 0 ) ?></td>
                                </tr>
                                <tr>
                                    <th>Cheques Incial:</th>
                                    <td><?php echo form_input ( 'cheques', 0 ) ?></td>
                                </tr>
                                <tr>
                                    <th>Tarjetas Inicial:</th>
                                    <td><?php echo form_input ( 'efectivo', 0 ) ?></td>
                                </tr>
                            </table>
                        </div>
                        <button type="button" class="btn btn-info btn-cierre" aria-label="X">Cierre X</button>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-danger">Cnacelar</button>
                        <button class="btn btn-success" id="btn-openCaja">Abrir Caja</button>
                    </div>
                </div>
            </div>
            <?php echo form_close () ?>
        <?php else: ?>
            <div class="alert alert-block alert-danger">Ya existe una caja abierta para este puesto.</div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#btn-openCaja").click(function () {
            $("#form-open-caja").submit();
        })
        $(".btn-cierre").click(function () {
            valor = $(this).attr('aria-label');
            url = "/caja/cierreJournal";
            $.post(url, {tipo: valor});
        });
    });
</script>