<!-- activar las alertas -->
<div class="text-center"> <!-- fila de comprobante -->
    <div class="col-lg-4 col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading"><h4><?php echo $fechoy; ?>&nbsp;<span id="clock"></span></h4></div>
            <div class="panel-body">
                <ul class="list-group">
                    <li class="list-group-item"><span id="tipcom_nom">Ticket</span></li>
                    <li class="list-group-item">
                        Nro:<?php printf("%04.0f - %08.0f", $presuEncab->puesto, $presuEncab->numero); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-2">
        <div class="panel panel-success">
            <div class="panel-heading"><h4>Cliente</h4></div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td id="idCuenta"><?php echo $presuEncab->cuenta_id ?></td>
                    </tr>
                    <tr>
                        <td id="nombreCuenta"><?php echo $presuEncab->cuenta_nombre ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-2">
        <div class="panel panel-warning">
            <div class=" panel-heading">Forma de Pago</div>
            <div class=" panel-body">
                <table class="table">
                    <tbody>
                    <?php foreach ($fpagos as $fp): ?>
                        <tr>
                            <td><?php echo $fp->nombre ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="panel panel-warning">
            <div class=" panel-heading"><h4>Importe</h4></div>
            <div class=" panel-body">
                <ul class=" list-group">
                    <li class=" list-group-item text-danger"
                        style="font-size:56px;"><?php printf("$%01.2f", floatval($totales->Total)); ?></li>
                </ul>
            </div>
            <div class="panel-footer ">
                <p>Total Bultos <?php echo intval($totales->Bultos) ?></p>
            </div>
        </div>
    </div>
</div><!-- /.row -->
<div class="row">
    <div class="col-log-12 col-md-12">
        <table class="table">
            <tr>
                <th width="20%">Codigo</th>
                <th width="50%">Descripcion</th>
                <th width="5%">Cantidad</th>
                <th width="10%">Precio</th>
                <th colspan="2">Importe</th>
            </tr>
            <?php
            $total = 0;
            $renglon = 0;
            foreach ($Articulos as $articulo) {
                ?>
                <?php if ($renglon == 0): ?>
                    <tr class="ui-state-default" >
                <?php else: ?>
                    <tr>
                <?php endif; ?>
                <td><?php echo $articulo->Codigobarra; ?> </td>
                <td><?php echo $articulo->Nombre ?></td>
                <td><?php echo $articulo->Cantidad ?></td>
                <td align="right"><?php printf("$%01.2f", $articulo->Precio); ?></td>
                <td align="right"><?php printf("$%01.2f", $articulo->Importe) ?></td>
                <td>
                    <div id="<?php echo $articulo->codmov ?>" class="botdel">Quitar Articulo</div>
                </td>
                </tr>
                <?php
                $total += $articulo->Importe;
                $renglon++;
            } ?>
            <tr>
                <th colspan="4" align="right">Total --&gt; </th>
                <th align="right" colspan="2"><?php printf("$%01.2f", $total); ?></th>
            </tr>
        </table>
    </div>
    <?php //if($Articulos):?>
    <?php //endif;?>
</div>

<script>
    $(document).ready(function () {
        setInterval('updateClock()', 1000);
        $(".botdel").button({icons: {primary: 'ui-icon-circle-minus'}, text: false});
        $(".botdel").click(function () {
            id = $(this).attr("id");
            delArt(id);
        });
        if ($("#condVta").html() == "Contado") {
            $("#condVta").removeClass('ui-state-error');
            $("#condVta").addClass('ui-state-default');
        } else {
            $("#condVta").removeClass('ui-state-default');
            $("#condVta").addClass('ui-state-error');
        }
    });
    function delArt(codmov) {
        pagina = $("#paginaBorroArticulo").val();
        $.ajax({
            url: pagina,
            contentType: "application/x-www-form-urlencoded",
            global: false,
            type: "POST",
            data: ({codmov: codmov}),
            dataType: "html",
            async: true,
            //beforeSend: function(){$("#loading").fadeIn();},
            success: function (msg) {
                $("#brief").html(msg);
                $("#codigobarra").val('');
                $("#codigobarra").focus();
                $("#loading").fadeOut(100);
            }
        }).responseText;
    }
    function updateClock() {
        var currentTime = new Date();
        var currentHours = currentTime.getHours();
        var currentMinutes = currentTime.getMinutes();
        var currentSeconds = currentTime.getSeconds();
        // Pad the minutes and seconds with leading zeros, if required
        currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
        currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
        timeOfDay = '';
        // Choose either "AM" or "PM" as appropriate
        //var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
        // Convert the hours component to 12-hour format if needed
        //currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
        // Convert an hours component of "0" to "12"
        //currentHours = ( currentHours == 0 ) ? 12 : currentHours;
        // Compose the string for display
        var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
        $("#clock").html(currentTimeString);
    }

</script>
