<div class="box_izq">
    <h1>Pendientes sin Liquidar</h1>
    <div>
        <table id="datos">
            <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($pendientes as $cuenta): ?>
                <tr>
                    <td style="text-align:center"><?php echo $cuenta->id ?></td>
                    <td><?php echo substr($cuenta->nombre, 0, 25) ?></td>
                    <td><?php echo anchor('ctacte/historial/' . $cuenta->id, 'Historial', 'class="botonLiq"'); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="box_der">
    Filtrar Cuenta:<?php echo form_input('nombreTXT', '', 'id="nombreTXT"'); ?>
    <table>
        <thead>
        <tr>
            <th colspan="3">Ultimas Operaciones de <?php echo $hoy ?></th>
        </tr>
        <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Importe</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ultimas as $last): ?>
            <tr>
                <td><?php echo $last->date ?></td>
                <td><?php echo $last->cliente ?></td>
                <td><?php echo $last->importe ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th colspan="3">Ultimos Cobros</th>
        </tr>
        <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Importe</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pagadas as $last): ?>
            <tr>
                <td><?php echo $last->fecha ?></td>
                <td><?php echo $last->nombre ?></td>
                <td><?php echo $last->importe ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="clear"></div>

<script>
    $("tr:even").css('background-color', '#F0E3A4');
    $(document).ready(function () {
        $('.botonLiq').button({text: false, icons: {primary: 'ui-icon-comment'}});
        $('.botonCob').button({text: false, icons: {primary: 'ui-icon-suitcase'}});
        $(".importe").css('text-align', 'right');
        $("#nombreTXT").addClass('focus');
        $("#nombreTXT").focus();
        $(".box_izq").css('float', 'left');
        $(".box_izq").css('width', '48%');
        $('td').css('border', 'thin solid');
        $('.botonLiq').parent().css('border', 'none');
        $('.botonCob').parent().css('border', 'none');
        var theTable = $('#datos');
        $("#nombreTXT").keypress(function () {
            $.uiTableFilter(theTable, this.value);
        });
    });
</script>
