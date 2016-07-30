<?php
/**
 * Created by PhpStorm.
 * User: sistemas
 * Date: 17/07/2016
 * Time: 0:23
 */ ?>
<script src="/assets/js/jquery-3.1.0.min.js"></script>
<div class="row">
    <?php echo form_open ( 'stock/articulos/searchAjax', 'id="consultaArticulo"' ) ?>
    <label>Busqueda:</label>
    <?php echo form_input ( 'nombreArticuloTXT', '', 'id="nombreArticuloTXT" class="form-control" ' ) ?>
    <!-- <?php echo form_button ( 'Consultar', 'Consultar' ); ?> -->
    <?php echo form_close () ?>
    <div class="col-md-12">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Subrubro</th>
                <th>Marca</th>
                <th>Precio</th>
            </tr>
            </thead>
            <tbody class="table-content" id="datosArticulos">

            </tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function () {
        $("#nombreArticuloTXT").focus();
        $("#nombreArticuloTXT").bind('keyup', function (e) {
            var code = e.keyCode;
            valor = $("#nombreArticuloTXT").val().toUpperCase();
            $("#nombreArticuloTXT").val(valor);
            if (( code < 90 && code > 57 ) || code === 13 || code === 8) {
                envioForm();
            }
            ;
        });
    });
    function envioForm() {
        articulo = $("#nombreArticuloTXT").val().trim();
        pagina = $("#consultaArticulo").attr('action');
        if (articulo.length > 1) {
            $.post({
                url: pagina,
                contentType: "application/x-www-form-urlencoded",
                global: false,
                type: "POST",
                data: ({
                    valor: articulo
                }),
                dataType: "json",
                async: true,
                success: function (articulos) {
                    muestroArticulos(articulos);
                }
            });
        }
    }
    function muestroArticulos(data) {
        $("#datosArticulos").html('');
        $.each(data, function (key, articulo) {
            linea = "<tr>";
            linea += "<td>" + articulo.id + "</td>";
            linea += "<td>" + articulo.nombre + "</td>";
            linea += "<td>" + articulo.subrubro + "</td>";
            linea += "<td>" + articulo.submarca + "</td>";
            linea += "<td>" + articulo.precio + "</td>";
            linea += "<td><div role='button' class='btn btn-success btn-xs btn-agregarArticulo' id='btn_" + articulo.id + "'><span class='fa fa-shopping-cart'></span></div></td>";
            linea += "</tr>";
            $("#datosArticulos").append(linea);
        })
        $(".btn-agregarArticulo").click(function () {
            aux = $(this).attr('id');
            aux = aux.split('_');
            id = aux[1];
            $("#codigobarra").val(id);
            busqueda.close();
        });
    }
</script>