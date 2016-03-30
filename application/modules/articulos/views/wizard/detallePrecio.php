<div class="post">
    <h1><?php echo $tit ?></h1>
    <div id="detalleArticulo" class="ui-widget">
        <table width="100%">
            <tr>
                <th>Codigo Barra</th>
                <td class="reqTXT" id="CB"><?php echo $articulo->CODIGOBARRA_ARTICULO ?></td>
                <td></td>
                <th>Descripcion</th>
                <td class="reqTXT"><?php echo $articulo->DESCRIPCION_ARTICULO ?></td>
            </tr>
            <tr>
                <th>Subrubro</th>
                <td>
                    (<span class="reqNUM" id="ID_SUBRUBRO"><?php echo $articulo->ID_SUBRUBRO ?></span>)
                    <?php echo $articulo->DESCRIPCION_SUBRUBRO ?>
                    - (<span class="reqNUM"><?php echo $articulo->ID_RUBRO ?></span>)
                    <?php echo $articulo->DESCRIPCION_RUBRO ?>
                </td>
                <td></td>
                <th>Submarca</th>
                <td>
                    (<span class="reqNUM" id="ID_MARCA"><?php echo $articulo->ID_SUBMARCA ?></span>)
                    <?php echo $articulo->DETALLE_SUBMARCA ?>
                    (<span class="reqNUM"><?php echo $articulo->ID_MARCA ?></span>)
                    <?php echo $articulo->DETALLE_MARCA ?>
                </td>
            </tr>
            <tr>
                <th>Espedificacion</th>
                <td class="reqTXT"><?php echo $articulo->especificacion ?></td>
                <td></td>
                <th>Medida</th>
                <td class="reqTXT"><?php echo $articulo->medida ?></td>
            </tr>
            <tr>
                <th>Nombre Final</th>
                <td colspan="4" class="reqTXT"><?php echo $articulo->detalle ?></td>
            </tr>
            <tr>
                <th>Fecha Creacion</th>
                <th><?php echo $articulo->FECHACREACION_ARTICULO ?></th>
                <td>&nbsp;</td>
                <th>Fecha Modificacion</th>
                <th><?php echo $articulo->FECHAMODIF_ARTICULO ?></th>
            </tr>
            <tr>
                <th>Cantidad vendida</th>
                <th><?php echo $ventas ?></th>
                <td>&nbsp;</td>
                <th>Ultima Fecha Facturada</th>
                <th><?php echo $ultimaVenta ?></th>
            </tr>
        </table>
    </div>
    <div id="asignar" class="ui-widget">
        <h2 class="ui-widget-header"><span class="ui-icon ui-icon-circle-plus" style="display: inline-block;"></span>Asignar...
        </h2>
        <div id="resultado" class="ui-widget-content">
            <?php echo form_open($accion, 'id="wizard"', $ocultos) ?>
            <div id="botonBack">Atras</div>
            <div id="botonNext">Continuar</div>
            <?php echo anchor('articulos/wizard/end/1', 'Salir Asistente', 'id="botonSkip"') ?>
            <br/>
            <?php echo form_label('Costo', 'preciocosto'); ?>
            <?php echo form_input('preciocosto_articulo', $articulo->PRECIOCOSTO_ARTICULO, 'id="preciocosto" size="8"'); ?>
            <div id="botonAjustoCosto">Rectifico Costo</div>
            <br/>
            <?php echo form_label('Markup', 'preciovta'); ?>
            Segun datos ( <span id="markupActual"></span> )
            <?php echo form_input('markup_articulo', ($articulo->MARKUP_ARTICULO == 0) ? 70.00 : $articulo->MARKUP_ARTICULO, 'id="markup" size="5"'); ?>
            <span id="minusfive"> -5% </span>
            <span id="plusfive"> +5% </span>
            <div style="width: 80%;margin:auto;">
                <h5>Markup's usados en este rubro</h5>
                <?php foreach ($markupRubros as $clave): ?>
                    <div class="markupRubros"><?php echo $clave ?></div>
                <?php endforeach; ?>
            </div>
            <br/>
            <?php echo form_label('Precio', 'preciovta'); ?>
            <?php echo form_input('preciovta_articulo', $articulo->PRECIOVTA_ARTICULO, 'id="preciovta" size="8"'); ?>
            <br/>
            Tasa Iva
            <div id="radio-iva">
                <?php echo form_label('21%', 'iva1'); ?><?php echo form_radio('TASAIVA_ARTICULO', 21, ($articulo->TASAIVA_ARTICULO == 21) ? true : false, 'id="iva1"') ?>
                <?php echo form_label('10.5%', 'iva2'); ?><?php echo form_radio('TASAIVA_ARTICULO', 10.50, ($articulo->TASAIVA_ARTICULO == 10.50) ? true : false, 'id="iva2"') ?>
                <?php echo form_label('OTRO', 'iva3'); ?><?php echo form_radio('TASAIVA_ARTICULO', 21, ($articulo->TASAIVA_ARTICULO == 0) ? true : false, 'id="iva3"') ?>
            </div>
        </div>
        <?php echo form_close(); ?>
        <input type="hidden" id="paginaAjaxGenero"
               value="<?php echo base_url() . 'index.php/articulos/generoNombre' ?>"/>
    </div>
