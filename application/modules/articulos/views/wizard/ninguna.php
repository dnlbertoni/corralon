<div class="ui-widget">
    <h2 class="ui-widget-header">
        <span class="ui-icon ui-icon-circle-plus" style="display: inline-block;"></span>Si No existe
    </h2>
    <?php echo form_open($accionMov, "id='add-mov'") ?>
    <div class="ui-widget-content">
        <div>
            <h2>Empresa que Fabrica</h2>
            <?php echo form_dropdown($maestro, $maestroSel, 'S', 'id="maestro"'); ?>
            <label>Si no existe:</label>
            <?php echo form_input($maestroAux, '', 'id="auxiliar"') ?>
            <?php echo '<input type="hidden"  name="' . $otro . '" id="otro" value="NO" />' ?>
            <?php echo form_hidden('estado', 1); ?>
        </div>
        <div>
            <h2>Marca que figura en el Envase</h2>
            Nombre:<?php echo form_input($descripcion, '', 'id="descripcion"'); ?>
            <br/>
            Nombre para el articulo Abreviado: <?php echo form_input($alias, '', 'id="alias"'); ?>
        </div>
        <div id='BOTGuardar'>Agregar</div>
    </div>
    <?php echo form_close(); ?>
    <div id='resultadoNuevo'></div>
</div>
<script>
    $(document).ready(function () {
        $("#descripcion").keyup(function (e) {
            $("#alias").val($(this).val());
        });
        $("#add-mov").submit(function (e) {
            e.preventDefault();
        });
        $("#BOTGuardar").button({icons: {primary: 'ui-icon-disk'}});
        $("#BOTGuardar").click(function () {
            url = $("#add-mov").attr('action');
            if ($("#maestro").val() === "O") {
                $("#otro").val("SI");
            } else {
                valor = $("#maestro").val();
                $("#auxiliar").val(valor);
            }
            datos = $("#add-mov").serialize();
            $.ajax({
                type: "POST",
                url: url,
                data: datos,
                success: function (data) {
                    $("#resultadoNuevo").append(data);
                    valor = $("#resultadoNuevo>.codigo").text();
                    $("#asignar>#resultado>#wizard>#codigo").text(valor);
                    valor = $("#resultadoNuevo>.nombre").text();
                    $("#asignar>#resultado>#wizard>#nombre").text(valor);
                }
            });
        });
    });
</script>