<div class="section">
    <div class="container">
        <div class="row well">
            <div class="col-lg-8 col-md-8">
                <h4>Conceptos de Caja</h4>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>detalle</th>
                        <th>saldo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($datos as $d): ?>
                        <?php
                        switch ($d->tipo) {
                            case 1:
                                $clase = "success";
                                break;
                            case -1:
                                $clase = "danger";
                                break;
                            default:
                                $clase = "active";
                                break;
                        }; ?>
                        <tr class="<?php echo $clase ?>">
                            <td><?php echo $d->id ?></td>
                            <td><?php echo $d->nombre ?></td>
                            <td><?php echo $d->tipo ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4 col-md-4">
                <a class="btn btn-lg btn-primary pull-right" href="http://startbootstrap.com">See More Templates!</a>
                mas daos
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
</div><!-- /.section -->
