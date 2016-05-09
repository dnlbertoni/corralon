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
            <?php echo form_open ( 'stock/articulos/importarProveedor' ) ?>
            <div style="position:relative;">
                <a class='btn btn-primary' href='javascript:;'>
                    Seleccionar Archivo de la PC
                    <input type="file" class="subirArchivo" name="file_source" size="40"
                           onchange='$("#upload-file-info").html($(this).val());'>
                </a>
                &nbsp;
                <span class='label label-info' id="upload-file-info"></span>
            </div>
            <?php echo form_close () ?>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <div class="panel panel-primary">
        <div class="panel-heading">Articulos a Procesar</div>
        <div class="panel-body">
            <div class="table-content">
                <table class="table table-responsive table-borded ">
                    <thead>
                    <tr class="table-title">
                        <th>#</th>
                        <th>Rubro</th>
                        <th>SubRubro</th>
                        <th>Descripcion</th>
                        <th>Marca</th>
                        <th>Costo</th>
                        <th>Precio</th>
                        <th>Accions</th>
                    </tr>
                    </thead>
                    <tbody id="datosAimportar">

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>