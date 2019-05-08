
    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
    <div class="col-lg-12">
    <div class="box">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $titulo; ?>
            <small><?php echo $descricao_titulo; ?> </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Atas</li>
        </ol>
    </section>
    <div class="box-header">
        <span class="pull-right-container">
            <div class=" clearfix no-border">
                <a title="Abrir Nova Ata" class="btn btn-default pull-right" href="<?= site_url('project/novaAta/' . $tabela_id . "/" . $menu_id); ?>" >  
                    <i class="fa fa-plus"></i>  Nova Ata 
                </a> 
            </div>
        </span>
    </div>
    </div>
    </div>    
    <br>
    <div class="col-lg-12">
            <div class="row">
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
    <!-- Main content -->
    <section  class="content">
        <div class="col-lg-12">
            <div class="row">
                
                
              
        
                        <div class="box">
                          
                              <br>
                            <div class="table-responsive">
                                <div class="box-body">
                                    <table style="width: 100%;" id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                    <th style="width:1%; text-align: center;"><input class="checkbox checkft" type="checkbox" name="check"/></th>
                                    <th style="width:5%;" ><?php echo $this->lang->line("ATA"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("DT ATA"); ?></th>
                                    <th style="width:34%;"><?php echo $this->lang->line("Pauta"); ?></th>
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
                                            $wu4[''] = '';
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

                                                    <td style="width: 1%;  font-size: 12;"><input class="checkbox checkft" type="checkbox" name="check"/></td> 
                                                    <td style="width: 5%; text-align: center; font-size: 12;"><small   ><?php echo $ata->sequencia; ?></small></td>
                                                    <td style="width: 10%; font-size: 12; "><small   ><?php echo exibirData(substr($ata->data_ata, 0, 10)); ?></small></td>
                                                    <td style="width: 34%;  font-size: 12px;"><small   ><?php echo $ata->pauta; ?></small></td> 
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
                                                        <div class="text-center"><div class="btn-group text-left">
                                                                <button style="color:#ffffff" type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                                            Selecione <span class="caret"></span></button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                            <li><a title="Editar Registro"  href="<?= site_url('atas/plano_acao/'.$ata->id); ?>"><i class="fa fa-edit"></i>  ATA </a></li>
                                                            <li><a title="Gantt das ações da Ata"  href="<?= site_url('project/ganttPlano/1/' .$ata->id); ?>"><i class="fa fa-tasks"></i>  GANTT </a></li>
                                                            <li><a href="#"><i class="fa fa-trash"></i>Cancelar Ata</a></li>
                                                            <li><a href="#"><i class="fa fa-download"></i>Download PDF</a></li>  
                                                            </ul>
                                                        </div>
                                                        </div>
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
    
         <script>
  $(function () {
  $('#example1').DataTable({
      "order": [[ 0, "desc" ]]
    })
  })
</script>