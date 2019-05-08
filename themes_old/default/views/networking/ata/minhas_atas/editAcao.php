  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>
   
<?php
        $acao = $this->atas_model->getPlanoByID($idplano);
        $usuario = $this->session->userdata('user_id');
        //$users = $this->site->geUserByID($acao->responsavel);     
        if($acao->idplano){
            $referencia = 'PLANO DE AÇÃO :'.$acao->idplano;
        }else if($acao->idatas){
            $referencia = 'ATA :'.$acao->idatas;
        }
        
        ?>    


<?php
    if($acao->status == "PENDENTE"){
        
         if ($dataHoje <= $data_prazo) {
            $novo_status = 'PENDENTE';
            $desc_tipo = "primary";
        }
        
          if ($dataHoje > $data_prazo) {
               $novo_status = 'ATRASADO';
                $desc_tipo = "danger";

              $time_inicial = geraTimestamp($this->sma->hrld($dataHoje));
                $time_final = geraTimestamp($this->sma->hrld($data_prazo));
              //  $time_inicial = date('d/m/Y', strtotime($dataHoje));
              //  $time_final = date('d/m/Y', strtotime($data_prazo));
                // Calcula a diferença de segundos entre as duas datas:
                $diferenca = $time_final - $time_inicial; // 19522800 segundos
                // Calcula a diferença de dias
                $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

                if ($dias >= '-5') {
                    $qtde_dias = $dias;
                } else if (($dias < '-5') && ($dias >= '-10')) {
                    $qtde_dias = $dias;
                } else if ($dias < '-10') {
                    $qtde_dias = $dias;
                } else if ($dias < '-15') {
                    $qtde_dias = '+15';
                }
                $qtde_dias = str_replace('-', '', $qtde_dias);
            }
        
       
    }else if($acao->status == "CONCLUÍDO"){
        $novo_status = 'CONCLUÍDO';
         $desc_tipo = "success";
    }else if($acao->status == "AGUARDANDO VALIDAÇÃO"){
         $novo_status = 'AGUARDANDO VALIDAÇÃO';
         $desc_tipo = "warning";
    }else if($acao->status == "CANCELADO"){
        $novo_status = 'CANCELADO';
         $desc_tipo = "default";
    }
