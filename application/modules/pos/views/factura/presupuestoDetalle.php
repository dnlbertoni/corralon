<?php if (!$existe): ?>
    <div id="error">
        <div class="ui-state-error ui-corner-all">
            <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
            El articulo <?php echo $codigobarra ?> no esta cargado en el sistema
        </div>
    </div>
    <script>
        $('#error').dialog({
            autoOpen: true,
            resizable: false,
            width: 600,
            modal: true,
            buttons: {
                "Por favor ingresar el Producto": function () {
                    $(this).dialog("close");
                }
            }
        });
        $("#error").focus();
    </script>
<?php endif; ?>
<?php if ($valorCero): ?>
    <div class="ui-state-error ui-corner-all">
        <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
        El articulo <?php echo $codigobarra ?> tiene VALOR CERO
    </div>
<?php endif; ?>
<table width="95%" border="0" class="brief">
    <tr>
        <td id="tipcom_nom">Ticket</td>
        <td>Nro:<?php printf("%04.0f - %08.0f", $puesto, $id_tmpencab); ?></td>
        <td><?php echo $fechoy; ?></td>
    </tr>
    <tr>
        <td>Cliente</td>
        <td>(<span id="idCuenta"><?php echo $idCuenta ?></span>)<span
                id="nombreCuenta"><?php echo $nombreCuenta ?></span</td>
        <th rowspan="3" class="importeTotal"><?php printf("$%01.2f", floatval($totales->Total)); ?></th>
    </tr>
    <tr>
        <td>Condicion Venta</td>
        <td id="condVta" class="ui-state-default" align="center"><?php echo $condVta ?>
        <td>
    </tr>
    <tr>
        <td>Total Bultos</td>
        <th><?php echo intval($totales->Bultos) ?></th>
    </tr>
</table>
<br/>
<?php if ($Articulos): ?>
    <table width="95%" cellpadding="3" cellspacing="3">
        <tr>
            <th width="20%">Codigo</th>
            <th width="50%">Descripcion</th>
            <th width="5%">Cantidad</th>
            <th width="10%">Precio</th>
            <th colspan="2">Importe</th>
        </tr>
        <?php
        $total = 0;
        $renglon = 0;
        foreach ($Articulos as $articulo) {
            ?>
            <?php if ($renglon == 0): ?>
                <tr class="ui-state-default" >
            <?php else: ?>
                <tr>
            <?php endif; ?>
            <td><?php echo $articulo->Codigobarra; ?> </td>
            <td><?php echo $articulo->Nombre ?></td>
            <td><?php echo $articulo->Cantidad ?></td>
            <td align="right"><?php printf("$%01.2f", $articulo->Precio); ?></td>
            <td align="right"><?php printf("$%01.2f", $articulo->Importe) ?></td>
            <td>
                <div id="<?php echo $articulo->codmov ?>" class="botdel">Quitar Articulo</div>
            </td>
            </tr>
            <?php
            $total += $articulo->Importe;
            $renglon++;
        } ?>
        <tr>
            <th colspan="4" align="right">Total --&gt; </th>
            <th align="right" colspan="2"><?php printf("$%01.2f", $total); ?></th>
        </tr>
    </table>
<?php endif; ?>

<script>
    $(".ui-state-error").css('font-size', '24px');
    $(".ui-state-error").css('height', '50px');
    $(".ui-state-error").css('vertical-align', 'middle');
    $('.importeTotal').css('font-size', '36px');
    $('td').css('font-size', '20px');
    $(document).ready(function () {
        $(".botdel").button({icons: {primary: 'ui-icon-circle-minus'}, text: false});
        $(".botdel").click(function () {
            id = $(this).attr("id");
            delArt(id);
        });
        if ($("#condVta").html() == "Contado") {
            $("#condVta").removeClass('ui-state-error');
            $("#condVta").addClass('ui-state-default');
        } else {
            $("#condVta").removeClass('ui-state-default');
            $("#condVta").addClass('ui-state-error');
        }
    });
    function delArt(codmov) {
        pagina = $("#paginaBorroArticulo").val();
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({codmov: codmov}),
            dataType: "html",
            async: true,
            //beforeSend: function(){$("#loading").fadeIn();},
            success: function (msg) {
                $("#brief").html(msg);
                $("#codigobarra").val('');
                $("#codigobarra").focus();
                $("#loading").fadeOut(100);
            }
        }).responseText;
    }
</script>
