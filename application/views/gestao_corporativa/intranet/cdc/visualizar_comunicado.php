
<!DOCTYPE html>
<section class="content">
    <div class="container-fluid">
        <div class="row" style="align-items: center;">
            <div class="col-xl-9" style=" margin-left: auto; margin-right: auto; margin-top: 10px;">

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">CDC - <?php echo $ci->codigo; ?> - <?php echo $ci->titulo; ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <p><strong>DESCRIÇÃO:</strong> <?php echo $ci->descricao; ?></p>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="wrimagecard wrimagecard-topimage" style="display: inline-block;">
                                    <a href="javascript:void(0);" onclick="document.getElementById('form').submit();">
                                        <div class="wrimagecard-topimage_header" style="background-color: #f0f5ff; ">
                                            <center><i class="fa fa-file-pdf" style="color: #82aafa;"></i></center>
                                        </div>
                                        <div class="wrimagecard-topimage_title">
                                            <span class="bold"><?php echo $ci->codigo; ?>.pdf</span>
                                        </div>
                                    </a>
                                </div>
                                <?php echo form_open(base_url('gestao_corporativa/Intranet_general/file_'), array('id' => 'form', 'method' => 'post', 'target' => '_blank')); ?>
                                <input type="hidden" name="name" value="<?php echo $ci->codigo; ?>">
                                <input type="hidden" name="file" value="<?php echo 'arquivos/cdc_arquivos/cdc/' . $ci->file; ?>">
                                <input type="hidden" name="publicado" value="1">
                                <?php echo form_close(); ?>

                            </div>

                        </div>
                    </div>




                    <!-- /.card-body -->
                    <div class="card-footer">
                        <span class="text-sm col--md-8">Criado por: <?php echo $staff->firstname . ' ' . $staff->lastname; ?>, <?php
                            echo _d($ci->data_cadastro);
                            ?>.
                        </span>
                        <br>

                        <?php if (!$ci->dt_read) { ?>
                            <a href="<?php echo base_url('gestao_corporativa/cdc/ciente/' . $ci->id); ?>" class="btn btn-primary col-md-4" >Estou Ciente</a>
                        <?php } else { ?>
                            <span class="text-sm col--md-8">Ciente em: <?php echo _d($ci->dt_read) ?>
                            </span>
                        <?php } ?>

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

<script src="<?php //echo base_url();                   ?>assets/lte/dist/js/demo.js"></script>
<style>
    .wrimagecard{
        margin-top: 0;
        margin-bottom: 1.5rem;
        text-align: left;
        position: relative;
        background: #fff;
        box-shadow: 12px 15px 20px 0px rgba(46,61,73,0.15);
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    .wrimagecard .fa{
        position: relative;
        font-size: 70px;
    }
    .wrimagecard-topimage_header{
        padding: 20px;
    }
    a.wrimagecard:hover, .wrimagecard-topimage:hover {
        box-shadow: 2px 4px 8px 0px rgba(46,61,73,0.2);
    }
    .wrimagecard-topimage a {
        width: 100%;
        height: 100%;
        display: block;
    }
    .wrimagecard-topimage_title {
        padding: 15px 15px;
        padding-bottom: 0.75rem;
        position: relative;
    }
    .wrimagecard-topimage a {
        border-bottom: none;
        text-decoration: none;
        color: #525c65;
        transition: color 0.3s ease;
    }

</style>



