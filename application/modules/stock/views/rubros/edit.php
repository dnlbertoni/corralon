<?php
/**
 * Created by PhpStorm.
 * User: dyf
 * Date: 21/05/2016
 * Time: 05:18 PM
 */
?>
<link href="/assets/css/bootstrap-switch.min.css" rel="stylesheet">
<script src="/assets/js/bootstrap-switch.min.js"></script>

<div class="section">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <h1>Rubro <?php echo ( $rubro->ID_RUBRO == "" ) ? "Nuevo" : "( " . $rubro->ID_RUBRO . " )" ?></h1>
            <?php echo form_open ( $accion, 'id="rubro-form"' ) ?>
            <?php echo form_hidden ( 'ID_RUBRO', $rubro->ID_RUBRO ); ?>
            Descripcion
            : <?php echo form_input ( 'DESCRIPCION_RUBRO', $rubro->DESCRIPCION_RUBRO, 'class="form-control"' ) ?>
            <br/>
            Alias: <?php echo form_input ( 'ALIAS_RUBRO', $rubro->ALIAS_RUBRO, 'class="form-control"' ) ?>
            <br/>
            <div>
                Unidad Medida<?php echo form_dropdown ( 'UNIDAD_RUBRO', $unidadSel, $rubro->UNIDAD_RUBRO ); ?>
            </div>
            <br/>

            <div>
                <?php echo form_checkbox ( 'ESTADO_RUBRO', $rubro->ESTADO_RUBRO, ( $rubro->ESTADO_RUBRO == 1 ) ? "checked" : "" ) ?>
            </div>
            <br/>
            <div>
                <?php echo anchor ( 'stock/rubros', 'Cancelar', 'class="btn btn-danger"' ); ?>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
            <?php echo form_close (); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("[name='ESTADO_RUBRO']").bootstrapSwitch({
            onText: "Si",
            offText: "No",
            labelText: "Activo",
            onColor: "success",
            offColor: "danger"
        })
    })
</script>