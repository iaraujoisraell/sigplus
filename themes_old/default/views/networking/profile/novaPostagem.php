
  
<div  class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Nova Publicação'); ?></h4>
        </div>
        
        <div class="modal-body">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#postagem" data-toggle="tab">Postagem</a></li>
              <li><a href="#youtube" data-toggle="tab">Youtube</a></li>
             
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="postagem">
                   
                        <div class="box">
                            <div class="box-header">
                              <h3 class="box-title">Publicação
                                <small></small>
                              </h3>
                              <!-- tools box -->
                              
                              <!-- /. tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body pad">
                             <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
                                echo form_open_multipart("welcome/novaPostagem", $attrib); 
                                echo form_hidden('nova_postagem', '1'); 


                                $date_hoje = date('Y-m-d');   

                            ?>
                                
                                      <textarea class="textarea" name="texto" placeholder="Escreva sua publicação aqui..."
                                              style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                      
                                       <!--
                                       <h3 class="box-title">Exibir no mural Público?</h3>       
                                       <input type="radio" name="tipo" value="1" checked="true">Sim        
                                       <input type="radio" name="tipo" value="2">Não
                                       -->
                                       
                                    <h3 class="box-title">Imagens (máx. 5)
                                    <small> a imagem 1 é a principal.</small>
                                  </h3>
                                       <input id="document1" type="file" name="document1"  data-show-upload="false" data-show-preview="false" class="form-control file">              
                                       <input id="document2" type="file" name="document2"  data-show-upload="false" data-show-preview="false" class="form-control file">      
                                       <input id="document3" type="file" name="document3"  data-show-upload="false" data-show-preview="false" class="form-control file">              
                                       <input id="document4" type="file" name="document4"  data-show-upload="false" data-show-preview="false" class="form-control file">
                                       <input id="document5" type="file" name="document5"  data-show-upload="false" data-show-preview="false" class="form-control file">
                                      <div class="modal-footer">
                                <?php echo form_submit('add_customer', lang('Publicar'), 'class="btn btn-primary"'); ?>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                            </div>
                </div>
                   
                <div class="tab-pane" id="youtube">
                    <div class="box">
                            <div class="box-header">
                              <h3 class="box-title">Link
                                <small></small>
                              </h3>
                              <!-- tools box -->
                              
                              <!-- /. tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body pad">
                            </div>
                    </div>        
                </div>
                </div>
                
            </div>
            </div>
            
            


        </div>
   
            <!-- /.modal-content -->
          </div>

