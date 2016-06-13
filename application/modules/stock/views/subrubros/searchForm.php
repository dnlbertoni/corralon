<?php
$urlListadoRubros = "'" . base_url () . 'index.php/articulos/subrubros/searchAjaxDo' . "'";
$destino = "'" . $target . "'";
$dataInput = array ( 'name' => 'subrubroTXT',
    'id' => 'subrubroTXT'
);
?>
<?php echo form_open ( 'articulos/subrubro/searchAjaxDo', 'id="subrubroSearch-form"' ) ?>
Submarca: <?php echo form_input ( $dataInput ); ?>
<?php echo form_close (); ?>

<div id="datosRubros"></div>

<script>
    $(document).ready(function () {
        $("#subrubroTXT").focus();
        $('#subrubroSearch-form').submit(function (e) {
            e.preventDefault();
        });
        $("#subrubroTXT").bind('keyup', function (e) {
            e.preventDefault();
            pagina = <?php echo $urlListadoRubros;?>;
            subrubroTXT = $("#subrubroTXT").val();
            destino = <?php echo $destino?>;
            $.post(pagina, {subrubroTXT: subrubroTXT, destino: destino}, function (data) {
                    $("#datosRubros").html(data)
                }
            );
        });
    });
</script>
