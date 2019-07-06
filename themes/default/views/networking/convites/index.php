  <?php 
    $usuario =  $this->session->userdata('user_id'); 
    $tarefa = 'tarefas';
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
                            <li class="active">Tarefas</li>
                          </ol>
                        </section>
                    
                     <div class="box-header">
                    <span class="pull-right-container">
                       <div class=" clearfix no-border">
                           
                         
                        </div>
                    </span>
                </div>
        <br><br>
    </div>
    </div>
<br>
    <section  class="content">
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
        
                        <div class="box">
                           
                              <br>
                            <div class="table-responsive">
                                <div class="box-body">
                                    <table id="convites" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                    <th style="width:2%;" >-</th>
                                    <th style="width:20%;" ><?php echo $this->lang->line("Enviado Por"); ?></th>
                                    <th style="width:28%;" ><?php echo $this->lang->line("Assunto"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("Data"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("Hora Início"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("Hora Término"); ?></th>
                                    <th style="width:5%;  text-align: center;" ><?php echo $this->lang->line("Confirmar Presença?"); ?></th>
                                    <th style="width:15%;  text-align: center;"><?php echo $this->lang->line("Detalhes"); ?></th>
                                </tr>
                                </thead>
                                    <tbody>
                                         <?php
                                            $wu4[''] = '';
                                            $cont_tar = 1;
                                            foreach ($convites as $convite) {

                                                 $confirmacao = $convite->confirmacao;
                                                        
                                                    if ($confirmacao){
                                                        if($confirmacao == 3){
                                                            $bt = "bg-orange";
                                                            $bt_texto = "aguardando";
                                                        }else if($confirmacao == 2){
                                                            $bt = "bg-blue";
                                                            $bt_texto = "CIENTE";
                                                        }else if($confirmacao == 1){
                                                            $bt = "bg-green";
                                                            $bt_texto = "SIM";
                                                        }else if($confirmacao == 0){
                                                            $bt = "bg-gray";
                                                            $bt_texto = "NÃO";
                                                        }else {
                                                            $bt = "primary";
                                                            $bt_texto = "";
                                                        }
                                                        $tipo = $convite->tipo;
                                                        if($tipo == 'REUNIÃO'){
                                                            $texto_label = 'bg-blue'; 
                                                        }else if($tipo == 'TREINAMENTO'){

                                                        }
                                                    }else{
                                                         $bt = "primary";
                                                        $bt_texto = "";
                                                    }
                                                    
                                                    $users_dados = $this->site->geUserByID($convite->user_origem);
                                            ?>               
                                                <tr  >
                                                    <td style="width: 2%; text-align: center;"><small ><?php echo (int)$cont_tar++; ?></small></td>
                                                    <td style="width: 20%; text-align: center;"><small  ><?php echo $users_dados->first_name; ?></small></td>
                                                    <td style="width: 28%;  font-size: 14px;"><small class="label <?php echo $texto_label; ?> " > <?php echo $convite->tipo; ?></small> <p><small ><?php echo $convite->titulo; ?></small></p></td> 
                                                    <td style="width: 10%; text-align: center;"><small  ><?php echo date('d/m/Y', strtotime($convite->data_evento)); ?></small></td> 
                                                    <td style="width: 10%; text-align: center;"><small  ><?php echo $convite->hora_inicio; ?></small></td> 
                                                    <td style="width: 10%; text-align: center;"><small  ><?php echo $convite->hora_fim; ?></small></td> 
                                                    <td style="width: 5%; text-align: center;"><small class="label pull-right <?php echo $bt; ?> " ><?php echo $bt_texto; ?></small></td> 
                                                    <td style="width: 15%; text-align: center;"><a class="btn btn-primary" title="Abrir Convite" data-toggle="modal" data-target="#myModal" href="<?= site_url('welcome/abrirConvite/'.$convite->id); ?>">  ABRIR </a></td>    
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
 
    $('#convites').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'info'        : true,
      'autoWidth'   : true,
      'iDisplayLength': <?=$Settings->rows_per_page?>
    })
  })
</script>
  
 