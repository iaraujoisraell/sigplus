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
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
?>
<div id="blanket"></div>
<div id="aguarde">Aguarde...</div>

    <section  class="content">
    <div class="col-lg-12">
    <div class="row">    
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Nova Ata'; ?>
              <small><?php echo $nome_projeto; ?></small>
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li >Ata</li>
            <li class="active">Nova Ata</li>
          </ol>

        </section>
        <br>
    </div>    
    
   
        <div class="box">
        <div class="modal-header">
            
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Abertura de Ata'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("project/novaAta", $attrib); 
            echo form_hidden('id_cadastro', '1'); 
             echo form_hidden('tipo_ata', $tipo_ata);
            echo form_hidden('idplano', $id_plano);
            echo form_hidden('projeto', $id_projeto);
       
            
        ?>
            
            <div class="row">
               
                        <div class="col-lg-12">
                           
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Data", "dateAta"); ?>
                                <input name="dateAta" required="true" class="form-control" type="date" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("hora Início ", "data_termino"); ?>
                                <input name="hora_inicio" required="true" class="form-control" type="time" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("hora Fim ", "data_termino"); ?>
                                <input name="hora_fim" required="true" class="form-control" type="time" >
                            </div>
                        </div>    
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Local ", "sllocal"); ?>
                                <?php echo form_input('local', (isset($_POST['local']) ? $_POST['local'] : $local), 'maxlength="200" required class="form-control input-tip"  id="sllocal"'); ?>
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                 <?= lang("Tipo", "tipo"); ?> <i class="fa fa-info-circle" title="A Ata do tipo REUNIÃO, é a forma padrão de um cadastro de ATA. Podendo regisrar quantas atas de reuniões for preciso. O tipo REUNIÃO CONTÍNUA, é para quando você quiser gerenciar reuniões de um assunto específico e determinado, relacionado a um item de evento específico do escopo. Por Exemplo: Se a reunião é do tipo PLANEJAMENTO, e haverá outras reuniões sobre esse mesmo assunto, pode vincular ao item do escopo que se refere ao planejamento, assim poderá acompanhar as ações definidas em reuniões e a este item."></i>
                                <select name="tipo" id="options" class="form-control" onchange="optionCheck()">
                                    <option value="REUNIÃO"> REUNIÃO</option>
                                    <option value="REUNIÃO CONTÍNUA"> REUNIÃO CONTÍNUA</option>
                                  
                                </select>
                            </div>
                        </div>
                    </div>    
                        <div id="hiddenDiv" style="display: none;" class="col-md-12">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Vincular Item do Evento (escopo) a esta ATA", "slEvento"); ?>
                                        <?php
                                        $wue[''] = '';
                                      foreach ($eventos as $evento) {
                                            $wue[$evento->id] = $evento->nome_fase.' > '. $evento->nome_evento.' > '. resume($evento->descricao, 100);
                                           
                                        }
                                        echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : ""), 'id="slEvento"  class="form-control  select" data-placeholder="' . lang("Selecione o Item do Evento") . ' "  style="width:100%;"  ');
                                        ?>
                                    </div>
                                </div>
                        </div>
                    <style>
                        textarea.form-control {
                          height: 100%;
                        }
                        textarea { 
                           min-height: 100%;
                        }
                    </style>
                        
                    <div class="col-lg-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Responsável", "slelaboracao"); ?><i class="fa fa-info-circle" title="É a pessoa Líder, quem convocou ou quem irá conduzir a Ata."></i>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        
                        <script>
                         function optionConvocacao(){
                                var option = document.getElementById("convocacao").value;
                                if(option == "SIM"){
                                 //   document.getElementById("hiddenDiv").style.visibility ="visible";
                                    document.getElementById("hiddenDivConcovacao").style.display = "block";
                                }else{
                                    document.getElementById("hiddenDivConcovacao").style.display = "none";
                                }
                              
                            }
                        </script>
                            
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Anexar Documento", "document") ?>
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>
                        <div id="hiddenDivConcovacao" style="display: none;" class="col-md-12">
                            <div class="form-group">
                                <?= lang("Digite o texto da Convocação (será enviado para os participantes) : ", "sltextoconvocacao"); ?>
                                <?php echo form_textarea('texto_convocacao', (isset($_POST['texto_convocacao']) ? $_POST['texto_convocacao'] : ""), 'class="form-control" id="sltextoconvocacao"  title="O Texto de convocação será enviado por email para os participantes. " style="margin-top: 10px; height: 150px;"'); ?>
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div> 
                    
                    <div class="col-lg-12">
                    <div class="col-sm-12">
                            <div class="form-group">
                                <?= lang("Assunto ", "assunto"); ?>
                                <?php echo form_input('assunto', (isset($_POST['assunto']) ? $_POST['assunto'] : $assunto), 'maxlength="250" required class="form-control input-tip"  id="assunto"'); ?>
                            </div>
                        </div>
                    </div>
                      
                    <div class="col-lg-12">
                        <div class="col-md-6" >
                            <div class="form-group">
                                <?= lang("Pauta", "slpauta"); ?><i class="fa fa-info-circle" title="Os principais Assuntos da Reunião."></i>

                                <?php echo form_textarea('pauta', (isset($_POST['pauta']) ? $_POST['pauta'] : $pauta), 'class="form-control" id="slpauta" required style="margin-top: 10px; height: 150px;"'); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Observação", "slnote"); ?>
                                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                            </div>
                        </div>
                    </div> 
                                      
                    <div class="col-lg-12">    
                        <div class="col-md-6" >
                            <div class="form-group">
                                <?= lang("Participantes", "slparticipantes"); ?><i class="fa fa-info-circle" title="As pessoas presente. "></i>

                                <div class="well well-sm well_1">
                                    <?php
                                    //$wu3[''] = '';
                                    foreach ($users as $user) {
                                        $wu_participantes[$user->id] = $user->nome . ' - ' . $user->setor;
                                    }

                                    echo form_dropdown('participantes[]', $wu_participantes, (isset($_POST['participantes']) ? $_POST['participantes'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Participantes") . ' "  style="width:100%;" ');
                                    ?>
                                </div>
                                <br><br>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Vincular Usuários ", "slAta_usuario"); ?> <i class="fa fa-info-circle" title="Os usuários vinculados podem visualizar o conteúdo completo da Ata, através do SIGPLUS.O usuário vinculado não precisa ser um participante da ATA."></i>
                                <div class="well well-sm well_1">
                                    <?php
                                    //$wu3[''] = '';
                                    foreach ($users as $user) {
                                        $wu_vu[$user->id] = $user->nome . ' - ' . $user->setor;
                                    }



                                    echo form_dropdown('usuarios_vinculo[]', $wu_vu, (isset($_POST['usuarios_vinculo']) ? $_POST['usuarios_vinculo'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Usuarios para vincular") . ' "  style="width:100%;" ');
                                    ?>
                                </div>
                                <br><br>
                                


                            </div>

                        </div>
                     </div> 
                   
               
            </div>


        
        <div class="modal-footer">
            <div class="col-lg-12">                          
                        <center>
                            <div class="col-md-12">
                            <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                        <a  class="btn btn-danger"  href="<?= site_url('Atas'.$ata); ?>"><?= lang('Sair') ?></a>
                             </div>
                       
                        </center> 
                             
                    
                </div>

        </div>
    
        <?php echo form_close(); ?>
            <!-- /.modal-content -->
        </div>        
    
   
    
    </div>
    </div>     
    </section>    
