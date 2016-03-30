// Define the entry point

$(document).ready(function () {
//      $("#dialogo").dialog({ autoOpen: true });
    $("#numero").focus();
    $("#fecha").datepicker({
        altField: '#fecha',
        altFormat: 'yy-mm-dd',
        autoSize: true,
        buttonImage: 'http://192.168.0.10/citrus/rsc/img/calendar.gif',
        buttonImageOnly: true,
        defaultDate: '-1m',
        maxDate: '-1d',
        showAnim: 'fold',
        showOn: 'button',
    });
    $("#ivatot").blur(function () {
        var importe = parseFloat($("#importe").val());
        var ivatot = parseFloat($('#ivatot').val());
        var neto = round(importe - ivatot);
        $("#neto").val(neto);
        var coef = 0.90;
        var coefmin = 1 - coef;
        var ivamax = round(neto * coef * 0.21);
        var ivamin = round(neto * coefmin * 0.105);
        var diff = round(ivatot - (ivamin + ivamax ));
        var coefdiff = ( diff / ivatot );
        for (i = 0; i < 13; i++) {
            coef += coefdiff;
            coefmin = 1 - coef;
            ivamax = round(neto * coef * 0.21);
            ivamin = round(neto * coefmin * 0.105);
            diff = round(ivatot - (ivamin + ivamax ));
            coefdiff = ( diff / ivatot );
        }
        ;
        ivamin = round(ivatot - ivamax);
        $("#ivamin").val(ivamin);
        $("#ivamax").val(ivamax);
    });
    $("#neto").change(function () {
        //alert($(this).val());
    });
    $('#addFac').submit(function (evnt) {
        //evnt.preventDefault();
        var importe = parseFloat($("#importe").val());
        var neto = parseFloat($("#neto").val());
        var ivamin = parseFloat($("#ivamin").val());
        var ivamax = parseFloat($("#ivamax").val());
        var diff = importe - neto - ivamin - ivamax;
        //alert(diff);
        //if( diff == 0 ){$(this).submit();};
    });
});


function round(valor) {
    var redondeo = 100;
    valor = parseFloat(valor);
    valor = Math.round(valor * redondeo) / redondeo;
    return valor;
}    

