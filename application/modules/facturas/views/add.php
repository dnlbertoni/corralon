<div id="buscoCuenta"></div>
<h1 class="<?= $claseTitulo ?>"><?php echo $tipcom_nombre ?></h1>
<?php $attrib = array('id' => 'addFac'); ?>
<?php echo form_open('facturas/addDo', $attrib, $ocultos); ?>
<table>
    <tr>
        <td>Cuenta</td>
        <td>
            <?php
            $datinput = array(
                'id' => 'cuenta_id',
                'name' => 'cuenta_id',
                'value' => $cuenta_id,
                'size' => 5
            );
            echo form_input($datinput) ?> |
            <?php
            $datinput = array(
                'id' => 'cuenta_nombre',
                'name' => 'cuenta_nombre',
                'value' => $cuenta_nombre
            );
            echo form_input($datinput) ?>
            <div id="botBuscaCuenta">Buscar</div>
            <div id="cuentaAjax"></div>
        </td>
    </tr>
    <tr>
        <td> Comprobante:</td>
        <td>
            <?php
            $datinput = array(
                'id' => 'letra',
                'name' => 'letra',
                'size' => 1
            );
            echo form_input($datinput) ?> |
            <?php
            $datinput = array(
                'id' => 'puesto',
                'name' => 'puesto',
                'size' => 5
            );
            echo form_input($datinput); ?> -
            <?php
            $datinput = array(
                'id' => 'numero',
                'name' => 'numero',
                'size' => 8
            );
            echo form_input($datinput); ?>
        </td>
    </tr>
    <tr>
        <td>Fecha</td>
        <td>
            <?php
            $datinput = array(
                'id' => 'fecha',
                'name' => 'fecha'
            );
            echo form_input($datinput); ?>
        </td>
    </tr>

    <tr>
        <td>Importe</td>
        <td><?php
            $datinput = array('id' => 'importe',
                'name' => 'importe'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>Neto</td>
        <td><?php
            $datinput = array('id' => 'neto',
                'name' => 'neto'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>IVA 21%</td>
        <td><?php
            $datinput = array('id' => 'ivamax',
                'name' => 'ivamax'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>IVA 10,5%</td>
        <td><?php
            $datinput = array('id' => 'ivamin',
                'name' => 'ivamin'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>I. Brutos</td>
        <td><?php
            $datinput = array('id' => 'ingbru',
                'name' => 'ingbru'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>Imp. Internos</td>
        <td><?php
            $datinput = array('id' => 'impint',
                'name' => 'impint'
            );
            echo form_input($datinput); ?></td>

    </tr>
    <tr>
        <td>Percep. 5%</td>
        <td><?php
            $datinput = array('id' => 'percep',
                'name' => 'percep'
            );
            echo form_input($datinput); ?></td>

    </tr>
    <tr>
        <td colspan="2"> <?php echo form_submit('Grabar', 'Grabar'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        buscaCuenta();
        $("#botBuscaCuenta").button({icons: {primary: 'ui-icon-search'}});
        $("#botBuscaCuenta").click(function () {
            buscaCuenta();
        });
        $.datepicker.regional["es"];
        $("#fecha").datepicker({
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            dateFormat: "yy-mm-dd",
            numberOfMonths: 3,
            showButtonPanel: true,
            defaultDate: "-90d",
            onClose: function () {
                $("#importe").focus();
            },
            changeMonth: true
        });
        /*
         $('#addFac').submit(function(evnt){
         //evnt.preventDefault();
         var importe = Decimal($("#importe").val());
         var neto    = Decimal($("#neto").val());
         var ivamin  = Decimal($("#ivamin").val());
         var ivamax  = Decimal($("#ivamax").val());
         var ingbru  = Decimal($("#ingbru").val());
         var impint  = Decimal($("#impint").val());
         var percep  = Decimal($("#percep").val());
         alert("entra");
         if(!(importe ==  neto + ivamin + ivamax + ingbru + impint + percep  ) ){
         return false;
         alert(neto+ivamin+ivamax+ingbru+impint+percep);
         }else{
         return true;
         };
         });
         */
        $('#neto').bind('blur', function () {
            total = parseFloat($('#importe').val());
            neto = parseFloat($('#net').val());
            iva21 = neto * 21 / 100;
            if (total === neto + iva21) {
                $('#ivamax').val(iva21);
            }
            ;
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
    function Decimal(valor) {
        numero = parseFloat(valor);
        numero = (isNaN(numero)) ? 0 : numero;
        numero = Math.round(numero * 100) / 100;
        return numero;
    }
</script>