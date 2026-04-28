<style>
    ul.breadcrumb {
        padding: 10px 16px;
        list-style: none;
        background-color: white;
    }
    ul.breadcrumb li {
        display: inline;
        font-size: 18px;
    }
    ul.breadcrumb li+li:before {
        padding: 8px;
        color: black;
        content: "/\00a0";
    }
    ul.breadcrumb li a {
        color: #0275d8;
        text-decoration: none;
    }
    ul.breadcrumb li a:hover {
        color: #01447e;
        text-decoration: underline;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row" style="align-items: center;">

            <div class="col-md-12" style="margin-top: 10px;">
                <ul class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intra/Comunicado'); ?>"><i class="fa fa-users"></i> Comunicados Internos </a></li> 
                    <li>CI - <?php echo $ci->codigo; ?> </li> 
                </ul>
            </div>
            <div class="col-xl-10" style=" margin-left: auto; margin-right: auto; margin-top: 10px;">

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">CI - <?php echo $ci->codigo; ?> - <?php echo $ci->titulo; ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <p><strong>TÍTULO:</strong> <?php echo $ci->titulo; ?></p>
                        <?php
                        $this->load->model('Comunicado_model');
                        $sends = $this->Comunicado_model->get_comunicado_send($ci->id);
                        if (count($sends) > 0) {
                            ?>
                            <p><strong>DESTINATÁRIOS: </strong>  <?php
                                foreach ($sends as $send) {
                                    echo $send['firstname'] . ' ' . $send['lastname'] . ', ';
                                }
                                ?></p>
                        <?php } ?>
                        <?php
                        $this->load->model('Comunicado_model');
                        $sends = $this->Comunicado_model->get_comunicado_send($ci->id, '1');
                        if (count($sends) > 0) {
                            ?>
                            <p><strong>COM CÓPIA:</strong> <?php
                                foreach ($sends as $send) {
                                    echo $send['firstname'] . ' ' . $send['lastname'] . ', ';
                                }
                                ?></p>

                        <?php } ?>
                        <p><?php echo $ci->descricao; ?></p>
                        <div class="row">
                            <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                                <?php
                                if ($ci->docs) {
                                    $docs = explode(",", $ci->docs);
                                    if (count($docs) > 0):
                                        foreach ($docs as $doc):

                                            $extensao = pathinfo($doc, PATHINFO_EXTENSION);

                                            if ($extensao == 'img' || $extensao == 'jpg' || $extensao == 'jpeg' || $extensao == 'png') {
                                                $icon = '<img src="' . base_url("assets/intranet/img/ci/$doc") . '" alt="Attachment" style="width: 150px; max-height: 100px;"> ';
                                            } elseif ($extensao == 'pdf') {
                                                $icon = '<i class="far fa-file-pdf"></i>';
                                            } elseif ($extensao == 'xlsx' || $extensao == 'zip' || $extensao == 'mp4' or $extensao == 'gif') {
                                                $icon = '<i class="fas fa-paperclip"></i>';
                                            } elseif ($extensao == 'txt' || $extensao == 'ppsx' or $extensao == 'ppt') {
                                                $icon = '<i class="far fa-file-word"></i>';
                                            }
                                            $file_size = filesize(base_url("assets/intranet/img/ci/$doc"));
                                            ?>

                                            <li>
                                                <span class="mailbox-attachment-icon"><?php echo $icon; ?></span>
                                                <div class="mailbox-attachment-info">
                                                    <a href="<?php echo base_url("assets/intranet/img/ci/$doc"); ?>" target="_blank" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> <?php echo $doc; ?></a>

                                                </div>
                                            </li>
                                            <?php
                                        endforeach;
                                    endif;
                                }
                                ?>
                            </ul>
                            <?php
                            if ($ci->retorno == 1) {
                                $this->load->model('Comunicado_model');
                                $respostas = $this->Comunicado_model->get_comunicado_send_repostas($ci->id);
                                if (count($respostas) > 0) {
                                    ?>

                                    <div class="card-footer card-comments w-100" style="margin-top: 20px;">
                                        <h5>Respostas:</h5>
                                        <hr>
                                        <?php foreach ($respostas as $resposta) { ?>
                                            <div class="card-comment">


                                                <?php
                                                echo staff_profile_image($resposta['staff_id'], [
                                                    'img-circle img-sm',
                                                ]);
                                                ?>
                                                <div class="comment-text">
                                                    <span class="username">
                                                        <?php echo $resposta['firstname'] . ' ' . $resposta['lastname']; ?>
                                                        <span class="text-muted float-right"><?php
                                                            echo date("d/m/Y H:i:s", strtotime($resposta['dt_ciente']));
                                                            ;
                                                            ?></span>
                                                    </span>
                                                    <?php echo $resposta['retorno']; ?>
                                                </div>
                                                <hr>

                                            </div>
                                        <?php } ?>

                                    </div>
                                    <?php
                                }
                            }
                            ?>



                        </div>
                    </div>





                    <!-- /.card-body -->
                    <div class="card-footer">
                        <span class="text-sm col--md-8">Criado por: <?php echo $staff->firstname . ' ' . $staff->lastname; ?>, <?php
                            $DataEspecifica = new DateTime($ci->data_cadastro);
                            echo $DataEspecifica->format('d/m/Y H:i:s');
                            ?>.
                        </span>
                        <br>
                        <a type="button" class="btn btn-primary col-md-4" href="<?php echo base_url(); ?>gestao_corporativa/intra/comunicado">Fechar</a>

                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>

        <!-- /.col -->

        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
