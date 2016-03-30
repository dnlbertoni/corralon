<?php
$urlListaSubrubros = sprintf("'%sindex.php/articulos/subrubros/combosubrubros'", base_url());
$urlListaSubmarcas = sprintf("'%sindex.php/articulos/submarcas/combosubmarcas'", base_url());
$urlConsultaPorRubros = sprintf("'%sindex.php/articulos/buscoPorRubro'", base_url());
$urlConsultaPorMarcas = sprintf("'%sindex.php/articulos/buscoPorMarca'", base_url());
$urlConsultaAvanzada = sprintf("'%sindex.php/articulos/busquedaAvanzada'", base_url());
$urlConsultaGlobal = sprintf("'%sindex.php/articulos/busquedaGlobal'", base_url());
$urlConsultaMarca = sprintf("'%sindex.php/articulos/busquedaGobalMarca'", base_url());
$urlBuscoAjaxRubro = sprintf("'%sindex.php/articulos/subrubros/searchAjax/%s'", base_url(), 'resultadoAjaxRubros');
$urlBuscoAjaxMarca = sprintf("'%sindex.php/articulos/submarcas/searchAjax/%s'", base_url(), 'resultadoAjaxMarcas');
?>
<div id="PorCodigoBarra" class="box">
    <h2>Por Codigo Barra</h2>
    <?php echo form_open($accionCodigobarra, 'id="buscoDetalle" class="search"'); ?>
    <?php echo form_input('codigobarra', '', 'id="codigobarra"'); ?> <br/>(Codigobarra)
    <?php echo form_close(); ?>
</div>
<div id="PorNombre" class="box">
    <h2>Por Nombre</h2>
    <?php echo form_open('articulos/buscoDescripcion', 'id="buscoNombre"'); ?>
    <?php echo form_input('descripcionTXT', '', 'id="descripcionTXT"'); ?><br/>(Detalle)
    <?php echo form_close(); ?>
</div>
<div class="clear"></div>
<div id="Avanzada" class="box">
    <h2>Avanzada</h2>
    <div>
        <span id="botBuscoRubros" class="boton">Subrubros</span>
        <span id="botBuscoMarcas" class="boton">Submarcas</span>
    </div>
    <div id="resultadoAjaxRubros"></div>
    <div id="resultadoAjaxMarcas"></div>
    <div id="botAvanzada" class="boton">Buscar</div>
</div>
<div id="Global" class="box">
    <h2>Global</h2>
    Rubro <?php echo form_dropdown('rubro', $rubrosSel, 'Seleccionar...', 'id="rubroGlob"'); ?>
    Marca <?php echo form_dropdown('marca', $marcasSel, 'Seleccionar...', 'id="marcaGlob"'); ?>
    <div id="botGlobal" class="boton">Buscar</div>
</div>
<div class="clear"></div>
<div id="Empresas" class="box">
    <h2>Asistente Articulos</h2>
    <?php echo anchor('articulos/wizard/masivo', 'Ingresar', 'class="boton"'); ?>
</div>
<div id="soloMarca" class="box">
    <h2>Solo Marca</h2>
    Marca <?php echo form_dropdown('marca', $marcasSel, 'Seleccionar...', 'id="marcaGlobal"'); ?>
    <div id="botGlobalMarca" class="boton">Buscar</div>
