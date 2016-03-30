<div style="with:48%;float:left">
    <h1>Ventas</h1>
    <ul>
        <li><?php echo anchor('facturas/add/2', 'Agregar Factura Venta'); ?></li>
        <li><?php echo anchor('facturas/add/3', 'Agregar Nota Credito Venta'); ?></li>
        <li><?php echo anchor('facturas/add/4', 'Agregar Ticket Z'); ?></li>
    </ul>
</div>
<div style="with:48%;float:right">
    <h1>Compras</h1>
    <ul>
        <li><?php echo anchor('facturas/add/10', 'Agregar Factura Compra'); ?></li>
        <li><?php echo anchor('facturas/add/13', 'Agregar Nota Credito Compra'); ?></li>
        <li><?php echo anchor('facturas/add/13', 'Agregar Comprobante Retencion'); ?></li>
    </ul>
</div>
<div style="clear:both;float:none;text-align:center">

    <?php
    $msj = '<div id="botBuscarFac" style="text-align:center">Buscar Comprobante<span class="ui-icon ui-icon-search" style="float: left; margin-right: .3em;"></span></div>';
    echo anchor('facturas/buscar', $msj);
    ?>
</div>

<script>
    $(document).ready(function () {
        $("#botBuscarFac").button();
    });
</script>