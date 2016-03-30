<h1>Submarca</h1>
<?php echo form_open($accion, 'id="submarca-form"', $ocultos) ?>
<?php $inputData = array('name' => 'descripcion',
    'id' => 'descripcion',
    'value' => $submarca->DETALLE_SUBMARCA,
    'size' => 20,
    'max' => 20
); ?>
Descripcion : <?php echo form_input($inputData) ?>
<br/>
<?php $inputData = array('name' => 'alias',
    'id' => 'alias',
    'value' => $submarca->ALIAS_SUBMARCA,
    'size' => 10,
    'max' => 9
); ?>
Nombre para el Producto: <?php echo form_input($inputData) ?>
<br/>
<?php echo form_dropdown('marca', $marcaSel, $submarca->ID_MARCA, 'id="marca"'); ?>
<br/>
<div id="otraMarca" class="boton"><span class="icono" style="float: left; margin-right: .3em;"></span>Marca con el mismo
    Nombre ( <span id="misma"></span> )
</div>
<br/>
<div id="estado">
    <?php echo form_label('Activo ', 'estado1'); ?><?php echo form_radio('estado', '1', ($submarca->ESTADO_SUBMARCA == 1) ? true : false, 'id="estado1"') ?>
    <?php echo form_label('Suspendido ', 'estado2'); ?><?php echo form_radio('estado', '0', ($submarca->ESTADO_SUBMARCA == 0) ? true : false, 'id="estado2"') ?>
</div>
<br/>
<?php if ($cancelar == "html"): ?>
    <?php echo anchor('articulos/submarcas', 'Cancelar', 'class="boton" id="botCancelar"'); ?>
<?php endif; ?>
<div id="botSave" class="boton">Guardar</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#estado").buttonset();
        $(".boton").button();
        $(".icono").addClass('ui-icon');
        $(".icono").addClass('ui-icon-circle-close');
        $("#misma").html('NO');
        <?php if ($cancelar != "html"):?>
        $("#submarca-form #botSave").click(function () {
            var url = $("#submarca-form").attr('action');
            var descripcion = $("#descripcion").val();
            var alias = $("#alias").val();
            var otra = $("#misma").html();
            var marca = $("#marca option:selected").val();
            var estado = $("[name=estado]:checked").val();
            valor = $(this).parent().parent().attr('id');
            imprimo = $(this).parent().parent().parent().attr('id');
            valor = "#" + valor;
            $.post(url, {
                descripcion: descripcion,
                alias: alias,
                marca: marca,
                otra: otra,
                estado: estado
            }, function (data) {
                $(valor).dialog('close');
            }, 'html');
        });
        <?php else:?>
        $("#botSave").click(function () {
            $("#submarca-form").submit();
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
        $("#otraMarca").click(function () {
            $("#otraMarca > .icono").removeClass('ui-icon-circle-close');
            $("#otraMarca > .icono").addClass('ui-icon-circle-check');
            $("#misma").html('SI');
        });
    });
</script>
