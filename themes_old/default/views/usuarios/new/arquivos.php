<?php



        
        ?>

<body class="hold-transition skin-green sidebar-collapse  sidebar-mini">


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Arquivos
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>


            <section class="col-lg-12 connectedSortable">
               

                <br>

                
                <br>

                <!-- DIV TABLE AÇÕES PENDENTES -->  
                <div id="acoes_pendentes">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="portlet portlet-default">



                                <div class="portlet-body">
                                    <div class="table-responsive">

                                        <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                            <thead>
                                                <tr style="background-color: orange;">
                                                    <th>-</th>
                                                    <th>Projeto</th>
                                                    <th>Título</th>
                                                    <th>Descrição</th>
                                                    <th>Postado por:</th>
                                                    <th>Download</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php
                                                $usuario = $this->session->userdata('user_id');    
                                                
                                                $equipes = $this->user_model->getAllPostagemByEquipeDistinct($usuario);
                                                $cont = 1;
                                                foreach ($equipes as $equipe) {
                                                  $projeto_id =   $equipe->id_projeto;
                                                  $projeto =   $equipe->projeto;

                                                  //$postagens = $this->projetos_model->getAllPostagemByProjeto($projeto_id);


                                                      $tipo_post = $equipe->tipo; 
                                                      $usuario_post = $equipe->user_id; 
                                                      $data_post = $equipe->data_postagem; 

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


                                                        $titulo = $equipe->titulo; 
                                                        $descricao = $equipe->descricao;

                                                      $dados_user = $this->site->getUser($usuario_post);
                                                      $avatar = $dados_user->avatar;
                                                      $genero = $dados_user->gender;
                                                      $nome = $dados_user->first_name;

                                                  if($tipo_post == 3){

                                                   ?>   

                                                    <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?></td> 
                                                        <td><?php echo $projeto; ?></td> 
                                                        <td><?php echo $titulo; ?></td> 
                                                        <td><?php echo $descricao; ?></td>    
                                                        <td><?php echo $nome; ?> <span class="description"> (<?php echo $dia2.'/'.$mes2.'/'.$ano2.'  '.$hora_post; ?> )</span></td> 
                                                       
                                                       
                                                        <td class="center">
                                                            <a target="_blank" style="background-color: chocolate; color: #ffffff;" class="btn fa fa-download" href="<?php echo base_url().'assets/uploads/projetos/'.$equipe->anexo; ?>"> </a>
                                                        </td>
                                                      
                                                    </tr>
                                                    <?php
                                                 }
            
                                                }
                                                 
                                                ?>



                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.portlet-body -->

                            </div>
                            <!-- /.portlet -->

                        </div>
                        <!-- /.col-lg-12 -->



                    </div>
                </div>
                <!-- /.FIM AÇÕES PENDENTES -->
            </section>

    </div>
    <!-- /.page-content -->

    <!-- /#wrapper -->

</body>
