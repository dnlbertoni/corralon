Precio: <input type="text" name="precio" size="4" id="precio"/>
<div id="botPrecio" class='boton'>Cambiar</div>
<br/>
Porcentaje : <input type="text" name="dif" id="dif" size="4"/>%
<div id="botPorce" class='boton'>Cambiar</div>


<script>
    $(document).ready(function () {
        $(".boton").button();
        $("#botPrecio").click(function () {
            valor = $('#precio').val();
            $('.precioNuevo').val(valor);
            $("#search").dialog("close");
        });
        $("#botPorce").click(function () {
            $('.precioNuevo').each(function () {
                var valor = 1 + (parseFloat($('#dif').val()) / 100);
                var precio = parseFloat($(this).val());
                var nuevo = (precio * valor).toFixed(2);
                $(this).val(nuevo);
            });
            $("#search").dialog("close");
        });
    });
</script>
