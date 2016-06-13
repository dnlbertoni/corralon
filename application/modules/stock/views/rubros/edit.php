<?php
/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 21/05/2016
 * Time: 05:18 PM
 */
?>
<div class="section">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <h1>Rubro <?php echo ( $rubro->ID_RUBRO == "" ) ? "Nuevo" : $rubro->ID_RUBRO ?></h1>
            <?php echo form_open ( $accion, 'id="rubro-form"' ) ?>
            <?php echo form_hidden ( 'ID_RUBRO', $rubro->ID_RUBRO ); ?>
            Descripcion
            : <?php echo form_input ( 'DESCRIPCION_RUBRO', $rubro->DESCRIPCION_RUBRO, 'class="form-control"' ) ?>
            <br/>
            Alias: <?php echo form_input ( 'ALIAS_RUBRO', $rubro->ALIAS_RUBRO, 'class="form-control"' ) ?>
            <br/>
            Unidad Medida<?php echo form_dropdown ( 'UNIDAD_RUBRO', $unidadSel, $rubro->UNIDAD_RUBRO ); ?>
            <BR/>
            <div class="btn-group" data-toggle="buttons">
                <?php echo form_label ( 'Activo ', 'estado1', 'class="btn btn-default"' ); ?><?php echo form_radio ( 'ESTADO_RUBRO', '1', ( $rubro->ESTADO_RUBRO == 1 ) ? true : false, ' id="estado1"' ) ?>
                <?php echo form_label ( 'Suspendido ', 'estado2', 'class="btn btn-default"' ); ?><?php echo form_radio ( 'ESTADO_RUBRO', '0', ( $rubro->ESTADO_RUBRO == 0 ) ? true : false, 'id="estado2"' ) ?>
            </div>
            <br/>
            <?php echo anchor ( 'stock/rubros', 'Cancelar', 'class="btn btn-danger"' ); ?>
            <button type="submit" class="btn btn-success">Guardar</button>
            <?php echo form_close (); ?>
        </div>
    </div>
</div>
