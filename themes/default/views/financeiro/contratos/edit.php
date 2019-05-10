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
    <script language='JavaScript'>
        function SomenteNumero(e){
            var tecla=(window.event)?event.keyCode:e.which;   
            if((tecla>47 && tecla<58)) return true;
            else{
                if (tecla==8 || tecla==0) return true;
                else  return false;
            }
        }
        </script>
    <script type="text/javascript">
        /* Máscaras ER */
        function mascara(o,f){
            v_obj=o
            v_fun=f
            setTimeout("execmascara()",1)
        }
        function execmascara(){
            v_obj.value=v_fun(v_obj.value)
        }
        function mcep(v){
            v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
            v=v.replace(/^(\d{5})(\d)/,"$1-$2")         //Esse é tão fácil que não merece explicações
            return v
        }
        function mdata(v){
            v=v.replace(/\D/g,"");                    //Remove tudo o que não é dígito
            v=v.replace(/(\d{2})(\d)/,"$1/$2");       
            v=v.replace(/(\d{2})(\d)/,"$1/$2");       

            v=v.replace(/(\d{2})(\d{2})$/,"$1$2");
            return v;
        }
        function mrg(v){
                v=v.replace(/\D/g,'');
                v=v.replace(/^(\d{2})(\d)/g,"$1.$2");
                v=v.replace(/(\d{3})(\d)/g,"$1.$2");
                v=v.replace(/(\d{3})(\d)/g,"$1-$2");
                return v;
        }
        function mvalor(v){
            v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
            v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões
            v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares

            v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
            return v;
        }
        function id( el ){
                return document.getElementById( el );
        }
        function next( el, next )
        {
                if( el.value.length >= el.maxLength ) 
                        id( next ).focus(); 
        }
        </script>
        <script type="text/javascript">

                    $("#dt_vencimento").datetimepicker({
                        format: site.dateFormats.js_ldate,
                        fontAwesome: true,
                        language: 'sma',
                        weekStart: 1,
                        todayBtn: 1,
                        autoclose: 1,
                        todayHighlight: 1,
                        startView: 2,
                        forceParse: 0
                    }).datetimepicker('update', new Date());

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
                        <a  href="<?= site_url('Contratos') ?>">
                        <i class="icon fa fa-list tip" data-placement="left" title="<?=lang("Lista de Contratos")?>"></i><?=lang('Lista de Contratos')?>
                    </a>
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4><i class="fa-fw fa fa-plus"></i><?= lang('Editar Contrato'); ?></h4>
                                </div>
                                
                    
                
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                    <div class="box">
                                        <div class="box-content">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <?php
                                                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                                    echo form_open_multipart("contratos/edit", $attrib);
                                                    echo form_hidden('id_contrato', $contrato->id);
                                                    ?>
                                                    <!-- DADOS DO CONTRATO  -->
                                                    <div class="row">
                                                        <?= lang("Dados do contrato", ""); ?> 
                                                        <div class="col-lg-12">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Projeto", "slProjeto"); ?>
                                                                        <?php
                                                                        $wu3[''] = '';
                                                                        /*foreach ($projetos as $projeto) {
                                                                            $wu3[$projeto->id] = $projeto->projeto;
                                                                            echo 'aquiiii'.$projeto->projeto_atual;
                                                                        }
                                                                          * 
                                                                         */
                                                                        echo form_dropdown('projeto_nome', $projetos->projeto, (isset($_POST['projeto']) ? $_POST['projeto'] : $projetos->projeto), 'id="slProjeto" required="required" disabled class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                                                       echo form_hidden('projeto', $projetos->id);
                                                                       // echo $projetos->projeto;
                                                                        ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Número do Contrato", "numero_contrato"); ?>
                                                                    <?php echo form_input('numero_contrato', (isset($_POST['numero_contrato']) ? $_POST['numero_contrato'] : $contrato->numero_contrato), 'maxlength="200" class="form-control input-tip" required="required" id="numero_contrato"'); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <?= lang("Tipo de Contrato", "tipo"); ?>
                                                                    <?php $pstipo_contrato[''] = '';
                                                                      $pstipo_contrato['CONTRATO'] = lang('CONTRATO');
                                                                      $pstipo_contrato['ASSINATURA ONLINE'] = lang('ASSINATURA ONLINE');
                                                                      $pstipo_contrato['ADITIVO'] = lang('ADITIVO');
                                                                      // 1 - Contrato; 2 - Assinatura Online; 3-Aditivo

                                                                    echo form_dropdown('tipo', $pstipo_contrato, (isset($_POST['tipo']) ? $_POST['tipo'] : $contrato->tipo), 'id="tipo" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de contrato") . '" required="required"   style="width:100%;" '); ?>

                                                                </div>
                                                            </div>
                                                        </div>   
                                                        <div class="col-lg-12">    
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Fornecedor", "fornecedor"); ?>

                                                                         <?php
                                                                            $wh2[''] = '';
                                                                            foreach ($providers as $provider) {
                                                                                $wh2[$provider->id] = $provider->company;
                                                                            }
                                                                            echo form_dropdown('fornecedor', $wh2, (isset($_POST['fornecedor']) ? $_POST['fornecedor'] : $contrato->fornecedor), 'id="fornecedor" class="form-control  select" data-placeholder="' . lang("select") . ' ' . lang("Fornecedor") . '" required="required" style="width:100%;" ');
                                                                            ?>
                                                                                                                                        </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Empresa Responsável", "empresa"); ?>

                                                                        <?php
                                                                            $wh_cliente[''] = '';
                                                                            foreach ($customers as $customer) {
                                                                               $wh_cliente[$customer->id] = $customer->company;
                                                                            }
                                                                            echo form_dropdown('empresa', $wh_cliente, (isset($_POST['empresa']) ? $_POST['empresa'] : $contrato->cliente), 'id="empresa" class="form-control  select" data-placeholder="' . lang("select") . ' ' . lang("Empresa Responsável") . '" required="required" style="width:100%;" ');
                                                                        ?>

                                                                         

                                                                </div>
                                                            </div>
                                                          </div>
                                                        <!-- /DADOS DO CONTRATO  -->

                                                        <!-- INFORMAÇÕES DO CONTRATO  -->
                                                         <hr style="width: 100%; height: 1px; margin-top:15px; background-color: #000000">
                                                         <?= lang("Informações do contrato", ""); ?> 
                                                         <div class="col-lg-12"> 
                                                            <div class="col-lg-12">
                                                             <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Data Início da vigência", "dt_inicio_vigencia"); ?>
                                                                            <?php echo form_input('dt_inicio_vigencia', (isset($_POST['dt_inicio_vigencia']) ? $_POST['dt_inicio_vigencia'] : $this->sma->hrld($contrato->inicio_vigencia)), 'maxlength="16" class="form-control input-tip"   id="dt_inicio_vigencia" required="required"'); ?>
                                                                 </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Vigencia Mínima (meses)", "vigencia_minima"); ?>
                                                                    <input type="text" name="vigencia_minima" id="vigencia_minima" value="<?php echo $contrato->vigencia_minima; ?>" required="required" class="form-control"  >
                                                                </div>
                                                            </div>     
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Valor (mínimo) do Contrato", "valor_original"); ?>
                                                                    <?php echo form_input('valor_original', (isset($_POST['valor_original']) ? $_POST['valor_original'] : str_replace('.', ',', $contrato->valor_original)), 'maxlength="15" class="form-control input-tip" onkeypress="mascara(this, mvalor);"  id="valor_original" required="required"'); ?>
                                                                </div>
                                                            </div> 
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?= lang("Quem Assinou", "quem_assinou"); ?>
                                                                        <?php echo form_input('quem_assinou', (isset($_POST['quem_assinou']) ? $_POST['quem_assinou'] : $contrato->quem_assinou), 'maxlength="200" class="form-control input-tip" required="required" id="quem_assinou"'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                               <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <?= lang("Status", "status"); ?>
                                                                        <?php $psStatus[''] = 'Selecione o Status';
                                                                          $psStatus['VIGENTE'] = lang('VIGENTE');
                                                                          $psStatus['SUSPENSO'] = lang('SUSPENSO');
                                                                          $psStatus['ENCERRADO'] = lang('ENCERRADO');
                                                                        echo form_dropdown('status', $psStatus, (isset($_POST['status']) ? $_POST['status'] : $contrato->status), 'id="status" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Renovação de contrato") . '" required="required"   style="width:100%;" '); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?= lang("Segmento do Contrato ", "quem_assinou"); ?>
                                                                        <?php echo form_input('seguimento', (isset($_POST['seguimento']) ? $_POST['seguimento'] : $contrato->seguimento), 'maxlength="200" class="form-control input-tip" required="required" id="seguimento"'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?= lang("document", "document") ?>
                                                                           <?php if($contrato->anexo){ ?>
                                                                            <div class="btn-group">
                                                                                <a href="<?= site_url('../assets/uploads/atas/' . $contrato->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                                                    <i class="fa fa-chain"></i>
                                                                                    <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                                                                                </a>
                                                                            </div>

                                                                            <?php } ?>
                                                                        <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                                                               data-show-preview="false" class="form-control file">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <?= lang("Observação/Assunto", "slnote"); ?>
                                                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $contrato->observacao), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <?= lang("Observação/Título", "obs_titulo"); ?>
                                                                        <?php echo form_textarea('obs_titulo', (isset($_POST['obs_titulo']) ? $_POST['obs_titulo'] : $contrato->obs_titulo), 'class="form-control" id="obs_titulo" style="margin-top: 10px; height: 100px;"'); ?>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>     
                                                       <!-- /INFORMAÇÕES DO CONTRATO  -->

                                                        <!-- INFORMAÇÕES DO REAJUSTE  -->
                                                            <hr style="width: 100%; height: 1px; margin-top:15px; background-color: #000000">
                                                            <?= lang("Reajuste", ""); ?>
                                                            <div class="col-lg-12">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Índice de Reajuste", "reajuste"); ?>
                                                                <?php echo form_input('reajuste', (isset($_POST['reajuste']) ? $_POST['reajuste'] : $contrato->indice_reajuste), 'maxlength="200" class="form-control input-tip"  id="reajuste"'); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Mês do Reajuste", "mes_reajuste"); ?>
                                                                <?php echo form_input('mes_reajuste', (isset($_POST['mes_reajuste']) ? $_POST['mes_reajuste'] : $contrato->mes_reajuste), 'maxlength="200" class="form-control input-tip"  id="mes_reajuste"'); ?>
                                                                </div>
                                                            </div>    
                                                            </div>
                                                            <!-- /INFORMAÇÕES DO REAJUSTE  -->

                                                        <!-- INFORMAÇÕES DA RENOVAÇÃO  -->
                                                            <hr style="width: 100%; height: 1px; margin-top:15px; background-color: #000000">
                                                            <?= lang("Renovação", ""); ?>
                                                                <div class="col-lg-12">
                                                                    <div class="col-sm-3">
                                                                        <div class="form-group">
                                                                            <?= lang("Tipo de Renovação", "tipo_renovacao"); ?>
                                                                            <?php $pstipo[''] = 'Selecione';
                                                                              $pstipo['AUTOMÁTICO'] = lang('AUTOMÁTICO');
                                                                              $pstipo['PÓS-ADITIVO'] = lang('PÓS-ADITIVO');
                                                                              $pstipo['DETERMINADO'] = lang('DETERMINADO');
                                                                            echo form_dropdown('tipo_renovacao', $pstipo, (isset($_POST['tipo_renovacao']) ? $_POST['tipo_renovacao'] : $contrato->tipo_renovacao), 'id="tipo_renovacao" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Renovação de contrato") . '" required="required"   style="width:100%;" '); ?>
                                                                        </div>
                                                                    </div>
                                                                 <div class="col-sm-3">
                                                                    <div class="form-group">
                                                                        <?= lang("Período de Renovação", "periodicidade"); ?>
                                                                        <?php $pstipo2[''] = '';
                                                                          $pstipo2['MENSAL'] = lang('MENSAL');
                                                                          $pstipo2['ANUAL'] = lang('ANUAL');
                                                                          $pstipo2['FIXO'] = lang('FIXO');
                                                                          // 1 - Mensal; 2 - Anual; 3-Fixo;

                                                                        echo form_dropdown('periodicidade', $pstipo2, (isset($_POST['periodicidade']) ? $_POST['periodicidade'] : $contrato->periodicidade), 'id="periodicidade" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o período de renovação de contrato") . '" required="required"   style="width:100%;" '); ?>

                                                                    </div>
                                                                </div>       
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?= lang("Tempo Limite de Renovação (ex. 90 dia antes)", "tempo_limite"); ?>
                                                                          <?php echo form_input('tempo_limite', (isset($_POST['tempo_limite']) ? $_POST['tempo_limite'] : $contrato->tempo_limite_renovacao), 'maxlength="200" class="form-control input-tip" required="required" id="tempo_limite"'); ?>
                                                                    </div>
                                                                </div>          
                                                          </div>
                                                         <!-- /INFORMAÇÕES DA RENOVAÇÃO  -->

                                                        <!-- INFORMAÇÕES DO PAGAMENTO-->   
                                                         <hr style="width: 100%; height: 2px; margin-top:15px; background-color: #000000">
                                                         <?= lang("Dados para pagamento", ""); ?>   
                                                        <div class="col-lg-12">

                                                            <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Data do Primeiro Pagamento", "dt_primeiro_pagamento"); ?>
                                                             
                                                                  <?php echo form_input('dt_primeiro_pagamento', (isset($_POST['dt_primeiro_pagamento']) ? $_POST['dt_primeiro_pagamento'] : $this->sma->hrld($contrato->data_primeiro_pgto)), 'class="form-control input-tip datetime"  id="dt_primeiro_pagamento" required=$projeto"required"'  ); ?>
                                  
                                                           
                                                            </div>
                                                        </div>      
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Número de Parcelas", "vigencia_minima"); ?>
                                                                    <input type="number" name="parcelas" id="parcelas" value="<?php echo $contrato->parcelas; ?>" required="required" class="form-control"  >
                                                                </div>
                                                            </div> 
                                                            
                                                        </div>
                                                        <div class="col-lg-12"> 
                                                            <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Valor das parcelas", "valor_parcela"); ?>
                                                                       <?php echo form_input('valor_parcela', (isset($_POST['valor_parcela']) ? $_POST['valor_parcela'] : str_replace('.', ',', $contrato->valor_atual)), 'maxlength="15" class="form-control input-tip" onkeypress="mascara(this, mvalor);"  id="valor_parcela" required="required"'); ?>
                                                            </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Dia do Vencimento", "dia"); ?>
                                                                <?php echo form_input('dia', (isset($_POST['dia']) ? $_POST['dia'] : $contrato->dia_vencimento), 'maxlength="200" class="form-control input-tip" required="required" id="dia"'); ?>
                                                           </div>
                                                        </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Tempo de Recebimento da NF (dias)", "tempo_recebimento"); ?>
                                                                         <?php echo form_input('tempo_recebimento', (isset($_POST['tempo_recebimento']) ? $_POST['tempo_recebimento'] : $contrato->tempo_recebimento_nf), 'maxlength="200" class="form-control input-tip" required="required" id="tempo_recebimento"'); ?>
                                                              </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Tempo limite de corte (dias)", "tempo_corte"); ?>
                                                                    <?php echo form_input('tempo_corte', (isset($_POST['tempo_corte']) ? $_POST['tempo_corte'] : $contrato->limite_corte), 'maxlength="200" class="form-control input-tip" required="required" id="tempo_corte"'); ?>


                                                                </div>
                                                            </div>
                                                        </div>   
                                                          <?php
                                                        if($contrato->status == 'VIGENTE'){
                                                            
                                                        ?>
                                                         <center>  
                                                            <div class="col-lg-12">   
                                                                <div class="col-md-12">
                                                                    <div
                                                                        class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                                                        <a href="<?= site_url('Contratos'); ?>" class="btn btn-danger" id="reset"><?= lang('Sair') ?></a></div>
                                                                </div>
                                                            </div>
                                                         </center>    
                                                         <?php
                                                         }else{
                                                         ?>
                                                         <center>  
                                                            <div class="col-lg-12">   
                                                                <div class="col-md-12">
                                                                    <div class="fprom-group">
                                                                        <a href="<?= site_url('Contratos'); ?>" class="btn btn-danger" id="reset"><?= lang('Sair') ?></a></div>
                                                                </div>
                                                            </div>
                                                         </center> 
                                                         <?php
                                                         }
                                                         ?>
                                                    </div>


                                                    <?php echo form_close(); ?>

                                                </div>
                                                <?php 
                                                /*
                                                ?>
                                                <div style="margin-top: 25px;" class="col-lg-12">
                                                    <div class="col-lg-4">
                                                        <a  href="#"  class="btn btn-block btn-social btn-lg btn-red">
                                                            <i class="fa fa-ban fa-fw fa-3x"></i>
                                                            SUSPENDER
                                                        </a>
                                                    </div>
                                                <div class="col-lg-4">
                                                        <a  href="#"  class="btn btn-block btn-social btn-lg btn-green">
                                                            <i class="fa fa-refresh fa-fw fa-3x"></i>
                                                            RENOVAR
                                                        </a>
                                                    </div>
                                                </div>   
                                                <?php 
                                                 * 
                                                 */
                                                ?>
                           
                                                
                                        </div>
                                    </div>
                                
                            </div>
                           
                            
                            
                            <h3>TÍTULOS PARA ESTE CONTRATO</h3>
                            
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr >
                                                <th></th>
                                                <th>PARCELA</th>
                                                <th>DT VENCTO  NF</th>
                                                <th>TITULO</th>
                                                <th>FATURA</th>
                                                <th>VL A PAGAR</th>
                                                <th>VL PAGO</th>
                                                
                                                
                                                <th>DT PGTO NF</th>
                                                <th>STATUS</th>
                                                <th><i class="fa fa-download"></i></th>
                                                <th><i class="fa fa-print"></i></th>
                                                
                                                
                                              
                                                
                                            </div>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                               
                                                $cont = 1; 
                                                $soma_a_pagar = 0;
                                                $soma_pago = 0;
                                                $titulos = $this->projetos_model->getTitulosByContrato($contrato->id);
                                                foreach ($titulos as $titulo) {
                                                   
                                                   /*
                                                    $fornecedor = $this->projetos_model->getForncedorByID($contrato->fornecedor);
                                                    $fornecedor_nome = $fornecedor->company;
                                                    
                                                    $titulos = $this->projetos_model->getForncedorByID($contrato->fornecedor);
                                                    
                                                    $data_inicio_vigencia = substr($this->sma->hrld($contrato->inicio_vigencia),0,10);
                                                    $data_primeiro_pagamento = substr($this->sma->hrld($contrato->data_primeiro_pgto),0,10) 
                                                  
                                                    * 
                                                    */
                                                    $soma_a_pagar += $titulo->amount;
                                                    $soma_pago += $titulo->cr;
                                                ?>   
                                                <tr   class="odd gradeX">
                                                        <td><?php $cont++; ?>   </td> 
                                                        <td><?php echo $titulo->parcela_atual.'/'.$titulo->parcela; ?></td>     
                                                         <td><?php echo substr($this->sma->hrld($titulo->date), 0, 10); ?></td>  
                                                        <td class="center"><?php echo $titulo->titulo; ?></td>
                                                       <td class="center"><?php echo $titulo->fatura; ?></td>     
                                                        <td class="center">
                                                           <?php echo ' R$ '. str_replace('.', ',', $titulo->amount); ?>
                                                         </td>     
                                                        <td>
                                                            <?php echo ' R$ '. str_replace('.', ',', $titulo->cr); ?>
                                                        </td> 
                                                           
                                                        <td><?php if(($titulo->date_pagamento != '0000-00-00')||($titulo->date_pagamento != '0000-00-00')){ echo substr($this->sma->hrld($titulo->date_pagamento), 0, 10); } ?></td> 
                                                        
                                                        <?php
                                                        if($titulo->status == 'PAGO'){
                                                            $cor = 'GREEN';
                                                            $icone = 'check';
                                                        }else if ($titulo->status == 'ABERTO'){
                                                            $cor = 'ORANGE';
                                                            $icone = 'circle-o';
                                                        }else if ($titulo->status == 'ATRASADO'){
                                                            $cor = 'RED';
                                                            $icone = 'circle-o';
                                                        }else if ($titulo->status == 'SUSPENSO'){
                                                            $cor = 'BLACK';
                                                            $icone = 'fa-ban';
                                                        }
                                                        ?>
                                                        
                                                        <td style="background-color: <?php echo $cor; ?>; color: #ffffff; " class="center"><?php echo $titulo->status; ?></td>
                                                        <th><?php if($titulo->anexo){ ?> <a title="Download" onclick="attachment(<?php echo $titulo->anexo; ?>);" href="#"><i class="fa fa-file-pdf-o"></i></a> <?php }else{ ?> - <?php } ?></th> 
                                                        <td>
                                                           
                                                                <a  href="<?= site_url('Contratos/imprimir_pdf/'.$contrato->id.'/'.$titulo->id); ?>"  class="btn btn-block btn-social">
                                                                   
                                                                    IMPRIMIR 
                                                                </a>
                                                           
                                                        </td>
                                            </tr>
                                                <?php
                                                
                                                }
                                                
                                                ?>
                                                
                                            
                                        </tbody>
                                       
                                    </table>
                                    
                                    <table>
                                        <tr>
                                              <td style="background-color: orange; color: #ffffff; height: 40px; font-size: 18px;" class="center">TOTAL A PAGAR : <?php echo ' R$ '. number_format($soma_a_pagar, 2, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                        <td style="background-color: green; color: #ffffff;height: 40px; font-size: 18px;" class="center">TOTAL PAGO : <?php echo ' R$ '.  number_format($soma_pago, 2, ',', '.'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
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
