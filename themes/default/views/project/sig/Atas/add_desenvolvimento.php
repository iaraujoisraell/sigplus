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
            
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Criar Nova ATA  - Desenv'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("atas/add_desenvolvimento", $attrib);
              
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
                             
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Data da Ata", "dateAta"); ?>
                                <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $dateAta), 'class="form-control datetime" id="dateAta"'); ?>
                            </div>
                        </div>
                        
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Local da ATA", "sllocal"); ?>
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
                              
                            }
                        </script>
                        <div class="col-sm-3">
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
                        
                    <div id="hiddenDiv" style="display: none;" class="col-md-12">
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Evento", "slEvento"); ?>
                                        <?php
                                        $wue[''] = '';
                                      foreach ($eventos as $evento) {
                                            $wue[$evento->id] = $evento->nome_evento;
                                           
                                        }
                                        echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : ""), 'id="slEvento"  class="form-control  select" data-placeholder="' . lang("Selecione o Evento") . ' "  style="width:100%;"  ');
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Responsável por Elaborar a Ata", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                            </div>
                        </div> 


                        <div class="col-sm-4">
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Anexar ATA", "document") ?>
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>
                        
                        
                        <div class="clearfix"></div>
                       
                                    
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
                    
                                          
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <?= lang("Participantes da ATA", "slparticipantes"); ?> *
                                       
                                        <div class="well well-sm well_1">
                                            <?php
                                        //$wu3[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->first_name.' '.$user->last_name;
                                        }
                                        
                                       
                                        
                                        echo form_dropdown('participantes[]', $wu4, (isset($_POST['participantes']) ? $_POST['participantes'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Outros Participantes") . ' "  style="width:100%;" ');
                                        ?>
                                        </div>
                                        
                                        <br><br>
                                    
                                    <div class="well well-sm well_1">
                                        
                                            
                                            
                                            <?php
                                           // print_r($participantes_lista);exit;
                                            foreach ($participantes as $participante) {
                                                
                                                $cadastro_usuario =  $this->site->getUser($participante->id_user);
                                                
                                                if($cadastro_usuario){
                                                ?>
                                                <div class="col-md-12">

                                                    <input type="checkbox" name="participantes[]"  value="<?php echo $cadastro_usuario->id; ?>"><?php echo ' '. $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name; ?>

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
                                    <?= lang("Vincular Usuários a ATA", "slAta_usuario"); ?>
                                     <div class="well well-sm well_1">
                                     <?php
                                        //$wu3[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->first_name.' '.$user->last_name;
                                        }
                                        
                                       
                                        
                                        echo form_dropdown('usuario_ata[]', $wu4, (isset($_POST['usuario_ata']) ? $_POST['usuario_ata'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Outros usuarios para vincular a ATA") . ' "  style="width:100%;" ');
                                        ?>
                                      </div>
                                    <br><br>
                                    <div class="well well-sm well_1">

                                            <br>

                                            <?php
                                          //  print_r($participantes_usuarios_ata);
                                            
                                            foreach ($participantes_usuarios_ata as $usu_vinculo) {
                                                
                                                $cadastro_usuario_vinculo =  $this->site->getUser($usu_vinculo->id_user);
                                                
                                                if($cadastro_usuario_vinculo){
                                                ?>
                                                <div class="col-md-12">
                                                    <input type="checkbox" name="usuarios_vinculo[]"  value="<?php echo $cadastro_usuario_vinculo->id; ?>"><?php echo ' '. $cadastro_usuario_vinculo->first_name.' '.$cadastro_usuario_vinculo->last_name; ?>
                                                </div>
                                             
                                                <?php
                                                }
                                            }
                                            ?>


                                            

                                            <div class="clearfix"></div>
                                        </div>
                           

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                                           
                            
                             
                    
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>


