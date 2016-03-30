$(function () {
    $('.nav-tabs').tab();
    $('.tip').tooltip();
    //$('.datepicker').datepicker({ format: '<?php //echo date_format_datepicker(); ?>'});
    $('.create-invoice').click(function () {
        $('#modal-placeholder').load("<?php echo site_url('invoices/ajax/modal_create_invoice'); ?>");
    });
    $('.create-quote').click(function () {
        $('#modal-placeholder').load("<?php echo site_url('quotes/ajax/modal_create_quote'); ?>");
    });
    $('#btn_quote_to_invoice').click(function () {
        quote_id = $(this).data('quote-id');
        $('#modal-placeholder').load("<?php echo site_url('quotes/ajax/modal_quote_to_invoice'); ?>/" + quote_id);
    });
    $('#btn_copy_invoice').click(function () {
        invoice_id = $(this).data('invoice-id');
        $('#modal-placeholder').load("<?php echo site_url('invoices/ajax/modal_copy_invoice'); ?>", {invoice_id: invoice_id});
    });
    $('#btn_copy_quote').click(function () {
        quote_id = $(this).data('quote-id');
        $('#modal-placeholder').load("<?php echo site_url('quotes/ajax/modal_copy_quote'); ?>", {quote_id: quote_id});
    });
    $('.client-create-invoice').click(function () {
        $('#modal-placeholder').load("<?php echo site_url('invoices/ajax/modal_create_invoice'); ?>", {
            client_name: $(this).data('client-name')
        });
    });
    $('.client-create-quote').click(function () {
        $('#modal-placeholder').load("<?php echo site_url('quotes/ajax/modal_create_quote'); ?>", {
            client_name: $(this).data('client-name')
        });
    });
    $(document).on('click', '.invoice-add-payment', function () {
        invoice_id = $(this).data('invoice-id');
        invoice_balance = $(this).data('invoice-balance');
        $('#modal-placeholder').load("<?php echo site_url('payments/ajax/modal_add_payment'); ?>", {
            invoice_id: invoice_id,
            invoice_balance: invoice_balance
        });
    });

});