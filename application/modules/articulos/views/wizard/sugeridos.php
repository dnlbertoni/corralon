<div class="ui-widget">
    <h2 class="ui-widget-header"><span class="ui-icon ui-icon-circle-plus" style="display: inline-block;"></span>
        Sugeridos</h2>
    <div class="ui-widget-content">
        <p>&nbsp;</p>
        <div id="sug">
            <?php
            $aux = false;
            $primero = true;
            ?>
            <?php foreach ($sugeridos as $s): ?>
                <?php if ($aux != $s->{$idMaster}): ?>
                    <?php if (!$primero): ?>
                        <?php echo "</p></div>\n" ?>
                    <?php endif; ?>
                    <h3><?php echo $s->{$nombreMaster} ?>&nbsp;<?php echo $s->acierto ?>%</h3>
                    <?php echo "<div><p>\n"; ?>
                    <?php
                    $aux = $s->{$idMaster};
                    $primero = false;
                    ?>
                    <div <?php echo "id='" . $s->{$idMov} . "'" . 'class="boton"' ?>><?php echo $s->{$nombreMov} ?></div>
                <?php else: ?>
                    <div <?php echo "id='" . $s->{$idMov} . "'" . 'class="boton"' ?>><?php echo $s->{$nombreMov} ?></div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php // ya salimos hay que acomodar el div
            echo "</p></div>";
            ?>
        </div>
        <p>&nbsp;</p>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#sug").css('width', '70%');
        $("#sug").css('margin', 'auto');
        $("#sug").accordion({
            collapsible: true,
            icons: {header: "ui-icon-circle-plus", activeHeader: "ui-icon-circle-minus"},
            heightStyle: "auto"
        });
        $("h3").css('padding', '5px 5px 5px 25px');
        $(".boton").button();
        $(".boton").click(function () {
            id = $(this).attr('id');
            texto = $(this).text();
            $("#asignar #resultado #codigo").text(id);
            $("#asignar #resultado #nombre").text(texto);
        });
    });
</script>
