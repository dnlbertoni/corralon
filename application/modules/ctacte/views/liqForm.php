<h1>Liquidacion de la cuenta <?php $nombreCliente ?></h1>
<?php echo form_open('ctacte/liquidarDo', 'id="liq-Form"', $ocultos); ?>
<table>
    <thead>
    <th></th>
    <th>Fecha</th>
    <th>Comp. Firmado</th>
    <th>Ticket/Factura</th>
    <th>Importe</th>
    <th></th>
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
            <td class="precios" id="importe_<?php echo $mov->id ?>"><?php echo sprintf("%6.2f", $mov->importe) ?></td>
            <td id="check_<?php echo $mov->id ?>"><?php echo form_checkbox($mov->id, $mov->id, true) ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <th colspan="4">Total</th>
        <th class="precios" id="totalLiq"><?php echo sprintf("%8.2f", $total) ?></th>
        <td></td>
    </tr>
</table>
<div id="botLiq">Liquidar</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $(".precios").css('text-align', 'right');
        $("#botLiq").button({icons: {primary: 'ui-icon-suitcase'}});
        $("input[type=checkbox]").change(function () {
            $('#totalLiq').html('0');
            $("input[type=checkbox]:checked").each(function () {
                nombre = $(this).parent().attr('id').split('_');
                aux = "#importe_" + nombre[1];
                importe = parseFloat($('#totalLiq').html());
                importe += parseFloat($(aux).html());
                importe = importe.toFixed(2);
                $("#totalLiq").html(importe);
                var importe = $("#totalLiq").html();
                $("input[name='importe']").val(importe);
            });
        });
        var importe = $("#totalLiq").html();
        $("input[name='importe']").val(importe);
        $("#botLiq").click(function () {
            $("#liq-Form").submit();
            //valor = $("input[name='importe']").val();
            //alert(valor);
        });
    });
</script>