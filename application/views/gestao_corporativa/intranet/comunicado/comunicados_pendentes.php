<section class="content">

    <div class="row">



        <!-- WIDGETS -->

        <div class="col-lg-12 position-relative ">

            <div class="mb-0 ">

                <div class="p-3">

                    <div class="row">
                        <h1>Comunicados Internos Pendentes</h1>

                        <div class="col-md-12 mtop30" data-container="top-12">

                            <div class="alert alert-warning alert-dismissible">

                                <i class="icon fas fa-exclamation-triangle"></i> ATENÇÃO!<br>

                                Você precisa dar CIÊNCIA nos seguintes comunicados:

                            </div>

                            <div class="widget relative" id="widget-comunicados" data-name="comunicados">

                                <div class="widget-dragger"></div>



                                <div class="box box-primary">



                                    <div class="box-body">



                                        <?php
                                        foreach ($comunicados as $comunicado) {
                                            $ci_id = $comunicado['ci'];
                                            $send_id = $comunicado['send_id'];
                                            ?>

                                            <div class="callout ">
                                                <a class="btn btn-sm bg-info" href="<?php echo base_url(); ?>gestao_corporativa/intra/Comunicado/visualizar_comunicado/?id=<?php echo $ci_id; ?>&send_id=<?php echo $send_id; ?>">CI <?php echo $comunicado['codigo']; ?></a>

                                            </div>

                                        <?php } ?>



                                    </div>

                                </div>

                            </div>

                        </div>    

                    </div>
                </div>
            </div>
        </div>
    </div>    
</section>    
