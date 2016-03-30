<h1><?php echo $nombreCuenta ?></h1>
<table>
    <thead>
    <tr>
        <th colspan="5">Liquidacion <?php echo $Liq->id ?></th>
    </tr>
    <tr>
        <th>Desde</th>
        <th><?php echo $Liq->fecini ?></th>
        <th></th>
        <th>Hasta</th>
        <th><?php echo $Liq->fecfin ?></th>
    </tr>
    <tr>
        <th></th>
        <th>Fecha</th>
        <th>Comp. Firmado</th>
        <th>Ticket/Factura</th>
        <th>Importe</th>
    </tr>
    </thead>
    <?php
    $total = 0;
    foreach ($movimientos as $mov):?>
        <?php $total += $mov->importe ?>
        <tr>
            <td><?php echo $mov->id ?></td>
            <td><?php echo $mov->fecha ?></td>
            <td><?php echo $mov->firmado ?></td>
            <td><?php echo $mov->comprobante ?></td>
            <td class="precios" id="importe_<?php echo $mov->id ?>"><?php echo sprintf("$%6.2f", $mov->importe) ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <th colspan="4">Total</th>
        <th class="precios" id="totalLiq"><?php echo sprintf("$%8.2f", $total) ?></th>
        <td></td>
    </tr>
</table>
<?php echo anchor('ctacte/cobrarDo/' . $Liq->id, 'Cobrar', 'id="botCobrar"') ?>

<div id="formaPago">
    <div id="debe">
        Importe : $<span id="importeDebe"><?php echo $total ?></span>
    </div>
    <?php echo form_open('ctacte/cobrarDo', 'id="formaPago-form"', $ocultos) ?>
    <div id="forma1">
        Entrega: <?php echo form_input('pago1', $total, 'id="pago1" size="5"'); ?>
        <div class="botOtro"></div>
    </div>
    <div id="saldo">
        Saldo : $<span id="importeSaldo"></span>
    </div>
    <div id="imprimeRec">
        Imprime Recibo
        <?php echo form_label('Si', 'print1') ?><?php echo form_radio('print', 1, TRUE, 'ID="print1"') ?>
        <?php echo form_label('NO', 'print2') ?><?php echo form_radio('print', 2, false, 'ID="print2"') ?>
    </div>
    <?php echo form_close(); ?>
</div>
<script>
    $(document).ready(function () {
        $("#imprimeRec").buttonset();
        $("#botCobrar").button({icons: {primary: 'ui-icon-cart'}});
        $("#formaPago").dialog({
            modal: true,
            height: 250,
            width: 350,
            title: "Forma de Pago",
            draggable: true,
            resizeable: true,
            autoOpen: false,
            buttons: {
                "Efectivo": function () {
                    $('#formaPago-form').submit(), $("#formaPago").dialog("destroy");
                },
                "Tarjeta": function () {
                    $('#formaPago-form').submit(), $("#formaPago").dialog("destroy");
                },
                "Cheque": function () {
                    $('#formaPago-form').submit(), $("#formaPago").dialog("destroy");
                }
            }
        });
        $("#botCobrar").click(function (e) {
            e.preventDefault();
            $("#formaPago").dialog("open");
        });
        $("#pago1").bind('keyup', function () {
            total = $("#importeDebe").html();
            entrega = $("#pago1").val();
            saldo = Math.round((total - entrega) * 100) / 100;
            $("#importeSaldo").html(saldo);
        });
    });
</script>