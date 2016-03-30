<?php if (count($cuentas) > 0): ?>
    <table>
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Vta</th>
            <th>&nbsp;</th>
        </tr>
        <?php if ($solo): ?>
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
            tipcom = (valor == 1) ? 1 : 2;
            $("#idCuenta").html(valor);
            $("#nombreCuenta").html(nombre);
            $("#condVta").html(ctacte);
            $("#tipcom_id").val(tipcom);
            $("#tipcom_nom").html("Factura");
            claseCondVta = (ctacte == "CtaCte") ? "ui-state-error" : "ui-state-default";
            $("#condVta").removeAttr('class');
            $("#condVta").addClass(claseCondVta);
            $("#cuenta").val(valor);
            pagina = $("#paginaActualizoCliente").val();
            puesto = $("#puesto").val();
            id_temporal = $("#id_tmpencab").val();
            cuenta = $("#cuenta").val();
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
            $("#cliente").dialog('destroy');
            $("#codigobarra").addClass('focus');
            $("#codigobarra").focus();
        });
    });
</script>
