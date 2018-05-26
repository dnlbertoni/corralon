<?php
/**
 * Created by PhpStorm.
 * User: sistemas
 * Date: 30/07/2016
 * Time: 15:23
 */ ?>
<script src="/assets/js/jquery-3.1.0.min.js"></script>
<div class="row">
    <?php echo form_open ( 'cuenta/consultaJson', 'id="consultaCuenta"' ) ?>
    <label>Busqueda:</label>
    <?php echo form_input ( 'nombreCuentaTXT', '', 'id="nombreCuentaTXT" class="form-control" ' ) ?>
    <!-- <?php echo form_button ( 'Consultar', 'Consultar' ); ?> -->
    <?php echo form_close () ?>
    <div class="col-md-12">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>CUIT</th>
                <th>Letra</th>
                <th>Relacion</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody class="table-content" id="datosCuentas">

            </tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#consultaCuenta").submit(function (e) {
            e.preventDefault();
        });
        $("#nombreCuentaTXT").focus();
        $("#nombreCuentaTXT").bind('keyup', function (e) {
            var code = e.keyCode;
            valor = $("#nombreCuentaTXT").val().toUpperCase();
            $("#nombreCuentaTXT").val(valor);
            if (( code < 90 && code > 57 ) || code === 13 || code === 8) {
                envioForm();
            }
            ;
        });
    });
    function envioForm() {
        cuenta = $("#nombreCuentaTXT").val().trim();
        pagina = $("#consultaCuenta").attr('action');
        if (cuenta.length > -1) {
            $.post({
                url: pagina,
                contentType: "application/x-www-form-urlencoded",
                global: false,
                type: "POST",
                data: ({
                    valor: cuenta
                }),
                dataType: "json",
                async: true,
                success: function (data) {
                    muestroCuentas(data);
                }
            });
        }else{
            alert('muchos clientes');
        }
    }
    function muestroCuentas(data) {
        $("#datosCuentas").html('');
        $.each(data.cuentas, function (key, cuenta) {
            switch (cuenta.tipo) {
                case "1":
                    relacion = "<span class='label label-primary'>Cliente</span>";
                    break;
                case "2":
                    relacion = "<span class='label label-info'>Proveedor</span>";
                    break;
                default:
                    relacion = "<span class='label label-success'>Ambos</span>";
                    break;
            }
            linea = "<tr>";
            linea += "<td>" + cuenta.id + "</td>";
            linea += "<td>" + cuenta.nombre + "</td>";
            linea += "<td>" + cuenta.cuit + "</td>";
            linea += "<td>" + cuenta.letra + "</td>";
            linea += "<td>" + relacion + "</td>";
            linea += "<td><div role='button' class='btn btn-success btn-xs btn-agregarCuenta' id='btn_" + cuenta.id + "'><span class='fa fa-user'></span></div></td>";
            linea += "</tr>";
            $("#datosCuentas").append(linea);
        })
        $(".btn-agregarCuenta").click(function () {
            aux = $(this).attr('id');
            aux = aux.split('_');
            id = aux[1];
            cuenta_id = id;
            busquedaCuenta.close();
        });
    }
</script>