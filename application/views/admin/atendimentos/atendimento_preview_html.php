<?php defined('BASEPATH') or exit('No direct script access allowed');  ?>

<div id="invoice-preview">
  
<div class="row">
   <div class="col-md-12">
      <div class="table-responsive">
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
                    <th width="5%">
                        <?php echo 'Opções'; ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($user_historico as $note){  ?>
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
                    
                      <td>
                       <?php if($atendimento['finished']==0) { ?>   
                        <a href="#" onclick="record_anamnese(<?php echo $atendimento['id']; ?>); return false;"  class="mleft10 pull-right btn btn-warning<?php if($invoice->status == Invoices_model::STATUS_CANCELLED){echo ' disabled';} ?>">
                        <i class="fa fa-pencil"></i> <?php echo 'Editar'; ?>
                        </a>
                       <?php } ?>  
                    </td>

           
        </tr>
        <?php }?>
    </tbody>
    </table>
      </div>
   </div>
   
</div>


</div>
