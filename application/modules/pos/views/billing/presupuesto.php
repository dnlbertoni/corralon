<div class="row ">
    <div class="panel panel-primary">
        <div class="panel-body">
            <div class="col-lg-4 col-md-4 col-xs-4">
                <!--
            <div class="alert alert-info"><?php echo $fechoy ?> <span id="clock"></span></div>
            -->
                <?php echo form_open('pos/billing/addArticulo', 'id="addCart"'); ?>
                <input type="hidden" name="tmpfacencab_id" value="<?php echo $tmpfacencab_id ?>" id="tmpfacencab_id"/>

                <div class="col-xs-4">
                    <?php echo form_label('Articulo', 'codigobarra', ' class="control-label"'); ?>
                </div>
                <div class="col-xs-8 right">
                    <?php echo form_input('codigobarra', '', 'id="codigobarra" data-toggle="tooltip" data-placement="top" title="articulo | (cant)*(articulo) | (cant)*(precio)*(articulo)" class="form-control"'); ?>
                </div>
                <?php echo form_close(); ?>
                <input type="hidden" id="paginaPrecio"
                       value="<?php echo base_url(), 'index.php/articulos/precioAjax' ?>"/>
                <input type="hidden" id="paginaCliente"
                       value="<?php echo base_url(), 'index.php/cuenta/searchCuentaX/1' ?>"/>
                <input type="hidden" id="paginaCancelo"
                       value="<?php echo base_url(), 'index.php/pos/billing/cancelo' ?>"/>
                <input type="hidden" id="paginaTicket"
                       value="<?php echo base_url(), 'index.php/pos/billing/printTicket/', $tmpfacencab_id ?>"/>
                <input type="hidden" id="paginaIndex"
                       value="<?php echo base_url(), 'index.php/pos/billing/presupuesto' ?>"/>
            </div>
            <div class="col-lg-8 col-md-8 col-xs-8">
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group">
                        <button class="btn btn-danger" id="F1"><span class="badge pull-left"> F1 </span>&nbsp;Cancelar
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-info" id="F6"><span class="badge pull-left"> F6 </span>&nbsp;Cliente
                        </button>
                        <!--  <button class="btn btn-info" id="F8"><span class="badge pull-left"> F8 -- sacar </span>&nbsp;Forma Pago</button> -->
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-success" id="F10"><span class="badge pull-left"> F10 </span>&nbsp;Vale
                        </button>
                        <?php echo anchor('pos/billing/emitoComprobante', '<span class="badge pull-left"> F12 </span>&nbsp;Impresion', 'role="button" class="btn btn-success" id="F12"') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.row -->
