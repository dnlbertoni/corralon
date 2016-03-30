<div class="box">
    <h2>Empresas Pendientes de Asignacion - <?php echo $totalEmpresas ?> | <?php echo $totalArticulos ?> Articulos </h2>
    <table>
        <tr>
            <th>Empresa</th>
            <th>Cant. Art.</th>
            <th></th>
            <th>Empresa</th>
            <th>Cant. Art.</th>
            <th></th>
            <th>Empresa</th>
            <th>Cant. Art.</th>
            <th></th>
        </tr>
        <?php
        $x = 0;
        foreach ($empresas as $empresa):?>
            <?php if ($x == 0): ?>
                <tr>
            <?php endif; ?>
            <td><?php echo $empresa->empresa ?></td>
            <td style="text-align: center;"><?php echo $empresa->cantidad ?></td>
            <td><?php echo anchor("articulos/empresas/verArticulos/" . $empresa->empresa, 'Ver', 'class="boton"'); ?></td>
            <?php
            $x++;
            if ($x == 3):?>
                </tr>
                <?php
                $x = 0;
            endif; ?>
        <?php endforeach; ?>
        <tr>
            <td colspan="9"><?php echo $this->pagination->create_links(); ?></td>
        </tr>
    </table>
</div>
<div class="clear">&nbsp;</div>
<div class="box">
    <h2>Marcas de las Empresas - <?php echo $totalMarcas ?> | <?php echo $totalArticulosMarcas ?> Articulos </h2>
    <table>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Gen.</th>
            <th>Asig.</th>
            <th>Total</th>
            <th>Indice</th>
            <th></th>
            <th>Id</th>
            <th>Nombre</th>
            <th>Gen.</th>
            <th>Asig.</th>
            <th>Total</th>
            <th>Indice</th>
            <th></th>
        </tr>
        <?php
        $x = 0;
        foreach ($marcas as $empresa):?>
            <?php if ($x == 0): ?>
                <tr>
            <?php endif; ?>
            <td><?php echo $empresa->id ?></td>
            <td><?php echo $empresa->nombre ?></td>
            <td style="text-align: center;"><?php echo $empresa->genericos ?></td>
            <td style="text-align: center;"><?php echo $empresa->asignados ?></td>
            <td style="text-align: center;"><?php echo $empresa->totales ?></td>
            <td style="text-align: center;"><?php echo sprintf("%03.2f%%", $empresa->tasa) ?></td>
            <td><?php echo anchor("articulos/empresas/verArticulos/" . $empresa->id . "/marca", 'Ver', 'class="boton"'); ?></td>
            <?php
            $x++;
            if ($x == 2):?>
                </tr>
                <?php
                $x = 0;
            endif; ?>
        <?php endforeach; ?>
    </table>
</div>


<script>
    $(document).ready(function () {
        $(".boton").button();
        $(".box").addClass('ui-widget ui-widget-content');
        $(".box >h2").addClass('ui-widget-header');
    });
</script>
