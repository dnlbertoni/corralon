<div class="col-sm-12 text-center">
    <h2>Imprimiendo <?php echo $Imprimo ?></h2>
    <h3>Por Favor espere...</h3>
    <div id="carga"><i class="fa fa-spin fa-spinner fa-3x"></i></div>
    <input type="hidden" id="tipcomAjax" value="<?php echo $tipcom_id ?>"/>
    <input type="hidden" id="importeAjax" value="<?php echo ( isset( $importe ) ) ? $importe : 0; ?>"/>
</div>
<script language="Javascript">
    $(document).ready(function () {
        pagina = "<?php echo base_url () . 'index.php/caja/', $accion ?>";
        importe = $('#importeAjax').val();
        tipcom_id = $('#tipcomAjax').val();
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                file: "<?php echo $file ?>",
                tipcom: tipcom_id,
                importe: importe,
                DNF: "<?php echo ( isset( $DNF ) ) ? $DNF : 0; ?>",
                idencab: "<?php echo $idencab ?>"
            }),
            dataType: "html",
            async: true,
            success: function (msg) {
                dialog.close();
            }
        }).responseText;
    });
</script>