</div>
<script>
    $(document).ready(function () {
        generoNombre();
        $("#wizard input:text").first().focus();
        $("#radio-iva").buttonset();
        $(".reqTXT").each(function () {
            valor = $(this).text().trim();
            if (valor.length < 1) {
                $(this).addClass('est_0');
            } else {
                $(this).addClass('est_1');
            }
            ;
        });
        $(".reqNUM").each(function () {
            valor = parseFloat($(this).text());
            if (valor > 0) {
                $(this).parent().addClass('est_1');
            } else {
                $(this).parent().addClass('est_0');
            }
            ;
        });
        $("#botonBack").button({icons: {primary: 'ui-icon-seek-prev'}});
        $("#botonSkip").button({icons: {primary: 'ui-icon-seek-next'}});
        $("#botonNext").button({icons: {primary: 'ui-icon-play'}});
        $("#botonNext").click(function () {
            $("#detalle").removeAttr('disabled');
            $("#wizard").submit();
        });
        $("#botonBack").click(function () {
            parent.history.back();
            return false;
        });
        $(".markupRubros").button();
        $(".markupRubros").click(function () {
            valor = $(this).text();
            $("#markup").val(valor);
        });
        $("#plusfive").button();
        $("#plusfive").click(function () {
            valor = parseFloat($("#markup").val()) + 5;
            $("#markup").val(valor);
            precio = parseFloat($("#preciocosto").val());
            markup = parseFloat($("#markup").val()) / 100;
            costo = (precio * (1 + markup)).toFixed(2);
            $("#preciovta").val(costo);
        });
        $("#minusfive").button();
        $("#minusfive").click(function () {
            valor = parseFloat($("#markup").val()) - 5;
            $("#markup").val(valor);
            precio = parseFloat($("#preciocosto").val());
            markup = parseFloat($("#markup").val()) / 100;
            costo = (precio * (1 + markup)).toFixed(2);
            $("#preciovta").val(costo);
        });
        if (isNaN(parseFloat($("#preciocosto").val())) || parseFloat($("#preciocosto").val()) == 0) {
            precio = parseFloat($("#preciovta").val());
            markup = parseFloat($("#markup").val()) / 100;
            costo = (precio / (1 + markup)).toFixed(2);
            $("#preciocosto").val(costo);
        }
        ;
        var valor = ((parseFloat($("#preciovta").val()) / parseFloat($("#preciocosto").val()) - 1) * 100).toFixed(2);
        $("#markupActual").text(valor);
        $("#markup").keyup(function () {
            ajustoPrecio();
        });
        $("#preciovta").keyup(function () {
            ajustoCosto();
        });
        $("#preciocosto").keyup(function () {
            ajustoPrecio();
        });
        $("#botonAjustoCosto").button();
        $("#botonAjustoCosto").click(function () {
            ajustoCosto();
        });
    });
    function generoNombre() {
        pagina = $("#paginaAjaxGenero").val();
        subrubro = $("#ID_SUBRUBRO").text();
        submarca = $("#ID_MARCA").text();
        especif = $("#especificacion").val();
        valor = $("#medida").val();
        medida = valor;
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                subrubro: subrubro,
                submarca: submarca,
                especif: especif,
                medida: medida,
            }),
            dataType: "html",
            async: true,
            success: function (msg) {
                $("#detalle").val(msg);
            }
        }).responseText;
    }
    function ajustoCosto() {
        precio = parseFloat($("#preciovta").val());
        markup = parseFloat($("#markup").val()) / 100;
        costo = (precio / (1 + markup)).toFixed(2);
        $("#preciocosto").val(costo);
    }
    function ajustoPrecio() {
        precio = parseFloat($("#preciocosto").val());
        markup = parseFloat($("#markup").val()) / 100;
        costo = (precio * (1 + markup)).toFixed(2);
        $("#preciovta").val(costo);
    }
</script>