<div id="column2">
    <h3>Tareas regulares</h3>
    <ul>
        <?php foreach ($tareas as $t): ?>
            <li><?php echo anchor($t[0], $t[1], (isset($t[2])) ? $t[2] : "") ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<script>
    $(document).ready(function () {
        $('.ajaxLoad').click(function (e) {
            e.preventDefault();
            url = $(this).attr('href');
            var titulo = $(this).html();
            var dialogOpts = {
                modal: true,
                bgiframe: true,
                autoOpen: false,
                height: 300,
                width: 500,
                title: titulo,
                draggable: true,
                resizeable: true,
                close: function () {
                    $('#ventanaAjax').dialog("destroy");
                }
            };
            $("#ventanaAjax").dialog(dialogOpts);   //end dialog
            $("#ventanaAjax").load(url, [], function () {
                    $("#ventanaAjax").dialog("open");
                }
            );
        });
    });
</script>
