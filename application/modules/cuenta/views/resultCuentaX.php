<?php if (count($cuentas) > 0): ?>
    <table>
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th colspan="2">Vta</th>
            <th>&nbsp;</th>
        </tr>
        <?php if ($directo): ?>
            <tr>
                <td>
                    <div id="id_<?php echo $cuentas->id ?>"><?php echo $cuentas->id ?></div>
                </td>
                <td>
                    <div id="nom_<?php echo $cuentas->id ?>"><?php echo $cuentas->nombre ?></div>
                </td>
                <td>
                    <div
                        id="cta_<?php echo $cuentas->id ?>"><?php echo ($cuentas->ctacte == 1) ? "CtaCte" : "Contado"; ?></div>
                </td>
                <td>
                    <div id="let_<?php echo $cuentas->id ?>"><?php echo $cuentas->letra; ?></div>
                </td>
                <td>
                    <div id="bot_<?php echo $cuentas->id ?>" class="boton">Seleccionar</div>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($cuentas as $cuenta): ?>
                <tr>
                    <td>
                        <div id="id_<?php echo $cuenta->id ?>"><?php echo $cuenta->id ?></div>
                    </td>
                    <td>
                        <div id="nom_<?php echo $cuenta->id ?>"><?php echo $cuenta->nombre ?></div>
                    </td>
                    <td>
                        <div
                            id="cta_<?php echo $cuenta->id ?>"><?php echo ($cuenta->ctacte == 1) ? "CtaCte" : "Contado"; ?></div>
                    </td>
                    <td>
                        <div id="let_<?php echo $cuenta->id ?>"><?php echo $cuenta->letra; ?></div>
                    </td>
                    <td>
                        <div id="bot_<?php echo $cuenta->id ?>" class="boton">Seleccionar</div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
<?php else: ?>
    <div class="ui-widget ui-state-error">No existe una cuenta con ese Codigo</div>
<?php endif; ?>
<input type="hidden" id="paginaActualizoCliente"
       value="<?php echo base_url(), 'index.php/pos/factura/cambioCuenta'; ?>"/>
<script language="Javascript">
    $(document).ready(function () {
        $('.boton').button();
        $('.boton').click(function () {
            str = $(this).attr('id').split('_');
            aux = str[1];
            nomaux = "#nom_" + aux;
            nombre = $(nomaux).html();
            nomaux = "#cta_" + aux;
            ctacte = $(nomaux).html();
            nomaux = "#id_" + aux;
            valor = $(nomaux).html();
            nomaux = "#let_" + aux;
            letra = $(nomaux).html();
            tipcom = (valor == 1) ? 1 : 2;
            resultado = "";
            resultado += '<span class="codigo">' + valor + '</span>';
            resultado += '<span class="nombre">' + nombre + '</span>';
            resultado += '<span class="ctacte">' + ctacte + '</span>';
            resultado += '<span class="tipcom">' + tipcom + '</span>';
            resultado += '<span class="letra"> ' + letra + '</span>';
            $("#cuentaAjax").html(resultado);
            pagina = $("#paginaActualizoCliente").val();
            puesto = $("#puesto").val();
            id_temporal = $("#id_tmpencab").val();
            cuenta = valor;
            $.ajax({
                url: pagina,
                contentType: "application/x-www-form-urlencoded",
                global: false,
                type: "POST",
                data: ({
                    puesto: puesto,
                    cuenta: cuenta,
                    id_tmpencab: id_temporal
                }),
                dataType: "html",
                async: true,
                success: function (msg) {
                    $("#datos").html(msg);
                }
            }).responseText;
            $("#cliente").dialog('close');
        });
        <?php if($directo):?>
        $(".boton").click();
        <?php endif;?>
    });
</script>
