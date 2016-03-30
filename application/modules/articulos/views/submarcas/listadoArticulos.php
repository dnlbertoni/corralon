<h1><?php echo $submarca ?> - <?php echo $marca ?></h1>
<table>
    <thead>
    <tr>
        <th>Codigo Barra</th>
        <th>Nombre</th>
        <th>SubRubro</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($articulos as $arti): ?>
        <tr class="<?php echo ($arti->w == 1) ? 'est_w' : 'est_nw' ?>">
            <td><?php echo $arti->cb ?></td>
            <td><?php echo $arti->nombre ?></td>
            <td><?php echo $arti->rubro ?></td>
            <td>
                <?php echo anchor('articulos/wizard/index/' . $arti->cb, 'Asistente', 'class="botonAsistente" target="_blank"') ?>
                <?php echo anchor('articulos/borrar/' . $arti->id . '/1', 'Borrar', 'class="botonBorrar botonAjax"') ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $(".botonAsistente").button({icons: {primary: 'ui-icon-star'}, text: false});
        $(".botonBorrar").button({icons: {primary: 'ui-icon-trash'}, text: false});
    });
</script>