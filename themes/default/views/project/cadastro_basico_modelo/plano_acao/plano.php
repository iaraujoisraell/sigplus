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
            <?php echo 'Plano de Ação '.$plano_acao->sequencial; ?>
              <small><?php echo $nome_projeto; ?></small>
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Ata</li>
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
    
    <section  class="content">
    <div class="row">    
    
    <div class="col-lg-12">
        <div class="box">
        <div class="modal-header">
            
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Plano de Ação '); ?></h4>
        </div>
            
            
            
            
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("project/plano_acao", $attrib); 
            echo form_hidden('id_cadastro', '1'); 
            echo form_hidden('menu_id', $menu_id); 
            echo form_hidden('tabela_id', $tabela_id); 
            echo form_hidden('tabela_nome', $tabela_nome);
            echo form_hidden('funcao', $funcao);
            echo form_hidden('projeto', $id_projeto);
            echo form_hidden('id', $plano_acao->id);
            
            $statusAta = $plano_acao->status;
            
        ?>
            
            
        <div class="row">
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
                                <?= lang("Data", "dateAta"); ?>
                                <?php if($statusAta == 1){ ?>
                                <input name="data_pa" value="<?php echo $plano_acao->data_pa; ?>" required="true" disabled="true" class="form-control" type="date" >
                                 <?php }else{ ?>
                                <input name="data_pa" value="<?php echo $plano_acao->data_pa; ?>" required="true" class="form-control" type="date" >
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Responsavel", "responsavel"); ?><i class="fa fa-info-circle" title="É a pessoa Responsável pelo Plano de Ação."></i>
                                <?php if($statusAta == 1){ ?>
                                   <?php echo form_input('responsavel', (isset($_POST['responsavel']) ? $_POST['responsavel'] : $plano_acao->responsavel), 'maxlength="150" disabled class="form-control input-tip" required="required" id="responsavel"'); ?>   
                                 <?php }else{ ?>
                                <?php echo form_input('responsavel', (isset($_POST['responsavel']) ? $_POST['responsavel'] : $plano_acao->responsavel), 'maxlength="150" class="form-control input-tip" required="required" id="responsavel"'); ?>
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
                                <?php echo form_textarea('objetivos', (isset($_POST['objetivos']) ? $_POST['objetivos'] : $plano_acao->objetivos), 'class="form-control" maxlength="500" id="objetivos" required style="margin-top: 10px; height: 150px;"'); ?>
                                <?php } ?>  
                            </div>
                        </div>
                </div>
            
            
            <div class="modal-footer">
                <div class="col-lg-12">                          
                    <center>
                        <div class="col-md-12">
                        <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success " style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                             
                                    <a style="color: #ffffff;" class="btn btn-warning  " title="Registro de Ações"  href="<?= site_url('project/adcionar_acao_plano_acao/'.$plano_acao->id); ?>"> Nova Ação <i class="fa fa-plus"></i></a> 
                                
                            <a  class="btn btn-danger"  href="<?= site_url('project/plano_acao/89/55'); ?>"><?= lang('Sair') ?> <i class="fa fa-sign-out"></i></a>
                         </div>
                    </center> 
                </div>
            </div>
            <?php echo form_close(); ?>
            <!-- /.modal-content -->
            <hr>
            <div class="row">
               
            </div>
            
            <div class="row">
                
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
                           
                            <div  class="col-lg-12">
                             <div class="table-responsive">
                                <div class="box-body">
                                    <table id="example1" style="width: 150%" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:5%;">Opções</th>
                                                <th style="width:5%;">ID</th>
                                                <th style="width:10%;">EVENTO</th>
                                                <th style="width:25%;">DESCRIÇÃO</th>
                                                <th style="width:10%;">RESPONSÁVEL</th>
                                                <th style="width:5%;">INÍCIO</th>
                                                <th style="width:5%;">TÉRMINO</th>
                                                <th style="width:10%;">COMO</th>
                                                <th style="width:10%;">POR QUÊ</th>
                                                <th style="width:10%;">LOCAL</th>
                                                <th style="width:10%;">CUSTO</th>
                                                <th style="width:5%;">HORAS PREVISTAS</th>
                                                <th style="width:5%;">PESO</th>
                                                
                                                <th style="width:5%;"><i class="fa fa-paperclip"> </i> ANEXO</th>
                                                <th style="width:5%;">STATUS</th>
                                                
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
                                                    <td style="width:5%;">
                                                            <div class="text-right">
                                                                <div class="btn-group text-left">
                                                                    <button style="color:#ffffff" type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                                                Selecionar <span class="caret"></span></button>
                                                                <ul class="dropdown-menu pull-right" role="menu">
                                                                     <li class="text-right"><a title="Editar Ação" href="<?= site_url('project/manutencao_acao_pendente/' . $plano->idplanos); ?>"><i class="fa fa-edit"></i>Editar </a></li>
                                                                     <li class="text-right"><a title="Duplicar Ação"  href="<?= site_url('project/replicar_acao/' . $plano->idplanos . '/' . $plano->idplano); ?>" ><i class="fa fa-refresh"></i> Replicar  </a></li>
                                                                     <li class="text-right"><a title="Deletar Ação." href="<?= site_url('project/deletar_acao/' . $plano->idplanos . '/' . $plano->idplano); ?>"><i class="fa fa-trash-o"></i>Deletar</a></li>  
                                                                </ul>
                                                            </div>
                                                            </div>
                                                        </td>
                                                    <td style="width:5%;"><font  style="font-size: 12px;"><?php echo $plano->sequencial; ?></font></td>
                                                    <td style="width:10%;"><font  style="font-size: 12px;"><?php echo $evento->evento . '/' . $evento->item; ?></font></td>
                                                    <td style="min-width:25%; width:25%"><font  style="font-size: 12px;"><?php echo $plano->descricao; ?> </font></td>
                                                    <td style="width:10%;" ><font  style="font-size: 12px;"><?php echo $plano->first_name.' - '.$plano->nome; ?></font></td>
                                                    
                                                   <td style="width:5%;" class="center">
                                                        <font  style="font-size: 12px;"><?php  echo exibirData($plano->data_entrega_demanda); ?> </font>
                                                    </td>     
                                                    <td style="width:5%;" class="center">
                                                        <font  style="font-size: 12px;"> <?php echo exibirData($plano->data_termino); ?></font>

                                                    </td>
                                                    <td style="width:10%;"><font  style="font-size: 12px;"><?php echo $plano->como; ?> </font></td>
                                                    <td style="width:10%;"><font  style="font-size: 12px;"><?php echo $plano->porque; ?> </font></td>
                                                    <td style="width:10%;"><font  style="font-size: 12px;"><?php echo $plano->onde; ?> </font></td>
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


                                                    <?php if ($plano->status == 'CONCLUÍDO') { ?>
                                                        <td style="background-color: #00CC00; width:5%;" class="center"><font  style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
                                                    <?php } else if (($plano->status == 'PENDENTE') || $plano->status == 'AGUARDANDO VALIDAÇÃO') { ?>
                                                        <td style="background-color: #CCA940; width:5%;" class="center"><font  style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
                                                    <?php } else if ($plano->status == 'ABERTO') { ?>
                                                        <td style="background-color: activecaption; width:5%;" class="center"><font  style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
                                                    <?php } else if ($plano->status == 'CANCELADO') { ?>
                                                        <td style="background-color: #000000; color: #ffffff; width:5%;" class="center"><font  style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
                                                        <?php } ?>  
                                                    
                                                        
                                                        

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
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>