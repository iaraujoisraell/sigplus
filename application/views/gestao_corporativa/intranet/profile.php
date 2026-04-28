<!-- FIM LTE-->
<style>
    .aa {
        max-width: 100%;
    }

    .avatar-upload {
        position: relative;
        max-width: 205px;
        margin: 5px auto;
    }

    .avatar-upload .avatar-edit {
        position: absolute;
        right: 5px;
        z-index: 1;
        top: 10px;
    }

    .avatar-upload .avatar-edit input {
        display: none;
    }

    .avatar-upload .avatar-edit input+label {
        display: inline-block;
        width: 34px;
        height: 34px;
        margin-bottom: 0;
        border-radius: 100%;
        background: #FFFFFF;
        border: 1px solid transparent;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        font-weight: normal;
        transition: all 0.2s ease-in-out;
    }

    .avatar-upload .avatar-edit input+label:hover {
        background: #f1f1f1;
        border-color: #d6d6d6;
    }

    .avatar-upload .avatar-preview {
        width: 150px;
        height: 150px;
        position: relative;
        border-radius: 100%;
        border: 6px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload .avatar-preview>div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="<?php echo base_url(); ?>assets/intranet/assinatura_support/clipboard.min.js"></script>
<section class="content-header">
    <div class="row mb-2">
        <div class="col-md-12">

            <div class="card card-widget widget-user">
                <?php
                if ($staff->background_image) :
                    $url_bg = base_url() . 'uploads/staff_profile_images/' . $staff->staffid . '/bg_' . $staff->background_image;
                else :
                    $url_bg = base_url() . 'uploads/staff_profile_images/bg.jpg';
                endif;
                ?>
                <div class="widget-user-header text-white" style="background: url('<?php echo $url_bg; ?>'); background-size: 100%; center: center;">


                    <?php echo form_open_multipart('gestao_corporativa/Intranet/recebe_imagem/bg'); ?>
                    <div class="avatar-upload" style="position: absolute; left: 100px;">
                        <div class="avatar-edit">
                            <input type="file" id="background" name="background" />
                            <label for="background"><img style="margin: 6px; width: 20px; height: 20px;" src="<?php echo base_url() ?>assets/lte/novosicones/edit.png"></label>
                        </div>
                        <button type="submit" id="editar" style="display: none;">
                        </button>
                        <?php echo form_close(); ?>

                    </div>
                    <h3 class="widget-user-username text-right">
                        <?php echo ($staff->firstname . ' ' . $staff->lastname); ?>
                    </h3>
                    <h5 class="widget-user-desc text-right">
                        <?php echo ($staff->name); ?>
                    </h5>
                </div>


                <div class="widget-user-image">
                    <div class="avatar-upload">
                        <?php echo form_open_multipart('gestao_corporativa/Intranet/recebe_imagem/profile'); ?>
                        <div class="avatar-edit">
                            <input type="file" id="imageUpload" name="imageUpload" />
                            <label for="imageUpload">
                                <img style="margin: 6px; width: 20px; height: 20px;" src="<?php echo base_url() ?>assets/lte/novosicones/pencil.png">
                            </label>
                        </div>
                        <button style="display: none;" type="submit" id="click"></button>
                        <?php echo form_close(); ?>

                        <div class="avatar-preview">
                            <?php
                            $profile_image = $staff->profile_image;
                            $color = "#d3d3d3"; // Cor padrão

                            // Verifica se existe uma imagem de perfil
                            if ($profile_image) {
                                // Obtém o ID do usuário
                                $usuario_id = $staff->staffid;

                                // Carrega o modelo e obtém a cor do terceiro, se houver
                                if ($staff->terceiro_id) {
                                    $this->load->model('Company_model');
                                    $row_terceiro = $this->Company_model->get_terceiros($staff->terceiro_id);
                                    $color = $row_terceiro->cor;
                                }

                                // Define a imagem de perfil com a cor de borda correspondente
                                echo "<div id='imagePreview' style='background-image: url(\"" . base_url() . "uploads/staff_profile_images/" . $usuario_id . "/small_" . $profile_image . "\"); border: 5px solid " . $color . ";'></div>";
                            } else {
                                // Define a imagem padrão se não houver imagem de perfil
                                echo "<div id='imagePreview' style='background-image: url(\"" . base_url() . "assets/images/user-placeholder.jpg\");'></div>";
                            }
                            ?>
                        </div>

                    </div>
                </div><br><br>

                <div class="card-footer" style="background-color:white;">
                    <div class="row">

                        <div class="col-sm-2">
                            <div class="description-block">
                                <!-- <h5 class="description-header">EXEMPLO</h5>
                                <span class="description-text">0</span> -->
                            </div>
                            <!-- /.description-block -->

                        </div>

                        <div class="col-sm-2">
                            <div class="description-block">
                                <!-- <h5 class="description-header">EXEMPLO</h5>
                                <span class="description-text">0</span> -->
                            </div>
                            <!-- /.description-block -->

                        </div>

                        <div class="col-sm-4">
                            <?php
                            $meus = $this->Departments_model->get_staff_departments('', false);
                            ?>

                            <div class="description-block">
                                <h3 style="margin-left: 50px;" class="widget-user-username">
                                    <?php echo ($staff->firstname . ' ' . $staff->lastname); ?>
                                    
                                </h3>

                                <?php foreach ($meus as $meu) { ?>
                                    <h6 style="margin-left: 50px;" class="widget-user-desc">
                                        <?php echo strtoupper($meu['name']); ?>
                                    </h6>
                                <?php } ?>

                            </div>

                        </div>

                        <div class="col-sm-2">
                            <div class="description-block">
                                <!--      <h5 class="description-header">EXEMPLO</h5> 
                                <span class="description-text">0</span>-->
                            </div>
                            <!-- /.description-block -->

                        </div>

                        <div class="col-sm-2">
                            <div class="description-block">
                                <!-- <h5 class="description-header">EXEMPLO</h5>
                                <span class="description-text">0</span> -->
                            </div>
                            <!-- /.description-block -->

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Sobre mim</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i>Formação Acadêmica</strong>

                        <p class="text-muted">
                            <?php echo ($staff->formacao_academica); ?>
                        </p>

                        <hr>

                        <strong><i class="fas fa fa-building"></i> Empresa</strong>

                        <p class="text-muted">
                            <?php echo ($staff->empresa); ?>
                        </p>

                        <hr>

                        <strong><i class="fas fa fa-building"></i> Terceirizada</strong>

                        <p class="text-muted">
                        <?php echo ($staff->company); ?>
                        </p>

                        <hr>

                        <strong><i class="fas fa fa-briefcase"></i> Setor</strong>

                        <p class="text-muted">
                            <?php foreach ($meus as $meu) { ?>
                                <span class="tag tag-danger">
                                    <?php echo strtoupper($meu['name']); ?>
                                </span>
                            <?php } ?>

                        </p>

                        <hr>

                        <strong><i class="far fa fa-birthday-cake"></i> Aniversário</strong>

                        <p class="text-muted">
                            <?php
                            if ($staff->data_nascimento) {
                                echo (date('d/m/Y', strtotime($staff->data_nascimento)));
                            } else {
                                echo 'Não Informado';
                            }
                            ?>
                        </p>

                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i> Sobre mim</strong>

                        <p class="tag tag-danger">
                            <?php echo ($staff->descricao); ?>
                        </p>

                        <hr>

                        <strong><i class="far fa fa-share-square"></i> Contatos <br><br></strong>

                        <div>
                            <p class="text-muted"><b style="color:black;">Pessoal: </b>
                                <?php echo ($staff->phonenumber); ?>
                            </p>
                            <p class="text-muted"><b style="color:black;">Comercial: </b>
                                <?php echo ($staff->num_comercial); ?>
                            </p>
                            <p class="text-muted"><b style="color:black;">Ramal: </b>
                                <?php echo ($staff->num_ramal); ?>
                            </p>
                        </div>
                        <div>

                        </div>
                        <?php if ($staff->facebook) { ?>
                            <a target="_blank" style="margin-right: 10px; " href="<?php echo 'https://www.facebook.com/' . ($staff->facebook); ?>">
                                <img alt="Qries" src="<?php echo base_url() ?>assets/lte/novosicones/logo-facebook.svg" width="25px" height="25px">
                            </a>
                        <?php } ?>
                        <?php if ($staff->instagram) { ?>
                            <a target="_blank" style="margin-right: 10px; " href="<?php echo 'https://www.instagram.com/' . ($staff->instagram); ?>">
                                <img alt="Qries" src="<?php echo base_url() ?>assets/lte/novosicones/logo-instagram.svg" width=25px" height="25px">
                            </a>
                        <?php } ?>
                        <?php if ($staff->linkedin) { ?>
                            <a target="_blank" style="margin-right: 10px; " href="<?php echo 'https://www.linkedin.com/in/' . ($staff->linkedin); ?>">
                                <img alt="Qries" src="<?php echo base_url() ?>assets/lte/novosicones/logo-linkedin.svg" width=25px" height="25px">
                            </a>
                        <?php } ?>
                    </div>
                    <!-- /.card-body -->

                </div>

                <!-- /.card -->
            </div>

            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <!--<li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Publicações</a></li>-->

                            <li class="nav-item"><a class="nav-link <?php
                                                                    if (!$_GET['notifications']) {
                                                                        echo 'active';
                                                                    }
                                                                    ?>" href="#settings" data-toggle="tab">Configurações</a></li>
                            <li class="nav-item"><a class="nav-link <?php
                                                                    if ($_GET['notifications']) {
                                                                        echo 'active';
                                                                    }
                                                                    ?>" href="#timeline" data-toggle="tab">Notificações</a></li>
                            <li class="nav-item"><a class="nav-link" href="#email" data-toggle="tab">Assinatura de
                                    email</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!--<div class="active tab-pane" id="activity">
                              
                             <div class="post">
                               <div class="user-block">
                                 <img class="img-circle img-bordered-sm" src="<?php echo base_url() ?>assets/lte/dist/img/user1-128x128.jpg" alt="user image">
                                 <span class="username">
                                   <a href="#">Jonathan Burke Jr.</a>
                                   <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                 </span>
                                 <span class="description">Publicado em - 7:30 hoje </span>
                               </div>
                             
                             <p>
                               Lorem ipsum represents a long-held tradition for designers,
                               typographers and the like. Some people hate it and argue for
                               its demise, but others ignore the hate as they create awesome
                               tools to help create filler text for everyone from bacon lovers
                               to Charlie Sheen fans.
                             </p>
       
                             <p>
                               <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Compartilhar</a>
                               <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Curtir</a>
                               <span class="float-right">
                                 <a href="#" class="link-black text-sm">
                                   <i class="far fa-comments mr-1"></i> Comentários (5)
                                 </a>
                               </span>
                             </p>
       
                             <input class="form-control form-control-sm" type="text" placeholder="Escreva um comentário">
                           </div>
                             <div class="post clearfix">
                               <div class="user-block">
                                 <img class="img-circle img-bordered-sm" src="<?php echo base_url() ?>assets/lte/dist/img/user7-128x128.jpg" alt="User Image">
                                 <span class="username">
                                   <a href="#">Sarah Ross</a>
                                   <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                 </span>
                                 <span class="description">Enviou uma mensagem - 3 dias atrás </span>
                               </div>
                             <p>
                               Lorem ipsum represents a long-held tradition for designers,
                               typographers and the like. Some people hate it and argue for
                               its demise, but others ignore the hate as they create awesome
                               tools to help create filler text for everyone from bacon lovers
                               to Charlie Sheen fans.
                             </p>
       
                             <form class="form-horizontal">
                               <div class="input-group input-group-sm mb-0">
                                 <input class="form-control form-control-sm" placeholder="Escreva sua resposta">
                                 <div class="input-group-append">
                                   <button type="submit" class="btn btn-danger">Enviar</button>
                                 </div>
                               </div>
                             </form>
                           </div>
         
                             <div class="post">
                               <div class="user-block">
                                 <img class="img-circle img-bordered-sm" src="<?php echo base_url() ?>assets/lte/dist/img/user6-128x128.jpg" alt="User Image">
                                 <span class="username">
                                   <a href="#">Adam Jones</a>
                                   <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                 </span>
                                 <span class="description">Postou 5 fotos - 5 dias atrás</span>
                               </div>
                             <div class="row mb-3">
                               <div class="col-sm-6">
                                 <img class="img-fluid" src="<?php echo base_url() ?>assets/lte/dist/img/photo1.png" alt="Photo">
                               </div>
                             <div class="col-sm-6">
                               <div class="row">
                                 <div class="col-sm-6">
                                   <img class="img-fluid mb-3" src="<?php echo base_url() ?>assets/lte/dist/img/photo2.png" alt="Photo">
                                   <img class="img-fluid" src="<?php echo base_url() ?>assets/lte/dist/img/photo3.jpg" alt="Photo">
                                 </div>
                             <div class="col-sm-6">
                               <img class="img-fluid mb-3" src="<?php echo base_url() ?>assets/lte/dist/img/photo4.jpg" alt="Photo">
                               <img class="img-fluid" src="<?php echo base_url() ?>assets/lte/dist/img/photo1.png" alt="Photo">
                             </div>
                           </div>
                           </div>
                           </div>
       
                             <p>
                               <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Compartilhar</a>
                               <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Curtir</a>
                               <span class="float-right">
                                 <a href="#" class="link-black text-sm">
                                   <i class="far fa-comments mr-1"></i> Comentários (5)
                                 </a>
                               </span>
                             </p>
       
                             <input class="form-control form-control-sm" type="text" placeholder="Escreva um comentário">
                           </div>
                           </div>
                            <div class="tab-pane" id="timeline">
                             <div class="timeline timeline-inverse">
                             <div class="time-label">
                               <span class="bg-danger">
                                 10 Feb. 2014
                               </span>
                             </div>
                             <div>
                               <i class="fas fa-envelope bg-primary"></i>
       
                               <div class="timeline-item">
                                 <span class="time"><i class="far fa-clock"></i> 12:05</span>
       
                                 <h3 class="timeline-header"><a href="#">Equipe de Suporte</a> enviou um email</h3>
       
                                 <div class="timeline-body">
                                   Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                   weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                   jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                   quora plaxo ideeli hulu weebly balihoo...
                                 </div>
                                 <div class="timeline-footer">
                                   <a href="#" class="btn btn-primary btn-sm">Ler mais</a>
                                   <a href="#" class="btn btn-danger btn-sm">Excluir</a>
                                 </div>
                               </div>
                             </div>
                             <div>
                               <i class="fas fa-user bg-info"></i>
       
                               <div class="timeline-item">
                                 <span class="time"><i class="far fa-clock"></i> 5 min atrás</span>
       
                                 <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> aceitou seu pedido de amizade</h3>
                               </div>
                             </div>
                             <div>
                               <i class="fas fa-comments bg-warning"></i>
       
                               <div class="timeline-item">
                                 <span class="time"><i class="far fa-clock"></i> 27 min atrás</span>
       
                                 <h3 class="timeline-header"><a href="#">Jay White</a> comentou em seu post</h3>
       
                                 <div class="timeline-body">
                                   Take me to your leader!
                                   Switzerland is small and neutral!
                                   We are more like Germany, ambitious and misunderstood!
                                 </div>
                                 <div class="timeline-footer">
                                   <a href="#" class="btn btn-warning btn-flat btn-sm">Ver comentário</a>
                                 </div>
                               </div>
                             </div>
                             <div class="time-label">
                               <span class="bg-success">
                                 3 Jan. 2014
                               </span>
                             </div>
                             <div>
                               <i class="fas fa-camera bg-purple"></i>
       
                               <div class="timeline-item">
                                 <span class="time"><i class="far fa-clock"></i> 2 dias atrás</span>
       
                                 <h3 class="timeline-header"><a href="#">Mina Lee</a> carregou novas fotos</h3>
       
                                 <div class="timeline-body">
                                   <img src="https://placehold.it/150x100" alt="...">
                                   <img src="https://placehold.it/150x100" alt="...">
                                   <img src="https://placehold.it/150x100" alt="...">
                                   <img src="https://placehold.it/150x100" alt="...">
                                 </div>
                               </div>
                             </div>
                             <div>
                               <i class="far fa-clock bg-gray"></i>
                             </div>
                           </div>
                         </div>-->
                            <div class="tab-pane <?php
                                                    if ($_GET['notifications']) {
                                                        echo 'active';
                                                    }
                                                    ?>" id="timeline">
                                <div class="timeline timeline-inverse">

                                    <div>
                                        <?php
                                        $this->load->model('Intranet_model');

                                        $notifications = $this->Intranet_model->get_notification();

                                        for ($i = 0; $i < count($notifications); $i++) {
                                        ?>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i>
                                                    <?php echo $notifications[$i]['date']; ?>
                                                </span>

                                                <h3 class="timeline-header"><a href="#">
                                                        <?php echo $notifications[$i]['firstname'] . ' ' . $notifications[$i]['lastname']; ?>
                                                    </a> enviou uma notificação</h3>

                                                <div class="timeline-body">
                                                    <?php echo $notifications[$i]['description']; //_l($notifications[$i]['description']); 
                                                    ?>
                                                </div>
                                                <div class="timeline-footer">
                                                    <a href="<?php echo $notifications[$i]['link']; ?>" target="_blanck" class="btn btn-primary btn-sm">Visualizar</a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="email">

                                <form class="form-horizontal" id='padrao' onsubmit='return false;'>
                                    <div class="row">
                                        <?php if (is_file('assets/intranet/assinatura_support/ass_nova/' . $staff->user . '.jpg')) { ?>
                                            <div class="col-md-12" style="text-align: center; margin-bottom: 10px;" id="resposta">
                                                <h5>Assinatura de Email Atual</h5>
                                                <button class="btn btn-success" data-clipboard-action="copy" data-clipboard-target="#copycurrent" type="button">Clique aqui para selecionar e copiar as imagens
                                                    novamente.</button>
                                                <div id="copycurrent" style="margin-top: 10px;">
                                                    <?php $timestamp = time(); ?>
                                                    <img alt="Assinatura" style="max-width: 30%;" src="<?php echo base_url('assets/intranet/assinatura_support/ass_nova/' . $staff->user . '.jpg'); ?>?version=<?php echo rand(0, 99999); ?>"><br />

                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="matricula" class="col-form-label">Matrícula</label>
                                                <input required="true" type="text" class="form-control" id="matricula" name="matricula" placeholder="Matrícula" <?php if($make_assignature != true){ echo 'readonly';}?> value="<?php echo $staff->user; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="nome" class="col-form-label">Nome</label>
                                                <input required="true" type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Nome Sobrenome" <?php if($make_assignature != true){ echo 'readonly';}?> value="<?php echo $info['NOME'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cargo" class="col-form-label">Cargo</label>
                                                <input required="true" type="text" class="form-control" id="cargo" name="cargo" placeholder="Ex: Cargo" <?php if($make_assignature != true){ echo 'readonly';}?> value="<?php if ($info['CARGO']) {
                                                                                                                                                                            echo $info['CARGO'];
                                                                                                                                                                        } else {
                                                                                                                                                                            echo $staff->cargo;
                                                                                                                                                                        } ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="setor" class="col-form-label">Setor</label>
                                                <input required="true" type="text" class="form-control" id="setor" name="setor" placeholder="Ex: Setor" <?php if($make_assignature != true){ echo 'readonly';}?> value="<?php echo $info['SETOR'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="col-form-label">E-mail do Funcionário</label>
                                                <input required="true" type="text" class="form-control" id="email_" name="email_" placeholder="Ex: exemplo@exemplo.exemplo" value="<?php echo $staff->email; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="telefone" class="col-form-label">Telefone do
                                                    Funcionário</label>
                                                <input required="true" type="text" class="form-control" id="telefone" name="telefone" placeholder="Ex: (99) 9999-9999" value="<?php echo $staff->phonenumber; ?>">
                                            </div>

                                        </div>
                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="site" class="col-form-label">Site da Unimed</label>
                                                <input required="true" type="text" class="form-control" id="site" name="site" placeholder="www.unimedmanaus.com.br" value="www.unimedmanaus.com.br">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <?php if (!is_file('assets/intranet/assinatura_support/ass_nova/' . $staff->user . '.jpg')) { ?>

                                                <button type="submit" id="criar"" class=" btn btn-primary w-100">CRIAR
                                                    ASSINATURA DE EMAIL</button>

                                                <div id="resposta">
                                                </div>
                                            <?php } else { ?>
                                                <button type="submit" id="criar"" class=" btn btn-primary w-100">ATUALIZAR
                                                    ASSINATURA DE EMAIL</button>

                                            <?php } ?>

                                        </div>

                                    </div>

                                </form>



                            </div>

                            <div class="tab-pane <?php
                                                    if (!$_GET['notifications']) {
                                                        echo 'active';
                                                    }
                                                    ?>" id="settings">
                                <?php echo form_open_multipart('gestao_corporativa/Intranet/salvar_configuracoes'); ?>
                                <input type="hidden" class="form-control" id="inputEmail" name="staffid" placeholder="Formação acadêmica" value="<?php echo $staff->staffid; ?>">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Nome</label>
                                        <div class="col-sm-10">
                                            <input required="true" type="text" class="form-control" id="inputName" name="nome_staff" placeholder="Nome" value="<?php echo $staff->firstname ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Sobrenome</label>
                                        <div class="col-sm-10">
                                            <input required="true" type="text" class="form-control" id="inputName" name="sobrenome_staff" placeholder="Sobrenome" value="<?php echo $staff->lastname ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Formação
                                            acadêmica</label>
                                        <div class="col-sm-10">
                                            <input required="true" type="text" class="form-control" id="inputEmail" name="formacao_acad" placeholder="Formação acadêmica" value="<?php echo $staff->formacao_academica; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Empresa</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="nome_empresa" placeholder="Empresa" value="<?php echo $staff->empresa; ?>" disabled="" style="color: grey;">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Setor</label>
                                        <div class="col-sm-10">
                                            <select class="select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" disabled name="departments[]">

                                                <?php
                                                $this->load->model('Departments_model');
                                                $departamentos = $this->Departments_model->get();
                                                $meus = $this->Departments_model->get_staff_departments('', true);

                                                foreach ($departamentos as $dep) {
                                                    $selected = '';
                                                    if (in_array($dep['departmentid'], $meus)) {
                                                        $selected = 'selected';
                                                    }
                                                ?>
                                                    <option value="<?php echo $dep['departmentid']; ?>" <?php echo $selected; ?>>
                                                        <?php echo $dep['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Aniversário</label>
                                        <div class="col-sm-10">
                                            <input required="true" type="date" class="form-control" id="inputSkills" name="aniversario" placeholder="Aniversário" value="<?php echo $staff->data_nascimento; ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Sobre mim</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control" id="inputSkills" name="descricao" placeholder="Sobre mim"><?php echo $staff->descricao; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="contatos" class="col-sm-2 col-form-label">Contatos</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" onclick="check_pessoal(this.checked)" class="custom-control-input" id="customSwitch1">
                                            <label class="custom-control-label" for="customSwitch1"></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input required="true" type="text" class="form-control" id="phonenumber" name="num_pessoal" placeholder="Número pessoal" value="<?php echo $staff->phonenumber; ?>" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" />
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label for="contatos" class="col-sm-2 col-form-label"></label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" onclick="check_comercial(this.checked)" class="custom-control-input" id="customSwitch2">
                                            <label class="custom-control-label" for="customSwitch2"></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input required="true" type="text" class="form-control" id="num_comercial" name="num_comercial" placeholder="Número comercial" value="<?php echo $staff->num_comercial; ?>" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" />
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label for="contatos" class="col-sm-2 col-form-label"></label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" onclick="check_ramal(this.checked)" class="custom-control-input" id="customSwitch3">
                                            <label class="custom-control-label" for="customSwitch3"></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input required="true" type="number" class="form-control" id="num_ramal" name="num_ramal" placeholder="Número do ramal" value="<?php echo $staff->num_ramal; ?>"" />
                                        </div>

                                    </div>

                                    <div class=" form-group row ">
                                        <label for=" inputSkills" class="col-sm-2 col-form-label"></label>
                                            <div>
                                                <img style="margin: 6px; width: 25px; height: 25px;" src="<?php echo base_url() ?>assets/lte/novosicones/logo-facebook.svg">
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="inputSkills" name="facebook" placeholder="Facebook" value="<?php echo $staff->facebook; ?>">
                                            </div>

                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label"></label>
                                            <div>
                                                <img style="margin: 6px; width: 25px; height: 25px;" src="<?php echo base_url() ?>assets/lte/novosicones/logo-instagram.svg">
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="inputSkills" name="instagram" placeholder="Instagram" value="<?php echo $staff->instagram; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label"></label>
                                            <div>
                                                <img style="margin: 6px; width: 25px; height: 25px;" src="<?php echo base_url() ?>assets/lte/novosicones/logo-linkedin.svg">
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="inputSkills" name="linkedin" placeholder="Linkedin" value="<?php echo $staff->linkedin; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label"></label>
                                            <div>
                                                <img style="margin: 6px; width: 25px; height: 25px;" src="<?php echo base_url() ?>assets/lte/novosicones/logo-email.svg">
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="email" class="form-control" id="inputSkills" name="email" placeholder="E-mail" value="<?php echo $staff->email; ?>" disabled>
                                            </div>
                                        </div>
                                        <br>

                                        <!-- <div class="row">
                                         <label for="inputSkills" class="col-sm-2 col-form-label">Editar imagem</label> 
                                                           
             
                                             <div class="col-lg-6">
                                             <div class="btn-group w-20">
                                             
                                             <button type="submit" class="btn btn-block btn-success btn-sm">
                                             <i class="fas fa-upload"></i>
                                             <span>Fazer upload</span>
                                             </button>
                                             </div>
                                             </div>
                             
                                     </div>-->


                                        <div class="form-group row">
                                            <div class="offset-sm-11 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                            </div>
                                        </div>

                                        <?php echo form_close(); ?>
                                    </div>
                                    <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
</section>
<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery/jquery.min.js"></script>


<script type='text/javascript'>
    $(document).ready(function() {
        $("#criar").click(function() {

            if ($('#telefone').val() == '') {
                toastr.warning('Telefone é obrigatório!');
                return false;
            }

            if ($('#email_').val() == '') {
                toastr.warning('Email é obrigatório!');
                return false;
            }

            if ($('#matricula').val() == '') {
                toastr.warning('Matrícula é obrigatória!');
                return false;
            }

            if ($('#nome').val() == '') {
                toastr.warning('Nome é obrigatório!');
                return false;
            }

            if ($('#cargo').val() == '') {
                toastr.warning('Cargo é obrigatório!');
                return false;
            }
            if ($('#site').val() == '') {
                toastr.warning('Site é obrigatório!');
                return false;
            }

            $("#resposta").html();
            $.post('<?php echo base_url('gestao_corporativa/Intranet/assinatura_step_do'); ?>', {
                matricula: $("#matricula").val(),
                nome: $("#nome").val(),
                cargo: $("#cargo").val(),
                setor: $("#setor").val(),
                email: $("#email_").val(),
                telefone: $("#telefone").val(),
                site: $("#site").val()
            }, function(resultado) {
                $("#resposta").html(resultado);
                $("#duvidas").css({
                    "display": "initial"
                });

                /*clica no botao de selecionar as imagens geradas*/
                //$('.btn').trigger('click');

            });
        });

        $("#li").click(function() {
            $("#padrao").css({
                "display": "none"
            });
            $("#envio").css({
                "display": "none"
            });
        });

        $("#duvidas").css({
            "display": "none"
        });


        var clipboard = new Clipboard('.btn');
        clipboard.on('success', function(e) {
            console.log(e);
        });
        clipboard.on('error', function(e) {
            console.log(e);
        });

    });

    function copyImages(id) {
        var divParaCopiar = document.getElementById(id);

        // Use html2canvas para converter a div em uma imagem
        html2canvas(divParaCopiar).then(function(canvas) {
            // Copie a imagem para a área de transferência
            canvas.toBlob(function(blob) {
                navigator.clipboard.write([
                    new ClipboardItem({
                        'image/png': blob
                    })
                ]);

                toastr.success('Imagens copiadas com sucesso!');
            }, 'image/png');
        });
    }
</script>
<script>
    //Funções checkbox dos campos de telefone


    function check_pessoal(selecionado) {
        document.getElementById('phonenumber').disabled = selecionado;
    }

    function check_comercial(selecionado) {
        document.getElementById('num_comercial').disabled = selecionado;

    }

    function check_ramal(selecionado) {
        document.getElementById('num_ramal').disabled = selecionado;

    }
    //Funções de máscara dos inputs de telefone
    function mask(o, f) {
        setTimeout(function() {
            var v = mphone(o.value);
            if (v != o.value) {
                o.value = v;
            }
        }, 1);
    }

    function mphone(v) {
        var r = v.replace(/\D/g, "");
        r = r.replace(/^0/, "");
        if (r.length > 10) {
            r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
        } else if (r.length > 5) {
            r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
        } else if (r.length > 2) {
            r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
        } else {
            r = r.replace(/^(\d*)/, "($1");
        }
        return r;
    }
</script>
<script>
    //Função para fazer upload de imagem
    const file = document.getElementById("imageUpload");
    const bg = document.getElementById("background");

    file.onchange = function() {
        document.getElementById("click").click();
    };
    bg.onchange = function() {
        document.getElementById("editar").click();
    };
</script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/toastr/toastr.min.js"></script>