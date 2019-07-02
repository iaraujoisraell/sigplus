  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>

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
<script type="text/javascript">
    function optionCheck(){
        var option = document.getElementById("options").value;
        if(option == "REUNIÃO CONTÍNUA"){
         //   document.getElementById("hiddenDiv").style.visibility ="visible";
            document.getElementById("hiddenDiv").style.display = "block";
        }else{
            document.getElementById("hiddenDiv").style.display = "none";
        }


    }
</script>
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
?>
 
    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Ata '.$ata->sequencia; ?>
              
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-home"></i> Minhas Atas</a></li>
            <li class="active">Ata</li>
          </ol>

        </section>
        <br>
    </div>    
    </div>
    <div class="row">  
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
    
    <section  class="content">
    <div class="row">    
    
    <div class="col-lg-12">
        <div class="box">
        <div class="modal-header">
            
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Ata '); ?></h4>
        </div>
            
            
            
            
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("welcome/edit", $attrib); 
            echo form_hidden('id_cadastro', '1'); 
            echo form_hidden('menu_id', $menu_id); 
            echo form_hidden('tabela_id', $tabela_id); 
            echo form_hidden('tabela_nome', $tabela_nome);
            echo form_hidden('funcao', $funcao);
            echo form_hidden('projeto', $id_projeto);
            echo form_hidden('id', $id);
            $statusAta = $ata->status;
            
        ?>
            
            
        <div class="row">
                <div class="col-md-12">
                    <div class="col-lg-12">
                           
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Data", "dateAta"); ?>
                                <?php if($statusAta == 1){ ?>
                                <input name="dateAta" value="<?php echo $ata->data_ata; ?>" required="true" disabled="true" class="form-control" type="date" >
                                 <?php }else{ ?>
                                <input name="dateAta" value="<?php echo $ata->data_ata; ?>" required="true" class="form-control" type="date" >
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("hora Início ", "data_termino"); ?>
                                 <?php if($statusAta == 1){ ?>
                                <input name="hora_inicio" value="<?php echo $ata->hora_inicio; ?>" disabled="true" required="true" class="form-control" type="time" >
                                <?php }else{ ?>
                                <input name="hora_inicio" value="<?php echo $ata->hora_inicio; ?>" required="true" class="form-control" type="time" >
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("hora Fim ", "data_termino"); ?>
                                <?php if($statusAta == 1){ ?>
                                <input name="hora_fim" required="true" value="<?php echo $ata->hora_termino; ?>" disabled="true" class="form-control" type="time" >
                                 <?php }else{ ?>
                                <input name="hora_fim" required="true" value="<?php echo $ata->hora_termino; ?>" class="form-control" type="time" >
                                <?php } ?>
                            </div>
                        </div>    
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Local ", "sllocal"); ?>
                                 <?php if($statusAta == 1){ ?>
                                <?php echo form_input('local', (isset($_POST['local']) ? $_POST['local'] : $ata->local), 'maxlength="200" disabled class="form-control input-tip" required="required" id="sllocal"'); ?>
                                <?php }else{ ?>
                                   <?php echo form_input('local', (isset($_POST['local']) ? $_POST['local'] : $ata->local), 'maxlength="200" class="form-control input-tip" required="required" onchange="optionCheck()" id="sllocal"'); ?>
                                      <?php } ?>
                            </div>
                        </div>
                        
                        
                    </div>    
                    <div class="col-lg-12">
                        <div class="col-sm-3">
                            <div class="form-group">
                                 <?= lang("Tipo", "tipo"); ?> <i class="fa fa-info-circle" title="A Ata do tipo REUNIÃO, é a forma padrão de um cadastro de ATA. Podendo regisrar quantas atas de reuniões for preciso. O tipo REUNIÃO CONTÍNUA, é para quando você quiser gerenciar reuniões de um assunto específico e determinado, relacionado a um item de evento específico do escopo. Por Exemplo: Se a reunião é do tipo PLANEJAMENTO, e haverá outras reuniões sobre esse mesmo assunto, pode vincular ao item do escopo que se refere ao planejamento, assim poderá acompanhar as ações definidas em reuniões e a este item."></i>
                                 <?php $pst[''] = '';
                                  $pst['REUNIÃO'] = lang('REUNIÃO');
                                  $pst['OUTRO'] = lang('OUTRO');
                                 
                                  
                                  ?>
                                 <?php if($statusAta == 1){ 
                                      echo form_dropdown('tipo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $ata->tipo), 'id="tipo" disabled class="form-control "  data-placeholder="' . lang("select") . ' ' . lang("o tipo de Ata") . '" required="required"   style="width:100%;" ');
                                 }else{
                                          echo form_dropdown('tipo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $ata->tipo), 'id="tipo"  class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Ata") . '" required="required"   style="width:100%;" ');
                                  }
                                  ?> 
                                 
                                 
                            </div>
                        </div>
                        
                    <style>
                        textarea.form-control {
                          height: 100%;
                        }
                        textarea { 
                           min-height: 100%;
                        }
                    </style>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Responsável", "slelaboracao"); ?><i class="fa fa-info-circle" title="É a pessoa Líder, quem convocou ou quem irá conduzir a Ata."></i>
                                <?php if($statusAta == 1){ ?>
                                   <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $ata->responsavel_elaboracao), 'maxlength="200" disabled class="form-control input-tip" required="required" id="slelaboracao"'); ?>   
                                 <?php }else{ ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $ata->responsavel_elaboracao), 'maxlength="200" class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                                <?php } ?>
                            </div>
                        </div> 
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Anexo", "document") ?> 
                                    <?php if($ata->anexo){ ?>
                                <div class="btn-group">
                            <a href="<?= site_url('assets/uploads/atas/' . $ata->anexo_ata) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                            </a>
                                    <?php /* <input type="checkbox"><button type="button" class="btn btn-danger" id="reset"><?= lang('REMOVER') ?> */ ?>
                        </div>
                               
                                <?php } ?>
                               <?php if($statusAta != 1){ ?>
                               <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" value="<?php echo $ata->anexo_ata; ?>" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                                <?php } ?>  
                            </div>
                        </div>
                        
                        
                        <div class="clearfix"></div>
                    </div> 
                    <div class="col-lg-12">
                        <div class="col-lg-12">
                        <div class="form-group">
                                <?= lang("Assunto ", "assunto"); ?>
                                 <?php if($statusAta == 1){ ?>
                                <?php echo form_input('assunto', (isset($_POST['assunto']) ? $_POST['assunto'] : $ata->assunto), 'maxlength="200" disabled class="form-control input-tip" required="required" id="assunto"'); ?>
                                <?php }else{ ?>
                                   <?php echo form_input('assunto', (isset($_POST['assunto']) ? $_POST['assunto'] : $ata->assunto), 'maxlength="200" class="form-control input-tip" required="required"  id="assunto"'); ?>
                                      <?php } ?>
                            </div>
                        </div>    
                    </div> 
                    <div class="col-lg-12">
                        <div class="col-md-6" >
                            <div class="form-group">
                                <?= lang("Pauta", "slpauta"); ?><i class="fa fa-info-circle" title="Os principais Assuntos da Reunião."></i>
                                <?php if($statusAta == 1){ ?>
                                <div id="div1">
                                    <?php echo $ata->pauta; ?>
                                </div>
                                <?php }else{ ?>
                                <?php echo form_textarea('pauta', (isset($_POST['pauta']) ? $_POST['pauta'] : $ata->pauta), 'class="form-control" id="slpauta" required style="margin-top: 10px; height: 100px;"'); ?>
                                <?php } ?>  
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Observação", "slnote"); ?>
                                <?php if($statusAta == 1){ ?>
                                 <div id="div1">
                                    <?php echo $ata->obs; ?>
                                </div>
                                <?php }else{ ?>
                                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $ata->obs), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div> 
                                      
                    <div class="col-lg-12">    
                        <div class="col-md-12" >
                            <div class="form-group">
                                <?= lang("Usuários", "slparticipantes"); ?> <i class="fa fa-info-circle" title="As pessoas presentes na Reunião. Para ser um participante é preciso ter um cadastro no SigPlus, caso o participante seja um convisado, visitante, ou não tem usuário no sigPlus, umaa sugestão é utilizar o campo observação para registar sua presença. "></i>

                                <?php if($statusAta == 1){ ?>
                                    <table   class="table">
                                            <tbody style="width: 100%;">
                                                <?php
                                                $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
                                                foreach ($participantes_cadastrados_ata as $participante_cadastrados) {

                                                    if ($participante_cadastrados) {
                                                       // $historico_convocado = $this->atas_model->listaConvocadosByUsuarioAta($cadastro_usuario->id, $id);
                                                        ?>
                                                        <tr>
                                                            <td style="width: 40%;">
                                                                <?php echo ' '. $participante_cadastrados->nome.' - '.$participante_cadastrados->setor; ?>  
                                                          
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                        </table>    
                                        
                                         <?php }else{ 
                                             /*
                                              * <a style="margin-left:10px;" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/lista_participante_plano_acao/'.$id) ?>"> 
                                            <i class="fa fa-users"></i>   <?= lang('Lista de Participantes') ?>
                                        </a>
                                              */
                                             ?>
                                        
                                          <div class="well well-sm well_1">
                                            <?php
                                        //$wu3[''] = '';
                                        foreach ($users as $user) {
                                             $wu4[$user->id] = $user->nome . ' - ' . $user->setor;
                                            //$wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                        }
                                        echo form_dropdown('participantes[]', $wu4, (isset($_POST['participantes']) ? $_POST['participantes'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Adicionar Usuário") . ' "  style="width:100%;" ');
                                        ?>
                                        </div>
                                        
                                        <br><br>
                                        <div class="col-md-12">
                                                <table width="100%">
                                                    <tr>
                                                        <td>Nome</td>
                                                        <td>Participante <i class="fa fa-info-circle" title="Pessoas que deverão comparecer a reunião. "></i></td>
                                                        <td>Vincular <i class="fa fa-info-circle" title="Pessoas que poderão acessar a ATA, mesmo que nãoe steja na reunião. "></i></td>
                                                        <td>Opções</td>
                                                    </tr>
                                                    <?php
                                                    $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
                                                    foreach ($participantes_cadastrados_ata as $participante_cadastrados) {

                                                        if ($participante_cadastrados) {
                                                            $participante = $participante_cadastrados->participante;
                                                            $vinculo = $participante_cadastrados->vinculo;
                                                            
                                                            if($participante == 1){
                                                                $check = "checked";
                                                            }else{
                                                                $check = "";
                                                            }
                                                            
                                                            if($vinculo == 1){
                                                                $check_vinculo = "checked";
                                                            }else{
                                                                $check_vinculo = "";
                                                            }
                                                            
                                                            ?>
                                                            <tr>
                                                                <td width="30%"> <?php echo ' ' . $participante_cadastrados->nome . ' - ' . $participante_cadastrados->setor; ?>  </td>
                                                                <td><input type="checkbox" value="1" <?php echo $check; ?>  name="<?php echo $participante_cadastrados->id; ?>participante" id="participar"></td>
                                                                <td><input type="checkbox" value="<?php echo $participante_cadastrados->id; ?>" <?php echo $check_vinculo; ?> name="<?php echo $participante_cadastrados->id; ?>vinculo" id="vinculo"></td>
                                                                <td><a style="color: red;margin-left: 15px;" title="Remover Usuário" href="<?= site_url("welcome/deleteParticipante/$participante_cadastrados->id/$id"); ?>" class=" fa fa-trash "></a></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </table>
                                             <br> <br>
                                            </div>     
                                       
                                               
                                                 <?php } ?>
                                <br><br>
                                
                            </div>
                        </div>
                        
                     </div> 
                   
                </div>
            </div>
            
            
            <div class="modal-footer">
                <div class="col-lg-12">                          
                    <center>
                        <div class="col-md-12">
                            
                          <?php if(!$statusAta == 1){ ?>    
                        <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success " title="Salvar as alterações feita no cadastro da ATA." style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                          <?php } ?>   
                            <a  class="btn btn-danger" title="Retorna para a lista de ATAS." onclick="history.go(-1)"><?= lang('Sair') ?> <i class="fa fa-sign-out"></i></a>
                         
                            <a  class="btn bg-gray-active" title="Imprimir a ATA " href="<?= site_url('atas/pdf_ata/'.$id); ?>"><?= lang('PDF Ata ') ?> <i class="fa fa-download"></i></a>
                             <?php if($statusAta == 1){ ?> 
                                 <a  class="btn btn-linkedin" title="Registro de convites enviados aos participantes " href="<?= site_url('welcome/convite_ata/'.$id); ?>"><?= lang('Convites') ?> <i class="fa fa-send"></i></a>
                            <?php } ?>     
                                
                        </div>
                    </center> 
                </div>
            </div>
            <?php echo form_close(); ?>
            <!-- /.modal-content -->
            <hr>
            <div class="row">
                <?php
                //$ataAtual = $this->atas_model->getAtaByID($id);
                //$statusAta = $ataAtual->status;
                //$avulsa = $ataAtual->avulsa;

                if($statusAta != 1){
                ?>
                   <center>
                        <?php if($statusAta != 1){ ?>
                        
                            <div class="row">    
                                
                                <div style="margin-top: 10px;" class="col-md-3">
                                    <a  class="btn btn-linkedin  " title="Adicionar uma nova ação a ATA"  href="<?= site_url('welcome/adcionar_acao/'.$id.'/'.$avulsa); ?>"> Nova Ação <i class="fa fa-plus"></i></a> 
                                </div>
                                <div style="margin-top: 10px;" class="col-md-3">
                                    <a  class="btn btn-linkedin" title="Registro da discussão da(s) pauta(s) " href="<?= site_url('welcome/edit_discussao/'.$id); ?>"><?= lang('Anotações da Ata ') ?> <i class="fa fa-file-text-o"></i></a>
                                </div>
                                
                                <div style="margin-top: 10px;" class="col-md-3">
                                    <a  class=" btn btn-linkedin btn-theme " title="A ata é Finalizada, após isso, a Ata não pode ser alterada. E as ações são enviadas aos responsáveis." href="<?= site_url('welcome/finalizaAta/'.$id.'/'.$avulsa); ?>" onclick="alertas();"><?= lang('Finalizar Ata ') ?> <i class="fa fa-check"></i></a>
                                 </div>
                               
                                <div style="margin-top: 10px;" class="col-md-3">
                                    <a  class="btn btn-linkedin" title="Registro de convites enviados aos participantes " href="<?= site_url('welcome/convite_ata/'.$id); ?>"><?= lang('Convites') ?> <i class="fa fa-send"></i></a>
                                </div>
                                   
                             </div>   
                        
                        <?php } ?>
                    </center>
                   <br><br>
                 <?php
                }
                 ?>
            </div>
            <script> 
                var obs = document.getElementById('anotacoes_disabled');
                obs.disabled = true;
            </script>    
            <div class="row">
                 <?php
                    $ataAtual = $this->atas_model->getAtaByID($id);
                    $discussao = $ataAtual->discussao;
                    $statusAta = $ataAtual->status;
                    if($statusAta == 1){
                        if($discussao){
                    ?>

                    <div  class="col-lg-12">
                        <div  class="col-md-12">
                            <div class="form-group">
                                <?= lang("Anotações/Discussão da ATA", "slpauta"); ?><i class="fa fa-info-circle" title="Anotações, Observações ou Discussão sobre a(s) pauta(s) da ata."></i>
                                <?php if($statusAta == 1){ ?>
                                <div id="div1">
                                    <?php echo $discussao; ?>
                                </div>
                                <?php } ?>  
                            </div>
                        </div>
                    </div>
                    <br>


                    <?php 

                        }
                    }
                    ?>
            </div>
           
            <div class="row">
                <?php
                $wu4[''] = '';
                $cont2 = 0;
                foreach ($planos as $plano2) {

                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                    $cont2++;
                }
               
                if ($cont2 > 0) {
                    ?>   
                <div  class="col-lg-12">
                    <div  class="col-lg-12">
                        <div class="portlet portlet-default">
                            
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="ata_acoes" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                               
                                                <th>DESCRIÇÃO</th>
                                                <th>RESPONSÁVEL</th>
                                                <th>PESO</th>
                                                <th>Dt Início</th>
                                                <th>Dt Término</th>
                                                <th>STATUS</th>
                                                <th>OPÇÕES</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $wu4[''] = '';
                                            $cont = 1;
                                            foreach ($planos as $plano) {

                                                $evento = $this->atas_model->getAllitemEventoByID($plano->eventos);
                                                //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                ?>   
                                                <tr class="odd gradeX">
                                                    <td><font  style="font-size: 12px;"><?php echo $plano->sequencial; ?></font></td>
                                                   
                                                    <td><font  style="font-size: 12px;"><?php echo $plano->descricao; ?> </font></td>
                                                    <td><font  style="font-size: 12px;"><?php echo $plano->first_name.' - '.$plano->nome; ?></font></td>
                                                    <td><font  style="font-size: 12px;"><?php echo $plano->peso; ?></font></td>
                                                   <td class="center">
                                                        <font  style="font-size: 12px;"><?php  echo exibirData($plano->data_entrega_demanda); ?> </font>
                                                    </td>     
                                                    <td class="center">
                                                        <font  style="font-size: 12px;"> <?php echo exibirData($plano->data_termino); ?></font>

                                                    </td>

                                                   

                                                    <?php if ($plano->status == 'CONCLUÍDO') { ?>
                                                        <td style="background-color: #00CC00" class="center"><font  style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
                                                    <?php } else if (($plano->status == 'PENDENTE') || $plano->status == 'AGUARDANDO VALIDAÇÃO') { ?>
                                                        <td style="background-color: #CCA940" class="center"><font  style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
                                                    <?php } else if ($plano->status == 'ABERTO') { ?>
                                                        <td style="background-color: activecaption" class="center"><font  style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
                                                    <?php } else if ($plano->status == 'CANCELADO') { ?>
                                                        <td style="background-color: #000000; color: #ffffff;" class="center"><font  style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
                                                        <?php } ?>  
                                                     <?php //if(!$statusAta == 1){ ?>
                                                        <td>
                                                            <div class="text-center">
                                                                <div class="btn-group text-left">
                                                                    <button style="color:#ffffff" type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                                                Ações <span class="caret"></span></button>
                                                                <ul class="dropdown-menu pull-right" role="menu"> 
                                                                     <li><a title="Editar Ação" href="<?= site_url('welcome/manutencao_acao_pendente/' . $plano->idplanos); ?>"><i class="fa fa-edit"></i>Editar </a></li>
                                                                     <li><a title="Duplicar Ação"  href="<?= site_url('welcome/replicar_acao/' . $plano->idplanos . '/' . $plano->idatas); ?>" ><i class="fa fa-refresh"></i> Replicar  </a></li>
                                                                     <li><a title="Deletar Ação." href="<?= site_url('welcome/deletar_acao/' . $plano->idplanos . '/' . $plano->idatas); ?>"><i class="fa fa-trash-o"></i>Deletar</a></li>  
                                                                </ul>
                                                            </div>
                                                            </div>
                                                        </td>
                                                        <?php //} ?>  

                                                </tr>
                                            <?php
                                            }
                                        ?>




                                        </tbody>
                                    </table>
                                    <br><br>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                    </div>
                 </div>   
        <?php } ?>
            </div>
            
            <div class="row">
                <br>

   
   
    <BR>
            </div>
            
        </div>        
    </div>
    </div>
    </section>    
   <script>
  $(function () {
 
    $('#ata_acoes').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>