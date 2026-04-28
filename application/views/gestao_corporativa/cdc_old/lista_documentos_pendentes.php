<section class="content">

                <div class="row">
                    
                    <h1>Documentos Pendentes</h1>

                    <!-- WIDGETS -->

                    <div class="col-lg-12 position-relative ">

                        <div class="mb-0 ">

                            <div class="p-3">

                                <div class="row">

                                    <div class="col-md-12 mtop30" data-container="top-12">

                                        <div class="alert alert-warning alert-dismissible">

                                                        <i class="icon fas fa-exclamation-triangle"></i> ATENÇÃO!<br>

                                                        Você precisa dar CIENTE nos seguintes Documentos:

                                                    </div>

                                        <div class="widget relative" id="widget-comunicados" data-name="comunicados">

                                            <div class="widget-dragger"></div>

                                            

                                            <div class="box box-primary">



                                                <div class="box-body">

                                                    

                                                    <?php foreach ($documentos as $documento) {
                                                        $ci_id = $documento['id'];
                                                        $send_id = $documento['send_id'];
                                                        ?>

                                                        <div class="callout ">
                                                            
                                                            <h5><?php echo $documento['titulo']; ?></h5>

                                                            <p><?php echo $documento['descricao']; ?></p>

                                                            <a class="btn btn-sm bg-info" href="<?php echo base_url(); ?>gestao_corporativa/intranet/visualizar_documento/<?php echo $send_id; ?>">VER DOCUMENTO </a>

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
      