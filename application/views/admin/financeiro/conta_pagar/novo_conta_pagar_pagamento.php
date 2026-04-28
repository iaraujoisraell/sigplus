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
            <?php echo form_open('admin/financeiro/manage_conta_pagar',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('id'); ?>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Fornecedor</label>
                        <select name="cliente_fornecedor_id" disabled="true" id="cliente_fornecedor_id"  required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($fornecedores as $for){ ?>
                             <option value="<?php echo $for['id']; ?>"><?php echo $for['company']; ?></option>   
                            <?php } ?>
                        </select>
                        <br><br>
                        <label>Conta/Banco</label>
                        <select name="banco_id" disabled="true" id="banco_id" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($bancos as $banco){ ?>
                             <option value="<?php echo $banco['id']; ?>"><?php echo $banco['banco']. ' ( AG: '.$banco['agencia'].' [Conta : '.$banco['numero_conta'].']) '; ?></option>   
                            <?php } ?>
                        </select>
                        
                        <br>
                        <label>Parcelas</label>
                        <input type="number" readonly="true" name="parcela" id="parcela" required="true" value="<?php echo '1'; ?>" class="form-control">
                      
                        <label>Valor da Parcela</label>
                        <input type="text" readonly="true" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="10" placeholder="1000,00"  class="form-control" required="true">
                         
                        <br>
                        <label>Data Vencimento</label>
                      
                        <input type="date" readonly="true" name="data_vencimento" id="data_vencimento" required="true"  class="form-control">
                        
                        <br>
                        <label>Data do Pagamento</label>
                        <input type="date"  name="data_pagamento" id="data_pagamento" required="true"  class="form-control">
                         
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
                $('.table-conta_pagar').DataTable().ajax.reload(null, false);
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
     // Add item to preview from the dropdown for invoices estimates
    $("body").on('change', 'select[name="item_select"]', function () {
        var itemid = $(this).selectpicker('val');
        if (itemid != '') {
            add_item_to_preview(id);
        }
    });

    // Items modal show action
    $("body").on('show.bs.modal', '#add_pagamento_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#add_pagamento_modal');
        $('input[name="itemid"]').val('');
        $itemModal.find('input').not('input[type="hidden"]').val('');
        $itemModal.find('textarea').val('');
        $itemModal.find('select').selectpicker('val', '').selectpicker('refresh');
        $('select[name="tax2"]').selectpicker('val', '').change();
        $('select[name="tax"]').selectpicker('val', '').change();
        $itemModal.find('.add-title').removeClass('hide');
        $itemModal.find('.edit-title').addClass('hide');

        var id = $(event.relatedTarget).data('id');
        // If id found get the text from the datatable
        if (typeof (id) !== 'undefined') {

            $('.affect-warning').removeClass('hide');
            $('input[name="itemid"]').val(id);

            requestGetJSON('financeiro/get_conta_pagar_by_id/' + id).done(function (response) {
                $itemModal.find('input[name="id"]').val(response.id);
                $itemModal.find('#categoria_id').selectpicker('val', response.categoria_id);
                $itemModal.find('input[name="numero_documento"]').val(response.numero_documento);
                $itemModal.find('#plano_conta_id').selectpicker('val', response.plano_conta_id);
                $itemModal.find('#tipo_documento').selectpicker('val', response.tipo_documento);
                $itemModal.find('#cliente_fornecedor_id').selectpicker('val', response.cliente_fornecedor_id);
                $itemModal.find('#banco_id').selectpicker('val', response.banco_id);
                $itemModal.find('#centro_custo_id').selectpicker('val', response.centro_custo_id);
                
                $itemModal.find('input[name="valor"]').val(response.valor);
                $itemModal.find('input[name="data_vencimento"]').val(response.data_vencimento);
                $itemModal.find('input[name="parcela"]').val(response.parcela);
                
                $.each(response, function (column, value) {
                    if (column.indexOf('rate_currency_') > -1) {
                        $itemModal.find('input[name="' + column + '"]').val(value);
                    }
                });
                
                
               

                $('#custom_fields_items').html(response.custom_fields_html);
                plano_conta_categoria(response.categoria_id, response.plano_conta_id)
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



function plano_conta_categoria(categoria_id, plano_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("financeiro/retorno_plano_conta"); ?>",
        data: {
          categoria_id: categoria_id,
          plano_id: plano_id
        },
        success: function(data) {
          $('#plano_contas').html(data);
        }
      });
}
</script>