?>
    <script>
        function exibe_andamento(plano) {
              $.ajax({
                type: "POST",
                url: "themes/default/views/project/cadastro_basico_modelo/plano_acao/exibe_andamento.php?tipo=0&id_plano="+plano+"&atualizou=1",
                
                success: function(data) {
                  $('#exibe_andamento').html(data);
                }
              });
            }
    </script>  

            
    <div class="col-lg-12">
    <div class="box">
        <section class="content-header">
             <h1>
            <?php echo 'Editar Ação '.$acao->sequencial; ?>
              <font class="label label-<?php echo $desc_tipo; ?>" style="font-size: 12px; font-weight: bold"><?php echo $novo_status; ?> <?php  if ($novo_status == 'ATRASADO') { echo  '  (' . $qtde_dias . ' dias ) ';   } ?>  </font>   
             </h1>
              <?php echo $acao->descricao; ?>
              <div id="exibe_andamento">
                  <script>exibe_andamento(<?php echo $idplano; ?>)</script>
              </div>
              
                  
         
          <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Plano de Ação</li>
          </ol>

        </section>
    
        <br>
    </div> 
        <button  class="btn btn-default" onclick="history.go(-2)"><?= lang('Voltar') ?></button>
    </div>
     
    <div class="row">  
    <div class="col-lg-12">
        <div class="col-lg-12">
            <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    </div>
       </div> 
    </div>
    
    <section  class="content">
    <div class="row">    
    
    <div class="col-lg-12">
        <div class="box">
                               
                <div  class="nav-tabs-custom">
                    <ul style="background-color: #d2d6de; " class="nav nav-tabs">
                        <li class="active"><a href="#cadastro"  data-toggle="tab"><b>Dados Cadastro <i class="fa fa-file-text-o"></i></b></a></li>  
                        <li><a href="#checklist" data-toggle="tab"><b>CheckList <i class="fa fa-check-square-o"></i></b></a></li>
                        <li><a href="#settings" data-toggle="tab"><b>Arquivos <i class="fa fa-folder-open"></i></b></a></li>
                        <li><a href="#historico" data-toggle="tab"><b>Histórico <i class="fa fa-comments-o"></i></b></a></li>
                        <li><a href="#rat" data-toggle="tab"><b>Rat's <i class="fa fa-pencil"></i></b></a></li>
                        <li><a href="#log" data-toggle="tab"><b>Log <i class="fa fa-search"></i></b></a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="cadastro">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <center>
                                            <h2>  Editar Cadastro  </h2>
                                        </center>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <?php
                                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                    echo form_open_multipart("welcome/manutencao_acao_pendente_form/$acao->idplanos", $attrib);
                                    echo form_hidden('id', $acao->idplanos);
                                    echo form_hidden('idatas', $acao->idplano);
                                    echo form_hidden('tipo', $acao->tipo_origem);
                                    ?>
                                   
                                    <?php if($acao->tipo_origem == 2){ ?>
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <?= lang("Categoria ", "categoria"); ?><small>(Categoria no Plano de Ação)</small>
                                                <?php
                                                $categorias = $this->networking_model->getAllCategoriaPlanoAcaoByPlano($acao->idplano);
                                                foreach ($categorias as $categoria) {
                                                    $wu_cat[$categoria->id] = $categoria->descricao;
                                                }
                                              //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                                echo form_dropdown('categoria', $wu_cat, (isset($_POST['categoria']) ? $_POST['categoria'] : $acao->categoria_plano), 'id="categoria"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"   ');

                                                ?>
                                            </div>
                                        </div>    
                                    </div>  
                                    <?php } ?>
                                    
                                    <div class="col-md-12">

                                        <div class="col-md-6">
                                            <!-- DESCRIÇÃO DA AÇÃO -->  

                                            <div  class="form-group">
                                                <?= lang("Descrição ", "sldescricao"); ?><small>(O que ?)</small>
                                                <?php echo form_textarea('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao), 'class="form-control  input-tip "   style="height: 120px;" id="sldescricao" required="true" '); ?>
                                            </div>

                                            <!-- ONDE -->  
                                            <div  class="form-group">
                                                <?= lang("Local ", "onde"); ?><small>(Onde ?)</small>
                                                <?php echo form_textarea('onde', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->onde), 'class="form-control"   style="height: 120px;" id="onde"  '); ?>
                                            </div>
                                            <!-- PRAZO de -->
                                            <div class="form-group">
                                                <?= lang("Data Início", "sldate"); ?><small>(De Quando ?)</small>
                                                <input type="date" value="<?php echo $acao->data_entrega_demanda; ?>" title="O período de data da ação." name="data_inicio" class="form-control pull-right" id="reservation">
                                            </div>
                                            <!-- PRAZO ATE -->
                                            <div class="form-group">
                                                <?= lang("Data Término", "sldate"); ?><small>(Até Quando ?)</small>
                                                <input type="date" value="<?php echo $acao->data_termino; ?>" title="O período de data da ação." name="data_termino" class="form-control pull-right" id="reservation">
                                            </div>
                                            
                        
                                            <!-- HORAS -->
                                            <div class="form-group">
                                                <?= lang("Horas Previstas", "horas"); ?>
                                                <input class="form-control input-tip"  placeholder="Horas Previstas" value="<?php if($acao->horas_previstas > 0){ echo $acao->horas_previstas; } ?>"  name="horas_previstas" type="number">
                                            </div>
                                            <!-- QUEM -->
                                            <div class="form-group">
                                                <?= lang("Responsável ", "slResponsavel"); ?><small>(Quem ?)</small>
                                                <?php
                                                //$wu4[''] = '';
                                                foreach ($users as $user) {
                                                    $wu4[$user->id] = $user->nome . ' ' . $user->last . ' - ' . $user->setor;
                                                }
                                                $id_usu_setor = $this->atas_model->getUserSetorByUsuarioAndSetor($acao->responsavel, $acao->setor);
                                                //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                                echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $id_usu_setor->id), 'id="slResponsavel" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"   ');
                                                ?>
                                            </div>

                                            <div class="form-group">
                                            <?= lang("Peso da Ação ", "peso"); ?><small>(Nível de Importancia/Impácto para o projeto)</small>
                                                <i class="fa fa-info-circle" title="O peso da ação é uma forma de classificar a ação."></i>
                                                <br>
                                                <input type="radio" class="form-control  " <?php if($acao->peso == 1){ ?> checked="true" <?php } ?> value="1" name="peso">  1
                                                <input type="radio" class="form-control  " <?php if($acao->peso == 2){ ?> checked="true" <?php } ?> value="2" name="peso">  2
                                                <input type="radio" class="form-control  " <?php if($acao->peso == 3){ ?> checked="true" <?php } ?> value="3" name="peso">  3
                                                <input type="radio" class="form-control  " <?php if($acao->peso == 4){ ?> checked="true" <?php } ?> value="4" name="peso">  4
                                                <input type="radio" class="form-control  " <?php if($acao->peso == 5){ ?> checked="true" <?php } ?> value="5" name="peso">  5
                                               
                                               
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <!-- PORQUE -->  
                                            <div class="form-group">
