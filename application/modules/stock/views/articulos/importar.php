<?php
/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 07/05/2016
 * Time: 07:36 AM
 */
?>
<style>
    .subirArchivo {
        position: absolute;
        z-index: 2;
        top: 0;
        left: 0;
        filter: alpha(opacity=0);
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
        opacity: 0;
        background-color: transparent;
        color: transparent;
    }
</style>
<div class="section">
    <?php if ( $tipo == "proveedor" ): ?>
        <div class="row">
            <div class="col-sm-12">
                <?php echo form_open_multipart ( 'stock/articulos/do_importar', "id='upload-form'" ) ?>
                <div style="position:relative;">
                    <a class='btn btn-primary' href='javascript:;'>
                        Seleccionar Archivo de la PC
                        <input type="file" class="subirArchivo" name="userfile" size="40"
                               onchange='$("#upload-file-info").html($(this).val());'>
                    </a>
                    &nbsp;
                    <span class='label label-info' id="upload-file-info"></span>
                </div>
                <button type="submit" class="btn btn-success">Leer Archivo</button>
                <?php echo form_close () ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <?php echo form_open ( 'stock/articulos/comparar', 'id="comparar-form"' ) ?>
    <div class="col-sm-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Articulos a Procesar para el Proveedor
                - <?php echo form_dropdown ( 'idProveedor', $proveedoresSel, 1, 'id=idProveedor' ); ?></div>
            <div class="panel-body">
                <div class="table-content">
                    <table class="table table-responsive table-borded ">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Codigor</th>
                            <th>Descripcion</th>
                            <th>Costo</th>
                        </tr>
                        </thead>
                        <tbody id="datosAimportar">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <button type="button" class="btn btn-warning" id="btn-verificar">Verificar</button>
            </div>
        </div>
    </div>
    <?php echo form_close (); ?>
</div>
<script>
    $(document).ready(function () {
        $("#btn-verificar").hide();
        $("#upload-form").submit(function (e) {
            e.preventDefault();
            url = $("#upload-form").attr('action');
            var fd = new FormData(document.getElementById("upload-form"));
            $.ajax({
                url: url,
                data: fd,
                cache: false,
                //contentType: 'multipart/form-data',
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    $.each(data, function (linea, dato) {
                        fila = "<tr id='" + linea + "'>";
                        fila += "<td>" + linea + "</td>";
                        fila += "<td><input type='hidden' value='" + dato.codigoProveedor + "' name='codigo_" + dato.codigoProveedor + "' />" + dato.codigoProveedor + "</td>";
                        fila += "<td><input type='hidden' value='" + dato.Descripcion + "' name='descripcion_" + dato.codigoProveedor + "' />" + dato.Descripcion + "</td>";
                        fila += "<td><input type='hidden' value='" + dato.Costo + "' name='costo_" + dato.codigoProveedor + "' />" + dato.Costo + "</td>";
                        fila += "</tr>";
                        $("#datosAimportar").append(fila);
                    });
                    $("#btn-verificar").show();
                }
            });
        });
        $("#btn-verificar").click(function (e) {
            e.preventDefault();
            proveedor = $("#idProveedor").val();
            if (proveedor == "S") {
                mensaje = "Debe seleccionar un Proveedor";
                alert(mensaje);
            } else {
                $("#comparar-form").submit();
            }
        })
    })
</script>
