<h1>Informe de la cuenta <?php echo $cliente ?></h1>

<h2>Mes en curso <?php echo $periodo ?></h2>
<table>
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Comprobante</th>
        <th>Importe</th>
    </tr>
    </thead>
    <tbody>
    <?php $total = 0; ?>
    <?php foreach ($pendientes as $last): ?>
        <tr>
            <td><?php echo $last->fecha ?></td>
            <td><?php echo $last->comprobante ?></td>
            <td><?php echo $last->importe ?></td>
            <td>
                <?php echo anchor('ctacte/detalleComprobante/' . $last->id, 'Detalle Comprobante', 'class="botView ajax"'); ?>
                <?php echo anchor('ctacte/detalleComprobante/' . $last->id . '/1', 'Sacar de la Cuenta', 'class="botdel ajax"'); ?>
            </td>
            <?php $total += $last->importe ?>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="2">Total</td>
        <th><?php echo $total ?></th>
    </tr>
    </tbody>
</table>
<?php echo anchor('ctacte/liquidar/' . $idCuenta, 'Liquidar', 'class="botLiq"'); ?>
<h2>Meses Anteriores</h2>
Promedio Mensual $<?php echo sprintf("%05.2F", $promedio) ?>
<table>
    <thead>
    <tr>
        <th>Periodo</th>
        <th>Importe</th>
        <th>Diario</th>
        <th>Tot/Prom</th>
        <th colspan="2">Estado</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($periodos as $hist): ?>
        <tr>
            <td><?php echo $hist->periodo ?></td>
            <td><?php echo $hist->importe ?></td>
            <td><?php echo sprintf("%3.2f ", $hist->importe / 30) ?></td>
            <td><?php echo sprintf("%3.2f ", $hist->importe / $promedio * 100) ?>%</td>
            <td><?php echo
                anchor('ctacte/pdf/liquidacion/' . $hist->id, 'Reimprimir', 'class="botLiq"') ?></td>
            <td>
                <?php if ($hist->estado == "C"): ?>
                    Cobrado
                <?php else: ?>
                    <?php echo
                    anchor('ctacte/cobrar/' . $hist->id, 'Cobrar', 'class="botCob"') ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>
    $("tbody > tr:even").css('background-color', '#F0E3A4');
    $(document).ready(function () {
        $(".botView").button({icons: {primary: 'ui-icon-zoomin'}, text: false});
        $(".botdel").button({icons: {primary: 'ui-icon-trash'}, text: false});
        $(".botLiq").button({icons: {primary: 'ui-icon-print'}});
        $(".botCob").button({icons: {primary: 'ui-icon-suitcase'}});
        $(".ajax").click(function (e) {
            e.preventDefault();
            url = $(this).attr('href');
            var titulo = $(this).text();
            var dialogOpts = {
                modal: true,
                bgiframe: true,
                autoOpen: false,
                height: 500,
                width: 600,
                title: titulo,
                draggable: true,
                resizeable: true,
                close: function () {
                    $('#ventanaAjax').dialog("destroy");
                    location.reload();
                }
            };
            $("#ventanaAjax").dialog(dialogOpts);   //end dialog
            $("#ventanaAjax").load(url, [], function () {
                $("#ventanaAjax").dialog("open");
            });
        });
    });
</script>
