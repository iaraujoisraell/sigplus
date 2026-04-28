<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>

<style>
 body {
 font-family:'Open Sans';
 background:#f1f1f1;
 }
 h3 {
 margin-top: 7px;
 font-size: 16px;
 }

 .install-row.install-steps {
 margin-bottom:15px;
 box-shadow: 0px 0px 1px #d6d6d6;
 }

 .control-label {
 font-size:13px;
 font-weight:600;
 }
 .padding-10 {
 padding:10px;
 }
 .mbot15 {
 margin-bottom:15px;
 }
 .bg-default {
 background: #03a9f4;
 border:1px solid #03a9f4;
 color:#fff;
 }
 .bg-success {
 border: 1px solid #dff0d8;
 }
 .bg-not-passed {
 border:1px solid #f1f1f1;
 border-radius:2px;
 }
 .bg-not-passed {
 border-right:0px;
 }
 .bg-not-passed.finish {
 border-right:1px solid #f1f1f1 !important;
 }
 .bg-not-passed h5 {
 font-weight:normal;
 color:#6b6b6b;
 }
 .form-control {
 box-shadow:none;
 }
 .bold {
 font-weight:600;
 }
 .col-xs-5ths,
 .col-sm-5ths,
 .col-md-5ths,
 .col-lg-5ths {
 position: relative;
 min-height: 1px;
 padding-right: 15px;
 padding-left: 15px;
 }
 .col-xs-5ths {
 width: 20%;
 float: left;
 }
 b {
 font-weight:600;
 }
 .bootstrap-select .btn-default {
 background: #fff !important;
 border: 1px solid #d6d6d6 !important;
 box-shadow: none;
 color: #494949 !important;
 padding: 6px 12px;
 }