<div class="row"><!-- fila de comprobante -->
    <div class="col-lg-2 col-md-2 col-xs-2">
        <div class="panel <?php echo ($presuEncab->tipcom_id == 1) ? 'panel-info' : 'panel-danger'; ?>">
            <div class="panel-heading"><h4>
          <span id="tipcom_nom">
            <?php
            switch ($presuEncab->tipcom_id) {
                case 1:
                    $msg = 'Ticket';
                    break;
                case 2:
                    $msg = 'Factura';
                    break;
                case 6:
                    $msg = 'Remito';
                    break;
            };
            echo $msg; ?>
          </span>
                    <span><?php printf("%04.0f", $presuEncab->puesto) ?></span>
                </h4>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item"><?php printf("%08.0f", $presuEncab->numero) ?></li>
                </ul>
            </div>
            <div class="panel-footer">
                Bultos <span id="bultos" class="text-right"></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-xs-3">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 style="text-align: center">
                    <span id="idCuenta"><?php echo sprintf(" %04u ", $presuEncab->cuenta_id) ?></span>
                </h4>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class=" list-group-item">
                        <span id="nombreCuenta"><?php echo $presuEncab->cuenta_nombre ?></span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-xs-3">
        <div class="panel panel-warning">
            <div class=" panel-heading">
                <h4 class="text-center">Forma de Pago </h4>
            </div>
            <div class=" panel-body">
                <div id="fpagosList">
                    <div>
                        <span class='fpagoNombre'>CONTADO</span>
                        <span> &nbsp;&nbsp;0</span>
                        <div class='btn btn_mod'><span class='fa fa-refresh'></span></div>
                        <div class='btn btn_del'><span class='fa fa-trash-o'></span></div>
                    </div>
                </div>
                <div class="btn btn-info"><span class="fa fa-plus-circle"></span> Nuevo Medio Pago</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-xs-4">
        <div class="panel panel-primary">
            <div class=" panel-heading">
                <h4 class="text-center">Total </h4></div>
            <div class=" panel-body">
                <div>
                    <div class="alert alert-info text-right " style="font-size:48px;font-weight: bolder " id="importe">
                        <?php printf("$%01.2f", floatval($totales->Total)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.row -->
<div class="row">
    <div class="col-log-12 col-md-12">
        <div class="panel panel-primary">
            <div class="panel-body">
                <table class="table" id="brief">
                    <thead>
                    <tr style="text-align: center">
                        <th width="20%">Codigo</th>
                        <th width="40%">Descripcion</th>
                        <th width="10%">Cantidad</th>
                        <th width="10%">Precio</th>
                        <th width="20%" colspan="2">Importe</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($Articulos && count($Articulos) > 0): ?>
                        <?php foreach ($Articulos as $articulo): ?>
                            <tr id="art_<?php echo $articulo->codmov; ?>">
                                <td><strong><?php echo $articulo->Codigobarra ?></strong></td>
                                <td><strong><?php echo $articulo->Nombre ?></strong></td>
                                <td id="cant_<?php echo $articulo->codmov; ?>"><?php echo $articulo->Cantidad ?></td>
                                <td class="text-right"
                                    id="pre_<?php echo $articulo->codmov; ?>"><?php printf("$%01.2f", $articulo->Precio); ?></td>
                                <td class="text-right"
                                    id="imp_<?php echo $articulo->codmov; ?>"><?php printf("$%01.2f", $articulo->Importe) ?></td>
                                <td>
                                    <?php echo anchor('pos/billing/delArticulo/' . $articulo->codmov, '<button type="button" class="btn btn-circle btn-xs btn-danger botdel"><span class="fa fa-minus-circle"></span></button> ') ?>
                                </td>
                            </tr>
                            <?php $total += $articulo->Importe; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                <h3>Total <span id="importe2"><?php printf("$%01.2f", $total); ?></span></h3>
            </div>
        </div>
    </div>
</div> <!-- /.row-->

<div class="modal fade" id="cliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Busco Cliente</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('cuenta/searchCuentaXDo', 'id="consultaCuenta"') ?>
                <?php echo form_input('cuentaTXT', '', 'id="cuentaTXT"') ?>
                <input type="hidden" id="filtro" value="1"/>
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
</div><!-- /.modal -->
<!-- modal de formas de pago -->
<div class="modal fade" id="fpago" tabindex="-1" role="dialog" aria-labelledby="myFpagos" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <?php echo form_open('pos/billing/fpagosDo', 'id="consultaFpagos"') ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Medios de Pago</h4>
            </div>
            <div class="modal-body">
                <div id="datosFpagos">
                    <div>
                        <?php echo form_label('Importe') ?>
                        <?php echo form_input('monto', 0, 'id="montoTXT"') ?>
                    </div>
                    <?php
                    $cant = 0;
                    echo "<div>";
                    foreach ($mediosDePagos as $mp): ?>
                        <?php
                        $label = $tiposMdP[$mp->tipo]['label'];
                        $icon = $tiposMdP[$mp->tipo]['icon'];
                        ?>
                        <button type="button" class="btn btn-<?php echo $label ?> btn-mpago">
                            <span class="fa <?php echo $icon ?>"></span> <?php echo $mp->nombre ?></button>
                        <?php
                        $cant++;
                        if ($cant == 3) {
                            $cant = 0;
                            echo "</div><div>&nbsp;</div><div>";
                        }; ?>
                    <?php endforeach; ?>
                    <?php echo "</div>"; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-fpagosSave"><span class="fa fa-save"></span>
                        Grabar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
        <?php echo form_close() ?>
    </div>
</div><!-- /.modal -->

<div class="modal fade" id="cartelImpresion" tabindex="-1" role="dialog" aria-labelledby="cartelPrint"
     aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Impresion de comprobante...</h4>
            </div>
            <div class="modal-body" id="cartelImpresionCuerpo">
                <div align="center">
                    <p>Se esta impriendo el comprobante</p>
                    <i class="fa fa-spinner fa-spin fa-4x"></i>

                    <p>esta panatalla estara presente mientras se imprime</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div><!-- /.modal impresion-->

<!-- Modal HTML -->
<div id="espera" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Content will be loaded here from "remote.php" file -->
        </div>
    </div>
</div>


<div id="precio"></div>

<script>
    $(document).ready(function () {
        muestroFpagos();
        $("#loading").css('text-align', 'center');
        $("#loading").hide();
        $("#cuentaAjax").hide();
        $("#codigobarra").addClass('focus');
        $("#codigobarra").focus();
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
                    case 'f10':
                        CambioComprobante();
                        break;
                    case 'f12':
                        Imprimo(e);
                        break;
                }
            }
        });
        $("#codigobarra").bind('keydown', function (e) {
            var code = e.keyCode;

            if ($("#codigobarra").hasClass('focus')) {
                if ($("#codigobarra").val().trim().length === 0) {
                    if (code === 13) {
                        ConsultoPrecio(e);
                    }
                } else {
                    if (code === 13) {
                        AgregoArticulo(e);
                    }
                }
            }
        });
        // fin de chequeo de teclas de funciones
        // ejecuto el formulario con el lector de codigo de barras
        $("#addCart").submit(function () {
            e.preventDefault();
            AgregoArticulo();
        });
        //activo botones
        //$("#F1").button();
        $("#F1").click(function () {
            CanceloComprobante();
        });
        //$("#F6").button();
        $("#F6").click(function () {
            CambioCliente();
        });
        //$("#F8").button();
        $("#F8").click(function () {
            CambioCondicion('agrego');
        });
        //$("#F10").button();
        $("#F10").click(function () {
            CambioComprobante();
        });
        //$("#F12").button();
        $("#F12").click(function (e) {
            e.stopPropagation();
            Imprimo(e);
        });
        $("#addCart").submit(function (e) {
            e.preventDefault();
        });
        $("#brief > tbody > tr").first().addClass('info');
    });
    function AgregoArticulo(e) {
        e.preventDefault();
        datos = validoDatosArticulo();
        pagina = $("#addCart").attr('action');
        if (datos != false) {
            $.post(
                pagina,
                datos,
                function (data) {
                    if (data.error) {
                        MuestroError(data.codigoB, data.errorTipo, data.descripcion);
                    } else {
                        AgregoRenglon(data.id, data.codigoB, data.descripcion, data.cantidad, data.precio, data.importe);
                        $("#bultos").html(data.Bultos);
                        $("#importe").html(data.Totales);
                        $("#importe2").html(data.Totales);
                    }
                    muestroFpagos();
                    $("#codigobarra").addClass('focus');
                    $("#codigobarra").val('');
                    $("#codigobarra").focus();
                    $("#loading").fadeOut(100);
                }
            );
        } else {
            alert('Hubo un ERROR en la estructora de envio de datos');
        }
    }
    function MuestroError(CB, error, descripcion) {
        alert(CB + " " + descripcion + " " + error);
    }
    function AgregoRenglon(id, codigobarra, descripcion, cantidad, precio, importe) {
        $("#brief > tbody > tr").first().removeClass('alert-warning');
        // busco si ya no hay un articulo similar
        nombreAux = '#art_' + id;
        if ($(nombreAux).length) {
            $(nombreAux).addClass('alert-warning');
            cantAux = '#cant_' + id;
            preAux = '#pre_' + id;
            impAux = '#imp_' + id;
            $(cantAux).html(cantidad);
            $(preAux).html(precio);
            $(impAux).html(importe);
        } else {
            url = <?php echo "'" . base_url() . "pos/billing/delArticulo/'";?>;
            boton = '<a href="' + url + id + '" class="btn btn-circle btn-xs btn-danger botdel"><span class="fa fa-minus-circle"></span></a>';
            linea = "<tr class='alert alert-warning' id='art_" + id + "'>";
            linea += "<td><strong>" + codigobarra + "</strong></td>";
            linea += "<td><strong>" + descripcion + "</strong></td>";
            linea += "<td id='cant_" + id + "'>" + cantidad + "</td>";
            linea += "<td align='right' id='pre_'" + id + "'>" + precio + "</td>";
            linea += "<td align='right' id='imp_" + id + "'>" + importe + "</td>";
            linea += "<td>" + boton + "</td>";
            linea += "</tr>";
            $("#brief > tbody").prepend(linea);
        }
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
        id_temporal = $("#tmpfacencab_id").val();
        pagina = $("#paginaCancelo").val();
        $.post(pagina, {tmpfacencab_id: id_temporal}, function () {
            location.reload();
        });
    }
    function CambioCliente() {
        $("#cliente").modal({keyboard: true});
        $("#cliente").modal('show');
        $("#cliente").on('shown.bs.modal', function () {
            $("#cuentaTXT").focus();
        });
        $("#cliente").on('hide.bs.modal', function () {
            $("#cuentaTXT").val('');
            $("#datosClientes > tbody").html('');
        });
        $("#cuentaTXT").bind('keyup', function (e) {
            var code = e.keyCode;
            if (( code < 90 && code > 57 ) || code === 13 || code === 8) {
                envioFormCliente();
            }
        });
        $("#consultaCuenta").submit(function (e) {
            e.preventDefault();
            envioFormCliente();
        });
    }
    function CambioCondicion(accion) {
        $(".btn-mpago").each(function () {
            $(this).show();
        });
        $("#fpago").modal({keyboard: true});
        $("#fpago").modal('show');
        $("#fpago").on('shown.bs.modal', function () {
            if (accin = 'cambio') {
                $("#montoTXT").val(1000);
                $("#montoTXT").hide();
            } else {
                $("#montoTXT").focus();
            }
            $(".fpagoNombre").each(function () {
                valor = $(this).html().trim();
                $(".btn-mpago").each(function () {
                    value = $(this).text().trim();
                    if (valor == value) {
                        $(this).hide();
                    }
                    ;
                });
            });
        });
    }
    function CambioComprobante() {
        switch ($('#tipcom_nom').text().trim()) {
            case 'Ticket':
                tipAux = 6;
                break;
            case 'Factura':
                tipAux = 6;
                break;
            case 'Remito':
                if (parseInt($('#idcuenta').text()) == 1) {
                    tipAux = 1;
                } else {
                    tipAux = 2;
                }
                break;
        }
        idencab = $("#tmpfacencab_id").val();
        url = <?php echo $paginaCambioComprob;?> +idencab + '/' + tipAux;
        $.getJSON(url, function (tipo) {
            $('#tipcom_nom').html(tipo.nombre);
        });
        //window.location.replace(url);
        /*
         $.ajax({
         url: url,
         method: "GET",
         data: {id:idencab,tipo:tipAux },
         dataType: "json"
         }).done(function(tipo){
         $('#tipcom_nom').html(tipo.nombre);
         });*/
    }
    function ImprimoTicketVie() {
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
    function Imprimo(e) {
        e.preventDefault();
        $("#cartelImpresion").on("show.bs.modal", function () {
            $(this).find(".modal-dialog").css("height", 300);
            $(this).find(".modal-dialog").css("width", 300);
        });
        $("#cartelImpresion").modal({keyboard: true});
        $("#cartelImpresion").modal('show');
        pagina = $("#F12").attr('href');
        tmpfacencab_id = $("#tmpfacencab_id").val();
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: true,
            type: "POST",
            data: ({
                tmpfacencab: tmpfacencab_id
            }),
            dataType: "json",
            async: true,
            success: function (datos) {
                location.reload();
            }
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
    function updateClock() {
        var currentTime = new Date();
        var currentHours = currentTime.getHours();
        var currentMinutes = currentTime.getMinutes();
        var currentSeconds = currentTime.getSeconds();
        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
        timeOfDay = '';
        // Choose either "AM" or "PM" as appropriate
        //var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
        // Convert the hours component to 12-hour format if needed
        //currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
        // Convert an hours component of "0" to "12"
        //currentHours = ( currentHours == 0 ) ? 12 : currentHours;
        // Compose the string for display
        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
        total = 0;
        $("#brief > tbody > tr").each(function () {
            total++;
        });
        $("#bultos").html(total);
        $("#clock").html(currentTimeString);
    }
    /* funciones de muestra del comprobante */
    function muestroFpagos() {
        $("#fpagosList").html('');
        $.getJSON(<?php echo $paginaMuestroFpagos;?>, function (data) {
            $.each(data, function (key, dato) {
                if (dato.fpagos_id == 1) {
                    label = 'alert-success';
                } else {
                    if (dato.fpagos_id == 9) {
                        label = 'alert-danger';
                    } else {
                        label = 'alert-warning';
                    }
                }
                linea = " <div class='alert " + label + " ' role='alert' >";
                linea += "<span class='fpagoNombre'>" + dato.pagoNombre + "</span>";
                linea += "<span> &nbsp;&nbsp;" + dato.monto + "</span>";
                linea += "<div class='btn btn_mod' id='mod_" + dato.id + "'><span class='fa fa-refresh'></span></div>";
                linea += "<div class='btn btn_del' id='del_" + dato.id + "'><span class='fa fa-trash-o'></span></div>";
                linea += "</div>";
                $("#fpagosList").append(linea);
            });
        });
        $(".btn_mod").each(function () {
            alert('entro');
        });
    }
    function getFpagos() {
        $("#getFpagos").html('');
        $.getJSON(<?php echo $paginaMuestroFpagos;?>, function (data) {
            $.each(data, function (key, dato) {
                if (dato.fpagos_id == 1) {
                    label = 'list-group-item list-group-item-success';
                } else {
                    if (dato.fpagos_id == 9) {
                        label = 'list-group-item list-group-item-alert';
                    } else {
                        label = 'list-group-item list-group-item-warning';
                    }
                }
                linea = "<tr class='" + label + "'><td>" + dato.pagoNombre + "</td><td></td><td>";
                linea += "<input value='" + dato.monto + "'/>";
                linea += "</td></tr>";
                $("#getFpagos").append(linea);
            });
        });
    }
    function envioFormCliente() {
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
            url = <?php echo "'" . base_url() . "pos/billing/cambioCuenta/$tmpfacencab_id/'"?>;
            linea = "<tr><td>" + cuenta.id + "</td>";
            linea += "<td>" + cuenta.nombre + "</td>";
            linea += "<td>" + cuenta.cuit + "</td>";
            if (cuenta.ctacte == 1) {
                clase = 'btn btn-danger';
                label = 'Ctacte';
            } else {
                clase = 'btn btn-success';
                label = 'Contado';
            }
            linea += "<td><a href='" + url + cuenta.id + "' class='" + clase + " btnCli' id='btn_" + cuenta.id + "'><span class='fa fa-check-circle-o'></span> " + label + "</a></td>";
            linea += "</tr>";
            $("#datosClientes > tbody").append(linea);
        })
    }
    function validoDatosArticulo() {
        if ($('#codigobarra').val().indexOf('*') > -1) {
            artic = $('#codigobarra').val().split('*');
            switch (artic.length) {
                case 2:
                    if ((!isNaN(parseFloat(artic[0]))) && (!isNaN(parseInt(artic[1])))) {
                        datos = {
                            tmpfacencab_id: $('#tmpfacencab_id').val(),
                            codigobarra: artic[1],
                            cantidad: parseFloat(artic[0])
                        };
                    } else {
                        datos = false;
                    }
                    break;
                case 3:
                    if ((!isNaN(parseFloat(artic[0]))) &&
                        (!isNaN(parseInt(artic[1]))) &&
                        (!isNaN(parseFloat(artic[2])))
                    ) {
                        datos = {
                            tmpfacencab_id: $('#tmpfacencab_id').val(),
                            codigobarra: artic[1],
                            cantidad: artic[0],
                            precio: artic[2]
                        };
                    } else {
                        datos = false;
                    }
                    break;
                default :
                    datos = false;
            }
        } else {
            datos = $('#addCart').serialize();
        }
        return datos;
    }
</script>