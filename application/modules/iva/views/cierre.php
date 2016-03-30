<style>
    .in {
        background-color: green;
        color: #FFF;
    }

    .out {
        background-color: white;
    }
</style>
<?php if ($selectLibro && $selectPeriodo): ?>
    <h1 align="center">Libro de IVA <?php echo $Libro ?></h1>
    <h2 align="center">Periodo <?php echo $Periodo ?></h2>
    <div id="botSel">Seleccionar Todo</div>
    <?php echo form_open('iva/cierreDo', 'id="cierre"'); ?>
    <table>
        <tr>
            <th>Fecha</th>
            <th>Comp.</th>
            <th>Razon Social</th>
            <th>Periodo</th>
            <th>Importe</th>
            <th>IVA</th>
            <th>Pasado</th>
        </tr>
        <?php foreach ($facturas as $factura): ?>
            <tr id="<?php echo $factura->id ?>" class="<?php echo ($factura->ivapass == 1) ? 'in' : 'out' ?>">
                <td><?php echo $factura->fecha ?></td>
                <!--    <td><?php echo $factura->tipcomp, " ", $factura->letra, $factura->puesto, "-", $factura->numero; ?></td> -->
                <td><?php echo $factura->tipcomp; ?></td>
                <td><?php echo $factura->razonSocial; ?></td>
                <td><?php echo $factura->periva ?></td>
                <td align="right"><?php echo sprintf("  %.2f", $factura->importe); ?></td>
                <td align="right"><?php echo sprintf("  %.2f", $factura->ivatot); ?></td>
                <td align="center">
                    <?php if ($factura->suma == 0): ?>
                        No Suma
                    <?php else : ?>
                        (-)<?php echo form_radio($factura->id, 0, ($factura->ivapass == 0) ? true : false, 'id="radio_' . $factura->id . '_-"'); ?>
                        (+)<?php echo form_radio($factura->id, 1, ($factura->ivapass == 1) ? true : false, 'id="radio_' . $factura->id . '_+"'); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="4">Totales(<span id="canFac"><?php echo count($facturas) ?></span>)</th>
            <th id="totimp" align="right"><?php echo sprintf(" $ %.2f", $totimp) ?></th>
            <th id="totiva" align="right"><?php echo sprintf(" $ %.2f", $totiva) ?></th>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6"><?php echo form_submit('Cerrar', 'Cerrar'); ?></td>
        </tr>
    </table>
    <?php echo form_hidden('periodo', $Periodo); ?>
    <?php echo form_close(); ?>
<?php else : ?>
    <?php echo form_open('iva/cierre'); ?>
    Ventas <?php echo form_radio('libro', '1', true); ?>
    <br/>
    Compras <?php echo form_radio('libro', '2') ?>
    <br/>
    Periodo :<?php echo form_dropdown('periodo', $periodos) ?>
    <?php echo form_submit('Ver', 'Ver'); ?>
    <?php echo form_close(); ?>
<?php endif; ?>

<script>
    $(document).ready(function () {
        $("#botSel").button();
        $("#botSel").click(function () {
            $("input:radio").each(function () {
                $(this).attr("checked", 1);
            });
            SumoTotal();
            SumoIva();
        });
        $("input:radio").change(function () {
            SumoTotal();
            SumoIva();
            var valor = $(this).attr('id').split('_');
            var nombre = "#" + valor[1];
            if ($(nombre).hasClass('in')) {
                $(nombre).addClass('out');
                $(nombre).removeClass('in');
            } else {
                $(nombre).addClass('in');
                $(nombre).removeClass('out');
            }
        });
        $('table').css('font-size', '10px');
    });
    function SumoTotal() {
        var Total = 0;
        var Cant = 0;
        $("input:radio:checked").each(function () {
            var valor = $(this).parent().prev().prev().text();
            valor = parseFloat(valor) * parseInt($(this).val());
            Total += valor;
            Cant += parseInt($(this).val());
        });
        Total = Math.round(Total * Math.pow(10, 2)) / Math.pow(10, 2);
        $("#totimp").html(Total);
        $("#canFac").html(Cant);
    }
    function SumoIva() {
        var Total = 0;
        $("input:radio:checked").each(function () {
            var valor = $(this).parent().prev().text();
            valor = parseFloat(valor) * $(this).val();
            Total += valor;
        });
        Total = Math.round(Total * Math.pow(10, 2)) / Math.pow(10, 2);
        $("#totiva").html(Total);
    }
</script>
