<div class="section">
    <div class="row">
        <div class="col-lg-12">
            <h1>LIsta de Precios</h1>
            <?php echo form_open('carteles/listaDePreciosDo', 'id="codart"') ?>
            <input type="hidden" name="pagina" value="<?php echo base_url(), 'index.php/carteles/listaDePreciosDo' ?>"
                   id="pagina"/>
            <?php echo form_label('Rubro:', 'rubro'); ?>
            <?php echo form_dropdown('rubro', $rubrosSel, $rubro, "id='rubro'"); ?>
            <?php echo form_submit('Agregar', 'Agregar'); ?>
            <?php echo form_close() ?>
            <button id="bot_checkAll" class="btn btn-info">Seleccionar Todo</button>
            <?php echo form_open($accion, 'id="Print"'); ?>
            <?php echo form_label('Tamano Letra:', 'tamano') ?>
            <?php echo form_dropdown('tamano', array(6 => 6, 11 => 11, 22 => 22), 6, 'id="tamano"') ?>
            <?php echo form_submit('Imprimir', 'Imprimir') ?>
            <?php echo form_submit('Imprimir', 'Descargar') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table id="articulos" class="table"></table>
        </div>
    </div>
    <?php echo form_close() ?>
</div>

<script>
    $(document).ready(function () {
        $("#bot_checkAll").click(function () {
            $("input:checkbox").click();
        });
        $("#rubro").focus();
        $("#codart").submit(function (evnt) {
            evnt.preventDefault();
            valor = $("#rubro").val();
            pagina = $("#pagina").val();
            alert(valor);
            $.ajax({
                    url: pagina,
                    contentType: "application/x-www-form-urlencoded",
                    global: false,
                    type: "POST",
                    data: ({rubro: valor}),
                    dataType: "html",
                    async: true,
                    success: function (msg) {
                        $("#articulos").append(msg);
                        $("#rubro").val('');
                        $("#rubro").focus();
                    }
                }
            ).responseText;
        });
    });
</script>