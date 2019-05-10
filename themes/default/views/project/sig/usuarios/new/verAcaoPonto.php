
<div style="width: 700px;" class="modal-dialog">
        <div class="modal-content">
        <div class="portlet portlet-default">
            <div class="portlet-heading">
                <div class="portlet-title">
                    <h4>Detalhes da Ação</h4>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="portlet-body">
                <div class="table-responsive">
                    <?php           
                    $idplano = $id;
                    $acao =  $this->atas_model->getPlanoByID($id);                    
                    $usuario = $this->session->userdata('user_id');   
                    $users = $this->site->geUserByID($acao->responsavel);                              
                        ?>


                       <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("welcome/manutencao_acao_form/" . $acao->idplanos, $attrib); 
                            echo form_hidden('id', $acao->idplanos);
                        ?>


                        <div style="margin-left: 10px; margin-right: 10px;" class="row pricing-circle">
                            <br>

                            
                                <div class="col-md-6">
                                  <h3 >   AÇÃO : <?PHP ECHO $idplano; ?> </h3>

                                </div>
                                
                               
                            


                             <?php //O QUE ?>    
                            <div class="col-md-12">
                                  <h3>Descrição</h3>
                                <div class="col-md-12"  >
                                    <div class="form-group">

                                        <?php echo $acao->descricao; ?>

                                    </div>
                                </div>
                            </div>

                            
                           
                            <div class="col-md-12">
                                        <h3>Observação</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">

                                                 <div   name='observacao'  >
                                                        <font style="font-size: 14px; text-align: justify;">   <?php echo $acao->observacao; ?></font>
                                                </div>

                                            </div>
                                        </div>
                                     </div>


                            <script>
                            function goForward() {
                               window.history.go(-1);
                            }
                            </script>




                    </div>
   
        <?php echo form_close(); ?>

                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.portlet-body -->
        </div>
      </div>
</div>    
