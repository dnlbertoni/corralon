<div style="text-align:center;">
    <h1>Subrubros</h1>
    <div>Filtrar <?php echo form_input('filtro', '', 'id="filtro"'); ?></div>
    <table id="datos">
        <thead>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Rubro</th>
        <th>Nombre para el Producto</th>
        <th>Cant. Art.</th>
        <th>Asistente</th>
        <th>&nbsp;</th>
        </thead>
        <?php $total = 0; ?>
        <?php foreach ($subrubros as $subrubro): ?>
            <?php $clase = ($subrubro->articulos == $subrubro->Warticulos) ? 'est_w' : 'est_nw' ?>
            <tr>
                <td><?php echo $subrubro->ID_SUBRUBRO ?></td>
                <td><?php echo $subrubro->DESCRIPCION_SUBRUBRO ?></td>
                <td><?php echo $subrubro->rubro ?></td>
                <td class="alias"><?php echo $subrubro->ALIAS_SUBRUBRO ?></td>
                <td class="<?php echo $clase ?>"><?php echo $subrubro->articulos ?></td>
                <td class="<?php echo $clase ?>"><?php echo $subrubro->Warticulos ?></td>
                <?php $total += $subrubro->articulos; ?>
                <td>
                    <?php echo anchor('articulos/subrubros/verArticulos/' . $subrubro->ID_SUBRUBRO, 'Ver Articulos', "class='botVerArt ajax'"); ?>
                    <?php echo anchor('articulos/subrubros/editar/' . $subrubro->ID_SUBRUBRO, 'Editar', "class='botEdit'"); ?>
                    <?php echo anchor('articulos/subrubros/borrar/' . $subrubro->ID_SUBRUBRO, 'Borrar', 'class="botDel"') ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="4">Total Ariculos</th>
            <th><?php echo $total ?></th>
            <th>&nbsp;</th>
        </tr>
    </table>
    <?php echo anchor('articulos/subrubros/agregar', "Agregar", "class='boton'"); ?>
    <?php echo anchor('articulos/', 'Menu Articulos', "class='boton'"); ?>
</div>


<script>
    $(document).ready(function () {
        var theTable = $("#datos");
        $("#filtro").keyup(function () {
            $.uiTableFilter(theTable, this.value);
        });
        $(".boton").button();
        $(".boton").css('margin-right', '5px');
        $(".boton").css('margin-left', '5px');
        $("#botSearch").click(function () {
            buscoSubmarca();
        });
        $(".alias").each(function () {
            var valor = $.trim($(this).text());
            if (valor == '') {
                $(this).addClass('est_0');
            }
        });
        $(".botVerArt").button({icons: {primary: 'ui-icon-zoomin'}, text: false});
        $(".botEdit").button({icons: {primary: 'ui-icon-pencil'}, text: false});
        $(".botDel").button({icons: {primary: 'ui-icon-trash'}, text: false});
        $(".ajax").click(function (e) {
            e.preventDefault();
            url = $(this).attr('href');
            var titulo = $(this).text();
            var dialogOpts = {
                modal: true,
                bgiframe: true,
                autoOpen: false,
                height: 600,
                width: 850,
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
