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
         font-size: 18px;
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
        <div class="col-md-10 col-md-offset-1">
        <div class="row">
         <div class="panel-body">
            <div class="col-md-12 " data-container="top-12">
                <div class="horizontal-scrollable-tabs preview-tabs-top">
                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                   <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                      <li role="presentation">
                         <a href="<?php echo admin_url('dashboard/dashboard_gestao/'); ?>" >
                         <?php echo 'Tesouraria'; ?>
                         </a>
                      </li>
                       <li role="presentation"  class="active">
                         <a  aria-controler="invoice_payments_received" role="tab" data-toggle="tab">
                         <?php echo 'Produção'; ?> <span class="badge"></span>
                         </a>
                      </li>
                    </ul>
                </div>
             </div>
         </div>
         </div>
       </div>     
          
        <div class="row">
            <div class="col-md-12 " data-container="top-12">
                <div class="row">
                    <div class="box " >
                        <div class="box-header with-border ">
                        <h3 class="box-title">DASHBOARD DE PRODUÇÃO</h3>    
                        <br><br>
                        <!-- CONTA MÉDICA -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <label style="font-size: 18px;" for="medicos"><?php echo _l('medico'); ?></label>
                           <div class="form-group">
                           <select class="selectpicker"
                                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                                   name="medicos_faturamento"
                                   id ="medicos_faturamento"
                                   data-actions-box="true"
                                   multiple="true"
                                   data-width="100%"
                                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                       <?php
                                       foreach ($medicos as $medico) {
                                       $selected = 'selected';
                                       ?>
                               <option  value="<?php echo $medico['id']; ?>" selected="true"><?php echo $medico['nome']; ?></option>
                                <?php } ?>
                           </select>
                           </div>    
                        </div>
                        <!-- CONVÊNIO -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <label style="font-size: 18px;" for="convenios"><?php echo _l('convenio'); ?></label>
                          <div class="form-group">
                           <select class="selectpicker"
                                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                                   name="convenios_faturamento"
                                   id="convenios_faturamento"
                                   data-actions-box="true"
                                   multiple="true"
                                   data-width="100%"
                                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                       <?php
                                       foreach ($convenios as $convenio) {
                                          $selected = ' selected';
                                           ?>
                                         <option  value="<?php echo $convenio['id']; ?>" <?php echo $selected; ?>><?php echo $convenio['nome']; ?></option>

                                <?php } ?>
                           </select>
                           </div>   
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label style="font-size: 18px;">2020 </label>
                            <div class="form-group">
                                <input type="checkbox" name="competencia1" id="competencia1" value="202006" > Jun
                           
                                <input type="checkbox" name="competencia2" id="competencia2" value="202007" > Jul
                          
                                <input type="checkbox" name="competencia3" id="competencia3" value="202008" > Ago
                          
                                <input type="checkbox" name="competencia4" id="competencia4" value="202009" > Set
                          
                                <input type="checkbox" name="competencia5" id="competencia5" value="202010" > Out
                           
                                <input type="checkbox" name="competencia6" id="competencia6" value="202011" > Nov
                           
                                <input type="checkbox" name="competencia7" id="competencia7" value="202012" > Dez
                           </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label style="font-size: 18px;">2022 </label>
                            <div class="form-group">
                                <input type="checkbox" name="competencia20"  id="competencia20" value="202201" checked="true"> Jan
                           
                                <input type="checkbox" name="competencia21"  id="competencia21" value="202202" checked="true"> Fev
                          
                                <input type="checkbox" name="competencia22" id="competencia22" value="202203" checked="true" > Mar
                          
                                <input type="checkbox" name="competencia23" id="competencia23" value="202204" checked="true"> Abr
                          
                                <input type="checkbox" name="competencia24" id="competencia24" value="202205" checked="true" > Mai
                           
                                <input type="checkbox" name="competencia25" id="competencia25" value="202206" checked="true"> Jun
                                
                                <input type="checkbox" name="competencia26" id="competencia26" value="202207" checked="true" > Jul
                                
                                <input type="checkbox" name="competencia27" id="competencia27" value="202208" checked="true" > Ago
                                
                                <input type="checkbox" name="competencia28" id="competencia28" value="202209" checked="true" > Set
                                
                                <input type="checkbox" name="competencia29" id="competencia29" value="202210" checked="true"> Out
                                
                                <input type="checkbox" name="competencia30" id="competencia30" value="202211" checked="true"> Nov
                                
                                <input type="checkbox" name="competencia31" id="competencia31" value="202212" checked="true"> Dez
                           </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label style="font-size: 18px;">2021 </label>
                            <div class="form-group">
                                <input type="checkbox" name="competencia8"  id="competencia8" value="202101" > Jan
                           
                                <input type="checkbox" name="competencia9"  id="competencia9" value="202102" > Fev
                          
                                <input type="checkbox" name="competencia10" id="competencia10" value="202103" > Mar
                          
                                <input type="checkbox" name="competencia11" id="competencia11" value="202104" > Abr
                          
                                <input type="checkbox" name="competencia12" id="competencia12" value="202105" > Mai
                           
                                <input type="checkbox" name="competencia13" id="competencia13" value="202106" > Jun
                                
                                <input type="checkbox" name="competencia14" id="competencia14" value="202107" > Jul
                                
                                <input type="checkbox" name="competencia15" id="competencia15" value="202108" > Ago
                                
                                <input type="checkbox" name="competencia16" id="competencia16" value="202109" > Set
                                
                                <input type="checkbox" name="competencia17" id="competencia17" value="202110" > Out
                                
                                <input type="checkbox" name="competencia18" id="competencia18" value="202111" > Nov
                                
                                <input type="checkbox" name="competencia19" id="competencia19" value="202112" > Dez
                           </div>
                        </div>
                        
                        
                        
                        
                        
                        
                    <!--    <div class="col-md-3 col-sm-6 col-xs-12">
                            <label style="font-size: 18px;">Mês Até</label>
                            <div class="form-group">
                                <select class="form-control" name="mes_ate" id="mes_ate">
                                    <option>Todos</option>
                                    <option value="1">Janeiro</option>
                                    <option value="2">Fevereiro</option>
                                    <option value="3">Março</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Maio</option>
                                    <option value="6">Junho</option>
                                    <option value="7">Julho</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                          </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label style="font-size: 18px;">Ano Até</label>
                            <div class="form-group">
                                <select class="form-control" name="ano_ate" id="ano_ate">
                                   <option value="2021">2021</option>
                                    <option value="2020">2020</option>
                                 </select>
                          </div>
                        </div> -->
                        
                        
                        </div>
                       
                       <div class="box-header ">
                           <div class="col-md-3 col-sm-6 col-xs-12">
                           <button class="btn btn-primary" onclick="filtraDashboard();">FILTRAR</button>
                           </div>
                       </div>
                    </div>
              </div>      
            
                <div id="conteudo_producao"> 
                
                </div>
            </div>
      
            <?php //hooks()->do_action('after_dashboard_top_container'); ?>

        </div>
        </div>    
       
    </div>
      
