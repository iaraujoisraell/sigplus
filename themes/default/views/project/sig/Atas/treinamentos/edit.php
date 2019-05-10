<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
}

    if (!localStorage.getItem('dateAta')) {
            $("#dateAta").datetimepicker({
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
                <?= lang('Este Treinamento não pode ser Editado, pois se encontra FINALIZADO.'); ?>
            <?php }else{ ?>
             <?= lang('Dados do Treinamento'); ?>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Projeto", "slProjeto"); ?>


                                <?php
                                $usuario = $this->session->userdata('user_id');
                                $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                                $projetos_usuario->projeto_atual;


                                $ata = $this->atas_model->getAtaByID($id);
                                $wu3[''] = '';
                                foreach ($projetos as $projeto) {
                                    $wu3[$projeto->id] = $projeto->projeto;
                                }
                                echo form_dropdown('projeto', $wu3, (isset($_POST['projeto']) ? $_POST['projeto'] : $ata->projetos), 'id="slProjeto" required="required" class="form-control  select" disabled data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                ?>


                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Ata", "ata"); ?>
                                <?php
                                $usuario = $this->session->userdata('user_id');
                                $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                                $projetos_usuario->projeto_atual;


                                $ata = $this->atas_model->getAtaByID($id);
                                $wu3[''] = '';
                               
                                echo form_dropdown('projeto', $ata->id, (isset($_POST['projeto']) ? $_POST['projeto'] : $ata->id), 'id="ata" required="required" class="form-control  select" disabled data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                ?>


                            </div>
                        </div>
                     </div>    
                    
                    <div class="col-md-12">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang("Data/Hora Início", "data_inicio"); ?>
                                    <?php if($statusAta == 1){ ?>
                                    <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($ata->inicio)), 'class="form-control input-tip datetime"  id="data_inicio" disabled required=$projeto"required"'  ); ?>
                                    <?php }else{ ?>
                                     <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($ata->inicio)), 'class="form-control input-tip datetime"  id="data_inicio" required=$projeto"required"'  ); ?>
                                  
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= lang("Data/hora Término", "data_termino"); ?>
                                    <?php if($statusAta == 1){ ?>
                                    <?php echo form_input('data_termino', (isset($_POST['data_termino']) ? $_POST['data_termino'] : $this->sma->hrld($ata->data_ata)), 'class="form-control input-tip datetime"  id="data_termino" disabled required=$projeto"required"'  ); ?>
                                    <?php }else{ ?>
                                    <?php echo form_input('data_termino', (isset($_POST['data_termino']) ? $_POST['data_termino'] : $this->sma->hrld($ata->data_ata)), 'class="form-control input-tip datetime"  id="data_termino" required=$projeto"required"'  ); ?>
                                    <?php } ?>
                                </div>
                            </div>
                    </div>
                         
                    <div class="col-md-12">
                      <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Facilitador", "facilitador"); ?>
                                                <?php
                                                $wu5[''] = '';
                                                foreach ($users as $user) {
                                                    $wu5[$user->id] = $user->username;
                                                }
                                                
                                                ?>
                                                <?php echo form_dropdown('usuario_ata', $wu5, (isset($_POST['usuario_ata']) ? $_POST['usuario_ata'] : $wua), 'id="facilitador"   class="form-control selectpicker  select" data-placeholder="' . lang("Selecione o Facilitador do Treinamento") . ' "  style="width:100%;" ');?>
                                          </div>
                                    </div>
                               
                         
                           
                        
                    </div>
                        
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("Conteúdo do Treinamento : ", "slpauta"); ?>
                                         <?php if($statusAta == 1){ ?>
                                        <?php echo $ata->pauta; ?>
                                         <?php }else{ ?>
                                       <?php echo form_textarea('pauta', (isset($_POST['pauta']) ? $_POST['pauta'] : $ata->pauta), 'class="form-control" id="slpauta" equired="required"  style="margin-top: 10px; height: 150px;"'); ?>
                                       <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("Observações do Facilitador", "slnote"); ?>
                                        <?php if($statusAta == 1){ ?>
                                        <?php echo $ata->obs; ?>
                                        <?php }else{ ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $ata->obs), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>
                                        <?php } ?> 
                                    </div>
                                </div>
                            </div>
                    
                             <div class="col-md-12">
                                <div class="col-md-12">
                                        <div class="form-group">
                                            <?= lang("Participantes", "slAta_usuario"); ?>
                                             <a style="margin-left:10px;" data-toggle="modal" data-target="#myModal" href="<?= site_url('atas/lista_participante') ?>"> 
                                            <i class="fa fa-users"></i>   <?= lang('Lista de Participantes') ?>
                                        </a>
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
                                                
                                        </div>
                                    </div>
                            </div>
                    
                    <br><br><br>
                    <center>
                        <div class="col-lg-12">
                            <div class="clearfix"></div>


                            <div class="col-md-12">
                                <?php if ($statusAta == 1) { ?>
                                    <div
                                        class="fprom-group">

                                        <a  class="btn btn-danger" href="<?= site_url('atas'); ?>"><?= lang('Fechar') ?></a>
                                    </div>
                                <?php } else { ?>
                                    <div
                                        class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                        <a  class="btn btn-danger" href="<?= site_url('atas'); ?>"><?= lang('Cancelar') ?></a>
                                    </div>     <?php } ?> 
                            </div>
                        </div>
                    </center>
                    
                    <center>
                        <div class="col-lg-12">
                          <a style="color: #ffffff;" class="btn btn-success  "  href="<?= site_url('atas/adcionar_acao/'.$id.'/'.$avulsa); ?>">Adicionar Item de Treinamento</a>
                            <a style="color: #ffffff;" class="btn btn-success  "  href="<?= site_url('atas/adcionar_acao/'.$id.'/'.$avulsa); ?>">Adicionar Nova Ação</a>
                         </div>  
                        </center>
                      <?php echo form_close(); ?>
                    <br><br>
                     <div class="col-lg-12">
                      <h3>TREINAMENTOS REALIZADOS </h3>
                      
                       <br><br>
                     <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>DATA</th>
                                                <th>INÍCIO</th>
                                                <th>TÉRMINO</th>
                                                <th>TOTAL</th>
                                                <th>DESCRICAO</th>
                                          
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont_planoContinuo = 1;
                                                foreach ($planosContinuo as $planoContinuo) {
                                                       
                                                   if($planoContinuo->idatas != $id){
                                                       
                                                   }
                                                }
                                                ?>   
                                                <tr  class="odd gradeX">
                                                <td  class="center"><?php echo '1';// $planoContinuo->idplanos; ?></td>
                                                <td  class="center"><?php echo '15/01/218';//$planoContinuo->descricao; ?> </td>
                                                <td  class="center"><?php echo '09:00';//$planoContinuo->first_name. ' '.$planoContinuo->last_name; ?></td>
                                                <td  class="center"><?php echo '13:00';//$planoContinuo->first_name. ' '.$planoContinuo->last_name; ?></td>
                                                <td  class="center"><?php echo '04:00';//$planoContinuo->first_name. ' '.$planoContinuo->last_name; ?></td>
                                              
                                                <td  class="center"><?php echo 'treinamento do móduto financeiro'; //$planoContinuo->status; ?></td>
                                               
                                               
                                            </tr>
                                                <?php
                                               // }
                                               // }
                                                ?>
                                            
                                            
                                           
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                     </div>  
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
                                     <h3>AÇÕES GERADAS : <?php echo $cont2; ?></h3>
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
                    
                    
                

              

            </div>

        </div>
    </div>
</div>



