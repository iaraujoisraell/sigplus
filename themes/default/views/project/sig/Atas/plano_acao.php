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
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('PLANO DE AÇÃO DA ATA '); ?><?php echo ' '.$id; ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

               
               
                <div class="row">
                    <div id="blanket"></div>
                    <div id="aguarde">Aguarde...</div>
                    <center><h2>DADOS DA ATA</h2></center>
                    <div class="col-md-3">
                                        <div class="form-group">
                                            <?= lang("Projeto : ", "slProjeto"); ?>
                                           
                                             
                                                <?php
                                                 $usuario = $this->session->userdata('user_id');
                                                 $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                                                 $projetos_usuario->projeto_atual;
                                                 
                                                 echo $projetos_usuario->projeto;
                                                 
                                                   ?>
                                                
                                                
                                        </div>
                                    </div>
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
                                    <?= lang("Data Início", "sldate"); ?>
                                    <?php if($statusAta == 1){ ?>
                                    <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($ata->data_ata)), 'class="form-control input-tip datetime"  id="sldate" disabled required=$projeto"required"'  ); ?>
                                    <?php }else{ ?>
                                     <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($ata->data_ata)), 'class="form-control input-tip datetime"  id="sldate" required=$projeto"required"'  ); ?>
                                  
                                    <?php } ?>
                                </div>
                            </div>
                          <div class="col-md-3">
                            <div class="form-group">
                                    <?= lang("Data Término ", "data_termino"); ?>
                                
                                    <?php if($statusAta == 1){ ?>
                                    <?php echo form_input('dateTermino', (isset($_POST['dateTermino']) ? $_POST['dateTermino'] : $this->sma->hrld($ata->data_termino)), 'class="form-control input-tip datetime"  id="data_termino" disabled required=$projeto"required"'  ); ?>
                                    <?php }else{ ?>
                                     <?php echo form_input('dateTermino', (isset($_POST['dateTermino']) ? $_POST['dateTermino'] : $this->sma->hrld($ata->data_termino)), 'class="form-control input-tip datetime"  id="data_termino" required=$projeto"required"'  ); ?>
                                  
                                    <?php } ?>
                                   
                                </div>
                            
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Local ", "sllocal"); ?>
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
                    
                    <div id="hiddenDivTreinamento" style="display:<?php if($ata->tipo == 'TREINAMENTO'){ ?> block <?php }else{ ?>  none <?php } ?>;" class="col-md-12">    
                      
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Avaliação de Reação", "reacao"); ?>
                                <div class="well well-sm well_1">
                                    <?php
                                    $wu5['0'] = 'N/A';
                                    foreach ($avaliacoes as $avaliacao) {
                                        $wu5[$avaliacao->id] = $avaliacao->titulo . ' - ' . $avaliacao->tipo;
                                    }
                                     if($statusAta == 1){
                                        echo form_dropdown('reacao', $wu5, (isset($_POST['reacao']) ? $_POST['reacao'] : $ata->avaliacao_reacao), 'id="reacao"   class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" disabled ');
                                     }else{
                                        echo form_dropdown('reacao', $wu5, (isset($_POST['reacao']) ? $_POST['reacao'] : $ata->avaliacao_reacao), 'id="reacao"   class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" ');
                                     }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Avaliação de Aprendizagem", "slFacilitador"); ?>

                                <div class="well well-sm well_1"> 
                                   <select name="aprendizagem" id="aprendizagem" class="form-control">
                                       <?php if($statusAta != 1){ ?>
                                       <?php if($ata->avaliacao_aprendizagem == 0){
                                           $a_aprendizagem = 'N/A';
                                           $a_valor = 0;
                                       }else{
                                           $a_aprendizagem = 'SIM';
                                           $a_valor = 1;
                                       } ?>
                                    <option value="<?php echo $ata->avaliacao_aprendizage ?>"><?php echo $a_aprendizagem; ?></option>   
                                    <option value="0">N/A</option>
                                    <option value="1"> SIM</option>
                                       <?php }else{ ?>
                                    <option value="0"><?php echo $ata->avaliacao_aprendizagem; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Avaliação de Desempenho", "desempenho"); ?>

                                <div class="well well-sm well_1"> 
                                    <?php
                                    $wu6['0'] = 'N/A';
                                     foreach ($avaliacoes as $avaliacao) {
                                        $wu6[$avaliacao->id] = $avaliacao->titulo . ' - ' . $avaliacao->tipo;
                                    }
                                 if($statusAta == 1){  
                                    echo form_dropdown('desempenho', $wu6, (isset($_POST['desempenho']) ? $_POST['desempenho'] : $ata->avaliacao_desempenho), 'id="desempenho"   class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" ');
                                }else{ 
                                    echo form_dropdown('desempenho', $wu6, (isset($_POST['desempenho']) ? $_POST['desempenho'] : $ata->avaliacao_desempenho), 'id="desempenho"   class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" ');
                                } 
                                ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                        <div class="form-group">
                                <?= lang("Facilitador(es) do Treinamento", "slFacilitador"); ?>
                                <div class="well well-sm well_1">
                                    <?php
                                    //$wu4[''] = '';
                                    $facilitadores_cadastrados_ata = $this->atas_model->getAtaFacilitadores_ByID_ATA($id);
                                    foreach ($facilitadores_cadastrados_ata as $facilitador) {
                                        $w_facilitador[$facilitador->usuario] = $facilitador->usuario;
                                    }
                                    
                                    foreach ($users as $user) {
                                        $wu4[$user->id] = $user->first_name . ' ' . $user->last_name;
                                    }
                                    if($statusAta == 1){
                                        echo form_dropdown('facilitador[]', $wu4, (isset($_POST['facilitador']) ? $_POST['facilitador'] : $w_facilitador), 'id="slFacilitador" multiple  class="form-control selectpicker  select" data-placeholder="' . lang("Facilitador do Treinamento") . ' "  style="width:100%;" ');
                                     }else{
                                        echo form_dropdown('facilitador[]', $wu4, (isset($_POST['facilitador']) ? $_POST['facilitador'] : $w_facilitador), 'id="slFacilitador" multiple  class="form-control selectpicker  select" data-placeholder="' . lang("Facilitador do Treinamento") . ' "  style="width:100%;" ');
                                    }
                                    ?>
                                </div>
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
                     <?php if($ata->tipo == 'REUNIÃO CONTÍNUA'){ ?>
                            <div class="col-md-12">
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Item do Evento", "slEvento"); ?>
                                        <?php
                                        $wue[''] = '';
                                      foreach ($eventos as $evento) {
                                                 $wue[$evento->id_item] = $evento->id_item.' - '. $evento->evento.' - '. resume($evento->descricao, 100);
                                           
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
                                                      $wue[$evento->id_item] = $evento->id_item.' - '. $evento->evento.' - '. resume($evento->descricao, 100).'( de: '.$evento->inicio .' até : '.$evento->fim .' )';
                                     }
                                        echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : ""), 'id="slEvento"  class="form-control  select" data-placeholder="' . lang("Selecione o Evento") . ' "  style="width:100%;"  ');
                                        ?>
                                    </div>
                                </div>
                                </div>
                    <?php } ?>
                         
                    <div class="col-md-12">
                      <div class="col-md-3">
                            <div class="form-group">
                                <?= lang("Responsável pela ATA", "slelaboracao"); ?>
                                <?php if($statusAta == 1){ ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $ata->responsavel_elaboracao), 'maxlength="200" disabled class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                                <?php }else{ ?>
                                   <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $ata->responsavel_elaboracao), 'maxlength="200" class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                                      <?php } ?>
                            </div>
                        </div>
                              
                           
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Todas Assinaturas?", "assinaturas"); ?>
                                <?php 
                                //$pst2[''] = '';
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
                        
                        <?php if($statusAta != 1){ ?>
                        
                        
                             <?php if($ata->convocacao != "SIM") { ?>
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

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <?= lang("Gerar Convocação ?", "convocacao"); ?>
                                    <a style="margin-left:10px;" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/lista_convocados/'.$id) ?>"> 
                                      <?= lang('Histórico') ?>
                                </a> <br>
                                   <select name="convocacao" id="convocacao" class="form-control" onchange="optionConvocacao()">
                                        <option value="NÃO"> NÃO</option>
                                        <option value="SIM"> SIM</option>
                                    </select>
                                </div>
                            </div>
                             <?php }else if($ata->convocacao == "SIM") { ?>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <?= lang("Gerou Convocação ?", "convocacao"); ?>
                                    <select name="convocacao" id="convocacao" class="form-control" >
                                            <option value="SIM">SIM</option>
                                    </select>
                                </div>    
                            </div>    
                            <?php } ?>
                        
                        
                         <?php }else{ ?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Gerou Convocação ?", "convocacao"); ?>
                                <select  id="convocacao" class="form-control" disabled="true">
                                        <option value="<?php echo $ata->convocacao ?>"> <?php echo $ata->convocacao ?></option>
                                </select>
                            </div>    
                        </div>   
                         <?php } ?>
                        
                        <div class="col-md-3">
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
                        
                    <?php if($ata->convocacao == "SIM") { ?>
                    
                        
                        <script>
                         function optioncancelamento_convocacao(){
                                var option = document.getElementById("cancelamento_convocacao").value;
                                if(option == "SIM"){
                                 //   document.getElementById("hiddenDiv").style.visibility ="visible";
                                    document.getElementById("hiddenDivCancelamentoConcovacao").style.display = "block";
                                }else{
                                    document.getElementById("hiddenDivCancelamentoConcovacao").style.display = "none";
                                }
                              
                            }
                        </script>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <?= lang("Foi gerado uma convodação para esta ata! ", "slpauta"); ?>
                            <a style="margin-left:10px;" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/lista_convocados/'.$id) ?>"> 
                                  <?= lang('Ver detalhes e Lista de Convocados') ?>
                            </a> <br>
                        
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= lang("Cancelar Convocação ?", "cancelamento_convocacao"); ?>
                             
                                <select name="cancelamento_convocacao" id="cancelamento_convocacao" class="form-control" onchange="optioncancelamento_convocacao()">
                                    <option value="NÃO"> NÃO</option>
                                    <option value="SIM"> SIM</option>
                                </select>
                                  
                            </div>
                        </div>
                    </div>
                         <?php } ?>
                    <div class="col-md-12">
                        <div id="hiddenDivCancelamentoConcovacao" style="display: none;" class="col-md-12">
                            <div class="form-group">
                                <?= lang("Texto do Cancelamento da Convocação (será enviado para os participantes) : ", "texto_cancelamento"); ?>
                                <?php echo form_textarea('texto_cancelamento', (isset($_POST['texto_convocacao']) ? $_POST['texto_convocacao'] : ""), 'class="form-control" id="texto_cancelamento"   style="margin-top: 10px; height: 150px;"'); ?>
                            </div>
                        </div>
                    <div id="hiddenDivConcovacao" style="display: none;" class="col-md-12">
                            <div class="form-group">
                                <?= lang("Texto da Convocação (será enviado para os participantes) : ", "sltextoconvocacao"); ?>
                                <?php echo form_textarea('texto_convocacao', (isset($_POST['texto_convocacao']) ? $_POST['texto_convocacao'] : ""), 'class="form-control" id="sltextoconvocacao"   style="margin-top: 10px; height: 150px;"'); ?>
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
                                        <table   class="table">
                            <tbody style="width: 100%;">
                                 <?php
                                    $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
                                            foreach ($participantes_cadastrados_ata as $participante_cadastrados) {

                                                if($participante_cadastrados){
                                                    $cadastro_usuario =  $this->site->getUser($participante_cadastrados->id_participante);
                                                    $historico_convocado =  $this->atas_model->listaConvocadosByUsuarioAta($cadastro_usuario->id, $id);


                                                    ?>
                                                    <tr>
                                                        <td style="width: 40%;">
                                                            <?php echo  $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name; ?> 
                                                        </td>

                                                    </tr>

                                                  <?php
                                                }
                                            }
                                        ?>

                            </tbody>
                </table>    
                                        
                                        
                                        
                                        
                                       
                                        
                                         <?php }else{ 
                                             /*
                                              * <a style="margin-left:10px;" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/lista_participante_plano_acao/'.$id) ?>"> 
                                            <i class="fa fa-users"></i>   <?= lang('Lista de Participantes') ?>
                                        </a>
                                              */
                                             ?>
                                        
                                          <div class="well well-sm well_1">
                                            <?php
                                        //$wu3[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->first_name.' '.$user->last_name;
                                            //$wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                        }
                                        echo form_dropdown('participantes[]', $wu4, (isset($_POST['participantes']) ? $_POST['participantes'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Outros Participantes") . ' "  style="width:100%;" ');
                                        ?>
                                        </div>
                                        
                                        <br><br>
                                        <?php if($ata->convocacao == "SIM") { ?>
                                        <div class="col-md-9">
                                            <font>Nome</font>
                                        </div>
                                        <div class="col-md-3">
                                            <font>Presença Confirmada ?</font>
                                        </div>
                                        <?php } ?>
                                            <?php
                                                    $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id);
                                                    foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
                                                        
                                                        if($participante_cadastrados){
                                                            $cadastro_usuario =  $this->site->getUser($participante_cadastrados->id_participante);
                                                            $historico_convocado =  $this->atas_model->listaConvocadosByUsuarioAta($cadastro_usuario->id, $id);
                                                             $status = $historico_convocado->status;
                                                                if($status == 0){
                                                                    $novo_status = "Sem Retorno";
                                                                }else if($status == 1){
                                                                    $novo_status = "SIM";
                                                                }else if($status == 2){
                                                                    $novo_status = "NÃO";
                                                                }
                                                            ?>
                                                           
                                                    <?php if($ata->convocacao == "SIM") { ?>
                                                            <div class="col-md-8">
                                                                <input type="checkbox" name="participantes_ch[]" checked  value="<?php echo $cadastro_usuario->id; ?>"><?php echo ' '. $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name; ?>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <?php echo $novo_status; ?>
                                                            </div>
                                                            
                                                    <?php }else{ ?>
                                                        <div class="col-md-12">
                                                                <input type="checkbox" name="participantes_ch[]" checked  value="<?php echo $cadastro_usuario->id; ?>"><?php echo ' '. $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name; ?>
                                                                <?php if(($ata->tipo == 'TREINAMENTO')&&($participante_cadastrados->avaliacao != 1)){ ?>
                                                                <a target="_blank" class="btn btn-danger" href="<?= site_url('welcome/pesquisa_reacao/'.$participante_cadastrados->id); ?>"><?= lang('Avaliação') ?></a>
                                                               <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                          <?php
                                                        }
                                                    }
                                                ?>
                                               
                                                 <?php } ?>
                                    </div>
                                </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("Usuários Vinculados", "slAta_usuario"); 
                                                 
                                                 ?>
                                              
                                             <div class="well well-sm well_1">
                                                 <?php if($statusAta != 1){ ?>
                                             <?php
                                                //$wu3[''] = '';
                                                foreach ($users as $user) {
                                                    $wu4[$user->id] = $user->first_name.' '.$user->last_name;
                                                }
                                                echo form_dropdown('usuario_ata[]', $wu4, (isset($_POST['usuario_ata']) ? $_POST['usuario_ata'] : ""), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Outros usuarios para vincular a ATA") . ' "  style="width:100%;" ');
                                                ?>
                                              </div>
                                            <br><br>
                                             <?php } ?>
                                            
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
                                                 
                                                   $usuarios_cadastrados_ata = $this->atas_model->getAtaUserByID_ATA($id );
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
                                               
                                           // }
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
                                            
                                            <?php }else{ ?>
                                                     <div
                                                class="fprom-group"><?php echo form_submit('add_projeto', lang("Salvar"), 'id="add_projeto" class="btn btn-primary" onclick="alertas();" style="padding: 6px 15px; margin:15px 0;"'); ?>
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
                        <div style="margin-top: 10px;" class="col-md-3">
                            <a style="color: #ffffff;" class="btn btn-success  "  href="<?= site_url('atas/adcionar_acao/'.$id.'/'.$avulsa); ?>">Adicionar Nova Ação</a>
                        </div>
                        <div style="margin-top: 10px;" class="col-md-3">
                                <a  class="btn btn-primary" href="<?= site_url('Atas/edit_discussao/'.$id); ?>"><?= lang('Discussão da Ata') ?></a>
                        </div>
                       
                        <div style="margin-top: 10px;" class="col-md-3">
                                <a  class=" btn btn-warning btn-theme " href="<?= site_url('Atas/finalizaAta/'.$id.'/'.$avulsa); ?>" onclick="alertas();"><?= lang('Finalizar Ata') ?></a>
                         </div>
                         <div style="margin-top: 10px;" class="col-md-3">
                                <a  class="btn btn-danger" href="<?= site_url('Atas'); ?>"><?= lang('Sair') ?></a>
                        </div>
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
                                <?php if($statusAta != 1){ ?>
                                 <a  class="btn btn-primary" href="<?= site_url('Atas/edit_discussao/'.$id); ?>"><?= lang('Discussão da Ata') ?></a>
                                <?php } ?>
                                 <a  class="btn btn-danger" href="<?= site_url('Atas'); ?>"><?= lang('Fechar') ?></a>
                              </div>
                         </center>
                        </div>
                            
                    <?php
                    }
                     ?>
                    <br>
                    
                    <?php
                    $ataAtual = $this->atas_model->getAtaByID($id);
                    $discussao = $ataAtual->discussao;
                    $statusAta = $ataAtual->status;
                    if($statusAta == 1){
                        if($discussao){
                    ?>
                    
                    <div  class="col-lg-12">
                        <div  class="col-lg-12">
                            <p>DISCUSSAO DA ATA :</p>
                            <BR>
                            <p><?php echo $discussao; ?></p>
                        </div>
                    </div>
                    <br>
                    
                    
                    <?php 
                        
                        }
                    }
                    ?>
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
                                                <th>EVENTO</th>
                                                <th>DESCRIÇÃO</th>
                                                <th>RESPONSÁVEL</th>
                                                <th>SETOR</th>
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
                                                       $evento =  $this->atas_model->getAllitemEventoByID($planoContinuo->eventos);   
                                                   if($planoContinuo->idatas != $id){
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td><?php echo $planoContinuo->idplanos; ?></td>
                                                <td><?php echo $evento->evento.'/'.$evento->item; ?></td>
                                                <td><?php echo $planoContinuo->descricao; ?></td>
                                                <p><font  style="font-size: 10px; color: #0000BB"><?php echo $planoContinuo->observacao; ?></font></p>    
                                                <p><font  style="font-size: 10px;"><?php echo $planoContinuo->tipo; ?> <?php echo $planoContinuo->processo; ?> <?php echo $planoContinuo->item_roteiro; ?></font></p>
                                                </td>
                                                <td><?php echo $planoContinuo->first_name. ' '.$planoContinuo->last_name; ?>
                                                    <td><?php echo $planoContinuo->nome; ?>
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
                                                <?php } else if( $planoContinuo->status == 'CANCELADO'){?>
                                               <td style="background-color: black; color: #ffffff;" class="center"><?php echo $planoContinuo->status; ?></td>
                                                <?php } ?>  ?> 
                                               
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
                                                <th>EVENTO</th>
                                                <th>DESCRIÇÃO</th>
                                                <th>RESPONSÁVEL</th>
                                                <th>SETOR</th>
                                                <th>DATA PRAZO</th>
                                                <th>ENTREGA DEMANDA</th>
                                                <th><i class="fa fa-paperclip"> </i> ANEXO</th>
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
                                                    
                                                    $evento =  $this->atas_model->getAllitemEventoByID($plano->eventos);   
                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td><?php echo $plano->idplanos; ?></td>
                                                <td><?php echo $evento->evento.'/'.$evento->item; ?>
                                                <td><?php echo $plano->descricao; ?>
                                                <p><font  style="font-size: 10px; color: #0000BB"><?php echo $plano->observacao; ?></font></p>    
                                                <p><font  style="font-size: 10px;"><?php echo $plano->tipo; ?> <?php echo $plano->processo; ?> <?php echo $plano->item_roteiro; ?></font></p>
                                                </td>
                                                <td><?php echo $plano->first_name. ' '.$plano->last_name; ?></td>
                                                <td><?php echo $plano->nome; ?>
                                                    
                                                <td class="center">
                                                   <font  style="font-size: 12px;"> <?php if($plano->data_termino != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_termino); }?></font>
                                                
                                                </td>
                                                <td class="center">
                                                 <font  style="font-size: 12px;">     <?php if($plano->data_entrega_demanda != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_entrega_demanda); }?> 
                                                   </font>
                                                </td>
                                                
                                                   
                                                </td>
                                                
                                               
                                                <td>
                                                        <?php if ($plano->anexo) { ?>

                                                            <a target="_blank" href="<?= site_url('../assets/uploads/atas/' . $plano->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                                Ver Anexo
                                                               

                                                            </a>
                                                        <?php } ?>
                                                </td>
                                               
                                                
                                                    <?php if ($plano->status == 'CONCLUÍDO') { ?>
                                                        <td style="background-color: #00CC00" class="center"><?php echo $plano->status; ?></td>
                                                    <?php } else if (($plano->status == 'PENDENTE') || $plano->status == 'AGUARDANDO VALIDAÇÃO') { ?>
                                                        <td style="background-color: #CCA940" class="center"><?php echo $plano->status; ?></td>
                                                    <?php } else if ($plano->status == 'ABERTO') { ?>
                                                        <td style="background-color: activecaption" class="center"><?php echo $plano->status; ?></td>
                                                    <?php } else if ($plano->status == 'CANCELADO') { ?>
                                                        <td style="background-color: #000000; color: #ffffff;" class="center"><?php echo $plano->status; ?></td>
                                                    <?php } ?>  
                                               
                                               
                                               <td class="center">
                                                     <?php if($statusAta != 1){ ?>
                                                     <a style="color: orange;" class="btn fa fa-refresh" href="<?= site_url('atas/duplicarPlano/'.$plano->idplanos.'/'.$plano->idatas); ?>"></a>
                                                     <?php } ?>
                                               </td>
                                               <td class="center">
                                                    <?php //if($statusAta != 1){ ?>
                                                     <a style="color: #128f76;" class="btn fa fa-edit"  href="<?= site_url('atas/manutencao_acao_pendente/'.$plano->idplanos); ?>"></a>
                                                   <?php //} ?>
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


    <script>
     function alertas(){
         
    
    document.getElementById('blanket').style.display = 'block';
    document.getElementById('aguarde').style.display = 'block';
   
    
    }
    </script>
