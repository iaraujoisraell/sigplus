<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
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
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="panel_s">
          <div class="panel-body">
          
          <?php echo form_open(admin_url('tabela_preco_repasse/add_repasse'),array('id'=>'fechar_caixa-form')); ?>  
              
              <h3>Add Procedimento / Médicos</h3>   
          <div class="panel-body mtop10">
    <div class="row">
        
        <div class="col-md-12">
            <label>Médicos</label>
            <div style="width: 100%" class="items-wrapper select-placeholder<?php if(has_permission('items','','create')){ echo ' input-group-select'; } ?>">
                <div class="items-select-wrapper">
                    <select multiple="true" required="true" style="width: 100%" name="medicoid[]" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?><?php if(has_permission('items','','create')){ echo ' _select_input_group'; } ?>" data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                      <option value="">Selecione o profissional</option>
                        <?php foreach($medicos as $item){ ?>
                       <option value="<?php echo $item['medicoid']; ?>" > <?php echo $item['nome_profissional']; ?></option>
                       <?php } ?>
                    </select>
                 </div>
            </div>  
            <br>
        </div>
        
        <div class="col-md-12">
            <label>Procedimentos</label>
            <div style="width: 100%" class="items-wrapper select-placeholder<?php if(has_permission('items','','create')){ echo ' input-group-select'; } ?>">
                <div class="items-select-wrapper">
                    <select multiple="true"  required="true" style="width: 100%" name="item_id[]" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?><?php if(has_permission('items','','create')){ echo ' _select_input_group'; } ?>" data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                      <option value=""></option>
                      <?php foreach($items as $group_id=>$_items){ ?>
                      <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                       <?php foreach($_items as $item){ ?>
                       <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>"><?PHP echo $item['id']; ?> - (<?php echo app_format_number($item['rate']); ; ?>) <?php echo $item['description'].' ['.$item['convenio'].'] '; ?></option>
                       <?php } ?>
                     </optgroup>
                     <?php } ?>
                   </select>
                 </div>
            </div>  
            <br>
        </div>
        
        <div class="col-md-12">
        <?php $hoje = date('Y-m-d'); ?>
        <label>Data Início da Vigência</label>
        <input type="date" required="true" name="data_inicio" value="<?php echo $hoje; ?>" class="form-control">
        <br>
        </div>
       
        <div class="col-md-6">
            <?php $amount = '0.00'; ?>
            <label>Valor do Procedimento</label>
            <input type="text" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" placeholder="100,00"  class="form-control" required="true">
            <?php //$attrs = array('required'=>true); ?>
            <?php //echo render_input( 'valor', 'valor_repasse','','text',$attrs); ?>
        </div>     
       
        <div class="col-md-6">
         <div class="form-group select-placeholder">
            <label  class="control-label"><?php echo _l('tipo_repasse_medico'); ?></label>
            <select name="tipo" class="selectpicker" data-width="100%" >
               <option value="1" selected> <?php echo 'R$ '; ?> </option>
               <option value="2"><?php echo '% '; ?></option>
              
            </select>
         </div>
        </div>
        
    
        
    </div> 
      <button class="btn btn-info pull-right mbot15">
        <?php echo _l( 'submit'); ?>
    </button>  
    </div>
      
           <?php echo form_close(); ?>
          </div>
</div>
</div>
</div>
</div>
    
</div>

<?php init_tail(); ?>
<script>
    
  $(function(){
  
    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
      
       CustomersServerParams['procedimento_items'] = '[name="procedimento_items"]';
       CustomersServerParams['convenios_procedimentos'] = '[name="convenios_procedimentos"]'; 
       CustomersServerParams['categorias_procedimentos'] = '[name="categorias_procedimentos"]';  
     //  CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';
       var tAPI = initDataTable('.table-invoice-items', admin_url+'invoice_items/table', [0], [0], CustomersServerParams, [1, 'desc']);
        $('select[name="convenios_procedimentos"]').on('change',function(){
          // alert('aqui');
           tAPI.ajax.reload();
          
         // procedimentos_table_reload();
       });
        //filtraCategoria();
  

    if(get_url_param('groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#groups').modal('show');
       },1000);
     }

     $('#new-item-group-insert').on('click',function(){
      var group_name = $('#item_group_name').val();
      if(group_name != ''){
        $.post(admin_url+'invoice_items/add_group',{name:group_name}).done(function(){
         window.location.href = admin_url+'invoice_items?groups_modal=true';
       });
      }
    });

     $('body').on('click','.edit-item-group',function(e){
      e.preventDefault();
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.group_name_plain_text').toggleClass('hide');
      tr.find('.group_edit').toggleClass('hide');
      tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
    });

     $('body').on('click','.update-item-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-group-row-id');
      name = tr.find('.group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_group/'+group_id,{name:name}).done(function(){
         window.location.href = admin_url+'invoice_items';
       });
      }
    });
   });
   
   function procedimentos_table_reload() {
      //alert('aqui');
       var CustomersServerParams = {};
     //  $.each($('._hidden_inputs._filters input'),function(){
     //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
     // });
       CustomersServerParams['procedimento_items'] = '[name="procedimento_items"]';
       CustomersServerParams['categorias_procedimentos'] = '[name="categorias_procedimentos"]';  
       CustomersServerParams['convenios_procedimentos'] = '[name="convenios_procedimentos"]';  
     if ($.fn.DataTable.isDataTable('.table-invoice-items')) {
       $('.table-invoice-items').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-invoice-items', admin_url+'invoice_items/table', [0], [0], CustomersServerParams, [1, 'desc']);
   // tAPI.ajax.reload();
     
    // filtraCategoria();
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

      var rows = $('.table-invoice-items').find('tbody tr');
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
