<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
}
</script>
<script type="text/javascript">
/* Máscaras ER */
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mcep(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2")         //Esse é tão fácil que não merece explicações
    return v
}
function mdata(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2");       
    v=v.replace(/(\d{2})(\d)/,"$1/$2");       
                                             
    v=v.replace(/(\d{2})(\d{2})$/,"$1$2");
    return v;
}
function mrg(v){
	v=v.replace(/\D/g,'');
	v=v.replace(/^(\d{2})(\d)/g,"$1.$2");
	v=v.replace(/(\d{3})(\d)/g,"$1.$2");
	v=v.replace(/(\d{3})(\d)/g,"$1-$2");
	return v;
}
function mvalor(v){
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
    v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões
    v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares
        
    v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}
function next( el, next )
{
	if( el.value.length >= el.maxLength ) 
		id( next ).focus(); 
}
</script>
<?php
$ataAtual = $this->atas_model->getAtaByID($id);
                                                 $statusAta = $ataAtual->status;
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i>
            <?php if($statusAta == 1){ ?>
                <?= lang('Esta ATA não pode ser Editada, pois se encontra FINALIZADA.'); ?>
            <?php }else{ ?>
             <?= lang('Editar ATA'); ?>
            <?php } ?>
        </h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("atas/edit", $attrib);
                 echo form_hidden('id', $id);
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
                                     <?php  echo form_dropdown('tipo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $ata->tipo), 'id="tipo" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Ata") . '" required="required"   style="width:100%;" '); ?>
                                  
                                    <?php } ?>
                            </div>
                        </div>
                    </div>    
                         
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
                                      <?php echo form_textarea('participantes', (isset($_POST['participantes']) ? $_POST['participantes'] :$ata->participantes), 'class="form-control" id="slparticipantes" equired="required"  style="margin-top: 10px; height: 150px;"'); ?>
                                         <?php } ?>
                                    </div>
                                </div>
                       <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("Vincular Usuários a ATA", "slAta_usuario"); ?>
                                            <div class="input-group">
                                             
                                                <?php
                                                //$wu3[''] = '';
                                                foreach ($users as $user) {
                                                    $wu4[$user->id] = $user->username;
                                                }
                                                
                                                 foreach ($users_ata as $user_ata) {
                                                    
                                                    $wua[$user_ata->id_usuario] = $user_ata->id_usuario;
                                                   
                                                } 
                                                ?>
                                                <?php if($statusAta == 1){ ?>
                                                <?php echo form_dropdown('usuario_ata[]', $wu4, (isset($_POST['usuario_ata']) ? $_POST['usuario_ata'] : $wua), 'id="slAta_usuario" disabled multiple class="form-control selectpicker  select" data-placeholder="' . lang("Click e selecione os usuarios para vincular a ATA") . ' "  style="width:100%;" ');?>
                                                 <?php }else{ ?>
                                                <?php echo form_dropdown('usuario_ata[]', $wu4, (isset($_POST['usuario_ata']) ? $_POST['usuario_ata'] : $wua), 'id="slAta_usuario"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Click e selecione os usuarios para vincular a ATA") . ' "  style="width:100%;" ');?>
                                                <?php } ?>   
                                                 <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                            </div>
                                    <div class="col-lg-12">
                                    
                        
                        
                        
                        
                        <div class="clearfix"></div>
                    
                        
                        <div class="col-md-12">
                             <?php if($statusAta == 1){ ?>
                            <div
                                class="fprom-group">
                                    
                                 <a  class="btn btn-danger" href="<?= site_url('atas'); ?>"><?= lang('Fechar') ?></a>
                        </div>
                            <?php }else{ ?>
                                     <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                 <a  class="btn btn-danger" href="<?= site_url('atas'); ?>"><?= lang('Cancelar') ?></a>
                        </div>     <?php } ?> 
                    </div>
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>



