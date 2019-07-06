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
                           <a  title="Cadastrar Nova Tarefa" class="btn btn-default pull-right" href="<?= site_url('welcome/novaTarefa/90/72/tarefas'); ?>" data-toggle="modal" data-target="#myModal">  
                           <i class="fa fa-plus"></i>   Nova Tarefa
                           </a> 
                         
                        </div>
                    </span>
                </div>
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
                                    <table id="tarefas" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="height: 30px;">
                                   <th style="width:5%;" >-</th>
                                    <th style="width:50%;" ><?php echo $this->lang->line("Descrição"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("Início Previsto"); ?></th>
                                    <th style="width:10%;"><?php echo $this->lang->line("Término Previsto"); ?></th>
                                    <th style="width:5%;  text-align: center;" ><?php echo $this->lang->line("Status"); ?></th>
                                    
                                    <th style="width:15%;  text-align: center;"><?php echo $this->lang->line("Opções"); ?></th>
                                </tr>
                                </thead>
                                    <tbody>
                                         <?php
                                            $wu4[''] = '';
                                            $cont_tar = 1;
                                            foreach ($tarefas as $ata) {

                                               // $gerente_dados = $this->site->geUserByID($projeto->gerente_area);
                                               // $coordenador_dados = $this->site->geUserByID($projeto->gerente_edp);
                                               // $analista_dados = $this->site->geUserByID($usuario);
                                               $status = $ata->status;
                                               if($status == 0){
                                                   $status_desc = 'Aberto';
                                                   $bg = "bg-orange";
                                               }else if($status == 1){
                                                   $status_desc = 'Concluído';
                                                   $bg = "bg-green";
                                               }
                                               
                                               $dateBR = preg_replace( '/([0-9]+)-([0-9]+)-([0-9]+)/', '$3/$2/$1 -', $ata->data_criacao );
                                            ?>               

                                                <tr  >

                                                    
                                                    <td style="width: 5%; text-align: center;"><small   ><?php echo (int)$cont_tar++; ?></small></td>
                                                    <td style="width: 50%;  font-size: 14px;"><small   ><?php echo $ata->descricao; ?></small></td> 
                                                    <td style="width: 10%; text-align: center;"><small  ><?php echo date('d/m/Y', strtotime($ata->data_inicio)); ?></small></td> 
                                                    <td style="width: 10%; text-align: center;"><small  ><?php echo date('d/m/Y', strtotime($ata->data_termino)); ?></small></td> 
                                                    <td style="width: 5%; text-align: center;"><small class="label pull-right <?php echo $bg; ?>" ><?php echo $status_desc; ?></small></td> 
                                                    
                                                    <td style="width: 15%;">
                                                        <div class="text-center"><div class="btn-group text-left">
                                                                <button style="color:#ffffff" type="button" class="btn btn-default btn-xs bg-blue dropdown-toggle" data-toggle="dropdown">
                                                            Selecione <span class="caret"></span></button>
                                                            <ul class="dropdown-menu pull-right" role="menu">
                                                             <li><a title="Concluir Tarefa" data-toggle="modal" data-target="#myModal" href="<?= site_url('welcome/ConcluirTarefas/90/'.$ata->id.'/72/tarefas'); ?>"><i class="fa fa-check"></i>  Concluir </a></li>    
                                                            <li><a title="Editar Registro" data-toggle="modal" data-target="#myModal" href="<?= site_url('welcome/editarCadastro/90/'.$ata->id.'/72/tarefas'); ?>"><i class="fa fa-edit"></i>  Editar </a></li>
                                                            <li><a href="<?= site_url('welcome/deletarCadastro/90/'.$ata->id.'/72/tarefas'); ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i>Deletar</a></li>
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
 
    $('#tarefas').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'info'        : true,
      'autoWidth'   : true,
      'iDisplayLength': <?=$Settings->rows_per_page?>
    })
  })
</script>