<?php
  $data_periodo = $this->user_model->getPeriodo_detalhesHEById($id);
  $dia = $data_periodo->dia;
  $mes = $data_periodo->mes;
  $id_periodo = $data_periodo->id_periodo;
  $credito = $data_periodo->saldo;
  $debito = $data_periodo->debito;

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
//document.getElementById("credito").style.display = "block";
document.getElementById("he_credito").style.display = "block";
</script>

    
<div style="width: 700px;" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><p class="introtext">REQUISIÇÃO DE HORAS </p>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-2x">&times;</i>
                </button>
                <h4 class="modal-title">Data : <?php echo $dia.'/'.$mes.' '; ?></h4>
                <p class="introtext">Crédito : <?php echo $credito.' / '; ?>
               Débito : <?php echo  $debito; ?></p>
            </div>
            <div class="modal-body">
                <div class="error"></div>
               <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("welcome/editarRequisicaoHorasDetalhes/" , $attrib); 
                echo form_hidden('id', $id);
                echo form_hidden('periodo', $id_periodo);
                ?>
                    <input type="hidden" value="" name="eid" id="eid">
                  
                            <div class="col-md-12">    
                                <div class="form-group">
                                    <?= lang('Descrição da atividade', 'title'); ?>
                                 
                                        <?php echo form_textarea('title', (isset($_POST['title']) ? $_POST['title'] : $data_periodo->descricao), ' class="form-control input-tip" required="required" id="title" style="margin-top: 10px; height: 100px;"'); ?>
                                </div>
                            
                                <div class="form-group">
                                        <?= lang('Deseja Solicitar Hora Extra', 'hora_extra'); ?>



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

                                       <?php       echo form_dropdown('hora_extra', $pst2, (isset($_POST['hora_extra']) ? $_POST['hora_extra'] : $data_periodo->hora_extra), 'id="hora_extra"  class="form-control " data-placeholder="' . lang("select") . ' ' . lang("") . '" required="required"   style="width:100%;" ');
                                             ?>




                                    </div>

                                
                            <div class="form-group">
                                <?= lang("Ações executadas", "slVinculoAcao"); ?>
                                <?php// echo 'acao : '.$data_periodo->id_acao; ?>
                                <div id="credito" >
                                    <?php                                       
                                    //AÇÕES SELECIONADAS SALVAS
                                    $acoes_uruario = $this->user_model->getAllPlanosRequisicaoHora($id);
                                    foreach ($acoes_uruario as $acao_u) {
                                        $wu_acao_u[$acao_u->id_acao] = $acao_u->id_acao;
                                    }
                                    
                                    $usuario_sessao = $this->session->userdata('user_id'); 
                                    $acoes = $this->atas_model->getAllPlanosUser($usuario_sessao);
                                    $wu_acao[0] = 'N/A (Não está relacionado a uma ação) ';
                                    foreach ($acoes as $acao) {
                                        $evento = $this->atas_model->getAllitemEventoByID($acao->eventos);
                                        $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($acao->idatas);
                                        
                                        $wu_acao[$acao->idplanos] = $acao->idplanos.' - '. substr($acao->descricao, 0, 100).' ('.$projetos_usuario->projetos.')';
                                    }
                                    echo form_dropdown('acoes_vinculo[]', $wu_acao, (isset($_POST['acoes_vinculo[]']) ? $_POST['acoes_vinculo[]'] : $wu_acao_u), 'id="slVinculoAcao" title="Projeto : '.$projetos_usuario->projetos.' -> '.$evento->evento.'/'.$evento->item.'"  multiple class="form-control  select" required="required" data-placeholder="' . lang("Selecione a(s) Ações(es)") . ' "   style="width:100%; height: 150px;" ');
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
            
    