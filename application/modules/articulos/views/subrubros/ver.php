<h1>Subrubros</h1>
<?php echo form_open($accionSub, 'id="subrubro-form"', $ocultos) ?>
<?php $inputData = array('name' => 'descripcion',
    'id' => 'descripcion',
    'value' => $subrubro->DESCRIPCION_SUBRUBRO,
    'size' => 20,
    'max' => 20
); ?>
Descripcion : <?php echo form_input($inputData) ?>
<br/>
<?php $inputData = array('name' => 'alias',
    'id' => 'alias',
    'value' => $subrubro->ALIAS_SUBRUBRO,
    'size' => 10,
    'max' => 9
); ?>
Nombre para el Producto: <?php echo form_input($inputData) ?>
<br/>
<?php echo form_dropdown('rubro', $rubroSel, $subrubro->ID_RUBRO, 'id="rubro"'); ?>
<br/>
<div id="otroRubro" class="boton"><span class="icono" style="float: left; margin-right: .3em;"></span>Rubro con el mismo
    Nombre ( <span id="otro"></span> )
</div>
<br/>
<div id="estadoSubrubro">
    <?php echo form_label('Activo ', 'estado1'); ?><?php echo form_radio('estado', '1', ($subrubro->ESTADO_SUBRUBRO == 1) ? true : false, 'id="estado1"') ?>
    <?php echo form_label('Suspendido ', 'estado2'); ?><?php echo form_radio('estado', '0', ($subrubro->ESTADO_SUBRUBRO == 0) ? true : false, 'id="estado2"') ?>
</div>
<br/>
<?php if ($cancelar == "html"): ?>
    <?php echo anchor('articulos/subrubros', 'Cancelar', 'class="boton"'); ?>
<?php endif; ?>
<div id="botSave" class="boton">Guardar</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#estadoSubrubro").buttonset();
        $(".boton").button();
        $(".icono").addClass('ui-icon');
        $(".icono").addClass('ui-icon-circle-close');
        $("#otro").html('NO')
        <?php if ($cancelar != "html"):?>
        $("#subrubro-form #botSave").click(function () {
            var url = $("#subrubro-form").attr('action');
            var descripcion = $("#subrubro-form #descripcion").val();
            var alias = $("#subrubro-form #alias").val();
            var otro = $("#subrubro-form #otro").html();
            var rubro = $("#subrubro-form #rubro option:selected").val();
            var estado = $("#subrubro-form [name=estado]:checked").val();
            valor = $(this).parent().parent().attr('id');
            imprimo = $(this).parent().parent().parent().attr('id');
            valor = "#" + valor;
            $.post(url, {
                descripcion: descripcion,
                alias: alias,
                rubro: rubro,
                otro: otro,
                estado: estado
            }, function (data) {
                $(valor).dialog('close');
            }, 'html');
        });
        <?php else:?>
        $("#botSave").click(function () {
            $("#subrubro-form").submit();
        });
        <?php endif;?>
        $("#alias").bind('keyup', function () {
            if ($("#alias").val().length < 10) {
                $("#alias").css('background-color', 'green');
            } else {
                $("#alias").css('background-color', 'red');
            }
            ;
        });
    });
</script>
