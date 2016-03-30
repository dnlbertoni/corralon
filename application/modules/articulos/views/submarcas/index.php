<?php $urlBuscoAjax = "'" . base_url() . "index.php/articulos/submarcas/searchAjax/resultadoAjax" . "'"; ?>
<div style="text-align:center;">
    <div id="botSearch" class="boton">Buscar</div>
    <div id="resultadoAjax"></div>
    <h1>Submarcas</h1>
    <div>Filtrar <?php echo form_input('filtro', '', 'id="filtro"'); ?></div>
    <table id="datos">
        <thead>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Alias</th>
        <th>Marca</th>
        <th>Asistente</th>
        <th>Cant. Art.</th>
        <th>&nbsp;</th>
        </thead>
        <?php $total = 0; ?>
        <tbody>
        <?php foreach ($submarcas as $submarca): ?>
            <?php $clase = ($submarca->articulos == $submarca->Warticulos) ? 'est_w' : 'est_nw' ?>
            <tr>
                <td><?php echo $submarca->ID_SUBMARCA ?></td>
                <td><?php echo $submarca->DETALLE_SUBMARCA ?></td>
                <td class="alias"><?php echo $submarca->ALIAS_SUBMARCA ?></td>
                <td><?php echo $submarca->marca ?></td>
                <td class="<?php echo $clase ?>"><?php echo $submarca->articulos ?></td>
                <td class="<?php echo $clase ?>"><?php echo $submarca->Warticulos ?></td>
                <?php $total += $submarca->articulos; ?>
                <td>
                    <?php echo anchor('articulos/submarcas/verArticulos/' . $submarca->ID_SUBMARCA, 'Ver Articulos', "class='botVerArt ajax'"); ?>
                    <?php echo anchor('articulos/submarcas/editar/' . $submarca->ID_SUBMARCA, 'Editar', "class='botEdit'"); ?>
                    <?php echo anchor('articulos/submarcas/borrar/' . $submarca->ID_SUBMARCA, 'Borrar', 'class="botDel"') ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="4">Total Ariculos</th>
            <th><?php echo $total ?></th>
            <th>&nbsp;</th>
        </tr>
    </table>
    <?php echo anchor('articulos/submarcas/agregar', "Agregar", "class='boton'"); ?>
    <?php echo anchor('articulos/', 'Menu Articulos', "class='boton'"); ?>
</div>
<div id="searchSubmarca"></div>

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
    function buscoSubmarca() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Busco Marca",
            draggable: true,
            resizeable: true,
            close: function (data) {
                $('#searchSubmarca').dialog("destroy");
            }
        };
        $('#searchSubmarca').dialog(dialogOpts);
        $("#searchSubmarca").load(<?php echo $urlBuscoAjax;?>, [], function () {
                $("#searchSubmarca").dialog("open");
            }
        );
    }
</script>
