<?php if ($articulo): ?>
    <input type="hidden" id="paginaCambioPrecio"
           value="<?php echo base_url(), 'index.php/articulos/cambioPrecioAjax'; ?>"/>
    <input type="hidden" id="idArticulo" value="<?php echo $articulo->id ?>"/>
    <div class="detalle-valor">
        <?php echo substr($articulo->descripcion, 0, 25); ?>
    </div>
    <div class="detalle-valor">
        <?php echo substr($articulo->descripcion, 25, 50); ?>
    </div>
    <div id="precio-valor">
        <?php echo "$ ", $articulo->precio; ?>
    </div>
    <br/>
    <div id="bot_CambioPrecio">Cambiar Precio</div>
    <script>
        $(document).ready(function () {
            $(".detalle-valor").css('font-size', '30px');
            $(".detalle-valor").css('margin-top', '10px');
            $("#precio-valor").css('font-size', '56px');
            $("#bot_CambioPrecio").button();
            $("#bot_CambioPrecio").click(function () {
                pagina = $("#paginaCambioPrecio").val();
                id = $("#idArticulo").val();
                $.ajax({
                    url: pagina,
                    contentType: "application/x-www-form-urlencoded",
                    global: false,
                    type: "POST",
                    data: ({id: id}),
                    dataType: "html",
                    async: true,
                    success: function (msg) {
                        $("#datos").html(msg);
                    }
                }).responseText;
            });
        });
    </script>
<?php else: ?>
    <div class="ui-state-error ui-corner-all">
        <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
        El articulo con el codigo de barra <?php echo $codigobarra ?> NO EXISTE
    </div>
    <script>
        $(".ui-state-error").css('font-size', '24px');
        $('.ui-state-error').css('height', '75px');
    </script>

<?php endif; ?>
<script>
    $('#precio > div').css('text-align', 'center');
</script>
