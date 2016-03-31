<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Cambia la navegacion</span>
                <span class="fa-wrench">Configuraciones</span>
            </button>
            <!-- You'll want to use a responsive image option so this logo looks good on devices - I recommend using something like retina.js (do a quick Google search for it and you'll find it) -->
            <?php echo anchor('/', 'Corralon del Sur', 'class="navbar-brand"') ?>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- muestro los accesos directos -->
                <li><?php echo anchor('ticket/', '<span class="fa fa-shopping-cart"></span>') ?></li>
                <li><?php echo anchor('setting/', '<span class="fa fa-wrench"></span>') ?></li>
                <!-- fin de accesos directos -->
                <!-- inicio de los modulos -->
                <?php foreach ($modulos as $mo): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="<?php echo $mo->clase; ?>"></span>&nbsp;<?php echo $mo->nombre ?><b
                                class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($menu as $me): ?>
                                <?php if ($mo->id == $me->modulo): ?>
                                    <?php if ($me->clase) {
                                        $nombre = " <span class='$me->clase fa-fw pull-right'></span> " . $me->nombre;
                                    } else {
                                        $nombre = $me->nombre;
                                    }; ?>
                                    <li><?php echo anchor($me->link, $nombre) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
                <!-- fin de los modulos -->
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>