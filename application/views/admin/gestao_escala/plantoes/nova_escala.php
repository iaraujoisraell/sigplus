<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal fade" id="add_escala_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="add-title"><?php echo 'Gerar Escala'; ?></span>
                   
                </h4>
            </div>
            <?php echo form_open('admin/gestao_plantao/manage_escala',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('id'); ?>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <label>Competência</label>
                        <select name="competencia_id" id="competencia_id"  required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($competencias as $comp){ ?>
                             <option value="<?php echo $comp['id']; ?>"><?php echo $comp['mes'].' / '.$comp['ano']; ?></option>   
                            <?php } ?>
                        </select>
                        <br><br>
                        
                        <?php 
                        /*
                         * 
                        
                        <label>Unidades Hospitalares</label>
                        <select name="unidade_id" id="unidade_id" onchange="setores(this.value)" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($unidades_hospitalares as $unidades){ ?>
                             <option value="<?php echo $unidades['id']; ?>"><?php echo $unidades['razao_social']; ?></option>   
                            <?php } ?>
                        </select>
                        <br><br>
                        <div id="setores_unidades">
                             
                        </div>
                        <div id="horario_plantao">
                             
                        </div>
                        <?php */ ?>
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
   
  }
// Items add/edit

function manage_invoice_items(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        
   alert_float('danger', data.responseText);
    alert_float('success', response.message);
    $('#add_escala_modal').modal('hide');
    location.reload();  
    });
}

/*
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
                }
               // location.reload();  
               // add_item_to_preview(response.item.id);
            } else {
                // Is general items view
               // $('.table-conta_pagar').DataTable().ajax.reload(null, false);
               location.reload();  
            }
            alert_float('success', response.message);
           
        }
        $('#add_escala_modal').modal('hide');
    }).fail(function (data) {
        alert_float('danger', data.responseText);
        location.reload();  
    });
    return false;
}*/

function init_item_js() {
    

    // Items modal show action
    $("body").on('show.bs.modal', '#add_escala_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#add_escala_modal');
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

    $("body").on("hidden.bs.modal", '#add_escala_modal', function (event) {
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


function setores(unidade_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("gestao_plantao/retorno_setores"); ?>",
        data: {
          unidade_id: unidade_id        
        },
        success: function(data) {
          $('#setores_unidades').html(data);
        }
      });
}

function horarios(setor_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("gestao_plantao/retorno_horarios_plantao"); ?>",
        data: {
          setor_id: setor_id,
          competencia_id: $('#competencia_id').val()
        },
        success: function(data) {
          $('#horario_plantao').html(data);
        }
      });
}
</script>
