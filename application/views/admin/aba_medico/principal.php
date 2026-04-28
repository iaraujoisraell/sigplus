<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/aba_medico/head');?>
<head>
    <br>
     <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <section class="content-header">
                        <h1>
                           Minha Agenda
                        </h1>
                        <ol class="breadcrumb">
                          <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Menu</a></li>
                           <li class="active">Minha Agenda</li>
                        </ol>
                    </section>
                
                </div>
            </div>
     </div>   
</head> 
<body>
    
    <div class="content">
        
                <div class="row">
                    <div class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-body">
                                <ul id="userTab" class="nav nav-tabs">
                                    <li class="active"><a href="#" data-toggle="tab">Visão geral</a>                                    
                                    </li>
                                    <li><a href="<?php echo admin_url('minha_agenda/atualizar_perfil'); ?>">Atualizar perfil</a> </li>
                                </ul>
                                <div id="userTabContent" class="tab-content">
                                    <div class="tab-pane fade in active" id="overview">

                                        <div class="row">
                                            <div class="col-lg-2 col-md-3">
                                                <a align = "left" href="#">
                                                    <span class="profile-edit"><i class="fa fa-pencil-square-o"></i></span>
                                                </a>
                                                <div align = "center">
                                                   <img class="img-responsive img-profile" src="<?php echo base_url();?>assets/ITOAM/template/img/profile-pic.jpg" alt="">  
                                                </div>
                                               
                                                <div class="list-group">
                                                    <a href="#" class="list-group-item active">Visão geral</a>
                                                    <a href="<?php echo admin_url('minha_agenda/mensagens'); ?>" class="list-group-item" target = "_blank">Mensagens<span class="badge green">4</span></a>
                                                    <!--<a href="#" class="list-group-item">Alertas<span class="badge orange">9</span></a>
                                                   --> <a href="<?php echo admin_url('minha_agenda/tarefas'); ?>" class="list-group-item" target = "_blank">Tarefas<span class="badge blue">10</span></a>
                                                </div>
                                            </div>
                                            <div class="col-lg-7 col-md-5">
                                                
                                                <h1><b><?php echo $info->nome_profissional; ?></h1>
                                                <p>Sobre você:</p>
                                                <ul class="list-inline">
                                                    <li><i class="fa fa-user fa-muted"></i> CPF: <?php echo $info->cpf?></li>
                                                    <li><i class="fa fa-group fa-muted"></i> Especialidade: <?php echo $info->especialidade?></li>
                                                    <li><i class="fa fa fa-user-md fa-muted"></i> CRM: <?php echo $info->CRM?></li>                                                    
                                                </ul>
                                                <br><br>
                                                <div class="portlet portlet-default">
                                                    <div class="portlet-heading">
                                                        <div class="portlet-title">
                                                            <h4>Minhas escalas</h4>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-responsive">
                                                            <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Dia</th>
                                                                        <th>Mês</th>
                                                                        <th>Dia da semana</th>
                                                                        <th>Unidade</th>
                                                                        <th>Setor</th>
                                                                        <th>Horário</th>
                                                                        <th>Qtde plantão</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php 
                                                                    foreach ($tabela as $tab){
                                                                    $hora_inicio = $tab['hora_inicio'];
                                                                    $hora_fim = $tab['hora_fim'];
                                                                    $hora_format_st = date('H:i', strtotime($hora_inicio));
                                                                    $hora_format_end = date('H:i', strtotime($hora_fim));                                                          
                                                                    ?>
                                                                    <tr class="gradeU">
                                                                        <td align="center"><?php echo $tab['dia'];?></td>
                                                                        <td align="center"><?php echo $tab['mes']; ?></td>
                                                                        <td><?php echo $tab['dia_semana']; ?></td>
                                                                        <td><?php echo $tab['fantasia']; ?></td>
                                                                        <td><?php echo $tab['setor']; ?></td>
                                                                        <td><?php echo $hora_format_st.'-'.$hora_format_end; ?></td>
                                                                        <td align="center"><?php echo $tab['quantidade']; ?></td>
                                                                    </tr>
                                                                    <?php }?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!-- /.table-responsive -->
                                                    </div>
                                                    <!-- /.portlet-body -->
                                                </div>
                                                                                                   
                                            </div>
                                            <div  class="col-lg-3 col-md-4">
                                                <h3>Detalhes do contato</h3>
                                                <p><i class="fa fa-phone fa-muted fa-fw"></i>Telefone: <?php echo $info->celular?></p>
                                                <p><i class="fa fa-building-o fa-muted fa-fw"></i>CEP: <?php echo $info->cep?></p>
                                                <p><i class="fa fa-map-marker fa-muted fa-fw"></i>Endereço: <?php echo $info->endereco.''.$info->numero?></p>
                                                <p><i class="fa fa-road fa-muted fa-fw"></i>Bairro: <?php echo$info->bairro?></p>
                                                <p><i class="fa fa-location-arrow fa-muted fa-fw"></i>Complemento: <?php echo $info->complemento?></p>    
                                                    
                                                <p><i class="fa fa-envelope-o fa-muted fa-fw"></i>E-mail: <a href="#"> <?php echo $info->email ?></a>
                                                </p> 
                                                <ul class="list-inline">
                                                    <li><a class="facebook-link" href="#"><i class="fa fa-facebook-square fa-2x"></i></a>
                                                    </li>
                                                    <li><a class="twitter-link" href="#"><i class="fa fa-twitter-square fa-2x"></i></a>
                                                    </li>
                                                    <li><a class="linkedin-link" href="#"><i class="fa fa-linkedin-square fa-2x"></i></a>
                                                    </li>
                                                    <li><a class="google-plus-link" href="#"><i class="fa fa-google-plus-square fa-2x"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                 
                                </div>
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->


                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

    </div>
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
    <div id="logout">
       
    </div>
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/datatables/datatables-bs3.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/flex.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/demo/advanced-tables-demo.js"></script>
    
</body>

</html>

