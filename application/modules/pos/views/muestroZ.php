<table>
    <tr>
        <td>Numero de Registro</td>
        <td><?php echo $fac->id ?>
        </td>
    </tr>
    <tr>
        <td>Cuenta</td>
        <td>
            <?php echo $fac->cuenta_id ?>
        </td>
    </tr>
    <tr>
        <td> Comprobante:</td>
        <td>(<?php echo $fac->tipcom_id ?>) <?php echo $fac->puesto ?> - <?php echo $fac->numero ?></td>
    </tr>
    <tr>
        <td>Fecha</td>
        <td><?php echo $fac->fecha ?></td>
    </tr>
    <tr>
        <td>Importe</td>
        <td><?php echo $fac->importe ?></td>
    </tr>
    <tr>
        <td>Neto</td>
        <td><?php echo $fac->neto ?></td>
    </tr>
    <tr>
        <td>IVA 21%</td>
        <td><?php echo $fac->ivamax ?></td>
    </tr>
    <tr>
        <td>IVA 10,5%</td>
        <td><?php echo $fac->ivamin ?></td>
    </tr>
    <tr>
        <td>I. Brutos</td>
        <td><?php echo $fac->ingbru ?></td>
    </tr>
    <tr>
        <td>Imp. Internos</td>
        <td><?php echo $fac->impint ?></td>
    </tr>
    <tr>
        <td>Percep. 5%</td>
        <td><?php echo $fac->percep ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <?php $msj = '<div id="bot_next"><span class="ui-icon ui-icon-seek-next" style="float: left; margin-right: .3em;"></span>Continuar</div>';
            echo anchor('facturas/', $msj); ?>
        </td>
    </tr>
</table>