<?= lang("Motivo, Justificativa", "porque"); ?><small>(Por Quê? )</small>
<?php echo form_textarea('porque', (isset($_POST['porque']) ? $_POST['porque'] : $acao->porque), 'class="form-control"   style="height: 120px;" id="porque"  '); ?>
                                            </div>
                                            <!-- COMO -->  
                                            <div class="form-group">
<?= lang("Detalhes", "como"); ?><small>(Como? )</small>
<?php echo form_textarea('como', (isset($_POST['como']) ? $_POST['como'] : $acao->como), 'class="form-control"   style="height: 120px;" id="como"  '); ?>
                                            </div>
                                            <!-- VALOR -->  
                                            <div class="form-group">
<?= lang("Custo", "custo"); ?><small> (Descrição do Custo? )</small>
<?php echo form_textarea('custo', (isset($_POST['custo']) ? $_POST['custo'] : $acao->custo), 'class="form-control"   style="height: 120px;" id="custo"  '); ?>
                                            </div>
                                                <?= lang("Valor", "custo"); ?><small> (Valor do Custo? )</small>
                                            <input class="form-control" placeholder="Valor do Custo para esta ação" onkeypress="mascara(this, mvalor);" value="<?php echo str_replace('.', ',', $acao->valor_custo); ?>"  name="valor_custo" type="text">

                                            <!-- DOCUMENTO -->  

                                        </div>

                                    </div>    

                                    <center>

                                        <div class="col-md-12">
                                            <?php echo form_submit('add_item', lang("Atualizar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                            <button  class="btn btn-danger" onclick="history.go(-2)"><?= lang('Sair') ?></button>

                                        </div>
                                    </center>
                                <?php echo form_close(); ?>
                                </div>       
                            </div>   
                        </div>
                        <div class="tab-pane" id="checklist">
                            <script type="text/javascript">
                                function div_check_list() {
                                  $.ajax({
                                    type: "POST",
                                    url: "themes/default/views/project/cadastro_basico_modelo/plano_acao/exibe_check_list_acao.php?tipo=1",
                                    data: {
                                      descricao_checklist: $('#descricao_checklist').val(),
                                      id_plano: $('#id_plano').val(),
                                      usuario: $('#usuario').val()
                                    },
                                    success: function(data) {
                                      $('#div_checklist').html(data);

                                    }
                                    
                                  });
                                 document.getElementById("descricao_checklist").value = "";
                                }
                                </script>
                            <div class="row">
                                <center>
                                     <div class="portlet-heading">
                                        <div class="portlet-title">
                                             <h3>CheckList de Atividades da Ação</h3>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                 </center>
                                <div class="row">
                                <input type="hidden" name="id_plano" id="id_plano" value="<?php echo $acao->idplanos; ?>" >
                                <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" >
                                <input type="hidden" name="empresa" id="empresa" value="<?php echo $empresa; ?>" >
                           
                                
                               
                           
                               
                                <br>
                                
                            <div class="col-lg-12">
                                 <div class="portlet portlet-default">
                                    <br><br>
                                         
                                     
                                    <div class="portlet-body">
                                      <div class="col-md-12">
                                        <div id="div_checklist">
                                             <script>
                                                div_check_list();
                                            </script>  
                                        </div>
                                     </div>
                                        <!-- /.table-responsive -->
                                    </div>
                                   
                                  </div>    
                             </div>  
                            </div>    
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="settings">
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("welcome/manutencao_acao_arquivos_ata", $attrib);
                            echo form_hidden('id', $acao->idplanos);
                            echo form_hidden('idatas', $acao->idplano);
                            ?>
                            <div class="col-md-6">
                                <div class="form-group">
                            <?= lang("Descrição", "descricao"); ?>
                            <?php echo form_input('descricao_arquivo', (isset($_POST['descricao']) ? $_POST['descricao'] : ""), 'class="form-control input" maxlength="250"   id="descricao"  '); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang("Arquivo", "descricao"); ?>
                                    <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" value="<?php echo $projeto->anexo; ?>" data-show-upload="false"
                                           data-show-preview="false" class="form-control file">
                                </div>
                            </div>
                            <center>
                                <div class="col-md-12">
                                <?php echo form_submit('add_acao_arquivo', lang("Adcionar Arquivo"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"  " '); ?>
                                <?php echo form_close(); ?>
                                </div>
                            </center>
                            <div class="portlet-body">
                                <table id="example2" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                    <thead>
                                        <tr>
                                            <th><font style="font-size: 10px;">ID</font></th>
                                            <th><font style="font-size: 10px;">DESCRIÇÃO</th>
                                            <th><font style="font-size: 10px;">ARQUIVO</th>
                                            <th><font style="font-size: 10px;">DOWNLOAD</th>
                                            <th><font style="font-size: 10px;">OPÇÃO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $wu4[''] = '';
                                    $cont_arquivo = 1;
                                    foreach ($acoes_arquivos as $arquivo) {
                                        ?>   
                                            <tr class="odd gradeX">
                                                <td><font style="font-size: 10px;"><?php echo $cont_arquivo++; ?></font></td>
                                                <td><font style="font-size: 10px;"><?php echo $arquivo->descricao; ?></font></td>
                                                <td><font style="font-size: 10px;"><?php echo $arquivo->anexo; ?></font></td>
                                                <td><font style="font-size: 10px;"><a target="_blank" href="assets/uploads/planos/arquivos/<?php echo $arquivo->anexo; ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>"><i class="fa fa-download"></i></a></font></td>  
                                                <td class="center">
                                                    <a style="color: red;" title="Apagar Arquivo" href="<?= site_url('project/remove_arquivo_acao/' . $arquivo->id); ?>"><font style="font-size: 10px;"><i class="fa fa-trash-o"></i> Excluir</font></a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>

                            </div> 
                        </div>                   
                        
                        <div class="tab-pane" id="historico">
                             <div class="row">
                                 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
                                <script type="text/javascript">
                                function alterar_div() {
                                  $.ajax({
                                    type: "POST",
                                    url: "themes/default/views/project/cadastro_basico_modelo/exibe_historico_plano.php?tipo=1",
                                    data: {
                                      observacao: $('#observacao').val(),
                                      id_plano: $('#id_plano').val(),
                                      usuario: $('#usuario').val(),
                                      total_obs: $('#total_obs').val()
                                    },
                                    success: function(data) {
                                      $('#conteudo').html(data);

                                    }
                                    
                                  });
                                 document.getElementById("observacao").value = "";
                                }
                                </script>
                                <?php 
                                $usuario = $this->session->userdata('user_id'); 
                                $total_observacoes =  $this->atas_model->getCountAllHistoricoAcoes($acao->idplanos);
                                $total_obs = $total_observacoes->total;
                                ?>
                                <input type="hidden" name="id_plano" id="id_plano" value="<?php echo $acao->idplanos; ?>" >
                                <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" >
                                <input type="hidden" name="total_obs" id="total_obs" value="<?php echo $total_obs; ?>" >
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <h2>  Histórico de Mensagens  </h2>
                                    </div>
                                </div>
                            
                                <div class="col-md-12" >
                                 <div class="col-md-12" style=" height: 200px; overflow:auto ; 
                                      border-width: 2px;
                                  border-style: dashed;
                                  border-color: #000000;  " >
                                    <div class="form-group">
                                        <div id="conteudo">
                                            <br>
                                             <?php
                                                    $observacoes       =  $this->atas_model->getAllHistoricoAcoes($acao->idplanos);
                                                    
                                                    //echo $total_obs;
                                                    $cont = $total_obs ;
                                                     foreach ($observacoes as $observacao) {

                                                         if($observacao->observacao){
                                                        $tipo = $observacao->tipo;    
                                                         if($tipo == 1){
                                                             $label = "primary";
                                                         }else if($tipo == 2){
                                                             $label = "warning";
                                                         }
                                                    ?>
                                                    <div class="col-md-12"  >
                                                        <div class="form-group">  
                                                        <font  class="label label-<?php echo $label; ?>" style="font-size: 12px; font-weight: bold"><?php echo $cont--.' - '. $observacao->first_name; ?>  </font>
                                                        <font class="label label-default"  style="font-size: 8px; font-style: italic">( <?php echo date('d/m/Y H:i:s', strtotime( $observacao->data_envio)); ?> )</font>
                                                        <font  style="font-size: 12px;"><?php echo ' : '.  strip_tags($observacao->observacao); ?> </font> 
                                                        </div>
                                                    </div>
                                                      <?php if($observacao->anexo != null){  ?> 
                                                      <font style="font-size: 12px;"><a href="<?= base_url() ?>assets/uploads/historico_acoes/<?php echo $observacao->anexo; ?>" target="_blank"><i class="fa fa-chain"></i>Ver Anexo</a></font><?php } ?>
                                                 
                                                 <?php }
                                                     }
                                                   ?>   
                                        </div>              
                                    </div>
                                </div>
                                </div>    
                                <br><br>
                            <div class="col-md-12">
                               <div class="col-md-12">
                                    <div class="form-group">
                                    <?= lang("Observação ", "observacao"); ?>
                                        <input class="form-control" onFocus="this.value=''; " id="observacao" name="observacao" maxlength="500" required="true">                            
                                    </div>
                                </div>

                        
                        <?php  ?>
                        </div>
                            <center>
                                <div class="col-md-12">
                                    <button class="btn btn-primary" type="button" onclick="alterar_div(); document.getElementById('observacao').value = '';">Confirmar Envio  </button>
                               
                                </div>
                            </center>
                            
                            </div>
                            <br>
                        </div>
                       
                        <script>
                            function alterar_rat() {
                                  $.ajax({
                                    type: "POST",
                                    url: "themes/default/views/project/cadastro_basico_modelo/exibe_rat_plano.php?tipo=1",
                                    data: {
                                      data_rat: $('#data_rat').val(),
                                      hora_inicio: $('#hora_inicio').val(),
                                      hora_fim: $('#hora_fim').val(),
                                      id_plano: $('#id_plano').val(),
                                      descricao_rat: $('#descricao_rat').val(),
                                      empresa: $('#empresa').val(),
                                      usuario: $('#usuario').val()
                                    },
                                    success: function(data) {
                                      $('#conteudo_rat').html(data);
                                      document.getElementById("data_rat").value = "";
                                     document.getElementById("hora_inicio").value = "";
                                     document.getElementById("hora_fim").value = "";
                                     document.getElementById("descricao_rat").value = "";
                                    }
                                    
                                  });
                                 
                                }
                        </script>
                        <script>
                            function deletar_rat(id_registro) {

                                  $.ajax({
                                    type: "POST",
                                    url: "themes/default/views/project/cadastro_basico_modelo/exibe_rat_plano.php?tipo=2&id_rat="+id_registro,
                                    data: {

                                      id_plano: $('#id_plano').val()

                                    },
                                    success: function(data) {
                                      $('#conteudo_rat').html(data);

                                    }

                                  });

                                }
                        </script>
                        <?php
                        $usuario = $this->session->userdata('user_id'); 
                        $empresa = $this->session->userdata('empresa'); 
                        ?>
                        <div class="tab-pane" id="rat">
                            <center>
                                     <div class="portlet-heading">
                                    <div class="portlet-title">
                                         <h3>Registro de Atividades da Ação</h3>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </center>
                            <div class="row">
                                <input type="hidden" name="id_plano" id="id_plano" value="<?php echo $acao->idplanos; ?>" >
                                <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" >
                                <input type="hidden" name="empresa" id="empresa" value="<?php echo $empresa; ?>" >
                            
                           
                            
                                <br>
                            <div class="col-lg-12">
                                 <div class="portlet portlet-default">
                                    <br><br>
                                         
                                     
                                    <div class="portlet-body">
                                        <div id="conteudo_rat">
                                            <div class="table-responsive  ">
                                            <table style="font-size: 12px;" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">ID</th>
                                                        <th style="width: 10%;">DATA</th>
                                                        <th style="width: 10%;">INÍCIO</th>
                                                        <th style="width: 10%;">TÉRMINO</th>
                                                        <th style="width: 10%;">TEMPO</th>

                                                        <th style="width: 45%;">DESCRICAO</th>
                                                        <th style="width: 10%;">EXCLUIR</th>
                                                    </tr>
                                                </thead>
                                               
                                                <tbody>
                                               
                                                     <?php
                                                        $wu4[''] = '';
                                                        $cont_planoContinuo = 1;
                                                        $total_tempo = '00:00:00';
                                                        $cont_array = 0;
                                                        $minhas_rats = $this->atas_model->getAllRatsAcoes($acao->idplanos);
                                                        foreach ($minhas_rats as $rat) {
                                                            $hora_inicio = $rat->hora_inicio;
                                                            $hora_fim = $rat->hora_fim;
                                                             $tempo = $rat->tempo;

                                                            $times[] = $tempo;
                                                        ?>   
                                                            <tr >
                                                            <td style="width: 5%;" class="center"><?php echo $cont_planoContinuo++; ?></td>
                                                            <td style="width: 10%;" class="center"><?php echo date("d/m/Y", strtotime($rat->data_rat)); ?></td>
                                                            <td style="width: 10%;" class="center"><?php echo $hora_inicio; ?></td>
                                                            <td style="width: 10%;" class="center"><?php echo $hora_fim; ?></td>
                                                            <td style="width: 10%;" class="center"><?php echo $tempo; ?></td>

                                                            <td style="width: 45%;" class="center"><?php echo $rat->descricao; ?></td>
                                                            <td style="width: 10%;" class="center"><button  class="btn btn-danger" onclick="deletar_rat(<?php echo $rat->id; ?>);"><i class="fa fa-trash"></i></button> </td>
     

                                                            </tr>
                                                        <?php

                                                        }


                                                      

                                                        ?>


                                                         
                                                            
                                                </tbody>
                                               
                                            </table>
                                        </div>
                                        </div>
                                        
                                        <!-- /.table-responsive -->
                                    </div>
                                   
                                  </div>    
                             </div>  
                            </div>    
                        </div>
                        
                        <div class="tab-pane" id="log">
                            <div class="row">
                            <div class="col-lg-12">
                                 <div class="portlet portlet-default">
                                     <div class="portlet-heading">
                                        <div class="portlet-title">
                                             <h4>LOG DA AÇÃO </h4>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="portlet-body">
                                        <div class="table-responsive  ">
                                            <table style=" font-size: 12px;" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">ID</th>
                                                        <th style="width: 15%;">QUANDO</th>
                                                        <th style="width: 20%;">QUEM</th>
                                                        <th style="width: 20%;">O QUE</th>
                                                        <th style="width: 20%;">ANTES</th>
                                                        <th style="width: 20%;">DEPOIS</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <?php
                                                        $wu4[''] = '';
                                                        $meus_logs = $this->atas_model->getLogByAcao($acao->idplanos);
                                                        foreach ($meus_logs as $log) {
                                                           $dados_user = $this->site->getUser($log->usuario);
                                                            $nome = $dados_user->first_name;
                                                        ?>   
                                                            <tr  class="odd gradeX">
                                                            <td style="width: 5%;" class="center"><?php echo $cont_planoContinuo++; ?></td>
                                                            <td style="width: 15%;" class="center"><?php echo date('d/m/Y H:i:s', strtotime($log->data_registro)); ?></td>
                                                            <td style="width: 20%;" class="center"><?php echo $nome; ?></td>
                                                            <td style="width: 20%;" class="center"><?php echo $log->descricao; ?></td>
                                                            <td style="width: 20%;" class="center"><?php echo $log->antes; ?></td>
                                                            <td style="width: 20%;" class="center"><?php echo $log->depois; ?></td>

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
                </div>
            
        </div>        
    </div>
        
    </div>
   
        
    </section>    


<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    //$('#reservation').daterangepicker();
    
     $(function() { $("#reservation").daterangepicker({
            locale: { format: 'DD/MM/YYYY' } ,  language: 'pt-BR'
        
        }); });
     

    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'DD/MM/YYYY' })
    
    
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',                
    language: 'pt-BR'
    })
    

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>

