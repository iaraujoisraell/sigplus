<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_menu_admin(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">
 <link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">


 
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
      
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
   
    <div class="content">
        <div class="col-md-112 ">
       
        <div class="row">
            <div class="col-md-12 " data-container="top-12">
                <div class="row">
                    <div class="box " >
                        <div class="box-header with-border ">
                        <h3 class="box-title">DASHBOARD AGENDA</h3>    
                        <br><br>
                        <!-- CONTA MÉDICA 
                        <div class="col-md-3 col-sm-6 col-xs-12">
                           <label style="font-size: 16px;" for="medicos"><?php echo _l('medico'); ?></label>
                           <div class="form-group">
                           <select class="selectpicker"
                                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                                   name="medicos_faturamento"
                                   id ="medicos_faturamento"
                                   data-actions-box="true"
                                   multiple="true"
                                   data-width="100%"
                                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                       <?php /*
                                       foreach ($medicos as $medico) {
                                       $selected = 'selected';
                                       ?>
                               <option  value="<?php echo $medico['id']; ?>" selected="true"><?php echo $medico['nome']; ?></option>
                                <?php } ?>
                           </select>
                           </div>    
                        </div>-->
                        <!-- CONVÊNIO 
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label style="font-size: 16px;" for="convenios"><?php echo _l('convenio'); ?></label>
                          <div class="form-group">
                           <select class="selectpicker"
                                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                                   name="convenios_faturamento"
                                   id="convenios_faturamento"
                                   data-actions-box="true"
                                   multiple="true"
                                   data-width="100%"
                                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                       <?php /*
                                       foreach ($convenios as $convenio) {
                                          $selected = ' selected';
                                           ?>
                                         <option  value="<?php echo $convenio['id']; ?>" <?php echo $selected; ?>><?php echo $convenio['name']; ?></option>

                                <?php //} ?>
                           </select>
                           </div>   
                        </div>-->
                        <!-- CATEGORIA 
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="form-group">
                           <label style="font-size: 16px;" for="categorias"><?php echo _l('expense_dt_table_heading_category'); ?></label>
                           <div class="form-group">
                           <select class="selectpicker"
                                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                                   name="categorias_faturamento"
                                   id="categorias_faturamento"
                                   data-actions-box="true"
                                   multiple="true"
                                   data-width="100%"
                                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                       <?php /*
                                       foreach ($categorias as $categoria) {
                                          $selected = ' selected'; */
                                           ?>
                                         <option  value="<?php echo $categoria['id']; ?>" <?php echo $selected; ?>><?php echo $categoria['name']; ?></option>

                                <?php// } ?>
                           </select>
                           </div>    
                        </div>
                        </div>
                        
                        <div class="col-md-3 col-xs-12">
                          <div class="form-group">
                           <label style="font-size: 16px;" for="categorias"><?php echo 'Procedimentos'; ?></label>
                            <?php
                            /*
                                $selected = '';
                                foreach($medicos as $medico){
                                 if(isset($invoice)){
                                   if($invoice->medicoid == $medico['medicoid']) {
                                     $selected = $medico['medicoid'];
                                   }
                                 }
                                }
                                echo render_select('procedimento_faturamento',$procedimentos,array('itemid',array('description',  'group_name')),'',$selected);
                             * 
                             */
                                ?>
                           
                        </div>
                        </div>-->
                        
                        <div class="col-md-3 col-sm-6 col-xs-12">
                             <label style="font-size: 16px;">Data de:</label>
                            <div class="form-group">
                                <input type="date"  name="data_de" id="data_de" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                          </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label style="font-size: 16px;">Data até:</label>
                           <div class="form-group">
                                <input type="date" name="data_ate" id="data_ate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        
                        
                        </div>
                       
                       <div class="box-header ">
                           <div class="col-md-3 col-sm-6 col-xs-12">
                           <button class="btn btn-primary" onclick="filtraDashboard();">ATUALIZAR</button>
                           </div>
                       </div>
                    </div>
              </div>      
            
                <div id="conteudo"> 
                
                </div>
            </div>
      
            <?php //hooks()->do_action('after_dashboard_top_container'); ?>

        </div>
        </div>    
    </div>
      
<script>
    function filtraDashboard() {
      
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("dashboard/retorno_dashboard_agendamento"); ?>",
        data: {
          medicos_faturamento: $('#medicos_faturamento').val(),
          convenios_faturamento: $('#convenios_faturamento').val(),
          categorias_faturamento: $('#categorias_faturamento').val(),
          procedimento_faturamento: $('#procedimento_faturamento').val(),
          data_de: $('#data_de').val(),
          data_ate: $('#data_ate').val()
        },
        success: function(data) {
          $('#conteudo').html(data);
        }
      });
    }
   </script> 

<?php init_tail(); ?>

