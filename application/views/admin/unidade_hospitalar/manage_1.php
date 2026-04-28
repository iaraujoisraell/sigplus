 <style>
                
            .input {
            display: none;
}
            </style>

<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
      
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
             
            <?php if(has_permission('unidade_hospitalar','','delete')){ ?>
             <a href="#" data-toggle="modal" data-table=".table-unidade_hospitalar" data-target="#unidade_hospitalar_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
             <div class="modal fade bulk_actions" id="unidade_hospitalar_bulk_actions" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
               </div>
               <div class="modal-body">
                 <?php if(has_permission('unidade_hospitalar','','delete')){ ?>
                   <div class="checkbox checkbox-danger">
                    <input type="checkbox" name="mass_delete" id="mass_delete">
                    <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                  </div>
                  <!-- <hr class="mass_delete_separator" /> -->
                <?php } ?>
              </div>
              <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
               <a href="#" class="btn btn-info" onclick="unidade_hospitalar_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
             </div>
           </div>
           <!-- /.modal-content -->
         </div>
         <!-- /.modal-dialog -->
       </div>
       <!-- /.modal -->
     <?php } ?>
     <?php hooks()->do_action('before_unidade_hospitalar_page_content'); ?>
     <?php if(has_permission('unidade_hospitalar','','create')){ ?>
       <div class="_buttons">
        <a href="<?php echo admin_url('unidades_hospitalares/add_edit_unidades_hospitalares'); ?>" class="btn btn-info pull-left" ><?php echo 'Nova Unidade Hospitalar'; ?></a>
        <a href="<?php //echo admin_url('unidades_hospitalares/add_edit_unidades_hospitalares'); ?>" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#groups"><?php echo 'Setores'; ?></a>
      </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
    <?php } ?>
      <!-- CONVENIO -->
      <div class="col-md-4">
          <div class="form-group">
               <label for="convenios"><?php echo _l('SETOR'); ?></label>
               <select onchange="unidade_hospitalar_table_reload()" class="selectpicker"
                       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                       name="convenios_procedimentos"
                       id="unidade_hospitalar"
                       data-actions-box="true"
                       multiple="true"
                       data-width="100%"
                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php
                             foreach($setores_medicos as $setor){ 
                           //foreach ($convenios as $convenio) {
                              $selected = ' selected';
                               ?>
                             <option   value="<?php echo $setor['id']; ?>" <?php echo $selected; ?>><?php echo $setor['nome']; ?></option>

                    <?php } ?>
               </select>
            </div>
      </div>
      <!-- CATEGORIA -->
      <div class="col-md-4">
          <div class="form-group">
               <label for="categorias"><?php echo _l('expense_dt_table_heading_category'); ?></label>
               
                <select onchange="unidade_hospitalar_table_reload()" class="selectpicker"
                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                   name="categorias_procedimentos"
                   id="categorias_procedimentos"
                   data-actions-box="true"
                   multiple="true"
                   data-width="100%"
                   data-title=" <?php echo _l('dropdown_non_selected_tex');//* ?>">
                       <?php

                       foreach ($categorias as $categoria) {
                         $selected = ' selected';
                           ?>
                         <option  value="<?php echo $categoria['id']; ?>" <?php echo $selected; ?>><?php echo $categoria['name']; ?></option>

                <?php } ?>
            </select>
               
          </div>
          
      </div>  

      <div class="col-md-4">
          <div class="form-group">

               <?php
                  //$selected = '';

                 // echo render_select('procedimento_items', $procedimentos, array('itemid', array('description','convenio')), 'procedimentos', $selected,array('multiple'=>'true', 'onchange'=>'procedimentos_table_reload()'));
                  ?>
            </div>

      </div>  
     
    <?php
    $table_data = [];

    if(has_permission('unidade_hospitalar','','delete')) {
      //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
        $table_data[] = '<span class="hide"> - </span><label>#</label>';
    }

    $table_data = array_merge($table_data, array(
      'Razão Social',
      'Fantazia',    
      'Contato',
      _l('ativo')
        ));

    $cf = get_custom_fields('unidade_hospitalar');
    foreach($cf as $custom_field) {
      array_push($table_data,$custom_field['name']);
    }
    render_datatable($table_data,'unidade_hospitalar'); ?>
  </div>
