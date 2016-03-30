<?php echo form_open('articulos/cambioPrecioDo', 'id="cambioPrecio-form"'); ?>
<?php echo form_label($articulo->descripcion, 'precio'); ?>
<?php echo form_input('precio', $articulo->precio, 'id="precioNew" size="5"'); ?>
<input type="hidden" name="idArt" id="idArt" value="<?php echo $idArt ?>"/>
<?php echo form_submit('Cambiar', 'Cambiar'); ?>
<?php echo form_close(); ?>
<script>
    $(document).ready(function () {
        $("#precioNew").addClass('focus');
        $("#precioNew").focus();
        $("#cambioPrecio-form").submit(function (e) {
            e.preventDefault();
            pagina = $("#cambioPrecio-form").attr('action');
            idArt = $("#idArt").val();
            precio = $("#precioNew").val();
            $.ajax({
                url: pagina,
                contentType: "application/x-www-form-urlencoded",
                global: false,
                type: "POST",
                data: ({
                    id: idArt,
                    precio: precio
                }),
                dataType: "html",
                async: true,
                success: function () {
                    $("#precio").dialog('close');
                }
            }).responseText;
        });
    });
</script>
