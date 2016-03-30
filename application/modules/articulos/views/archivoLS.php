<div class="post">
    <h1>Importacion de Articulos y Precios de Costos y precios Sugeridos </h1>
    <h3>Precio de Costo SIN IVA - Precio de Venta FINAL - MARKUP sujeto a cada articulo</h3>
    <div id="productos">
        <?php foreach ($nuevos as $c): ?>
            <div>
                <table>
                    <thead>
                    <tr>
                        <th><?php echo $c['BARRAS']; ?></th>
                        <th>Lista</th>
                        <th>En la Base</th>
                    </tr>
                    </thead>
                    <tr>
                        <th>Nombre</th>
                        <td id="detaleFILE_<?php echo $c['BARRAS']; ?>"><?php echo $c['PRODUCTO']; ?></td>
                    </tr>
                    <tr>
                        <th>Costo</th>
                        <td id="costo_<?php echo $c['BARRAS']; ?>"><?php echo $c['COSTO']; ?></td>
                    </tr>
                    <tr>
                        <th>Precio</th>
                        <td id="precio_<?php echo $c['BARRAS']; ?>"><?php echo $c['PRECIO']; ?></td>
                    </tr>
                    <tr>
                        <th>Markup</th>
                        <td id="markup_<?php echo $c['BARRAS']; ?>"><?php echo $c['markup']; ?></td>
                    </tr>
                </table>
                <div>
                    <span><?php echo anchor('articulos/insertoDesdeLSCSV/', 'Crear', 'id="' . $c['BARRAS'] . '" class="botonInserto"') ?></span>
                    <span id="graba_<?php echo $c['BARRAS']; ?>"></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="productos">
        <?php foreach ($productos as $c): ?>
            <div>
                <table>
                    <thead>
                    <tr>
                        <th><?php echo $c['BARRAS']; ?></th>
                        <th>Lista</th>
                        <th>En la Base</th>
                    </tr>
                    </thead>
                    <tr>
                        <th>Nombre</th>
                        <td><?php echo $c['PRODUCTO']; ?></td>
                        <td><?php echo $c['detalle_db']['descripcion']; ?></td>
                    </tr>
                    <tr>
                        <th>Costo</th>
                        <td id="costo_<?php echo $c['BARRAS']; ?>"><?php echo $c['COSTO']; ?></td>
                        <td><?php echo $c['detalle_db']['costo']; ?></td>
                    </tr>
                    <tr>
                        <th>Precio</th>
                        <td id="precio_<?php echo $c['BARRAS']; ?>"><?php echo $c['PRECIO']; ?></td>
                        <td><?php echo $c['detalle_db']['precio']; ?></td>
                    </tr>
                    <tr>
                        <th>Markup</th>
                        <td id="markup_<?php echo $c['BARRAS']; ?>"><?php echo $c['markup']; ?></td>
                        <td><?php echo $c['detalle_db']['markup']; ?></td>
                    </tr>
                </table>
                <div>
                    <span>Ultima Fecha de Modificacion: <?php echo $c['detalle_db']['fechamodif']; ?></span>
                    <span><?php //echo anchor('articulos/getDatosArticuloCSV/'.$c['BARRAS'],'Buscar Datos', 'id="'.$c['BARRAS'].'_bot", class="botonAjax"');?></span>
                    <span><?php echo anchor('articulos/graboDesdeLSCSV/', 'Grabo', 'id="' . $c['BARRAS'] . '" class="botonGrabo"') ?></span>
                    <span id="graba_<?php echo $c['BARRAS']; ?>"></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".botonAjax").button({icons: {primary: 'ui-icon-search'}});
        $(".botonAjax").click(function (e) {
            e.preventDefault();
            var nom = $(this).attr('id');
            nombr = nom.split('_');
            nombre = nombr[0];
            url = $(this).attr('href');
            detalleArticulo(nombre, url);
        });
        $(".botonGrabo").button({icons: {primary: 'ui-icon-disk'}});
        $(".botonGrabo").click(function (e) {
            e.preventDefault();
            var nom = $(this).attr('id');
            var url = $(this).attr('href');
            nombre = "#costo_" + nom;
            var CB = nom;
            var costo = $(nombre).text();
            nombre = "#precio_" + nom;
            var precio = $(nombre).text();
            nombre = "#markup_" + nom;
            var markup = $(nombre).text();
            $.post(url,
                {codigobarra: CB, costo: costo, precio: precio, markup: markup},
                function (data) {
                    nombre = "#graba_" + nom;
                    $(nombre).html(data);
                    nombre = "#" + nom;
                    $(nombre).hide();
                });
        });
        $(".botonInserto").button({icons: {primary: 'ui-icon-info'}});
        $(".botonInserto").click(function (e) {
            e.preventDefault();
            var nom = $(this).attr('id');
            var url = $(this).attr('href');
            nombre = "#costo_" + nom;
            var CB = nom;
            var costo = $(nombre).text();
            nombre = "#precio_" + nom;
            var precio = $(nombre).text();
            nombre = "#markup_" + nom;
            var markup = $(nombre).text();
            nombre = "#detalleFILE_" + nom;
            var detalle = $(nombre).text();
            $.post(url,
                {codigobarra: CB, costo: costo, precio: precio, markup: markup, detalle: detalle},
                function (data) {
                    nombre = "#graba_" + nom;
                    $(nombre).html(data);
                    nombre = "#" + nom;
                    $(nombre).hide();
                });
        });
    });
    function detalleArticulo(CB, url) {
        $.ajax({
            type: "GET",
            url: url,
            success: function (msg) {
                nombre = "#" + CB;
                $(nombre).html(msg);
            }
        });
    }
</script>
