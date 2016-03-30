<?php
//variables para ajax
$borraLotes = sprintf("'%sindex.php/articulos/borrarLote'", base_url());
?>
<?php if (!$vacio): ?>
    <div>
        <br/>
        <div id="allSelect" class="boton">Seleccionar Todos</div>
        <div id="allUnselect" class="boton">Deseleccionar Todos</div>
        <div id="accionSet">
            <?php echo form_label('Asignar Marca', 'accion1'); ?><?php echo form_radio('accion', 'marca', false, 'id="accion1"'); ?>
            <?php echo form_label('Asignar Rubro', 'accion2'); ?><?php echo form_radio('accion', 'rubro', false, 'id="accion2"'); ?>
            <?php echo form_label('Asignar Precio', 'accion3'); ?><?php echo form_radio('accion', 'precio', false, 'id="accion3"'); ?>
        </div>
    </div>
    <br/>
    <?php echo $total ?> Resultados de la busqueda
    <?php echo form_input('filtro', '', 'id="filtro"') ?>
    <?php echo form_open('articulos/cambioMuchos', 'id="resultadosAjax-form"') ?>
    <input type="hidden" name="accion" id="accion" value=""/>
    <table id="datos">
        <thead>
        <th>Codigo</th>
        <th>Descripcion</th>
        <th>Precio</th>
        <th>Subrubro</th>
        <th>Marca</th>
        <th colspan="3">&nbsp;</th>
        </thead>
        <tbody>
        <?php foreach ($articulos as $articulo): ?>
            <tr id="linea_<?php echo $articulo->id ?>" class="linea est_<?php echo $articulo->estado ?>">
                <td><?php echo $articulo->id ?></td>
                <td><?php echo $articulo->nombre ?></td>
                <td><?php echo $articulo->precio ?></td>
                <td><?php echo $articulo->subrubro ?></td>
                <td><?php echo $articulo->marca ?></td>
                <td><?php echo form_checkbox($articulo->id, $articulo->id, false, 'id="' . $articulo->id . '"') ?></td>
                <td><?php echo anchor('articulos/wizard/index/' . $articulo->codigobarra, 'Asistente', 'class="botWi botonAjax"') ?></td>
                <td><?php echo anchor('articulos/ver/' . $articulo->id, 'Editar', 'class="botEd botonAjax"') ?></td>
                <td><?php echo anchor('articulos/borrar/' . $articulo->id, 'Borrar', 'class="botDel botonAjax"') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div id="botCambiar" class="boton">Cambiar Seleccionados</div>
    <div id="botBorrarLote" class="boton">Borrar Seleccionados</div>
    <?php echo form_close(); ?>
<?php endif; ?>


<script>
    $(document).ready(function () {
        $('table').css('font-size', '10px');

        var theTable = $('#datos');
        theTable.find("tbody > tr").mousedown(function () {
            $(this).find(":checkbox").click()
        });
        $("#filtro").keyup(function () {
            $.uiTableFilter(theTable, this.value);
        })

        $(".botWi").button({icons: {primary: 'ui-icon-star'}, text: false});
        $(".botEd").button({icons: {primary: 'ui-icon-pencil'}, text: false});
        $(".botDel").button({icons: {primary: 'ui-icon-trash'}, text: false});
        $(".boton").button();
        $('.est_1').each(function () {
            $(this).css('background-color', 'green');
            $(this).css('color', '#FFF');
        });
        $('.est_0').each(function () {
            $(this).css('background-color', 'red');
            $(this).css('color', '#FFF');
        });
        $("#allSelect").click(function () {
            $(":checkbox").attr('checked', 'checked');
            $(".linea").addClass('focus');
        });
        $("#allUnselect").click(function () {
            $(":checkbox").removeAttr('checked');
            $(".linea").removeClass('focus');
        });
        $("#accionSet").buttonset();
        $("#accionSet").change(function () {
            valor = $("[name=accion]:checked").val();
            $("#accion").val(valor);
        });
        $("#botCambiar").click(function () {
            var accion = $("[name=accion]:checked").val();
            if (accion == undefined) {
                alert("Debe elegir una accion");
            } else {
                $('#resultadosAjax-form').submit();
            }
        });
        $(":checkbox").change(function () {
            valor = $(this).attr('checked');
            nombre = "#linea_" + $(this).attr('id');
            $(this).css('background-color', 'white');
            $(this).css('color', '#000');
            $(this).removeClass('est_0');
            if (valor) {
                $(nombre).addClass('focus');
            } else {
                $(nombre).removeClass('focus');
            }
        });
        $("#botBorrarLote1").click(function () {
            $("#resultadosAjax-form").attr('action', <?php echo $borraLotes?>);
            $('#resultadosAjax-form').submit();
        });
    });
</script>

