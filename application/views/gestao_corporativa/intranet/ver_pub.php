<!-- CONTENT -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $aviso->titulo;?></h3>

                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <!-- /.mailbox-read-info -->
                    <div class="mailbox-controls with-border text-center">
                        <img height="500" src="<?php echo base_url(); ?>assets/intranet/img/avisos/<?php echo $aviso->foto; ?>" alt="Photo">
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
                        <h6>De: <?php echo $aviso->staff->firstname . ' ' . $aviso->staff->lastname; ?>
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
