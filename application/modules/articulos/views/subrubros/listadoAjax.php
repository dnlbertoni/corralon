<?php $destino = "'#" . $target . "'"; ?>
<?php if ($vacio): ?>
    <?php echo $subrubroTXT ?>, no esta grabado <span id="botCrearRubro" class="boton">Crear un nuevo Subrubro</span>
    <div id="nuevoRubro"></div>
<?php else: ?>
    <table>
        <thead>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Marca</th>
        <th>&nbsp;</th>
        </thead>
        <?php foreach ($subrubros as $subrubro): ?>
            <tr>
                <td><?php echo $subrubro->id ?></td>
                <td id="nom_<?php echo $subrubro->id ?>"><?php echo $subrubro->nombre ?></td>
                <td id="rub_<?php echo $subrubro->id ?>"><?php echo $subrubro->rubro ?></td>
                <td>
                    <div id="id_<?php echo $subrubro->id ?>" class="boton">Seleccionar</div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<script language="javascript">
    $(document).ready(function () {
        $('.boton').button();
        $('div [id^=id_]').click(function () {
            aux = $(this).attr('id');
            valor = aux.split('_');
            destino = <?php echo $destino ?>;
            nombreAux = $(this).parent().parent().parent().parent().parent().parent().attr('id');
            nombreDialogo = "#" + nombreAux;
            nombre = '#nom_' + valor[1];
            rubro = '#rub_' + valor[1];
            data = "<span class='codigo'>" + valor[1] + "</span>" + " - " + "<span class='detalle'>" + $(nombre).html() + "</span>" + "( " + $(rubro).html() + " )";
            $(destino).html(data);
            $(nombreDialogo).dialog("close");
        });
        $("#botCrearRubro").click(function () {
            creoRubro();
        });
    });
    function creoRubro() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Agrego Subrubro",
            draggable: true,
            resizeable: true,
            close: function () {
                valor = $("#nuevaMarca > .codigo").html()
                $('#nuevaMarca').dialog("destroy");
            }
        };
        $("#nuevoRubro").dialog(dialogOpts);   //end dialog
        $("#nuevoRubro").load(<?php echo $targetRubro;?>, [], function () {
                $("#nuevoRubro").dialog("open");
            }
        );
    }
</script>
