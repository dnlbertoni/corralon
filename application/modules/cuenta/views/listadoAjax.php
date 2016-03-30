<?php $destino = "'#" . $target . "'"; ?>
<?php if ($vacio): ?>
    <?php echo $nombreTXT ?>, no esta grabada <span id="botCrearCuenta" class="boton">Crear una nueva Cuenta</span>
    <div id="nuevaCuenta"></div>
<?php else: ?>
    <table>
        <thead>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>CUIT</th>
        <th>Comprobante</th>
        <th>&nbsp;</th>
        </thead>
        <?php if ($byId): ?>
            <tr>
                <td><?php echo $cuentas->id ?></td>
                <td id="nom_<?php echo $cuentas->id ?>"><?php echo $cuentas->nombre ?></td>
                <td id="cuit_<?php echo $cuentas->id ?>"><?php echo $cuentas->cuit ?></td>
                <td id="letra_<?php echo $cuentas->id ?>"><?php echo $cuentas->letra ?></td>
                <td>
                    <div id="id_<?php echo $cuentas->id ?>" class="boton">Seleccionar</div>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($cuentas as $cuenta): ?>
                <tr>
                    <td><?php echo $cuenta->id ?></td>
                    <td id="nom_<?php echo $cuenta->id ?>"><?php echo $cuenta->nombre ?></td>
                    <td id="cuit_<?php echo $cuenta->id ?>"><?php echo $cuenta->cuit ?></td>
                    <td id="letra_<?php echo $cuenta->id ?>"><?php echo $cuenta->letra ?></td>
                    <td>
                        <div id="id_<?php echo $cuenta->id ?>" class="boton">Seleccionar</div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
<?php endif; ?>
<script language="javascript">
    $(document).ready(function () {
        $('.boton').button();
        $('div [id^=id_]').click(function () {
            aux = $(this).attr('id');
            valor = aux.split('_');
            destino = <?php echo $destino ?>;
            nombreAux = $(this).parent().parent().parent().parent().parent().parent().attr('id');
            nombreDialogo = "#" + nombreAux;
            nombre = '#nom_' + valor[1];
            cuit = '#cuit_' + valor[1];
            letra = '#letra_' + valor[1];
            data = "<span class='codigo'>" + valor[1] + "</span>" + " - " + "<span class='nombre'>" + $(nombre).html() + "</span>" + "( " + $(cuit).html() + " )" + "<span class='letra'>" + $(letra).html() + "</span>";
            $(destino).html(data);
            $(nombreDialogo).dialog("close");
        });
        $("#botCrearCuenta").click(function () {
            creoCuenta();
        });
    });

    function creoCuenta() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Agrego Cuenta",
            draggable: true,
            resizeable: true,
            close: function () {
                valor = $("#nuevaCuenta > .codigo").html()
                $('#nuevaCuenta').dialog("destroy");
            }
        };
        $("#nuevaCuenta").dialog(dialogOpts);   //end dialog
        $("#nuevaCuenta").load(<?php echo $targetCuenta;?>, [], function () {
                $("#nuevaCuenta").dialog("open");
            }
        );
    }
</script>
