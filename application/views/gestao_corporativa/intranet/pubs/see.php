<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet(false); ?>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">

   <?php $this->load->view('gestao_corporativa/intranet/navbar.php'); ?>   

        <div class="content-wrapper">
    <div class="row" style="padding: 10px;">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $aviso->titulo;?></h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <!-- /.mailbox-read-info -->
                    <div class="mailbox-controls with-border text-center">
                        <img <?php if($aviso->tipo == '1'){?> class="w-100"<?php }?>height="500" src="<?php echo base_url(); ?>assets/intranet/img/avisos/<?php echo $aviso->foto; ?>" alt="Photo">
                    </div>
                    <!-- /.mailbox-controls -->
                    <div class="mailbox-read-message">
                        <?php echo $aviso->descricao; ?>
                        <?php if ($aviso->link): ?>
                            <p class="">Link anexado: <a href="<?php echo $aviso->link; ?>" target="_blank"><?php echo $aviso->link; ?></a></p>
                        <?php endif; ?>
                    </div>
                    <!-- /.mailbox-read-message -->
                </div>
                <div class="card-footer">
                    <div class="mailbox-read-info">
                        <h5>Publicação - Intranet</h5>
                        <h6>De: <?php echo $aviso->firstname . ' ' . $aviso->lastname; ?>
                            <span class="mailbox-read-time float-right"><?php
                                $DataEspecifica = new DateTime($aviso->data_cadastro);
                                echo $DataEspecifica->format('d-m-Y');
                                ?></span></h6>
                    </div>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
        <!-- FOOTER -->


        <!-- Control Sidebar -->
<?php //$this->load->view('gestao_corporativa/intranet/control_sidebar.php');  ?>
        <!-- /.control-sidebar -->
    </div>




</div>

<?php $this->load->view('gestao_corporativa/intranet/footer.php'); ?>


</div>


<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="<?php echo base_url();?>assets/lte/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url();?>assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url();?>assets/lte/dist/js/adminlte.min.js?v=3.2.0"></script>

<script src="<?php echo base_url();?>assets/lte/dist/js/demo.js"></script>
</body>
</html>
