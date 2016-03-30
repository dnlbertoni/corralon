<?php
$urlListadoSubmarcas = "'" . base_url() . 'index.php/articulos/submarcas/searchAjaxDo' . "'";
$destino = "'" . $target . "'";
$dataInput = array('name' => 'submarcaTXT',
    'id' => 'submarcaTXT'
);
?>
<?php echo form_open('articulos/submarcas/searchAjaxDo', 'id="submarcas-form"') ?>
Submarca: <?php echo form_input($dataInput); ?>
<?php echo form_close(); ?>

<div id="datosSubmarcas"></div>

<script>
    $(document).ready(function () {
        $("#submarcaTXT").focus();
        $('#submarcas-form').submit(function (e) {
            e.preventDefault();
        });
        $("#submarcaTXT").bind('keyup', function (e) {
            e.preventDefault();
            pagina = <?php echo $urlListadoSubmarcas;?>;
            submarcaTXT = $("#submarcaTXT").val();
            destino = <?php echo $destino?>;
            $.post(pagina, {submarcaTXT: submarcaTXT, destino: destino}, function (data) {
                    $("#datosSubmarcas").html(data)
                }
            );
        });
    });
</script>
