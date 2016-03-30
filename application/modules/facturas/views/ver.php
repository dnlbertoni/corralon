<div class="post">
    <h1 class="<?= $claseTitulo ?>"><?php echo $tipcom_nombre ?></h1>
    <?php $attrib = array('id' => 'fact'); ?>
    <?php echo form_open($accion, $attrib, $ocultos); ?>
    <table>
        <tr>
            <td>Cuenta</td>
            <td>
                <?php
                $datinput = array(
                    'id' => 'cuenta_id',
                    'name' => 'cuenta_id',
                    'value' => $factura->cuenta_id,
                    'size' => 5
                );
                echo form_input($datinput) ?> |
                <?php
                $datinput = array(
                    'id' => 'cuenta_nombre',
                    'name' => 'cuenta_nombre',
                    'value' => $factura->cuenta_nombre
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
                    'value' => $factura->letra,
                    'size' => 1
                );
                echo form_input($datinput) ?> |
                <?php
                $datinput = array(
                    'id' => 'puesto',
                    'name' => 'puesto',
                    'value' => $factura->puesto,
                    'size' => 5
                );
                echo form_input($datinput); ?> -
                <?php
                $datinput = array(
                    'id' => 'numero',
                    'name' => 'numero',
                    'value' => $factura->numero,
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
                    'value' => $factura->fecha,
                    'name' => 'fecha'
                );
                echo form_input($datinput); ?>
            </td>
        </tr>

        <tr>
            <td>Importe</td>
            <td><?php
                $datinput = array('id' => 'importe',
                    'value' => $factura->importe,
                    'name' => 'importe'
                );
                echo form_input($datinput); ?></td>
        </tr>
        <tr>
            <td>Neto</td>
            <td><?php
                $datinput = array('id' => 'neto',
                    'value' => $factura->neto,
                    'name' => 'neto'
                );
                echo form_input($datinput); ?></td>
        </tr>
        <tr>
            <td>IVA 21%</td>
            <td><?php
                $datinput = array('id' => 'ivamax',
                    'value' => $factura->ivamax,
                    'name' => 'ivamax'
                );
                echo form_input($datinput); ?></td>
        </tr>
        <tr>
            <td>IVA 10,5%</td>
            <td><?php
                $datinput = array('id' => 'ivamin',
                    'value' => $factura->ivamin,
                    'name' => 'ivamin'
                );
                echo form_input($datinput); ?></td>
        </tr>
        <tr>
            <td>I. Brutos</td>
            <td><?php
                $datinput = array('id' => 'ingbru',
                    'value' => $factura->ingbru,
                    'name' => 'ingbru'
                );
                echo form_input($datinput); ?></td>
        </tr>
        <tr>
            <td>Imp. Internos</td>
            <td><?php
                $datinput = array('id' => 'impint',
                    'value' => $factura->impint,
                    'name' => 'impint'
                );
                echo form_input($datinput); ?></td>

        </tr>
        <tr>
            <td>Percep. 5%</td>
            <td><?php
                $datinput = array('id' => 'percep',
                    'value' => $factura->percep,
                    'name' => 'percep'
                );
                echo form_input($datinput); ?></td>

        </tr>
        <tr>
            <td colspan="2"> <?php echo form_submit('Grabar', 'Grabar'); ?></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>
<div id="buscoCuenta"></div>

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
        $('#addFac').submit(function (evnt) {
            evnt.preventDefault();
            var importe = Decimal($("#importe").val());
            var neto = Decimal($("#neto").val());
            var ivamin = Decimal($("#ivamin").val());
            var ivamax = Decimal($("#ivamax").val());
            var ingbru = Decimal($("#ingbru").val());
            var impint = Decimal($("#impint").val());
            var percep = Decmial($("#percep").val());
            if (importe === ( neto + ivamin + ivamax + ingbru + impint + percep )) {
                next;
            } else {
                return false;
                alert(neto + ivamin + ivamax + ingbru + impint + percep);
            }
        });
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