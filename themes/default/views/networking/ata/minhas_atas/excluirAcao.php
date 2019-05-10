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
        ?>    
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
?>

    
    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
              <font style="color: red;"> <?php echo 'Excluir Ação '.$idplano; ?> </font>
              <small><?php echo $nome_projeto; ?></small>
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Ata</li>
          </ol>

        </section>
        <br>
    </div>    
    </div>
    
    
    <section  class="content">
    
    <div class="row">    
    
    
    <div class="col-lg-12">
        <div class="box">
            <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <h2>  ATA  <?PHP ECHO $acao->idatas; ?>  </h2>
                                    </center>
                                </div>
                            </div>
                            
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                           echo form_open_multipart("welcome/deletePlanoForm", $attrib); 
                            echo form_hidden('id', $acao->idplanos);
                            echo form_hidden('idatas', $acao->idatas);
                            ?>
                            
                            <div class="col-md-12">
                                
                                <div class="col-md-6">
                                  <!-- DESCRIÇÃO DA AÇÃO -->  
                                  
                                  <div  class="form-group">
                                        <?= lang("Descrição ", "sldescricao"); ?><small>(O que ?)</small>
                                        <?php echo form_textarea('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao), 'class="form-control  input-tip "  readonly  style="height: 120px;" id="sldescricao" required="true" '); ?>
                                  </div>
                              
                                    <!-- ONDE -->  
                                  <div  class="form-group">
                                        <?= lang("Local ", "onde"); ?><small>(Onde ?)</small>
                                        <?php echo form_textarea('onde', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->onde), 'class="form-control" disabled="true"  style="height: 120px;" id="onde"  '); ?>
                                  </div>
                                    <!-- PRAZO de -->
                                  <div class="form-group">
                                            <?= lang("Data Início", "sldate"); ?><small>(Quando ?)</small>
                                            <input class="form-control input-tip " value="<?php echo $acao->data_entrega_demanda; ?>" required="true" disabled="true" name="dateEntrega" type="date">
                                        </div>
                                    <!-- PRAZO ATE -->
                                  <div class="form-group">
                                            <?= lang("Data Término ", "sldateEntregaDemanda"); ?>
                                            <input class="form-control input-tip " value="<?php echo $acao->data_termino; ?>" required="true" disabled="true" name="data_termino" type="date">
                                        </div>
                                    <!-- HORAS -->
                                  <div class="form-group">
                                        <?= lang("Horas Previstas", "horas"); ?>
                                       <input class="form-control input-tip" placeholder="Horas Previstas" value="<?php echo $acao->horas_previstas; ?>" disabled="true"  name="horas_previstas" type="number">
                                       </div>
                                   <!-- QUEM -->
                                  <div class="form-group">
                                        <?= lang("Responsável ", "slResponsavel"); ?><small>(Quem ?)</small>
                                        <?php
                                        //$wu4[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                        }
                                        $id_usu_setor =  $this->atas_model->getUserSetorByUsuarioAndSetor($acao->responsavel, $acao->setor);
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
                                        <?php echo form_textarea('porque', (isset($_POST['porque']) ? $_POST['porque'] : $acao->porque), 'class="form-control" disabled="true"  style="height: 120px;" id="porque"  '); ?>
                                    </div>
                                    <!-- COMO -->  
                                    <div class="form-group">
                                        <?= lang("Detalhes", "como"); ?><small>(Como? )</small>
                                        <?php echo form_textarea('como', (isset($_POST['como']) ? $_POST['como'] : $acao->como), 'class="form-control" disabled="true"  style="height: 120px;" id="como"  '); ?>
                                    </div>
                                    <!-- VALOR -->  
                                    <div class="form-group">
                                        <?= lang("Custo", "custo"); ?><small> (Descrição do Custo? )</small>
                                        <?php echo form_textarea('custo', (isset($_POST['custo']) ? $_POST['custo'] : $acao->custo), 'class="form-control" disabled="true"  style="height: 120px;" id="custo"  '); ?>
                                    </div>
                                    <?= lang("Valor", "custo"); ?><small> (Valor do Custo? )</small>
                                    <input class="form-control" placeholder="Valor do Custo para esta ação" onkeypress="mascara(this, mvalor);" disabled="true" value="<?php echo str_replace('.', ',', $acao->valor_custo); ?>"  name="valor_custo" type="text">
                                    
                                    <!-- DOCUMENTO -->  
                                    <div class="form-group">
                                <?= lang("Anexar Documento", "document") ?> 
                                    <?php if($ata->anexo){ ?>
                                <div class="btn-group">
                            <a href="<?= site_url('assets/uploads/atas/' . $ata->anexo_ata) ?>" class="tip btn btn-file" disabled="true" title="<?= lang('Arquivo em Anexo') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                            </a>
                                    <?php /* <input type="checkbox"><button type="button" class="btn btn-danger" id="reset"><?= lang('REMOVER') ?> */ ?>
                        </div>
                               
                                <?php } ?>
                               <?php if($statusAta != 1){ ?>
                               <input id="document" disabled="true" type="file" data-browse-label="<?= lang('browse'); ?>" disabled="true" name="document" value="<?php echo $projeto->anexo; ?>" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                                <?php } ?>  
                               
                            </div>
                                </div>
                           
                            </div>    
                            

                            <center>

                                <div class="col-md-12">
                                      <?php echo form_submit('add_item', lang("Deletar"), 'id="add_item" class="btn btn-danger" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                        <a  class="btn btn-warning"  href="<?= site_url('Atas/plano_acao/'.$acao->idatas); ?>"><?= lang('Cancelar') ?></a>
                             
                                </div>
                                 </center>
                             <?php echo form_close(); ?>
                       
       
                <br><br><br>
                </div>
            
            
            <div class="row">
                <br>
                <BR>
            </div>
            
        </div>        
    </div>
        
   
    
    
        
    </div>
   
    
        
     
    
    
    <!-- /.col-lg-12 -->
        
    </section>    
</div>
