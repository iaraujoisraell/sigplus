<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}
</script>

<div class="col-md-12 no-padding">
   <div class="panel_s">
      <div class="panel-body">
         <?php if($invoice->recurring > 0){
            echo '<div class="ribbon info"><span>'._l('invoice_recurring_indicator').'</span></div>';
            } ?>
         <div class="horizontal-scrollable-tabs preview-tabs-top">
            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
            <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
            <div class="horizontal-tabs">
               <ul class="nav nav-tabs nav-tabs-horizontal mbot15" role="tablist">
                  <li role="presentation" class="label label-success tab-separator ">
                     <a href="#tab_invoice" aria-controls="tab_invoice" role="tab" data-toggle="tab">
                         <font style=""><?php echo 'Anamnese'; ?></font>
                     </a>
                  </li>
                  
                  <li role="presentation" class="label label-info tab-separator">
                     <a href="#tab_notes"  aria-controls="tab_notes" role="tab" data-toggle="tab">
                     <?php echo 'Evolução'; ?> <span class="notes-total">
                     <?php if($totalNotes > 0){ ?>
                     <span class="badge"><?php echo $totalNotes; ?></span>
                     <?php } ?>
                     </span>
                     </a>
                  </li>
                  
                  <li role="presentation" class="label label-danger tab-separator">
                     <a href="#tab_exames"  aria-controls="tab_exames" role="tab" data-toggle="tab">
                     <?php echo 'EXAMES'; ?> <span class="notes-total">
                     <?php if($totalNotes > 0){ ?>
                     <span class="badge"><?php echo $totalNotes; ?></span>
                     <?php } ?>
                     </span>
                     </a>
                  </li>
                  
                  <li role="presentation" class="label label-warning tab-separator">
                     <a href="#tab_historico" aria-controls="tab_historico" role="tab" data-toggle="tab">
                     <?php echo 'HISTÓRICO'; ?>
                     </a>
                  </li>
                  
                  <li role="presentation" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>" class="tab-separator toggle_view">
                     <a href="#" onclick="small_table_full_view(); return false;">
                     <i class="fa fa-expand"></i></a>
                  </li>
               </ul>
            </div>
         </div>
         <div class="row mtop10">
            
            <div class="col-md-12 _buttons">
              
               <div class="pull-left">
                   <!-- Editar cadastro Cliente -->
                 
                  
                <!--  <div class="btn-group">
                     <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                     <ul class="dropdown-menu dropdown-menu-right">
                        <li class="hidden-xs"><a href="<?php echo admin_url('invoices/pdf/'.$invoice->id.'?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a></li>
                        <li class="hidden-xs"><a href="<?php echo admin_url('invoices/pdf/'.$invoice->id.'?output_type=I'); ?>" target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                        <li><a href="<?php echo admin_url('invoices/pdf/'.$invoice->id); ?>"><?php echo _l('download'); ?></a></li>
                        <li>
                           <a href="<?php echo admin_url('invoices/pdf/'.$invoice->id.'?print=true'); ?>" target="_blank">
                           <?php echo _l('print'); ?>
                           </a>
                        </li>
                     </ul>
                  </div> -->
                  
                    <?php
                    $medico_id = $atendimento['medico_id']; 
                    $member = $this->staff_model->get(get_staff_user_id());  
                    $medico_user_id = $member->medico_id; 
                    
                   
                    if($medico_id == $medico_user_id) {
                    ?>
                    <?php if(!$atendimento['finished']) { ?>
                    <?php //if(has_permission('medico','','anamnese') ){
                    $total_anamnese =  count($this->invoices_model->get_historico_cliente_atendimento($atendimento['id']));
                    if(!$total_anamnese){
                    ?>
                    <a href="#" onclick="record_anamnese(<?php echo $atendimento['id']; ?>); return false;"  class="mleft10 pull-right btn btn-success<?php if($invoice->status == Invoices_model::STATUS_CANCELLED){echo ' disabled';} ?>">
                     <i class="fa fa-plus-square"></i> <?php echo 'ANAMNESE'; ?>
                    </a>
                    <?php } ?>
                  <?php }
                  }?>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <hr class="hr-panel-heading" />
         <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tab_invoice">
               <?php $this->load->view('admin/atendimentos/atendimento_preview_html'); ?>
            </div>
             
             <div role="tabpanel" class="tab-pane " id="tab_historico">
                <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                    <thead>
                        <tr>
                            <th width="5%">
                                <?php echo 'Data'; ?>
                            </th>
                            <th width="50%">
                                <?php echo _l( 'clients_notes_table_description_heading'); ?>
                            </th>

                            <th>
                                <?php echo _l( 'clients_notes_table_addedfrom_heading'); ?>
                            </th>
                            
                            

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $user_historico_completo = $this->misc_model->get_historico_ficha($atendimento['contact_id']);
                        foreach($user_historico_completo as $note){  ?>
                        <tr>
                            <td data-order="<?php echo $note['dateadded']; ?>">
                              <?php echo _dt($note[ 'dateadded']); ?>
                            </td>
                            <td width="70%">
                                <div data-note-description="<?php echo $note['id']; ?>">
                                    <table width="100%">


                                        <?php if($note['queixas_hda']){ ?>
                                        <tr>
                                            <td width="30%"><b>QUEIXAS E HDA :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['queixas_hda']); ?></p></td>
                                        </tr>
                                        <?php } ?>

                                        <?php if($note['antecedentes']){ ?>
                                        <tr>
                                            <td width="30%"><b>ANTECEDENTES FAMILIARES  :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['antecedentes']); ?></p></td>
                                        </tr>
                                        <?php } ?>

                                        <?php if($note['habitos_sociais']){ ?>
                                        <tr>
                                            <td width="30%"><b>HABITOS SOCIAIS :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['habitos_sociais']); ?></p></td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if($note['alergias']){ ?>
                                        <tr>
                                            <td width="30%"><b>ALERGIAS :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['alergias']); ?></p></td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if($note['uso_medicacoes']){ ?>
                                        <tr>
                                            <td width="30%"><b>USO MEDICAÇÕES :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['uso_medicacoes']); ?></p></td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if($note['informacoes_adicionais']){ ?>
                                        <tr>
                                            <td width="30%"><b>INFORMAÇÕES ADICIONAIS :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['informacoes_adicionais']); ?></p></td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if($note['exames_fisicos']){ ?>
                                        <tr>
                                            <td width="30%"><b>EXAMES FÍSICOS :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['exames_fisicos']); ?></p></td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if($note['refracao']){ ?>
                                        <tr>
                                            <td width="30%"><b>REFRAÇÃO :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['refracao']); ?></p></td>
                                        </tr>
                                        <?php } ?>

                                         <?php if($note['biomicroscopia']){ ?>
                                        <tr>
                                            <td width="30%"><b>BIOMICROSCOPIA :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['biomicroscopia']); ?></p></td>
                                        </tr>
                                        <?php } ?>

                                        <?php if($note['fundoscopia']){ ?>
                                        <tr>
                                            <td width="30%"><b>FUNDOSCOPIA :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['fundoscopia']); ?></p></td>
                                        </tr>
                                        <?php } ?>

                                        <?php if($note['diagnostico']){ ?>
                                        <tr>
                                            <td width="30%"><b>DIAGNÓSTICO :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['diagnostico']); ?></p></td>
                                        </tr>
                                        <?php } ?>

                                        <?php if($note['conduta']){ ?>
                                        <tr>
                                            <td width="30%"><b>CONDUTA :</b></td>
                                            <td width="70%"><p style="text-align: justify"><?php echo check_for_links($note['conduta']); ?></p></td>
                                        </tr>
                                        <?php } ?>

                                        
                                        
                                        

                                        

                                        

                                        
                            
                                    </table>
                                </div>
                            </td>
                            <td>
                               <?php echo '<a href="'.admin_url( 'profile/'.$note[ 'addedfrom']). '">'.$note[ 'firstname'] . ' ' . $note[ 'lastname'] . '</a>' ?>
                            </td>

                    
                </tr>
                <?php } ?>
            </tbody>
            </table>
            </div>
             
             
            <div role="tabpanel" class="tab-pane" id="tab_notes">
               <?php
               if($atendimento['finished']==0) {
               echo form_open(admin_url('invoices/add_note_atendimento/'.$atendimento['id']),array('id'=>'sales-notes','class'=>'invoice-notes-form')); ?>
                <input type="hidden" name="paciente_id" value="<?php echo $atendimento['contact_id']; ?>">
                <input type="hidden" name="tipo" value="evolucao">
                
               <?php echo render_textarea('description', 'evolucao', null,array('rows'=>5, 'required'=>true)); ?>
               <div class="text-right">
                  <button type="submit" onClick="window.location.reload();" class="btn btn-info mtop15 mbot15"><?php echo 'Adicionar Evolução'; ?></button>
               </div>
               <?php echo form_close(); } ?>
               <hr />
               <div class="panel_s mtop20 no-shadow" id="sales_notes_area"></div>
               <div class="mtop15">
                   <label>Evolução</label>
                    <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                        <thead>
                            <tr>
                                <th width="50%">
                                    <?php echo _l( 'clients_notes_table_description_heading'); ?>
                                </th>
                                <th>
                                    <?php echo _l( 'clients_notes_table_addedfrom_heading'); ?>
                                </th>
                                <th>
                                    <?php echo _l( 'clients_notes_table_dateadded_heading'); ?>
                                </th>
                                <th>
                                    <?php echo _l( 'options'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $notes_evolucao = $this->misc_model->get_notes_atendimento($atendimento['id'], 'evolucao');
                            foreach($notes_evolucao as $note){ ?>
                            <tr>
                                <td width="50%">
                                  <div data-note-description="<?php echo $note['id']; ?>">
                                      <h3><?php echo check_for_links($note['description']); ?></h3>
                                </div>
                                <div data-note-edit-textarea="<?php echo $note['id']; ?>" class="hide">
                                    <textarea name="description" class="form-control" rows="4"><?php echo clear_textarea_breaks($note['description']); ?></textarea>
                                    <div class="text-right mtop15">
                                      <button type="button" class="btn btn-default" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                                      <button type="button" class="btn btn-info" onclick="edit_note_atendimento(<?php echo $note['id']; ?>);"><?php echo 'Atualizar Evolução'; ?></button>
                                  </div>
                              </div>
                          </td>
                          <td>
                            <?php echo $note[ 'firstname'] . ' ' . $note[ 'lastname']  ?>
                        </td>
                        <td data-order="<?php echo $note['dateadded']; ?>">
                         <?php if(!empty($note['date_contacted'])){ ?>
                           <span data-toggle="tooltip" data-title="<?php echo html_escape(_dt($note['date_contacted'])); ?>">
                              <i class="fa fa-phone-square text-success font-medium valign" aria-hidden="true"></i>
                          </span>
                          <?php } ?>
                          <?php echo _dt($note[ 'dateadded']); ?>
                        </td>
                        <td>
                            <?php
                             if($atendimento['finished']==0) {
                            if($note['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
                            <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
                            <a href="<?php echo admin_url('misc/delete_note_atendimento/'. $note['id'].'/'.$atendimento['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                            <?php } 
                             }?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
                </div>
            </div>
             
             <div role="tabpanel" class="tab-pane" id="tab_exames">
               <?php
               if($atendimento['finished']==0) {
               echo form_open(admin_url('invoices/add_note_atendimento/'.$atendimento['id']),array('id'=>'sales-notes','class'=>'invoice-notes-form')); ?>
                 <input type="hidden" name="paciente_id" value="<?php echo $atendimento['contact_id']; ?>">
                <input type="hidden" name="tipo" value="exames">
                
               <?php echo render_textarea('description', 'solicitacao_exames', null,array('rows'=>5, 'required'=>true)); ?>
               <div class="text-right">
                  <button type="submit" onClick="window.location.reload();" class="btn btn-info mtop15 mbot15"><?php echo 'Adicionar Exames'; ?></button>
               </div>
               <?php echo form_close(); } ?>
               <hr />
               <div class="panel_s mtop20 no-shadow" id="sales_notes_area"></div>
               <div class="mtop15">
                   <label>Exames Solicitados</label>
                    <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                        <thead>
                            <tr>
                                <th width="50%">
                                    <?php echo _l( 'clients_notes_table_description_heading'); ?>
                                </th>
                                <th>
                                    <?php echo _l( 'clients_notes_table_addedfrom_heading'); ?>
                                </th>
                                <th>
                                    <?php echo _l( 'clients_notes_table_dateadded_heading'); ?>
                                </th>
                                <th>
                                    <?php echo _l( 'options'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $notes_exames = $this->misc_model->get_notes_atendimento($atendimento['id'], 'exames');
                            foreach($notes_exames as $note){ ?>
                            <tr>
                                <td width="50%">
                                  <div data-note-description="<?php echo $note['id']; ?>">
                                      <h3><?php echo check_for_links($note['description']); ?></h3>
                                </div>
                                <div data-note-edit-textarea="<?php echo $note['id']; ?>" class="hide">
                                    <textarea name="description" class="form-control" rows="4"><?php echo clear_textarea_breaks($note['description']); ?></textarea>
                                    <div class="text-right mtop15">
                                      <button type="button" class="btn btn-default" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                                      <button type="button" class="btn btn-info" onclick="edit_note_atendimento(<?php echo $note['id']; ?>);"><?php echo 'Atualizar Evolução'; ?></button>
                                  </div>
                              </div>
                          </td>
                          <td>
                            <?php echo $note[ 'firstname'] . ' ' . $note[ 'lastname']  ?>
                        </td>
                        <td data-order="<?php echo $note['dateadded']; ?>">
                         <?php if(!empty($note['date_contacted'])){ ?>
                           <span data-toggle="tooltip" data-title="<?php echo html_escape(_dt($note['date_contacted'])); ?>">
                              <i class="fa fa-phone-square text-success font-medium valign" aria-hidden="true"></i>
                          </span>
                          <?php } ?>
                          <?php echo _dt($note[ 'dateadded']); ?>
                        </td>
                        <td>
                            <?php
                             if($atendimento['finished']==0) {
                            if($note['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
                            <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
                            <a href="<?php echo admin_url('misc/delete_note_atendimento/'. $note['id'].'/'.$atendimento['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                            <?php } 
                             }?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                </table>
                </div>
            </div>
             
           
         </div>
      </div>
   </div>
</div>
<?php $this->load->view('admin/invoices/invoice_send_to_client'); ?>
<?php $this->load->view('admin/credit_notes/apply_invoice_credits'); ?>
<?php $this->load->view('admin/credit_notes/invoice_create_credit_note_confirm'); ?>

