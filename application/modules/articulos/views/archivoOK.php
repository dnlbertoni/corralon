<table>
    <?php foreach ($csvData as $c): ?>
        <tr>
            <td><?php echo $c['BARRAS']; ?></td>
            <td><?php echo $c['PRODUCTO']; ?></td>
            <td><?php echo $c['RUBRO']; ?></td>
            <td><?php echo $c['COSTO']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
