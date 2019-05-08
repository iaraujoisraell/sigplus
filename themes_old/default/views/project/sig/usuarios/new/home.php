<html>

   <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<style>
    .agenda {  }

/* Dates */
.agenda .agenda-date { width: 170px; }
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
.agenda .agenda-time { width: 140px; } 


/* Events */
.agenda .agenda-events {  } 
.agenda .agenda-events .agenda-event {  } 

@media (max-width: 767px) {
    
}
</style>   



    
<body class="hold-transition skin-green  sidebar-mini">
<div class="wrapper">

    <div class="content-wrapper">
    
    <?php 
    $usuario =  $this->session->userdata('user_id'); 
    ?>
  
    <!-- Content Header (Page header) -->
     <section class="content-header">
      <h1>
        SIG
        <small>Portal de atividades</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= site_url('welcome/home'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
        
      <div class="row">
        
          
        <!-- Left col -->
        <section class="col-lg-4 " >
            <div class="box box-widget">
                <div class="box box-warning">
                <h3>Publicações</h3>
                <hr>
                    <?php 
                    $equipes = $this->user_model->getAllPostagemByEquipeDistinct($usuario);
                    foreach ($equipes as $post) {
                      $projeto_id =   $post->id_projeto;
                      $projeto =   $post->projeto;

                      $postagens = $this->projetos_model->getAllPostagemByProjeto($projeto_id);

                   

                            $tipo_post = $post->tipo; 
                            $usuario_post = $post->user_id; 
                            $data_post = $post->data_postagem; 

                            $partes = explode(" ", $data_post);
                            $data_postagem = $partes[0];
                            $hora_postagem = $partes[1];

                            $partes_data = explode("-", $data_postagem);
                            $ano2 = $partes_data[0];
                            $mes2 = $partes_data[1];
                            $dia2 = $partes_data[2];

                            $partes_hora = explode(":", $hora_postagem);
                            $hora = $partes_hora[0];
                            $min = $partes_hora[1];
                            $seg = $partes_hora[2];

                            $hora_post = $hora.':'.$min;


                            $titulo = $post->titulo; 
                            $descricao = $post->descricao;

                          $dados_user = $this->site->getUser($usuario_post);
                          $avatar = $dados_user->avatar;
                          $genero = $dados_user->gender;
                          $nome = $dados_user->first_name;

                      if($tipo_post == 1){


                    ?>
                    <div class="box box-widget">
                    <div class="box-header with-border">
                      <div class="user-block">

                           <img class="img-circle" src="<?= $avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar : $assets . 'images/' . $genero . '.png'; ?>" alt="User Image">
                        <span class="username"><a href="#"><?php echo $nome; ?></a></span>
                        <span class="description">Publicado em - <?php echo $dia2.'/'.$mes2.'/'.$ano2.'  '.$hora_post; ?></span>
                      </div>
                      <!-- /.user-block -->
                      <div class="box-tools">

                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>

                      </div>
                      <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <?php if($tipo_post == 1){ ?>

                        <p><?php echo $titulo; ?></p>
                             <a href="<?= site_url('welcome/visualizaImagemHome/'.$post->id_post); ?>" data-toggle="modal" data-target="#myModal"> 
                              
                                <img class="img-responsive pad" src="<?php echo base_url().'assets/uploads/projetos/'.$post->anexo; ?>" alt="Photo">
                               </a>   
                      <p><?php echo $descricao; 
                      
                      /*
                       * <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
                      <span class="pull-right text-muted">127 likes </span>
                       */

                      ?></p>
                      
                        <?php } ?>
                    </div>
                    
                  </div>

                    <?php
                        
                    } 
                } 
                    ?>
                </div>
            </div>   
        </section>
        
        
        
        <section class="col-lg-4 " >
            <!-- /.box -->
            <div class="row">
                <div class="box box-widget">
                    <div class="box box-success">
                        <h3>Agenda</h3>
                        <div class="agenda">
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Projeto</th>
                                            <th>Data</th>
                                            <th>Hora</th>
                                            <th>Descricao</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Single event in a single day -->
                                        <?php
                                        $date_hoje = date('Y-m-d');  
                                        $equipes = $this->user_model->getAllEquipeByUserDistinct($usuario);
                                        foreach ($equipes as $equipe) {
                                            $projeto_id = $equipe->id_projeto;
                                            $projeto = $equipe->projeto;
                                            
                                            if ($cont_equipe == 1) {
                                               // echo $projeto_id.'<br>';
                                            }
                                            
                                            $agendas = $this->user_model->getAgendaProjetos($projeto_id);
                                            $cont_agenda = 1;
                                            foreach ($agendas as $agenda2) {
                                                $cont_agenda++;
                                            }
                                            
                                            if($cont_agenda > 1){
                                            ?> 
                                            <tr>
                                                <td style="font-size: 12px;"  rowspan="<?php echo $cont_agenda; ?>"><?php echo $projeto; ?></td>
                                            </tr>    
                                                    <?php
                                            }
                                                    $agendas = $this->user_model->getAgendaProjetos($projeto_id);
                                                    foreach ($agendas as $agenda) {
                                                        $data_inicio_agenda = $agenda->start;
                                                        $descricao = $agenda->title;
                                                        //echo $data_inicio_agenda.'<br>';

                                                        $partes = explode(" ", $data_inicio_agenda);
                                                        $data_inicio_evento = $partes[0];
                                                        $hora_inicio_evento = $partes[1];

                                                        $partes_data_inicio = explode("-", $data_inicio_evento);
                                                        $ano = $partes_data_inicio[0];
                                                        $mes = $partes_data_inicio[1];
                                                        $dia = $partes_data_inicio[2];

                                                        $partes_hora_inicio = explode(":", $hora_inicio_evento);
                                                        $hora = $partes_hora_inicio[0];
                                                        $min = $partes_hora_inicio[1];
                                                        $seg = $partes_hora_inicio[2];

                                                        $hora_inicio = $hora . ':' . $min;


                                                        $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                                                        $diasemana_numero = date('w', strtotime($data_inicio_evento));


                                                        setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
                                                        date_default_timezone_set('America/Sao_Paulo');
                                                        $monthNum = $mes;
                                                        $dateObj = DateTime::createFromFormat('!m', $monthNum);
                                                        $monthName = $dateObj->format('F');
                                                        
                                                        if($cont_agenda > 1){
                                                        ?>
                                                        <tr <?php if($data_inicio_evento == $date_hoje){ ?> style="background-color : orange; " <?php } ?> >
                                                            <td style="font-size: 10px;" class="agenda-date" class="active" >
                                                                <div class="dayofmonth"><?php echo $dia; ?></div>
                                                                <div class="dayofweek"><?php echo $diasemana[$diasemana_numero]; ?></div>
                                                                <div class="shortdate text-muted"><?php echo substr($monthName, 0, 3); ?>, <?php echo $ano; ?></div>
                                                            </td>
                                                            <td style="font-size: 12px;" class="agenda-time">
                                                                <?php echo $hora_inicio; ?>
                                                            </td>
                                                            <td style="font-size: 10px;" class="agenda-events">
                                                                <div class="agenda-event">

                                                                    <?php echo $descricao; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        }
                                                    }
                                                

                                                        $cont_equipe++;
                                                    }
                                                    ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </section>
            
        <section class="col-lg-4 " >
            <!-- 
            <div class="box box-solid">
                <!-- /.box-header 
                <div class="box-body">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <?php
                        /*
                        $equipes = $this->user_model->getAllEquipeByUser($usuario);
                        $cont_slide = 0;
                        foreach ($equipes as $equipe) {
                            $projeto_id = $equipe->id_projeto;
                            $projeto = $equipe->projeto;

                            $postagens = $this->projetos_model->getAllPostagemByProjeto($projeto_id);

                            if ($postagens) {
                                
                                foreach ($postagens as $post) {

                                    $tipo_post = $post->tipo;
                                    $usuario_post = $post->user_id;
                                    $data_post = $post->data_postagem;

                                    $partes = explode(" ", $data_post);
                                    $data_postagem = $partes[0];
                                    $hora_postagem = $partes[1];

                                    $partes_data = explode("-", $data_postagem);
                                    $ano2 = $partes_data[0];
                                    $mes2 = $partes_data[1];
                                    $dia2 = $partes_data[2];

                                    $partes_hora = explode(":", $hora_postagem);
                                    $hora = $partes_hora[0];
                                    $min = $partes_hora[1];
                                    $seg = $partes_hora[2];

                                    $hora_post = $hora . ':' . $min;


                                    $titulo = $post->titulo;
                                    $descricao = $post->descricao;

                                    $dados_user = $this->site->getUser($usuario_post);
                                    $avatar = $dados_user->avatar;
                                    $genero = $dados_user->gender;
                                    $nome = $dados_user->first_name;

                                    if ($tipo_post == 2) {
                                        
                                    
                        ?>
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="<?php echo $cont_slide;  ?>" <?php if($cont_slide == 0){ ?> class="active" <?php } ?>></li>
                           
                        </ol>
                        <div class="carousel-inner">
                            <div <?php if($cont_slide == 0){ ?> class="item active" <?php } ?> >
                                <img style="height: 200px; width: 100%;" src="<?php echo base_url().'assets/uploads/projetos/'.$post->anexo; ?>" alt="First slide">

                                <div class="carousel-caption">

                                </div>
                            </div>
                           
                        </div>
                                        <?php
                                    }
                                    $cont_slide++;
                                }
                            }
                        }
                         * 
                         */
                        ?>
                        <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                            <span class="fa fa-angle-left"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                            <span class="fa fa-angle-right"></span>
                        </a>
                        
                    </div>
                </div>
                <!-- /.box-body
            </div>
            <!-- /.slide -->





            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Equipe da TI</h3>

                    <div class="box-tools pull-right">
                        <span class="label label-danger"></span>
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                       
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <ul class="users-list clearfix">
                        <?php 
            
           // $equipes = $this->user_model->getAllEquipeByUser($usuario);
          //  foreach ($equipes as $equipe) {
              $projeto_id = 4;//  $equipe->id_projeto;
             // $projeto =   $equipe->projeto;

                $equipes_projeto = $this->atas_model->getAllEquipesMembrosDistinct($projeto_id);
                
               $cont_menbros = 0;
                foreach ($equipes_projeto as $equipe_projeto) {
                  $id_usuario = $equipe_projeto->user_id;
              
                    $dados_user = $this->site->getUser($id_usuario);
                    $avatar = $dados_user->avatar;
                    $genero = $dados_user->gender;
                    $nome = $dados_user->first_name;
                    $cargo = $dados_user->cargo;
                    
                    
            ?>
                        <li>
                            <img style="height: 70px;" src="<?= $avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar : $assets . 'images/' . $genero . '.png'; ?>" alt="User Image">
                            <a title="<?php echo $nome; ?>" class="users-list-name" href="#"><?php echo $nome; ?></a>
                            
                        </li>
                        
                        
               <?php
              
                $cont_menbros++;
                }
               ?>         
                    </ul>
                    <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                
                <!-- /.box-footer -->
            </div>


        </section>
        
      </div>
        
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
 
 </div>
  
 
 </div>    
</body>




</html>