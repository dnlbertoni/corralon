<h1>Liquidacion de la cuenta <?php echo $cliente ?></h1>

<h2>Mes <?php echo $periodo ?></h2>
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
    <?php foreach ($liquidados as $last): ?>
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

<script>
    $(document).ready(function () {
        $(".precios").css('text-align', 'right');
    });
</script>