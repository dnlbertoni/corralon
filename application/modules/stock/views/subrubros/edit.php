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
            <h1>
                Subrubro <?php echo ( $subrubro->ID_SUBRUBRO == "" ) ? "Nuevo" : "( " . $subrubro->ID_SUBRUBRO . " )" ?></h1>
            <?php echo form_open ( $accion, 'id="subrubro-form"' ) ?>
            <?php echo form_hidden ( 'ID_SUBRUBRO', $subrubro->ID_SUBRUBRO ); ?>
            Descripcion
            : <?php echo form_input ( 'DESCRIPCION_SUBRUBRO', $subrubro->DESCRIPCION_SUBRUBRO, 'class="form-control"' ) ?>
            <br/>
            Alias: <?php echo form_input ( 'ALIAS_SUBRUBRO', $subrubro->ALIAS_SUBRUBRO, 'class="form-control"' ) ?>
            <br/>
            Cantx
            bulto: <?php echo form_input ( 'CANTXBULTO_SUBRUBRO', $subrubro->CANTXBULTO_SUBRUBRO, 'class="form-control"' ) ?>
            <br/>
            <div>
                Rubro:<?php echo form_dropdown ( 'ID_RUBRO', $rubrosSel, $subrubro->ID_RUBRO ); ?>
            </div>
            <br/>

            <div>
                <input type="hidden" value="0" name="ESTADO_SUBRUBRO"/>
                <?php echo form_checkbox ( 'ESTADO_SUBRUBRO', 1, ( $subrubro->ESTADO_SUBRUBRO == 1 ) ? true : false, 'id="estadoID"' ) ?>
            </div>
            <br/>
            <div>
                <?php echo anchor ( 'stock/subrubros', 'Cancelar', 'class="btn btn-danger"' ); ?>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
            <?php echo form_close (); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#estadoID").bootstrapSwitch({
            onText: "Si",
            offText: "No",
            labelText: "Activo",
            onColor: "success",
            offColor: "danger",
            state:<?php  echo ( $subrubro->ESTADO_SUBRUBRO == 1 ) ? "true" : "false"?>
        });
        /*
         $("#subrubro-form").submit(function(e){
         e.preventDefault();
         valor=$(this).serialize();
         alert(valor);
         })2
         */
    })
</script>
