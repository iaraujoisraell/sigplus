<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal fade" id="add_registro_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                     <span class="edit-title"><?php echo 'Add Registro Configuração de Escala'; ?></span>
                   
                   
                </h4>
            </div>
            <?php echo form_open('admin/unidades_hospitalares/manage_configuracao',array('id'=>'invoice_item_form')); ?>
            <?php echo form_hidden('id'); ?>
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Horário</label>
                        <select name="horario_id" id="horario_id" onchange="plano_conta_categoria(this.value)" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($horarios as $hora){ ?>
                             <option value="<?php echo $hora['id']; ?>"><?php echo $hora['hora_inicio'].' - '.$hora['hora_fim']; ?></option>   
                            <?php } ?>
                        </select>     
                        <br>
                        <label>Setores</label>
                        <select name="setor_id" id="setor_id" onchange="plano_conta_categoria(this.value)" required="true" class="form-control">
                            <option checked="true" value="">Selecione</option>
                            <?php foreach ($setores as $setor){ ?>
                             <option value="<?php echo $setor['id']; ?>"><?php echo $setor['nome']; ?></option>   
                            <?php } ?>
                        </select>
                        <br>
                        <label>Segunda-Feira</label>
                        <input type="number"  name="segunda" id="segunda"  value="1" required="true"  class="form-control">
                        <br>
                        <label>Terça-Feira</label>
                        <input type="number"  name="terca" id="terca"  value="1" required="true"  class="form-control">
                        <br>
                        <label>Quarta-Feira</label>
                        <input type="number"  name="quarta" id="quarta"  value="1" required="true"  class="form-control">
                        <br>
                        <label>Quinta-Feira</label>
                        <input type="number"  name="quinta" id="quinta"  value="1" required="true"  class="form-control">
                        <br>
                        <label>Sexta-Feira</label>
                        <input type="number"  name="sexta" id="sexta"  value="1" required="true"  class="form-control">
                        <br>
                        <label>Sábado</label>
                        <input type="number"  name="sabado" id="sabado"  value="1" required="true"  class="form-control">
                        <br>
                        <label>Domingo</label>
                        <input type="number"  name="domingo" id="domingo"  value="1" required="true"  class="form-control">
                         
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
               // $('.table-conta_pagar').DataTable().ajax.reload(null, false);
               location.reload();  
            
            alert_float('success', response.message);
        }
        $('#add_horario_plantao_modal').modal('hide');
    }).fail(function (data) {
        alert_float('danger', data.responseText);
    });
    return false;
}
function init_item_js() {
    

    // Items modal show action
    $("body").on('show.bs.modal', '#add_horario_plantao_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#add_horario_plantao_modal');
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
            
            requestGetJSON('horario_plantao/get_horario_by_id/' + id).done(function (response) {
                $itemModal.find('input[name="id"]').val(response.id);
                
                $itemModal.find('#id').selectpicker('val', response.id);
                $itemModal.find('input[name="hora_inicio"]').val(response.hora_inicio);
                $itemModal.find('input[name="hora_fim"]').val(response.hora_fim);
                $itemModal.find('input[name="plantao"]').val(response.plantao);
                $itemModal.find('#ativo').selectpicker('val', response.ativo);
                
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

    $("body").on("hidden.bs.modal", '#add_horario_plantao_modal', function (event) {
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
