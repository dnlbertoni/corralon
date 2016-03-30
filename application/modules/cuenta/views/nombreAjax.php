<script src="<?php echo base_url(); ?>/rsc/js/cuenta/nombreAjax.js"></script>
<div>
    <?php
    $attr = array('id' => 'buscoNombre');
    echo form_open('cuenta/muestroAjax', $attr) ?>
    <?php echo form_label('Cuenta', 'nombreTXT'); ?>
    <?php
    $attr = array('id' => 'nombreTXT',
        'name' => 'nombreTXT'
    );
    echo form_input($attr); ?>
    <?php echo form_submit('Buscar', 'Buscar'); ?>
    <?php echo form_close(); ?>
</div>
<div id="MuestroDatos">

</div>