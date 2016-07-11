<?php
/**
 * Created by PhpStorm.
 * User: dnlbe
 * Date: 9/6/2016
 * Time: 8:58 PM
 */
?>

<html>
<head></head>
<body>
<form action="fact.php" method="post">
    <table width="75%">
        <tr>
            <td>Nombre</td>
            <td colspan="2"><input type="text" size="30" value="" name="nombre"/></td>
            <td>Direccion</td>
            <td><input type="text" size="30" value="" name="direccion"/></td>
        </tr>
        <tr>
            <td>Tipo Doc</td>
            <td>
                <select name="tipdoc">
                    <option value="1">CUIT</option>
                    <option value="2" selected="selected">DNI</option>
                </select>
            </td>
            <td>Numero Doc.</td>
            <td>
                <input type="text" value="" name="numdoc"/>
            </td>
            <td>
                <select name="tipo">
                    <option value="C">Consumidor Final</option>
                    <option value="I" selected="selected">Responsable Inscripto</option>
                    <option value="E" selected="selected">Excento</option>
                </select>
            </td>

        </tr>
        <tr>
            <td>Cant</td>
            <td colspan="2">Descripcion</td>
            <td>IVA</td>
            <td>Importe</td>
        </tr>
        <?php for ( $i = 0; $i < 8; $i++ ): ?>
            <tr>
                <td><input type="text" value="" size="5" name="cantidad_<?= $i ?>"/></td>
                <td colspan="2"><input type="text" value="" name="producto_<?= $i ?>" size="50"/></td>
                <td><input type="text" value="21" size="10" name="iva_<?= $i ?>"/></td>
                <td><input type="text" value="" size="10" name="precio_<?= $i ?>"/></td>
            </tr>
        <?php endfor; ?>
    </table>
    <button type="submit" value="Facturar">Facturar</button>
</form>
</body>
</html>
