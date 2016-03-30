<?php
//variables para ajax
$borraLotes = sprintf("'%sindex.php/articulos/borrarLote'", base_url());
?>
Detalle de la Empresa <?php echo $empresa ?> - <h1><?php echo $nombreEmpresa ?></h1>
<?php echo form_open('articulos/empresas/asignaEmpresa', 'id="asigna-form"') ?>
<h2>Empresa</h2>
<input type="hidden" name="id" id="id" value="<?php echo $empresa ?>"/>
<input type="hidden" name="id_marca" id="campo" value=""/>
<div id="resultadoAjaxEmpresa"></div>
<div id="botBuscarEmpresa" class="boton">Buscar Empresa</div>
<div id="botCambiarEmpresa" class="boton">Asignar Empresa</div>
<?php echo form_close(); ?>
<div>Filtrar:<?php echo form_input('filtro', '', 'id="filtro"'); ?> | <span id="resultadosFiltrados"></span>&nbsp;Resultados
</div>
<?php echo form_open('articulos/empresas/asigna', 'id="marca-form"') ?>
<input type="hidden" name="valor" id="valor" value=""/>
<input type="hidden" name="tipo" id="tipo" value=""/>
<div style="width:48%;float:right">
    <div id="resultadoAjaxMarca"></div>
    <div id="botBuscarMarca" class="boton">Buscar Marca</div>
    <div id="botCambiarMarca" class="boton">Asignar Marca</div>
</div>
<div style="width:48%;float:left">
    <div id="resultadoAjaxRubro"></div>
    <div id="botBuscarRubro" class="boton">Buscar Rubro</div>
    <div id="botCambiarRubro" class="boton">Asignar Rubro</div>
</div>
<div>
    <div class="boton" id="selAll">Seleccionar Todos</div>
    <div class="boton" id="delete">Borrar Seleccionados</div>
</div>
<table id="datos">
    <thead>
    <th>Codigo</th>
    <th>Descripcion</th>
    <th>Codigo Barra</th>
    <th>Submarca</th>
    <th>Marca</th>
    <th>Subrubro</th>
    <th>Rubro</th>
    <th colspan="2">Ult. Mod.</th>
    </thead>
    <tbody>
    <?php foreach ($articulos as $articulo): ?>
        <tr class="est_<?php echo $articulo->estado ?>" id="linea_<?php echo $articulo->id ?>">
            <td><?php echo $articulo->id ?></td>
            <td><?php echo $articulo->descripcion ?></td>
            <td><?php echo $articulo->codigobarra ?></td>
            <td><?php echo $articulo->submarca ?></td>
            <td><?php echo $articulo->marca ?></td>
            <td><?php echo $articulo->subrubro ?></td>
            <td><?php echo $articulo->rubro ?></td>
            <td><?php echo $articulo->modif ?></td>
            <td><?php echo form_checkbox($articulo->id, $articulo->id, false); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php echo form_close(); ?>
<div id="search"></div>

<script>
    $(document).ready(function () {
        $('.boton').button();
        //$("#datos").tableFilter();
        var theTable = $('#datos');
        var valor = theTable.find("tbody > tr:visible").length;
        $("#resultadosFiltrados").html(valor);
        theTable.find("tbody > tr").mousedown(function () {
            $(this).find(":checkbox").click()
        });
        $("#filtro").keyup(function () {
            $.uiTableFilter(theTable, this.value);
            var valor = theTable.find("tbody > tr:visible").length;
            $("#resultadosFiltrados").html(valor);
        })

        $("#botCambiarEmpresa").click(function () {
            $('#asigna-form').submit();
        });
        $("#botBuscarEmpresa").click(function () {
            buscoAsignacionEmpresa();
        });
        $("#botCambiarMarca").click(function () {
            $("#tipo").val('marca');
            $('#marca-form').submit();
        });
        $("#botBuscarMarca").click(function () {
            buscoAsignacionMarca();
        });
        $("#botCambiarRubro").click(function () {
            $("#tipo").val('rubro');
            $('#marca-form').submit();
        });
        $("#botBuscarRubro").click(function () {
            buscoAsignacionRubro();
        });
        $("#selAll").click(function () {
            theTable.find("tbody > tr:visible").find(":checkbox").click();
        });
        $('form').addClass('ui-widget');
        $('form').addClass('ui-widget-content');
        $('form').css('padding', '5px');
        $("#delete").click(function () {
            $("#marca-form").attr('action', <?php echo $borraLotes?>);
            $('#marca-form').submit();
        });
    });
    function buscoAsignacionEmpresa() {
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
                resultado = $("#resultadoAjaxEmpresa >.id_marca").html();
                $("#campo").val(resultado);
            }
        };
        $('#search').dialog(dialogOpts);
        $("#search").load(<?php echo $urlBuscoAjaxEmpresa;?>, [], function () {
                $("#search").dialog("open");
            }
        );
    }
    function buscoAsignacionMarca() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: '>>MARCA<<',
            draggable: true,
            resizeable: true,
            close: function (data) {
                $('#search').dialog("destroy");
                resultado = $("#resultadoAjaxMarca >.codigo").html();
                $("#valor").val(resultado);
            }
        };
        $('#search').dialog(dialogOpts);
        $("#search").load(<?php echo $urlBuscoAjaxMarca;?>, [], function () {
                $("#search").dialog("open");
            }
        );
    }
    function buscoAsignacionRubro() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: '>>RUBRO<<',
            draggable: true,
            resizeable: true,
            close: function (data) {
                $('#search').dialog("destroy");
                resultado = $("#resultadoAjaxRubro >.codigo").html();
                $("#valor").val(resultado);
            }
        };
        $('#search').dialog(dialogOpts);
        $("#search").load(<?php echo $urlBuscoAjaxRubro;?>, [], function () {
                $("#search").dialog("open");
            }
        );
    }
</script>
