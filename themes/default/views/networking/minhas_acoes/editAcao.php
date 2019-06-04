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
       // $empresa = $this->session->userdata('empresa');
        //$users = $this->site->geUserByID($acao->responsavel);     
        // echo 'Plano : '.$empresa; exit;
        if($acao->idplano){
            $plano_acao = $this->atas_model->getPlanoAcaoByID($acao->idplano);
            $referencia = 'PLANO DE AÇÃO : '.$plano_acao->sequencial;
        }else if($acao->idatas){
            $ata = $this->atas_model->getAtaByID($acao->idatas);
            $referencia = 'ATA : '.$ata->sequencia;
        }
        
        if($acao->projeto){
            $projeto_acao = $this->projetos_model->getProjetoByID($acao->projeto);
            $nome_projeto = $projeto_acao->projeto;
        }
        
        ?>    


<?php
    if($acao->status == "PENDENTE"){
        
         if ($dataHoje <= $data_prazo) {
            $novo_status = 'PENDENTE';
            $desc_tipo = "orange-active";
        }
        
          if ($dataHoje > $data_prazo) {
               $novo_status = 'ATRASADO';
                $desc_tipo = "red-active";

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
         $desc_tipo = "green-active";
    }else if($acao->status == "AGUARDANDO VALIDAÇÃO"){
         $novo_status = 'AGUARDANDO VALIDAÇÃO';
         $desc_tipo = "blue-gradient";
    }else if($acao->status == "CANCELADO"){
        $novo_status = 'CANCELADO';
         $desc_tipo = "black-active";
    }
?>
    <script>
        function exibe_andamento(plano) {
              $.ajax({
                type: "POST",
                url: "themes/default/views/networking/minhas_acoes/exibe_andamento.php?tipo=0&id_plano="+plano+"&atualizou=1",
                
                success: function(data) {
                  $('#exibe_andamento').html(data);
                 
                }

              });

            }
            
            
    </script>    
            
    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
             <h3>
            <font class="label bg-navy-active" style="font-size: 12px; font-weight: bold"><?php echo 'Ação '.$acao->sequencial; ?></font> 
                 
             <?php if($acao->projeto){ ?>    
             <font class="label bg-green-active" style="font-size: 12px; font-weight: bold"><?php echo 'Projeto : '. $nome_projeto;  ?>  </font> 
            <?php  } ?>
            <?php
             if($acao->idplano){
             ?>    
             <font class="label bg-blue" style="font-size: 12px; font-weight: bold"><?php echo $referencia;  ?>  </font> 
            <?php
            }else if($acao->idatas){
            ?>    
             <font class="label bg-blue" style="font-size: 12px; font-weight: bold"><?php echo $referencia;  ?>  </font> 
            <?php
            }
             
            
              
              ?>
              <font class="label bg-<?php echo $desc_tipo; ?>" style="font-size: 12px; font-weight: bold"><?php echo $novo_status; ?> <?php  if ($novo_status == 'ATRASADO') { echo  '  (' . $qtde_dias . ' dias ) ';   } ?>  </font>   
             </h3>
        <h2><?php echo $acao->descricao; ?></h2>
              <div id="exibe_andamento">
                  <script>exibe_andamento(<?php echo $idplano; ?>)</script>
              </div>
         
          <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Minhas Ações</li>
          </ol>

        </section>
        <br>
    </div> 
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

    <style>
#div1
{
border: 1px solid;
border-color: #000000;
width:100%;
height:100px;
background-color: #f4f4f4;

 
}
 
</style>

    <div class="row">  
        <div class="col-lg-12">
            <div class="col-lg-12">
                <?php if ($acao->status == 'PENDENTE') { ?>
                <a href="<?= site_url('welcome/retorno_new/'.$idplano); ?>" data-toggle="modal" data-target="#myModal" class="btn bg-olive-active"><i class="fa fa-check"></i> Retornar Ação</a>
                <?php } ?>
                <a href="<?= site_url('welcome/minhasAcoes/0/69'); ?>"  class="btn btn-primary"><i class="fa fa-backward"></i> Voltar</a>

            </div>
        </div>
    </div>    

    <section  class="content">
        <div class="col-lg-12">
            <div class="row">    
               
            <div class="box">
               <div  class="nav-tabs-custom">
                    <ul style="background-color: #ddd; " class="nav nav-tabs">
                        <li class="active"><a href="#cadastro"  data-toggle="tab"><b>Dados Cadastro <i class="fa fa-file-text-o"></i></b></a></li> 
                        <li><a href="#checklist" data-toggle="tab"><b>CheckList <i class="fa fa-check-square-o"></i></b></a></li>
                        <li><a href="#activity" data-toggle="tab"><b>Ações Vinculadas <i class="fa fa-link"></i></b></a></li>                         
                        <li><a href="#settings" data-toggle="tab"><b>Arquivos <i class="fa fa-folder-open"></i></b></a></li>
                        <li><a href="#historico" data-toggle="tab"><b>Comunicação <i class="fa fa-comments-o"></i></b></a></li>
                        <li><a href="#rat" data-toggle="tab"><b>Rat's <i class="fa fa-pencil"></i></b></a></li>
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
                               
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <?php
                                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                    echo form_open_multipart("project/manutencao_acao_pendente_form/$acao->idplanos", $attrib);
                                    echo form_hidden('id', $acao->idplanos);
                                    echo form_hidden('idatas', $acao->idplano);
                                    ?>
                                    
                                    <?php if ($acao->projeto) { ?>
                                    <div class="col-md-12">
                                        <!-- ITEM EVENTO -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <?= lang("Escopo", "slEvento"); ?>
                                                <?php
                                                $wue[''] = '';
                                                foreach ($eventos as $evento) {
                                                    $wue[$evento->id] = $evento->nome_fase . ' > ' . $evento->nome_evento . ' > ' . resume($evento->descricao, 100) . ' ( ' . exibirData($evento->dt_inicio) . ' - ' . exibirData($evento->dt_fim) . ' ) ';
                                                }
                                                echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : $acao->eventos), 'id="slEvento" disabled  class="form-control  select" data-placeholder="' . lang("Selecione o Item do Evento") . ' " required="required"  style="width:100%;"  ');
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
                                                <div id="div1">
                                                  <?php echo $acao->descricao; ?>
                                               </div>
                                                <?php // echo form_textarea('onde', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao), 'class="form-control"   style="height: 120px;" id="onde"  '); ?>
                                         
                                            </div>

                                            <!-- ONDE -->  
                                            <div  class="form-group">
                                                <?= lang("Local ", "onde"); ?><small>(Onde ?)</small>
                                                <div id="div1">
                                                    <?php echo $acao->onde; ?>
                                                </div>
                                                <?php // echo form_textarea('onde', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->onde), 'class="form-control"   style="height: 120px;" id="onde"  '); ?>
                                            </div>
                                            <!-- PRAZO de -->
                                            <div class="form-group">
                                                <?= lang("Data Início e Término", "sldate"); ?><small>(Quando ?)</small>
                                                <i class="fa fa-info-circle" title="A Data de Início e Término, precisa estar dentro do período de datas do Item do Evento selecionado."></i>
                                                <input type="text" disabled="true" value="<?php echo exibirData($acao->data_entrega_demanda) ?> - <?php echo exibirData($acao->data_termino) ?>" title="O período de data da ação, não pode estar fora do período de datas do Item de evento selecionado." name="periodo_acao" class="form-control pull-right" id="reservation">

                                            </div>
                                            <!-- HORAS -->
                                            <div class="form-group">
                                                <?= lang("Horas Previstas", "horas"); ?>
                                                <input class="form-control input-tip" disabled="true"  placeholder="Horas Previstas" value="<?php echo $acao->horas_previstas; ?>"  name="horas_previstas" type="number">
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
                                                echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $id_usu_setor->id), 'id="slResponsavel" disabled="true" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"   ');
                                                ?>
                                            </div>

                                            <div class="form-group">
                                            <?= lang("Peso da Ação ", "peso"); ?><small>(Nível de Importancia/Impácto para o projeto)</small>
                                                <i class="fa fa-info-circle" title="O peso da ação é uma forma de classificar a ação."></i>
                                                <br>
                                                <input disabled="true" type="radio" class="form-control  " <?php if($acao->peso == 1){ ?> checked="true" <?php } ?> value="1" name="peso">  1
                                                <input disabled="true" type="radio" class="form-control  " <?php if($acao->peso == 2){ ?> checked="true" <?php } ?> value="2" name="peso">  2
                                                <input disabled="true" type="radio" class="form-control  " <?php if($acao->peso == 3){ ?> checked="true" <?php } ?> value="3" name="peso">  3
                                                <input disabled="true" type="radio" class="form-control  " <?php if($acao->peso == 4){ ?> checked="true" <?php } ?> value="4" name="peso">  4
                                                <input disabled="true" type="radio" class="form-control  " <?php if($acao->peso == 5){ ?> checked="true" <?php } ?> value="5" name="peso">  5
                                               
                                               
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <!-- PORQUE -->  
                                            <div class="form-group">
                                            <?= lang("Motivo, Justificativa", "porque"); ?><small>(Por Quê? )</small>
                                            <div id="div1">
                                                <?php echo $acao->porque; ?>
                                            </div>
                                            <?php // echo form_textarea('porque', (isset($_POST['porque']) ? $_POST['porque'] : $acao->porque), 'class="form-control"   style="height: 120px;" id="porque"  '); ?>
                                            </div>
                                            <!-- COMO -->  
                                            <div class="form-group">
                                            <?= lang("Detalhes", "como"); ?><small>(Como? )</small>
                                            <div id="div1">
                                                <?php echo $acao->como; ?>
                                            </div>
                                            <?php // echo form_textarea('como', (isset($_POST['como']) ? $_POST['como'] : $acao->como), 'class="form-control"   style="height: 120px;" id="como"  '); ?>
                                            </div>
                                            <!-- VALOR -->  
                                            <div class="form-group">
                                            <?= lang("Custo", "custo"); ?><small> (Descrição do Custo? )</small>
                                            <div id="div1">
                                                <?php echo $acao->custo; ?>
                                            </div>
                                            <?php // echo form_textarea('custo', (isset($_POST['custo']) ? $_POST['custo'] : $acao->custo), 'class="form-control"   style="height: 120px;" id="custo"  '); ?>
                                            </div>
                                                <?= lang("Valor", "custo"); ?><small> (Valor do Custo? )</small>
                                                <input disabled="true" class="form-control" placeholder="Valor do Custo para esta ação" onkeypress="mascara(this, mvalor);" value="<?php echo str_replace('.', ',', $acao->valor_custo); ?>"  name="valor_custo" type="text">

                                            <!-- DOCUMENTO -->  

                                        </div>

                                    </div>    

                                    <center>

                                        <div class="col-md-12">
                                            <?php // echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                            <button  class="btn btn-danger" onclick="history.go(-1)"><?= lang('Sair') ?></button>

                                        </div>
                                    </center>
