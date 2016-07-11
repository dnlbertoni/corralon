<?php
/**
 * Created by PhpStorm.
 * User: dnlbe
 * Date: 21/5/2016
 * Time: 7:22 PM
 */ ?>
<div class="section">
    <form class="form-actions" id="articulo-form" action="<?= $accion ?>" method="post">
        <h2>Articulo <?php echo ( $articulo->ID_ARTICULO == 0 ) ? " Nuevo" : $articulo->ID_ARTICULO; ?></h2>
        <div class="row">
            <div class="col-md-6 alert-info">
                <?php echo form_label ( 'Codigo' ); ?>
                <?php echo form_input ( 'ID_ARTICULO', $articulo->ID_ARTICULO, 'size="10" ' ); ?>
            </div>
            <div class="col-md-6 alert-info">
                <?php echo form_label ( 'Descripcion' ); ?>
                <?php echo form_input ( 'DESCRIPCION_ARTICULO', $articulo->DESCRIPCION_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-4 alert-info">
                <?php echo form_label ( 'Color' ); ?>
                <?php echo form_input ( 'DET_COLOR_ARTICULO', $articulo->DET_COLOR_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-4 alert-info">
                <?php echo form_label ( 'Material' ); ?>
                <?php echo form_input ( 'DET_MATERIAL_ARTICULO', $articulo->DET_MATERIAL_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-3 alert-info">
                <?php echo form_label ( 'Peso' ); ?>
                <?php echo form_input ( 'DET_PESO_ARTICULO', $articulo->DET_PESO_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-3 alert-info">
                <?php echo form_label ( 'Alto' ); ?>
                <?php echo form_input ( 'DET_ALTO_ARTICULO', $articulo->DET_ALTO_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-3 alert-info">
                <?php echo form_label ( 'Ancho' ); ?>
                <?php echo form_input ( 'DET_ANCHO_ARTICULO', $articulo->DET_ANCHO_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-3 alert-info">
                <?php echo form_label ( 'Cant x Bulto' ); ?>
                <?php echo form_input ( 'CANTXBULTO_ARTICULO', $articulo->CANTXBULTO_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-4 alert-info">
                <?php echo form_label ( 'Stock Minimo' ); ?>
                <?php echo form_input ( 'STOCKMIN_ARTICULO', $articulo->STOCKMIN_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-4 alert-info">
                <?php echo form_label ( 'Sotck Maximo' ); ?>
                <?php echo form_input ( 'STOCKMAX_ARTICULO', $articulo->STOCKMAX_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-4 alert-info">
                <?php echo form_label ( 'Costo' ); ?>
                <?php echo form_input ( 'COSTO_ARTICULO', $articulo->COSTO_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-4 alert-info">
                <?php echo form_label ( 'Precio' ); ?>
                <?php echo form_input ( 'PRECIO_ARTICULO', $articulo->PRECIO_ARTICULO, 'size="15"' ); ?>
            </div>
            <div class="col-md-4 alert-info">
                <?php echo form_label ( 'Tasa de IVA' ); ?>
                <?php echo form_input ( 'TASAIVA_ARTICULO', $articulo->TASAIVA_ARTICULO, 'size="15"' ); ?>
            </div>
        </div>
    </form>
</div>
<div class="section">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-success">Guardar</button>
        </div>
    </div>
</div>
