

    
    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
    <div class="col-lg-12">
        <div class="box">
        <section class="content-header">
            <h1>
                <?php echo $titulo; ?>
                <small><?php echo $descricao_titulo; ?> </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Plano Ação</li>
            </ol>
        </section>
        <div class="box-header">
                    <span class="pull-right-container">
                       <div class=" clearfix no-border">
                           <a  title="Cadastrar Nova Tarefa" class="btn btn-primary pull-right" href="<?= site_url('project/novoPlano'); ?>" data-toggle="modal" data-target="#myModal">  
                           <i class="fa fa-plus"></i>   Novo Plano de Ação
                           </a> 
                         
                        </div>
                    </span>
                </div>
        <br>
        </div>    
    </div>
    <div class="col-lg-12">
    <!-- Content Header (Page header) -->
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
    <!-- Main content -->
    </div>
    <section  class="content">
        <div class="col-lg-12">
            <div class="row">
                
                        <div class="box">
                    <br>
                            <div class="table-responsive">
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                    <th style="width:3%; "><input class="checkbox checkft" type="checkbox" name="check"/></th>    
                                    <th style="width:3%;" ><?php echo $this->lang->line("Id"); ?></th>
                                    <th style="width:30%;"><?php echo $this->lang->line("Título"); ?></th>
                                    <th style="width:14%;"><?php echo $this->lang->line("Status"); ?></th>
                                    <th style="width:15%;"><?php echo $this->lang->line("Responsável"); ?></th>
                                    <th style="width:5%;"><?php echo $this->lang->line("Data"); ?></th>
                                     <th style="width:5%;"><?php echo $this->lang->line("Ações"); ?></th>
                                    <th style="width:15%;  text-align: center;" ><?php echo $this->lang->line("Andamento"); ?></th>
                                    <th style="width:10%;  text-align: center;"><?php echo $this->lang->line("Opções"); ?></th>
                                </tr>
                                </thead>
                                    <tbody>
                                         <?php
                                            $wu4[''] = '';
                                            $cont = 1;
                                            foreach ($planos_acao as $ata) {
                                                $status_plano = $ata->status;
                                                if($status_plano == 1){
                                                    $status_desc = "ATIVO";
                                                    $label_status = "success";
                                                }else  if($status_plano == 0){
                                                    $status_desc = "ABERTO";
                                                    $label_status = "warning";
                                                }else  if($status_plano == 2){
                                                    $status_desc = "CANCELADO";
                                                    $label_status = "default";
                                                }
                                               //TOTAL DE ACOES
                                               $planos = $this->atas_model->getTotalAcoesByPlanoAcao($ata->id);
                                               $total_acao = $planos->total_acoes;
                                               //TOTAL PESO
                                               $planos_peso = $this->atas_model->getTotalPesoAcoesByPlanoAcao($ata->id);
                                               $total_peso = $planos_peso->total_peso;
                                               //ATRASADO
                                               $planos_peso_atrasado = $this->atas_model->getTotalPesoAcoesAtrasadasByPlanoAcao($ata->id);
                                               $total_peso_atrasado = $planos_peso_atrasado->atrasado_peso;
                                               //PENDENTE
                                               $planos_peso_pendente = $this->atas_model->getTotalPesoAcoesPendentesByPlanoAcao($ata->id);
                                               $total_peso_pendente = $planos_peso_pendente->pendente_peso;
                                               //CONCLUÍDO
                                               $planos_peso_concluido = $this->atas_model->getTotalPesoAcoesConcluidoByPlanoAcao($ata->id);
                                               $total_peso_concluido = $planos_peso_concluido->conclusao_peso;
                                               
                                              
                                               
                                               
                                               $status = $ata->status;
                                                
                                                if($status == 0){
                                                    $status_ata = 'Aberto';
                                                    $label = "warning";
                                                }else 
                                                if($status == 1){
                                                    $status_ata = 'Fechado';
                                                    $label = "success";
                                                }
                                                
                                                if($ata->tipo == "REUNIÃO"){
                                                    $label_tipo = "default";
                                                }else if($ata->tipo == "REUNIÃO CONTÍNUA"){
                                                    $label_tipo = "primary";
                                                }
                                                
                                                $porc_concluido = $total_peso_concluido * 100/ $total_peso;;
                                                $porc_pendente  = $total_peso_pendente * 100/ $total_peso;;
                                                $porc_atrasado  = $total_peso_atrasado * 100/ $total_peso;

                                            ?>               

                                                <tr  >
                                                    <td style="width: 3%;"><input class="checkbox checkft" type="checkbox" name="check"/></td>
                                                    <td style="width: 3%; text-align: center;"><small   ><?php echo $cont++; ?></small></td>
                                                    <td style="width: 30%;  font-size: 16px;"><small   ><?php echo $ata->assunto; ?></small></td> 
                                                    <td style="width: 14%;  font-size: 16px;"><font class="label label-<?php echo $label_status; ?>"><?php echo $status_desc; ?></font></td>
                                                    <td style="width: 15%; "><small  ><?php echo $ata->responsavel; ?></small></td> 
                                                    <td style="width: 5%; "><small   ><?php echo exibirData($ata->data_pa); ?></small></td>
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
                                                    <td style="width: 15%;">
                                                        <a class="btn bg-primary" title="Plano de Ação"  href="<?= site_url('project/plano_acao_detalhes/' .$ata->id); ?>"><i class="fa fa-folder-open"></i>  Abrir </a>
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
    </section>
    <!-- /.content -->
 


<div  class="modal fade" id="modal-insert">
          <div style="width: 800px; " class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Empresa'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/empresas", $attrib); ?>
        <div class="modal-body">
           

         

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        <?= lang("Tipo", "tipo"); ?>
                        <select name="tipo" class="form-control ">
                            <option value="Jurídica">Jurídica</option>
                            <option value="Física">Física</option>
                        </select>
                    </div>
                    <div class="form-group company">
                        <?= lang("Empresa *", "company"); ?>
                        <?php echo form_input('company', '', 'class="form-control tip" maxlength="300" id="company" required="true" data-bv-notempty="true"'); ?>
                    </div>
                    
                    
                    
                  
                    
                </div>
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>
        </div>

          
 
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