// Define the entry point

$(document).ready(function () {
    /*      $("#dialogo").dialog({ autoOpen: true });
     $('#dialogo').button();
     $('#dialogo').colorbox({  open: true,
     width:"50%",
     height:"50%",
     onClosed: function(){$("input[name*='puesto']").focus();},
     onComplete: function(){$("#nombreTXT").focus();}
     });*/
    $("#fecha").datepicker();
    $("#neto").change(function () {
        //alert($(this).val());
    });
    $('#addFac').submit(function (evnt) {
        //evnt.preventDefault();
        var importe = parseFloat($("#importe").val());
        var neto = parseFloat($("#neto").val());
        var ivamin = parseFloat($("#ivamin").val());
        var ivamax = parseFloat($("#ivamax").val());
        var ingbru = parseFloat($("#ingbru").val());
        var impint = parseFloat($("#impint").val());
        var percep = parseFloat($("#percep").val());
        if (importe == neto + ivamin + ivamax + ingbru + impint + percep) {
            $(this).submit();
        }
    });
});
    

