<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Busco Cliente</h4>
        </div>
        <div class="modal-body">
            <?php echo form_open('cuenta/searchCuentaXDo', 'id="consultaCuenta"') ?>
            <?php echo form_input('cuentaTXT', '', 'id="cuentaTXT"') ?>
            <input type="hidden" id="filtro" value="<?php echo $filtro ?>"/>
            <?php echo form_submit('Consultar', 'Consultar'); ?>
            <?php echo form_close() ?>
            <div id="datosCliente">
                <table class="table" id="datosClientes">
                    <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>CUIT</th>
                        <th>Cond. Vta</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div>

</div>
<script>
    $(document).ready(function () {
        nombreDIV = "#" + $('.modal-dialog').parent().attr('id');
        $(nombreDIV).on('shown.bs.modal', function () {
            $("#cuentaTXT").focus();
        });
        $(nombreDIV).on('hide.bs.modal', function () {
            $("#cuentaTXT").val('');
            $("#datosClientes > tbody").html('');
        });
        $("#datosClientes .btn").click(function () {
            alert('hola');
        });
        $("#cuentaTXT").bind('keyup', function (e) {
            var code = e.keyCode;
            if (( code < 90 && code > 57 ) || code === 13 || code === 8) {
                envioForm();
            }
            ;
        });
        $("#consultaCuenta").submit(function (e) {
            e.preventDefault();
            envioForm();
        });
    });
    function envioForm() {
        cuenta = $("#cuentaTXT").val().trim();
        filtro = $("#filtro").val();
        pagina = $("#consultaCuenta").attr('action');
        if (cuenta.length > 0) {
            $.ajax({
                url: pagina,
                contentType: "application/x-www-form-urlencoded",
                global: false,
                type: "POST",
                data: ({
                    cuentaTXT: cuenta,
                    filtro: filtro
                }),
                dataType: "json",
                async: true,
                success: function (msg) {
                    muestroClientes(msg.cuentas);
                }
            }).responseText;
        }
    }
    function muestroClientes(data) {
        $("#datosClientes > tbody").html('');
        $.each(data, function (key, cuenta) {
            linea = "<tr><td>" + cuenta.id + "</td>";
            linea += "<td>" + cuenta.nombre + "</td>";
            linea += "<td>" + cuenta.cuit + "</td>";
            if (cuenta.ctacte == 1) {
                linea += "<td><div role='button' class='btn btn-success' id='btn_" + cuenta.id + "'><span class='fa fa-check-circle-o'> Cta. Cte.</span></div></td>";
            } else {
                linea += "<td><div role='button' class='btn btn-danger' id='btn_" + cuenta.id + "'><span class='fa fa-check-circle-o'> Contado</span></div></td>";
            }
            linea += "</tr>";
            $("#datosClientes > tbody").append(linea);
        })
    }
</script>