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
          
              <?php echo form_open(admin_url('tabela_preco/add_repasse/')); ?>
                                <hr class="hr-panel-heading" />
                                
                                <div id="procedimentos">
                                   <?php $this->load->view('admin/invoice_items/select_item_padrao'); ?>
                                </div>
                               
                                <br>                                

                                <?php $hoje = date('Y-m-d'); ?>
                                
                                 <label>Data Início da Vigência</label>
                                 <input type="date" required="true" name="data_inicio" value="<?php echo $hoje; ?>" class="form-control">
                                
                               <!--  <label>Data Fim da Vigência</label>
                                 <input type="date" name="data_fim"  class="form-control"> -->
                                 <?php

                                 ?>
                                 
                                 <br>
                                 <?php
                                $amount = '0.00';
                                 //  echo render_input('valor','valor_repasse',$amount,'text',array('max'=>$saldo_final)); ?> 
                                <div class="col-md-6">
                                <label>Valor 1 da Vigência (principal)</label>
                                <input type="text" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" value="<?php echo $amount; ?>"  class="form-control">
                                </div>
                                 <div class="col-md-6">
                                <div class="form-group">
                        
                                    <label for="paymentmode" class="control-label"><?php echo _l('payment_mode'); ?></label>

                                    <select class="selectpicker" name="paymentmode[]" multiple="true" data-width="100%" data-none-selected-text="<?php echo 'NÃO SE APLICA'; ?>">
                                        <option value=""></option>
                                        <?php

                                        foreach($payment_modes as $mode){
                                            $selected = '';
                                            
                                            $list = array("1", "2", "4", "6", "9", "10", "13");
                                            if (!in_array($mode['id'], $list)) {
                                                $selected = 'selected="true"';
                                            }
                                            
                                            ?>
                                        <option <?php echo $selected; ?>  value="<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></option>
                                       
                                        <?php } ?>
                                    </select>
                                </div>
                                </div>
                                 
                                 <div class="col-md-6">
                                <label>Valor 2 da Vigência</label>
                                <input type="text" name="valor2" id="valor2" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" value="<?php echo $amount; ?>"  class="form-control">
                                </div>
                                 <div class="col-md-6">
                                <div class="form-group">
                        
                                    <label for="paymentmode" class="control-label"><?php echo _l('payment_mode'); ?></label>

                                    <select class="selectpicker" name="paymentmode2[]" multiple="true" data-width="100%" data-none-selected-text="<?php echo 'NÃO SE APLICA'; ?>">
                                        <option value=""></option>
                                        <?php

                                        foreach($payment_modes as $mode){
                                            
                                            $selected = '';
                                            
                                            $list = array("1", "2", "4", "6", "9", "10", "13");
                                            if (in_array($mode['id'], $list)) {
                                                $selected = 'selected="true"';
                                            }
                                            ?>
                                        
                                        <option <?php echo $selected; ?>  value="<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></option>
                                        
                                        <?php } ?>
                                    </select>
                                </div>
                                </div>
                                <?php echo render_textarea( 'observacao', 'observacao', '',array( 'rows'=>5)); ?>
                                <button class="btn btn-info pull-right mbot15">
                                    <?php echo _l( 'submit'); ?>
                                </button>
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