</style>

	<div class="content">
		<div class="row">
                    <section class="content-header">
                      <h1>
                       Editar  Títulos a Pagar
                      </h1>
                      <ol class="breadcrumb">
                        <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Financeiro </a></li>
                        <li class="active"><a href="<?php echo admin_url('financeiro'); ?>">Títulos a pagar </a></li>
                      </ol>
                    </section>
                    <div class="panel_s">
                                
                            <?php echo form_open('admin/financeiro/manage_conta_pagar',array('id'=>'invoice_item_form')); ?>
                        <input type="hidden" name="id" id="id" value="<?php echo $titulo->id; ?>" required="true" class="form-control">
                            

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="col-md-6">
                                        <label>Categoria</label>
                                        <select name="categoria_id" id="categoria_id" onchange="plano_conta_categoria(this.value)" required="true" class="form-control">
                                            <option checked="true" value="">Selecione</option>
                                            <?php foreach ($categorias_financeira as $cat){
                                                $selectd = "";
                                                if($titulo->categoria_id == $cat['id']){
                                                    $selectd = 'selected';
                                                }
                                                ?>
                                             <option value="<?php echo $cat['id']; ?>" <?php echo $selectd ?>><?php echo $cat['name']; ?></option>   ;
                                            <?php } ?>
                                             
                                           
                                        </select>     
                                        </div>
                                        <div class="col-md-6">
                                        <label>Plano de Contas</label>
                                        <select name="plano_conta_id" id="plano_conta_id" required="true" class="form-control">
                                            <option checked="true" value="">Selecione</option>
                                            <?php foreach ($planos_conta as $plano){
                                                $selectd = "";
                                                if($titulo->plano_conta_id == $plano['id']){
                                                    $selectd = 'selected';
                                                }
                                                ?>
                                             <option value="<?php echo $plano['id']; ?>" <?php echo $selectd ?>><?php echo $plano['descricao']; ?></option>   ;
                                            <?php } ?>
                                        </select>
                                        </div>
                                        <div class="col-md-12">
                                            <br>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Título/ Descrição</label>
                                        <input type="text" name="complemento" id="complemento" value="<?php echo $titulo->complemento; ?>" required="true" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                        <label>Data de Emissão</label>
                                        <?php $data_hoje = date("Y-m-d"); ?>
                                        <input type="date" name="data_emissao" id="data_emissao" required="true" value="<?php echo $titulo->data_emissao; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-12">
                                            <br>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Número Documento</label>
                                        <input type="text" name="numero_documento" id="numero_documento" value="<?php echo $titulo->numero_documento; ?>"  class="form-control">    
                                        </div>    
                                        <div class="col-md-6">
                                        <label>Tipo Documento</label>
                                        <select name="tipo_documento" id="tipo_documento"  required="true" class="form-control">
                                            <option checked="true" value="">Selecione</option>
                                           <?php foreach ($tipos_documentos as $tipo){
                                               $selectd = "";
                                                if($titulo->tipo_documento == $tipo['id']){
                                                    $selectd = 'selected';
                                                }
                                               
                                               ?>
                                             <option value="<?php echo $tipo['id']; ?>" <?php echo $selectd ?>><?php echo $tipo['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        </div>
                                        <div class="col-md-12">
                                            <br>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Fornecedores</label>
                                        <select name="cliente_fornecedor_id" id="cliente_fornecedor_id"  required="true" class="form-control">
                                            <option checked="true" value="">Selecione</option>
                                            <?php foreach ($fornecedores as $for){
                                                $selectd = "";
                                                if($titulo->cliente_fornecedor_id == $for['id']){
                                                    $selectd = 'selected';
                                                }
                                                ?>
                                             <option value="<?php echo $for['id']; ?>" <?php echo $selectd ?>><?php echo $for['company'].'('.$for['vat'].')'; ?></option>   
                                            <?php } ?>
                                        </select>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Conta/Banco</label>
                                        <select name="banco_id" id="banco_id" required="true" class="form-control">
                                            <option checked="true" value="">Selecione</option>
                                            <?php foreach ($bancos as $banco){ 
                                                $selectd = "";
                                                if($titulo->banco_id == $banco['id']){
                                                    $selectd = 'selected';
                                                }
                                                ?>
                                             <option value="<?php echo $banco['id']; ?>" <?php echo $selectd ?>><?php echo $banco['banco']. ' ( AG: '.$banco['agencia'].' [Conta : '.$banco['numero_conta'].']) '; ?></option>   
                                            <?php } ?>
                                        </select>
                                        </div>
                                        <div class="col-md-12">
                                            <br>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Centro de Custo</label>
                                        <select name="centro_custo_id" id="centro_custo_id" required="true" class="form-control">
                                            <option checked="true" value="">Selecione</option>
                                            <?php foreach ($centro_custo as $cc){ 
                                                $selectd = "";
                                                if($titulo->centro_custo_id == $cc['id']){
                                                    $selectd = 'selected';
                                                }
                                                ?>
                                             <option value="<?php echo $cc['id']; ?>" <?php echo $selectd ?>><?php echo $cc['descricao'].' ['.$cc['tipo'].'] '; ?></option>   
                                            <?php } ?>
                                        </select>  
                                        </div>
                                        <div class="col-md-6">
                                        <label>Valor Total(Contrato)</label>
                                        <input type="text" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="20" value="<?php echo $titulo->valor; ?>" placeholder="1000,00"  class="form-control" required="true">
                                        </div>
                                        <div class="col-md-12">
                                            <br>
                                        </div>
                                       
                                        <div class="col-md-6">
                                        <label>Data 1a Parcela</label>
                                        <?php $data_hoje = date("Y-m-d"); ?>
                                        <input type="date" name="data_vencimento" id="data_vencimento" value="<?php echo $titulo->data_vencimento; ?>" required="true"  class="form-control">
                                        </div>
                                        
                                        <div class="col-md-6">
                                        <label>Quantidade Parcelas</label>
                                        <input type="number" name="parcela" id="parcela" required="true" value="<?php echo '1'; ?>" value="<?php echo $titulo->parcela; ?>" class="form-control">
                                        </div>

                                </div>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <a href="<?php echo admin_url('financeiro'); ?>"  class="btn btn-default"><?php echo _l('close'); ?></a> 
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
		</div>
	</div>

<?php init_tail(); ?>

<script>


  function procedimentos_table_reload() {
      //alert('aqui');
       var CustomersServerParams = {};
     //  $.each($('._hidden_inputs._filters input'),function(){
     //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
     // });
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['natureza'] = '[name="natureza"]'; 
     if ($.fn.DataTable.isDataTable('.table-conta_pagar')) {
       $('.table-conta_pagar').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-conta_pagar', admin_url+'financeiro/table_conta_pagar', [0], [0], CustomersServerParams, [1, 'desc']);
   // tAPI.ajax.reload();

    // filtraCategoria();
   }
   
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

  function filtraCategoria() {

      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("invoice_items/retorno_filtro_categoria"); ?>",
        data: {
          convenios_procedimentos: $('#convenios_procedimentos').val()
          },
        success: function(data) {
          $('#filtro_categoria').html(data);
        }
      });
    }

  function items_bulk_action(event) {
    if (confirm_delete()) {
      var mass_delete = $('#mass_delete').prop('checked');
      var ids = [];
      var data = {};

      if(mass_delete == true) {
        data.mass_delete = true;
      }

      var rows = $('.table-conta_pagar').find('tbody tr');
      $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') === true) {
          ids.push(checkbox.val());
        }
      });
      data.ids = ids;
      $(event).addClass('disabled');
      setTimeout(function() {
        $.post(admin_url + 'invoice_items/bulk_action', data).done(function() {
          window.location.reload();
        }).fail(function(data) {
          alert_float('danger', data.responseText);
        });
      }, 200);
    }
  }


 </script>
</body>
</html>
