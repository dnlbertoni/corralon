<h2>Imprimiendo <?php echo $Imprimo ?></h2>
<h3>Por Favor espere...</h3>
<div id="carga"><?php echo Assets::image('loading.gif', array('alt' => "Loading...")); ?></div>
<input type="hidden" id="puestoAjax" value="<?php echo $puesto ?>"/>
<input type="hidden" id="idencabAjax" value="<?php echo $idencab ?>"/>
<input type="hidden" id="cuentaAjax" value="<?php echo $cuenta ?>"/>
<input type="hidden" id="tipcomAjax" value="<?php echo $tipcom_id ?>"/>
<input type="hidden" id="fileAjax" value="<?php echo $file ?>"/>
<input type="hidden" id="DNF" value="<?php echo (isset($DNF)) ? $DNF : 0; ?>"/>
<input type="hidden" id="importeAjax" value="<?php echo (isset($importe)) ? $importe : 0; ?>"/>
<input type="hidden" id="paginaAjax" value="<?php echo base_url() . 'index.php/pos/factura/', $accion ?>"/>
<script language="Javascript">
    $(document).ready(function () {
        pagina = $("#paginaAjax").val();
        puesto = $("#puestoAjax").val();
        id_temporal = $("#idencabAjax").val();
        file = $("#fileAjax").val();
        cuentaAjax = $('#cuentaAjax').val();
        importe = $('#importeAjax').val();
        dnf = $('#DNF').val();
        tipcom_id = $('#tipcomAjax').val();
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({
                file: file,
                puesto: puesto,
                cuentaAjax: cuenta,
                tipcom: tipcom_id,
                importe: importe,
                DNF: dnf,
                idencab: id_temporal
            }),
            dataType: "html",
            async: true,
            beforeSend: function () {
                $("#carga").fadeIn();
            },
            success: function (msg) {
                $("#carga").append(msg);
                $("#imprimo").dialog('close');
                $("#loading").fadeOut(100);
            }
        }).responseText;
    });
</script>
