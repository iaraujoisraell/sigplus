  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>
<script>
     if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            
        if (!localStorage.getItem('sldate')) {
            $("#sldate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
            
            $(document).on('change', '#sldate', function (e) {
            localStorage.setItem('sldate', $(this).val());
        });
        if (sldate = localStorage.getItem('sldate')) {
            $('#sldate').val(sldate);
        }
         
         
         
                   $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});


onload : optionCheck();
</script>
<style>
    #blanket,#aguarde {
    position: fixed;
    display: none;
}

#blanket {
    left: 0;
    top: 0;
    background-color: #f0f0f0;
    filter: alpha(opacity =         65);
    height: 100%;
    width: 100%;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
    opacity: 0.65;
    z-index: 9998;
}

#aguarde {
    width: auto;
    height: 30px;
    top: 40%;
    left: 45%;
    background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%; 
    line-height: 30px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    z-index: 9999;
    padding-left: 27px;
}
</style> 
<script type="text/javascript">
    function optionCheck(){
        var option = document.getElementById("options").value;
        if(option == "REUNIÃO CONTÍNUA"){
         //   document.getElementById("hiddenDiv").style.visibility ="visible";
            document.getElementById("hiddenDiv").style.display = "block";
        }else{
            document.getElementById("hiddenDiv").style.display = "none";
        }


    }
</script>
   
    <?php
$ataAtual = $this->atas_model->getAtaByID($id);
 $statusAta = $ataAtual->status;
?>
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
?>
<div id="blanket"></div>
<div id="aguarde">Aguarde...</div>

    
    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Anotações da ATA : '.$ataAtual->sequencia; ?>
              <small><?php echo $nome_projeto; ?></small>
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Ata</li>
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
    
    <section  class="content">
    <div class="row">    
       

    <div class="col-lg-12">
        <div class="box">
            <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <h2>  Registro das Anotações  </h2>
                                    </center>
                                </div>
                            </div>
                              <div id="blanket"></div>
                                <div id="aguarde">Aguarde...Enviando Email</div>
                            <div class="clearfix"></div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("atas/edit_discussao", $attrib);
                            echo form_hidden('id', $id);
                            ?>
                            <div class="col-md-12">
                                
                                <div class="col-md-12">    
                                    <div class="form-group">
                                            <?= lang("Título", "sldate"); ?><small></small>
                                            <i class="fa fa-info-circle" title="Título da Discussão da Reunião."></i>
                                            <input type="text" value="<?php echo $ata->titulo_discussao; ?>" placeholder="Ex: Resumo da Ata, Principais Assuntos, etc." name="titulo_discussao" maxlength="250" class="form-control pull-right" id="titulo_discussao">
                                    </div>
                                </div>        
                                
                            <!-- ITEM EVENTO -->
                                <div class="col-md-12">
                                  <div  class="form-group">
                                        <?= lang("Anotações ", "sldescricao"); ?>
                                       <?php if($statusAta == 1){ ?>
                                            <?php echo form_textarea('discussao', (isset($_POST['descricao']) ? $_POST['descricao'] : $ata->discussao), 'class="form-control  input-tip " disabled="true"  style="height: 120px;" id="sldescricao" required="true" '); ?>   
                                      <?php }else{ ?>
                                          <textarea   id="discussao" name="discussao" rows="10" required="true" cols="80">
                                              <?php echo $ata->discussao; ?>
                                          </textarea>
                                        <?php // echo form_textarea('discussao', (isset($_POST['descricao']) ? $_POST['descricao'] : $ata->discussao), 'class="form-control  input-tip "   style="height: 120px;" id="sldescricao" required="true" '); ?>
                                      <?php } ?>
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
            
            
            <div class="row">
                <br>
                <BR>
            </div>
            
        </div>        
    </div>
    </div>
   
        
    </section>    