<script>
    function filtraDashboard() {
            var val_competencia1 = 0;
            competencia1: $('#competencia1').val();
            if(competencia1.checked){ 
                 val_competencia1 = 1;
            }else{
                  val_competencia1 = 0;
            }
            
            var val_competencia2 = 0;
            competencia2: $('#competencia2').val();
            if(competencia2.checked){ 
                 val_competencia2 = 1;
            }else{
                  val_competencia2 = 0;
            }
            
            var val_competencia3 = 0;
            competencia3: $('#competencia3').val();
            if(competencia3.checked){ 
                 val_competencia3 = 1;
            }else{
                  val_competencia3 = 0;
            }
            
            var val_competencia4 = 0;
            competencia4: $('#competencia4').val();
            if(competencia4.checked){ 
                 val_competencia4 = 1;
            }else{
                  val_competencia4 = 0;
            }
            
            var val_competencia5 = 0;
            competencia5: $('#competencia5').val();
            if(competencia5.checked){ 
                 val_competencia5 = 1;
            }else{
                  val_competencia5 = 0;
            }
            
            var val_competencia6 = 0;
            competencia6: $('#competencia6').val();
            if(competencia6.checked){ 
                 val_competencia6 = 1;
            }else{
                  val_competencia6 = 0;
            }
            
            var val_competencia7 = 0;
            competencia7: $('#competencia7').val();
            if(competencia7.checked){ 
                 val_competencia7 = 1;
            }else{
                  val_competencia7 = 0;
            }
            
            var val_competencia8 = 0;
            competencia8: $('#competencia8').val();
            if(competencia8.checked){ 
                 val_competencia8 = 1;
            }else{
                  val_competencia8 = 0;
            }
            
            var val_competencia9 = 0;
            competencia9: $('#competencia9').val();
            if(competencia1.checked){ 
                 val_competencia9 = 1;
            }else{
                  val_competencia9 = 0;
            }
            
            var val_competencia10 = 0;
            competencia10: $('#competencia10').val();
            if(competencia10.checked){ 
                 val_competencia10 = 1;
            }else{
                  val_competencia10 = 0;
            }
            
            var val_competencia11 = 0;
            competencia11: $('#competencia11').val();
            if(competencia11.checked){ 
                 val_competencia11 = 1;
            }else{
                  val_competencia11 = 0;
            }
            
            var val_competencia12 = 0;
            competencia12: $('#competencia12').val();
            if(competencia12.checked){ 
                 val_competencia12 = 1;
            }else{
                  val_competencia12 = 0;
            }
            
            var val_competencia13 = 0;
            competencia13: $('#competencia13').val();
            if(competencia13.checked){ 
                 val_competencia13 = 1;
            }else{
                  val_competencia13 = 0;
            }
            
            var val_competencia14 = 0;
            competencia14: $('#competencia14').val();
            if(competencia14.checked){ 
                 val_competencia14 = 1;
            }else{
                  val_competencia14 = 0;
            }
            
            var val_competencia15 = 0;
            competencia15: $('#competencia15').val();
            if(competencia15.checked){ 
                 val_competencia15 = 1;
            }else{
                  val_competencia15 = 0;
            }
            
            var val_competencia16 = 0;
            competencia15: $('#competencia16').val();
            if(competencia16.checked){ 
                 val_competencia16 = 1;
            }else{
                  val_competencia16 = 0;
            }
            
            var val_competencia17 = 0;
            competencia17: $('#competencia17').val();
            if(competencia17.checked){ 
                 val_competencia17 = 1;
            }else{
                  val_competencia17 = 0;
            }
            
            var val_competencia18 = 0;
            competencia18: $('#competencia18').val();
            if(competencia18.checked){ 
                 val_competencia18 = 1;
            }else{
                  val_competencia18 = 0;
            }
            
            var val_competencia19 = 0;
            competencia19: $('#competencia19').val();
            if(competencia19.checked){ 
                 val_competencia19 = 1;
            }else{
                  val_competencia19 = 0;
            }
            
            var val_competencia20 = 0;
            competencia20: $('#competencia20').val();
            if(competencia20.checked){ 
                 val_competencia20 = 1;
            }else{
                  val_competencia20 = 0;
            }
            
            var val_competencia21 = 0;
            competencia21: $('#competencia21').val();
            if(competencia21.checked){ 
                 val_competencia21 = 1;
            }else{
                  val_competencia21 = 0;
            }
            
            var val_competencia22 = 0;
            competencia22: $('#competencia22').val();
            if(competencia22.checked){ 
                 val_competencia22 = 1;
            }else{
                  val_competencia22 = 0;
            }
            
            var val_competencia23 = 0;
            competencia23: $('#competencia23').val();
            if(competencia23.checked){ 
                 val_competencia23 = 1;
            }else{
                  val_competencia23 = 0;
            }
            
            var val_competencia24 = 0;
            competencia24: $('#competencia24').val();
            if(competencia24.checked){ 
                 val_competencia24 = 1;
            }else{
                  val_competencia24 = 0;
            }
            
            var val_competencia25 = 0;
            competencia25: $('#competencia25').val();
            if(competencia25.checked){ 
                 val_competencia25 = 1;
            }else{
                  val_competencia25 = 0;
            }
            
            var val_competencia26 = 0;
            competencia26: $('#competencia26').val();
            if(competencia26.checked){ 
                 val_competencia26 = 1;
            }else{
                  val_competencia26 = 0;
            }
            
            var val_competencia27 = 0;
            competencia27: $('#competencia27').val();
            if(competencia27.checked){ 
                 val_competencia27 = 1;
            }else{
                  val_competencia27 = 0;
            }
            
            var val_competencia28 = 0;
            competencia28: $('#competencia28').val();
            if(competencia28.checked){ 
                 val_competencia28 = 1;
            }else{
                  val_competencia28 = 0;
            }
            
            var val_competencia29 = 0;
            competencia29: $('#competencia29').val();
            if(competencia29.checked){ 
                 val_competencia29 = 1;
            }else{
                  val_competencia29 = 0;
            }
            
            var val_competencia30 = 0;
            competencia30: $('#competencia30').val();
            if(competencia30.checked){ 
                 val_competencia30 = 1;
            }else{
                  val_competencia30 = 0;
            }
            
            var val_competencia31 = 0;
            competencia31: $('#competencia31').val();
            if(competencia31.checked){ 
                 val_competencia31 = 1;
            }else{
                  val_competencia31 = 0;
            }
            
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("dashboard/retorno_dashboard_producao"); ?>",
        data: {
          medicos_faturamento: $('#medicos_faturamento').val(),
          convenios_faturamento: $('#convenios_faturamento').val(),
          
         
          competencia1: val_competencia1,
          competencia2: val_competencia2,
          competencia3: val_competencia3,
          competencia4: val_competencia4,
          competencia5: val_competencia5,
          competencia6: val_competencia6,
          competencia7: val_competencia7,
          
          competencia8: val_competencia8,
          competencia9: val_competencia9,
          competencia10: val_competencia10,
          competencia11: val_competencia11,
          competencia12: val_competencia12,
          competencia13: val_competencia13,
          competencia14: val_competencia14,
          competencia15: val_competencia15,
          competencia16: val_competencia16,
          competencia17: val_competencia17,
          competencia18: val_competencia18,
          competencia19: val_competencia19,
          
          competencia20: val_competencia20,
          competencia21: val_competencia21,
          competencia22: val_competencia22,
          competencia23: val_competencia23,
          competencia24: val_competencia24,
          competencia25: val_competencia25,
          competencia26: val_competencia26,
          competencia27: val_competencia27,
          competencia28: val_competencia28,
          competencia29: val_competencia29,
          competencia30: val_competencia30,
          competencia31: val_competencia31,
         // mes_de: $('#mes_de').val(),
         // mes_ate: $('#mes_ate').val()
        },
        success: function(data) {
          $('#conteudo_producao').html(data);
        }
      });
    }
   </script> 

<?php init_tail(); ?>



<script src="<?php echo base_url() ?>assets/menu/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url() ?>assets/menu/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url() ?>assets/menu/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url() ?>assets/menu/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo base_url() ?>assets/menu/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url() ?>assets/menu/bower_components/chart.js/Chart.js"></script>
<script src="<?php echo base_url() ?>assets/menu/dist/js/pages/dashboard2.js"></script>
<script src="<?php echo base_url() ?>assets/menu/dist/js/demo.js"></script>

