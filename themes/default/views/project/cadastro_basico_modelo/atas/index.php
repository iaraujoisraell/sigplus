
    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
    <div class="col-lg-12">
    <div class="box">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo 'ATAS'; ?>
            <small><?php echo 'Lista de Atas'; ?> </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Atas</li>
        </ol>
    </section>
    <div class="box-header">
        <span class="pull-right-container">
            <div class=" clearfix no-border">
                <a title="Abrir Nova Ata" class="btn btn-primary pull-right" href="<?= site_url('project/novaAta'); ?>" >  
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
                            <table style="width: 100%;" id="lista_atas_project" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                             <th style="width:5%;" ><?php echo $this->lang->line("ATA"); ?></th>
                            <th style="width:10%;"><?php echo $this->lang->line("DT ATA"); ?></th>
                            <th style="width:20%;"><?php echo $this->lang->line("Assunto"); ?></th>
                            <th style="width:35%;"><?php echo $this->lang->line("Pauta"); ?></th>
                            <th style="width:5%;"><?php echo $this->lang->line("Tipo"); ?></th>
                            <th style="width:5%;"><?php echo $this->lang->line("Resp."); ?></th>
                            <th style="width:5%;"><?php echo $this->lang->line("Status"); ?></th>
                            <th style="width:5%;"><?php echo $this->lang->line("Ações"); ?></th>
                            <th style="width:15%;  text-align: center;" ><?php echo $this->lang->line("Andamento"); ?></th>
                            <th style="width:15%;  text-align: center;"><?php echo $this->lang->line("Menu"); ?></th>
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

                                      $total_peso_ata = $total_peso_atrasado + $total_peso_pendente + $total_peso_concluido;
                                      $diferenca_peso = $total_peso - $total_peso_ata;


                                       $status = $ata->status;

                                        if($status == 0){
                                            $status_ata = 'Aberto';
                                            $label = "warning";
                                        }else 
                                        if($status == 1){
                                            $status_ata = 'Ativo';
                                            $label = "success";
                                        }

                                        if($ata->tipo == "REUNIÃO"){
                                            $label_tipo = "default";
                                        }else if($ata->tipo == "REUNIÃO CONTÍNUA"){
                                            $label_tipo = "primary";
                                        }

                                        $porc_concluido = $total_peso_concluido * 100/ $total_peso;
                                        $porc_pendente  = $total_peso_pendente * 100/ $total_peso;
                                        $porc_atrasado  = $total_peso_atrasado * 100/ $total_peso;

                                        $diferenca_peso_porc = $diferenca_peso * 100/ $total_peso;

                                    ?>               

                                        <tr  >

                                            <td style="width: 5%; text-align: center; "><small   ><?php echo $ata->sequencia; ?></small></td>
                                            <td style="width: 10%;  "><small   ><?php echo exibirData(substr($ata->data_ata, 0, 10)); ?></small></td>
                                            <td style="width: 20%;  "><small   ><?php echo $ata->assunto; ?></small></td> 
                                            <td style="width: 35%;  font-size: 12px;"><small   ><?php echo $ata->pauta; ?></small></td> 
                                            <td style="width: 5%; font-size: 12; text-align: center;"><small class="label label-<?php echo $label_tipo; ?>" ><?php echo $ata->tipo; ?></small></td> 
                                            <td style="width: 5%;font-size: 12; "><small  ><?php echo $ata->responsavel_elaboracao; ?></small></td> 
                                            <td style="width: 5%; font-size: 12; text-align: center;"><small class="label label-<?php echo $label; ?>" ><?php echo $status_ata; ?></small></td> 
                                            <td style="width: 5%; font-size: 12; text-align: center;"><b><?php echo $total_acao; ?></b></td> 
                                            <td style="width: 15%;font-size: 12; ">
                                                <?php if($total_acao > 0) { ?>
                                                <div class="progress">
                                                  <div title="Ações Concluídas" class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido;  ?>%">
                                                   <?php if($porc_concluido != 100){ echo  substr($porc_concluido,0,2); }else{ echo $porc_concluido; } ?> % Concluído
                                                  </div>
                                                  <div title="Ações Pendentes" class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente;  ?>%">
                                                   <?php if($porc_pendente != 100){ echo  substr($porc_pendente,0,2); }else{ echo $porc_pendente; } ?>% Pendente
                                                  </div>
                                                  <div title="Ações Atrasadas" class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php  echo $porc_atrasado;  ?>%">
                                                   <?php if($porc_atrasado != 100){ echo  substr($porc_atrasado,0,2); }else{ echo $porc_atrasado; } ?>% Atrasado
                                                  </div>
                                                   <?php if($diferenca_peso_porc){ ?>
                                                    <div title="Ações em Aberto" class="progress-bar bg-gray" role="progressbar" style="width:<?php  echo $diferenca_peso_porc;  ?>%">
                                                   <?php if($diferenca_peso_porc != 100){ echo  substr($diferenca_peso_porc,0,2); }else{ echo $diferenca_peso_porc; } ?>% Aberto
                                                  </div>
                                                   <?php } ?> 
                                                </div>
                                                <?php } ?>
                                            </td> 
                                            <td style="width: 15%; font-size: 12;">
                                                <a title="Exibir ATA" class="btn btn-primary" href="<?= site_url('atas/exibir_ata/'.$ata->id); ?>"><i class="fa fa-file-text-o"></i>  ATA </a>
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
 
    $('#lista_atas_project').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'info'        : true,
      'autoWidth'   : false,
      'iDisplayLength': <?=$Settings->rows_per_page?>,
       "order": [[ 0, "desc" ]]
    })
  })
</script>