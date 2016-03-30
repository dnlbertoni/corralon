<?php foreach ($tareas as $t): ?>
    <li><?php echo anchor($t[0], $t[1]) ?></li>
<?php endforeach; ?>