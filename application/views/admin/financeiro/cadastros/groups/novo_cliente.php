<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="add_cliente_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo 'Editar Cliente'; ?></span>
                    <span class="add-title"><?php echo 'Add Cliente'; ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/financeiro/manage_clientes',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('id'); ?>
           
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('company','Cliente'); ?>
                        <?php echo render_input('vat','CPF/ CNPJ'); ?>
                        <?php echo render_input('phonenumber','Contato'); ?>
                        
                        
                </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
        <?php echo form_close(); ?>
    </div>
</div>
</div>
</div>
<script>
    // Maybe in modal? Eq convert to invoice or convert proposal to estimate/invoice
    if(typeof(jQuery) != 'undefined'){
        init_item_js();
    } else {
     window.addEventListener('load', function () {
       var initItemsJsInterval = setInterval(function(){
            if(typeof(jQuery) != 'undefined') {
                init_item_js();
                clearInterval(initItemsJsInterval);
            }
         }, 1000);
     });
  }
// Items add/edit
function manage_invoice_items(form) {
    var data = $(form).serialize();

    var url = form.action;
    $.post(url, data).done(function (response) {
        response = JSON.parse(response);
        if (response.success == true) {
            var item_select = $('#item_select');
             
                // Is general items view
                $('.table-clientes_financeiro').DataTable().ajax.reload(null, false);
           
            alert_float('success', response.message);
        }
        $('#add_cliente_modal').modal('hide');
    }).fail(function (data) {
        alert_float('danger', data.responseText);
    });
    return false;
}
function init_item_js() {
     // Add item to preview from the dropdown for invoices estimates
    $("body").on('change', 'select[name="item_select"]', function () {
        var itemid = $(this).selectpicker('val');
        if (itemid != '') {
            add_item_to_preview(id);
        }
    });

    // Items modal show action
    $("body").on('show.bs.modal', '#add_cliente_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#add_cliente_modal');
        $('input[name="itemid"]').val('');
        $itemModal.find('input').not('input[type="hidden"]').val('');
        $itemModal.find('textarea').val('');
        $itemModal.find('select').selectpicker('val', '').selectpicker('refresh');
        $itemModal.find('.add-title').removeClass('hide');
        $itemModal.find('.edit-title').addClass('hide');

        var id = $(event.relatedTarget).data('id');
        // If id found get the text from the datatable
        if (typeof (id) !== 'undefined') {

            $('.affect-warning').removeClass('hide');
            $('input[name="itemid"]').val(id);

            requestGetJSON('financeiro/get_clientes_by_id/' + id).done(function (response) {
                $itemModal.find('input[name="id"]').val(response.id);
                $itemModal.find('input[name="company"]').val(response.company);
                $itemModal.find('input[name="vat"]').val(response.vat);
                $itemModal.find('input[name="phonenumber"]').val(response.phonenumber);
                
                
                $.each(response, function (column, value) {
                    if (column.indexOf('rate_currency_') > -1) {
                        $itemModal.find('input[name="' + column + '"]').val(value);
                    }
                });
                
                
               

                $('#custom_fields_items').html(response.custom_fields_html);

                init_selectpicker();
                init_color_pickers();
                init_datepicker();

                $itemModal.find('.add-title').addClass('hide');
                $itemModal.find('.edit-title').removeClass('hide');
                validate_item_form();
            });

        }
    });

    $("body").on("hidden.bs.modal", '#add_cliente_modal', function (event) {
        $('#item_select').selectpicker('val', '');
    });

   validate_item_form();
}
function validate_item_form(){
    // Set validation for invoice item form
    appValidateForm($('#invoice_item_form'), {
        descricao: 'required',
       
    }, manage_invoice_items);
}
</script>
