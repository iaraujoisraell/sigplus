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
                
                 <?php  $this->load->view($this->theme . 'atalhos'); ?>
                <br><br>
                
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
                                    <h4><i class="fa-fw fa fa-plus"></i><?= lang('Novo Contrato'); ?></h4>
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
                                                    echo form_open_multipart("contratos/add", $attrib);

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
                                                                        echo form_dropdown('projeto_nome', $projetos->projeto, (isset($_POST['projeto']) ? $_POST['projeto'] : $projetos->projeto), 'id="slProjeto" required="required" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                                                       echo form_hidden('projeto', $projetos->id);
                                                                       // echo $projetos->projeto;
                                                                        ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Número do Contrato", "numero_contrato"); ?>
                                                                    <?php echo form_input('numero_contrato', (isset($_POST['numero_contrato']) ? $_POST['numero_contrato'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="numero_contrato"'); ?>
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

                                                                    echo form_dropdown('tipo', $pstipo_contrato, (isset($_POST['tipo']) ? $_POST['tipo'] : ""), 'id="tipo" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de contrato") . '" required="required"   style="width:100%;" '); ?>

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
                                                                            echo form_dropdown('fornecedor', $wh2, (isset($_POST['fornecedor']) ? $_POST['fornecedor'] : ""), 'id="fornecedor" class="form-control  select" data-placeholder="' . lang("select") . ' ' . lang("Fornecedor") . '" required="required" style="width:100%;" ');
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
                                                                            echo form_dropdown('empresa', $wh_cliente, (isset($_POST['empresa']) ? $_POST['empresa'] : $Settings->default_supplier), 'id="empresa" class="form-control  select" data-placeholder="' . lang("select") . ' ' . lang("Empresa Responsável") . '" required="required" style="width:100%;" ');
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
                                                                    <?= lang("Data Início da vigência", "dt_vencimento"); ?>
                                                                    <input type="datetime-local" name="dt_inicio_vigencia" id="dt_inicio_vigencia" required="required" class="form-control"  >
                                                                 </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Vigencia Mínima (meses)", "vigencia_minima"); ?>
                                                                    <input type="number" name="vigencia_minima" id="vigencia_minima" required="required" class="form-control"  >
                                                                </div>
                                                            </div>     
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Valor (mínimo) do Contrato", "valor_original"); ?>
                                                                    <?php echo form_input('valor_original', (isset($_POST['valor_original']) ? $_POST['valor_original'] : $Settings->default_value), 'maxlength="15" class="form-control input-tip" onkeypress="mascara(this, mvalor);"  id="valor_original" required="required"'); ?>
                                                                </div>
                                                            </div> 
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?= lang("Quem Assinou", "quem_assinou"); ?>
                                                                        <?php echo form_input('quem_assinou', (isset($_POST['quem_assinou']) ? $_POST['quem_assinou'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="quem_assinou"'); ?>
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
                                                                        echo form_dropdown('status', $psStatus, (isset($_POST['status']) ? $_POST['status'] : ""), 'id="status" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Renovação de contrato") . '" required="required"   style="width:100%;" '); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?= lang("Segmento do Contrato ", "quem_assinou"); ?>
                                                                        <?php echo form_input('seguimento', (isset($_POST['seguimento']) ? $_POST['seguimento'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="seguimento"'); ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?= lang("document", "document") ?>
                                                                        <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                                                               data-show-preview="false" class="form-control file">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <?= lang("Observação/Assunto", "slnote"); ?>
                                                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

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
                                                                <?php echo form_input('reajuste', (isset($_POST['reajuste']) ? $_POST['reajuste'] : $slnumber), 'maxlength="200" class="form-control input-tip"  id="reajuste"'); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Mês do Reajuste", "mes_reajuste"); ?>
                                                                <?php echo form_input('mes_reajuste', (isset($_POST['mes_reajuste']) ? $_POST['mes_reajuste'] : $slnumber), 'maxlength="200" class="form-control input-tip"  id="mes_reajuste"'); ?>
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
                                                                            echo form_dropdown('tipo_renovacao', $pstipo, (isset($_POST['tipo_renovacao']) ? $_POST['tipo_renovacao'] : ""), 'id="tipo_renovacao" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Renovação de contrato") . '" required="required"   style="width:100%;" '); ?>
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

                                                                        echo form_dropdown('periodicidade', $pstipo2, (isset($_POST['periodicidade']) ? $_POST['periodicidade'] : ""), 'id="periodicidade" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o período de renovação de contrato") . '" required="required"   style="width:100%;" '); ?>

                                                                    </div>
                                                                </div>       
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?= lang("Tempo Limite de Renovação (ex. 90 dia antes)", "tempo_limite"); ?>
                                                                          <?php echo form_input('tempo_limite', (isset($_POST['tempo_limite']) ? $_POST['tempo_limite'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="tempo_limite"'); ?>
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
                                                                <input type="datetime-local" name="dt_primeiro_pagamento" id="dt_primeiro_pagamento" required="required" class="form-control"  >
                                                            </div>
                                                        </div>      
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Número de Parcelas", "vigencia_minima"); ?>
                                                                    <input type="number" name="parcelas" id="parcelas" required="required" class="form-control"  >
                                                                </div>
                                                            </div> 
                                                            
                                                        </div>
                                                        <div class="col-lg-12"> 
                                                             <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Valor 1a Parcela", "valor_primeiro_pgto"); ?>
                                                                <?php echo form_input('valor_primeiro_pgto', (isset($_POST['valor_primeiro_pgto']) ? $_POST['valor_primeiro_pgto'] : $Settings->default_value), 'maxlength="15" class="form-control input-tip" onkeypress="mascara(this, mvalor);"  id="valor_atual" required="required"'); ?>
                                                            </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Valor das parcelas", "valor_parcela"); ?>
                                                                <?php echo form_input('valor_parcela', (isset($_POST['valor_parcela']) ? $_POST['valor_parcela'] : $Settings->default_value), 'maxlength="15" class="form-control input-tip" onkeypress="mascara(this, mvalor);"  id="valor_parcela" required="required"'); ?>
                                                            </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Dia do Vencimento", "dia"); ?>
                                                                <?php echo form_input('dia', (isset($_POST['dia']) ? $_POST['dia'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="dia"'); ?>
                                                           </div>
                                                        </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Tempo de Recebimento da NF (dias)", "tempo_recebimento"); ?>
                                                                         <?php echo form_input('tempo_recebimento', (isset($_POST['tempo_recebimento']) ? $_POST['tempo_recebimento'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="tempo_recebimento"'); ?>
                                                              </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <?= lang("Tempo limite de corte (dias)", "tempo_corte"); ?>
                                                                    <?php echo form_input('tempo_corte', (isset($_POST['tempo_corte']) ? $_POST['tempo_corte'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="tempo_corte"'); ?>


                                                                </div>
                                                            </div>
                                                        </div>   

                                                         <br>
                                                          <!-- INFORMAÇÕES DO AVALIAÇÃO  -->
                                                            <hr style="width: 100%; height: 1px; margin-top:15px; background-color: #000000">
                                                            <?= lang("Controle de Avaliação de desempenho", ""); ?>
                                                            <div class="col-lg-12">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Responsável pela avaliação", "reajuste"); ?>
                                                              <?php
                                                            $wu4[''] = 'Selecione o Responsável';
                                                            foreach ($users as $user) {
                                                                $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                                            }
                                                          //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                                            echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $participantes_usuarios), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsavel") . ' "   style="width:100%;"   ');

                                                            ?>  
                                                              </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Data de retorno da avaliação", "mes_reajuste"); ?>
                                                                        <input type="datetime-local" name="data_retorno" id="data_retorno" class="form-control"  >
                                                               </div>
                                                            </div>    
                                                                
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Tipo de Índice", "reajuste"); ?>
                                                                <?php echo form_input('reajuste', (isset($_POST['reajuste']) ? $_POST['reajuste'] : $slnumber), 'maxlength="200" class="form-control input-tip"  id="reajuste"'); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Valor Mínimo para o índice", "mes_reajuste"); ?>
                                                                <?php echo form_input('mes_reajuste', (isset($_POST['mes_reajuste']) ? $_POST['mes_reajuste'] : $slnumber), 'maxlength="200" class="form-control input-tip"  id="mes_reajuste"'); ?>
                                                                </div>
                                                            </div>
                                                                
                                                                
                                                            </div>
                                                            
                                                            <div class="col-lg-12">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Ata para vincular as ações", "reajuste"); ?>
                                                              <?php
                                                            $wu4[''] = 'Selecione a ATA';
                                                            foreach ($users as $user) {
                                                                $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                                            }
                                                          //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                                            echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $participantes_usuarios), 'id="slResponsavel"   class="form-control  select" data-placeholder="' . lang("Selecione o Responsavel") . ' "   style="width:100%;"   ');

                                                            ?>  
                                                              </div>
                                                            </div>
                                                              
                                                             <div class="col-md-3">
                                                                <div class="form-group">
                                                                <?= lang("Avaliação para medir o desempenho", "reajuste"); ?>
                                                              <?php
                                                            $wu4[''] = 'Selecione a Avaliação';
                                                            foreach ($users as $user) {
                                                                $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                                            }
                                                          //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                                            echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $participantes_usuarios), 'id="slResponsavel"   class="form-control  select" data-placeholder="' . lang("Selecione o Responsavel") . ' "   style="width:100%;"   ');

                                                            ?>  
                                                              </div>
                                                            </div>   
                                                            
                                                            </div>
                                                                
                                                                
                                                            </div>
                                                            <!-- /INFORMAÇÕES DO AVALIAÇÃO  -->
                                                         
                                                        <div class="col-lg-12">   


                                                            <div class="col-md-12">
                                                                <div
                                                                    class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                                                    <a href="<?= site_url('Contratos'); ?>" class="btn btn-danger" id="reset"><?= lang('Sair') ?></a></div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <?php echo form_close(); ?>

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
