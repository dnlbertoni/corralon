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
            <h1>Rubros</h1>
            <?php echo form_open ( $accion, 'id="rubro-form"', $ocultos ) ?>
            Descripcion : <?php echo form_input ( 'descripcion', $rubro->DESCRIPCION_RUBRO ) ?>
            <br/>
            Alias: <?php echo form_input ( 'alias', $rubro->ALIAS_RUBRO ) ?>
            <br/>
            Unidad Medida<?php echo form_dropdown ( 'unidad', $unidadSel, $rubro->UNIDAD_RUBRO ); ?>
            <BR/>
            <div id="estado">
                <?php echo form_label ( 'Activo ', 'estado1' ); ?><?php echo form_radio ( 'estado', '1', ( $rubro->ESTADO_RUBRO == 1 ) ? true : false, 'id="estado1"' ) ?>
                <?php echo form_label ( 'Suspendido ', 'estado2' ); ?><?php echo form_radio ( 'estado', '0', ( $rubro->ESTADO_RUBRO == 0 ) ? true : false, 'id="estado2"' ) ?>
            </div>
            <br/>
            <?php echo anchor ( 'articulos/rubros', 'Cancelar', 'class="boton"' ); ?>
            <div id="botSave" class="boton">Guardar</div>
            <?php echo form_close (); ?>
        </div>
    </div>
</div>
