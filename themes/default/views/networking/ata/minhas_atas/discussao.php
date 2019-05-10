  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>

<?php
        $acao = $this->atas_model->getPlanoByID($idplano);
        $usuario = $this->session->userdata('user_id');
//$users = $this->site->geUserByID($acao->responsavel);                              
        ?>    
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
?>


    
    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Anotações da ATA '.$ata->sequencia; ?>
              
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome'); ?>"><i class="fa fa-home"></i> Minhas Atas</a></li>
            <li class="active">Anotações</li>
          </ol>

        </section>
        <br>
    </div> 
    </div>
    
    <div class="row">  
    <div class="col-lg-12">
            <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    </div>
    </div>
    <div class="col-md-12">
                              <a class="btn btn-primary" href="audio/demos/audio.php" target="_blank">Gravar Reunião <i style="color: #ffffff;" class="fa fa-microphone"></i></a>
                            </div>   
    <section  class="content">
    <div class="row">    
       
    <?php
$ataAtual = $this->atas_model->getAtaByID($id);
 $statusAta = $ataAtual->status;
?>
    <div class="col-lg-12">
        <div class="box">
            <div  class="nav-tabs-custom">
                <ul style="background-color: #ddd; " class="nav nav-tabs">
                    <li class="active"><a href="#discussao"  data-toggle="tab"><b>Discussão ATA <i class="fa fa-file-text-o"></i></b></a></li> 
                    <li><a href="#audio" data-toggle="tab"><b>Áudios <i class="fa fa-microphone"></i></b></a></li>
                   
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="discussao">
                        <div class="row">
                            
                            <div class="clearfix"></div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("welcome/edit_discussao", $attrib);
                            echo form_hidden('id', $id);
                            ?>
                            <div class="col-md-12">
                            <!-- ITEM EVENTO -->
                            <div class="col-md-12">
                                  <div  class="form-group">
                                        <?= lang("Descrição ", "sldescricao"); ?>
                                       <?php if($statusAta == 1){ ?>
                                            <?php echo form_textarea('discussao', (isset($_POST['descricao']) ? $_POST['descricao'] : $ata->discussao), 'class="form-control  input-tip " disabled="true"  style="height: 120px;" id="sldescricao" required="true" '); ?>   
                                      <?php }else{ ?>
                                        <?php echo form_textarea('discussao', (isset($_POST['descricao']) ? $_POST['descricao'] : $ata->discussao), 'class="form-control  input-tip "   style="height: 120px;" id="sldescricao" required="true" '); ?>
                                      <?php } ?>
                                  </div>
                                </div>
                            
                               
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?= lang("Upload do Áudio", "descricao"); ?>
                                    <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document"  data-show-upload="false"
                                           data-show-preview="false" class="form-control file">
                                </div>
                            </div>
                            </div>
                            <center>

                                <div class="col-md-12">
                                      <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                        <a  class="btn btn-danger" class="close" data-dismiss="modal"  href="<?= site_url('Atas/plano_acao/'.$id); ?>"><?= lang('Sair') ?></a>

                                </div>
                                 </center>
                             <?php echo form_close(); ?>
                            <br><br><br>
                        </div>
                    </div>
                    <div class="tab-pane" id="audio">
                        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
                        <script type="text/javascript">
                        function audio_div() {
                          $.ajax({
                            type: "POST",
                            url: "themes/default/views/networking/ata/audio/exibe_audios.php?tipo=0",
                            data: {
                              ata: $('#ata').val()
                            },
                            success: function(data) {
                              $('#audio_div').html(data);
                            }
                          });
                        }
                        </script>
                         
                            <div id="audio_div">
                                <input type="hidden" id="ata" value="<?php echo $id; ?>">
                                <div class="row">
                                    <div class="col-lg-12">
                                   
                                    <script>
                                         audio_div();
                                    </script>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                </div>    
            </div>
            
            
            
            <div class="row">
                <br>
                <BR>
            </div>
            
        </div>        
    </div>
        
   
    
    
        
    </div>
   
        
    </section>    

