<div class="ui-widget">
    <h2 class="ui-widget-header"><span class="ui-icon ui-icon-circle-plus" style="display: inline-block;"></span>Resto
    </h2>
    <div class="ui-widget-content">
        <p>&nbsp;</p>
        <table id="resto">
            <thead>
            <tr>
                <th><?php echo $nombreMaster ?></th>
                <th><?php echo $nombreMov ?></th>
            </tr>
            <tr>
                <th colspan="2">Buscar: <input type="text" id="buscador"/></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $aux = false;
            $primero = true;
            $col = 0;
            $row = 0;
            ?>
            <?php foreach ($todos as $s): ?>
                <?php if ($row == 0): ?>
                    <tr>
                <?php endif; ?>
                <td><?php echo $s->{$nombreMaster} ?></td>
                <td>
                    <div <?php echo "id='" . $s->{$idMov} . "'" . 'class="boton"' ?> ><?php echo $s->{$nombreMov} ?></div>
                </td>
                <?php
                $row += 3;
                if ($row == 3) {
                    $row = 0;
                } ?>
                <?php if ($row == 0): ?>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php echo "</div></div>"; ?>
            </tbody>
        </table>
        <p>&nbsp;</p>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#resto >tbody>tr>td").css('padding', '5px');
        jQuery("#buscador").keyup(function () {
            if (jQuery(this).val() != "") {
                jQuery("#resto tbody>tr").hide();
                jQuery("#resto td:contiene-palabra('" + jQuery(this).val() + "')").parent("tr").show();
            }
            else {
                jQuery("#resto tbody>tr").show();
            }
        });

        jQuery.extend(jQuery.expr[":"],
            {
                "contiene-palabra": function (elem, i, match, array) {
                    return (elem.textContent || elem.innerText || jQuery(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                }
            });
        $("h3").css('padding', '5px 5px 5px 25px');
        $(".boton").button();
        $(".boton").click(function () {
            nombre = $(this).attr('id');
            data = "<div>" + $(nombre).text() + "</div>";
            $("#asignar").append(data);
        });
    });
</script>
