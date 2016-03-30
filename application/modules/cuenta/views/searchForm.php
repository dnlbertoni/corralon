<?php
$urlListadoCuentas = "'" . base_url() . 'index.php/cuenta/searchAjaxDo' . "'";
$destino = "'" . $target . "'";
$dataInput = array('name' => 'nombreTXT',
    'id' => 'nombreTXT'
);
?>
<?php echo form_open('cuenta/searchAjaxDo', 'id="cuentas-form"') ?>
Cuenta: <?php echo form_input($dataInput); ?>
<?php echo form_close(); ?>

<div id="datosCuentas"></div>

<script>
    $(document).ready(function () {
        $("#nombreTXT").focus();
        $('#cuentas-form').submit(function (e) {
            e.preventDefault();
        });
        $("#nombreTXT").bind('keyup', function (e) {
            e.preventDefault();
            pagina = <?php echo $urlListadoCuentas;?>;
            nombreTXT = $("#nombreTXT").val();
            destino = <?php echo $destino?>;
            $.post(pagina, {nombreTXT: nombreTXT, destino: destino}, function (data) {
                    $("#datosCuentas").html(data)
                }
            );
        });
    });
</script>
