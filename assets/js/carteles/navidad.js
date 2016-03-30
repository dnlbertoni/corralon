$(document).ready(function () {
    $("#codart").submit(function (evnt) {
        evnt.preventDefault();
        valor = $("#codigobarra").val();
        pagina = $("#pagina").val()
        $.ajax({
                url: pagina,
                contentType: "application/x-www-form-urlencoded",
                global: false,
                type: "POST",
                data: ({codigobarra: valor}),
                dataType: "html",
                async: true,
                success: function (msg) {
                    $("#articulos").append(msg);
                    $("#codigobarra").val('');
                    $("#codigobarra").focus();
                }
            }
        ).responseText;
    });
});
