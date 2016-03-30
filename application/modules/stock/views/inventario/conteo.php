<?php
/**
 * Created by PhpStorm.
 * User: dnl
 * Date: 20/05/14
 * Time: 19:15
 */ ?>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 ">
                <?php echo form_open('stock/inventario/agregoAlConteo', 'id="form-Add"'); ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3>Conteo del <?php echo $depositoNombre ?></h3>
                        <?php echo form_hidden('deposito', $depositoId); ?>
                        <?php echo form_hidden('inventario', $inventarioId); ?>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-lg-12 ">
                                <?php echo form_label('Codigo'); ?>
                                <?php echo form_input('CB', '', 'class="form-control focus" id="codigobarra"'); ?>
                            </div>
                            <div class="col-lg-12">
                                <?php echo form_label('Nombre Articulo'); ?>
                                <div id="nombreArticulo"></div>
                            </div>
                            <div class="col-lg-6 ">
                                <?php echo form_label('Cajas'); ?>
                                <?php echo form_input('cantidadBultos', '', ' class="form-control" id="cantidadBultos"'); ?>
                            </div>
                            <div class="col-lg-6 ">
                                <?php echo form_label('Cant.X Bultos'); ?>
                                <?php echo form_input('cantidadXbultos', '', ' class="form-control" id="cantidadXbultos"'); ?>
                            </div>
                            <br/>
                            <div class="col-lg-6 ">
                                <?php echo form_label('Sueltas'); ?>
                                <?php echo form_input('unidades', '', ' class="form-control" id="unidades"'); ?>
                            </div>
                            <div class="col-lg-6 ">
                                <?php echo form_label('Total'); ?>
                                <?php echo form_input('cantidad', '', ' class="form-control" id="cantidad"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <button type="button" class="btn btn-info" id="ADD">
                            <span class="fa fa-plus-circle"></span> Agregar al Conteo
                        </button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="col-lg-6 col-md-6 ">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3>Estadisticas</h3>
                    </div>
                    <div class="panel-body">
                        <p>van estadisticas</p>
                    </div>
                    <div class="panel-footer text-center">
                        <button type="button" class="btn btn-success" id="OPEN">
                            <span class="fa fa-unlock"></span> Iniciar el Conteo
                        </button>
                        <button type="button" class="btn btn-danger" id="CLOSE">
                            <span class="fa fa-lock"></span> Terminar el Conteo
                        </button>
                        <button type="button" class="btn btn-info" id="BACK">
                            <span class="fa fa-backward"></span> Volver
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
        <div class="row">
            <div class="panel panel-info">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Codigo Barra</th>
                        <th>Nombre</th>
                        <th>Cant. de Bultos</th>
                        <th>Cant. X Bultos</th>
                        <th>Unid. Sueltas</th>
                        <th>Cant. Total</th>
                        <th>Fecha Conteo</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody id="datos">
                    <?php foreach ($articulos as $arti): ?>
                        <tr id="<?php echo $arti->id ?>">
                            <td><?php echo $arti->codigobarra; ?></td>
                            <td><?php echo $arti->descripcion; ?></td>
                            <td class="text-right">
                                <span><?php echo $arti->cant_bultos; ?></span>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-xs">
                                        <?php echo anchor('stock/inventario/modificarConteo/box/plus/', '<span class="fa fa-caret-up"></span>'); ?>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs">
                                        <?php echo anchor('stock/inventario/modificarConteo/box/minus/', '<span class="fa fa-caret-down"></span>'); ?>
                                    </button>
                                </div>
                            </td>
                            <td class="text-right">
                                <?php echo $arti->unidades_bulto; ?>
                            </td>
                            <td class="text-right">
                                <span><?php echo $arti->unidades_sueltas; ?></span>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-xs">
                                        <?php echo anchor('stock/inventario/modificarConteo/uni/plus/', '<span class="fa fa-caret-up"></span>'); ?>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs">
                                        <?php echo anchor('stock/inventario/modificarConteo/uni/minus/', '<span class="fa fa-caret-down"></span>'); ?>
                                    </button>
                                </div>
                            </td>
                            <td class="text-right">
                                <span class="totalUnidades"><?php echo $arti->cantidad; ?></span>
                            </td>
                            <td><?php echo date("d/m/Y H:i:s", strtotime($arti->fecha)); ?></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"><span
                                        class="fa fa-trash-o"></span><?php echo anchor('stock/inventario/sacarDelConteo/', ' '); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /.container -->
</div><!-- /.section -->
<div class="modal fade modal-lg " role="dialog" id="ventanaCantidades" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cantidad de unidades</h4>
            </div>
            <div class="modal-body text-center">
                <div class="alert-success">
                    <span id="nombreArticuloModal"></span>
                </div>
                <div class="form-group">
                    <?php echo form_label('Cantidad de Bultos', 'cantidadBultosModal'); ?>
                    <?php echo form_input('cantidadBultosModal', $bultosDefault, 'id="cantidadBultosModal" '); ?>
                    <br/>
                    <?php echo form_label('Unidades X Bultos', 'cantidadXbultosModal'); ?>
                    <?php echo form_input('cantidadXbultosModal', '', 'id="cantidadXbultosModal"'); ?>
                </div>
                <div class="form-group">
                    <?php echo form_label('Unidades Sueltas', 'unidadesModal'); ?>
                    <?php echo form_input('unidadesModal', $unidadesDefault, 'id="unidadesModal" '); ?>
                </div>
                <div class="alert-success">
                    Cantidad Total :<span id="cantidadTotalModal">0</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnACEPTARModal">Agregar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade modal-lg " role="dialog" id="ventanaError" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header dialog-header-error">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Error</h4>
            </div>
            <div class="modal-body text-center">
                <p>El articulo no se encuentra en la base de datos</p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(document).ready(function () {
        $("#datos > tr:first").addClass('success');
        $('input').focusin(function () {
            $(this).select();
        });
        $("#codigobarra").focus();
        /*inicio de definiciones de capturas de teclas */
        $("#codigobarra").bind('keydown', function (e) {
            var code = e.keyCode;
            if ($("#codigobarra").hasClass('focus')) {
                if ($("#codigobarra").val().trim().length > 0) {
                    if (code === 13) {
                        PreguntoCantidadBultos(e);
                    }
                }
            }
            ;
        });
        $("#cantidadBultosModal").bind('keydown', function (e) {
            var code = e.keyCode;
            if ($("#cantidadBultosModal").hasClass('focus')) {
                if ($("#cantidadBultosModal").val().trim().length > 0) {
                    if (code === 13) {
                        $("#cantidadBultosModal").removeClass('focus');
                        if (parseInt($("#cantidadBultosModal").val().trim()) > 0) {
                            $("#cantidadXbultosModal").addClass('focus');
                            $("#cantidadXbultosModal").focus();
                        } else {
                            $("#unidadesModal").addClass('focus');
                            $("#unidadesModal").focus();
                        }
                        ;
                    }
                }
            }
            ;
        });
        $("#cantidadXbultosModal").bind('keydown', function (e) {
            var code = e.keyCode;
            if ($("#cantidadXbultosModal").hasClass('focus')) {
                if ($("#cantidadXbultosModal").val().trim().length > 0) {
                    if (code === 13) {
                        $("#cantidadXbultosModal").removeClass('focus');
                        $("#unidadesModal").addClass('focus');
                        $("#unidadesModal").focus();
                    }
                }
            }
            ;
        });
        $("#unidadesModal").bind('keydown', function (e) {
            var code = e.keyCode;
            if ($("#unidadesModal").hasClass('focus')) {
                if ($("#unidadesModal").val().trim().length > 0) {
                    if (code === 13) {
                        $("#unidadesModal").removeClass('focus');
                        cantidad = parseInt($("#cantidadBultosModal").val());
                        bultos = parseInt($("#cantidadXbultosModal").val());
                        unidades = parseInt($("#unidadesModal").val());
                        total = (cantidad * bultos) + unidades;
                        $("#cantidadTotalModal").text(total);
                        $("#btnACEPTARModal").focus();
                    }
                }
            }
            ;
        });
        /* termina la captura de teclas */
        /**
         * si cambia el valor del codigo de barra buscar el articulo
         */
        $("#codigobarra").change(function () {
            valor = $(this).val().trim();
            DatosDelArticulo(valor);
        });
        $("#ventanaCantidades").on('shown.bs.modal', function (e) {
            <?php if($depositoId != "salon"):?>
            $(this).find('input:text:visible:first').focus();
            $(this).find('input:text:visible:first').addClass('focus');
            <?php else :?>
            $("#unidadesModal").focus();
            $("#unidadesModal").addClass('focus');
            <?php endif;?>
        });
        $("#ventanaCantidades").on('hidden.bs.modal', function (e) {
            $("#codigobarra").val('');
            $("#unidades").val('');
            $("#cantidadBultos").val('');
            $("#cantidadXBultos").val('');
            $("#unidadesModal").val('');
            $("#cantidadBultosModal").val('');
            $("#cantidadXBultosModal").val('');
            $("#codigobarra").addClass('focus');
            $("#codigobarra").focus();
        });
        $("#btnACEPTARModal").click(function () {
            cantidad = parseInt($("#cantidadBultosModal").val());
            bultos = parseInt($("#cantidadXbultosModal").val());
            unidades = parseInt($("#unidadesModal").val());
            $("#cantidadBultos").val(cantidad);
            $("#cantidadXbultos").val(bultos);
            $("#unidades").val(unidades);
            total = (cantidad * bultos) + unidades;
            $("#cantidad").val(total);
            $("#ADD").click();
            $("#ventanaCantidades").modal('hide');
        });
        $("#ADD").click(function () {
            envioArticuloAlConteo();
        });
        /*****************************
         * incrementa o resta unidades
         ******************************/
        $(".btn-xs").click(function (e) {
            e.preventDefault();
            id = $(this).parent().parent().parent().attr('id');
            url = $(this).find("a").attr('href') + '/' + id;
            texto = $(this).parent().parent().find("span:first");
            cantidad = $(this).parent().parent().parent().find(".totalUnidades:first");
            $.post(url, '', function (data) {
                texto.text(data.numero);
                cantidad.text(data.total);
            }, "json");
        });
        $(".btn-sm").click(function (e) {
            e.preventDefault();
            linea = $(this).parent().parent();
            id = linea.attr('id');
            url = $(this).find("a").attr('href') + '/' + id;
            $.post(url, '', function () {
                linea.remove();
                $("#codigobarra").addClass('focus');
                $("#codigobarra").focus();
            }, "json");
        });
    });
    function getSpecialKey(code) {
        if (code > 111 && code < 124) {
            aux = code - 111;
            return 'f' + aux;
        } else {
            return false;
        }
    }
    function PreguntoCantidadBultos(e) {
        $("#codigobarra").removeClass('focus');
        valor = $("#codigobarra").val();
        DatosDelArticulo(valor);
    }
    function DatosDelArticulo(codigo) {
        url =<?php echo $paginaAjaxDatosArticulo?> +'/' + codigo;
        $.getJSON(url, function (data) {
            if (data.existe) {
                $("#nombreArticulo").text(data.nombre);
                $("#nombreArticuloModal").text(data.nombre);
                $("#cantidadXbultosModal").val(data.bultos);
                $("#cantidadXbultos").val(data.bultos);
                $("#ventanaCantidades").modal('show');
            } else {
                $("#ventanaError").modal('show');
            }
        });
    }
    function envioArticuloAlConteo() {
        datos = $("#form-Add").serialize();
        url = $("#form-Add").attr('action');
        $.post(url, datos, function (data) {
            if ($("#datos > tr:first").length) {
                $("#datos > tr:first").removeClass('success');
                linea = $("#datos > tr:first").clone(true);
                linea.children('td').eq(0).text(data.codigobarra);
                linea.children('td').eq(1).text(data.nombre);
                linea.children('td').eq(2).find("span:first").text(data.cantbultos);
                linea.children('td').eq(3).text(data.cantxbultos);
                linea.children('td').eq(4).find("span:first").text(data.unidades);
                linea.children('td').eq(5).find("span:first").text(data.cantidad);
                linea.children('td').eq(6).text(data.fecha);
                linea.addClass('success');
                $("#datos").prepend(linea);
            } else {
                location.reload();
            }
        }, 'json');
    }
</script>