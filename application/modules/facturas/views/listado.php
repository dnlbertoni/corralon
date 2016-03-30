<div class="post">
    <h3>Listado Comprobantes </h3>
    <h4 class="meta">Cuenta: <?php echo $provee ?> - Fechas <?php echo $fechas ?></h4>
    <table>
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Comprobante</th>
            <th>Proveedor</th>
            <th>Importe Fact.</th>
            <th>Perido Iva</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php $total = 0; ?>
        <?php foreach ($facturas as $f): ?>
            <?php $total += $f->importe; ?>
            <tr>
                <td><?php echo $f->fecha ?></td>
                <td><?php echo $f->comprobante ?></td>
                <td><?php echo $f->nombre ?></td>
                <td><?php echo money_format('%= (#10.2n', $f->importe) ?></td>
                <td><?php echo $f->periva ?></td>
                <td>
                    <?php echo anchor('facturas/view/' . $f->id, 'Ver', 'class="botView ajax"') ?>
                    <?php echo anchor('facturas/editar/' . $f->id, 'Editar', 'class="botEdit ajax"') ?>
                    <?php echo anchor('facturas/borrar/' . $f->id, 'Borrar', 'class="botDel ajax"') ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tr>
            <th colspan="3">Total</th>
            <th><?php echo money_format('%= (#10.2n', $total) ?></th>
            <th colspan="2">&nbsp;</th>
        </tr>
    </table>
</div>
<script>
    $("tbody > tr:even").css('background-color', '#F0E3A4');
    $(document).ready(function () {
        $(".botView").button({icons: {primary: 'ui-icon-zoomin'}, text: false});
        $(".botDel").button({icons: {primary: 'ui-icon-trash'}, text: false});
        $(".botEdit").button({icons: {primary: 'ui-icon-pencil'}});
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
