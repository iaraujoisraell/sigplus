<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_menu_admin(false); ?>

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
         <div class="col-md-12">
             <section class="content-header">
              <h1>
                Movimentação Bancária
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
                <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
                <li class="active">Movimentação Bancária</li>
              </ol>
            </section>
            <?php if(isset($medico) && (!has_permission('customers','','view') && is_customer_admin($medico->medicoid))){?>
            <div class="alert alert-info">
               <?php echo _l('customer_admin_login_as_client_message',get_staff_full_name(get_staff_user_id())); ?>
            </div>
            <?php } ?>
         </div>
  
            <div class="clearfix"></div>
            <div class="mtop15">
               <div class="row">
                  <div class="col-md-12">
                    <div class="panel_s">
                      <div class="panel-body">
                          
                     <?php hooks()->do_action('before_items_page_content'); ?>
                     <?php if(has_permission('items','','create')){ ?>
                       <div class="_buttons">
                        <a href="#" class="btn btn-info pull-left"  data-toggle="modal" data-target="#plano_conta_modal" data-id=""><?php echo 'Add Movimentação'; ?></a>   
                        <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#transferencia_modal"><?php echo 'Add Transferência'; ?></a>

                      </div>
                      <div class="clearfix"></div>
                      <hr class="hr-panel-heading" />
                    <?php } ?>
                      <!-- CONVENIO -->

                      <!-- NATUREZA 
                      <div class="col-md-4">

                               <label for="categorias"><?php echo 'Natureza'; ?></label>

                                <select onchange="procedimentos_table_reload()" class="selectpicker"
                                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                                   name="natureza"
                                   id="natureza"
                                   data-actions-box="true"

                                   data-width="100%"
                                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option  value="1" selected="true">DESPESAS</option>
                                    <option  value="2" >RECEITAS</option>
                            </select>

                      </div>  -->
                      <!-- CATEGORIA 
                      <div class="col-md-4">
                          <div class="form-group">

                               <?php
                                  $selected = '';

                                  echo render_select('categorias', $categorias, array('id', array('name')), 'categorias', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                                  ?>
                            </div>

                      </div>  -->

                    <?php
                    /*
                    $table_data = [];

                    $table_data = array_merge($table_data, array(
                      'Data',
                      'Descrição',  
                      'Banco',
                      'Entrada',
                      'Saída',
                      'Tipo',
                      'Log'  
                       ));

                    $cf = get_custom_fields('items');
                    foreach($cf as $custom_field) {
                      array_push($table_data,$custom_field['name']);
                    }
                    render_datatable($table_data,'movimento_bancario'); */ ?>
                    <table class="table table-movimento_bancario scroll-responsive">
                     <thead>
                        <tr>
                          <th><?php echo 'Data'; ?></th>
                          <th><?php echo 'Banco'; ?></th>
                          <th><?php echo 'Descrição'; ?></th>
                          <th><?php echo 'Entrada'; ?></th>
                          <th><?php echo 'Saída'; ?></th>  
                          <th><?php echo 'Tipo'; ?></th>  
                          <th><?php echo 'Log'; ?></th>
                          
                        </tr>
                      </thead>
                     <tbody></tbody>
                     <tfoot>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="entrada"></td>
                          <td class="saida"></td>
                          
                          <td></td>
                          <td></td>
                           </tr>
                     </tfoot>
                     <thead>
                        <tr>
                          <th><?php echo 'Data'; ?></th>
                          <th><?php echo 'Banco'; ?></th>
                          <th><?php echo 'Descrição'; ?></th>
                          <th><?php echo 'Entrada'; ?></th>
                          <th><?php echo 'Saída'; ?></th>  
                          <th><?php echo 'Tipo'; ?></th>  
                          <th><?php echo 'Log'; ?></th>
                        </tr>
                      </thead>
                  </table>  
                  </div>
                    </div>
                  </div>
                </div>
            </div>
                     
      </div>
      <?php if($group == 'plano_contas'){ ?>
         <div class="btn-bottom-pusher"></div>
      <?php } ?>
   </div>

<?php $this->load->view('admin/financeiro/movimentacao_bancaria/transferencia'); ?>
<?php $this->load->view('admin/financeiro/movimentacao_bancaria/novo_movimento'); ?>


<?php init_tail(); ?>
<script>

  $(function(){

    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['natureza'] = '[name="natureza"]'; 
       var tAPI = initDataTable('.table-movimento_bancario', admin_url+'financeiro/table_movimento_bancario', [0], [0], CustomersServerParams, [1, 'asc']);
     



   });

   function procedimentos_table_reload() {
      //alert('aqui');
       var CustomersServerParams = {};
     //  $.each($('._hidden_inputs._filters input'),function(){
     //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
     // });
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['natureza'] = '[name="natureza"]'; 
     if ($.fn.DataTable.isDataTable('.table-movimento_bancario')) {
       $('.table-movimento_bancario').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-movimento_bancario', admin_url+'financeiro/table_movimento_bancario', [0], [0], CustomersServerParams, [1, 'desc']);
   // tAPI.ajax.reload();

    // filtraCategoria();
   }


  

 </script>
<?php //$this->load->view('admin/medicos/client_js');  ?>
</body>
</html>
