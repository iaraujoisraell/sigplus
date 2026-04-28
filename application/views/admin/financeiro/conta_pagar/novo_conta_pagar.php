<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script> 
function Mudarestado(el) {
    var display = document.getElementById(el).style.display;
    if(display == "none")
        document.getElementById(el).style.display = 'block';
    else
        document.getElementById(el).style.display = 'none';
}

 function moeda(a, e, r, t) {
    let n = ""
      , h = j = 0
      , u = tamanho2 = 0
      , l = ajd2 = ""
      , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}
</script>



<div class="modal fade" id="add_conta_pagar_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo 'Editar Conta a Pagar'; ?></span>
                    <span class="add-title"><?php echo 'Add Conta a Pagar'; ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/financeiro/manage_conta_pagar',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('id'); ?>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Categoria</label>
                        <select name="categoria_id" id="categoria_id" onchange="plano_conta_categoria(this.value)" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($categorias_financeira as $cat){ ?>
                             <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>   
                            <?php } ?>
                        </select>     
                   
                        <br>
                        <div id="plano_contas">
                             
                        </div>
                        
                        <label>Título/ Descrição</label>
                        <input type="text" name="complemento" id="complemento" required="true" class="form-control">
                        <br>
                        
                        <label>Data de Emissão</label>
                        <?php $data_hoje = date("Y-m-d"); ?>
                        <input type="date" name="data_emissao" id="data_emissao" required="true" value="<?php echo $data_hoje; ?>" class="form-control">
                        <br>
                        <?php echo render_input('numero_documento','Número Documento'); ?>
                        
                        <label>Tipo Documento</label>
                        <select name="tipo_documento" id="tipo_documento" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                           <?php foreach ($tipos_documentos as $tipo){ ?>
                             <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['name']; ?></option>
                            <?php } ?>
                        </select>
                        <br><br>
                        
                        <label>Fornecedores</label>
                        <select name="cliente_fornecedor_id" id="cliente_fornecedor_id"  required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($fornecedores as $for){ ?>
                             <option value="<?php echo $for['id']; ?>"><?php echo $for['company'].'('.$for['vat'].')'; ?></option>   
                            <?php } ?>
                        </select>
                        <br><br>
                        <label>Conta/Banco</label>
                        <select name="banco_id" id="banco_id" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($bancos as $banco){ ?>
                             <option value="<?php echo $banco['id']; ?>"><?php echo $banco['banco']. ' ( AG: '.$banco['agencia'].' [Conta : '.$banco['numero_conta'].']) '; ?></option>   
                            <?php } ?>
                        </select>
                        <br><br>
                        <label>Centro de Custo</label>
                        <select name="centro_custo_id" id="centro_custo_id" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($centro_custo as $cc){ ?>
                             <option value="<?php echo $cc['id']; ?>"><?php echo $cc['descricao'].' ['.$cc['tipo'].'] '; ?></option>   
                            <?php } ?>
                        </select>  
                        <br><br>
                        <label>Valor Total(Contrato)</label>
                        <input type="text" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="20" placeholder="1000,00"  class="form-control" required="true">
                        <br>
                        <label>Valor da Parcela igual ao valor do contrato?</label>
                        <select name="valor_parcela_contrato" id="valor_parcela_contrato" required="true" class="form-control">
                            <option checked="true" value="0">Não</option>
                            <option checked="true" value="1">Sim</option>
                        </select>  
                        <br><br>
                        <label>Data 1a Parcela</label>
                        <?php $data_hoje = date("Y-m-d"); ?>
                        <input type="date" name="data_vencimento" id="data_vencimento" required="true" value="<?php echo $data_hoje; ?>" class="form-control">
                        <br>
                        <label>Quantidade Parcelas</label>
                        <input type="number" name="parcela" id="parcela" required="true" value="<?php echo '1'; ?>" class="form-control">
                        
                        <label>Iniciar parcela em :</label>
                        <input type="number" name="inicio_parcela" id="inicio_parcela" required="true" value="<?php echo '1'; ?>" class="form-control">
                        
                         
                </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="send" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
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
                    var group = item_select.find('[data-group-id="' + response.item.group_id + '"]');
                    if (group.length == 0) {
                        var _option = '<optgroup label="' + (response.item.group_name == null ? '' : response.item.group_name) + '" data-group-id="' + response.item.group_id + '">' + _option + '</optgroup>';
                        if (item_select.find('[data-group-id="0"]').length == 0) {
                            item_select.find('option:first-child').after(_option);
                        } else {
                            item_select.find('[data-group-id="0"]').after(_option);
                        }
                    } else {
                        group.prepend('<option data-subtext="' + response.item.long_description + '" value="' + response.item.itemid + '">(' + accounting.formatNumber(response.item.rate) + ') ' + response.item.descricao + '</option>');
                    }
                }
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
        $('#add_conta_pagar_modal').modal('hide');
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
    $("body").on('show.bs.modal', '#add_conta_pagar_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#add_conta_pagar_modal');
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
                $itemModal.find('input[name="data_emissao"]').val(response.data_emissao);
                $itemModal.find('input[name="numero_documento"]').val(response.numero_documento);
                $itemModal.find('input[name="valor_parcela_contrato"]').val(response.valor_parcela_contrato);
                $itemModal.find('input[name="complemento"]').val(response.complemento);
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

    $("body").on("hidden.bs.modal", '#add_conta_pagar_modal', function (event) {
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

 var formID = document.getElementById("invoice_item_form");
var send = $("#send");

$(formID).submit(function(event){
  if (formID.checkValidity()) {
    send.attr('disabled', 'disabled');
  }
});

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
