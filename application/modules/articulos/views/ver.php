<?php
$urlBuscoAjaxMarca = sprintf("'%sindex.php/articulos/submarcas/searchAjax/%s'", base_url(), 'resultadoAjaxMarcas');
$urlBuscoAjaxRubro = sprintf("'%sindex.php/articulos/subrubros/searchAjax/%s'", base_url(), 'resultadoAjaxRubros');
?>
<input type="hidden" id="nuevo" value="<?php echo ($new) ? 1 : 0 ?>"/>
<input type="hidden" id="paginaAjaxGenero" value="<?php echo base_url() . 'index.php/articulos/generoNombre' ?>"/>
<?php echo form_open($accion, "id='form-articulo'"); ?>
<table>
    <tr>
        <td>Id</td>
        <td>
            <?php
            $attrib = "readonly ";
            $attrib .= "id='ID_ARTICULO'";
            $attrib .= "size='5'";
            $attrib .= "class='readonly'";
            echo form_input('ID_ARTICULO', $Articulo->ID_ARTICULO, $attrib); ?>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Codigo Barra</td>
        <td>
            <?php
            $attrib = "readonly ";
            $attrib .= "id='CODIGOBARRA_ARTICULO'";
            $attrib .= "size='15'";
            $attrib .= "class='readonly'";
            echo form_input('CODIGOBARRA_ARTICULO', $Articulo->CODIGOBARRA_ARTICULO, $attrib); ?>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Descripcion</td>
        <td>
            <?php
            $attrib = (!$new) ? "readonly " : " ";
            $attrib .= "id='DESCRIPCION_ARTICULO'";
            $attrib .= (!$new) ? "class='readonly'" : "";
            echo form_input('DESCRIPCION_ARTICULO', $Articulo->DESCRIPCION_ARTICULO, $attrib); ?>
        </td>
        <td>
            <div id="edit-DESCRIPCION_ARTICULO">Cambiar</div>
        </td>
    </tr>
    <tr>
        <td>Precio</td>
        <td>
            <?php
            $attrib = (!$new) ? "readonly " : " ";
            $attrib .= "id='PRECIOVTA_ARTICULO'";
            $attrib .= "size='5'";
            $attrib .= (!$new) ? "class='readonly'" : "";
            echo form_input('PRECIOVTA_ARTICULO', $Articulo->PRECIOVTA_ARTICULO, $attrib); ?>
        </td>
        <td>
            <div id="edit-PRECIOVTA_ARTICULO">Cambiar</div>
        </td>
    </tr>
    <tr>
        <td>I.V.A</td>
        <td>
            <div id="radio-iva">
                <?php echo form_label('21%', 'iva1'); ?><?php echo form_radio('TASAIVA_ARTICULO', 21, ($Articulo->TASAIVA_ARTICULO == 21) ? true : false, 'id="iva1"') ?>
                <?php echo form_label('10.5%', 'iva2'); ?><?php echo form_radio('TASAIVA_ARTICULO', 10.50, ($Articulo->TASAIVA_ARTICULO == 10.50) ? true : false, 'id="iva2"') ?>
                <?php echo form_label('OTRO', 'iva3'); ?><?php echo form_radio('TASAIVA_ARTICULO', 21, ($Articulo->TASAIVA_ARTICULO == 0) ? true : false, 'id="iva3"') ?>
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Subrubro ( Producto )</td>
        <td>
            <?php
            /*
            $attrib = (!$new)?"disabled ":"";
            $attrib .= "id='ID_SUBRUBRO'";
            echo form_dropdown('ID_SUBRUBRO', $subrubroSel, $Articulo->ID_SUBRUBRO,$attrib);*/

            $attrib = (!$new) ? "readonly " : " ";
            $attrib .= "id='ID_SUBRUBRO'";
            $attrib .= "size='5'";
            $attrib .= (!$new) ? "class='readonly'" : "";
            echo form_input('ID_SUBRUBRO', $Articulo->ID_SUBRUBRO, $attrib); ?>
            <span id="resultadoAjaxRubros" class="ui-state-default"><?php echo $nombreSubrubro ?></span>
        </td>
        <td>
            <div id="botRubro" class="boton">Cambiar</div>
        </td>
    </tr>
    <tr>
        <td>Marca</td>
        <td>
            <?php
            /*
            $attrib = (!$new)?"disabled ":"";
            $attrib .= "id='ID_MARCA'";
            echo form_dropdown('ID_MARCA', $marcaSel, $Articulo->ID_MARCA,$attrib);
            */
            $attrib = (!$new) ? "readonly " : " ";
            $attrib .= "id='ID_MARCA'";
            $attrib .= "size='5'";
            $attrib .= (!$new) ? "class='readonly'" : "";
            echo form_input('ID_MARCA', $Articulo->ID_MARCA, $attrib); ?>
            <span id="resultadoAjaxMarcas" class="ui-state-default"><?php echo $nombreSubmarca ?></span>
        </td>
        <td>
            <div id="botMarca" class="boton">Cambiar</div>
        </td>
    </tr>
    <tr>
        <td>Especific. ( Caract/Sabor )</td>
        <td>
            <?php
            $attrib = (!$new) ? "readonly " : "";
            $attrib .= "id='especificacion'";
            $attrib .= (!$new) ? "class='readonly'" : "";
            echo form_input('especificacion', $Articulo->especificacion, $attrib); ?>
        </td>
        <td>
            <div id="edit-especificacion">Cambiar</div>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>Cantidad ( Kg/gr/Lt/ml )</td>
        <td>
            <?php
            $attrib = (!$new) ? "readonly " : "";
            $attrib .= "id='medida'";
            $attrib .= (!$new) ? "class='readonly'" : "";
            echo form_input('medida', $Articulo->medida, $attrib); ?>
        </td>
        <td>
            <div id="edit-medida">Cambiar</div>
        </td>
        <td></td>
    </tr>
    <tr>
        <td>Detalle Asistente</td>
        <td>
            <?php
            $attrib = "readonly ";
            $attrib .= "id='detalle'";
            $attrib .= "class='readonly'";
            echo form_input('detalle', $Articulo->detalle, $attrib); ?>
        </td>
        <td>
            <div id="botDetalle" class="boton">Generar</div>
            <div id="botCopiar" class="boton">Copiar al Nombre</div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="radio-estado-articulo">
                <?php echo form_label('Activo ', 'estadoArticulo1'); ?><?php echo form_radio('ESTADO_ARTICULO', 1, ($Articulo->ESTADO_ARTICULO == 1) ? true : false, 'id="estadoArticulo1"') ?>
                <?php echo form_label('Suspendido ', 'estadoArticulo2'); ?><?php echo form_radio('ESTADO_ARTICULO', 0, ($Articulo->ESTADO_ARTICULO == 0) ? true : false, 'id="estadoArticulo2"') ?>
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Fecha de modificacion</td>
        <td><?php echo $Articulo->FECHAMODIF_ARTICULO ?></td>
    </tr>
    <tr>
        <td colspan="3"><?php echo anchor('articulos/index', 'Cancelar', 'class="boton"') ?>
            <div id="botSave" class="boton">Grabar</div>
        </td>
    </tr>
</table>
<?php echo form_close(); ?>

<div id="searchSubrubros"></div>
<div id="searchSubmarcas"></div>

<script>
    $(document).ready(function () {
        $("#botRubro").click(function () {
            buscoSubrubro();
        });
        $("#botMarca").click(function () {
            buscoSubmarca();
        });
    });
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
                resultado = $("#resultadoAjaxRubros >.codigo").html();
                $("#ID_SUBRUBRO").val(resultado);
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
                resultado = $("#resultadoAjaxMarcas >.codigo").html();
                $("#ID_MARCA").val(resultado);
            }
        };
        $('#searchSubmarcas').dialog(dialogOpts);
        $("#searchSubmarcas").load(<?php echo $urlBuscoAjaxMarca;?>, [], function () {
                $("#searchSubmarcas").dialog("open");
            }
        );
    }
</script>