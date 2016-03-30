$(document).ready(function () {
    $(".casilla").click(function () {
        if ($(this).attr('checked'))
            $(this).parent().parent().addClass('ui-state-highlight');
        else
            $(this).parent().parent().removeClass('ui-state-highlight');
    });
});
