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

<div id="blanket"></div>
                                <div id="aguarde">Aguarde...</div>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Criar Nova ATA '); ?></h2>
    </div>
    
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("atas/add", $attrib);
              
                ?>
                <div class="row">
                    
                    
                    <div class="col-lg-12">
                           <div class="col-md-3">
                                        <div class="form-group">
                                            <?= lang("Projeto", "slProjeto"); ?>
                                                <?php
                                                $wu3[''] = '';
                                                /*foreach ($projetos as $projeto) {
                                                    $wu3[$projeto->id] = $projeto->projeto;
                                                    echo 'aquiiii'.$projeto->projeto_atual;
                                                }
                                                  * 
                                                 */
                                                echo form_dropdown('projeto_nome', $projetos->projeto, (isset($_POST['projeto']) ? $_POST['projeto'] : $projetos->projeto), 'id="slProjeto" required="required" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                               echo form_hidden('projeto', $projetos->id);
                                               // echo $projetos->projeto;
                                                ?>
                                        </div>
                                    </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <?= lang("Data Início", "dateAta"); ?>
                                <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $dateAta), 'class="form-control datetime" id="dateAta" required'); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                    <?= lang("Data Término ", "data_termino"); ?>
                                      <?php echo form_input('dateTermino', (isset($_POST['dateTermino']) ? $_POST['dateTermino'] : $dateAta), 'class="form-control input-tip datetime"  id="data_termino" '  ); ?>
                                  </div>
                            
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Local ", "sllocal"); ?>
                                <?php echo form_input('local', (isset($_POST['local']) ? $_POST['local'] : $local), 'maxlength="200" class="form-control input-tip"  id="sllocal"'); ?>
                            </div>
                        </div>
                        <script type="text/javascript">
                            function optionCheck(){
                                var option = document.getElementById("options").value;
                                if(option == "REUNIÃO CONTÍNUA"){
                                 //   document.getElementById("hiddenDiv").style.visibility ="visible";
                                    document.getElementById("hiddenDiv").style.display = "block";
                                }else{
                                    document.getElementById("hiddenDiv").style.display = "none";
                                }
                                
                                
                                 if(option == "TREINAMENTO"){
                                 //   document.getElementById("hiddenDiv").style.visibility ="visible";
                                    document.getElementById("hiddenDivTreinamento").style.display = "block";
                                }else{
                                    document.getElementById("hiddenDivTreinamento").style.display = "none";
                                }
                              
                            }
                        </script>
                        <div class="col-sm-2">
                            <div class="form-group">
                                 <?= lang("Tipo", "tipo"); ?>
                                <select name="tipo" id="options" class="form-control" onchange="optionCheck()">
                                    <option value="REUNIÃO"> REUNIÃO</option>
                                    <option value="REUNIÃO CONTÍNUA"> REUNIÃO CONTÍNUA</option>
                                    <option value="TREINAMENTO"> TREINAMENTO</option>
                                    <option value="EMAIL"> EMAIL</option>
                                    <option value="PORTARIA"> PORTARIA</option>
                                    <option value="AVULSA"> AVULSA</option>
                                </select>
                            </div>
                        </div>
                    </div>    
                        <?php
                        function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
                                  if (strlen($var) > $limite)	{
                        		$var = substr($var, 0, $limite);		
                                        $var = trim($var) . "...";	
                                        
                                  }return $var;
                                  
                                  }
                              ?>
                    
                        <div id="hiddenDiv" style="display: none;" class="col-md-12">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Item do Evento que será vinculado", "slEvento"); ?>
                                        <?php
                                        $wue[''] = '';
                                      foreach ($eventos as $evento) {
                                            $wue[$evento->id_item] = $evento->id_item.' - '. $evento->evento.' - '. resume($evento->descricao, 100);
                                           
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
                <div id="hiddenDivTreinamento" style="display: none;" class="col-md-12">   
                      <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Facilitador(es) do Treinamento", "slFacilitador"); ?>
                                <div class="well well-sm well_1">
                                    <?php
                                    //$wu4[''] = '';
                                    foreach ($users as $user) {
                                        $wu4[$user->id] = $user->first_name . ' ' . $user->last_name;
                                    }
                                    echo form_dropdown('facilitador[]', $wu4, (isset($_POST['facilitador']) ? $_POST['facilitador'] : ""), 'id="slFacilitador" multiple  class="form-control selectpicker  select" data-placeholder="' . lang("Facilitador do Treinamento") . ' "  style="width:100%;" ');
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Avaliação de Reação", "reacao"); ?>
                                <div class="well well-sm well_1">
                                    <?php
                                    $wu5['0'] = 'N/A';
                                    foreach ($avaliacoes as $avaliacao) {
                                        $wu5[$avaliacao->id] = $avaliacao->titulo . ' - ' . $avaliacao->tipo;
                                    }
                                    echo form_dropdown('reacao', $wu5, (isset($_POST['reacao']) ? $_POST['reacao'] : ""), 'id="reacao"   class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" ');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Avaliação de Aprendizagem", "slFacilitador"); ?>

                                <div class="well well-sm well_1"> 
                                   <select name="aprendizagem" id="aprendizagem" class="form-control">
                                    <option value="0">N/A</option>
                                    <option value="1"> SIM</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Avaliação de Desempenho", "desempenho"); ?>

                                <div class="well well-sm well_1"> 
                                    <?php
                                    $wu6['0'] = 'N/A';
                                     foreach ($avaliacoes as $avaliacao) {
                                        $wu6[$avaliacao->id] = $avaliacao->titulo . ' - ' . $avaliacao->tipo;
                                    }

                                    echo form_dropdown('desempenho', $wu6, (isset($_POST['desempenho']) ? $_POST['desempenho'] : ""), 'id="desempenho"   class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" ');
                                    ?>
                                </div>
                            </div>
                        </div>
                    
                    
                        
                          
                    </div> 
                    <div class="col-lg-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Responsável", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Todas Assinaturas?", "assinaturas"); ?>
                                <?php
                                //$pst2[''] = '';
                                $pst2['SIM'] = lang('SIM');
                                $pst2['NÃO'] = lang('NÃO');


                                echo form_dropdown('assinaturas', $pst2, (isset($_POST['assinaturas']) ? $_POST['assinaturas'] : $Settings->default_slpayment_status), 'id="assinaturas" class="form-control " data-placeholder="' . lang("Selecione") . ' " required="required"   style="width:100%;" ');
                                ?>

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
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Enviar Convocação?", "document") ?>
                                <select name="convocacao" id="convocacao" class="form-control" onchange="optionConvocacao()">
                                    <option value="NÃO"> NÃO</option>
                                    <option value="SIM"> SIM</option>
                                </select>
                            </div>
                        </div>        
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Anexar Documento", "document") ?>
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>
                        <div id="hiddenDivConcovacao" style="display: none;" class="col-md-12">
                            <div class="form-group">
                                <?= lang("Digite o texto da Convocação (será enviado para os participantes) : ", "sltextoconvocacao"); ?>
                                <?php echo form_textarea('texto_convocacao', (isset($_POST['texto_convocacao']) ? $_POST['texto_convocacao'] : ""), 'class="form-control" id="sltextoconvocacao"   style="margin-top: 10px; height: 150px;"'); ?>
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div> 
                      
                    <div class="col-lg-12">
                        <div class="col-md-6" >
                            <div class="form-group">
                                <?= lang("Pauta", "slpauta"); ?>

                                <?php echo form_textarea('pauta', (isset($_POST['pauta']) ? $_POST['pauta'] : $pauta), 'class="form-control" id="slpauta" required="required" style="margin-top: 10px; height: 150px;"'); ?>

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
                                <?= lang("Participantes", "slparticipantes"); ?>

                                <div class="well well-sm well_1">
                                    <?php
                                    //$wu3[''] = '';
                                    foreach ($users as $user) {
                                        $wu_participantes[$user->id] = $user->first_name . ' ' . $user->last_name;
                                    }

                                    echo form_dropdown('participantes[]', $wu_participantes, (isset($_POST['participantes']) ? $_POST['participantes'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Participantes") . ' "  style="width:100%;" ');
                                    ?>
                                </div>
                                <br><br>
                                <div class="well well-sm well_1">
                                    <?php
                                        // print_r($participantes_lista);exit;
                                        foreach ($participantes as $participante) {
                                            $cadastro_usuario = $this->site->getUser($participante->id_user);
                                            if ($cadastro_usuario) {
                                                ?>
                                                <div class="col-md-12">
                                                    <input type="checkbox" name="participantes[]"  value="<?php echo $cadastro_usuario->id; ?>"><?php echo ' ' . $cadastro_usuario->first_name . ' ' . $cadastro_usuario->last_name; ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                    ?>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Vincular Usuários (Quem recebe ou visualiza a Ata)", "slAta_usuario"); ?>
                                <div class="well well-sm well_1">
                                    <?php
                                    //$wu3[''] = '';
                                    foreach ($users as $user) {
                                        $wu_vu[$user->id] = $user->first_name . ' ' . $user->last_name;
                                    }



                                    echo form_dropdown('usuarios_vinculo[]', $wu_vu, (isset($_POST['usuarios_vinculo']) ? $_POST['usuarios_vinculo'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Usuarios para vincular") . ' "  style="width:100%;" ');
                                    ?>
                                </div>
                                <br><br>
                                <div class="well well-sm well_1">

                                    <br>

                                    <?php
                                    //  print_r($participantes_usuarios_ata);

                                    foreach ($participantes_usuarios_ata as $usu_vinculo) {

                                        $cadastro_usuario_vinculo = $this->site->getUser($usu_vinculo->id_user);

                                        if ($cadastro_usuario_vinculo) {
                                            ?>
                                            <div class="col-md-12">
                                                <input type="checkbox" name="usuarios_vinculo[]"  value="<?php echo $cadastro_usuario_vinculo->id; ?>"><?php echo ' ' . $cadastro_usuario_vinculo->first_name . ' ' . $cadastro_usuario_vinculo->last_name; ?>
                                            </div>

                                            <?php
                                        }
                                    }
                                    ?>




                                    <div class="clearfix"></div>
                                </div>


                            </div>

                        </div>
                     </div> 
                      
                    <div class="col-lg-12">                          
                        <center>
                            <div class="col-md-12">
                            <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                        <a  class="btn btn-danger"  href="<?= site_url('Atas'.$ata); ?>"><?= lang('Sair') ?></a>
                             </div>
                       
                        </center> 
                             
                    
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

    <br><br>
    <script>
     function alertas(){
         
    
    document.getElementById('blanket').style.display = 'block';
    document.getElementById('aguarde').style.display = 'block';
   
    
    }
    </script>