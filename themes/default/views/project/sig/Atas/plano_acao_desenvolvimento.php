<script>
  localStorage.setItem('sldateEntregaDemanda', '<?= $this->sma->hrld($data_hoje) ?>');
  
    $("#sldateEntregaDemanda").datetimepicker({
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
            
            $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});
</script>
<script>
     if (localStorage.getItem('sldateEntrega')) {
                localStorage.removeItem('sldateEntrega');
            }
            
        if (!localStorage.getItem('sldateEntrega')) {
            $("#sldateEntrega").datetimepicker({
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
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('PLANO DE AÇÃO DA ATA - Desenv '); ?><?php echo $id; ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

               
               
                <div class="row">
                    <div id="blanket"></div>
                    <div id="aguarde">Aguarde...Enviando Email</div>
                    <center><h2>DADOS DA ATA</h2></center>
                    <div style="border-width: 1px; border-style: solid; border-color: #128f76;   " class="col-lg-12">
                        
                           <div class="row">
            <div class="col-lg-12">

               
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("Atas/edit/$id", $attrib);
                 echo form_hidden('id', $id);
                 
                 
                 $ataAtual = $this->atas_model->getAtaByID($id);
                    $statusAta = $ataAtual->status;
                    $avulsa = $ataAtual->avulsa;
                ?>
                
                
                <div class="row">
                    <div class="col-lg-12">
                           <div class="col-md-3">
                                        <div class="form-group">
                                            <?= lang("Projeto", "slProjeto"); ?>
                                           
                                             
                                                <?php
                                                 $usuario = $this->session->userdata('user_id');
                                                 $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                                                 $projetos_usuario->projeto_atual;
                                                 
                                                 
                                                 
                                                $wu3[''] = '';
                                                foreach ($projetos as $projeto) {
                                                    $wu3[$projeto->id] = $projeto->projeto;
                                                }
                                                echo form_dropdown('projeto', $wu3, (isset($_POST['projeto']) ? $_POST['projeto'] : $projetos_usuario->projeto_atual), 'id="slProjeto" required="required" class="form-control  select" disabled data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                                ?>
                                                
                                                
                                        </div>
                                    </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <?= lang("Data da ATA", "sldate"); ?>
                                    <?php if($statusAta == 1){ ?>
                                    <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($ata->data_ata)), 'class="form-control input-tip datetime"  id="sldate" disabled required=$projeto"required"'  ); ?>
                                    <?php }else{ ?>
                                     <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($ata->data_ata)), 'class="form-control input-tip datetime"  id="sldate" required=$projeto"required"'  ); ?>
                                  
                                    <?php } ?>
                                </div>
                            </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Local da ATA", "sllocal"); ?>
                                <?php if($statusAta == 1){ ?>
                                <?php echo form_input('local', (isset($_POST['local']) ? $_POST['local'] : $ata->local), 'maxlength="200" disabled class="form-control input-tip" required="required" id="sllocal"'); ?>
                                <?php }else{ ?>
                                   <?php echo form_input('local', (isset($_POST['local']) ? $_POST['local'] : $ata->local), 'maxlength="200" class="form-control input-tip" required="required" id="sllocal"'); ?>
                                      <?php } ?>
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
                                <?php $pst[''] = '';
                                  $pst['REUNIÃO'] = lang('REUNIÃO');
                                  $pst['REUNIÃO CONTÍNUA'] = lang('REUNIÃO CONTÍNUA');
                                  $pst['TREINAMENTO'] = lang('TREINAMENTO');
                                  $pst['EMAIL'] = lang('EMAIL');
                                  $pst['PORTARIA'] = lang('PORTARIA');
                                  $pst['AVULSA'] = lang('AVULSA');
                                  
                                  ?>
                                 <?php if($statusAta == 1){ 
                                      echo form_dropdown('tipo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $ata->tipo), 'id="tipo" disabled class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Ata") . '" required="required"   style="width:100%;" ');
                                     ?> 
                                
                                <?php }else{ ?>
                                
                                    <select name="tipo" id="options" class="form-control" onchange="optionCheck()">
                                            <option value="<?php echo $ata->tipo ?>"> <?php echo $ata->tipo ?></option>
                                            <option value="REUNIÃO"> REUNIÃO</option>
                                            <option value="REUNIÃO CONTÍNUA"> REUNIÃO CONTÍNUA</option>
                                            <option value="TREINAMENTO"> TREINAMENTO</option>
                                            <option value="EMAIL"> EMAIL</option>
                                            <option value="PORTARIA"> PORTARIA</option>
                                            <option value="AVULSA"> AVULSA</option>
                                        </select>
                                
                                   
                                    <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                     <?php if($ata->tipo == 'REUNIÃO CONTÍNUA'){ ?>
                            <div class="col-md-12">
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Evento", "slEvento"); ?>
                                        <?php
                                        $wue[''] = '';
                                      foreach ($eventos as $evento) {
                                            $wue[$evento->id] = $evento->nome_evento;
                                           
                                        }
                                        echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : $ata->evento), 'id="slEvento"  class="form-control  select" data-placeholder="' . lang("Selecione o Evento") . ' "  style="width:100%;"  ');
                                        ?>
                                    </div>
                                    </div>
                                   
                                </div>
                    <?PHP }else{ ?>          
                    
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
                    <?php } ?>
                         
                    <div class="col-md-12">
                      <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Responsável por Elaborar da ATA", "slelaboracao"); ?>
                                <?php if($statusAta == 1){ ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $ata->responsavel_elaboracao), 'maxlength="200" disabled class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                                <?php }else{ ?>
                                   <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $ata->responsavel_elaboracao), 'maxlength="200" class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                                      <?php } ?>
                            </div>
                        </div>
                              
                           
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Todas Assinaturas?", "assinaturas"); ?>
                                <?php $pst2[''] = '';
                                  $pst2['SIM'] = lang('SIM');
                                  $pst2['NÃO'] = lang('NÃO');
                                  ?>
                                  <?php if($statusAta == 1){ ?>
                                   <?php echo form_dropdown('assinaturas', $pst2, (isset($_POST['assinaturas']) ? $_POST['assinaturas'] : $ata->assinaturas), 'id="assinaturas" disabled  class="form-control " data-placeholder="' . lang("Selecione") . ' " required="required"   style="width:100%;" '); ?>
                                     <?php }else{ ?>
                                   <?php echo form_dropdown('assinaturas', $pst2, (isset($_POST['assinaturas']) ? $_POST['assinaturas'] : $ata->assinaturas), 'id="assinaturas"  class="form-control " data-placeholder="' . lang("Selecione") . ' " required="required"   style="width:100%;" '); ?>
                                      <?php } ?>      
                            </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                                <?= lang("Anexar Ata", "document") ?> 
                                    <?php if($ata->anexo){ ?>
                                <div class="btn-group">
                            <a href="<?= site_url('assets/uploads/atas/' . $ata->anexo_ata) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                            </a>
                                    <?php /* <input type="checkbox"><button type="button" class="btn btn-danger" id="reset"><?= lang('REMOVER') ?> */ ?>
                        </div>
                               
                                <?php } ?>
                               <?php if($statusAta != 1){ ?>
                               <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" value="<?php echo $projeto->anexo; ?>" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                                <?php } ?>  
                            </div>
                            
                        </div>
                    </div>
                        
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("Pauta : ", "slpauta"); ?>
                                         <?php if($statusAta == 1){ ?>
                                        <?php echo $ata->pauta; ?>
                                         <?php }else{ ?>
                                       <?php echo form_textarea('pauta', (isset($_POST['pauta']) ? $_POST['pauta'] : $ata->pauta), 'class="form-control" id="slpauta" equired="required"  style="margin-top: 10px; height: 150px;"'); ?>
                                       <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("Observação", "slnote"); ?>
                                        <?php if($statusAta == 1){ ?>
                                        <?php echo $ata->obs; ?>
                                        <?php }else{ ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $ata->obs), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>
                                        <?php } ?> 
                                    </div>
                                </div>
                            </div>
                    
                             <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("Participantes : ", "slparticipantes"); ?>
                                        
                                        <?php if($statusAta == 1){ ?>
                                        <?php echo $ata->participantes; ?>
                                         <?php }else{ ?>
                                        <a style="margin-left:10px;" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/lista_participante_plano_acao/'.$id) ?>"> 
                                            <i class="fa fa-users"></i>   <?= lang('Lista de Participantes') ?>
                                        </a>
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
                                            <?php
                                            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
                                                    foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
                                                        
                                                        if($participante_cadastrados){
                                                            $cadastro_usuario =  $this->site->getUser($participante_cadastrados->id_participante);
                                                          ?>
                                                           <div class="col-md-12">

                                                            <input type="checkbox" name="participantes[]" checked  value="<?php echo $cadastro_usuario->id; ?>"><?php echo ' '. $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name; ?>

                                                        </div>
                                        
                                                          <?php
                                                        }
                                                    }
                                                ?>
                                               
                                                 <?php } ?>
                                    </div>
                                </div>
                       <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("Vincular Usuários a ATA", "slAta_usuario"); ?>
                                            <a style="margin-left:10px;" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/lista_usuarios_vinculados_plano_acao/'.$id) ?>"> 
                                            <i class="fa fa-users"></i>   <?= lang('Lista de Usuários') ?>
                                        </a>
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
                                                <?php
                                                //$wu3[''] = '';
                                                foreach ($users as $user) {
                                                    $wu4[$user->id] = $user->first_name.' '.$user->last_name;
                                                }
                                                
                                                 foreach ($users_ata as $user_ata) {
                                                    
                                                    $wua[$user_ata->id_usuario] = $user_ata->id_usuario;
                                                   
                                                } 
                                                ?>
                                                <?php if($statusAta == 1){ ?>
                                                <?php echo form_dropdown('usuario_ata[]', $wu4, (isset($_POST['usuario_ata']) ? $_POST['usuario_ata'] : $wua), 'id="slAta_usuario" disabled multiple class="form-control selectpicker  select" data-placeholder="' . lang("Click e selecione os usuarios para vincular a ATA") . ' "  style="width:100%;" ');?>
                                                 <?php }else{ ?>
                                               
                                              <?php
                                          //  print_r($participantes_usuarios_ata);
                                            
                                            foreach ($participantes_usuarios_ata as $usu_vinculo) {
                                                
                                                   $usuarios_cadastrados_ata = $this->atas_model->getAtaUserByID_ATA($id, $usu_vinculo->id_user );
                                                    foreach ($usuarios_cadastrados_ata as $usuario_cadastrados) {
                                                        if($usuario_cadastrados){
                                                            
                                                             $cadastro_usuario_vinculo =  $this->site->getUser($usuario_cadastrados->id_usuario);
                                                             
                                                ?>
                                                <div class="col-md-12">
                                                    <input type="checkbox" name="usuario_ata[]" checked  value="<?php echo $cadastro_usuario_vinculo->id; ?>"><?php echo ' '. $cadastro_usuario_vinculo->first_name.' '.$cadastro_usuario_vinculo->last_name; ?>
                                                </div>
                                             
                                                <?php
                                                        }
                                                    }
                                               
                                            }
                                            ?>
                                                 <?php } ?>   
                                                
                                           
                                        </div>
                                    </div>


                            </div>
                                    <div class="col-lg-12">
                                    
                        
                        
                        
                        
                        <div class="clearfix"></div>
                    
                                <center>
                                        <div class="col-md-12">
                                             <?php if($statusAta == 1){ ?>
                                            <div
                                                class="fprom-group">

                                                 <a  class="btn btn-danger" href="<?= site_url('atas'); ?>"><?= lang('Sair') ?></a>
                                        </div>
                                            <?php }else{ ?>
                                                     <div
                                                class="fprom-group"><?php echo form_submit('add_projeto', lang("Salvar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                                 <a  class="btn btn-danger" href="<?= site_url('atas'); ?>"><?= lang('Sair') ?></a>
                                        </div>     <?php } ?> 
                                    </div>
                                    </center>
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
                     </div>   
                    <?php
                    $ataAtual = $this->atas_model->getAtaByID($id);
                    $statusAta = $ataAtual->status;
                    $avulsa = $ataAtual->avulsa;
                    
                    if($statusAta != 1){
                    ?>
                        
                    <div  class="col-lg-12">
                            <div class="col-md-12">
                            <div class="form-group">
                                <center>
                                <h2>  OPÇÕES  </h2>
                                </center>
                            </div>
                        </div>
                            
                   <?php
                  
                   
                   ?>
                <center>
                        <?php if($statusAta != 1){ ?>
                            
                    <div style="margin-top: 10px;" class="col-md-12">
                             <?php if($ata->tipo == 'TREINAMENTO'){ ?>
                                    <a style="color: #ffffff;" class="btn btn-success  "  href="<?= site_url('atas/add_treinamento/'.$id.'/'.$avulsa); ?>">Abrir Treinamento</a>
                              <?php }else{ ?>
                                <a style="color: #ffffff;" class="btn btn-success  "  href="<?= site_url('atas/adcionar_acao/'.$id.'/'.$avulsa); ?>">Adicionar Nova Ação</a>
                               <?php } ?>
                                <a  class="btn btn-primary" href="<?= site_url('Atas/edit_discussao/'.$id); ?>"><?= lang('Discussão da Ata') ?></a>
                                <a  class="btn btn-danger" href="<?= site_url('Atas'); ?>"><?= lang('Sair') ?></a>
                                <a  class=" btn btn-warning btn-theme " href="<?= site_url('Atas/finalizaAta/'.$id.'/'.$avulsa); ?>" onclick="javascript:document.getElementById('blanket').style.display = 'block';document.getElementById('aguarde').style.display = 'block';"><?= lang('Finalizar Ata') ?></a>
                         </div>
                        <?php } ?>
                                               
                </center>
                        <div style="height: 20px;" class="col-lg-12">
                        
                    </div>
                    </div>
                     <?php
                    }else if($statusAta == 1){
                     ?>
                    
                     <div  class="col-lg-12">
                         <br><br>
                     <center>
                            <div class="col-md-12">
                                 <a  class="btn btn-primary" href="<?= site_url('Atas/edit_discussao/'.$id); ?>"><?= lang('Discussão da Ata') ?></a>
                                 <a  class="btn btn-danger" href="<?= site_url('Atas'); ?>"><?= lang('Fechar') ?></a>
                              </div>
                         </center>
                        </div>
                            
                    <?php
                    }
                     ?>
                    <br>
                    <?php if($ata->tipo == 'REUNIÃO CONTÍNUA'){ ?>
                    <div  class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <?php
                                                $wu4[''] = '';
                                                $contContinuo2 = 0;
                                                foreach ($planosContinuo as $planoContinuo2) {
                                                       
                                                    if($planoContinuo2->idatas != $id){
                                                        $contContinuo2++;
                                                    }
                                                }
                                                ?>   
                                     <h3>AÇÕES PENDENTES DE ATAS PASSADAS : <?php echo $contContinuo2; ?></h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>DESCRIÇÃO</th>
                                                <th>RESPONSÁVEL</th>
                                                <th>DATA PRAZO</th>
                                                <th>ENTREGA DEMANDA</th>
                                                
                                               
                                                <th>STATUS</th>
                                          
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont_planoContinuo = 1;
                                                foreach ($planosContinuo as $planoContinuo) {
                                                       
                                                   if($planoContinuo->idatas != $id){
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td><?php echo $planoContinuo->idplanos; ?></td>
                                                <td><?php echo $planoContinuo->descricao; ?>
                                                <p><font  style="font-size: 10px; color: #0000BB"><?php echo $planoContinuo->observacao; ?></font></p>    
                                                <p><font  style="font-size: 10px;"><?php echo $planoContinuo->tipo; ?> <?php echo $planoContinuo->processo; ?> <?php echo $planoContinuo->item_roteiro; ?></font></p>
                                                </td>
                                                <td><?php echo $planoContinuo->first_name. ' '.$planoContinuo->last_name; ?>
                                                <td class="center">
                                                   <font  style="font-size: 12px;"> <?php if($planoContinuo->data_termino != '0000-00-00 00:00:00'){ echo $this->sma->hrld($planoContinuo->data_termino); }?></font>
                                                
                                                </td>
                                                <td class="center">
                                                 <font  style="font-size: 12px;">     <?php if($planoContinuo->data_entrega_demanda != '0000-00-00 00:00:00'){ echo $this->sma->hrld($planoContinuo->data_entrega_demanda); }?> 
                                                   </font>
                                                </td>
                                                </td>
                                              
                                                    <?php if($planoContinuo->status == 'CONCLUÍDO'){ ?>
                                               <td style="background-color: #00CC00" class="center"><?php echo $planoContinuo->status; ?></td>
                                                <?php } else if(($planoContinuo->status == 'PENDENTE')||$planoContinuo->status == 'AGUARDANDO VALIDAÇÃO' ){?>
                                               <td style="background-color: #CCA940" class="center"><?php echo $planoContinuo->status; ?></td>
                                                <?php } else if( $planoContinuo->status == 'ABERTO'){?>
                                               <td style="background-color: activecaption" class="center"><?php echo $planoContinuo->status; ?></td>
                                                <?php } ?> 
                                               
                                            </tr>
                                                <?php
                                                }
                                                }
                                                ?>
                                            
                                            
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <?php } ?>
                    <BR>
                    <div  class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <?php
                                                $wu4[''] = '';
                                                $cont2 = 0;
                                                foreach ($planos as $plano2) {
                                                       
                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                $cont2++;
                                                }
                                                ?>   
                                     <h3>AÇÕES DO PLANO : <?php echo $cont2; ?></h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>DESCRIÇÃO</th>
                                                <th>RESPONSÁVEL</th>
                                                <th>DATA PRAZO</th>
                                                <th>ENTREGA DEMANDA</th>
                                                
                                                <th>CONSULTORIA</th>
                                                <th>STATUS</th>
                                                <th>Duplicar</th>
                                                <th>Editar</th>
                                                <th>Excluir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($planos as $plano) {
                                                       
                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td><?php echo $plano->idplanos; ?></td>
                                                <td><?php echo $plano->descricao; ?>
                                                <p><font  style="font-size: 10px; color: #0000BB"><?php echo $plano->observacao; ?></font></p>    
                                                <p><font  style="font-size: 10px;"><?php echo $plano->tipo; ?> <?php echo $plano->processo; ?> <?php echo $plano->item_roteiro; ?></font></p>
                                                </td>
                                                <td><?php echo $plano->first_name. ' '.$plano->last_name; ?>
                                                <td class="center">
                                                   <font  style="font-size: 12px;"> <?php if($plano->data_termino != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_termino); }?></font>
                                                
                                                </td>
                                                <td class="center">
                                                 <font  style="font-size: 12px;">     <?php if($plano->data_entrega_demanda != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_entrega_demanda); }?> 
                                                   </font>
                                                </td>
                                                
                                                   
                                                </td>
                                                
                                                <td>
                                                    <?php echo $plano->consultoria ?> 
                                                     <p> <font  style="font-size: 12px;"><?php echo $plano->acaoconsultoria; ?></font></p>
                                                </td>
                                                
                                               
                                                
                                                    <?php if($plano->status == 'CONCLUÍDO'){ ?>
                                               <td style="background-color: #00CC00" class="center"><?php echo $plano->status; ?></td>
                                                <?php } else if(($plano->status == 'PENDENTE')||$plano->status == 'AGUARDANDO VALIDAÇÃO' ){?>
                                               <td style="background-color: #CCA940" class="center"><?php echo $plano->status; ?></td>
                                                <?php } else if( $plano->status == 'ABERTO'){?>
                                               <td style="background-color: activecaption" class="center"><?php echo $plano->status; ?></td>
                                                <?php } ?> 
                                               <td class="center">
                                                     <?php if($statusAta != 1){ ?>
                                                     <a style="color: orange;" class="btn fa fa-refresh" href="<?= site_url('atas/duplicarPlano/'.$plano->idplanos.'/'.$plano->idatas); ?>"></a>
                                                     <?php } ?>
                                               </td>
                                               <td class="center">
                                                    <?php if($statusAta != 1){ ?>
                                                     <a style="color: #128f76;" class="btn fa fa-edit" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/manutencao_acao_pendente/'.$plano->idplanos); ?>"></a>
                                                   <?php } ?>
                                                </td>
                                                <td class="center">
                                                    <?php if($statusAta != 1){ ?>
                                                    <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('atas/deletePlano/'.$plano->idplanos.'/'.$plano->idatas); ?>"></a>
                                                    <?php }else if($statusAta == 1){ ?>
                                                     <a style="color: #128f76;" class="btn fa fa-eye" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/manutencao_acao_av/'.$plano->idplanos); ?>"><?= lang('Ver') ?></a>
                                                  
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                                <?php
                                                }
                                                ?>
                                            
                                            
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col-lg-12 -->

              
                             
            </div>

        </div>
    </div>
</div>



