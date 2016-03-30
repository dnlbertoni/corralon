<?php
$urlAgregoRubro = "'" . base_url() . "index.php/articulos/rubros/agregar/ajax" . "'" ?>
<div style="text-align:center;">
    <h1>Rubros</h1>
    <table>
        <thead>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>unidad</th>
        <th colspan="3">&nbsp;</th>
        </thead>
        <?php foreach ($rubros as $rubro): ?>
            <tr>
                <td><?php echo $rubro->ID_RUBRO ?></td>
                <td><?php echo $rubro->DESCRIPCION_RUBRO ?></td>
                <td><?php echo $rubro->UNIDAD_RUBRO ?></td>
                <td><?php echo anchor('articulos/rubros/editar/' . $rubro->ID_RUBRO, 'Editar', "class='boton'"); ?></td>
                <td><?php echo anchor('articulos/rubros/subrubros/' . $rubro->ID_RUBRO, 'Subrubros', "class='boton'"); ?></td>
                <td><?php echo anchor('articulos/rubros/borrar/' . $rubro->ID_RUBRO, 'Borrar', 'class="boton"') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div id="botAdd" class='boton'>Agregar</div>
    <?php echo anchor("articulos/", 'Menu Articulos', "class='boton'"); ?>
</div>
<div id="addRubro"></div>

<script>
    $(document).ready(function () {
        $(".boton").button();
        $("#botAdd").button({icons: {primary: 'ui-icon-circle-plus'}});
        $(".boton").css('margin-right', '5px');
        $(".boton").css('margin-left', '5px');
        $("#botAdd").click(function () {
            agregoRubro();
        });
    });

    function agregoRubro() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Agrego Rubro",
            draggable: true,
            resizeable: true,
            close: function () {
                $('#addRubro').dialog("destroy");
            }
        };
        $("#addRubro").dialog(dialogOpts);   //end dialog
        $("#addRubro").load(<?php echo $urlAgregoRubro;?>, [], function () {
                $("#addRubro").dialog("open");
            }
        );
    }
</script>
