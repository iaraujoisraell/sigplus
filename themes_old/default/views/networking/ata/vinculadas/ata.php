  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>


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
              <small title="Nome do Projeto em que a ATA esta ligada" class="label label-primary"><?php echo $nome_projeto; ?></small> 
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-home"></i> Networking</a></li>
            <li class="active">Ata Vinculada</li>
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
            echo form_open_multipart("atas/edit", $attrib); 
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
                              
                                <input name="dateAta" value="<?php echo $ata->data_ata; ?>" required="true" disabled="true" class="form-control" type="date" >
                               
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("hora Início ", "data_termino"); ?>
                              
                                <input name="hora_inicio" value="<?php echo $ata->hora_inicio; ?>" disabled="true" required="true" class="form-control" type="time" >
                              
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("hora Fim ", "data_termino"); ?>
                               
                                <input name="hora_fim" required="true" value="<?php echo $ata->hora_termino; ?>" disabled="true" class="form-control" type="time" >
                                
                            </div>
                        </div>    
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Local ", "sllocal"); ?>
                               
                                <?php echo form_input('local', (isset($_POST['local']) ? $_POST['local'] : $ata->local), 'maxlength="200" disabled class="form-control input-tip" required="required" id="sllocal"'); ?>
                                
                            </div>
                        </div>
                        
                        
                    </div>    
                        
                        
                    <div class="col-lg-12">
                        <div class="col-sm-3">
                            <div class="form-group">
                                 <?= lang("Tipo", "tipo"); ?> <i class="fa fa-info-circle" title="A Ata do tipo REUNIÃO, é a forma padrão de um cadastro de ATA. Podendo regisrar quantas atas de reuniões for preciso. O tipo REUNIÃO CONTÍNUA, é para quando você quiser gerenciar reuniões de um assunto específico e determinado, relacionado a um item de evento específico do escopo. Por Exemplo: Se a reunião é do tipo PLANEJAMENTO, e haverá outras reuniões sobre esse mesmo assunto, pode vincular ao item do escopo que se refere ao planejamento, assim poderá acompanhar as ações definidas em reuniões e a este item."></i>
                                 <?php $pst[''] = '';
                                  $pst['REUNIÃO'] = lang('REUNIÃO');
                                  $pst['REUNIÃO CONTÍNUA'] = lang('REUNIÃO CONTÍNUA');
                                      echo form_dropdown('tipo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $ata->tipo), 'id="tipo" disabled class="form-control "  data-placeholder="' . lang("select") . ' ' . lang("o tipo de Ata") . '" required="required"   style="width:100%;" ');
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Responsável", "slelaboracao"); ?><i class="fa fa-info-circle" title="É a pessoa Líder, quem convocou ou quem irá conduzir a Ata."></i>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $ata->responsavel_elaboracao), 'maxlength="200" disabled class="form-control input-tip" required="required" id="slelaboracao"'); ?>   
                            </div>
                        </div> 
                        
                        <script>
                         function optionConvocacao(){
                                var option = document.getElementById("convocacao").value;
                                if(option == "SIM"){
                                 //   document.getElementById("hiddenDiv").style.visibility ="visible";
                                    document.getElementById("hiddenDivConcovacao").style.display = "block";
                                }else{
                                    document.getElementById("hiddenDivConcovacao").style.display = "none";
                                }
                              
                            }
                        </script>
                             
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Anexo", "document") ?> 
                                    <?php if($ata->anexo){ ?>
                                <div class="btn-group">
                            <a href="<?= site_url('assets/uploads/atas/' . $ata->anexo_ata) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                <br>
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                            </a>
                                    <?php /* <input type="checkbox"><button type="button" class="btn btn-danger" id="reset"><?= lang('REMOVER') ?> */ ?>
                        </div>
                               
                                <?php } ?>
                              
                            </div>
                        </div>
                        
                        
                        <div class="clearfix"></div>
                    </div> 
                    
                    <div id="hiddenDiv" class="col-md-12">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Escopo do Projeto", "slEvento"); ?>
                                        <?php
                                        $wue[''] = '';
                                      foreach ($eventos as $evento) {
                                            $wue[$evento->id] = $evento->nome_fase.' > '. $evento->nome_evento.' > '. resume($evento->descricao, 100);
                                           
                                        }
                                       
                                          echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : $ata->evento), 'id="slEvento" disabled  class="form-control  select" data-placeholder="' . lang("Selecione o Item do Evento") . ' "  style="width:100%;"  ');    
                                        
                                         ?>
                                    </div>
                                </div>
                        </div>
                    
                      
                    <div class="col-lg-12">
                        <div class="col-md-6" >
                            <div class="form-group">
                                <?= lang("Pauta", "slpauta"); ?><i class="fa fa-info-circle" title="Os principais Assuntos da Reunião."></i>
                                <div id="div1">
                                    <?php echo $ata->pauta; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Observação", "slnote"); ?>
                                <div id="div1">
                                    <?php echo $ata->obs; ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                                      
                    <div class="col-lg-12">    
                        <div class="col-md-6" >
                            <div class="form-group">
                                <?= lang("Participantes", "slparticipantes"); ?> <i class="fa fa-info-circle" title="As pessoas presentes na Reunião. Para ser um participante é preciso ter um cadastro no SigPlus, caso o participante seja um convisado, visitante, ou não tem usuário no sigPlus, umaa sugestão é utilizar o campo observação para registar sua presença. "></i>
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
                                <br><br>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Usuários Vinculados", "slAta_usuario");?> <i class="fa fa-info-circle" title="Os usuários vinculados podem visualizar o conteúdo completo da Ata, através do SIGPLUS.O usuário vinculado não precisa ser um participante da ATA."></i>
                                <table   class="table">
                                        <tbody style="width: 100%;">
                                            <?php
                                            $usuarios_cadastrados_ata = $this->atas_model->getAtaUserByID_ATA($id);
                                            foreach ($usuarios_cadastrados_ata as $usuario_cadastrados) {
                                                if ($usuario_cadastrados) {
                                                    ?>
                                                    <tr>
                                                        <td style="width: 40%;">
                                                            <?php echo ' ' . $usuario_cadastrados->nome . ' - ' . $usuario_cadastrados->setor; ?>
                                                        </td>
                                                    </tr>

                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table> 
                            </div>
                        </div>
                     </div> 
                   
                </div>
            </div>
            
            
            <div class="modal-footer">
                <div class="col-lg-12">                          
                    <center>
                        <div class="col-md-12">
                            <a  class="btn btn-danger" title="Retorna para a lista de ATAS." href="<?= site_url('project/atas'); ?>"><?= lang('Sair') ?> <i class="fa fa-sign-out"></i></a>
                            <a target="_blank" class="btn btn-bitbucket" title="Ver o Gráfico de Gantt das ações da Ata"  href="<?= site_url('project/ganttPlano/1/' .$ata->id); ?>"><i class="fa fa-tasks"></i>  GANTT </a>
                         </div>
                    </center> 
                </div>
            </div>
            <?php echo form_close(); ?>
            <!-- /.modal-content -->
            <hr>
            <div class="row">
                 <?php
                    $ataAtual = $this->atas_model->getAtaByID($id);
                    $discussao = $ataAtual->discussao;
                    $statusAta = $ataAtual->status;
                   
                        if($discussao){
                    ?>

                    <div  class="col-lg-12">
                        <div  class="col-md-12">
                            <div class="form-group">
                                <?= lang("Anotações/Discussão da ATA", "slpauta"); ?><i class="fa fa-info-circle" title="Anotações, Observações ou Discussão sobre a(s) pauta(s) da ata."></i>
                               <div id="div1">
                                    <?php echo $discussao; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php 
                    }
                    ?>
            </div>
               
            <div class="row">
                <center>
                    <h3>Lista de Ações</h3>
                </center>
                <div  class="col-lg-12">
                        <div  class="col-lg-12">
                            <div class="portlet portlet-default">

                                <div class="portlet-body">
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>EVENTO</th>
                                                    <th>DESCRIÇÃO</th>
                                                    <th>RESPONSÁVEL</th>
                                                    <th>PESO</th>
                                                    <th>Dt Início</th>
                                                    <th>Dt Término</th>
                                                    <th>STATUS</th>
                                                    <th>AÇÃO</th>

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
                                                        <td><font  style="font-size: 12px;"><?php echo $evento->evento . '/' . $evento->item; ?></font></td>
                                                        <td><font  style="font-size: 12px;"><?php echo $plano->descricao; ?> </font></td>
                                                        <td><font  style="font-size: 12px;"><?php echo $plano->first_name . ' - ' . $plano->nome; ?></font></td>
                                                        <td><font  style="font-size: 12px;"><?php echo $plano->peso; ?></font></td>
                                                        <td class="center">
                                                            <font  style="font-size: 12px;"><?php echo exibirData($plano->data_entrega_demanda); ?> </font>
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
                                                            <td class="center"><a title="Ver Ação" href="<?= site_url('welcome/consultar_acao/' . $plano->idplanos); ?>"><i class="fa fa-folder-open"></i> </a></td>
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
            </div>
           
    </div>
    </div>
    </section>    
