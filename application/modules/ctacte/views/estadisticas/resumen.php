<h1><?php echo $periodo ?></h1>
<table>
    <thead>
    <tr>
        <th>Codigo</th>
        <th>Cliente</th>
        <th>Cant. Compras</th>
        <th>Promedio Compra</th>
        <th>Total</th>
    </tr>
    </thead>
    <?php $total = 0; ?>
    <tbody>
    <?php foreach ($clientes as $cli): ?>
        <tr>
            <td><?php echo $cli->cuenta_id ?></td>
            <td><?php echo $cli->cliente ?></td>
            <td><?php echo $cli->compras ?></td>
            <td><?php echo sprintf("$%5.2f", $cli->total / $cli->compras) ?></td>
            <td><?php echo sprintf("$%5.2f", $cli->total) ?></td>
        </tr>
        <?php $total += $cli->total; ?>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4">Total</th>
        <th><?php echo $total; ?></th>
    </tr>
    </tfoot>
</table>
<div style="text-align: center">
    <?php echo anchor('ctacte/pdf/resumenPeriodo/' . $periodo, 'Imprimir', 'id="botPrint"'); ?>
    <?php echo anchor('ctacte/estadisticas/excelResumenPerido/' . $periodo, 'Exportar Excel', 'id="botExcel"'); ?>
    <?php echo anchor('ctacte/estadisticas/', 'Volver', 'id="botBack"'); ?>
</div>
<script>
    $(document).ready(function () {
        $("#botPrint").button({icons: {primary: 'ui-icon-print'}});
        $("#botExcel").button({icons: {primary: 'ui-icon-document'}});
        $("#botBack").button({icons: {primary: 'ui-icon-cancel'}});
    });
</script>