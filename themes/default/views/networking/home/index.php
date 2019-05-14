    <?php
     // $this->load->view($this->theme . 'header_networking_home', $meta);
  
      $usuario = $this->session->userdata('user_id');
      $dados_user = $this->site->getUser($usuario);   
        
    ?>
  
        <BR>
    <script>
        /*
        function exibe_chat() {
            
              $.ajax({
                type: "POST",
                url: "themes/default/views/networking/colaboradores/exibe_chat.php",
               
                success: function(data) {
                  $('#exibe_chat').html(data);
                 
                }

              });

            }
            */
            
    </script>
    
    
    <div id="conteudo_home">
    
    
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
    <div class="col-md-12">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('<?= $this->session->userdata('foto_capa') ? $assets . '../../../assets/uploads/avatars/' . $this->session->userdata('foto_capa') : $assets . 'bi/dist/img/photo1.png' ?>') center center; background-repeat: no-repeat;  width: 100%; ">
              <h3 class="widget-user-username"><?php echo $dados_user->first_name; ?></h3>
              <h5 class="widget-user-desc"><?php echo $dados_user->cargo; ?></h5>
            </div>
            <div class="widget-user-image">
                <img style="width: 88px; height: 88px;" class="img-circle" src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" alt="User Avatar">
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
            <a href="<?= site_url('welcome/tarefas/90/72'); ?>" class="small-box-footer">Acessar<i class="fa fa-arrow-circle-right"></i></a>
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
            <a href="<?= site_url('welcome/minhasAcoes/2'); ?>" class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
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
            <a href="<?= site_url('welcome/minhasAcoes/3'); ?>" class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
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
            <a href="<?= site_url('welcome/minhasAcoes/1'); ?>" class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
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
                                                                    <label class="label bg-<?php echo $bg; ?>"><?php echo strtoupper($tipo); ?></label> <br>
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
                                <a href="<?= site_url('welcome/novoCompromisso'); ?>" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-info btn-flat pull-left">Novo Compromisso</a>
                             
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
                  <small class="label label-<?php echo $bg; ?>"><i class="fa fa-<?php echo $fa; ?>"></i> <?php echo $status_desc; ?>  </small>
                  <br><small class="label label-primary"><?php echo 'Conclusão para : '. date('d/m/Y', strtotime($tarefa->data_termino)); ?> </small>
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
                
                
                        <div class="box box-success">  
                            <div class="box-header">
                              <i class="fa fa-picture-o"></i>

                              <h3 class="box-title">Publicações Institucionais <small>Últimos 3</small></h3>


                            </div>
                        <div class="row">
                            <div class="col-md-12">    
                                <div class="active tab-pane" id="mural">

                        <script type="text/javascript">
                            function exibe_like_post(post, usuario) {
                                     $.ajax({
                                    type: "POST",
                                    url: "themes/default/views/networking/mural/exibe_like_postagem_profile.php",
                                    data: {
                                          usuario: usuario,
                                          post: post
                                        },
                                    success: function(data) {
                                      $('#div_like'+post).html(data);
                                    }
                                  });
                                }        

                              function oculta_post(post, usuario) {
                                     $.ajax({
                                    type: "POST",
                                    url: "themes/default/views/networking/mural/oculta_post.php",
                                    data: {
                                          usuario: usuario,
                                          post: post
                                         // comentario:  document.getElementById("comentario").value 
                                        },
                                    success: function(data) {
                                      $('#div_like'+post).html(data);
                                    }
                                  });
                                }
                        </script>
                        <meta charset="utf-8">

                    <?php 
                            $usuario =  $this->session->userdata('user_id');
                            $empresa =  $this->session->userdata('empresa');

                            $posts_profile = $this->networking_model->getLastPostInstitucionalByEmpresa();
                             foreach ($posts_profile as $posts) {    
                                 
                                 $dados_user = $this->site->getUserSetorByUser($posts->user_de);
                                 
                                 $user_id = $dados_user->user_id; 
                                 $nome = $dados_user->first_name; 
                                 $avatar = $dados_user->avatar;
                                 $gender = $dados_user->gender;
                                 $setor_id = $dados_user->setor_id;

                                 // dados post
                                 $data_postagem = $posts->data_postagem;
                                 $texto = $posts->descricao;
                                 $post_id = $posts->id;

                                 if($user_id != $usuario){
                                     $descricao = "Publicou";
                                 }else{
                                     $descricao = "Publicou";
                                 }

                                 /***SUPORTE***
                                $empresa = $this->session->userdata('empresa');
                                $dados_empresa = $this->owner_model->getEmpresaById($empresa);
                                $empresa_id = $dados_empresa->id;
                                $suporte = $dados_empresa->suporte;
                                */

                                 // CALCULA A DIFERENÇA ENTRE A DATA DA PUBLICAÇÃO E HOJE
                                 $data_inicial = $data_postagem;
                                 $data_final = $date_hoje;
                                 // Calcula a diferença em segundos entre as datas
                                 $diferenca = strtotime($data_final) - strtotime($data_inicial);
                                 //Calcula a diferença em dias
                                 $dias = floor($diferenca / (60 * 60 * 24)) + 1;

                                 if($dias > 1){
                                     $text_dias = "dias";
                                 }else{
                                     $text_dias = "dia";
                                 }

                                 // Imagens
                                 $qtde_imagem = 0;
                                 $imagem1 = $posts->imagem1; 
                                 if($imagem1){
                                    $qtde_imagem++;
                                    $url_img_post1 =  "assets/uploads/$empresa/posts/$imagem1";
                                 }
                                 $imagem2 = $posts->imagem2; 
                                 if($imagem2){
                                     $qtde_imagem++;
                                     $url_img_post2 =  "assets/uploads/$empresa/posts/$imagem2";
                                 }
                                 $imagem3 = $posts->imagem3; 
                                 if($imagem3){
                                     $qtde_imagem++;
                                     $url_img_post3 = "assets/uploads/$empresa/posts/$imagem3";
                                 }
                                 $imagem4 = $posts->imagem4; 
                                 if($imagem4){
                                     $qtde_imagem++;
                                     $url_img_post4 = "assets/uploads/$empresa/posts/$imagem4";
                                 }
                                 $imagem5 = $posts->imagem5; 
                                 if($imagem5){
                                     $qtde_imagem++;
                                     $url_img_post5 = "assets/uploads/$empresa/posts/$imagem5";
                                 }


                                 $assets = "themes/default/assets/";
                                 $avatar_profile = "assets/uploads/avatars/thumbs/$avatar";
                                 $avatar_genero = "assets/images/$gender".'1'.".png";


                                ?> 

                                    <div class="post clearfix">
                                      <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="<?php if($avatar){ echo $avatar_profile; }else{ echo $avatar_genero; }?> " alt="User Image">
                                            <span class="username">
                                              <a href="<?= site_url('welcome/profile_visitor/'.$user_id); ?>"><?php echo $nome; ?></a>
                                              <?php  if($user_id == $usuario){ ?>
                                              <a style="cursor:pointer;" onclick="oculta_post(<?php echo $post_id; ?>, <?php echo $usuario; ?>)" class="pull-right btn-box-tool"><i title="Remover" class="fa fa-trash"></i></a>
                                              <?php  } ?>
                                            </span>
                                        <span class="description"><?php echo $descricao. ' em '. date('d/m/Y H:i:s', strtotime($data_postagem)); ?> </span>
                                      </div>

                                        <?php
                                        if($qtde_imagem > 0){ 

                                           if($qtde_imagem == 1){  
                                        ?>

                                                <div class="row margin-bottom">
                                                <div class="col-sm-12">
                                                    <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">   
                                                        <img style="width: 100%; height: 400px; " class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                                    </a>
                                                </div>

                                              </div>

                                        <?php 

                                           }else  if($qtde_imagem == 2){

                                                ?>

                                                <div class="row margin-bottom">

                                                    <!-- /.col -->
                                                    <div  class="col-sm-6">
                                                       <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">    
                                                        <img style="width: 100%; height: 400px; " class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                                        </a>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem2"); ?>" data-toggle="modal" data-target="#myModal">   
                                                            <img style=" width: 100%; height: 400px;  " class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                                        </a>
                                                    </div>


                                                </div>

                                            <?php 

                                           }else  if($qtde_imagem == 3){

                                               ?>

                                                    <div class="row margin-bottom">

                                                    <!-- /.col -->
                                                    <div class="col-sm-4">
                                                        <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">    
                                                        <img style=" width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                                        </a>

                                                </div>

                                                    <div class="col-sm-4">
                                                        <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem2"); ?>" data-toggle="modal" data-target="#myModal">       
                                                  <img style=" width: 100%; height: 70%; max-height: 70%;"  class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                                  </a>
                                                </div>

                                                    <div class="col-sm-4">
                                                     <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem3"); ?>" data-toggle="modal" data-target="#myModal">        
                                                  <img style=" width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
                                                  </a>
                                                </div>
                                                <!-- /.col -->

                                                    <!-- /.col -->
                                                  </div>

                                            <?php 

                                           }else  if($qtde_imagem == 4){

                                               ?>

                                                <div  class="row margin-bottom">

                                                <!-- /.col -->
                                                <div class="col-sm-12">
                                                  <div class="row">
                                                    <div class="col-sm-3">
                                                        <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">    
                                                      <img style="width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                                      </a>
                                                    </div>  
                                                      <div class="col-sm-3">
                                                          <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem2"); ?>" data-toggle="modal" data-target="#myModal">    
                                                      <img style="width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                                      </a>
                                                    </div>
                                                    <!-- /.col -->
                                                    <div  class="col-sm-3">
                                                        <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem3"); ?>" data-toggle="modal" data-target="#myModal">  
                                                      <img style="width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
                                                      </a>
                                                    </div>  
                                                    <div class="col-sm-3">
                                                        <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem4"); ?>" data-toggle="modal" data-target="#myModal">  
                                                      <img style="width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post4 ?>" alt="Photo">
                                                      </a>
                                                    </div>
                                                    <!-- /.col -->
                                                  </div>
                                                  <!-- /.row -->
                                                </div>
                                                <!-- /.col -->
                                              </div>

                                        <?php 

                                           }else  if($qtde_imagem == 5){

                                               ?>

                                                <div class="row margin-bottom">
                                                <div class="col-sm-6">
                                                  <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">  
                                                    <img style="width: auto; height: 71%; max-height: 71%" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                                  </a>
                                                </div>
                                                <!-- /.col -->
                                                <div class="col-sm-6">
                                                  <div class="row">
                                                    <div class="col-sm-6">
                                                        <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem2"); ?>" data-toggle="modal" data-target="#myModal">  
                                                            <img style="width: auto; height: 34%; max-height: 34%" class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                                        </a>
                                                      <br>
                                                      <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem3"); ?>" data-toggle="modal" data-target="#myModal">
                                                        <img style="width: auto; height: 34%; max-height: 34%" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
                                                      </a>
                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-sm-6">
                                                        <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem4"); ?>" data-toggle="modal" data-target="#myModal">  
                                                      <img style="width: auto; height: 34%; max-height: 34%" class="img-responsive" src="<?= $url_img_post4 ?>" alt="Photo">
                                                      </a>
                                                      <br>
                                                      <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem5"); ?>" data-toggle="modal" data-target="#myModal">
                                                      <img style="width: auto; height: 34%; max-height: 34%" class="img-responsive" src="<?= $url_img_post5 ?>" alt="Photo">
                                                      </a>
                                                    </div>
                                                    <!-- /.col -->
                                                  </div>
                                                  <!-- /.row -->
                                                </div>
                                                <!-- /.col -->
                                              </div>

                                        <?php 

                                           }



                                        } ?>


                                      <p>
                                        <?php echo $texto; ?>
                                      </p>


                                    </div>

                            <?php     
                            $cont_total++;
                            }   

                            //    }
                        ?>

                      </div>
                            </div>    
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

    </div>
<?php
//$this->load->view($this->theme . 'footer_welcome_home');
?>