  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>

 

    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo $plano_acao->assunto; ?>
              <small><?php echo 'Plano de Ação '; ?></small>
              <br>
              <?php $status_plano = $plano_acao->status;
                    if($status_plano == 1){
                        $status_desc = "FECHADO";
                        $label_status = "success";
                    }else{
                        $status_desc = "ABERTO";
                        $label_status = "warning";
                    }
              ?>
              <font class="label label-<?php echo $label_status; ?>"><?php echo $status_desc; ?></font>
               <?php if($status_plano != 1){ ?>
            <a  class="btn btn-success"  href="<?= site_url('welcome/finalizaPlano/'.$plano_acao->id); ?>"><?= lang('Ativar Plano') ?> <i class="fa fa-check"></i></a>
            <?php } ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('networking'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Plano de Ação</li>
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

    <div class="row">  
        <div class="col-lg-12">
            <div class="col-lg-12">
            <a style="color: #ffffff;" class="btn bg-navy-active  " title="Registro de Ações"  href="<?= site_url('welcome/adcionar_acao/'.$plano_acao->id.'/2'); ?>"> Nova Ação <i class="fa fa-plus"></i></a> 
            <a style="color: #ffffff;" class="btn bg-blue-active  " title="Registro de Atas"  href="<?= site_url('welcome/novaAta/0/86/'.$plano_acao->id.'/2'); ?>"> Nova Reunião <i class="fa fa-plus"></i></a> 
            <a style="color: #ffffff;" class="btn bg-blue-gradient  " title="Visualizar PDF do Plano de Ação"  href="<?= site_url('welcome/pdf_plano_acao/'.$plano_acao->id); ?>"> PDF <i class="fa fa-download"></i></a>
            <a style="color: #ffffff;" class="btn bg-black-gradient  " title="Visualizar o Gráfico de Gantt"  href="<?= site_url('welcome/ganttPlanoAcao/'.$plano_acao->id); ?>"> GANNT <i class="fa fa-dashboard"></i></a> 
           
            <a  class="btn btn-danger"  onclick="history.go(-1)"><?= lang('Sair') ?> <i class="fa fa-sign-out"></i></a>
             
             </div>
        </div>
    </div>    
    <section  class="content">
        
        <div  class="nav-tabs-custom">
                    <ul style="background-color: #d2d6de; " class="nav nav-tabs">
                        <li class="active"><a href="#5w2h" data-toggle="tab"><b>Ações 5W2H<i class="fa fa-list"></i></b></a></li>
                        <li ><a href="#acoes" data-toggle="tab"><b>Ações Card <i class="fa fa-list"></i></b></a></li>
                        <li ><a href="#cadastro"  data-toggle="tab"><b>Dados Cadastro <i class="fa fa-file-text-o"></i></b></a></li>  
                        <li><a href="#reunioes" data-toggle="tab"><b>Reuniões<i class="fa fa-book"></i></b></a></li>
                        <li><a href="#categoria" data-toggle="tab"><b>Categorias do Plano <i class="fa fa-th-list"></i></b></a></li>
                    </ul>
                    <div class="tab-content">
                         <div class="active tab-pane" id="5w2h">
                            <div class="row">
                                    <div  class="col-lg-12">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Lista de Ações de Ações '); ?></h4>
                                        </div>
                                    </div> 
                                
                                <div  class="col-lg-12">
                                 <div class="table-responsive">
                                    <div class="box-body">
                                        <table id="example1" style="width: 150%; min-width: 150%;" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width:5%;">Opções</th>
                                                    <th style="width:5%;">ID</th>
                                                    <th style="width:10%;">CATEGORIA</th>
                                                    <th style="width:35%;">DESCRIÇÃO</th>
                                                    <th style="width:10%;">RESPONSÁVEL</th>
                                                    <th style="width:5%;">INÍCIO</th>
                                                    <th style="width:5%;">TÉRMINO</th>
                                                    <th style="width:20%;">COMO</th>
                                                    <th style="width:20%;">POR QUÊ</th>
                                                    <th style="width:20%;">LOCAL</th>
                                                    <th style="width:10%;">CUSTO</th>
                                                    <th style="width:5%;">HORAS PREVISTAS</th>
                                                    <th style="width:5%;">PESO</th>

                                                    <th style="width:5%;"><i class="fa fa-paperclip"> </i> ANEXO</th>
                                                   

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $wu4[''] = '';
                                                $cont_plano_list = 1;
                                                foreach ($planos as $plano) {
                                                    
                                                    $evento = $this->atas_model->getAllitemEventoByID($plano->eventos);
                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                    ?>   
                                                <?php if ($plano->status == 'CONCLUÍDO') { 
                                                     $label_status_acao = "success";
                                                } else if (($plano->status == 'PENDENTE') || $plano->status == 'AGUARDANDO VALIDAÇÃO') { 
                                                    $label_status_acao = "warning";
                                                 } else if ($plano->status == 'ABERTO') { 
                                                     $label_status_acao = "primary";
                                                 } else if ($plano->status == 'CANCELADO') { 
                                                     $label_status_acao = "default";
                                                 } ?>  
                                                
                                                
                                                    <tr class="odd gradeX">
                                                        <td style="width:5%;">
                                                                <div class="text-right">
                                                                    <div class="btn-group text-left">
                                                                        <button style="color:#ffffff" type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                                                    Selecionar <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                                         <li class="text-right"><a title="Editar Ação" href="<?= site_url('welcome/manutencao_acao_pendente/' . $plano->id); ?>"><i class="fa fa-folder-open"></i>Abrir </a></li>
                                                                         <li class="text-right"><a title="Deletar Ação." href="<?= site_url('welcome/deletar_acao/' . $plano->id . '/' . $plano->idplano); ?>"><i class="fa fa-trash-o"></i>Deletar</a></li>  
                                                                    </ul>
                                                                </div>
                                                                </div>
                                                            </td>
                                                        <td style="width:5%;"><font  style="font-size: 12px;"><?php echo $cont_plano_list++; ?></font></td>
                                                        <td style="width:10%;"><font  style="font-size: 12px;"><?php echo $plano->categoria; ?></font></td>
                                                        <td style="min-width:35%; width:25%"><font  style="font-size: 12px;"><label class="label label-<?php echo $label_status_acao; ?>"><?php echo $plano->status; ?></label> <?php echo 'ID '.$plano->sequencial.' - '. $plano->descricao; ?> </font></td>
                                                        <td style="width:10%;" ><font  style="font-size: 12px;"><?php echo $plano->responsavel.' - '.$plano->setor; ?></font></td>

                                                       <td style="width:5%;" class="center">
                                                            <font  style="font-size: 12px;"><?php  echo exibirData($plano->data_entrega_demanda); ?> </font>
                                                        </td>     
                                                        <td style="width:5%;" class="center">
                                                            <font  style="font-size: 12px;"> <?php echo exibirData($plano->data_termino); ?></font>

                                                        </td>
                                                        <td style="width:20%;"><font  style="font-size: 12px;"><?php echo $plano->como; ?> </font></td>
                                                        <td style="width:20%;"><font  style="font-size: 12px;"><?php echo $plano->porque; ?> </font></td>
                                                        <td style="width:20%;"><font  style="font-size: 12px;"><?php echo $plano->onde; ?> </font></td>
                                                        <td style="width:10%;"><font  style="font-size: 12px;"><?php echo $plano->custo.' R$ : '.$plano->valor_custo; ?> </font></td>
                                                        <td style="width:5%;" class="center"><font  style="font-size: 12px;"><?php echo $plano->horas_previstas; ?> </font></td>
                                                        <td style="width:5%;" class="center"><font  style="font-size: 12px;"><?php echo $plano->peso; ?> </font></td>

                                                        <td style="width:5%;">
                                                    <?php if ($plano->anexo) { ?>

                                                                <a target="_blank" href="<?= site_url('../assets/uploads/atas/' . $plano->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                                    <i class="fa fa-download"></i>


                                                                </a>
                                                        <?php } ?>
                                                        </td>



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
                            </div>  
                                
                            </div>
                        </div>      
                        <div class=" tab-pane" id="acoes">
                            <div class="row">
                                    <div  class="col-lg-12">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Ações '); ?></h4>
                                        </div>
                                    </div>    
                                <?php
                                $wu4[''] = '';
                                $cont2 = 0;
                                foreach ($planos as $plano2) {

                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                    $cont2++;
                                }
                                ?>   
     
                              
                                    <?php
                                    $wu4[''] = '';
                                    $cont = 1;
                                    foreach ($planos as $plano) {

                                        //$evento = $this->atas_model->getAllitemEventoByID($plano->eventos);
                                        //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                        ?>   
                                <div  class="col-md-4">
                                              <div class="box-tools pull-right" data-toggle="tooltip" >
                                                <div class="btn-group" data-toggle="btn-toggle">
                                                    <a class="btn btn-warning btn-sm active" title="Detalhes da Ação" href="<?= site_url('welcome/manutencao_acao_pendente/' . $plano->id); ?>"><i class="fa fa-folder-open"></i> </a>
                                                    <a class="btn btn-danger btn-sm active" title="Deletar Ação." href="<?= site_url('welcome/deletar_acao/' . $plano->id . '/' . $plano->idplano); ?>"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                              </div>
                                              <!-- Box Comment -->
                                              <div class="box box-widget">
                                                <div style="height: 200px; min-height: 200px;" class="box-header with-border">
                                                  <div class="user-block">
                                                    <img src="<?= $dados_user_pi->avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $plano->avatar : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="img-circle" alt="User Image">
                                                    <span class="username"><a href="#"><?php echo $plano->responsavel.' - '.$plano->setor; ?></a></span>
                                                     <?php if ($plano->status == 'CONCLUÍDO') { 
                                                        $label_desc = 'success';
                                                      } else if (($plano->status == 'PENDENTE') || $plano->status == 'AGUARDANDO VALIDAÇÃO') { 
                                                        $label_desc = 'warning';  
                                                      } else if ($plano->status == 'ABERTO') {
                                                        $label_desc = 'primary';
                                                      } else if ($plano->status == 'CANCELADO') { 
                                                        $label_desc = 'default';  
                                                      } ?>
                                                    <span class="description"><font style="font-size: 16px; "> <?php echo 'Ação '.$plano->id.' : '. $plano->descricao; ?> </font>
                                                        <font   class="label label-<?php echo $label_desc; ?>" style="font-size: 12px; "><?php echo $plano->status; ?>  </font> 
                                                    </span>
                                                    <br>
                                                    <font class="label label-primary"><?php echo 'Categoria '. $plano->categoria; ?></font>
                                                  </div>
                                                </div>
                                                <!-- /.box-header -->
                                                <div class="box-body">
                                                  <p style="font-size: 12px; ">Prazo: de <?php  echo '<b>'. exibirData($plano->data_entrega_demanda).'</b>'; ?> até <?php echo '<b>'. exibirData($plano->data_termino).'</b>'; ?></p>
                                                </div>
                                              </div>
                                              <!-- /.box -->
                                    </div>
                                    <?php
                                    }
                                ?>

                                                       
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="cadastro">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="modal-header">
                                        <h4 class="modal-title" ><?php echo lang('Cadastro de Plano de Ação '); ?></h4>
                                    </div>
                                </div>    
                              
                              
                                    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
                                        echo form_open_multipart("welcome/plano_acao_networking", $attrib); 
                                        echo form_hidden('id_cadastro', '1'); 
                                        echo form_hidden('menu_id', $menu_id); 
                                        echo form_hidden('tabela_id', $tabela_id); 
                                        echo form_hidden('id', $plano_acao->id);

                                        $statusAta = $plano_acao->status;
                                    ?>                                  
                                        <div class="col-md-12">
                                                <div class="col-lg-6">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <?= lang("Assunto", "assunto"); ?><i class="fa fa-info-circle" title="É a pessoa Líder, quem convocou ou quem irá conduzir a Ata."></i>
                                                            <?php if($statusAta == 1){ ?>
                                                               <?php echo form_input('assunto', (isset($_POST['assunto']) ? $_POST['assunto'] : $plano_acao->assunto), 'maxlength="250" disabled class="form-control input-tip" required="required" id="assunto"'); ?>   
                                                             <?php }else{ ?>
                                                            <?php echo form_input('assunto', (isset($_POST['assunto']) ? $_POST['assunto'] : $plano_acao->assunto), 'maxlength="250" class="form-control input-tip" required="required" id="assunto"'); ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>    
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <?= lang("Data De", "dateAta"); ?>
                                                            <?php if($statusAta == 1){ ?>
                                                            <input name="data_pa" value="<?php echo $plano_acao->data_pa; ?>" required="true" disabled="true" class="form-control" type="date" >
                                                             <?php }else{ ?>
                                                            <input name="data_pa" value="<?php echo $plano_acao->data_pa; ?>" required="true" class="form-control" type="date" >
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <?= lang("Data Até", "dateAta"); ?>
                                                            <?php if($statusAta == 1){ ?>
                                                            <input name="data_ate" value="<?php echo $plano_acao->data_termino_previsto; ?>" required="true" disabled="true" class="form-control" type="date" >
                                                             <?php }else{ ?>
                                                            <input name="data_ate" value="<?php echo $plano_acao->data_termino_previsto; ?>" required="true" class="form-control" type="date" >
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <?= lang("Responsavel Elaboração", "responsavel"); ?><i class="fa fa-info-circle" title="É a pessoa Responsável pela elaboração do Plano de Ação."></i>
                                                            <?php if($statusAta == 1){ ?>
                                                               <?php echo form_input('responsavel', (isset($_POST['responsavel']) ? $_POST['responsavel'] : $plano_acao->responsavel), 'maxlength="150" disabled class="form-control input-tip" required="required" id="responsavel"'); ?>   
                                                             <?php }else{ ?>
                                                            <?php echo form_input('responsavel', (isset($_POST['responsavel']) ? $_POST['responsavel'] : $plano_acao->responsavel), 'maxlength="150" class="form-control input-tip" required="required" id="responsavel"'); ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <?= lang("Responsavel Aprovação", "responsavel"); ?><i class="fa fa-info-circle" title="É a pessoa quem aprova o Plano de Ação."></i>
                                                            <?php if($statusAta == 1){ ?>
                                                               <?php echo form_input('responsavel_aprovacao', (isset($_POST['responsavel_aprovacao']) ? $_POST['responsavel_aprovacao'] : $plano_acao->responsavel_aprovacao), 'maxlength="150" disabled class="form-control input-tip" required="required" id="responsavel_aprovacao"'); ?>   
                                                             <?php }else{ ?>
                                                            <?php echo form_input('responsavel_aprovacao', (isset($_POST['responsavel_aprovacao']) ? $_POST['responsavel_aprovacao'] : $plano_acao->responsavel_aprovacao), 'maxlength="150" class="form-control input-tip" required="required" id="responsavel_aprovacao"'); ?>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-md-6" >
                                                    <div class="form-group">
                                                        <?= lang("Objetivos", "objetivos"); ?><i class="fa fa-info-circle" title="Os principais Objetivos do Plano de Ação."></i>
                                                        <?php if($statusAta == 1){ ?>
                                                        <?php echo form_textarea('objetivos', (isset($_POST['objetivos']) ? $_POST['objetivos'] : $plano_acao->objetivos), 'class="form-control" maxlength="500" disabled id="objetivos" required style="margin-top: 10px; height: 150px;"'); ?> 
                                                        <?php }else{ ?>
                                                        <?php echo form_textarea('objetivos', (isset($_POST['objetivos']) ? $_POST['objetivos'] : $plano_acao->objetivos), 'class="form-control" maxlength="500" id="objetivos"  style="margin-top: 10px; height: 150px;"'); ?>
                                                        <?php } ?>  
                                                    </div>
                                                </div>
                                            </div>
                                        <br>
                                       
                                            <div class="col-lg-12">                          
                                                <center>
                                                    <div class="col-md-12">
                                                    <?php echo form_submit('add_item', lang("Atualizar"), 'id="add_item" class="btn btn-success " style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>

                                                     </div>
                                                </center> 
                                            </div>
                                       
                                        <?php echo form_close(); ?>
                                        <!-- /.modal-content -->
                              
                            </div>
                        </div>
                        
                        <div class=" tab-pane" id="reunioes">
                            <div class="col-lg-12">
                                <div class="row">

                                            <div class="box">

                                                  <br>
                                                <div class="table-responsive">
                                                    <div class="box-body">
                                                        <table style="width: 100%;" id="minhas_atas" class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                        <th style="width:1%; text-align: center;"><input class="checkbox checkft" type="checkbox" name="check"/></th>
                                                        <th style="width:5%;" ><?php echo $this->lang->line("ATA"); ?></th>
                                                        <th style="width:10%;"><?php echo $this->lang->line("DT ATA"); ?></th>
                                                        <th style="width:34%;"><?php echo $this->lang->line("Assunto/ Pauta"); ?></th>
                                                        <th style="width:5%;"><?php echo $this->lang->line("Tipo"); ?></th>
                                                        <th style="width:5%;"><?php echo $this->lang->line("Resp."); ?></th>
                                                        <th style="width:5%;"><?php echo $this->lang->line("Status"); ?></th>
                                                        <th style="width:5%;"><?php echo $this->lang->line("Ações"); ?></th>
                                                        <th style="width:15%;  text-align: center;" ><?php echo $this->lang->line("Andamento"); ?></th>
                                                        <th style="width:15%;  text-align: center;"><?php echo $this->lang->line("Opções"); ?></th>
                                                    </tr>
                                                    </thead>
                                                        <tbody>
                                                             <?php
                                                                $cont = 1;
                                                               
                                                                foreach ($atas as $ata) {

                                                                   $hoje = date('Y-m-s');
                                                                   //TOTAL DE ACOES
                                                                   $planos = $this->atas_model->getTotalAcoesByAta($ata->id);
                                                                   $total_acao = $planos->total_acoes;
                                                                   //TOTAL PESO
                                                                   $planos_peso = $this->atas_model->getTotalPesoAcoesByAta($ata->id);
                                                                   $total_peso = $planos_peso->total_peso;
                                                                   //ATRASADO
                                                                   $planos_peso_atrasado = $this->atas_model->getTotalPesoAcoesAtrasadasByAta($ata->id);
                                                                   $total_peso_atrasado = $planos_peso_atrasado->atrasado_peso;
                                                                   //PENDENTE
                                                                   $planos_peso_pendente = $this->atas_model->getTotalPesoAcoesPendentesByAta($ata->id);
                                                                   $total_peso_pendente = $planos_peso_pendente->pendente_peso;
                                                                   //CONCLUÍDO
                                                                   $planos_peso_concluido = $this->atas_model->getTotalPesoAcoesConcluidoByAta($ata->id);
                                                                   $total_peso_concluido = $planos_peso_concluido->conclusao_peso;

                                                                   $status = $ata->status;

                                                                    if($status == 0){
                                                                        $status_ata = 'Aberto';
                                                                        $label = "warning";
                                                                    }else if($status == 1){
                                                                        $status_ata = 'Fechado';
                                                                        $label = "success";
                                                                    }


                                                                        $label_tipo = "default";


                                                                    $porc_concluido = $total_peso_concluido * 100/ $total_peso;;
                                                                    $porc_pendente  = $total_peso_pendente * 100/ $total_peso;;
                                                                    $porc_atrasado  = $total_peso_atrasado * 100/ $total_peso;
                                                                ?>               

                                                                    <tr  >

                                                                        <td style="width: 1%;  font-size: 12;"><input class="checkbox checkft" type="checkbox" name="check"/></td> 
                                                                        <td style="width: 5%; text-align: center; font-size: 12;"><small   ><?php echo $ata->sequencia; ?></small></td>
                                                                        <td style="width: 10%; font-size: 12; "><small   ><?php echo exibirData(substr($ata->data_ata, 0, 10)); ?></small></td>
                                                                        <td style="width: 34%;  font-size: 12px;">
                                                                            <?php echo $ata->assunto; ?>
                                                                            <small ><p><?php echo $ata->pauta; ?></p></small>
                                                                        </td> 
                                                                        <td style="width: 5%; font-size: 12; text-align: center;"><small class="label label-<?php echo $label_tipo; ?>" ><?php echo $ata->tipo; ?></small></td> 
                                                                        <td style="width: 5%;font-size: 12; "><small  ><?php echo $ata->responsavel_elaboracao; ?></small></td> 
                                                                        <td style="width: 5%; font-size: 12; text-align: center;"><small class="label label-<?php echo $label; ?>" ><?php echo $status_ata; ?></small></td> 
                                                                        <td style="width: 5%; font-size: 12; text-align: center;"><b><?php echo $total_acao; ?></b></td> 
                                                                        <td style="width: 15%;font-size: 12; ">
                                                                            <div class="progress">
                                                                              <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido;  ?>%">
                                                                               <?php if($porc_concluido != 100){ echo  substr($porc_concluido,0,2); }else{ echo $porc_concluido; } ?> % Concluído
                                                                              </div>
                                                                              <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente;  ?>%">
                                                                               <?php if($porc_pendente != 100){ echo  substr($porc_pendente,0,2); }else{ echo $porc_pendente; } ?>% Pendente
                                                                              </div>
                                                                              <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php  echo $porc_atrasado;  ?>%">
                                                                               <?php if($porc_atrasado != 100){ echo  substr($porc_atrasado,0,2); }else{ echo $porc_atrasado; } ?>% Atrasado
                                                                              </div>
                                                                            </div>
                                                                        </td> 
                                                                        <td style="width: 15%; font-size: 12;">
                                                                            <a class="btn btn-primary" title="Abrir ATA"  href="<?= site_url('welcome/plano_acao/'.$ata->id); ?>"><i class="fa fa-folder-open"></i>  ABRIR </a>
                                                                        </td>    


                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </tbody>
                                                    </table>
                                                        <br><br><br><br>
                                                    </div>    
                                                </div>

                                            </div>

                          <!-- /.row (main row) -->
                                </div>
                            </div>
                        </div>
                        
                        <div class=" tab-pane" id="categoria">
                            <div class="row"> 
                                <div  class="col-lg-12">
                               <div class="box box-primary">
                                <div class="box-header">
                                  <i class="ion ion-clipboard"></i>

                                  <h3 class="box-title">Lista de Categorias</h3>


                                </div>
                                    <!-- Content Header (Page header)   -->
                                  
                                    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
                                        echo form_open_multipart("welcome/atualizarOrdemCategoriaPlano", $attrib); 
                                        echo form_hidden('id_cadastro', '1'); 
                                        echo form_hidden('plano_acao', $plano_acao->id); 
                                    ?>   
                                <!-- /.box-header -->
                                <div class="box-body">
                                           <ul class="todo-list " >
                                    <?php
                                        $wu4[''] = '';
                                        $cont_arquivo = 1;
                                        foreach ($categorias as $categoria) {
                                    ?>   
                                            <li >
                                                <input type="hidden" name="ordem_categoria[]" value="<?php echo $categoria->id; ?>">
                                          <!-- drag handle -->
                                          <span class="handle">
                                                <i class="fa fa-ellipsis-v"></i>
                                                <i class="fa fa-ellipsis-v"></i>
                                              </span>
                                          <!-- checkbox -->

                                          <!-- todo text -->
                                          <span class="text"><?php echo $categoria->descricao; ?></span>
                                          <!-- Emphasis label -->

                                          <!-- General tools such as edit or delete-->
                                          
                                          <div class="tools">
                                            <a class="btn btn-warning" title="Apagar Arquivo" href="<?= site_url('welcome/deletarCadastro/108/' . $categoria->id.'/89/0'); ?>"><i class="fa fa-edit"></i></a>
                                           <?php  if($status_plano != 1){ ?>
                                            <a class="btn btn-danger" title="Apagar Arquivo" href="<?= site_url('welcome/deletarCadastro/108/' . $categoria->id.'/89/0'); ?>"><i class="fa fa-trash-o"></i></a> 
                                           <?php } ?>
                                          </div>
                                        </li>
                                    <?php 
                                        }
                                      ?>  
                                  </ul>
                                             
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer clearfix no-border">
                                   <?php echo form_submit('add_item', lang("Atualizar Ordem"), 'id="add_item" class="btn btn-primary "   '); ?>
                                    <a href="<?= site_url('welcome/novaCategoriaPlano/'.$plano_acao->id); ?>" data-toggle="modal" data-target="#myModal" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Nova Categoria</a>
                                     <?php echo form_close(); ?>
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
    $('#example1').DataTable()
    $('#categoria_plano').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>