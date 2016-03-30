<?php
/*
 * Vista del controlador de carteles  funcion de precios
 *
 */
?>
<div class="post">
    <h1>Listado de Articulos con Cambio de Precio</h1>
    <h3>Se muestran <?php echo ($total > 90) ? 90 : $total ?> de <?php echo $total ?></h3>
    <div class="boton" id="all">Imprimir Todo</div>
    <?php echo form_open($accion, "id='listado'"); ?>
    <table width="85%">
        <thead>
        <tr>
            <th>ID</th>
            <th>Descripcion</th>
            <th>Subrubro</th>
            <th colspan="2">Precio</th>
        </tr>
        </thead>
        <?php $renglon = 0; ?>
        <tbody>
        <?php foreach ($articulos as $articulo): ?>
            <tr>
                <td><?php echo $articulo->id ?></td>
                <td><?php echo $articulo->Descripcion ?></td>
                <td><?php echo $articulo->Subrubro ?></td>
                <td align="right"><?php echo money_format('%= (#8.2n', $articulo->Precio) ?></td>
                <td>
                    <div class="radio">
                        <?php echo form_label('Imprimir', $articulo->id . 'p') ?>
                        <?php echo form_radio($articulo->id, 'p', ($articulo->Precio == 0) ? false : true, 'id="' . $articulo->id . 'p" class="print"') ?>
                        <?php echo form_label('No Imprimir', $articulo->id . 's') ?>
                        <?php echo form_radio($articulo->id, 's', ($articulo->Precio == 0) ? true : false, 'id="' . $articulo->id . 's"  class="save"') ?>
                    </div>
                </td>
            </tr>
            <?php
            $renglon++;
            if ($renglon > 87) {
                break;
            }
            ?>
        <?php endforeach; ?>
        </tbody>
        <tr>
            <th colspan="5" align="center"><?php echo form_submit('', 'Imprimir'); ?></th>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>
<script>
    $(document).ready(function () {
        $("tbody > tr:even").css('background-color', '#F0E3A4');
        $("#all").button();
        $("#all").click(function () {
            $(".print").each(function () {
                $(this).attr('checked', 'checked');
            });
            $(".radio").buttonset("refresh");
        });
        $(".radio").buttonset();
        $(".print").button({icons: {primary: 'ui-icon-print'}, text: false});
        $(".save").button({icons: {primary: 'ui-icon-disk'}, text: false});
    });
</script>
