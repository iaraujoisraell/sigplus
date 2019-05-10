<?php
  $data_periodo = $this->user_model->getPeriodo_detalhesHEById($id);
  $dia = $data_periodo->dia;
  $mes = $data_periodo->mes;
  $id_periodo = $data_periodo->id_periodo;

//  print_r($data_periodo);
?>
<script type="text/javascript">
    function optionCheck(){
        var option = document.getElementById("tipo").value;
        if(option == "Débito"){
            document.getElementById("debito").style.display ="block";
            document.getElementById("credito").style.display = "none";
            
            document.getElementById("he_debito").style.display ="block";
            document.getElementById("he_credito").style.display = "none";
            
        }else{
            document.getElementById("debito").style.display ="none";
            document.getElementById("credito").style.display = "block";
            
            document.getElementById("he_debito").style.display ="none";
            document.getElementById("he_credito").style.display = "block";
        }

    }


</script>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><p class="introtext">REQUISIÇÃO DE HORAS </p>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-2x">&times;</i>
                </button>
                <h4 class="modal-title">Data : <?php echo $dia.'/'.$mes; ?></h4>
            </div>
            <div class="modal-body">
                <div class="error"></div>
               <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("welcome/novaRequisicaoHorasDetalhes/" , $attrib); 
                echo form_hidden('id', $id);
                echo form_hidden('dia', $dia);
                echo form_hidden('mes', $mes);
                echo form_hidden('periodo', $id_periodo);
                ?>
                    <input type="hidden" value="" name="eid" id="eid">

                    <div class="form-group">
                        <?= lang('Descrição da atividade', 'title'); ?>
                            <?php echo form_input('title', (isset($_POST['title']) ? $_POST['title'] : ""), 'maxlength="200" class="form-control input-tip" required="required" id="title"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("Selecione a ação vinculada a requisição", "slVinculoAcao"); ?>
                       
                        <div id="credito" style="display: block ;">
                            <?php                                       

                            $usuario_sessao = $this->session->userdata('user_id'); 
                            $acoes = $this->atas_model->getAllPlanosUser($usuario_sessao);
                            foreach ($acoes as $acao) {
                                $wu_acao[$acao->idplanos] = $acao->idplanos .' - '. substr($acao->descricao, 0, 100);
                            }
                            echo form_dropdown('acoes_vinculo', $wu_acao, (isset($_POST['acoes_vinculo']) ? $_POST['acoes_vinculo'] : ""), 'id="slVinculoAcao"  class="form-control  select" required="required" data-placeholder="' . lang("Selecione a(s) Ações(es)") . ' "   style="width:100%;" ');
                            ?>
                        </div>   
                        
                        <div id="debito" style="display:  none ;">
                            <?php                                       
                            $wu_acao2['N/A'] = 'N/A (Débito)';
                            
                            echo form_dropdown('acoes_vinculo_debito', $wu_acao2, (isset($_POST['acoes_vinculo_debito']) ? $_POST['acoes_vinculo_debito'] : ""), 'id="slVinculoAcaoacoes_vinculo_debito"  class="form-control  select" required="required" data-placeholder="' . lang("Selecione a(s) Ações(es)") . ' "   style="width:100%;" ');
                            ?>
                        </div> 
                        
                        <?php
                       
                            
                            ?>
                        </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang('start', 'start'); ?>
                                <input type="time" required="true"  class="form-control" name="start">

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang('Fim', 'end'); ?>
                              <input type="time" required="true"  class="form-control" name="end">
                              </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang('Tipo de Hora', 'tipo'); ?>
                                
                                
                                            <?php 
                                              $pst['Crédito'] = lang('CRÉDITO');
                                              $pst['Débito'] = lang('DÉBITO');
                                        
                                      echo form_dropdown('tipo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : ""), 'id="tipo" onchange="optionCheck()"  class="form-control " data-placeholder="' . lang("select") . ' ' . lang("") . '" required="required"   style="width:100%;" ');
                                     ?>
                                            
                                           
                            </div>
                        </div>
                        <div class="col-md-3">
                           
                              
                                <div class="form-group">
                                <?= lang('Hora Extra', 'hora_extra'); ?>
                               
                                
                                    
                                     <div id="he_debito" style="display:<?php if($data_periodo->tipo_registro == 'Débito'){ ?> block <?php }else{ ?>  none <?php } ?>;">
                                    <?php 
                                      
                                       $pst2['NÃO'] = lang('NÃO');
                                     ?>
                                </div>   
                                <div id="he_credito" style="display:<?php if($data_periodo->tipo_registro == 'Crédito'){ ?> block <?php }else{ ?>  none <?php } ?>;">
                             <?php 
                                       $pst2['SIM'] = lang('SIM');
                                       $pst2['NÃO'] = lang('NÃO');
                             ?>          
                              </div>         
                                     
                                       
                                       
                               <?php       echo form_dropdown('hora_extra', $pst2, (isset($_POST['hora_extra']) ? $_POST['hora_extra'] : ""), 'id="hora_extra"  class="form-control " data-placeholder="' . lang("select") . ' ' . lang("") . '" required="required"   style="width:100%;" ');
                                     ?>
                                  
                                    
                                          
                                
                            </div>
                          
                        </div>
                    </div>






                   <div class="fprom-group">
            <?php echo form_submit('add_projeto', lang("Confirmar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>

    </div>
                    <?php echo form_close(); ?>
            </div>

        </div>
    </div>
               
           
