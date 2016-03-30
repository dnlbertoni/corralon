<div class="post">
    <h1>Importacion de Facturas de IVA Sistema Viejo</h1>
    <h3>Peridos 1999 - 2009</h3>
    <div id="facturas">
        <table>
            <tr>
                <th>Fecha</th>
                <th>Tipo Comp.</th>
                <th colspan="2">Cuenta</th>
                <th>CUIT</th>
                <th colspan="2">Base datos</th>
            </tr>
            <?php $x = 0; ?>
            <?php foreach ($facturas as $f): ?>
                <tr id="<?php echo $x ?>">
                    <td><?php echo $f['fecha'] ?></td>
                    <td><?php echo $f['tipcom_id'] ?></td>
                    <td class="cuenta_id"><?php echo $f['cuenta_id'] ?></td>
                    <td><?php echo $f['nom'] ?></td>
                    <td><?php echo $f['cuit'] ?></td>
                    <td class="nom"></td>
                    <td class="cuit"></td>
                </tr>
                <?php $x++; ?>
            <?php endforeach; ?>
        </table>
    </div>
</div>

