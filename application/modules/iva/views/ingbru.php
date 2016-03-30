<?php if ($selectPeriodo): ?>
    <h1 align="center">Listado Percepciones</h1>
    <h2 align="center">Periodo <?php echo $Periodo ?></h2>
    <table>
        <tr>
            <th>Razon Social</th>
            <th>Cuit</th>
            <th>Importe</th>
        </tr>
        <?php foreach ($facturas as $factura): ?>
            <tr>
                <td><?php echo $factura->razonSocial; ?></td>
                <td><?php echo $factura->cuit ?></td>
                <td align="right"><?php echo sprintf("  %.2f", $factura->importe); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php echo anchor('iva/PercepcionestoExcel/' . $Periodo, 'Exportar Excel', 'id="botExcel"') ?>
    <?php echo anchor('iva/PercepcionestoPdf/' . $Periodo, 'Exportar PDF', 'id="botPDF"') ?>
    <?php echo anchor('iva/PercepcionestoPrint/' . $Periodo, 'Imprimir', 'id="botPrint"'); ?>

<?php else : ?>
    <?php echo form_open('iva/ingbru'); ?>
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