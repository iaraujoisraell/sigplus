<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal fade" id="add_pagamento_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo 'Confirmar Pagamento'; ?></span>
                   
                </h4>
            </div>
            <?php echo form_open('admin/financeiro/manage_conta_pagar_parcelas',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('id'); ?>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <label>Parcelas</label>
                        <input type="number" readonly="true" name="parcela" id="parcela" required="true" value="<?php echo '1'; ?>" class="form-control">
                      
                        <label>Valor da Parcela</label>
                        <input type="text" readonly="true" name="valor_parcela" id="valor_parcela" onKeyPress="return(moeda(this,'.',',',event))" maxlength="10"   class="form-control" required="true">
                         
                        <br>
                        <label>Data Vencimento</label>
                        <input type="date" readonly="true" name="data_vencimento" id="data_vencimento" required="true"  class="form-control">
                        <br>
                         <label>Conta/Banco</label>
                        <select name="banco_id"  id="banco_id" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($bancos as $banco){ ?>
                             <option value="<?php echo $banco['id']; ?>"><?php echo $banco['banco']. ' ( AG: '.$banco['agencia'].' [Conta : '.$banco['numero_conta'].']) '; ?></option>   
                            <?php } ?>
                        </select>
                        <br><br>
                        <label>Data do Pagamento</label>
                        <input type="date"  name="data_pagamento" id="data_pagamento"  value="<?php echo date('Y-m-d'); ?>" required="true"  class="form-control">
                        <br>
                         <label>Formas Pagamento</label>
                        <select name="forma_pagamento"  id="forma_pagamento" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($formas_pagamentos as $forma){ ?>
                             <option value="<?php echo $forma['id']; ?>"><?php echo $forma['name']; ?></option>   
                            <?php } ?>
                        </select>
                         
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
            if ($("body").find('.accounting-template').length > 0) {
              
                if (!item_select.hasClass('ajax-search')) {
                    item_select.selectpicker('refresh');
                } else {

                    item_select.contents().filter(function () {
                        return !$(this).is('.newitem') && !$(this).is('.newitem-divider');
                    }).remove();

                    var clonedItemsAjaxSearchSelect = item_select.clone();
                    item_select.selectpicker('destroy').remove();
                    $("body").find('.items-select-wrapper').append(clonedItemsAjaxSearchSelect);
                    init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
                }

                add_item_to_preview(response.item.id);
            } else {
                // Is general items view
               // $('.table-conta_pagar').DataTable().ajax.reload(null, false);
               location.reload();  
            }
            alert_float('success', response.message);
        }
        $('#add_pagamento_modal').modal('hide');
    }).fail(function (data) {
        alert_float('danger', data.responseText);
    });
    return false;
}
function init_item_js() {
    

    // Items modal show action
    $("body").on('show.bs.modal', '#add_pagamento_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#add_pagamento_modal');
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
            
            requestGetJSON('financeiro/get_conta_pagar_parcela_by_id/' + id).done(function (response) {
                $itemModal.find('input[name="id"]').val(response.id);
                
                $itemModal.find('#banco_id').selectpicker('val', response.banco_id);
                
                $itemModal.find('input[name="valor_parcela"]').val(response.valor_parcela);
                $itemModal.find('input[name="data_vencimento"]').val(response.data_vencimento);
                $itemModal.find('input[name="parcela"]').val(response.parcela);
                
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

    $("body").on("hidden.bs.modal", '#add_pagamento_modal', function (event) {
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
