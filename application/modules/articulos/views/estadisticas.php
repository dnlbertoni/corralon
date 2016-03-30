porcentaje de normalizacion de los nombres <?php echo $normalizacion ?>
<br/>
Activos <?php echo $activos ?>
<br/>
Suspendidos <?php echo $suspendidos ?>
<br/>
Faltan Procesar <?php echo $faltanGenericas ?> articulos sin marcas.
<br/>
Procesando ... <?php echo ($total - $faltanGenericas) / $total * 100, "%" ?>
<div id="progressBar1"></div>
<div>
    <?php echo anchor('articulos/rankings/importe', 'Estadisticas en Precio Vta', 'class="boton"') ?>
    <?php echo anchor('articulos/rankings/cantidad', 'Estadisticas en Cantidades Vendidas', 'class="boton"') ?>
    <?php echo anchor('articulos/rankings/marcas', 'Estadisticas en Marcas ', 'class="boton"') ?>
</div>
<script>
    $(document).ready(function () {
        $(".boton").button();
        $("#progressBar1").progressbar({value: <?php echo ($total - $faltanGenericas) / $total * 100?>});
    });
</script>

