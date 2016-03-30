<?php
/*
 *  menu de modulos para el template
 */
//print_r($Modulos);
//hay que definir el current
?>
<ul>
    <?php foreach ($Modulos as $modulo): ?>
        <?php $class = (isset($modulo['clase'])) ? $modulo['clase'] : ""; ?>
        <li><?php echo anchor($modulo['link'], $modulo['nombre'], $class) ?></li>
    <?php endforeach; ?>
</ul>
