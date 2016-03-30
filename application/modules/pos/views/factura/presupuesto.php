<div style="float: right; width: 60%">
    <span id="F1">F1 - Cancela</span>
    <span id="F6">F6 - Cliente</span>
    <span id="F8">F8 - Ctacte</span>
    <span id="F10">F10 - Vale</span>
    <span id="F12">F12 - Impresion</span>
</div>
<div style="float:left;">
    <?php echo form_open('factura/addcart', 'id="addCart"'); ?>
    <input type="hidden" name="pagina" value="<?php echo base_url(), 'index.php/pos/factura/addArticulo' ?>"
           id="pagina"/>
    <input type="hidden" name="puesto" value="<?php echo $puesto ?>" id="puesto"/>
    <input type="hidden" name="cuenta" value="<?php echo $idCuenta ?>" id="cuenta"/>
    <input type="hidden" name="tipcom_id" value="<?php echo $tipcom_id ?>" id="tipcom_id"/>
    <input type="hidden" name="id_tmpencab" value="<?php echo $id_tmpencab ?>" id="id_tmpencab"/>
    <input type="hidden" name="condVtaId" value="<?php echo $condVtaId ?>" id="condVtaId"/>
    <input type="hidden" id="paginaPrecio" value="<?php echo base_url(), 'index.php/articulos/precioAjax' ?>"/>
    <input type="hidden" id="paginaBorroArticulo"
           value="<?php echo base_url(), 'index.php/pos/factura/delArticulo' ?>"/>
    <input type="hidden" id="paginaCliente" value="<?php echo base_url(), 'index.php/cuenta/searchCuentaX/1' ?>"/>
    <input type="hidden" id="paginaCancelo" value="<?php echo base_url(), 'index.php/pos/factura/cancelo' ?>"/>
    <input type="hidden" id="paginaTicket"
           value="<?php echo base_url(), 'index.php/pos/factura/printTicket/', $puesto, '/', $id_tmpencab ?>"/>
    <input type="hidden" id="paginaIndex" value="<?php echo base_url(), 'index.php/pos/factura/presupuesto' ?>"/>
    <div>
        Articulo <?php echo form_input('codigobarra', '', 'id="codigobarra"'); ?>
    </div>
    <div style="font-size: 8px;">(cant)*(articulo) | (cant)*(precio)*(articulo)</div>
    <?php //echo form_submit('Agregar','Agregar');?>
    <?php echo form_close(); ?>
</div>
<div class="clear"></div>
<div id="loading"><?php echo Assets::image('loading.gif', array('alt' => "Loading...")); ?></div>

<div id="brief">

</div>
<div id="cliente"></div>
<div id="cuentaAjax"></div>
<div id="precio"></div>
<div id="imprimo"></div>

