<script>
    $(document).ready(function () {
        var BUTTON = $("td > div[id*='bot']");
        BUTTON.button();
        BUTTON.click(function () {
            valor = $(this).attr('id');
            valor = valor.substr(4);
            strNombre = "#nom_" + valor;
            strLetra = "#letra_" + valor;
            nombre = $(strNombre).text().trim();
            letra = $(strLetra).text().trim();
            $('#cuenta_id').val(valor);
            $("#cuenta_nombre").val(nombre);
            $("#letra").val(letra);
            $('dialogo').colorbox.close();
        });
    });
</script>
<table>
    <?php foreach ($datos as $cuenta): ?>
        <tr>
            <td>
                <?php echo $cuenta->id; ?>
            </td>
            <td id="nom_<?php echo $cuenta->id ?>">
                <?php echo $cuenta->nombre; ?>
            </td>
            <td>
                <?php echo $cuenta->cuit; ?>
            </td>
            <td id="letra_<?php echo $cuenta->id ?>">
                <?php echo $cuenta->letra; ?>
            </td>
            <td>
                <div id='bot_<?php echo $cuenta->id ?>'>Seleccionar</div>
        </tr>
    <?php endforeach; ?>
</table>
<?php if ($byId): ?>
    <!-- ver para sacar el numero molesto -->
    <div id="tipcuenta"><?php echo $cuenta->tipo ?></div>
    <script>
        $(document).ready(function () {
            var tipcom = parseInt($("input[name*='tipcom_id']").val());
            var cuenta_tipo = parseInt($("#tipcuenta").text());
            if ((tipcom == 10) && (cuenta_tipo == 2))
                $("td > div[id*='bot']").click();
        });
    </script>
<?php endif;