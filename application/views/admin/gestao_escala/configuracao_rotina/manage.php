<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<!-- 
Larissa Oliveira 
01/08/2022
Descrição: adição dos filtos de escalados, substitutos e médicos duplicados
-->

 
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


  <div class="content">
      
      <div  class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
          <section class="content-header">
              <h1>
                Gestão de Escala - Controle e Rotina
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?php echo admin_url('dashboard/menu'); ?>"><i class="fa fa-dashboard"></i> Gestão de Plantão </a></li>
                <li class="active"><a href="<?php echo admin_url('gestao_plantao/listagem_escala'); ?>">Gestão de escala </a></li>
              </ol>
            </section>
            
              <!-- OPÇÕES BULK -->
               <!-- mudar horário de plantão -->
            <?php if(has_permission('items','','delete')){ ?>
             <a href="#" data-toggle="modal" data-table=".table-escala_gestao" data-target="#trocar_horario_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo 'TROCAR HORÁRIO'; ?></a>
             <div class="modal fade bulk_actions" id="trocar_horario_bulk_actions" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                   <div class="modal-content">
                    <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title"><?php echo 'Trocar Horário'; ?></h4>
                   </div>
                   <div class="modal-body">
                     <?php if(has_permission('leads','','delete')){ ?>
                       
                       <label>Horário Desejado</label>
                      <select name="horario_troca_id" id="horario_troca_id"   class="form-control">
                            <option value="">Selecione o horário</option>
                            <?php foreach ($horarios as $horario){ ?>
                             <option value="<?php echo $horario['id']; ?>"><?php echo $horario['hora_inicio'].' / '.$horario['hora_fim']; ?></option>   
                            <?php } ?>
                        </select>
                       
                       
                      <!-- <hr class="mass_delete_separator" /> -->
                    <?php } ?>
                  </div>
                  <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                   <a href="#" class="btn btn-info" data-dismiss="modal" onclick="troca_horario_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                 </div>
               </div>
               <!-- /.modal-content -->
             </div>
             <!-- /.modal-dialog -->
           </div>
              <?php } ?>
            <!-- /.mudar horário de plantão -->
              
              <!-- Quebrar horário de plantão   de 1 plantão inteiro em 2 de 1/2 plantão -->
            <?php if(has_permission('items','','delete')){ ?>
             <a href="#" data-toggle="modal" data-table=".table-escala_gestao" data-target="#quebrar_horario_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo 'QUEBRAR HORÁRIO'; ?></a>
             <div class="modal fade bulk_actions" id="quebrar_horario_bulk_actions" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                   <div class="modal-content">
                    <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title"><?php echo 'Quebra de Horário de : 1 Plantão para 2 [1/2] Plantões'; ?></h4>
                   </div>
                   <div class="modal-body">
                     <?php if(has_permission('leads','','delete')){ ?>
                       
                        <label>Horário Desejado 1</label>
                        <select name="horario_novo_1" id="horario_novo_1"   class="form-control">
                            <option value="">Selecione o horário</option>
                            <?php foreach ($horarios_quebrar as $horario){ ?>
                             <option value="<?php echo $horario['id']; ?>"><?php echo $horario['hora_inicio'].' / '.$horario['hora_fim']; ?></option>   
                            <?php } ?>
                        </select>
                        
                        <label>Horário Desejado 2</label>
                        <select name="horario_novo_2" id="horario_novo_2"   class="form-control">
                            <option value="">Selecione o horário</option>
                            <?php foreach ($horarios_quebrar as $horario){ ?>
                             <option value="<?php echo $horario['id']; ?>"><?php echo $horario['hora_inicio'].' / '.$horario['hora_fim']; ?></option>   
                            <?php } ?>
                        </select>
                       
                       
                      <!-- <hr class="mass_delete_separator" /> -->
                    <?php } ?>
                  </div>
                  <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                   <a href="#" class="btn btn-info" data-dismiss="modal" onclick="quebra_horario_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                 </div>
               </div>
               <!-- /.modal-content -->
             </div>
             <!-- /.modal-dialog -->
           </div>
              <?php } ?>
            <!-- /.Quebrar horário de plantão -->
            
              
            <!-- ADD HORÁRIO -->
              <?php if(has_permission('items','','delete')){ ?>
             <a href="#" data-toggle="modal" data-table=".table-escala_gestao" data-target="#add_horario" class="hide bulk-actions-btn table-btn btn btn-primary"><?php echo 'ADD HORÁRIO'; ?></a>
             <div class="modal fade bulk_actions" id="add_horario" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                   <div class="modal-content">
                    <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title"><?php echo 'Add Plantonista'; ?></h4>
                   </div>
                   <div class="modal-body">
                     <?php if(has_permission('leads','','delete')){ ?>
                       <div class="form-group">
                          <label>Competência</label>
                          <select name="add_plantonista_competencia_id" id="add_plantonista_competencia_id"  required="true" class="form-control" >

                                <?php foreach ($competencias as $comp){ ?>
                                 <option value="<?php echo $comp['id']; ?>"><?php echo $comp['mes'].' / '.$comp['ano']; ?></option>   
                                <?php } ?>
                            </select>
                      </div>
                       <div class="form-group">
                           <label>Dia da Semana</label>
                           <select  name="add_plantonista_dia_semana" id="add_plantonista_dia_semana" class="form-control">
                                <option value="">Selecione</option>
                                 <option value="segunda">Segunda-Feira</option>
                                 <option value="terca">Terça-Feira</option>
                                 <option value="quarta">Quarta-Feira</option>
                                 <option value="quinta">Quinta-Feira</option>
                                 <option value="sexta">Sexta-Feira</option>
                                 <option value="sabado">Sábado</option>
                                 <option value="domingo">Domingo</option>
                            </select>
                        </div>
                       <div class="form-group">
                           <label>Unidades Hospitalares</label>
                            <select name="add_plantonista_unidade_id" id="add_plantonista_unidade_id" onchange="add_plantonista_setores(this.value); "  required="true" class="form-control">
                                <option value="">Selecione uma unidade</option>
                                <?php foreach ($unidades_hospitalares as $unidades){ ?>
                                 <option value="<?php echo $unidades['id']; ?>"><?php echo $unidades['razao_social']; ?></option>   
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div id="add_plantonista_setores_unidades">             
                            </div>
                        </div>
                        <div class="form-group">
                            <div id="add_plantonista_horario_plantao">             
                            </div>
                        </div>
                       
                       
                       
                       <div class="form-group">
                           <?php
                        $selected = '';
                        
                        echo render_select('medicoid',$titulares,array('medicoid',array('nome_profissional',  'especialidade')),'Titular',$selected);
                        ?>
                       </div>
                       
                       <div class="form-group">
                           <?php
                        $selected = '';
                        
                        echo render_select('escalado',$especialistas,array('medicoid',array('nome_profissional',  'especialidade')),'Escalado',$selected);
                        ?>
                       </div>
                       
                       
                      <!-- <hr class="mass_delete_separator" /> -->
                    <?php } ?>
                  </div>
                  <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                   <a href="#" class="btn btn-info" data-dismiss="modal"  onclick="add_plantonista_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                 </div>
               </div>
               <!-- /.modal-content -->
             </div>
             <!-- /.modal-dialog -->
           </div>
            <!-- /.ADD HORÁRIO -->
            <?php } ?>
            
             <!-- EXCLUIR plantão -->
              <?php if(has_permission('items','','delete')){ ?>
            <a href="#"  data-toggle="modal" data-table=".table-escala_gestao" data-target="#deletar_plantao_bulk_actions" class="hide bulk-actions-btn table-btn  btn-info "><?php echo 'DELETAR HORÁRIO'; ?></a>
                 <div class="modal fade bulk_actions" id="deletar_plantao_bulk_actions" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                       <div class="modal-content">
                        <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title"><?php echo 'DELETAR HORÁRIO'; ?></h4>
                       </div>
                       <div class="modal-body">
                      </div>
                      <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                       <a href="#" class="btn btn-info" data-dismiss="modal" onclick="deletar_horario_bulk_action(this); "><?php echo _l('confirm'); ?></a>
                     </div>
                   </div>
                   <!-- /.modal-content -->
                 </div>
                 <!-- /.modal-dialog -->
               </div>
              <?php } ?>
            <!-- /.DELETAR HORARIO de plantão -->
            
            <!-- mudar TITULAR de plantão -->
              <?php if(has_permission('items','','delete')){ ?>
            <a href="#"  data-toggle="modal" data-table=".table-escala_gestao" data-target="#trocar_titular_bulk_actions" class="hide bulk-actions-btn table-btn  btn-info "><?php echo 'TROCAR TITULAR'; ?></a>
                 <div class="modal fade bulk_actions" id="trocar_titular_bulk_actions" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                       <div class="modal-content">
                        <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title"><?php echo 'Trocar Titular'; ?></h4>
                       </div>
                       <div class="modal-body">
                         <?php if(has_permission('leads','','delete')){ ?>

                           <div class="form-group">
                               <?php
                            $selected = '';

                            echo render_select('titular_troca_id',$titulares,array('medicoid',array('nome_profissional',  'especialidade')),'Profissional',$selected);
                            ?>
                           </div>


                          <!-- <hr class="mass_delete_separator" /> -->
                        <?php } ?>
                      </div>
                      <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                       <a href="#" class="btn btn-info" data-dismiss="modal" onclick="troca_titular_bulk_action(this); "><?php echo _l('confirm'); ?></a>
                     </div>
                   </div>
                   <!-- /.modal-content -->
                 </div>
                 <!-- /.modal-dialog -->
               </div>
              <?php } ?>
            <!-- /.mudar ESCALADO de plantão -->
            
            <!-- mudar ESCALADO de plantão -->
              <?php if(has_permission('items','','delete')){ ?>
            <a href="#"  data-toggle="modal" data-table=".table-escala_gestao" data-target="#trocar_escalado_bulk_actions" class="hide bulk-actions-btn table-btn  btn-info "><?php echo 'TROCAR ESCALADO'; ?></a>
                 <div class="modal fade bulk_actions" id="trocar_escalado_bulk_actions" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                       <div class="modal-content">
                        <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title"><?php echo 'Trocar Escalado'; ?></h4>
                       </div>
                       <div class="modal-body">
                         <?php if(has_permission('leads','','delete')){ ?>

                           <div class="form-group">
                               <?php
                            $selected = '';

                            echo render_select('escalado_troca_id',$titulares,array('medicoid',array('nome_profissional',  'especialidade')),'Profissional',$selected);
                            ?>
                           </div>


                          <!-- <hr class="mass_delete_separator" /> -->
                        <?php } ?>
                      </div>
                      <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                       <a href="#" class="btn btn-info" data-dismiss="modal" onclick="troca_escalado_bulk_action(this); "><?php echo _l('confirm'); ?></a>
                     </div>
                   </div>
                   <!-- /.modal-content -->
                 </div>
                 <!-- /.modal-dialog -->
               </div>
              <?php } ?>
            <!-- /.mudar ESCALADO de plantão -->
            
            
            <!-- mudar SUBSTITUTO de plantão -->
              <?php if(has_permission('items','','delete')){ ?>
            <a href="#"  data-toggle="modal" data-table=".table-escala_gestao" data-target="#trocar_substituto_bulk_actions" class="hide bulk-actions-btn table-btn  btn-info "><?php echo 'TROCAR SUBSTITUTO'; ?></a>
                 <div class="modal fade bulk_actions" id="trocar_substituto_bulk_actions" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                       <div class="modal-content">
                        <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title"><?php echo 'Trocar Substituto'; ?></h4>
                       </div>
                       <div class="modal-body">
                         <?php if(has_permission('leads','','delete')){ ?>

                           <div class="form-group">
                               <?php
                            $selected = '';

                            echo render_select('substituto_troca_id',$titulares,array('medicoid',array('nome_profissional',  'especialidade')),'Profissional',$selected);
                            ?>
                           </div>


                          <!-- <hr class="mass_delete_separator" /> -->
                        <?php } ?>
                      </div>
                      <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                       <a href="#" class="btn btn-info" data-dismiss="modal" onclick="troca_substituto_bulk_action(this); "><?php echo _l('confirm'); ?></a>
                     </div>
                   </div>
                   <!-- /.modal-content -->
                 </div>
                 <!-- /.modal-dialog -->
               </div>
              <?php } ?>
            <!-- /.mudar ESCALADO de plantão -->
              
            <?php hooks()->do_action('before_items_page_content'); ?>
            <?php echo form_open('admin/Gestao_plantao/pdf'); ?>  
              <div class="col-md-12">        
              <div class="col-md-3">
                  <div class="form-group">
                      <label>Competência</label>
                      <select name="competencia_id" id="competencia_id"  required="true" class="form-control" onchange="medicos_duplicados(this.value);procedimentos_table_reload();">
                          <option value="0">SELECIONE</option> 
                            <?php foreach ($competencias as $comp){ ?>
                             <option value="<?php echo $comp['id']; ?>"><?php echo $comp['mes'].' / '.$comp['ano']; ?></option>   
                            <?php } ?>
                        </select>
                  </div>
                </div>  
              <!-- CONVENIO -->
              <div class="col-md-3">
                  <div class="form-group">
                       <label>Unidades Hospitalares</label>
                        <select name="unidade_id" id="unidade_id" onchange="setores(this.value); procedimentos_table_reload();"class="form-control">
                            <option value="">Selecione uma unidade</option>
                            <?php foreach ($unidades_hospitalares as $unidades){ ?>
                             <option value="<?php echo $unidades['id']; ?>"><?php echo $unidades['razao_social']; ?></option>   
                            <?php } ?>
                        </select>
                    </div>
              </div>
              <!-- CATEGORIA -->
              <div class="col-md-3">
                  <div class="form-group">
                        <div id="setores_unidades">             
                        </div>
                  </div>
              </div>  
              
              <div class="col-md-3">
                  <div class="form-group">
                            <label>Horários</label>
                            <select name="horario_plantao_id" id="horario_plantao_id"  onchange="procedimentos_table_reload();" class="form-control">
                                <option checked="true" value="">Selecione</option>
                                <?php foreach ($horarios as $plano){

                                    ?>
                                 <option value="<?php echo $plano['id']; ?>" <?php echo $selectd ?>><?php echo $plano['hora_inicio'].' - '.$plano['hora_fim']; ?></option>   ;
                                <?php } ?>
                            </select>
                    </div>

              </div>
              </div>
      
              <div class="col-md-12">
                  <div class="col-md-3">
                      <div class="form-group">
                           <label>Dia da Semana</label>
                            <select name="dia_semana" id="dia_semana" onchange="procedimentos_table_reload();"  class="form-control">
                                <option value="">Todos</option>
                                 <option value="segunda">Segunda-Feira</option>
                                 <option value="terca">Terça-Feira</option>
                                 <option value="quarta">Quarta-Feira</option>
                                 <option value="quinta">Quinta-Feira</option>
                                 <option value="sexta">Sexta-Feira</option>
                                 <option value="sabado">Sábado</option>
                                 <option value="domingo">Domingo</option>
                            </select>
                        </div>
                  </div>
                  
                  
                  
                  <div class="col-md-3">
                      <div class="form-group">
                           <label>Titular</label>
                            <select name="titular_id" id="titular_id" onchange=" procedimentos_table_reload();"  class="form-control">
                                <option value="">Selecione um profissional</option>
                                <?php foreach ($titulares as $tit){ ?>
                                 <option value="<?php echo $tit['medicoid']; ?>"><?php echo $tit['nome_profissional']; ?></option>   
                                <?php } ?>
                            </select>
                        </div>
                  </div>
                  
                  <div class="col-md-3">
                      <div class="form-group">
                           <label>Escalados</label>
                            <select name="escalado_filtro_id" id="escalado_filtro_id" onchange="procedimentos_table_reload();"  class="form-control">
                                <option value="">Selecione um profissional</option>
                                <?php foreach ($escalados as $esc){ ?>
                                 <option value="<?php echo $esc['medicoid']; ?>"><?php echo $esc['nome_profissional']; ?></option>   
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                  
                    <div class="col-md-3">
                      <div class="form-group">
                           <label>Substitutos</label>
                            <select name="substituto_id" id="substituto_id" onchange=" procedimentos_table_reload();" class="form-control">
                                <option value="">Selecione um profissional</option>
                                <?php foreach ($substitutos as $subs){ ?>
                                 <option value="<?php echo $subs['medicoid']; ?>"><?php echo $subs['nome_profissional']; ?></option>   
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                  
                                    
                  <div class="col-md-3">
                      <div class="form-group">
                           <label>Status Escalado</label>
                            <select name="status_escalado" id="status_escalado" onchange="procedimentos_table_reload();" class="form-control">
                                <option value="">Todos</option>
                                <option value="1">Ocupados</option>
                                <option value="2">Disponíveis</option>
                            </select>
                        </div>
                  </div>
                  
                     <div class="col-md-3">
                        <div class="form-group">
                                  <div id="med_duplicados">         
                                  </div>
                        </div>

                     </div>
                
                                      
                    <div class="box-header ">
                                <div  style="margin-left: 410px; margin-top: 102px;">

                                    <button class="btn btn-primary"><i class="fa fa-download"></i></button>
                                </div>
                    </div>
                  
               </div> 
            
              
 
            
      
    <?php
    $table_data = [];

    if(has_permission('items','','delete')) {
      $table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="escala_gestao"><label></label></div>';
       // $table_data[] = '<span class="hide"> - </span><label>#</label>';
    }

    $table_data = array_merge(
            $table_data, array(
                'Dia',
                'Dia Semana',
                'Unidade/',
                'Setor',
                'Início',  
                'Fim',
                'Qtde Plantão',
                'Títular',
                'Escalado',
                'Substituto',
                'Plantonista',
                'Creditado', 
                'Status Plantão',
                'Faturamento'
                
                
            )
            );

    
    $cf = get_custom_fields('items');
    foreach($cf as $custom_field) {
      array_push($table_data,$custom_field['name']);
    }
    render_datatable($table_data,'escala_gestao'); ?>
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

           CustomersServerParams['competencia_id'] = '[name="competencia_id"]';
           CustomersServerParams['unidade_id'] = '[name="unidade_id"]'; 
           CustomersServerParams['setor_id'] = '[name="setor_id"]';  
           CustomersServerParams['horario_plantao_id'] = '[name="horario_plantao_id"]';
           CustomersServerParams['dia_semana'] = '[name="dia_semana"]';
           CustomersServerParams['titular_id'] = '[name="titular_id"]';
           CustomersServerParams['escalado_filtro_id'] = '[name="escalado_filtro_id"]';
           CustomersServerParams['substituto_id'] = '[name="substituto_id"]';
           CustomersServerParams['status_escalado'] = '[name="status_escalado"]';  
           CustomersServerParams['med_duplicados'] = '[name="med_duplicados"]';
           
          // CustomersServerParams['horario_id'] = '[name="exclude_inactive"]:checked';
           var tAPI = initDataTable('.table-escala_gestao', admin_url+'gestao_plantao/table_escala_gestao', [0], [0], CustomersServerParams, [1, 'desc']);
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
           CustomersServerParams['competencia_id'] = '[name="competencia_id"]';
           CustomersServerParams['unidade_id'] = '[name="unidade_id"]'; 
           CustomersServerParams['setor_id'] = '[name="setor_id"]';  
           CustomersServerParams['horario_plantao_id'] = '[name="horario_plantao_id"]';
           CustomersServerParams['dia_semana'] = '[name="dia_semana"]';
           CustomersServerParams['titular_id'] = '[name="titular_id"]';
           CustomersServerParams['escalado_filtro_id'] = '[name="escalado_filtro_id"]';
           CustomersServerParams['substituto_id'] = '[name="substituto_id"]';
           CustomersServerParams['status_escalado'] = '[name="status_escalado"]';  
           CustomersServerParams['med_duplicados'] = '[name="med_duplicados"]';
           
          if ($.fn.DataTable.isDataTable('.table-escala_gestao')) {
           $('.table-escala_gestao').DataTable().destroy();
          }
          var tAPI = initDataTable('.table-escala_gestao', admin_url+'gestao_plantao/table_escala_gestao', [0], [0], CustomersServerParams, [1, 'desc']);
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
      
    function setores(unidade_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("gestao_plantao/retorno_setores_gestao_escala"); ?>",
        data: {
          unidade_id: unidade_id        
        },
        success: function(data) {
          $('#setores_unidades').html(data);
        }
      });
    }

      function medicos_duplicados(competencia_id) {
    
    $.ajax({
        
        type: "POST",
        url: "<?php echo admin_url("gestao_plantao/retorno_duplicados_gestao_escala"); ?>",
        data: {
          competencia_id:  competencia_id
          
        },
        success: function(data) {
          $('#med_duplicados').html(data);
        }
      });
    }

    function horarios(setor_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("gestao_plantao/retorno_horarios_plantao_gestao_escala"); ?>",
        data: {
          setor_id: setor_id   
        },
        success: function(data) {
          $('#horario_plantao').html(data);
        }
      });
    }
    
   
    
    function troca_horario_bulk_action(event) {
        if (confirm_delete()) {
        
          var mass_delete = $('#mass_delete').prop('checked');
          
          var horario_troca = document.getElementById('horario_troca_id');
	  var horario_value = horario_troca.options[horario_troca.selectedIndex].value;
         
          //var horario_troca_id = $('#horario_troca_id').val();
          var ids = [];
          var data = {};
          
          
           data.horario_troca_id = horario_value;

          var rows = $('.table-escala_gestao').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
          data.ids = ids;
         // $(event).addClass('disabled');
          setTimeout(function() {
            $.post(admin_url + 'gestao_plantao/bulk_action_trocar_escala', data).done(function() {
             // window.location.reload();
             procedimentos_table_reload();
            }).fail(function(data) {
              alert_float('danger', data.responseText);
            });
          }, 200);
        }
        
      }
    
    function quebra_horario_bulk_action(event) {
        if (confirm_delete()) {
        
          var mass_delete = $('#mass_delete').prop('checked');
          
          var horario_quebra = document.getElementById('horario_novo_1');
	  var horario_value = horario_quebra.options[horario_quebra.selectedIndex].value;
         
         var horario_quebra2 = document.getElementById('horario_novo_2');
	  var horario_value2 = horario_quebra2.options[horario_quebra2.selectedIndex].value;
          //var horario_troca_id = $('#horario_troca_id').val();
          var ids = [];
          var data = {};
          
          
           data.horario_quebra_1 = horario_value;
           data.horario_quebra_2 = horario_value2;

          var rows = $('.table-escala_gestao').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
          data.ids = ids;
         // $(event).addClass('disabled');
          setTimeout(function() {
            $.post(admin_url + 'gestao_plantao/bulk_action_quebra_escala', data).done(function() {
             // window.location.reload();
             procedimentos_table_reload();
            }).fail(function(data) {
              alert_float('danger', data.responseText);
            });
          }, 200);
        }
        
      }
    
    function add_plantonista_bulk_action(event) {
        
       if (confirm_delete()) {
          
          var competencia = document.getElementById('add_plantonista_competencia_id');
	  var competencia_value = competencia.options[competencia.selectedIndex].value;
           
          var dia_Semana = document.getElementById('add_plantonista_dia_semana');
	  var dia_Semana_value = dia_Semana.options[dia_Semana.selectedIndex].value;
          
          var unidades = document.getElementById('add_plantonista_unidade_id');
	  var unidades_value = unidades.options[unidades.selectedIndex].value;
          
          var setores = document.getElementById('setor_id_add_plantonista');
	  var setores_value = setores.options[setores.selectedIndex].value;
          
          var horario = document.getElementById('horario_id_add_plantonista');
	  var horario_value = horario.options[horario.selectedIndex].value;
          
          var horario_exec = document.getElementById('horario_executado_id_add_plantonista');
	  var horario_exec_value = horario_exec.options[horario_exec.selectedIndex].value;
          
          var medico = document.getElementById('medicoid');
	  var medico_value = medico.options[medico.selectedIndex].value;
          
         
          var escalado = document.getElementById('escalado');
	  var escalado_value = escalado.options[escalado.selectedIndex].value;
 
       
          var data = {};
          
          data.competencia = competencia_value;
          data.dia_Semana = dia_Semana_value;
          data.unidades = unidades_value;
          data.setores = setores_value;
          data.horario = horario_value; 
          data.horario_exec = horario_exec_value; 
          data.medico = medico_value;
          data.escalado = escalado_value;

         
          //data.ids = ids;
         // $(event).addClass('disabled');
          setTimeout(function() {
            $.post(admin_url + 'gestao_plantao/bulk_action_add_plantonista', data).done(function() {
             // window.location.reload();
             procedimentos_table_reload();
            }).fail(function(data) {
              alert_float('danger', data.responseText);
            });
          }, 200);
        }
        
      }
      
    function troca_titular_bulk_action(event) {
        if (confirm_delete()) {
        
          
          var titular_troca = document.getElementById('titular_troca_id');
	  var titular_value = titular_troca.options[titular_troca.selectedIndex].value;
         
          //var horario_troca_id = $('#horario_troca_id').val();
          var ids = [];
          var data = {};
          
          
           data.titular_id = titular_value;

          var rows = $('.table-escala_gestao').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
          data.ids = ids;
          //$(event).addClass('disabled');
          setTimeout(function() {
            $.post(admin_url + 'gestao_plantao/bulk_action_trocar_titular', data).done(function() {
             // window.location.reload();
             procedimentos_table_reload();
            }).fail(function(data) {
              alert_float('danger', data.responseText);
            });
          }, 200);
        }
    }  

    function troca_escalado_bulk_action(event) {
        if (confirm_delete()) {
        
          
          var escalado_troca = document.getElementById('escalado_troca_id');
	  var escalado_value = escalado_troca.options[escalado_troca.selectedIndex].value;
         
          //var horario_troca_id = $('#horario_troca_id').val();
          var ids = [];
          var data = {};
          
          
           data.escalado_id = escalado_value;

          var rows = $('.table-escala_gestao').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
          data.ids = ids;
          //$(event).addClass('disabled');
          setTimeout(function() {
            $.post(admin_url + 'gestao_plantao/bulk_action_trocar_escalado', data).done(function() {
             // window.location.reload();
             procedimentos_table_reload();
            }).fail(function(data) {
              alert_float('danger', data.responseText);
            });
          }, 200);
        }
    }
    
    function troca_substituto_bulk_action(event) {
        if (confirm_delete()) {
        
          
          var substituto_troca = document.getElementById('substituto_troca_id');
	  var substituto_value = substituto_troca.options[substituto_troca.selectedIndex].value;
         
          //var horario_troca_id = $('#horario_troca_id').val();
          var ids = [];
          var data = {};
          
          
           data.substituto_id = substituto_value;

          var rows = $('.table-escala_gestao').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
          data.ids = ids;
          //$(event).addClass('disabled');
          setTimeout(function() {
            $.post(admin_url + 'gestao_plantao/bulk_action_trocar_substituto', data).done(function() {
             // window.location.reload();
             procedimentos_table_reload();
            }).fail(function(data) {
              alert_float('danger', data.responseText);
            });
          }, 200);
        }
    }
    
    function deletar_horario_bulk_action(event) {
        if (confirm_delete()) {
        
          
          var ids = [];
          var data = {};
          
          
        
          var rows = $('.table-escala_gestao').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
          data.ids = ids;
          //$(event).addClass('disabled');
          setTimeout(function() {
            $.post(admin_url + 'gestao_plantao/bulk_action_deletar_horario', data).done(function() {
             // window.location.reload();
             procedimentos_table_reload();
            }).fail(function(data) {
              alert_float('danger', data.responseText);
            });
          }, 200);
        }
    }
    
    function add_plantonista_setores(unidade_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("gestao_plantao/retorno_setores_gestao_escala_add_plantonista"); ?>",
        data: {
          unidade_id: unidade_id        
        },
        success: function(data) {
          $('#add_plantonista_setores_unidades').html(data);
        }
      });
    } 
    
    function add_plantonista_horarios(setor_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("gestao_plantao/retorno_horarios_plantao_gestao_escala_add_plantonista"); ?>",
        data: {
          setor_id: setor_id        
        },
        success: function(data) {
          $('#add_plantonista_horario_plantao').html(data);
        }
      });
    }
    
     </script>
    </body>
    </html>
