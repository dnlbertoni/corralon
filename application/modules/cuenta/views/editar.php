<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
            <h3 class="modal-title"><?php echo ( isset( $ocultos ) ) ? 'Cuenta Nro: ' . $ocultos['id'] : 'Crear Cuenta' ?></h3>
        </div>
        <div class="modal-body">
            <?php echo form_open ( $accion, array ( 'id' => 'cuentaAdd', 'name' => 'cuentaAdd' ), ( isset( $ocultos ) ) ? $ocultos : 'class="form"' ); ?>
            <div class="col-md-12">
                Razon Social:
                <?php
                $datainput = array ( 'id' => 'nombre',
                    'name' => 'nombre',
                    'size' => 50,
                    'value' => $cuenta->nombre,
                    'class' => 'form-control');
                echo form_input ( $datainput ); ?>
            </div>
            <div class="col-md-12">
                Datosde Facturacion
                <div id="datos_fac">
                    <?php echo form_label ( 'Mismo Nombre', 'datos_fac1' ); ?>
                    <?php echo form_radio ( 'datos_fac', '0', ( $cuenta->datos_fac == 0 ) ? true : false, 'id="datos_fac1"' ) ?>
                    <?php echo form_label ( 'Otros datos', 'datos_fac2' ); ?>
                    <?php echo form_radio ( 'datos_fac', '1', ( $cuenta->datos_fac == 1 ) ? true : false, 'id="datos_fac2"' ) ?>
                </div>
                <div id="verDatos">
                    <?php
                    $datainput = array ( 'id' => 'nombre_facturacion',
                        'name' => 'nombre_facturacion',
                        'size' => 50,
                        'value' => $cuenta->nombre_facturacion,
                        'class' => 'form-control'
                    );
                    echo form_input ( $datainput ); ?>
                </div>
            </div>
            <div id="datos">Condicion de IVA:
                <?php
                echo form_dropdown ( 'condiva_id', $condiva, $cuenta->condiva_id, 'id="condiva_id"' ); ?>
            </div>
            <div id="datos">Tipo de Documento:
                <?php
                echo form_dropdown ( 'tipdoc', array ( '1' => 'DNI', '2' => 'CUIT' ), $cuenta->tipdoc, 'id="tipdoc"' ); ?>
            </div>
            <div id="datos">CUIT/DNI:
                <?php
                $datainput = array ( 'id' => 'cuit',
                    'name' => 'cuit',
                    'value' => $cuenta->cuit );
                echo form_input ( $datainput ); ?>
                <button type="button" id="bot_CuitOK" class="btn btn-xs btn-warning">Comprobar Cuit</button>
            </div>
            <div id="datos">Direccion:
                <?php
                $datainput = array ( 'id' => 'direccion',
                    'name' => 'direccion',
                    'value' => $cuenta->direccion );
                echo form_input ( $datainput ); ?>
            </div>
            <div id="datos">Telefono:
                <?php
                $datainput = array ( 'id' => 'telefono',
                    'name' => 'telefono',
                    'value' => $cuenta->telefono );
                echo form_input ( $datainput ); ?>
            </div>
            <div id="datos">Celular:
                <?php
                $datainput = array ( 'id' => 'celular',
                    'name' => 'celular',
                    'value' => $cuenta->celular );
                echo form_input ( $datainput ); ?>
            </div>
            <div id="datos">E-mail:
                <?php
                $datainput = array ( 'id' => 'email',
                    'name' => 'email',
                    'value' => $cuenta->email );
                echo form_input ( $datainput ); ?>
            </div>
            <div id="ctacte">
                <?php echo form_label ( 'Contado', 'ctacte1' ); ?><?php echo form_radio ( 'ctacte', '0', ( $cuenta->ctacte == 0 ) ? true : false, 'id="ctacte1"' ) ?>
                <?php echo form_label ( 'Cta. Cte.', 'ctacte2' ); ?><?php echo form_radio ( 'ctacte', '1', ( $cuenta->ctacte == 1 ) ? true : false, 'id="ctacte2"' ) ?>
            </div>
            <div id="tipo">
                <?php echo form_label ( 'Cliente ', 'tipo1' ); ?><?php echo form_radio ( 'tipo', '1', ( $cuenta->tipo == 1 ) ? true : false, 'id="tipo1"' ) ?>
                <?php echo form_label ( 'Proveedor ', 'tipo2' ); ?><?php echo form_radio ( 'tipo', '2', ( $cuenta->tipo == 2 ) ? true : false, 'id="tipo2"' ) ?>
            </div>
            <div id="datos">Letra: <span id="valorLetra"><?php echo $cuenta->letra ?></span>
                <input type="hidden" id="letra" name="letra" value="<?php echo $cuenta->letra ?>"/>
                <input type="hidden" id="estado" name="estado" value="<?php echo $cuenta->estado ?>"/>
            </div>
            <?php echo form_close (); ?>
            <?php if ( isset( $ocultos ) ): ?>
                <div id="info">
                    Cantidad Comprobantes <?php echo $totComp ?>
                    <br/>
                    Importe Facturado <?php echo $totFact ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancelar
            </button>
            <button type="button" id="botAccion" class="btn btn-success" data-dismiss="modal"><i class="fa fa-save"></i>
                Guardar
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
//controlo cuit
        valor = $("#cuit").val();
        estado = ControloCuit(valor);
        ValidoCuit(estado);
        //
        var pos = $("#condiva_id").offset();
        $("#cuentaAdd input").offset({left: pos.left});
        var otrodato = parseInt($("#datos_fac :checked").val());
        if (otrodato === 1) {
            $("#verDatos").show();
        } else {
            $("#verDatos").hide();
        }
        $("#datos_fac").change(function () {
            $("#verDatos").toggle();
        });
        $("#tipdoc").change(function () {
            cambioLetra();
            valor = $("#cuit").val();
            estado = ControloCuit(valor);
            ValidoCuit(estado);
        });
        $("#condiva_id").change(function () {
            cambioLetra();
        });
        $("#tipo").change(function () {
            cambioLetra();
        });
        $("#botAccion").click(function () {
            $("form").submit();
        });
        $("#bot_CuitOK").click(function () {
            valor = $("#cuit").val();
            estado = ControloCuit(valor);
            ValidoCuit(estado);
        });
    });

    function cambioLetra() {
        var tipo = (typeof $("#tipo1:checked").val() == "undefined") ? 2 : 1;
        var condi = $("#condiva_id").val();
        var tipdoc = $("#tipdoc").val();
        switch (tipo) {
            case 1:
                if (condi == '1') {
                    $("#letra").val('A');
                    $("#valorLetra").html('A');
                } else {
                    $("#letra").val('B');
                    $("#valorLetra").html('B');
                }
                ;
                break;
            case 2:
                switch (condi) {
                    case '1':
                        $("#letra").val('A');
                        $("#valorLetra").html('A');
                        break;
                    case '2':
                        $("#letra").val('B');
                        $("#valorLetra").html('B');
                        break;
                    case '3':
                        $("#letra").val('C');
                        $("#valorLetra").html('C');
                        break;
                    case '4':
                        $("#letra").val('C');
                        $("#valorLetra").html('C');
                        break;
                    case '5':
                        $("#letra").val('ERROR');
                        $("#valorLetra").html('ERROR');
                        break;
                    case '6':
                        $("#letra").val('ERROR');
                        $("#valorLetra").html('ERROR');
                        break;
                }
                ;
                break;
        }
        ;
    }
    function ControloCuit(cuit) {
        if (typeof (cuit) == 'undefined')
            return true;
        cuit = cuit.toString().replace(/[-_]/g, "");
        if (cuit == '')
            return true; //No estamos validando si el campo esta vacio, eso queda para el "required"
        if (cuit.length != 11)
            return false;
        else {
            var mult = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
            var total = 0;
            for (var i = 0; i < mult.length; i++) {
                total += parseInt(cuit[i]) * mult[i];
            }
            var mod = total % 11;
            var digito = mod == 0 ? 0 : mod == 1 ? 9 : 11 - mod;
        }
        return digito == parseInt(cuit[10]);
    }
    function ValidoCuit(estado) {
        tipo = $("#tipdoc").val();
        clase = $("#cuit").attr('class');
        $("#cuit").removeClass(clase);
        if (estado) {
            clase = 'success';
        } else {
            if (tipo == '1') {
                clase = 'success';
            } else {
                clase = 'danger';
            }
            ;
        }
        ;
        $("#cuit").addClass(clase);
    }
</script>