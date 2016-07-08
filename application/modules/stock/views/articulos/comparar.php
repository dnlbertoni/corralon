<?php
/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 15/05/2016
 * Time: 12:31 PM
 */ ?>

    <div class="section">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-success">
                    <div class="panel-heading">Articulos encontrados</div>
                    <div class="panel-body">
                        <div class="table-content">
                            <table class="table table-responsive table-borded ">
                                <thead>
                                <tr>
                                    <th colspan="2">Codigo</th>
                                    <th colspan="2">Descripcion</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th colspan="2">Costos</th>
                                    <th colspan="2">Precios</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr>
                                    <th>Sist</th>
                                    <th>Prov</th>
                                    <th>Prov</th>
                                    <th>Sist</th>
                                    <th>Subrubro</th>
                                    <th>Marca</th>
                                    <th>Sistema</th>
                                    <th>Nuevo</th>
                                    <th>Sistema</th>
                                    <th>Sugerido</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody id="datosEncontrados">
                                <?php foreach ( $articulosEncontrados as $encontrado ): ?>
                                    <tr>
                                        <td><?php echo $encontrado->id ?></td>
                                        <td><?php echo $articulosProveedor[$encontrado->codigoProveedor]['codigo'] ?></td>
                                        <td><?php echo $articulosProveedor[$encontrado->codigoProveedor]['descripcion'] ?></td>
                                        <td><?php echo $encontrado->descripcion ?></td>
                                        <td><?php echo $encontrado->subrubro ?></td>
                                        <td><?php echo $encontrado->submarca ?></td>
                                        <td><?php echo $encontrado->costo ?></td>
                                        <td><?php echo $articulosProveedor[$encontrado->codigoProveedor]['costo'] ?></td>
                                        <td><?php echo $encontrado->precio ?></td>
                                        <td><?php echo form_input ( 'precio_' . $encontrado->id, round ( floatval ( $encontrado->precio ) * floatval ( $articulosProveedor[$encontrado->codigoProveedor]['costo'] ) / $encontrado->costo, 2 ), "size='5'" ) ?></td>
                                        <td>Acciones</td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        boton
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">Articulos No ENCONTRADOS</div>
                    <div class="panel-body">
                        <div class="table-content">
                            <table class="table table-responsive table-borded ">
                                <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Costos</th>
                                    <th>&nbsp;</th>
                                    <th>Precios</th>
                                </tr>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Descripcion</th>
                                    <th>Subrubro</th>
                                    <th>Marca</th>
                                    <th>Nuevo</th>
                                    <th>Markup</th>
                                    <th>Sugerido</th>
                                    <th>&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody id="datosNoEncontrados">
                                <?php foreach ( $articulosProveedor as $key => $encontrado ): ?>
                                    <?php if ( $encontrado['accion'] == "add" ): ?>
                                        <tr id="<?php echo $key ?>">
                                            <td>
                                                <input type="hidden" value="<?php echo $cuenta_id; ?>"
                                                       name="cuenta_id"/>
                                                <?php echo form_input ( 'codigoProveedor', $encontrado['codigo'], 'size="5"' ) ?>
                                            </td>
                                            <td><?php echo form_input ( 'descripcion', $encontrado['descripcion'], 'size="80"' ) ?></td>
                                            <th><?php echo form_dropdown ( 'subrubro', $subrubrosSel, 1 ) ?></th>
                                            <th><?php echo form_dropdown ( 'submarca', $submarcasSel, 1 ) ?></th>
                                            <td><?php echo form_input ( 'costo', $encontrado['costo'], 'size="5"' ) ?></td>
                                            <td><?php echo form_input ( 'markup', $encontrado['markup'], 'size="5"' ) ?></td>
                                            <td><?php echo form_input ( 'precio', $encontrado['precio'], 'size="5"' ) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-xs btn-insert"><i
                                                        class="fa fa-plus-circle"></i></button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        boton
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">Articulos Procesados</div>
                    <div class="panel-body">
                        <div class="table-content">
                            <table class="table table-responsive table-borded ">
                                <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Costos</th>
                                    <th>&nbsp;</th>
                                    <th>Precios</th>
                                </tr>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Descripcion</th>
                                    <th>Subrubro</th>
                                    <th>Marca</th>
                                    <th>Nuevo</th>
                                    <th>Markup</th>
                                    <th>Sugerido</th>
                                    <th>&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody id="datosProcesados">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        boton
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function () {
        $(".btn-insert").click(function () {
            lineaHTML = $(this).parent().parent().html();
            datos = $(this).parent().parent().find("input").serialize();
            datos += "&";
            datos += $(this).parent().parent().find("select").serialize();
            alert(datos);
            $.ajax({
                type: "GET",
                url: '/stock/articulos/agregarDeLote',
                data: datos,
                dataType: "json",
                success: function (data) {
                    Procesado(lineaHTML);
                    $(this).parent().parent().remove();
                },
                error: function () {
                    alert('Ocurrio un error en la insercion');
                }
            });
        });
    });
    function Procesado(lineaHTML) {
        lineaHTML = "<tr>" + lineaHTML + "</tr>";
        $("#datosProcesados").append(lineaHTML);
    }
</script>