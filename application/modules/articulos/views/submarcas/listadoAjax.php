<?php $destino = "'#" . $target . "'"; ?>
<?php if ($vacio): ?>
    <?php echo $submarcaTXT ?>, no esta grabada <span id="botCrearMarca" class="boton">Crear una nueva Marca</span>
    <div id="nuevaMarca"></div>
<?php else: ?>
    <table>
        <thead>
        <th>Codigo</th>
        <th>Nombre</th>
        <th>Marca</th>
        <th>&nbsp;</th>
        </thead>
        <tbody>
        <?php foreach ($submarcas as $submarca): ?>
            <tr>
                <td><?php echo $submarca->id ?></td>
                <td id="nom_<?php echo $submarca->id ?>"><?php echo $submarca->nombre ?></td>
                <td id="idmar_<?php echo $submarca->id ?>"><?php echo $submarca->id_marca ?></td>
                <td id="mar_<?php echo $submarca->id ?>"><?php echo $submarca->marca ?></td>
                <td>
                    <div id="id_<?php echo $submarca->id ?>" class="boton">Seleccionar</div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
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
            marca = '#mar_' + valor[1];
            idmarca = '#idmar_' + valor[1];
            data = "<span class='codigo'>" + valor[1] + "</span>" + " - " + "<span class='detalle'>" + $(nombre).html() + "</span>" + "( " + "<span class='id_marca'>" + $(idmarca).html() + "</span>" + " ) " + $(marca).html();
            $(destino).html(data);
            $(nombreDialogo).dialog("close");
        });
        $("#botCrearMarca").click(function () {
            creoMarca();
        });
    });

    function creoMarca() {
        var dialogOpts = {
            modal: true,
            bgiframe: true,
            autoOpen: false,
            height: 300,
            width: 500,
            title: "Agrego Marca",
            draggable: true,
            resizeable: true,
            close: function () {
                valor = $("#nuevaMarca > .codigo").html()
                $('#nuevaMarca').dialog("destroy");
            }
        };
        $("#nuevaMarca").dialog(dialogOpts);   //end dialog
        $("#nuevaMarca").load(<?php echo $targetMarca;?>, [], function () {
                $("#nuevaMarca").dialog("open");
            }
        );
    }
</script>
