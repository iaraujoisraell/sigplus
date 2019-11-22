<style>
        .agenda {}
        /* Dates */
        
        .agenda .agenda-date {
            width: 170px;
        }
        
        .agenda .agenda-date .dayofmonth {
            width: 40px;
            font-size: 36px;
            line-height: 36px;
            float: left;
            text-align: right;
            margin-right: 10px;
        }
        
        .agenda .agenda-date .shortdate {
            font-size: 0.75em;
        }
        /* Times */
        
        .agenda .agenda-time {
            width: 140px;
        }
        /* Events */
        
        .agenda .agenda-events {}
        
        .agenda .agenda-events .agenda-event {}
        
        @media (max-width: 767px) {}
    </style>
    <!-- /.col -->
    <?php 
    $empresa = $this->session->userdata('empresa');
    ?>
    <div class="col-md-12">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('<?= $this->session->userdata('foto_capa') ? $assets . '../../../assets/uploads/'.$empresa.'/avatars/' . $this->session->userdata('foto_capa') : $assets . 'bi/dist/img/photo1.png' ?>') center center; background-repeat: no-repeat;  width: 100%; ">
              <h3 class="widget-user-username"><?php echo $dados_user->first_name; ?></h3>
              <h5 class="widget-user-desc"><?php echo $dados_user->cargo; ?></h5>
            </div>
            <div class="widget-user-image">
                <img style="width: 88px; height: 88px;" class="img-circle" src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/'.$empresa.'/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
              <?php 
              $qtde_projetos_users = $this->networking_model->getQuantidadeProjetosEquipe();
              $qtde_projeto = $qtde_projetos_users->quantidade_projetos;
              ?>    
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?php echo $qtde_projeto; ?></h5>
                    <span class="description-text">PROJETO<?php if($qtde_projeto > 1){ ?>S <?php } ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <?php 
              $qtde_acoes_users = $this->networking_model->getQuantidadeAcoesEmpresa();
              $qtde_acao = $qtde_acoes_users->quantidade_acao;
              ?>  
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?php echo $qtde_acao; ?></h5>
                    <span class="description-text"><?php if($qtde_projeto > 1){ ?>AÇÕES<?php }else{ echo 'AÇÃO'; } ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <?php 
              $qtde_tarefas_users = $this->networking_model->getQuantidadeTarefaUserEmpresa();
              $qtde_tarefa = $qtde_tarefas_users->quantidade_tarefa;
              ?>  
                <div class="col-sm-4">
                  <div class="description-block">
                    <h5 class="description-header"><?php echo $qtde_tarefa; ?></h5>
                    <span class="description-text">TAREFA<?php if($qtde_tarefa > 1){ ?>S <?php } ?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
    <!-- /.col -->
    <!-- Main content -->
    <section class="content">  
        
        <div class="row">
            <div class="col-md-12">
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
        
      <div class="row">
        
        
         <?php 
         // TAREFAS ABERTAS
              $qtde_tarefas_aberta_users = $this->networking_model->getQuantidadeTarefaAbertasUserEmpresa();
              $qtde_tarefa_aberta = $qtde_tarefas_aberta_users->quantidade_tarefa_aberta;
              ?>  
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $qtde_tarefa_aberta; ?></h3>

              <p>Tarefas Abertas</p>
            </div>
            <div class="icon">
              <i class="ion ion-document-text"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
         <!-- ./col -->
          <?php 
         // AÇÕES PENDENTES
            $qtde_acoes_pendentes_users = $this->networking_model->getQuantidadeAcoesPendenteUserEmpresa();
            $qtde_acao_pendente = $qtde_acoes_pendentes_users->qtde_acoes_pendentes;
            
            // AÇÕES AGUARDANDO VALIDAÇÃO
            $qtde_acoes_aguardando_users = $this->networking_model->getQuantidadeAcoesAguardandoValidacaoUserEmpresa();
            $qtde_acao_aguardando = $qtde_acoes_aguardando_users->qtde_acoes_aguardando;
            
            $total_acao_pendente = $qtde_acao_pendente + $qtde_acao_aguardando;
          ?>
         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $total_acao_pendente; ?></h3>

              <p>Ações Pendentes </p>
            </div>
            <div class="icon">
              <i class="ion ion-clock"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col --> 
        <?php 
         // AÇÕES ATRASADAS
            $qtde_acoes_atrasadas_users = $this->networking_model->getQuantidadeAcoesAtrasadasUserEmpresa();
            $qtde_acao_atrasada = $qtde_acoes_atrasadas_users->qtde_acoes_atrasadas;
          ?>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $qtde_acao_atrasada; ?></h3>

             <p>Ações Atrasadas</p>
            </div>
            <div class="icon">
              <i class="ion ion-alert-circled"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <?php 
         // AÇÕES CONCLUÍDAS
            $qtde_acoes_concluidas_users = $this->networking_model->getQuantidadeAcoesConcluidasUserEmpresa();
            $qtde_acao_concluida = $qtde_acoes_concluidas_users->qtde_acoes_concluidas;
          ?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $qtde_acao_concluida; ?><sup style="font-size: 20px"></sup></h3>

              <p>Ações Concluídas</p>
            </div>
            <div class="icon">
              <i class="ion ion-checkmark-circled"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        
      </div>
      <br>
        <div class="row">
            <div class="col-md-6">
                 <!-- MINHA AGENDA -->
                <div class="box box-widget">

                        <div class="box box-warning">
                            <div class="box-header">
                              <i class="fa fa-calendar"></i>

                              <h3 class="box-title">Meus Compromissos <small>Período de 7 dias</small></h3>


                            </div>
                            <div class="box-header">
                            <div class="agenda">
                                <div class="table-responsive">
                                     <table class=" table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                              
                                                <th>Data</th>
                                                <th>Hora</th>
                                                <th>Descricao</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Single event in a single day -->
                                            <?php
                                            $cont_agenda = 1;
                                            $dataHoje = date('Y-m-d');
                                            
                                            $miinhas_agendas = $this->networking_model->getMArcosProjetoByProjetoEmpresa();
                                             foreach ($miinhas_agendas as $agenda) {
                                                $cont_agenda++;
                                               $data_inicio_evento = $agenda->data_prevista;
                                               $hora_inicio_evento = $agenda->hora_inicio;
                                               $hora_fim_evento = $agenda->hora_fim;
                                               $descricao = $agenda->descricao;
                                               $detalhe = $agenda->projeto;
                                               $tipo_invite = $agenda->tipo_invite;
                                                //echo $data_inicio_agenda.'<br>';
                                               
                                               $agenda_marco = $agenda->marco;
                                               $agenda_convite = $agenda->invite;
                                               $agenda_feriado = $agenda->feriado;
                                               
                                               if($agenda_marco){
                                                   $tipo = "Marco Projeto";
                                                   $bg = "blue-active";
                                               }else if($agenda_convite){
                                                   $tipo = $tipo_invite;
                                                   $bg = "orange-active";
                                               }else if($agenda_feriado){
                                                   $tipo = "Feriado";
                                                   $bg = "purple-active";
                                               }
                                               
                                                $partes_data_inicio = explode("-", $data_inicio_evento);
                                                $ano = $partes_data_inicio[0];
                                                $mes = $partes_data_inicio[1];
                                                $dia = $partes_data_inicio[2];

                                                $partes_hora_inicio = explode(":", $hora_inicio_evento);
                                                $hora = $partes_hora_inicio[0];
                                                $min = $partes_hora_inicio[1];
                                                $seg = $partes_hora_inicio[2];

                                                $hora_inicio = $hora . ':' . $min;
                                                
                                                $partes_hora_fim = explode(":", $hora_fim_evento);
                                                $horaf = $partes_hora_fim[0];
                                                $minf = $partes_hora_fim[1];
                                                $segf = $partes_hora_fim[2];
                                                
                                                $hora_fim = $horaf . ':' . $minf;

                                                $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                                                $diasemana_numero = date('w', strtotime($data_inicio_evento));


                                                setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
                                                date_default_timezone_set('America/Sao_Paulo');
                                                $monthNum = $mes;
                                                $dateObj = DateTime::createFromFormat('!m', $monthNum);
                                                $monthName = $dateObj->format('F');
                                                 
                                           
                                                    if($cont_agenda > 1){
                                                    ?>
                                            <tr title="<?php echo strip_tags($detalhe); ?>"  >
                                                            <td class="agenda-date" class="active"  >
                                                                <div class="dayofmonth"><?php if($data_inicio_evento == $dataHoje){ ?> <b><?php echo $dia; ?></b> <?php }else { ?><?php echo $dia; }?>  </div>
                                                                <div class="dayofweek">  <?php if($data_inicio_evento == $dataHoje){  echo '<b>'. $diasemana[$diasemana_numero].'</b>'; }else{ echo  $diasemana[$diasemana_numero]; } ?></div>
                                                                <div class="shortdate text-muted" ><?php if($data_inicio_evento == $dataHoje){ echo '<b>'.substr($monthName, 0, 3).'</b>'; }else{echo substr($monthName, 0, 3); } ?>, <?php echo $ano; ?> </div>
                                                               
                                                            </td>
                                                            <td class="agenda-time">
                                                                <?php if($hora){ echo $hora_inicio.'h - '.$hora_fim.'h'; }else{ echo 'Dia Todo';} ?>
                                                            </td>
                                                            <td  class="agenda-events">
                                                                <div class="agenda-event">
                                                                    <label class="label bg-<?php echo $bg; ?>"><?php echo $tipo; ?></label> <br>
                                                                    <?php echo $descricao; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                }
                                                
                                                // FIM MINHA AGENDA
                                            ?>
                                             
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                             </div>
                            <div class="box-footer clearfix">
                              <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Novo Compromisso</a>
                              <a href="<?= site_url('welcome/calendario/0/73'); ?>" class="btn btn-sm btn-default btn-flat pull-right">Calendário</a>
                            </div>
                        </div>
                    </div>    
                
                
            <!-- MINHAS TAREFAS -->
            <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Minhas Tarefas</h3>

              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list">
                
                  <?php
                  
                    $wu4[''] = '';
                    $cont = 1;
                    $tarefas = $this->networking_model->getLastTarefas();
                    foreach ($tarefas as $tarefa) {
                  
                    $status = $tarefa->status;
                       if($status == 0){
                           $status_desc = 'Aberto';
                           $bg = "warning";
                           $fa = "clock-o";
                       }else if($status == 1){
                           $status_desc = 'Concluído';
                           $bg = "success";
                            $fa = "check";
                       }
                                               
                  ?>
                  
                  <li>
                      
                  <span class="text" title="<?php echo 'Início Previsto : '. date('d/m/Y', strtotime($tarefa->data_inicio)) . '. Término Previsto '.date('d/m/Y', strtotime($tarefa->data_termino)); ?>"><?php echo $tarefa->descricao; ?></span>
                  <small class="label label-<?php echo $bg; ?>"><i class="fa fa-<?php echo $fa; ?>"></i> <?php echo $status_desc; ?></small>
                  <div class="tools">
                    <a title="Concluir Tarefa" href="<?= site_url('welcome/ConcluirTarefas/90/'.$tarefa->id.'/72/index'); ?>" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success btn-flat pull-left"> <i class="fa fa-check"></i></a>  
                    <a title="Editar Cadastro" href="<?= site_url('welcome/editarCadastro/90/'.$tarefa->id.'/72/index'); ?>" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-warning btn-flat pull-left"> <i class="fa fa-edit"></i></a>
                    <a title="Deletar Cadastro" href="<?= site_url('welcome/deletarCadastro/90/'.$tarefa->id.'/72/index'); ?>" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-danger btn-flat pull-left"> <i class="fa fa-trash-o"></i></a>
                  </div>
                </li>
                  <?php 
                    }
                    ?>
                  
                
                
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="<?= site_url('welcome/novaTarefa/90/72/index'); ?>" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-info btn-flat pull-left">Nova Tarefa</a>
              <a href="<?= site_url('welcome/tarefas/90/72/tarefas'); ?>" class="btn btn-sm btn-default btn-flat pull-right">Visulizar Todas</a>
            </div>
          </div>
          <!-- /.box -->
          
            </div>
              
            <div class="col-md-6">
             <div class="col-md-12">    
                <div class="row">
                
                    <!-- /.box -->
                  <!-- POSTAGEM -->
                <div class="box box-widget">
                <div class="box box-success">  
                <div class="box-header with-border">
                  <div class="user-block">
                    <img class="img-circle" src="<?= $assets ?>bi/dist/img/user1-128x128.jpg" alt="User Image">
                    <span class="username"><a href="#">Jonathan Burke Jr.</a></span>
                    <span class="description">Shared publicly - 7:30 PM Today</span>
                  </div>
                  <!-- /.user-block -->
                  <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Mark as read">
                      <i class="fa fa-circle-o"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <img class="img-responsive pad" src="<?= $assets ?>bi/dist/img/photo2.png" alt="Photo">

                  <p>I took this photo this morning. What do you guys think?</p>
                  <button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Share</button>
                  <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
                  <span class="pull-right text-muted">127 likes - 3 comments</span>
                </div>
                <!-- /.box-body -->
                <div class="box-footer box-comments">
                  <div class="box-comment">
                    <!-- User image -->
                    <img class="img-circle img-sm" src="<?= $assets ?>bi/dist/img/user3-128x128.jpg" alt="User Image">

                    <div class="comment-text">
                          <span class="username">
                            Maria Gonzales
                            <span class="text-muted pull-right">8:03 PM Today</span>
                          </span><!-- /.username -->
                      It is a long established fact that a reader will be distracted
                      by the readable content of a page when looking at its layout.
                    </div>
                    <!-- /.comment-text -->
                  </div>
                  <!-- /.box-comment -->
                  <div class="box-comment">
                    <!-- User image -->
                    <img class="img-circle img-sm" src="<?= $assets ?>bi/dist/img/user4-128x128.jpg" alt="User Image">

                    <div class="comment-text">
                          <span class="username">
                            Luna Stark
                            <span class="text-muted pull-right">8:03 PM Today</span>
                          </span><!-- /.username -->
                      It is a long established fact that a reader will be distracted
                      by the readable content of a page when looking at its layout.
                    </div>
                    <!-- /.comment-text -->
                  </div>
                  <!-- /.box-comment -->
                </div>
                <!-- /.box-footer -->
                <div class="box-footer">
                  <form action="#" method="post">
                    <img class="img-responsive img-circle img-sm" src="<?= $assets ?>bi/dist/img/user4-128x128.jpg" alt="Alt Text">
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                      <input type="text" class="form-control input-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                </div>
                <!-- /.box-footer -->
                </div>
              </div>
              <!-- /.box -->   
                </div>
            </div>        
            </div>
        </div>
        
            <style>
             .div_chat_home{ 
              position: fixed;
              bottom: 0;
              right: 0;
              width: 400px;
              background-color: white;

            }
            </style>  
            
            <div class="box-default">
              <div class="div_chat_home">
                  <div id="exibe_chat">
                     
                      <script>exibe_chat()</script>
                  </div>
                  
              </div>
            </div>
          <!-- /.box -->
          
            
          
            
       
        
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
 
    <script>
  $(function () {
   
    $('#minha_agenda_home').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>