<script>
    $(document).ready(function () {
        $("#loading").css('text-align', 'center');
        $("#loading").hide();
        $("#cuentaAjax").hide();
        $("#codigobarra").addClass('focus');
        $("#codigobarra").focus();
        ;
        //chequeo las teclas de funciones
        $(document).bind('keydown', function (e) {
            var code = e.keyCode;
            key = getSpecialKey(code);
            if (key) {
                e.preventDefault();
                switch (key) {
                    case 'f1':
                        CanceloComprobante();
                        break;
                    case 'f3':
                        $("#codigobarra").addClass('focus');
                        $("#codigobarra").focus();
                        break;
                    case 'f6':
                        CambioCliente();
                        break;
                    case 'f8':
                        CambioCondicion();
                        break;
                    case 'f9':
                        ImprimoDescuento();
                        break;
                    case 'f10':
                        ImprimoRemito();
                        break;
                    case 'f12':
                        ImprimoTicket();
                        break;
                }
            }
            ;
        });
        $("#codigobarra").bind('keydown', function (e) {
            var code = e.keyCode;
            if ($("#codigobarra").hasClass('focus')) {
                if ($("#codigobarra").val().trim().length === 0) {
                    if (code === 13) {
                        ConsultoPrecio(e);
                    }
                    ;
                } else {
                    if (code === 13) {
                        AgregoArticulo(e);
                    }
                }
            }
            ;
        });
        // fin de chequeo de teclas de funciones
        //inicio de envio de datos al comprobante
        $("#addCart").submit(function (e) {
            AgregoArticulo(e);
        });
        //fin de envio de datos al comprobante
        //activo botones
        $("#F1").button();
        $("#F1").click(function () {
            CanceloComprobante();
        });
        $("#F6").button();
        $("#F6").click(function () {
            CambioCliente();
        });
        $("#F8").button();
        $("#F8").click(function () {
            CambioCondicion();
        });
        $("#F10").button();
        $("#F10").click(function () {
            ImprimoRemito();
        });
        $("#F12").button();
        $("#F12").click(function () {
            ImprimoTicket();
        });
        MuestroArticulos();
    });

    function AgregoArticulo(e) {
        e.preventDefault();
        codigobarra = $("#codigobarra").val();
        puesto = $("#puesto").val();
        id_temporal = $("#id_tmpencab").val();
        cuenta = $("#cuenta").val();
        pagina = $("#pagina").val();
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                codigobarra: codigobarra,
                cantidad: 1,
                puesto: puesto,
                cuenta: cuenta,
                id_tmpencab: id_temporal
            }),
            dataType: "html",
            async: true,
            beforeSend: function () {
                $("#loading").fadeIn();
            },
            success: function (msg) {
                $("#brief").html(msg);
                $("#codigobarra").addClass('focus');
                $("#codigobarra").val('');
                $("#codigobarra").focus();
                $("#loading").fadeOut(100);
            }
        }).responseText;
    }
    function MuestroArticulos() {
        codigobarra = '';
        puesto = $("#puesto").val();
        id_temporal = $("#id_tmpencab").val();
        cuenta = $("#cuenta").val();
        pagina = $("#pagina").val();
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                codigobarra: codigobarra,
                cantidad: 1,
                puesto: puesto,
                cuenta: cuenta,
                id_tmpencab: id_temporal
            }),
            dataType: "html",
            async: true,
            beforeSend: function () {
                $("#loading").fadeIn();
            },
            success: function (msg) {
                $("#brief").html(msg);
                $("#codigobarra").addClass('focus');
                $("#codigobarra").val('');
                $("#codigobarra").focus();
                $("#loading").fadeOut(100);
            }
        }).responseText;
    }
    function ConsultoPrecio(e) {
        e.preventDefault();
        $("#codigobarra").removeClass('focus');
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Consulta de Precios",
            draggable: true,
            resizeable: true,
            close: function () {
//          $('#precio').dialog("destroy");
                $("#codigobarra").addClass('focus');
                $("#codigobarra").val('');
                $("#codigobarra").focus();
            }
        };
        $("#precio").dialog(dialogOpts);   //end dialog
        $("#precio").load($("#paginaPrecio").val(), [], function () {
                $("#precio").dialog("open");
            }
        );
    }
    function CanceloComprobante() {
        $("#cuenta").val(1);
        puesto = $("#puesto").val();
        id_temporal = $("#id_tmpencab").val();
        pagina = $("#paginaCancelo").val();
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                puesto: puesto,
                id_tmpencab: id_temporal
            }),
            dataType: "html",
            async: true,
            beforeSend: function () {
                $("#loading").fadeIn();
            },
            success: function (msg) {
                $("#brief").html(msg);
                $("#codigobarra").val('');
                $("#codigobarra").focus();
                $("#loading").fadeOut(200);
            }
        }).responseText;
    }
    function CambioCliente() {
        $("#codigobarra").removeClass('focus');
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            hide: "explode",
            height: 300,
            width: 500,
            title: "Consulta de Clientes",
            draggable: true,
            resizeable: true,
            close: function () {
                valor = $("#cuentaAjax > .codigo").html();
                nombre = $("#cuentaAjax > .nombre").html();
                ctacte = $("#cuentaAjax > .ctacte").html();
                ctacteId = (ctacte === "CtaCte" ) ? 1 : 0;
                tipcom = $("#cuentaAjax > .tipcom").html();
                $("#idCuenta").html(valor);
                $("#cuentaAjax").html(valor);
                $("#cuenta").val(valor);
                $("#nombreCuenta").html(nombre);
                $("#condVta").html(ctacte);
                $("#condVtaId").val(ctacteId);
                $("#tipcom_id").val(tipcom);
                $("#tipcom_nom").html("Factura");
                claseCondVta = (ctacte == "CtaCte") ? "ui-state-error" : "ui-state-default";
                $("#condVta").removeAttr('class');
                $("#condVta").addClass(claseCondVta);

                $("#cliente").dialog('destroy');
                $("#codigobarra").addClass('focus');
                $("#codigobarra").val('');
                $("#codigobarra").focus();
            }
        };
        $("#cliente").dialog(dialogOpts);   //end dialog
        $("#cliente").load($("#paginaCliente").val(), [], function () {
                $("#cliente").dialog("moveToTop");
                $("#cliente").dialog("open");
            }
        );
    }
    function CambioCondicion() {
        valor = $("#condVta").html();
        if (valor == "Contado") {
            $("#condVta").html('CtaCte');
            $("#condVta").removeClass('ui-state-default');
            $("#condVta").addClass('ui-state-error');
            $("#condVtaId").val(1);
        } else {
            $("#condVta").html('Contado');
            $("#condVta").removeClass('ui-state-error');
            $("#condVta").addClass('ui-state-default');
            $("#condVtaId").val(0);
        }
    }
    function ImprimoRemito() {
        tipo = parseInt($("#tipcom_id").val());
        if (tipo == 6) {
            $("#tipcom_nom").removeClass('ui-state-default');
            if ($("#idCuenta").val() == 1) {
                $("#tipcom_id").val('1');
                $("#tipcom_nom").html('Ticket');
            } else {
                $("#tipcom_id").val('2');
                $("#tipcom_nom").html('Factura');
            }
        } else {
            $("#tipcom_id").val('6');
            $("#tipcom_nom").html('Remito');
            $("#tipcom_nom").addClass('ui-state-default');
        }
    }
    function ImprimoDescuento() {
        tipo = parseInt($("#tipcom_id").val());
        if (tipo == 9) {
            $("#tipcom_nom").removeClass('ui-state-default');
            if ($("#idCuenta").val() == 1) {
                $("#tipcom_id").val('1');
                $("#tipcom_nom").html('Ticket');
            } else {
                $("#tipcom_id").val('2');
                $("#tipcom_nom").html('Factura');
            }
        } else {
            $("#tipcom_id").val('9');
            $("#tipcom_nom").html('Descuento CTACTE');
            $("#tipcom_nom").addClass('ui-state-default');
        }
    }
    function ImprimoTicket() {
        var url = $("#paginaTicket").val() + '/' + $("#tipcom_id").val() + '/' + $("#condVtaId").val();
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            hide: "explode",
            open: function () {
                $("#carga").fadeIn();
            },
            height: 200,
            width: 300,
            title: "Imprimo Comprobante",
            draggable: true,
            resizeable: true,
            close: function () {
                CanceloComprobante();
                window.location = $("#paginaIndex").val();
            }
        };
        $("#imprimo").dialog(dialogOpts);   //end dialog
        $("#imprimo").load(url, [], function () {
            $("#imprimo").dialog("moveToTop");
            $("#imprimo").dialog("open");
        });
    }
    function getSpecialKey(code) {
        if (code > 111 && code < 124) {
            aux = code - 111;
            return 'f' + aux;
        } else {
            return false;
        }
    }
</script>