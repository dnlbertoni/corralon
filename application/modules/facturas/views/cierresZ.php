<?php if ($formulario): ?>
    <?php echo form_open('facturas/addCierresZDo'); ?>
    Nro de Cierre: <?php echo form_input('numeroCierre'); ?>
    <?php echo form_submit('Consultar', 'Consultar'); ?>
<?php else : ?>
    <?php echo form_open('facturas/graboCierreZ'); ?>
    Numero: <?php echo form_input('numero', $hasar['numero']); ?>
    <br/>
    Fecha: <?php echo form_input('fecha', $hasar['fecha']); ?>
    <br/>
    Importe: <?php echo form_input('importe', $hasar['importe']); ?>
    <br/>
    Neto: <?php echo form_input('neto', $hasar['neto']); ?>
    <br/>
    Iva 10,5%: <?php echo form_input('ivamin', $hasar['ivamin']); ?>
    <br/>
    Iva 21%: <?php echo form_input('ivamax', $hasar['ivamax']); ?>
    <br/>
    Impuesto Interno: <?php echo form_input('impint', $hasar['impint']); ?>
    <br/>
    Periodo IVA:<?php echo form_input('periva', $hasar['periva']); ?>
    <br/>
    <?php echo form_submit('Grabar', 'Grabar'); ?>
<?php endif; ?>
<?php echo form_close(); ?>
