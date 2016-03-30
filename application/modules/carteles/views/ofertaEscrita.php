<?php
/*
 * Vista para la funcion Navidad que buscar los articulos, los lista y despues los envia para que se impriman en php
 */

?>
<div class="post">
    <h1>Carteles Escritos</h1>
    <h2>Ideal hasta 5 lineas escritas</h2>
    <?php echo form_label('Linea (max 20 caracteres) :', 'linea'); ?>
    <?php echo form_input('linea', '', 'id="linea"'); ?>
    <div>Restan <span id="restoLinea">20</span> caracteres</div>
    <div id="LinAdd">Agrego</div>
    <br/>
    <?php echo form_open($accion, 'id="Print"'); ?>
    <?php echo form_label('Titulo del cartel (max 20 caracteres) :', 'linea'); ?>
    <?php echo form_input('titulo', '', 'id="titulo" max="20"'); ?>
    <br/>
    <?php echo form_label('Fecha', 'fecha'); ?>
    <?php echo form_input('fecha', $fecha, 'id="fecha"'); ?>
    <?php echo form_label('Copias', 'copias'); ?>
    <?php echo form_dropdown('copias', array('1', '2', '3'), 1, 'id="copias"'); ?>
    <div id="lineas"></div>
    <?php echo form_submit('Imprimir', 'Imprimir') ?>
    <?php echo form_close() ?>
</div>
<script>
    $(document).ready(function () {
        $("#linea").focus();
        $("#LinAdd").button({icons: {primary: 'ui-icon-circle-plus'}});
        $("#linea").bind('keydown', function () {
            $(this).val($(this).val().substr(0, 20));
            var usado = $(this).val().length;
            var resto = 20 - usado;
            $("#restoLinea").text(resto);
        });
        $("#LinAdd").click(function () {
            cantidad = $("#lineas > div").length + 1;
            valor = $("#linea").val().toUpperCase();
            var msg = "<div id='linea_" + cantidad + "'><strong>Linea " + cantidad + " : </strong>" + valor + "</div>";
            var msg2 = "<input type='hidden' name='linea_" + cantidad + "' value='" + valor + "' />";
            $("#lineas").append(msg);
            $("#lineas").append(msg2);
            $("#linea").val('');
            $("#linea").focus();
        });
        $("#fecha").datepicker({
            altField: "#fecha",
            altFormat: 'dd/mm/yy',
            appendText: "dd/mm/aaaa",
            showButtonPanel: true,
            showOn: "both"
        });
    });
</script>
