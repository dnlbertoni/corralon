Filtrar: <?php echo form_input(array('id' => 'nombreTXT', 'name' => 'nombreTXT')); ?>
<div style="clear:both"><br/></div>
<?php echo anchor('cuenta/index/1', 'Clientes', 'class="boton"'); ?>
<?php echo anchor('cuenta/index/2', 'Proveedores', 'class="boton"'); ?>
<table id="datos">
    <thead>
    <tr>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>CUIT</th>
        <th>Tipo</th>
        <th colspan="2">Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($cuentas as $cuenta): ?>
        <tr>
            <td><?php echo $cuenta->id ?></td>
            <td><?php echo $cuenta->nombre ?></td>
            <td><?php echo $cuenta->cuit ?></td>
            <td><?php echo $cuenta->tipo ?></td>
            <td><?php echo anchor('cuenta/editar/' . $cuenta->id, 'Editar', 'class="botonEdit"') ?></td>
            <td><?php echo anchor('cuenta/borrar/' . $cuenta->id, 'Borrar', 'class="botonDel"') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        var largo = $("tbody > tr:visible").length;
        if (largo < 150) {
            pongoOpciones();
        }
        var theTable = $('#datos');
        $("#nombreTXT").keyup(function () {
            $.uiTableFilter(theTable, this.value);
            var largo = $("tbody > tr:visible").length;
            if (largo < 150) {
                pongoOpciones();
            }
        });
        $(".boton").button();
        $("#nombreTXT").focus();
        $("#nombreSearch").click(function () {
            $("#nombreTXT").focus();
        });
    });
    function pongoOpciones() {
        $(".botonEdit").button({text: false, icons: {primary: 'ui-icon-pencil'}});
        $(".botonDel").button({text: false, icons: {primary: 'ui-icon-trash'}});
    }
</script>