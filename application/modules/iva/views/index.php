<div id="ventas" class="accordion" style="width: 48%; float:left">
    <?php
    $aux = false;
    $primero = true;
    ?>
    <?php foreach ($periven as $p): ?>
        <?php if ($aux != round(($p->periva / 100), 0)): ?>
            <?php if (!$primero): ?>
                <?php echo "</table>\n" ?>
                <?php echo "</p></div>\n" ?>
            <?php endif; ?>
            <h1>Ventas - <?php echo round(($p->periva / 100), 0) ?></h1>
            <?php echo "<div><p>\n"; ?>
            <?php echo "<table>\n" ?>
            <?php
            $aux = round(($p->periva / 100), 0);
            $primero = false;
            ?>
            <tr>
                <td><strong><?php echo $p->periva; ?></strong></td>
                <td>( <?php echo $p->cantidad; ?> )</td>
                <td><?php echo money_format('%= (#10.2n', $p->total); ?></td>
                <td>
                    <?php echo anchor('iva/PeriodotoPrint/1/' . $p->periva, "Imprimir Libro", 'class="botPrintLibro" target="_blank"'); ?>
                    <?php echo anchor('iva/PeriodotoPdf/1/' . $p->periva, "Ver Libro", 'class="botVerLibro" target="_blank"'); ?>
                </td>
                <td>
                    <?php echo anchor('iva/PercepcionestoPdf/' . $p->periva, "Ver Percepciones", 'class="botVerPercep" target="_blank"'); ?>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td><strong><?php echo $p->periva; ?></strong></td>
                <td>( <?php echo $p->cantidad; ?> )</td>
                <td><?php echo money_format('%= (#10.2n', $p->total); ?></td>
                <td>
                    <?php echo anchor('iva/PeriodotoPrint/1/' . $p->periva, "Imprimir Libro", 'class="botPrintLibro" target="_blank"'); ?>
                    <?php echo anchor('iva/PeriodotoPdf/1/' . $p->periva, "Ver Libro", 'class="botVerLibro" target="_blank"'); ?>
                </td>
                <td>
                    <?php echo anchor('iva/PercepcionestoPdf/' . $p->periva, "Ver Libro", 'class="botVerPercep" target="_blank"'); ?>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php // ya salimos hay que acomodar el div
    echo "</table>\n";
    echo "</p></div>";
    ?>
</div>
<div id="compras" class="accordion" style="width: 48%; float:right">
    <?php
    $aux = false;
    $primero = true;
    ?>
    <?php foreach ($pericom as $p): ?>
        <?php if ($aux != round(($p->periva / 100), 0)): ?>
            <?php if (!$primero): ?>
                <?php echo "</table>\n" ?>
                <?php echo "</p></div>\n" ?>
            <?php endif; ?>
            <h1>Compras - <?php echo round(($p->periva / 100), 0) ?></h1>
            <?php echo "<div><p>\n"; ?>
            <?php echo "<table>\n" ?>
            <?php
            $aux = round(($p->periva / 100), 0);
            $primero = false;
            ?>
            <tr>
                <td><strong><?php echo $p->periva; ?></strong></td>
                <td>( <?php echo $p->cantidad; ?> )</td>
                <td><?php echo money_format('%= (#10.2n', $p->total); ?></td>
                <td>
                    <?php echo anchor('iva/PeriodotoPrint/2/' . $p->periva, "Imprimir Libro", 'class="botPrintLibro" target="_blank"'); ?>
                    <?php echo anchor('iva/PeriodotoPdf/2/' . $p->periva, "Ver Libro", 'class="botVerLibro" target="_blank"'); ?>
                </td>
            </tr>
        <?php else: ?>
            <tr>
                <td><strong><?php echo $p->periva; ?></strong></td>
                <td>( <?php echo $p->cantidad; ?> )</td>
                <td><?php echo money_format('%= (#10.2n', $p->total); ?></td>
                <td>
                    <?php echo anchor('iva/PeriodotoPrint/2/' . $p->periva, "Imprimir Libro", 'class="botPrintLibro" target="_blank"'); ?>
                    <?php echo anchor('iva/PeriodotoPdf/2/' . $p->periva, "Ver Libro", 'class="botVerLibro" target="_blank"'); ?>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php // ya salimos hay que acomodar el div
    echo "</table>\n";
    echo "</p></div>";
    ?>
</div>

<script>
    $(document).ready(function () {
        $(".accordion").accordion();
        $("h1").css('padding', '5px 5px 5px 25px');
        $(".botPrintLibro").button({icons: {primary: "ui-icon-print"}, text: false});
        $(".botVerLibro").button({icons: {primary: "ui-icon-document"}, text: false});
        $(".botVerPercep").button({icons: {primary: "ui-icon-link"}, text: false});
    });
</script>