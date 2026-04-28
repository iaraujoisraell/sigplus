<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/aba_medico/head');?>

<head>
    <br>
     <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <section class="content-header">
                        <h1>
                           Agenda de Médicos
                        </h1>
                        <ol class="breadcrumb">
                          <li><a href="<?php echo admin_url('dashboard/menu'); ?>"><i class="fa fa-dashboard"></i> Menu</a></li>
                           <li class="active">Agenda de Médicos</li>
                        </ol>
                    </section>
                                <?php hooks()->do_action('before_items_page_content'); ?>
                    <div class="col-md-5">  
                      <div class="form-group">
                           <label>Médicos</label>
                           <select name="medicoid" id="medicoid" onchange="filtraMedico();" class="form-control">
                                <option value="0">Selecione um profissional</option>
                                <?php foreach ($medicos as $med){ ?>
                                 <option value="<?php echo $med['medicoid']; ?>"><?php echo $med['nome_profissional']; ?></option>   
                                <?php } ?>
                            </select>
                      </div>
                     </div>
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
                                    <li><a href="<?php echo admin_url('minha_agenda/agenda'); ?>">Visão geral</a>
                                    </li>
                                    <li class="active"><a href="#" data-toggle="tab">Atualizar perfil</a>
                                    </li>
                                </ul>
                                <div id="userTabContent" class="tab-content">
                                          <div class="tab-pane fade in active" id="profile-settings">

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <ul id="userSettings" class="nav nav-pills nav-stacked">
                                                    <li class="active"><a href="#basicInformation" data-toggle="tab"><i class="fa fa-user fa-fw"></i> Informações básicas</a>
                                                    </li>
                                                    <li><a href="#profilePicture" data-toggle="tab"><i class="fa fa-picture-o fa-fw"></i>Foto do perfil</a>
                                                    </li>
                                                    <li><a href="#changePassword" data-toggle="tab"><i class="fa fa-lock fa-fw"></i>Mudar senha</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-9">
                                                <div id="userSettingsContent" class="tab-content">
                                                    <div class="tab-pane fade in active" id="basicInformation">
                                                        <form role="form">
                                                            <h4 class="page-header">Informações pessoais:</h4>
                                                            <div class="form-group">
                                                                <label>Nome Completo</label>
                                                                <input type="text" class="form-control" value="<?php echo $info->nome_profissional?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nome reduzido <label style="color: darkgray;">(Digite seu primeiro e último nome)</label></label>
                                                                <input type="text" class="form-control" value="<?php echo $info->nome_reduzido?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Número de telefone</label>
                                                                <input type="tel" class="form-control" value="<?php echo $info->celular?>">
                                                            </div>
                                                            
                                                            <div class="form-inline">
                                                               
                                                                <div class="form-group">
                                                                    <label>Cidade: </label>
                                                                    <input type="text" class="form-control" value="<?php echo $info->cidade?>">
                                                                </div> 
                                                                  <div class="form-group">
                                                                      <label>Estado</label>
                                                                      <input type="text" class="form-control" value="<?php echo $info->uf?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>CEP:</label>
                                                                    <input type="text" class="form-control" value="<?php echo $info->cep?>">
                                                                </div>                                                                 
                                                                <div class="form-group">
                                                                    <label>N°:</label>
                                                                    <input type="text" class="form-control" value="<?php echo $info->numero?>">
                                                                </div>                                                            
                                                                
                                                            </div>
                                                              <br>
                                                                                                                             
                                                                <div class="form-group">
                                                                    <label>Endereço: </label>
                                                                    <input type="text" class="form-control" value="<?php echo $info->endereco?>">
                                                                </div>
                                                                
                                                              <div class="form-group">
                                                                    <label> Bairro: </label>
                                                                    <input type="text" class="form-control" value="<?php echo $info->bairro?>">
                                                              </div>
                                                              
                                                                <div class="form-group">
                                                                    <label>Complemento</label>
                                                                    <input type="text" class="form-control" value="<?php echo $info->complemento?>">
                                                                </div>
                                                            <div class="form-inline">
                                                                
                                                            </div>
                                                            
                                                            <h4 class="page-header">Detalhes do contato:</h4>
                                                            <div class="form-group">
                                                                <label><i class="fa fa-envelope-o fa-fw"></i>Endereço de e-mail</label>
                                                                <input type="email" class="form-control" value="<?php echo $info->email?>">
                                                            </div>                                                           
                                                            <h4 class="page-header">Informações do perfil:</h4>
                                                            <div class="form-group">
                                                                <label><i class="fa fa-info fa-fw"></i> Sobre você</label>
                                                                <textarea class="form-control" rows="3"></textarea>
                                                            </div>
                                                           <!-- <div class="form-group">
                                                                <label><i class="fa fa-building-o fa-fw"></i>Especialidade</label>
                                                                <select multiple class="form-control">
                                                                    <?php foreach ($tabela as $tab){ ?>
                                                                    <option value="<?php echo $tab['especialidade']; ?>"><?php echo $tab['especialidade']; ?></option>   
                                                                   <?php } ?>
                                                                </select>

                                                            </div>-->
                                                            <h4 class="page-header">Redes sociais:</h4>
                                                            <div class="form-group">
                                                                <label><i class="fa fa-facebook fa-fw"></i> Facebook Profile URL</label>
                                                                <input type="url" class="form-control" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label><i class="fa fa-linkedin fa-fw"></i> LinkedIn Profile URL</label>
                                                                <input type="url" class="form-control" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label><i class="fa fa-google-plus fa-fw"></i> Google+ Profile URL</label>
                                                                <input type="url" class="form-control" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label><i class="fa fa-twitter fa-fw"></i> Twitter Username</label>
                                                                <input type="text" class="form-control" value="">
                                                            </div>
                                                            <button type="submit" class="btn btn-default">Atualizar perfil</button>
                                                            <button class="btn btn-green">Cancelar</button>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane fade" id="profilePicture">
                                                        <h3>Imagem atual:</h3>
                                                        <img class="img-responsive img-profile" src="<?php echo base_url() ?>assets/ITOAM/template/img/profile-pic.jpg" alt="">
                                                        <br>
                                                        <form role="form">
                                                            <div class="form-group">
                                                                <label>Escolha uma nova imagem</label>
                                                                <input type="file">
                                                                <p class="help-block"><i class="fa fa-warning"></i>A imagem não deve ter mais de 500 x 500 pixels. Formatos suportados: JPG, GIF, PNG</p>
                                                                <button type="submit" class="btn btn-default">Atualizar foto do perfil</button>
                                                                <button class="btn btn-green">Cancelar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane fade in" id="changePassword">
                                                        <h3>Mudar senha:</h3>
                                                        <form role="form">
                                                            <div class="form-group">
                                                                <label>Senha antiga</label>
                                                                <input type="password" class="form-control" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nova senha</label>
                                                                <input type="password" class="form-control" value="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Digite novamente a nova senha</label>
                                                                <input type="password" class="form-control" value="">
                                                            </div>
                                                            <button type="submit" class="btn btn-default">Atualizar senha</button>
                                                            <button class="btn btn-green">Cancelar</button>
                                                        </form>
                                                    </div>
                                                </div>
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
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->

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

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/flex.js"></script>
    <script>

function filtraMedico() {
          //alert('chegou aqui'); exit;
          $.ajax({
            type: "POST",
            url: "<?php echo admin_url("minha_agenda/retorno_medicoid"); ?>",
            data: {
              medicoid: $('#medicoid').val()
              },
            success: function(data) {
              $('#trocar').html(data);
            }
          });
        }
    
</script>
</body>

</html>

