<h2>Cierre Jornada</h2>
<h3><?php echo $fecha ?></h3>
<?php echo form_open('pos/cierreJournalDo'); ?>
Tipo de Cierre:
<?php echo form_dropdown('tipo', $opciones); ?>
<?php echo form_submit('Cerrar', 'Cerrar'); ?>
<?php echo form_close(); ?>

