<h1>Existen <?php echo count($articulos); ?> articulos para revisar...</h1>
<h2>Avance <?php echo round($progreso, 2) ?>%</h2>
<div id="progressbar"></div>
<div class="post">
    <table>
        <tr>
            <th>Dias Transc.</th>
            <td><?php echo $dd->dias ?></td>
            <th>Articulos</th>
            <td><?php echo $dd->cantidad ?></td>
            <th>Promedio de Articulos Diarios</th>
            <td><?php echo round($dd->cantidad / $dd->dias, 2) ?></td>
            <th>Proyeccion dias</th>
            <td><?php echo round((count($articulos) / ($dd->cantidad / $dd->dias)) / 30, 2) ?> meses</td>
        </tr>
    </table>
    <table>
        <tr>
            <th colspan='6'>Tendencia</th>
        </tr>
        <tr>
            <th>Semana Nro</th>
            <th>Cant.</th>
            <th>Variacion</th>
            <th>Prom. Semanal</th>
            <th>Prom. Diario</th>
            <th>Proy. Diaria</th>
        </tr>
        <?php
        $total = 0;
        $vez = 0;
        $semAnt = false;
        $muestro = count($semanas) - 4;
        ?>
        <?php foreach ($semanas as $ss): ?>
            <?php
            if ($semAnt) {
                $aux = (($ss->cantidad / $semAnt) - 1) * 100;
                $semAnt = $ss->cantidad;
            } else {
                $semAnt = $ss->cantidad;
            };
            $total += $ss->cantidad;
            $vez++;
            ?>
            <?php if ($vez >= $muestro): ?>
                <tr>
                    <td><?php echo $ss->semana ?></td>
                    <td><?php echo $ss->cantidad ?></td>
                    <td><?php echo sprintf("%4.2f", $aux); ?>%
                    </td>
                    <td><?php echo sprintf("%4.2f", ($total / $vez)) ?></td>
                    <td><?php echo sprintf("%4.2f", ($total / $vez) / 7) ?></td>
                    <td>
                        <?php
                        $resto = (count($articulos) + $total) / (($total / $vez) / 7);
                        $inicio = new DateTime($ss->ano . '-01-01');
                        $inicio->modify('+' . $ss->semana . ' weeks');
                        $format = "+" . intval($resto) . " days";
                        $inicio->modify($format);
                        echo $inicio->format('d/m/Y');
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr>
            <th>Total / Promedio</th>
            <th><?php echo $total ?></th>
            <th>&nbsp;</th>
            <th><?php echo $total / $vez; ?> art. x Semana</th>
            <th><?php echo round($dd->cantidad / $dd->dias, 2) ?></th>
            <th><?php echo $fechaAdivinada ?></th>
        </tr>
    </table>
</div>
<div class="post">
    <table id="datos">
        <thead>
        <th><?php echo anchor('articulos/wizard/masivo/id', 'Codigo') ?></th>
        <th><?php echo anchor('articulos/wizard/masivo/nombre', 'Descripcion') ?></th>
        <th><?php echo anchor('articulos/wizard/masivo/precio', 'Precio') ?></th>
        <th><?php echo anchor('articulos/wizard/masivo/subrubro', 'Subrubro') ?></th>
        <th><?php echo anchor('articulos/wizard/masivo/marca', 'Marca') ?></th>
        <th>&nbsp;</th>
        </thead>
        <tbody>
        <?php $x = 0; ?>
        <?php foreach ($articulos as $articulo): ?>
            <tr <?php echo 'id="linea_' . $articulo->id . '"' ?> class="linea est_<?php echo $articulo->estado ?>">
                <td><?php echo $articulo->id ?></td>
                <td><?php echo $articulo->nombre ?></td>
                <td><?php echo $articulo->precio ?></td>
                <td><?php echo $articulo->subrubro ?></td>
                <td><?php echo $articulo->marca ?></td>
                <td>
                    <?php echo anchor('articulos/wizard/index/' . $articulo->codigobarra, 'Asistente', 'class="botonAsistente"') ?>
                    <?php echo anchor('articulos/wizard/outWizard/' . $articulo->codigobarra, 'Sacar Wizard', 'class="botonOut botonAjax"') ?>
                    <?php echo anchor('articulos/borrar/' . $articulo->id . '/1', 'Borrar', 'class="botonBorrar botonAjax"') ?>
                </td>
            </tr>
            <?php $x++;
            if ($x > 50) {
                break;
            } ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {
        $("#progressbar").progressbar({value:<?php echo $progreso?>});
        $(".botonAsistente").button({icons: {primary: 'ui-icon-star'}, text: false});
        $(".botonOut").button({icons: {primary: 'ui-icon-cancel'}, text: false});
        $(".botonBorrar").button({icons: {primary: 'ui-icon-trash'}, text: false});
    });
</script>
