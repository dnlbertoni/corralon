<div class="section">
    <div class="row">
        <h3><?= $title ?></h3>
        <div class="col-md-12">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>CUIT</th>
                    <th>Tipo</th>
                    <th colspan="2">
                        <a href="/cuenta/crear" class="btn btn-lg btn-success" data-toggle="modal" data-target="#add"><i
                                class="fa fa-check"></i> Agregar Cuenta</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ( $cuentas as $cuenta ): ?>
                    <tr>
                        <td><?php echo $cuenta->id ?></td>
                        <td><?php echo $cuenta->nombre ?></td>
                        <td><?php echo $cuenta->cuit ?></td>
                        <td><?php echo $cuenta->tipo ?></td>
                        <td><?php echo anchor ( 'cuenta/editar/' . $cuenta->id, 'Editar', 'class="btn btn-info"' ) ?></td>
                        <td><?php echo anchor ( 'cuenta/borrar/' . $cuenta->id, '<i class="fa fa-trash"></i>', 'class="btn btn-danger"' ) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
        </div>   <!-- /.modal-content -->
    </div>   <!-- /.modal-dialog -->
</div>   <!-- /.modal -->