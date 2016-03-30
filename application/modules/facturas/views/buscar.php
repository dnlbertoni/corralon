<?php echo form_open($accion) ?>
<?php echo form_label('Proveedor'); ?>
<?php
$datinput = array(
    'id' => 'cuenta_id',
    'name' => 'cuenta_id',
    'value' => '',
    'size' => 5
);
echo form_input($datinput) ?> |
<?php
$datinput = array(
    'id' => 'cuenta_nombre',
    'name' => 'cuenta_nombre',
    'value' => ''
);
echo form_input($datinput) ?>
<div id="botBuscaCuenta">Buscar</div>
<div id="cuentaAjax"></div>
<div id="buscoCuenta"></div>
<br/>
<?php echo form_label('Desde'); ?>
<?php echo form_input('desde', '', 'class="date"'); ?>
<br/>
<?php echo form_label('Hasta'); ?>
<?php echo form_input('hasta', '', 'class="date"'); ?>
<br/>
<?php echo form_submit('find', 'Buscar') ?>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#botBuscaCuenta").button({icons: {primary: 'ui-icon-search'}});
        $("#botBuscaCuenta").click(function () {
            buscaCuenta();
        });
        $.datepicker.regional["es"];
        $(".date").datepicker({
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            onClose: function () {
                $("#importe").focus();
            }
        });
    });
    function buscaCuenta() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Busco Cuenta",
            draggable: true,
            resizeable: true,
            close: function () {
                valor = $("#cuentaAjax > .codigo").html();
                nombre = $("#cuentaAjax > .nombre").html();
                letra = $("#cuentaAjax > .letra").html();
                $("#cuenta_id").val(valor);
                $("#cuenta_nombre").val(nombre);
                $("#letra").val(letra);
                $('#buscoCuenta').dialog("destroy");
                $("#puesto").focus();
            }
        };
        $("#buscoCuenta").dialog(dialogOpts);   //end dialog
        $("#buscoCuenta").load(<?php echo $targetCuenta;?>, [], function () {
                $("#buscoCuenta").dialog("open");
            }
        );
    }
</script>