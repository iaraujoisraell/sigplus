<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">

<?php  $this->load->view($this->theme . 'menu_head'); ?>
<script>
       function attachment(x) {
            if (x) {
                return '<a href="' + site.base_url + 'assets/uploads/atas/' + x + '" target="_blank"><i class="fa fa-chain"></i></a>';
            }
            return x;
        }
</script>
<body>

    <div id="wrapper">
        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">
            <div class="page-content">
                <br><br>
                <?php  $this->load->view($this->theme . 'top'); ?>
                <?php 
                 $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    $perfil_atual = $projetos->group_id;
                    $perfis_user = $this->site->getUserGroupAtual($perfil_atual);

                   $perfis_user = $this->site->getPerfilusuarioByID($usuario);
                   $qtde_perfis_user = 0;
                       foreach ($perfis_user as $item) {
                           $qtde_perfis_user++;
                       }
                ?>
                <center>
                          
                              <div style="width: 70%;" >
                                    <?php
                                    $usuario = $this->session->userdata('user_id');
                                    $projetos_user = $this->site->getAllProjetosUsers($usuario);
                                    $cont = 1;
                                    $qtde_perfis_user = 0;
                                 //   foreach ($projetos_user as $item) {
                                        $id_projeto = $projetos->projeto_atual;
                                        $wu3[''] = '';
                                        $projeto = $this->atas_model->getProjetoByID($id_projeto);
                                        
                                        
                                        /*
                                         * VERIFICA SE TEM AÇÕES AGUARDANDO VALIDAÇÃO
                                         */
                                        $quantidadeAvalidacao = $this->site->getAllPlanosAguardandoValidacao($id_projeto);
                         
                                        
                                        
                                         $acoes_aguardando_validacao = $quantidadeAvalidacao->quantidade;
                                         
                                        
                                        ?>
                                        <a href="<?= site_url('Login_Projetos/projeto_ata/'.$projeto->id); ?>" class="btn btn-block btn-social btn-lg " style="background-color: <?php echo $projeto->botao; ?>">
                                            <i style="color:#ffffff;" class="fa fa-tasks fa-fw fa-3x"></i>
                                            <font style="color:#ffffff; font-weight:bold;">  <?php echo $projeto->projeto; ?>  </font>  
                                    <?php if($acoes_aguardando_validacao > 0){  ?>  <font style="color:#ffffff; font-size: 14px; margin-left: 15px;"><?php if($acoes_aguardando_validacao > 1){ ?>  <?php echo $acoes_aguardando_validacao; ?> Ações A.Validação <?php }else{ ?>  <?php echo $acoes_aguardando_validacao; ?> Ação A. Validação <?php } ?></font>  <?php } ?>
                                        </a>
                                      <?php
                                       //Qtde de AÇÕES
                                        $total_acoes =  $this->projetos_model->getQtdeAcoesByProjeto($id_projeto);
                                        $total_acoes = $total_acoes->total_acoes;
                                        //Qtde de Ações concluídas
                                        $concluido = $this->projetos_model->getStatusAcoesByProjeto($id_projeto, 'CONCLUÍDO');
                                        $concluido =  $concluido->status;
                                        //Qtde de ações Pendentes
                                        $pendente = $this->projetos_model->getAcoesPendentesByProjeto($id_projeto, 'PENDENTE');
                                        $avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByProjeto($id_projeto, 'AGUARDANDO VALIDAÇÃO');
                                        $pendente =  $pendente->pendente + $avalidacao->avalidacao;
                                        //Qtde de Ações Atrasadas
                                        $atrasadas = $this->projetos_model->getAcoesAtrasadasByProjeto($id_projeto, 'PENDENTE');
                                        $atrasadas =  $atrasadas->atrasadas;
                                        
                                        if($concluido){
                                            $porc_concluido = ($concluido * 100)/$total_acoes;
                                        }else{
                                            $porc_concluido = 0;
                                        }
                                        if($pendente){
                                            $porc_pendente = ($pendente * 100)/$total_acoes;
                                        }else{
                                            $porc_pendente = 0;
                                        }
                                        
                                        if($atrasadas){
                                            $porc_atrasado = ($atrasadas * 100)/$total_acoes;
                                        }else{
                                            $porc_atrasado = 0;
                                        }
                                      ?>
                                        <div class="progress">
                                          <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido;  ?>%">
                                           <?php if($porc_concluido != 100){ echo  substr($porc_concluido,0,2); }else{ echo $porc_concluido; } ?> % Concluído
                                          </div>
                                          <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente;  ?>%">
                                           <?php if($porc_pendente != 100){ echo  substr($porc_pendente,0,2); }else{ echo $porc_pendente; } ?>% Em Andamento
                                          </div>
                                          <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php  echo $porc_atrasado;  ?>%">
                                           <?php if($porc_atrasado != 100){ echo  substr($porc_atrasado,0,2); }else{ echo $porc_atrasado; } ?>% Atrasado
                                          </div>
                                        </div>
                                         
                                        <?php
                                        $cont++;
                                   // }
                                    //  }
                                    ?>   
                                          
                                          
                                          
                                        </div>   
                            
                           
                         
                        </center>
               
                
               <!-- DIV TABLE AÇÕES PENDENTES -->  
                <div id="acoes_pendentes">
                    <div class="row">
                    <div class="col-lg-12">
                        <div style="width: 200px; " class="portlet-title">
                                 <h4>  <a  href="<?= site_url('Contratos/add') ?>">
                                    <i class="icon fa fa-plus-circle tip" data-placement="left" title="<?=lang("Novo Contrato")?>"></i> Novo Contrato
                                    </a> </h4>
                                </div>
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h3>Gestão Contratos do Projeto</h3>
                                </div>
                                
                    
                
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr >
                                                <th>ID</th>
                                                <th>No. CONTRATO</th>
                                              
                                                <th>VALORES ATUAIS</th>
                                                <th>VIGÊNCIA</th>
                                                <th>REAJUSTE</th>
                                                <th>RENOVAÇÃO</th>
                                                <th>CONTROLE</th>
                                                <th>STATUS</th>
                                                <th><i class="fa fa-download"></i></th>
                                                <th>OPÇÕES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                                $wu4[''] = '';
                                                $cont = 1; 
                                                foreach ($contratos as $contrato) {
                                                    echo
                                                    $fornecedor = $this->projetos_model->getForncedorByID($contrato->fornecedor);
                                                    $fornecedor_nome = $fornecedor->company;
                                                    
                                                    $titulos = $this->projetos_model->getForncedorByID($contrato->fornecedor);
                                                    
                                                    $data_inicio_vigencia = substr($this->sma->hrld($contrato->inicio_vigencia),0,10);
                                                    $data_primeiro_pagamento = substr($this->sma->hrld($contrato->data_primeiro_pgto),0,10) 
                                                  
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?>   </td> 
                                                        <td><?php echo  $fornecedor_nome.' / '; ?><?php echo $contrato->tipo.' No. : '.$contrato->numero_contrato; ?>
                                                            <p><font  style="font-size: 12px; ">Seguimento : </font><font  style="font-size: 12px; "><?php echo $contrato->seguimento; ?></font></p>
                                                            <p><font  style="font-size: 12px; color: blue;"><?php echo $contrato->observacao; ?></font></p>
                                                             <p><font  style="font-size: 12px; color: blue;"><?php echo 'Assinado por : '.$contrato->quem_assinou; ?></font></p>
                                                        </td>     
                                                              
                                                        <td class="center">
                                                           <?php echo 'Vl. do Contrato: R$ '. str_replace('.', ',', $contrato->valor_original); ?>
                                                            <p><font  style="font-size: 12px; color: blue;"><?php echo '1o Pgto: '.$data_inicio_vigencia; ?></font></p>
                                                             <p><font style="font-size: 12px; color: blue;"><?php echo  $contrato->parcelas.' X  R$ '.  str_replace('.', ',', $contrato->valor_atual); ?></font></p>
                                                            <p><font style="font-size: 12px; color: blue;">Dia do Vencimento: <?php echo $contrato->dia_vencimento; ?></font></p>
                                                        </td>     
                                                        <td>
                                                             <p> <?php echo $contrato->vigencia_minima.' (meses)'; ?> </p>
                                                              <p><font  style="font-size: 12px; color: blue;"><?php echo 'Início : '.$data_inicio_vigencia; ?></font></p>
                                                        </td> 
                                                        <td class="center "> 
                                                            <p><font style="font-size: 12px; "><?php if($contrato->indice_reajuste){ echo 'Reajuste : '. $contrato->indice_reajuste; } ?></font></p> 
                                                            <p><font style="font-size: 12px; "><?php echo 'Mês  : '. $contrato->mes_reajuste; ?></font></p>
                                                     
                                                        </td>    
                                                        <td class="center "> 
                                                            <p><font style="font-size: 12px; "><?php echo 'Tipo : '. $contrato->tipo_renovacao; ?></font></p> 
                                                            <p><font style="font-size: 12px; "><?php echo 'Período : '. $contrato->periodicidade; ?></font></p>
                                                     
                                                        </td>
                                                        
                                                        <td class="center">
                                                            <p><font  style="font-size: 12px; color: blue;"><?php echo 'Renovação : '.$contrato->tempo_limite_renovacao; ?></font></p>
                                                            <p><font  style="font-size: 12px; color: blue;"><?php echo 'N.F. : '.$contrato->tempo_recebimento_nf; ?></font></p>
                                                            <p><font style="font-size: 12px; color: blue;"> <?php echo 'Corte : '.$contrato->limite_corte; ?></font></p>
                                                        </td>  
                                                        
                                                        <?php
                                                        if($contrato->status == 'VIGENTE'){
                                                            $cor = 'GREEN';
                                                        }else if ($contrato->status == 'SUSPENSO'){
                                                            $cor = 'ORANGE';
                                                        }else if ($contrato->status == 'ENCERRADO'){
                                                            $cor = 'BLACK';
                                                        }
                                                        ?>
                                                        
                                                        <td style="background-color: <?php echo $cor; ?>; color: #ffffff; " class="center"><?php echo $contrato->status; ?></td>
                                                        <td>
                                                            <?php  if($contrato->anexo){ ?> 
                                                            <a href="<?= site_url('../assets/uploads/atas/' . $contrato->anexo) ?>" target="_blank"><i class="fa fa-download"></i></a>
                                                             
                                                                <?php }else{ ?> - <?php } ?>
                                                        </td> 
                                                        <td class="center">
                                                            <a  href="<?= site_url('Contratos/edit/'.$contrato->id); ?>"  class="btn btn-block btn-social btn-lg btn-green">
                                                            <i class="fa fa-folder-o fa-fw fa-3x"></i>
                                                          Abrir
                                                        </a>
                                                                
                                                         </td>
                                            </tr>
                                                <?php
                                                
                                                }
                                                ?>
                                            
                                            
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col-lg-12 -->

                </div>
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