<?php echo form_close(); ?>
                                </div>       
                            </div>   
                        </div>
                        
                        <div class=" tab-pane" id="activity">
                            <div class="row">
                                <div class="col-md-12">
                                    
                                
                                <div class="portlet-body">
                                
                                <table id="chcklist_networking" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                    <thead>
                                        <tr>
                                            <th><font style="font-size: 10px;">ID</font></th>
                                            <th><font style="font-size: 10px;">AÇÃO</th>
                                            <th><font style="font-size: 10px;">PRAZO</th>
                                            <th><font style="font-size: 10px;">TIPO</th>
                                          
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $wu4[''] = '';
                                    $cont_planoContinuo = 1;
                                    foreach ($acoes_vinculadas as $user_ata) {
                                        $acao_detalhes = $this->atas_model->getPlanoByID($user_ata->id_vinculo);
                                        $tipo = $user_ata->tipo;
                                        if ($tipo == 'II') {
                                            $desc_tipo = 'INÍCIO - INÍCIO';
                                        } else if ($tipo == 'IF') {
                                            $desc_tipo = 'INICIO - FIM';
                                        }
                                        ?>   
                                            <tr class="odd gradeX">
                                                <td><font style="font-size: 10px;"><?php echo $user_ata->id_vinculo; ?></font></td>
                                                <td><font style="font-size: 10px;"><?php echo $acao_detalhes->descricao; ?></font></td>
                                                <td><font style="font-size: 10px;"><?php echo '( ' . exibirData($acao_detalhes->data_entrega_demanda) . ' - ' . exibirData($acao_detalhes->data_termino) . ' ) '; ?></font></td>
                                                <td><font style="font-size: 10px;"><?php echo $desc_tipo; ?></font></td>  

                                            </tr>
                                            <?php
                                        }
                                        ?>



 
                                    </tbody>
                                    
                                </table>
                                    
                                <br>
                                <center>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php // echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                            <button  class="btn btn-danger" onclick="history.go(-1)"><?= lang('Sair') ?></button>

                                        </div>
                                      </div>  
                                    </center>
                            </div>
                                </div>    
                                    
                            </div>    
                        </div>
                        
                        <div class="tab-pane" id="settings">
                            <div class="row">
                                <div class="col-md-12">
                                <h3>Lista de Arquivos da ação</h3>
                                <?php
                                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                echo form_open_multipart("project/manutencao_acao_arquivos", $attrib);
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
                                    <table id="networking_arquivos" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th><font style="font-size: 10px;">ID</font></th>
                                                <th><font style="font-size: 10px;">DESCRIÇÃO</th>
                                                <th><font style="font-size: 10px;">ARQUIVO</th>
                                                <th><font style="font-size: 10px;">DOWNLOAD</th>

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

                                                </tr>
        <?php
    }
    ?>




                                        </tbody>
                                    </table>
                                     <br>
                                    <center>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php // echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                                <button  class="btn btn-danger" onclick="history.go(-1)"><?= lang('Sair') ?></button>

                                            </div>
                                          </div>  
                                        </center>
                                </div> 
                                </div>
                            </div>    
                        </div>                   
                        
                        <div class="tab-pane" id="historico">
                             <div class="row">
                                 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
                                <script type="text/javascript">
                                function alterar_div() {
                                  $.ajax({
                                    type: "POST",
                                    url: "themes/default/views/project/cadastro_basico_modelo/exibe_historico_plano.php?tipo=2",
                                    data: {
                                      observacao: $('#observacao').val(),
                                      id_plano: $('#id_plano').val(),
                                      usuario: $('#usuario').val(),
                                      empresa: $('#empresa').val(),
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
                                $empresa = $this->session->userdata('empresa'); 
                                $total_observacoes =  $this->atas_model->getCountAllHistoricoAcoes($acao->idplanos);
                                $total_obs = $total_observacoes->total;
                                ?>
                                <input type="hidden" name="id_plano" id="id_plano" value="<?php echo $acao->idplanos; ?>" >
                                <input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>" >
                                <input type="hidden" name="empresa" id="empresa" value="<?php echo $empresa; ?>" >
                                <input type="hidden" name="total_obs" id="total_obs" value="<?php echo $total_obs; ?>" >
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <h2>  Histórico de Mensagens  </h2>
                                    </div>
                                </div>
                            
                                <div class="col-md-12" >
                                 <div class="col-md-12" style=" height: 200px; overflow:auto ; 
                                      border-width: 1px;
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
                                    <?= lang("Descrição ", "observacao"); ?>
                                        <input class="form-control" onFocus="this.value=''; " id="observacao" name="observacao" maxlength="500" required="true">                            
                                    </div>
                                </div>

                        
                        <?php  ?>
                        </div>
                            <center>
                                <div class="col-md-12">
                                    <button class="btn btn-primary" type="button" onclick="alterar_div(); document.getElementById('observacao').value = '';">Enviar</button>
                                    <button  class="btn btn-danger" onclick="history.go(-1)"><?= lang('Sair') ?></button>
                                </div>
                            </center>
                             <br>
                                
                            </div>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Data", "data_rat"); ?>
                                    <input type="date" name="data_rat" id="data_rat" class="form-control ">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                <?= lang("Hora Início", "hora_inicio"); ?>
                                  <input type="time" name="hora_inicio" id="hora_inicio" class="form-control ">
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Hora Fim", "hora_fim"); ?>
                                    <input  type="time" name="hora_fim" id="hora_fim" class="form-control ">
                                </div>
                            </div>   
                                
                            <div class="col-md-12">
                                <div class="form-group">
                            <?= lang("Descrição", "descricao_rat"); ?>
                                    <textarea name="descricao_rat" id="descricao_rat" class="form-control"></textarea>      
                            <?php // echo form_input('descricao_rat', (isset($_POST['descricao']) ? $_POST['descricao'] : ""), 'class="form-control input" maxlength="250"   id="descricao_rat"  '); ?>
                                </div>
                            </div>
                           
                            <center>
                                <div class="col-md-12">

                                <button class="btn btn-primary" type="button" onclick="alterar_rat(); document.getElementById('observacao').value = '';">Registrar Atividade</button>
                            
                                    <button  class="btn btn-danger" onclick="history.go(-1)"><?= lang('Sair') ?></button>
                      
                                </div>
                            </center>
                                <br>
                            <div class="col-lg-12">
                                 <div class="portlet portlet-default">
                                    <br><br>
                                         
                                     
                                    <div class="portlet-body">
                                        <div id="conteudo_rat">
                                            <div class="table-responsive  ">
                                            <table id="networking_rats" style="font-size: 12px;" class="table table-striped">
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
                        
                        <div class="tab-pane" id="checklist">
                            <script type="text/javascript">
                                function div_check_list() {
                                  $.ajax({
                                    type: "POST",
                                    url: "themes/default/views/project/cadastro_basico_modelo/exibe_check_list_acao.php?tipo=1",
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
                           
                                
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                        <?php echo form_input('descricao_checklist', (isset($_POST['descricao_checklist']) ? $_POST['descricao_checklist'] : ""), 'class="form-control input"  placeholder="Descreva a Atividade" maxlength="350"   id="descricao_checklist"  '); ?>
                                        </div>
                                     </div> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button class="btn btn-success" type="submit" onclick="div_check_list(); document.getElementById('descricao_checklist').value = '';"><i class="fa fa-plus"></i> Enviar</button>                                      
                                        </div>
                                     </div>    
                                </div>
                           
                               
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
                                        <!-- /.table-responsive   -->
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
        </div>       
    </section>    

    <script>
  $(function () {
    $('#networking_rats').DataTable()
    $('#networking_arquivos').DataTable()
    $('#chcklist_networking').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
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
            locale: { format: 'DD/MM/YYYY' } ,  language: 'pt-BR',
            minDate: '<?php echo exibirData($evento->dt_inicio) ?>',
            maxDate: '<?php echo exibirData($evento->dt_fim) ?>'
        
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

