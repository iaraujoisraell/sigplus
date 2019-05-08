<html lang="en">

<?php  $this->load->view($this->theme . 'menu_head'); ?>

    <body >

    

        <!-- begin TOP NAVIGATION -->
         
      
                <!-- begin TOP NAVIGATION  style="background-color: seagreen; position: relative; width: 100%; height: 50px;"-->
                <nav  class="navbar-top"  role="navigation">

                    <div class="navbar-header">
                           
                                        <a  href="<?= site_url('Login_Projetos/menu'); ?>" >
                                            <img width="170px" height="50px" src="<?= base_url() ?>assets/uploads/logos/LogoUnimed1.png " >
                                        </a>  
                    </div>
                </nav>
                
                
                <?php echo form_close(); ?>
        <!-- /.navbar-top -->
        <!-- end TOP NAVIGATION -->
        <!-- end TOP NAVIGATION -->

       
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper" >

           
            <div class="box">
             <?php
             $ata = $this->atas_model->getAtaByID($id_ata);
             $dados_usuario = $this->site->geUserByID($id_usuario);
             ?>
                <div class="box-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <center>  
                                <p class="introtext"> <font style="font-size: 20px;"> <?php echo 'DADOS DA PESQUISA DE REAÇÃO PARA ESTE TREINAMENTO'; ?> </font></p>
                                       </center>

                            
                        </div>
                         <hr>
                         
                          <div style="margin-top:15px;" class="col-lg-12">
                         <div style="width: 100%;height: 40%" class="portlet portlet-default">
                            <div class="portlet-heading">
                                        <div class="portlet-title">
                                             <h4>DADOS DO TREINAMENTO </h4>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                             <br>

                        
                                                        
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <?= lang("Projeto :", "slProjeto"); ?>
                                                  <?php
                                                 $projetos = $this->projetos_model->getProjetoByID($ata->projetos);

                                                 echo $projetos->projeto;

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
                                    <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <?= lang("Conteúdo do Treinamento : ", "slpauta"); ?>
                                                                        <p> <?php echo $ata->pauta ?></p>
                                                                     
                                                                    </div>
                                                                </div>                                                                
                                    <div class="col-md-4">
                                        <div class="form-group">
                                                                            <?= lang("Facilitador(es):", "facilitador"); ?>
                                                                                <?php
                                                                                 $facilitadores_cadastrados_ata = $this->atas_model->getAtaFacilitadores_ByID_ATA($id_ata);
                                                                                    foreach ($facilitadores_cadastrados_ata as $facilitador) {
                                                                                      $facilitador_id_usuario = $facilitador->usuario;
                                                                                        
                                                                                        $facilitador = $this->site->geUserByID($facilitador_id_usuario);
                                                                                        echo $facilitador->first_name.' '.$facilitador->last_name. '<br>';
                                                                                    }
                                                                                 
                                                                                
                                                                                ?>
                                                                          </div>
                                    </div>
                                                           
                         
                          </div>   
                              </div>
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
                                                    $treinamentos = $this->atas_model->getTreinamentosByATA($id_ata);
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
                         
                         

                    </div>
                </div>
            </div>
            
            
            
            
            
              <div class="portlet portlet-default">
                     <div class="portlet-heading">
                        <div class="portlet-title">
                             <h4>PARTICIPANTES </h4>
                        </div>
                        <div class="clearfix"></div>
                    </div>

             <div class="portlet-body">
                        <div class="table-responsive">
                            <table style="width: 100%;" class="table table-striped table-bordered table-hover table-green">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;" >ID</th>

                                        <th style="width: 20%;">NOME</th>
                                        <th style="width: 10%;">PRESENÇA</th>
                                        <th style="width: 10%;">AVALIAÇÃO</th>
                                        <th style="width: 30%;">SUGESTÕES DO PARTICIPANTE</th>
                                        <th style="width: 25%;">OBSERVAÇÃO DO FACILITADOR</th>


                                    </tr>
                                </thead>
                                <tbody>
                                     <?php




                                        $wu4[''] = '';
                                        $cont_treinamento = 1;
                                        $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id_ata);
                                        foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
                                            if($participante_cadastrados){
                                            $cadastro_usuario =  $this->site->getUser($participante_cadastrados->id_participante);


                                            $avaliacao = $participante_cadastrados->avaliacao;

                                            if($avaliacao == 1){
                                                $resp_avaliacao = 'SIM';
                                            }else{
                                                $resp_avaliacao = 'NÃO';
                                            }
                                            
                                            
                                            $presenca_confirmada = $participante_cadastrados->presenca_confirmada;
                                            
                                            if(!$presenca_confirmada){
                                                $presenca_confirmada = "NÃO";
                                            }
                                            }
                                        ?>   
                                            <tr  class="odd gradeX">
                                            <td style="width: 5%;" class="center"><?php echo $cont_treinamento++; ?></td>

                                            <td style="width: 20%;" class="center"> <?php echo  $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name; ?></td>
                                            <td style="width: 10%;" class="center"><?php echo $presenca_confirmada; ?></td>
                                            <td style="width: 10%;" class="center"><?php echo $resp_avaliacao; ?></td>
                                            <td style="width: 30%;" class="center"><?php echo $participante_cadastrados->sugestao; ?></td>
                                            <td style="width: 25%;" class="center"><?php echo $participante_cadastrados->observacao_facilitador; ?></td>



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
             <?php
             
              //$participantes_cadastrados_treinamentos = $this->atas_model->getAtaUserParticipantePresente_ByID_ATA($id_ata);
            // print_r($participantes_cadastrados_treinamentos);
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("welcome/informacoes_avaliacao_form", $attrib);
                echo form_hidden('id_participante', $id_participante);
               echo form_hidden('id_ata', $id_ata);
            ?>
            <div class="portlet portlet-default">
                 <div class="portlet-heading">
                    <div class="portlet-title">
                         <h4>AVALIAÇÃO DO TREINAMENTO </h4>
                    </div>
                    <div class="clearfix"></div>
                </div>
              <div class="row ">
                    <div class="col-md-12">
                       
                            

                            <?php
                            //$wu3[''] = '';
                            foreach ($grupo_perguntas as $grupo) {
                                $wu4[$grupo->id] = $grupo->nome;
                            
                            ?>
                            <div class="row " style="width: 100%" >
                                    <div  class="col-lg-12">
                                        <table style="width: 100%" class="table table-striped table-bordered table-hover table-green">
                                            <thead>
                                                <tr style="width: 100%" >
                                                    <th style="width: 60%" > <?php echo $grupo->nome; ?></th>
                                                    <th style="width: 40%" > Respostas</th>
                                              </tr>
                                            </thead>   
                                        </table>
                                        
                                        <table style="width: 100%" >

                                                <tbody>
                                                    <?php
                                                    $wu4[''] = '';
                                                    $cont = 1;
                                                    $perguntas = $this->atas_model->getAllPerguntasByGrupo($grupo->id);
                                                    foreach ($perguntas as $pergunta) {
                                                        ?>   
                                                        <tr class="odd gradeX">
                                                            <td style="width: 1%"><?php echo $cont++; ?>  </td>
                                                            <td style="width: 59%"><font style="font-size: 15px;"><?php echo $pergunta->pergunta; ?></font></td>
                                                            <td style="width: 40%">
                                                                <table style="width: 100%" >
                                                                    <tbody>
                                                                        <tr class="odd gradeX">
                                                                        <?php
                                                                                
                                                                            
                                                                                $respostas = $this->atas_model->getAllRespostaByPergunta($pergunta->id);
                                                                                foreach ($respostas as $resposta) {
                                                                        
                                                                                  $cont_resposta_usuario = 0; 
                                                                                  $quantidade_resposta = 0;
                                                                                  $soma_total_participantes = 0;
                                                                                $participantes_cadastrados_treinamentos = $this->atas_model->getAtaUserParticipantePresente_ByID_ATA($id_ata);
                                                                                foreach ($participantes_cadastrados_treinamentos as $participante_treinamento) {
                                                                                
                                                                                    $soma_total_participantes++;
                                                                                    
                                                                                    $respostas_usuario = $this->atas_model->getAllRespostasUsuariosByParticipanteAndPergunta($participante_treinamento->id, $resposta->id);
                                                                                   $quantidade_resposta = $respostas_usuario->quantidade;
                                                                                   $cont_resposta_usuario+= $quantidade_resposta;
                                                                                   
                                                                                }
                                                                                $porcentagem_resposta = ($cont_resposta_usuario * 100)/$soma_total_participantes;
                                                                                //$soma_total_respostas += $cont_resposta_usuario;
                                                                            ?>  
                                                                            
                                                                            <td >  <?php if($porcentagem_resposta == 100){echo $porcentagem_resposta.' % - '. $resposta->resposta;}else{ echo substr($porcentagem_resposta, 0, 2).' % - '. $resposta->resposta; } ?></td>
                                                                            <?php
                                                                            
                                                                            
                                                                            
                                                                                }
                                                                             
                                                                                ?>
                                                                            </tr>
                                                                    </tbody>
                                                                </table>
                                                                
                                                                
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        
                    </div>
                </div>
             </div>   
            <br>
        
 

 
 <div class="row ">
 <br><br><br>
 <br><br><br>
</div>
 
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
   
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>

</body>

</html>