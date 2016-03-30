$(document).ready(function () {
    $("#botSel").button();
    $("#botSel").click(function () {
        $("input:radio").each(function () {
            $(this).attr("checked", 1);
        });
        SumoTotal();
        SumoIva();
    });
    $("input:radio").change(function () {
        SumoTotal();
        SumoIva();
    });
    $('table').css('font-size', '10px');
});


function SumoTotal() {
    var Total = 0;
    var Cant = 0;
    $("input:radio:checked").each(function () {
        var valor = $(this).parent().prev().prev().text();
        valor = parseFloat(valor) * parseInt($(this).val());
        Total += valor;
        Cant += parseInt($(this).val());
    });
    Total = Math.round(Total * Math.pow(10, 2)) / Math.pow(10, 2);
    $("#totimp").html(Total);
    $("#canFac").html(Cant);
}

function SumoIva() {
    var Total = 0;
    $("input:radio:checked").each(function () {
        var valor = $(this).parent().prev().text();
        valor = parseFloat(valor) * $(this).val();
        Total += valor;
    });
    Total = Math.round(Total * Math.pow(10, 2)) / Math.pow(10, 2);
    $("#totiva").html(Total);
}