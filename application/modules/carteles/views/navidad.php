<?php
/*
 * Vista para la funcion Navidad que buscar los articulos, los lista y despues los envia para que se impriman en php
 */

?>

<h1>Carteles de Navidad</h1>

<?php echo form_open('carteles/buscoDetalles', 'id="codart"') ?>
<input type="hidden" name="pagina" value="<?php echo base_url(), 'index.php/carteles/buscoDetalles' ?>" id="pagina"/>
<?php echo form_label('Articulo:', 'codigobarra'); ?>
<?php echo form_input('codigobarra', '', 'id="codigobarra"'); ?>
<?php echo form_submit('Agregar', 'Agregar'); ?>
<?php echo form_close() ?>

<?php echo form_open('carteles/topdf/navidad', 'id="Print"'); ?>
<table id="articulos" width="85%">
</table>
<table>
    <tr>
        <td colspan="5"><?php echo form_submit('Imprimir', 'Imprimir') ?></td>
    </tr>
</table>
<?php echo form_close() ?>
