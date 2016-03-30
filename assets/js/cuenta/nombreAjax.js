$(document).ready(function () {
    $('#buscoNombre').submit(function (evnt) {
        evnt.preventDefault(); //Evitamos que el evento submit siga en ejecución, evitando que se recargue toda la página
        var valor = $('#nombreTXT').val();
        var accion = $(this).attr('action');
        var dataString = 'nombreTXT=' + valor;
        //alert (dataString);return false;  
        $.ajax({
            type: "POST",
            url: accion,
            data: {'nombreTXT': valor},
            success: function (data) {
                $('#MuestroDatos').html(data)
            }
        });
    });
});
