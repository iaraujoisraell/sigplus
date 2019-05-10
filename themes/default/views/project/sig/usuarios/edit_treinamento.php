<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">

<?php  $this->load->view($this->theme . 'usuarios/header'); ?>

<body>

    <div id="wrapper">

        
      
        
        
        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">
                 <br><br>
               
                    <?php  $this->load->view($this->theme . 'usuarios/menus'); ?>
                
                <br><br>
               <!-- DIV TABLE AÇÕES PENDENTES -->  
                
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     <?php
                                $ataAtual = $this->atas_model->getAtaByID($id);
                                $statusAta = $ataAtual->status;
                                ?>
                                    <?php if(($statusAta == 1)||($statusAta == 2)){ ?>
                                     <h4>Este Treinamento se encontra FINALIZADO</h4>
                                    <?php }else if($statusAta == 0){ ?>
                                     <h3>DADOS DO TREINAMENTO</h3>
                                    <?php } ?>
                                   
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                               
                                <div class="box">
                                  
                                    <div class="box-content">
                                        <div class="row">
                                            <div class="col-lg-12">

                                           <?php 
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                                               
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Projeto :", "slProjeto"); ?>
                                                                      <?php
                                                                     $usuario = $this->session->userdata('user_id');
                                                                     $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                                                                     $projetos_usuario->projeto_atual;

                                                                     echo $projetos_usuario->projeto;

                                                                ?>

                                                                <?php
                                                                $ata = $this->atas_model->getAtaByID($id);
                                                                
                                                                if(!$id){
                                                                    redirect("welcome/treinamentos");
                                                                }
                                                                
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <?= lang("Ata : ", "ata"); ?>
                                                                <?php
                                                                   echo $ata->id;
                                                                ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <?= lang("Local :", "facilitador"); ?>
                                                                                <?php
                                                                                
                                                                                     echo $ata->local;
                                                                                ?>
                                                                          </div>
                                                                    </div>
                                                     
                                                        <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Data/Hora Início : ", "data_inicio"); ?>
                                                                    <?php echo $this->sma->hrld($ata->data_ata); ?>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Data/hora Término : ", "data_termino"); ?>
                                                                    <?php echo $this->sma->hrld($ata->data_termino); ?>
                                                                  
                                                                </div>
                                                            </div>
                                                    
                                                        </div>   
                                                    <div class="col-md-12">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <?= lang("Conteúdo do Treinamento : ", "slpauta"); ?>
                                                                        <p> <?php echo $ata->pauta ?></p>
                                                                     
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <?= lang("Responsável pelo Treinamento : ", "slpauta"); ?>
                                                                         <?php echo $ata->responsavel_elaboracao ?>
                                                                     
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <?= lang("Facilitador :", "facilitador"); ?>
                                                                                <?php
                                                                                $facilitador = $this->site->geUserByID($facilitador_usuario);
                                                                                    echo $facilitador->first_name.' '.$facilitador->last_name;
                                                                                ?>
                                                                          </div>
                                                                    </div>
                                                            </div>
                                                    
                                                    <br><br><br>
                                                    <hr><hr>
                                                     <br><br><br>
                                                  
                                                           
                                                            <div class="col-lg-12">
                                                                <div style="margin-top: 30px; height: 100%" class="portlet portlet-default">
                                                                   
                                                                   
                                                                        <br>
                                                                     
                                                                   <div style="width: 99%;margin-left:12px; " >     
                                                                            <h3>Participantes</h3>   
                                                                    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                                                          echo form_open_multipart("welcome/addParticipanteTreinamento/", $attrib); 
                                                                          echo form_hidden('id_ata', $id);
                                                                          echo form_hidden('id_ata_facilitador', $id_ata_facilitador);
                                                                        
                                                                    ?>
                                                                            <div style="height: 120px;" class="well ">
                                                                                <?php
                                                                                $users = $this->atas_model->getAllUsersSetores();
                                                                                 ?>
                                                                                <select name="participante"  <?php if($status_treinamento == 1){ ?> disabled  <?php } ?> class="form-control">
                                                                                    <option value="0">Selecione</option>

                                                                                    <?php
                                                                                    foreach ($users as $user) {
                                                                                     ?>

                                                                                    <option value="<?php echo $user->user_id; ?>"><?php echo $user->nome.' '.$user->last.' - '.$user->setor; ?></option>

                                                                                    <?php


                                                                                    }

                                                                                    ?>        

                                                                                </select>      
                                                                                  
                                                                                  
                                                                                  <center>
                                                                                <div class="col-md-12">
                                                                                    <div class="fprom-group center">
                                                                                    <button type="submit" style="padding: 6px 15px; margin:15px 0;" class="btn btn-primary btn-theme" name="cdi" >Adicionar</button>
                                                                                    </div>
                                                                                </div>
                                                                                </center>
                                                                                  
                                                                            </div>
                                                                            
                                                                             
                                                                            
                                                                             <?php echo form_close(); ?>
                                                                            
                                                                            
                                                                               <?php
                                                                                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                                                                    echo form_open_multipart("welcome/informacoes_treinamento_form", $attrib);
                                                                                    echo form_hidden('id_ata', $id);
                                                                                    echo form_hidden('id_ata_facilitador', $id_ata_facilitador);
                                                                                ?>
                                                                             
                                                                            
                                                                            <div style="margin-top: 20px;" class="col-md-12">
                                                                            </div>
                                                                           
                                                                                <div class="col-md-12">
                                                                                        <div class="col-md-3">
                                                                                              PARTICIPANTES
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                              AVALIAÇÃO
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                              PRESENTE?
                                                                                        </div>
                                                                                        <div class="col-md-5">
                                                                                              OBSERVAÇÕES INDIVIDUAIS
                                                                                        </div>
                                                                                </div>
                                                                            <br>
                                                                            <div style="margin-top: 20px;" class="col-md-12">
                                                                        <?php
                                                                            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
                                                                            foreach ($participantes_cadastrados_ata as $participante_cadastrados) {

                                                                                if($participante_cadastrados){
                                                                                    $cadastro_usuario =  $this->site->getUser($participante_cadastrados->id_participante);
                                                                                    $historico_convocado =  $this->atas_model->listaConvocadosByUsuarioAta($cadastro_usuario->id, $id);
                                                                                     $status = $historico_convocado->status;
                                                                                        if($status == 0){
                                                                                            $novo_status = "Sem Retorno";
                                                                                        }else if($status == 1){
                                                                                            $novo_status = "SIM";
                                                                                        }else if($status == 2){
                                                                                            $novo_status = "NÃO";
                                                                                        }
                                                                                ?>
                                                                                            <div class="col-md-3">
                                                                                               <?php echo  $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name; ?>
                                                                                                 <?php if($participante_cadastrados->avaliacao != 1){ ?>   
                                                                                                
                                                                                                <a target="_blank" class="btn btn-danger" href="<?= site_url('welcome/deleteParticipanteTreinamento/'.$participante_cadastrados->id.'/'.$id_ata_facilitador); ?>"><i class="fa fa-trash-o"></i></a>
                                                                                             <?PHP } ?>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                             <?php if($participante_cadastrados->avaliacao != 1){ ?>   
                                                                                            <a target="_blank" class="btn btn-green" href="<?= site_url('welcome/pesquisa_reacao/'.$participante_cadastrados->id); ?>"><?= lang('Avaliação') ?></a>
                                                                                             <?PHP } ?>
                                                                                            </div>
                                                                                            <div class="col-md-2">
                                                                                                <select name="presenca[]"  <?php if($status_treinamento == 1){ ?> disabled  <?php } ?> class="form-control">
                                                                                                    
                                                                                                    <?php if($participante_cadastrados->presenca_confirmada){ ?>
                                                                                                    <option value="<?php echo $participante_cadastrados->presenca_confirmada; ?>"><?php echo $participante_cadastrados->presenca_confirmada; ?></option>
                                                                                                    <?php } ?>
                                                                                                   
                                                                                                    <option value="SIM">SIM</option>
                                                                                                    <option value="NÃO">NÃO</option>
                                                                                                   
                                                                                                    
                                                                                                    
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-md-5">
                                                                                                <?php if($status_treinamento == 1){ ?>
                                                                                              <?php echo form_textarea('observacoes[]', ("$participante_cadastrados->observacao_facilitador"), 'class="form-control" id="sltextoconvocacao" disabled  style=" height: 60px;"'); ?>
                                                                                            <?php }else{ ?>
                                                                                                  <?php echo form_textarea('observacoes[]', ("$participante_cadastrados->observacao_facilitador"), 'class="form-control" id="sltextoconvocacao"   style=" height: 60px;"'); ?>
                                                                                           
                                                                                                <?php } ?>
                                                                                            </div>
                                                                                          <?php
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                                </div>
                                                                            
                                                                            <div style="width: 99%;margin-left:12px; " >
                                                                         <h3 style="">Minhas Anotações </h3>
                                                                         <?php 
                                                                         $usuario = $this->session->userdata('user_id');
                                                                          $facilitador_ata = $this->atas_model->getAtaFacilitadorByUserAta($usuario, $id);
                                                                           $status_treinamento = $facilitador_ata->status;
                                                                         ?>
                                                                        <div class="form-group">
                                                                            <?= lang("Anotações e observações do treinamento ", "sltextoconvocacao"); ?>
                                                                            <?php if($status_treinamento == 1){ ?>
                                                                            <?php echo form_textarea('anotacoes', (isset($_POST['anotacoes']) ? $_POST['anotacoes'] : $facilitador_ata->observacao), 'class="form-control" id="sltextoconvocacao"  disabled  style="margin-top: 10px; height: 150px;"'); ?>
                                                                            <?php }else{ ?>
                                                                            <?php echo form_textarea('anotacoes', (isset($_POST['anotacoes']) ? $_POST['anotacoes'] : $facilitador_ata->observacao), 'class="form-control" id="sltextoconvocacao"   style="margin-top: 10px; height: 150px;"'); ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                            
                                                                        </div>
                                                                        
                                                                           <center>
                                                                       <br><br>
                                                                            <div class="clearfix"></div>
                                                                           <br><br>
                                                                                <?php if ($status_treinamento == 1) { ?>
                                                                                    <div class="fprom-group">
                                                                                        <a  class="btn btn-danger" href="<?= site_url('welcome/treinamentos'); ?>"><?= lang('Sair') ?></a>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="fprom-group">
                                                                                       <?php if($status_treinamento != 1){ ?>
                                                                                        <?php echo form_submit('add_projeto', lang("Salvar"), 'id="add_projeto" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                                                                        <a  class="btn btn-danger" href="<?= site_url('welcome/treinamentos'); ?>"><?= lang('Sair') ?></a>
                                                                                        <a  class="btn btn-orange" href="<?= site_url('welcome/fechar_treinamento/'.$id_ata_facilitador); ?>"><?= lang('Fechar Treinamento') ?></a>
                                                                                       <?php } else if($status_treinamento == 1){ ?>
                                                                                           <a  class="btn btn-danger" href="<?= site_url('welcome/treinamentos'); ?>"><?= lang('Sair') ?></a>
                                                                                    
                                                                                       <?php } ?> 
                                                                                    </div>     
                                                                                  <?php } ?> 
                                                                            
                                                                        
                                                                            </center>
                                                                        <br><br>
                                                               
                                                            </div>
                                                             </div>    
                                                        <?php echo form_close(); ?>
                                                    
                                                    <?php if($status_treinamento != 1){ ?>
                                                        <?php
                                                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                                            echo form_open_multipart("welcome/add_treinamento", $attrib);
                                                            echo form_hidden('id_ata', $id);
                                                            echo form_hidden('id_ata_facilitador', $id_ata_facilitador);
                                                        ?>
                                                     
                                                        <div class="col-lg-12">
                                                            <div class="col-lg-2">
                                                                <input type="time" name="hora_inicio" required="true" class="form-control" placeholder="Hora Início">
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <input type="time" name="hora_termino" required="true" class="form-control" placeholder="Hora Término">
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <input type="text" maxlength="250" name="conteudo" required="true" class="form-control" placeholder="Conteúdo do Treinamento">
                                                            </div>
                                                        </div>
                                                        <center>
                                                            <div class="col-lg-12" style="margin-top:15px;">
                                                          <?php echo form_submit('add_treinamento', lang("Adicionar Treinamento"), 'id="add_treinamento" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                
                                                         </div>  
                                                        </center>
                                                      <?php echo form_close(); ?>
                                                    <?php } ?>
                                                     
                                                     <div style="margin-top:15px;" class="col-lg-12">
                                                         <div class="portlet portlet-default">
                                                             <div class="portlet-heading">
                                                                <div class="portlet-title">
                                                                     <h4>TREINAMENTOS REALIZADOS </h4>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        
                                                     <div class="portlet-body">
                                                                <div class="table-responsive">
                                                                    <table  class="table table-striped table-bordered table-hover table-green">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                               
                                                                                <th>INÍCIO</th>
                                                                                <th>TÉRMINO</th>
                                                                                <th>TEMPO</th>
                                                                                <th>DESCRICAO</th>

                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                             <?php
                                                                             
                                                                             function dateDiff( $dateStart, $dateEnd, $format = '%a' ) {

                                                                                $d1     =   new DateTime( $dateStart );

                                                                                $d2     =   new DateTime( $dateEnd );

                                                                                //Calcula a diferença entre as datas
                                                                                $diff   =   $d1->diff($d2, true);   

                                                                                //Formata no padrão esperado e retorna
                                                                                return $diff->format( $format );

                                                                            }
                                                                             
                                                                             
                                                                                $wu4[''] = '';
                                                                                $cont_planoContinuo = 1;
                                                                                $total_tempo = '00:00:00';
                                                                                $cont_array = 0;
                                                                                foreach ($treinamentos as $treinamento) {
                                                                                    $hora_inicio = $treinamento->hora_inicio;
                                                                                    $hora_fim = $treinamento->hora_termino;
                                                                                    $tempo = gmdate('H:i:s', strtotime( $hora_fim) - strtotime( $hora_inicio  ) );
                                                                                    
                                                                                    $times[] = $tempo;
                                                                                ?>   
                                                                                    <tr  class="odd gradeX">
                                                                                    <td  class="center"><?php echo $cont_planoContinuo++; ?></td>
                                                                                   
                                                                                    <td  class="center"><?php echo $hora_inicio; ?></td>
                                                                                    <td  class="center"><?php echo $hora_fim; ?></td>
                                                                                    <td  class="center"><?php echo $tempo; ?></td>

                                                                                    <td  class="center"><?php echo $treinamento->descricao; ?></td>


                                                                                    </tr>
                                                                                <?php
                                                                               
                                                                                }
                                                                                
                                                                                
                                                                                //$times = array($hora1, $hora2);
                                                                               // print_r($times);
                                                                                    $seconds = 0;
                                                                                    foreach ( $times as $time ){   
                                                                                    list( $g, $i ) = explode( ':', $time );   
                                                                                    $seconds += $g * 3600;   
                                                                                    $seconds += $i * 60;   
                                                                                    }
                                                                                    $hours = floor( $seconds / 3600 );
                                                                                    $seconds -= $hours * 3600;
                                                                                    $minutes = floor( $seconds / 60 );
                                                                                    $seconds -= $minutes * 60;
                                                                                   
                                                                                ?>


                                                                                    <tr  class="odd gradeX">
                                                                                    <td  class="center"></td>
                                                                                   
                                                                                    <td  class="center"></td>
                                                                                    <td  class="center">TOTAL</td>
                                                                                    <td  class="center"><?php  echo "{$hours}:{$minutes}"; ?></td>

                                                                                    <td  class="center"></td>


                                                                                    </tr>

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- /.table-responsive -->
                                                            </div>
                                                          </div>    
                                                     </div>  
                                                     <?php if($status_treinamento != 1){ ?>
                                                     <center>
                                                         <div class="col-lg-12" style="margin-top:15px;">
                                                          <a style="color: #ffffff;" class="btn btn-blue  "  href="<?= site_url('welcome/adcionar_acao/'.$id.'/'.$id_ata_facilitador); ?>">Adicionar Nova Ação</a>
                                                         </div> 
                                                     </center> 
                                                     <?php 
                                                     }
                                                     ?>
                                                     
                                                    <div style="margin-top : 20px;" class="col-lg-12">
                                                        <div class="portlet portlet-default">
                                                            <div class="portlet-heading">
                                                                <div class="portlet-title">
                                                                    <?php
                                                                                $wu4[''] = '';
                                                                                $cont2 = 0;
                                                                                foreach ($planos as $plano2) {

                                                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                                                $cont2++;
                                                                                }
                                                                                ?>   
                                                                     <h4>AÇÕES GERADAS NO TREINAMENTO: <?php echo $cont2; ?></h4>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            <div class="portlet-body">
                                                                <div class="table-responsive">
                                                                    <table  class="table table-striped table-bordered table-hover table-green">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>DESCRIÇÃO</th>
                                                                                <th>RESPONSÁVEL</th>
                                                                                <th>DATA PRAZO</th>
                                                                                <th>ENTREGA DEMANDA</th>

                                                                                <th>CONSULTORIA</th>
                                                                                <th>STATUS</th>
                                                                                <?php if($status_treinamento != 1){ ?>
                                                                                <th>Editar</th>
                                                                                <th>Excluir</th>
                                                                                <?php } ?>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                             <?php
                                                                                $wu4[''] = '';
                                                                                $cont = 1;
                                                                                foreach ($planos as $plano) {

                                                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                                                ?>   
                                                                                <tr class="odd gradeX">
                                                                                <td><?php echo $plano->idplanos; ?></td>
                                                                                <td><?php echo $plano->descricao; ?>
                                                                                <p><font  style="font-size: 10px; color: #0000BB"><?php echo $plano->observacao; ?></font></p>    
                                                                                <p><font  style="font-size: 10px;"><?php echo $plano->tipo; ?> <?php echo $plano->processo; ?> <?php echo $plano->item_roteiro; ?></font></p>
                                                                                </td>
                                                                                <td><?php echo $plano->first_name. ' '.$plano->last_name; ?>
                                                                                <td class="center">
                                                                                   <font  style="font-size: 12px;"> <?php if($plano->data_termino != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_termino); }?></font>

                                                                                </td>
                                                                                <td class="center">
                                                                                 <font  style="font-size: 12px;">     <?php if($plano->data_entrega_demanda != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_entrega_demanda); }?> 
                                                                                   </font>
                                                                                </td>


                                                                                </td>

                                                                                <td>
                                                                                    <?php echo $plano->consultoria ?> 
                                                                                     <p> <font  style="font-size: 12px;"><?php echo $plano->acaoconsultoria; ?></font></p>
                                                                                </td>



                                                                                    <?php if($plano->status == 'CONCLUÍDO'){ ?>
                                                                               <td style="background-color: #00CC00" class="center"><?php echo $plano->status; ?></td>
                                                                                <?php } else if(($plano->status == 'PENDENTE')||$plano->status == 'AGUARDANDO VALIDAÇÃO' ){?>
                                                                               <td style="background-color: #CCA940" class="center"><?php echo $plano->status; ?></td>
                                                                                <?php } else if( $plano->status == 'ABERTO'){?>
                                                                               <td style="background-color: activecaption" class="center"><?php echo $plano->status; ?></td>
                                                                                <?php } ?> 
                                                                               <?php if($status_treinamento != 1){ ?>
                                                                               <td class="center">
                                                                                    <?php if($statusAta != 1){ ?>
                                                                                     <a style="color: #128f76;" class="btn fa fa-edit" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/manutencao_acao_pendente/'.$plano->idplanos); ?>"></a>
                                                                                   <?php } ?>
                                                                                </td>
                                                                                <td class="center">
                                                                                    <?php if($statusAta != 1){ ?>
                                                                                    <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('atas/deletePlano/'.$plano->idplanos.'/'.$plano->idatas); ?>"></a>
                                                                                    <?php }else if($statusAta == 1){ ?>
                                                                                     <a style="color: #128f76;" class="btn fa fa-eye" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/manutencao_acao_av/'.$plano->idplanos); ?>"><?= lang('Ver') ?></a>

                                                                                    <?php } ?>
                                                                                </td>
                                                                                <?php } ?>
                                                                            </tr>
                                                                                <?php
                                                                                }
                                                                                ?>




                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <!-- /.table-responsive -->
                                                            </div>
                                                        </div>
                                                    </div>






                                           

                                        </div>
                                    </div>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col-lg-12 -->

                </div>
               
                <!-- /.FIM AÇÕES PENDENTES -->
                
               
            </div>
            <!-- /.page-content -->

        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->

    </div>
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
    <div id="logout">
        <div class="logout-message">
            <img class="img-circle img-logout" src="img/profile-pic.jpg" alt="">
            <h3>
                <i class="fa fa-sign-out text-green"></i> Ready to go?
            </h3>
            <p>Select "Logout" below if you are ready<br> to end your current session.</p>
            <ul class="list-inline">
                <li>
                    <a href="login.html" class="btn btn-green">
                        <strong>Logout</strong>
                    </a>
                </li>
                <li>
                    <button class="logout_close btn btn-green">Cancel</button>
                </li>
            </ul>
        </div>
    </div>
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <script src="<?= $assets ?>dashboard/js/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/datatables/datatables-bs3.js"></script>
    
    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/advanced-tables-demo.js"></script>

    <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
</body>

</html>
