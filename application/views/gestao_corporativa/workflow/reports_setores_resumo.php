reports_setores_resumo<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - WORKFLOW POR SETORES - RESUMO</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Workflow'); ?>"><i class="fa fa-backward"></i> Workflow </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Workflow/reports'); ?>"><i class="fa fa-backward"></i> Relatários </a></li>
                </ol>
            </div>
        </div>
        
        <div class="row">

        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                <div class=" col-md-3">
                <?php
                  $ultima_data_atual = date('Y-m-d');
                 ?>
                <label>Prazo de</label>
                <input type="date" value="<?php echo $ultima_data_atual; ?>" onkeydown="procedimentos_table_reload()" onkeyup="procedimentos_table_reload()" id="prazo_de" class="form-control" name="prazo_de" class="btn label-primary label-big">
                
               
            </div>
            <div class="  col-md-3  ">
                   <label>Prazo ate</label>
                <input type="date" value="<?php echo $ultima_data_atual; ?>" onkeydown="procedimentos_table_reload()" onkeyup="procedimentos_table_reload()" id="prazo_ate" class="form-control" name="prazo_ate" class="btn label-primary label-big">
       
            </div><!-- comment -->
          
          <!--  <div class="col-md-3 col-sm-3 "  >
            <label for="categorias"><?php echo 'Categorias'; ?></label>
                    
                    <select onchange="procedimentos_table_reload()" class="selectpicker"  data-live-search="true"
                    
                    name="categoria"
                    id="categoria"
                    
                    data-width="100%"
                    data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       
                    <?php
                    
                    foreach($categorias as $dep){  ?>        
                        <option  value="<?php echo $dep['id']; ?>" ><?php echo $dep['titulo']; ?></option>
                    
                    <?php } ?>
                        
                </select>
                <?php
               
               // echo render_select('categoria_id', $categorias, array('id', 'titulo'), '', $cat_filter, $array_settings);
                ?>


            </div> -->



            <div class="col-md-3 col-sm-3 " >
                    <label for="categorias"><?php echo 'Departamentos'; ?></label>
                    
                    <select onchange="procedimentos_table_reload()" class="selectpicker"  data-live-search="true"
                    data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                    name="departments"
                    id="departments"
                    
                    data-width="100%"
                    data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       
                    <?php foreach($departments as $dep){  ?>        
                        <option  value="<?php echo $dep['departmentid']; ?>" ><?php echo $dep['name']; ?></option>
                    
                    <?php } ?>
                        
                </select>

                <?php
                //echo render_select('departments', $departments, array('departmentid', 'name'), '', json_decode($filters['departments']), array('data-none-selected-text' => _l('dropdown_non_selected_tex')));
                ?>
            </div>

            <div class="col-md-3">
                
                    <label for="categorias"><?php echo 'Situação'; ?></label>
                    
                        <select onchange="procedimentos_table_reload()" class="selectpicker"
                        data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                        name="status"
                        id="status"
                        
                        data-width="100%"
                        data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option  value="1" >CONCLUÍDOS</option>
                            <option  value="2" >PENDENTES</option>
                            
                    </select>
                
            </div>  

                
      
            </div>
            </div>
         </div>
        </div>              
    
      
      
      <div class="row">

        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">  
        <?php
        $data['tipo'] = 1;
        //$this->load->view('gestao_corporativa/workflow/summary', $data);
        ?>
        <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
        <div class="clearfix"></div>
        <?php
        $table_data = [];

        $table_data = array_merge($table_data, array(
            '#',
            'Etapa',    
            'Setor',
            'Qtde',
            'Data Prazo',
            'Categoria',
            'Status',
            'Finalizado por'
                ));
        render_datatable($table_data, 'workflow_setores_resumo');
        ?>

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

    var status = $('select[name="status"]');
    var departments = $('select[name="departments"]');
    
    CustomersServerParams['prazo_de'] = '[name="prazo_de"]';
    CustomersServerParams['prazo_ate'] = '[name="prazo_ate"]';  
    CustomersServerParams['status'] = status; 
    CustomersServerParams['departments'] = departments; 


   //  CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';
   var tAPI = initDataTable('.table-workflow_setores_resumo', '<?php echo base_url(); ?>' + 'gestao_corporativa/workflow/table_wf_setores_resumo', [0], [0], CustomersServerParams, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1, 'desc'))); ?>);

     //var tAPI = initDataTable('.table-workflow_setores_resumo', admin_url+'workflow/table_wf_setores', [0], [0], CustomersServerParams, [1, 'desc']);
      $('select[name="status"]').on('change',function(){
        // alert('aqui');
         tAPI.ajax.reload();
        
       // procedimentos_table_reload();
     });
      //filtraCategoria();


      $('select[name="departments"]').on('change',function(){
        // alert('aqui');
         tAPI.ajax.reload();
        
       // procedimentos_table_reload();
     });


     $('select[name="categoria"]').on('change',function(){
        // alert('aqui');
         tAPI.ajax.reload();
        
       // procedimentos_table_reload();
     });


 });
 
 function procedimentos_table_reload() {
   
     var CustomersServerParams = {};
   //  $.each($('._hidden_inputs._filters input'),function(){
   //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
   // });
   var status = $('select[name="status"]');
   var departments = $('select[name="departments"]');
   //var categoria = $('select[name="categoria"]');


     CustomersServerParams['prazo_de'] = '[name="prazo_de"]';
    CustomersServerParams['prazo_ate'] = '[name="prazo_ate"]';  
    CustomersServerParams['status'] = status; 
    //CustomersServerParams['categoria'] = categoria; 
    CustomersServerParams['departments'] = departments; 
    

   if ($.fn.DataTable.isDataTable('.table-workflow_setores_resumo')) {
     $('.table-workflow_setores_resumo').DataTable().destroy();
   }
   var tAPI = initDataTable('.table-workflow_setores_resumo', '<?php echo base_url(); ?>' + 'gestao_corporativa/workflow/table_wf_setores_resumo', [0], [0], CustomersServerParams, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1, 'desc'))); ?>);

   // var tAPI = initDataTable('.table-lotes', admin_url+'lotes/table', [0], [0], CustomersServerParams, [1, 'desc']);
 // tAPI.ajax.reload();
   
  // filtraCategoria();
 }
</script>

</body>
</html>
