<h1>inidice</h1>
<div>
    <h3>Resumen</h3>
    <?php echo form_open('ctacte/estadisticas/resumenPeriodo', 'id="resumen-Form"'); ?>
    <?php echo form_label('Mes', 'mes_id'); ?>
    <?php echo form_dropdown('mes_id', $meses, '0', 'id="mes_id"'); ?>
    <?php echo form_label('Año', 'ano_id'); ?>
    <?php echo form_dropdown('ano_id', $anos, '0', 'id="ano_id"'); ?>
    <?php echo form_submit('resumen', 'Consultar'); ?>
    <?php echo form_close(); ?>
</div>
