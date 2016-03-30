<div class="post">
    <h3>Administrador de Ofertas</h3>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Detalle</th>
            <th>Incio</th>
            <th>Final</th>
            <th>Cant. Articulos</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ofertas as $ofer): ?>
            <tr>
                <td><?php echo $ofer->id ?></td>
                <td><?php echo $ofer->detalle ?></td>
                <td><?php echo $ofer->inicio ?></td>
                <td><?php echo $ofer->final ?></td>
                <td><?php echo $ofer->cantidad ?></td>
                <td><?php echo $ofer->estado ?></td>
                <td>
                    <?php echo anchor('articulos/ofertas/detalle/' . $ofer->id, 'Detalle Articulos', 'class="botDet"'); ?>
                    <?php echo anchor('articulos/ofertas/ver/' . $ofer->id, 'Ver Oferta', 'class="botDet"'); ?>
                    <?php echo anchor('articulos/ofertas/duplicar/' . $ofer->id, 'Duplicar ', 'class="botDet"'); ?>
                    <?php echo anchor('articulos/ofertas/suspender/' . $ofer->id, 'Detalle Articulos', 'class="botDet"'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo anchor('articulos/ofertas/nueva', 'Crear un grupo de Ofertas', 'id="nueva"'); ?>
<div class="post" id="resultado">

</div>
<script>
    $(document).ready(function () {
        $("#nueva").button({icons: {primary: 'ui-icon-circle-plus'}});
    });
</script>