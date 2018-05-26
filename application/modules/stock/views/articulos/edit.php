<?php
/**
 * Created by PhpStorm.
 * User: dnlbe
 * Date: 21/5/2016
 * Time: 7:22 PM
 */ ?>




<form class="form-actions" id="articulo-form" action="<?= $accion ?>" method="post">
    <h2>Articulo <?php echo ( $articulo->ID_ARTICULO == 0 ) ? " Nuevo" : $articulo->ID_ARTICULO; ?></h2>
    <?php echo form_hidden( 'ID_ARTICULO', $articulo->ID_ARTICULO ); ?>
<section>
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#caract" aria-controls="caract" role="tab" data-toggle="tab"><i class="fa fa-weixin"></i> Caracteristicas</a>
            </li>
            <li role="presentation">
                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-money"></i> Precios y Costos</a>
            </li>
            <li role="presentation">
                <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><i class="fa fa-truck"></i> Stock</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="caract">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo form_label ( 'Rubro' ); ?>
                        <?php echo form_dropdown( 'ID_RUBRO', $selRubros,$rubro, 'id="rubro" class="form-control"' ); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo form_label ( 'Subrubro' ); ?>
                        <?php echo form_dropdown( 'ID_SUBRUBRO', $selSubrubros,$articulo->ID_SUBRUBRO, 'id="subrubro" class="form-control ' ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo form_label ( 'Descripcion' ); ?>
                        <?php echo form_input ( 'DESCRIPCION_ARTICULO', $articulo->DESCRIPCION_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo form_label ( 'Color' ); ?>
                        <?php echo form_input ( 'DET_COLOR_ARTICULO', $articulo->DET_COLOR_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo form_label ( 'Material' ); ?>
                        <?php echo form_input ( 'DET_MATERIAL_ARTICULO', $articulo->DET_MATERIAL_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-md-4 ">
                        <?php echo form_label ( 'Peso' ); ?>
                        <?php echo form_input ( 'DET_PESO_ARTICULO', $articulo->DET_PESO_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo form_label ( 'Alto' ); ?>
                        <?php echo form_input ( 'DET_ALTO_ARTICULO', $articulo->DET_ALTO_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo form_label ( 'Ancho' ); ?>
                        <?php echo form_input ( 'DET_ANCHO_ARTICULO', $articulo->DET_ANCHO_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <input type="hidden" value="0" name="ESTADO_ARTICULO"/>
                        <?php echo form_checkbox ( 'ESTADO_ARTICULO', 1, ( $articulo->ESTADO_ARTICULO == 1 ) ? true : false, 'id="estadoID"' ) ?>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="profile">
                <div class="row">
                    <div class="col-md-6">
                        <?php echo form_label ( 'Costo' ); ?>
                        <?php echo form_input ( 'COSTO_ARTICULO', $articulo->COSTO_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo form_label ( 'Precio' ); ?>
                        <?php echo form_input ( 'PRECIO_ARTICULO', $articulo->PRECIO_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="clearfix">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo form_label ( 'Tasa de IVA' ); ?>
                        <?php echo form_dropdown('TASAIVA_ARTICULO',$opcTasas,$articulo->TASAIVA_ARTICULO, 'class="form-control"')?>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="settings">
                <div class="row">
                    <div class="col-md-3">
                        <?php echo form_label ( 'Cant x Bulto' ); ?>
                        <?php echo form_input ( 'CANTXBULTO_ARTICULO', $articulo->CANTXBULTO_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?php echo form_label ( 'Stock Minimo' ); ?>
                        <?php echo form_input ( 'STOCKMIN_ARTICULO', $articulo->STOCKMIN_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?php echo form_label ( 'Sotck Maximo' ); ?>
                        <?php echo form_input ( 'STOCKMAX_ARTICULO', $articulo->STOCKMAX_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                    <div class="col-md-3 ">
                        <?php echo form_label ( 'Sotck Actual' ); ?>
                        <?php echo form_input ( 'STOCK_ARTICULO', $articulo->STOCK_ARTICULO, 'class="form-control"' ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="clearfix">&nbsp;</div>
                </div>

            </div>
        </div>

    </div>
</section>
    <div class="section">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        $("#tab-articulo").tab();
        $("#estadoID").bootstrapSwitch({
            onText: "Si",
            offText: "No",
            labelText: "Activo",
            onColor: "success",
            offColor: "danger",
            state:<?php  echo ( $articulo->ESTADO_ARTICULO == 1 ) ? "true" : "false"?>
        });
    });
</script>