</div>
<div class="clear"></div>
<div id="searchSubrubros"></div>
<div id="searchSubmarcas"></div>
<div class="clear"></div>
<div id="datos"></div>
<script>
    $(document).ready(function () {
        $('#codigobarra').focus();
        $('#codigobarra').addClass('focus');
        $('#buscoNombre').submit(function (e) {
            e.preventDefault();
        });
        $("#buscoDetalle").submit(function (e) {
            valor = $("#codigobarra").val().trim();
            if (valor == "") {
                e.preventDefault();
            }
        });
        $("#descripcionTXT").bind('keyup', function (e) {
            var code = e.keyCode;
            if (( code < 90 && code > 57 ) || code == 13 || code == 8) {
                if ($("#descripcionTXT").val().length > 3)
                    envioNombre();
            }
        });
        $("#PorCodigoBarra").css('float', 'right');
        $("#Avanzada").css('float', 'right');
        $("#Empresas").css('float', 'right');
        $(".box").css('width', '45%');
        $(".box").addClass('ui-widget');
        $(".box").addClass('ui-widget-content');
        $(".box").css('padding', '10px');
        $(".box").css('text-align', 'center');

        //$(".large").css('width', '45%');
        $(".large").addClass('ui-widget');
        $(".large").addClass('ui-widget-content');
        $(".large").css('padding', '10px');

        $(".boton").button();
        $("#botRubro").click(function () {
            envioRubro();
        });
        $("#botMarca").click(function () {
            envioMarca();
        });
        $("#botAvanzada").click(function () {
            envioAvanzada();
        });
        $("#botGlobal").click(function () {
            rubro = $("#rubroGlob option:selected").val();
            marca = $("#marcaGlob option:selected").val();
            $.post( <?= $urlConsultaGlobal?>, {rubro: rubro, marca: marca},
                function (data) {
                    $("#datos").html(data);
                });
        });
        $("#botGlobalMarca").click(function () {
            marca = $("#marcaGlobal option:selected").val();
            $.post( <?= $urlConsultaMarca?>, {marca: marca},
                function (data) {
                    $("#datos").html(data);
                });
        });
    });
    $(document).ready(function () {
        // Parametros para rubros
        $("#rubrosSel").change(function () {
            $("#rubrosSel option:selected").each(function () {
                //alert($(this).val());
                elegido = $(this).val();
                $.post( <?= $urlListaSubrubros?>, {id: elegido},
                    function (data) {
                        $("#subrubrosSel").html(data);
                    });
            });
        });
        // Parametros para marcas
        $("#marcasSel").change(function () {
            $("#marcasSel option:selected").each(function () {
                //alert($(this).val());
                elegido = $(this).val();
                $.post( <?= $urlListaSubmarcas?>, {id: elegido},
                    function (data) {
                        $("#submarcasSel").html(data);
                    });
            });
        });
        $("#botBuscoRubros").click(function () {
            buscoSubrubro();
        });
        $("#botBuscoMarcas").click(function () {
            buscoSubmarca();
        });
    });
    function envioNombre() {
        nombre = $("#descripcionTXT").val().trim().toUpperCase();
        $("#descripcionTXT").val(nombre);
        pagina = $("#buscoNombre").attr('action');
        if (nombre.length > 0) {
            $.ajax({
                url: pagina,
                contentType: "application/x-www-form-urlencoded",
                global: false,
                type: "POST",
                data: ({
                    nombreTXT: nombre
                }),
                dataType: "html",
                async: true,
                success: function (msg) {
                    $("#datos").html(msg);
                }
            }).responseText;
        }
    }
    function envioRubro() {
        //valor  = $("#subrubrosSel option:selected").val();
        valor = $("#resultadoAjaxRubros >.codigo").html();
        pagina = <?php echo $urlConsultaPorRubros?>;
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                subrubro: valor
            }),
            dataType: "html",
            async: true,
            success: function (msg) {
                $("#datos").html(msg);
            }
        }).responseText;
    }
    function envioMarca() {
        //valor  = $("#submarcasSel option:selected").val();
        valor = $("#resultadoAjaxMarcas >.codigo").html();
        pagina = <?php echo $urlConsultaPorMarcas?>;
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                submarca: valor
            }),
            dataType: "html",
            async: true,
            success: function (msg) {
                $("#datos").html(msg);
            }
        }).responseText;
    }
    function envioAvanzada() {
        marca = $("#resultadoAjaxMarcas >.codigo").html();
        rubro = $("#resultadoAjaxRubros >.codigo").html();
        pagina = <?php echo $urlConsultaAvanzada?>;
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                submarca: marca,
                subrubro: rubro
            }),
            dataType: "html",
            async: true,
            success: function (msg) {
                $("#datos").html(msg);
            }
        }).responseText;
    }
    function buscoSubrubro() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Busco Subrubro",
            draggable: true,
            resizeable: true,
            close: function (data) {
                $('#searchSubrubros').dialog("destroy");
                //resultado = $("#resultadoAjaxRubros").html();
                //$("#rubroAvanzado").val() = resultado;
            }
        };
        $('#searchSubrubros').dialog(dialogOpts);
        $("#searchSubrubros").load(<?php echo $urlBuscoAjaxRubro;?>, [], function () {
                $("#searchSubrubros").dialog("open");
            }
        );
    }
    function buscoSubmarca() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Busco Submarca",
            draggable: true,
            resizeable: true,
            close: function (data) {
                $('#searchSubmarcas').dialog("destroy");
                //resultado = $("#resultadoAjaxRubros").html();
                //$("#rubroAvanzado").val() = resultado;
            }
        };
        $('#searchSubmarcas').dialog(dialogOpts);
        $("#searchSubmarcas").load(<?php echo $urlBuscoAjaxMarca;?>, [], function () {
                $("#searchSubmarcas").dialog("open");
            }
        );
    }
</script>
