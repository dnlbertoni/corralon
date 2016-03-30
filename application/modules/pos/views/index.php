<h1>Modulo de Puesto de Venta</h1>

<div class="row">
    <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
            <div class="caption">
                <h3>Presupuesto y Venta</h3>
                <p>Para Presupuestar y facturar compras en un puesto de Venta</p>
                <p><?php echo anchor('pos/factura/presupuesto', 'Emision Ticket', 'class="btn btn-info btn-lg" role="button"'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="thumbnail">
            <div class="caption">
                <h3>Caja</h3>
                <p>Al inicio de la Jornada se debe abrir caja y luego al finalizar cerrarla</p>
                <?php echo anchor('caja', 'Abrir Caja', 'class="btn btn-danger btn-lg" role="button"'); ?>
                <?php echo anchor('caja', 'Cerrar Caja', 'class="btn btn-success btn-lg" role="button"'); ?>
            </div>
        </div>
    </div>
</div>