</div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('Setores'); ?>
        </h4>
      </div>
        
      <div class="modal-body">
        <?php// if(has_permission('unidade_hospitalar','','create')){ ?>
       
          <div  class="input-group">
            <input type="hidden" id="id" name="id" class="form-control" value="<?php echo $setor_id->id; ?>">
      
           <input type="text" id="nome" name="nome"  class="form-control" value="<?php echo $setor_nome->nome; ?>" placeholder="<?php echo _l('nome do setor'); ?>">
        
              <span>
            
              </span>
           
            <!-----
     <input type="text" id="descricao" name="descricao"  class="form-control" placeholder="<?php echo _l('descrição'); ?>">
         --->
              <span class="input-group-btn">
              </span>
              <span class="input-group-btn">
              <button class="btn btn-info p7" type="button" id="new-item-group-insert"><?php echo _l('novo setor'); ?></button>
              </span>
    
         
           </div>
          
                <hr/>
        <?php// } ?>
         
         
          
        <div class="row">
         <div class="container-fluid">
          
          <table class="table dt-table table-setores-medicos" data-order-col="1" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <th><?php echo _l('nome'); ?></th>
                
              </tr>
            </thead>
             
            <tbody>
           
                  
              <?php foreach($setores_medicos as $setor){ ?>
                
                <tr class="row-has-options" data-group-row-id="<?php echo $setor['id']; ?>">
                   
                  <td data-order="<?php echo $setor['id']; ?>"><?php echo $setor['id']; ?></td>
                 
                  <td data-order="<?php echo $setor['nome']; ?>"><?php echo $setor['nome']; ?></td>
                  
                  <div id = "input" class = "input">
                       <div class="col-lg-offset-md2 col-lg-6 text-left">    
                       <input type="text" id="nome" name="nome"  class="form-control" placeholder="<?php echo _l('nome do setor'); ?>">
                    
                       </div>
                    
                       <span>
            
                       </span>
                    <span class="input-group-btn">
              <button class="btn btn-info p7" type="button" id="new-item-group-insert"><?php echo _l('novo setor'); ?></button>
              </span>
                   </div>
                    <span class="group_name_plain_text"><?php echo $setor['nome']; ?></span>
                 
                    <div class="group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                    
                      <span class="input-group-btn">
                        <button class="btn btn-info p8 update-item-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                       
                  <div class="row-options">
                       <!--  <a href="<?php //echo admin_url('unidades_hospitalares/add_edit_unidades_hospitalares'); ?>" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#groups"><?php //echo 'teste'; ?></a>--->
                    <?php if(has_permission('unidade_hospitalar','','edit')){ ?>
                      <a  href="<?php echo admin_url('unidades_hospitalares/update_setor'); ?>" data-toggle="" data-target="">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                      
                    <?php if(has_permission('unidade_hospitalar','','delete')){ ?>
                      <a href="<?php echo admin_url('unidades_hospitalares/delete_setor/'.$setor['id']); ?>" class="delete-unidade_hospitalar-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div><!-- modal body--->
 
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  </div>
</div>
</div>
</div>
            
            
            <!--- testehh---->
            
             <!--- testehh---->
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
       var tAPI = initDataTable('.table-unidade_hospitalar', admin_url+'unidades_hospitalares/table', [0], [0], CustomersServerParams, [1, 'desc']);
        $('select[name="convenios_procedimentos"]').on('change',function(){
          // //// table controller 
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
      var setor_nome = $('#nome').val();
      var setor_descricao = $('#descricao').val();
      if(setor_nome != ''){
        $.post(admin_url+'Unidades_hospitalares/add_setor',{nome:setor_nome,descricao:setor_descricao}).done(function(){
      window.location.href = admin_url+'unidades_hospitalares?groups_modal=true';
       });
      }
        });
      
        
       // jQuery('body').on('click','.edit-item-group',function(e){
        //var btn = document.getElementById('edit');
        //var container = document.querySelector('.input');
        //btn.addEventListener('click', function() {
        
/* Edit Item */
      //$("body").on("click",".group_edit",function(){

      //var id = $(this).parent("td").data('id');
      //var title = $(this).parent("td").prev("td").prev("td").text();
      
      //var title = $(this).parent("td").prev("td").prev("td").text();
      //var description = $(this).parent("td").prev("td").text();

    // $("#edit").find("input[nome='nome']").val(title);
    // $("#edit").find("textarea[name='description']").val(description);
    // $("#edit").find("edit-item-group").val(id);

      //});

       //if(container.style.display === 'block') {
       //container.style.display = 'none';
       //} else {
       //container.style.display = 'block';
    
       //}
       //});
        //$('#input').show();
      //exibir input $('#input').hide();

      //#edit.on('click'),'.input-group.style.display = 'block'');
  
     
     //e.preventDefault();
     //var tr = jQuery(this).parents('tr'),
     //group_id = tr.attr('data-group-row-id');
     //tr.find('.group_name_plain_text').toggleClass('hide');
     // tr.find('.group_edit').toggleClass('hide');
     //tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
         
    //});

  
     
        
     //   $('body').on('click','.edit-item-group',function(e){
    
        //$('#input').show();
      //exibir input $('#input').hide();

      //#edit.on('click'),'.input-group.style.display = 'block'');
  
     
     // e.preventDefault();
      //var tr = $(this).parents('tr'),
      //group_id = tr.attr('data-group-row-id');
      //tr.find('.group_name_plain_text').toggleClass('hide');
      //tr.find('.group_edit').toggleClass('hide');
      //tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
         
   // }) ;

     $('body').on('click','.update-item-group',function(){
      var tr = $(this).parents('tr');
      var setor_id = tr.attr('data-group-row-id');
      nome = tr.find('.group_edit input').val();
      if(nome != ''){
        $.post(admin_url+'unidades_hospitalares/update_setor/'+setor_id,{nome:nome}).done(function(){
       window.location.href = admin_url+'unidade_hospitalar';
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
     if ($.fn.DataTable.isDataTable('.table-unidade_hospitalar')) {
       $('.table-unidade_hospitalar').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-unidade_hospitalar', admin_url+'unidades_hospitalares/table', [0], [0], CustomersServerParams, [1, 'desc']);
   // tAPI.ajax.reload();
     
    // filtraCategoria();
   }
   
   
  
  function filtraCategoria() {
      
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("unidade_hospitalar/retorno_filtro_categoria"); ?>",
        data: {
          convenios_procedimentos: $('#convenios_procedimentos').val()
          },
        success: function(data) {
          $('#filtro_categoria').html(data);
        }
      });
    }
    
  function unidade_hospitalar_bulk_action(event) {
    if (confirm_delete()) {
      var mass_delete = $('#mass_delete').prop('checked');
      var ids = [];
      var data = {};

      if(mass_delete == true) {
        data.mass_delete = true;
      }

      var rows = $('.table-unidade_hospitalar').find('tbody tr');
      $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') === true) {
          ids.push(checkbox.val());
        }
      });
      data.ids = ids;
      $(event).addClass('disabled');
      setTimeout(function() {
        $.post(admin_url + 'unidade_hospitalar/bulk_action', data).done(function() {
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
