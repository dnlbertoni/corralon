<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <?php Assets::add_js(array('jquery-ui-1.8.9.min.js', 'jquery-1.7.1.js')) ?>
    <?php echo Assets::css('jquery-ui') ?>
    <?php echo Assets::js() ?>
    <?php echo Assets::css() ?>
    <title>Autoservicio Santa Lucia</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
</head>
<body>
<div id="outer">
    <div id="outer2">
        <div id="header">
      <span id="logo">
        <?php echo Assets::image('Isologo_santalucia_2010_color.jpg', array('alt' => "Loading...", 'height' => '80')); ?>
      </span>
            <h1>Autoservicio Santa Lucia</h1>
            <h2>Sistema de Gestion</h2>
            <div id="boxAyuda">
                <?php echo anchor('ticket', 'Ticket', 'id="carrito"'); ?>
                <div id="admin">Configuracion</div>
                <div id="problema">Problema</div>
                <a id="radiooline" href="http://www.cadena3.com/multimedia.asp?programacion=CADENA%203" target="_blank">Radio
            </div>
        </div>
    </div>
    <div id="menu">
        <?php if (isset($Modulos)): ?>
            <?php echo Template::block('menu', 'menu') ?>
        <?php else: ?>
            Falta definir el menu;
        <?php endif; ?>
    </div>
    <div id="content">
        <?php if (isset($tareas)): ?>
            <div id="column1">
                <?php echo Template::yield1() ?>
            </div>
            <?php echo Template::block('tareas'); ?>
        <?php else: ?>
            <div id="columnFull">
                <?php echo Template::yield1() ?>
            </div>
        <?php endif; ?>
    </div>
    <div id="ventanaAjax"></div>
    <div id="radioonline"></div>
    <div id="footer">
        <p>2010 &copy; 2013 | Autoservicio Santa Lucia | Author >> DnL
            | <?php echo anchor('version/', 'Version ' . VERSION); ?></p>
    </div>
</div>
</div>

</body>
</html>

<script>
    $(document).ready(function () {
        $("#carrito").button({icons: {primary: 'ui-icon-cart'}, text: false});
        $("#admin").button({icons: {primary: 'ui-icon-wrench'}, text: false});
        $("#ayuda").button({icons: {primary: 'ui-icon-help'}});
        $("#problema").button({icons: {primary: 'ui-icon-alert'}, text: false});
        $("#radiooline").button({icons: {primary: 'ui-icon-signal-diag'}, text: false});
        $("#boxAyuda").css('float', 'right');

        alto = $("#header").css('height');
        largo = $("#header").css('width');
        posArr = $("#header > h1").position().top - 80;
        posIzq = $("#header > h1").position().left + 200;
        valor = 0 + " " + 0;
        valor = posIzq + " " + posArr;
        $("#boxAyuda").position({
            my: "top",
            of: "#header",
            offset: valor,
            collision: "flip"
        });
        capa = $("#boxAyuda");
        var posicion = "";
        posicion += "Posición relativo al documento:\nLEFT: " + capa.offset().left + "\nTOP:" + capa.offset().top;
        posicion += "\n\nPosición si no tuviera margen:\nLEFT: " + capa.position().left + "\nTOP:" + capa.position().top;
        //alert(posicion);

    });
</script>