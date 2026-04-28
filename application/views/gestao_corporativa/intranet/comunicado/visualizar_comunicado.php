
<!DOCTYPE html>
<section class="content">
    <div class="container-fluid">
        <div class="row" style="align-items: center;">
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
                            <p><strong>DESTINATÁRIOS: </strong>  <?php foreach ($sends as $send) {
                                echo $send['firstname'] . ' ' . $send['lastname'] . ', ';
                            } ?></p>
                        <?php } ?>
                        <?php
                        $this->load->model('Comunicado_model');
                        $sends = $this->Comunicado_model->get_comunicado_send($ci->id, '1');
                        if (count($sends) > 0) {
                            ?>
                            <p><strong>COM CÓPIA:</strong> <?php foreach ($sends as $send) {
                                echo $send['firstname'] . ' ' . $send['lastname'] . ', ';
                            } ?></p>
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

<?php if ($ci->retorno == 1 && $send_staff->cc == 0) { ?>
                            <a type="button" class="btn btn-primary col-md-4"  data-toggle="modal" data-target="#modal-default">Estou Ciente</a>
<?php } else { ?>
                            <a type="button" class="btn btn-primary col-md-4" href="<?php echo base_url(); ?>gestao_corporativa/intra/Comunicado/ciente?id=<?php echo $send_staff->id; ?>">Estou Ciente</a>
<?php } ?>
                        <div class="modal fade" id="modal-default">
                            <div class="modal-dialog">
                                <div class="modal-content bg-default">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Este comunicado exige um retorno</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
<?php echo form_open("gestao_corporativa/intra/Comunicado/ciente?id=$send_staff->id", array("id" => "ci_send-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Retorno Sobre o Comunicado</label>
                                            <textarea name="retorno" class="form-control" rows="3" placeholder="Escreva ..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                        <button type="submit" class="btn btn-primary">Estou Ciente!</button>
                                    </div>
<?php echo form_close(); ?>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>

            <!-- /.col -->

            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="<?php echo base_url(); ?>assets/lte/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url(); ?>assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url(); ?>assets/lte/dist/js/adminlte.min.js?v=3.2.0"></script>

<script src="<?php //echo base_url();              ?>assets/lte/dist/js/demo.js"></script>



