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
                                        <td><?php echo round ( floatval ( $encontrado->precio ) * floatval ( $articulosProveedor[$encontrado->codigoProveedor]['costo'] ) / $encontrado->costo, 2 ) ?></td>
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
                                <?php foreach ( $articulosProveedor as $encontrado ): ?>
                                    <?php if ( $encontrado['accion'] == "add" ): ?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><?php echo $encontrado['codigo'] ?></td>
                                            <td><?php echo $encontrado['descripcion'] ?></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td><?php echo $encontrado['costo'] ?></td>
                                            <td></td>
                                            <td></td>
                                            <td>Acciones</td>
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
    </div>
<?php var_dump ( $articulosProveedor ); ?>