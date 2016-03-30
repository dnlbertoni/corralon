<h1>Marca</h1>
<?php echo form_open($accion, 'id="marca-form"', $ocultos) ?>
<?php $inputData = array('name' => 'descripcion',
    'id' => 'descripcion',
    'value' => $marca->DETALLE_MARCA,
    'size' => 20,
    'max' => 20
); ?>
Descripcion : <?php echo form_input($inputData) ?>
<br/>
<?php $inputData = array('name' => 'alias',
    'id' => 'alias',
    'value' => $marca->ABRE_MARCA,
    'size' => 10,
    'max' => 9
); ?>
Nombre para el Producto: <?php echo form_input($inputData) ?>
<br/>
<?php if ($cancelar == "html"): ?>
    <?php echo anchor('articulos/marcas', 'Cancelar', 'class="boton" id="botCancelar"'); ?>
<?php endif; ?>
<div id="botSave" class="boton">Guardar</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#estado").buttonset();
        $(".boton").button();
        <?php if ($cancelar != "html"):?>
        $("#botSave").click(function () {
            var url = $("#marca-form").attr('action');
            var descripcion = $("#descripcion").val();
            var alias = $("#alias").val();
            var estado = $("[name=estado]:checked").val();
            valor = $(this).parent().parent().attr('id');
            valor = "#" + valor;
            $.post(url, {descripcion: descripcion, alias: alias}, function (data) {
                $(valor).dialog('close');
            }, 'html');
        });
        <?php else:?>
        $("#botSave").click(function () {
            $("#marca-form").submit();
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
