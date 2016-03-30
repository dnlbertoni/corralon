<div id="resultadoAjax"></div>
<div id="botBuscar" class="boton">Calcular</div>
<div id="botCambiar" class="boton">Asignar Precios</div>
<?php echo form_open('articulos/cambioMuchosDo/precios', 'id="cambioMuchos-form"') ?>
<input type="hidden" name="<?php echo $accion ?>" id="campo" value=""/>
<table>
    <tr>
        <th>Codigo</th>
        <th>Descripcion</th>
        <th>Subrubro</th>
        <th>Submarca</th>
        <th>Precio Act.</th>
        <th colspan="2">Nuevo</th>
    </tr>
    <?php foreach ($articulos as $articulo): ?>
        <tr>
            <td><?php echo $articulo->ID_ARTICULO ?></td>
            <td><?php echo $articulo->DESCRIPCION_ARTICULO ?></td>
            <td><?php echo $articulo->DESCRIPCION_SUBRUBRO ?></td>
            <td><?php echo $articulo->DETALLE_SUBMARCA ?></td>
            <td><?php echo $articulo->PRECIOVTA_ARTICULO ?></td>
            <td><?php echo form_input('precio_' . $articulo->ID_ARTICULO, $articulo->PRECIOVTA_ARTICULO, 'class="precioNuevo" size="4"'); ?></td>
            <td><?php echo form_checkbox($articulo->ID_ARTICULO, $articulo->ID_ARTICULO, true) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php echo form_close(); ?>
<div id="search"></div>

<script>
    $(document).ready(function () {
        buscoAsignacion();
        $('.boton').button();
        $("#botCambiar").click(function () {
            $('#cambioMuchos-form').submit();
        });
        $("#botBuscar").click(function () {
            buscoAsignacion();
        });
    });
    function buscoAsignacion() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: <?php echo $titulo?>,
            draggable: true,
            resizeable: true,
            close: function (data) {
                $('#search').dialog("destroy");
                resultado = $("#resultadoAjax >.codigo").html();
                $("#campo").val(resultado);
            }
        };
        $('#search').dialog(dialogOpts);
        $("#search").load(<?php echo $urlBuscoAjax;?>, [], function () {
                $("#search").dialog("open");
            }
        );
    }
</script>
