<table>
    <thead>
    <tr>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Importe</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($pendientes as $pend): ?>
        <tr>
            <td><?php echo $pend->id ?></td>
            <td><?php echo $pend->nombre ?></td>
            <td><?php echo $pend->importe ?></td>
            <td><?php echo anchor('ctacte/cobrar/' . $pend->id, 'Cobrar', 'class="botCob"') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $(".botCob").button({icons: {primary: 'ui-icon-suitcase'}});
    });
</script>