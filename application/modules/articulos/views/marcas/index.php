<?php
$urlAgregoMarca = "'" . base_url() . "index.php/articulos/marcas/agregar/ajax" . "'" ?>
<div style="text-align:center;">
    <h1>Marcas</h1>
    <div>Filtrar <?php echo form_input('filtro', '', 'id="filtro"'); ?></div>
    <table id="datos">
        <thead>
        <th>Codigo</th>
        <th>Nombre</th>
        <th colspan="3">&nbsp;</th>
        </thead>
        <tbody>
        <?php foreach ($marcas as $marca): ?>
            <tr>
                <td><?php echo $marca->ID_MARCA ?></td>
                <td><?php echo $marca->DETALLE_MARCA ?></td>
                <td><?php echo anchor('articulos/marcas/editar/' . $marca->ID_MARCA, 'Editar', "class='boton'"); ?></td>
                <td><?php echo anchor('articulos/marcas/submarcas/' . $marca->ID_MARCA, 'Submarcas', "class='boton'"); ?></td>
                <td><?php echo anchor('articulos/marcas/borrar/' . $marca->ID_MARCA, 'Borrar', 'class="boton"') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div id="botAdd" class='boton'>Agregar</div>
    <?php echo anchor("articulos/", 'Menu Articulos', "class='boton'"); ?>
</div>
<div id="addMarca"></div>

<script>
    $(document).ready(function () {
        var theTable = $("#datos");
        $("#filtro").keyup(function () {
            $.uiTableFilter(theTable, this.value);
        });
        $(".boton").button();
        $("#botAdd").button({icons: {primary: 'ui-icon-circle-plus'}});
        $(".boton").css('margin-right', '5px');
        $(".boton").css('margin-left', '5px');
        $("#botAdd").click(function () {
            agregoMarca();
        });
    });

    function agregoMarca() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Agrego Marca",
            draggable: true,
            resizeable: true,
            close: function () {
                $('#addRubro').dialog("destroy");
            }
        };
        $("#addMarca").dialog(dialogOpts);   //end dialog
        $("#addMarca").load(<?php echo $urlAgregoMarca;?>, [], function () {
                $("#addMarca").dialog("open");
            }
        );
    }
</script>
