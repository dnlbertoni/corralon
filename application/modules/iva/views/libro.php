<?php if ($selectLibro && $selectPeriodo): ?>
    <h1 align="center">Libro de IVA <?php echo $Libro ?></h1>
    <h2 align="center">Periodo <?php echo $Periodo ?></h2>
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
            <tr id="<?php echo $factura->id ?>">
                <td><?php echo $factura->fecha ?></td>
                <td><?php echo $factura->tipcomp, " ", $factura->letra, $factura->puesto, "-", $factura->numero; ?></td>
                <td><?php echo $factura->razonSocial; ?></td>
                <td><?php echo $factura->periva ?></td>
                <td align="right"><?php echo sprintf("  %.2f", $factura->importe); ?></td>
                <td align="right"><?php echo sprintf("  %.2f", $factura->ivatot); ?></td>
                <td align="center">
                    <?php if ($factura->suma == 0): ?>
                        No Suma
                    <?php else : ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="4">Totales</th>
            <th id="totimp" align="right"><?php echo sprintf(" $ %.2f", $totimp) ?></th>
            <th id="totiva" align="right"><?php echo sprintf(" $ %.2f", $totiva) ?></th>
            <td>&nbsp;</td>
        </tr>
    </table>
    <?php echo anchor('iva/PeriodotoExcel/' . $libro . '/' . $Periodo, 'Exportar Excel', 'id="botExcel"') ?>
    <?php echo anchor('iva/PeriodotoPdf/' . $libro . '/' . $Periodo, 'Exportar PDF', 'id="botPDF"') ?>
    <?php echo anchor('iva/PeriodotoPrint/' . $libro . '/' . $Periodo, 'Imprimir', 'id="botPrint"'); ?>
<?php else : ?>
    <?php echo form_open('iva/libro'); ?>
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
        $("#botExcel").button({icons: {primary: 'ui-icon-document'}});
        $("#botPDF").button({icons: {primary: 'ui-icon-document'}});
        $("#botPrint").button({icons: {primary: 'ui-icon-print'}});
    });
</script>