<h1><?php echo $tipcom_nombre ?></h1>
<?php $attrib = array('id' => 'addFac'); ?>
<?php echo form_open('facturas/addDo', $attrib, $ocultos); ?>
<table>
    <tr>
        <td>Cuenta</td>
        <td>
            <?php
            $datinput = array(
                'id' => 'cuenta_id',
                'name' => 'cuenta_id',
                'value' => 1,
                'size' => 5
            );
            echo form_input($datinput) ?> |
            <?php
            $datinput = array(
                'id' => 'cuenta_nombre',
                'name' => 'cuenta_nombre',
                'value' => 'Consumidor Final'
            );
            echo form_input($datinput) ?>
        </td>
    </tr>
    <tr>
        <td> Comprobante:</td>
        <td>
            <?php
            $datinput = array(
                'id' => 'letra',
                'name' => 'letra',
                'value' => 'Z',
                'size' => 1
            );
            echo form_input($datinput) ?> |
            <?php
            $datinput = array(
                'id' => 'puesto',
                'name' => 'puesto',
                'value' => '3',
                'size' => 5
            );
            echo form_input($datinput); ?> -
            <?php
            $datinput = array(
                'id' => 'numero',
                'name' => 'numero',
                'size' => 8
            );
            echo form_input($datinput); ?>
        </td>
    </tr>
    <tr>
        <td>Fecha</td>
        <td>
            <?php
            $datinput = array(
                'id' => 'fecha',
                'name' => 'fecha',
            );
            echo form_input($datinput); ?>
            <div id="fecha_ing"></div>
        </td>
    </tr>

    <tr>
        <td>Importe</td>
        <td><?php
            $datinput = array('id' => 'importe',
                'name' => 'importe'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>Iva Total</td>
        <td><?php
            $datinput = array('id' => 'ivatot',
                'name' => 'ivatot'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>Neto</td>
        <td><?php
            $datinput = array('id' => 'neto',
                'name' => 'neto'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>IVA 21%</td>
        <td><?php
            $datinput = array('id' => 'ivamax',
                'name' => 'ivamax'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>IVA 10,5%</td>
        <td><?php
            $datinput = array('id' => 'ivamin',
                'name' => 'ivamin'
            );
            echo form_input($datinput); ?></td>
    </tr>
    <tr>
        <td>I. Brutos</td>
        <td><?php echo form_input('ingbru') ?></td>
    </tr>
    <tr>
        <td>Imp. Internos</td>
        <td><?php echo form_input('impint') ?></td>
    </tr>
    <tr>
        <td>Percep. 5%</td>
        <td><?php echo form_input('percep') ?></td>
    </tr>
    <tr>
        <td colspan="2"> <?php echo form_submit('Grabar', 'Grabar'); ?></td>
    </tr>
</table>
<?php echo form_close();
