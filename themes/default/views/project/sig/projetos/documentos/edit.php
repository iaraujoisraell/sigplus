<link href='<?= $assets ?>fullcalendar/css/fullcalendar.min.css' rel='stylesheet' />
<link href='<?= $assets ?>fullcalendar/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href="<?= $assets ?>fullcalendar/css/bootstrap-colorpicker.min.css" rel="stylesheet" />

<style>
    .fc th {
        padding: 10px 0px;
        vertical-align: middle;
        background:#F2F2F2;
        width: 14.285%;
    }
    .fc-content {
        cursor: pointer;
    }
    .fc-day-grid-event>.fc-content {
        padding: 4px;
    }

    .fc .fc-center {
        margin-top: 5px;
    }
    .error {
        color: #ac2925;
        margin-bottom: 15px;
    }
    .event-tooltip {
        width:150px;
        background: rgba(0, 0, 0, 0.85);
        color:#FFF;
        padding:10px;
        position:absolute;
        z-index:10001;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
    }
</style>

           
                
           
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><p class="introtext">EDITAR DOCUMENTO DO PROJETO</p>
                             
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                               <div class="error"></div>
                               <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                 echo form_open_multipart("Projetos/edit_documento" , $attrib); 
                                 echo form_hidden('id', $id)
                                ?>
                                   
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <?= lang('Cód. Documento', 'codigo'); ?>
                                        <?php echo form_input('codigo', (isset($_POST['codigo']) ? $_POST['codigo'] : $documento->codigo_documento), 'maxlength="200" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                                    </div>
                                    </div>  
                                    <div class="col-lg-6">
                                     <div class="form-group">
                                        <?= lang('Tipo do documento', 'grupo'); ?>
                                        <?php echo form_input('grupo', (isset($_POST['grupo']) ? $_POST['grupo'] : $documento->grupo_documento), 'maxlength="200" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                                      
                                    </div>
                                        </div>
                               
                               
                               <div class="col-lg-12">
                                        <div class="form-group">
                                        <?= lang('Setor', 'setor'); ?>
                                           <?php
                                          //  $setor_doc['TODOS S/A'] = 'TODOS S/A';
                                          //  $setor_doc['TODOS OPERADORA'] = 'TODOS OPERADORA';
                                           
                                            foreach ($documento_setor as $doc_setor) {
                                                $setor_doc[$doc_setor->setor] = $doc_setor->setor;
                                               
                                            }
                                        
                                        
                                             $wu42['TODOS S/A'] = 'TODOS S/A';
                                            $wu42['TODOS OPERADORA'] = 'TODOS OPERADORA';
                                            foreach ($setores as $setor) {
                                                 $wu42[$setor->setor_id] = $setor->setor.' - '.$setor->superintendencia;
                                            }
                                          //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                            echo form_dropdown('setor[]', $wu42, (isset($_POST['setor']) ? $_POST['setor'] : $setor_doc), 'id="setor" required="required"  class="form-control  select" data-placeholder="' . lang("Setor") . ' "   style="width:100%;"  multiple ');

                                            ?>
                                            
                                          
                                        </div>
                                    </div>
                               
                               <div class="col-lg-12">
                                     <div class="form-group">
                                        <?= lang('Nome do documento', 'nome'); ?>
                                       <?php echo form_input('nome', (isset($_POST['nome']) ? $_POST['nome'] : $documento->nome_documento), 'maxlength="200" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                                    </div>
                                </div>   
                               <div class="col-lg-12">
                                    <div class="form-group">
                                        <?= lang("Documento", "document") ?> 
                                         <?php if($documento->anexo){ ?>
                                        <div class="btn-group">
                                            <a target="_blanck" href="<?= site_url('../assets/uploads/projetos/' . $tap->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                <i class="fa fa-chain"></i>
                                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                                            </a>
                                                    <?php /* <input type="checkbox"><button type="button" class="btn btn-danger" id="reset"><?= lang('REMOVER') ?> */ ?>
                                        </div>

                                        <?php } ?>
                                       <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>"  name="document" value="<?php echo $tap->anexo; ?>" data-show-upload="false"
                                               data-show-preview="false" class="form-control file">
                                    </div>
                                 </div>    
                                    
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                        <?= lang('Revisão', 'revisao'); ?>
                                       <?php echo form_input('revisao', (isset($_POST['revisao']) ? $_POST['revisao'] : $documento->revisao), 'maxlength="200" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                                    
                                    </div>
                                    </div>
                                        
                                        
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                        <?= lang('Data Revisão *', 'data_revisao'); ?>
                                        <input type="date" name='data_revisao' value="<?php echo $documento->data_revisao ?>" class="form-control tip" required="required">
                                    </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                        <?= lang('Data Validade *', 'data_validade'); ?>
                                        <input type="date" name='data_validade' value="<?php echo $documento->data_validade ?>" class="form-control tip" required="required">
                                    </div>
                                        </div>
                                   
                                
                                 <div class="col-lg-12">    
                                    
                                    <div class="form-group">
                                        <?= lang("Quem pode ver", "slResponsavel"); ?>
                                        
                                        <?php
                                        $us['0'] = 'PUBLICO';
                                        foreach ($usuarios_setor as $usuario_setor) {
                                            $us[$usuario_setor->usuario_setor] = $usuario_setor->usuario_setor;
                                        }
                                        
                                        $wu4['0'] = 'PUBLICO';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                        }
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $us), 'id="slResponsavel" required="required"  class="form-control  select" data-placeholder="' . lang("Selecione quem poderá acessar o documento") . ' "   style="width:100%;"  multiple ');
                              
                                        ?>
                                    </div>
                                </div>
                               <div class="col-lg-12">
                                    <div class="form-group">
                                        <?= lang('Status', 'title'); ?>
                                         <?php $pst[''] = '';
                                          $pst['OBSOLETO'] = lang('OBSOLETO');
                                          $pst['ATUALIZADO'] = lang('ATUALIZADO');
                                         
                                          
                                              echo form_dropdown('status', $pst, (isset($_POST['status']) ? $_POST['status'] : $documento->status), 'id="tipo"  class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Ata") . '" required="required"   style="width:100%;" ');
                                 
                                        ?>
                                        
                                    </div>
                                 </div>   
                                   <div class="fprom-group">
                                    <?php echo form_submit('add_projeto', lang("Editar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                                   <a  class="btn btn-danger" href="<?= site_url('projetos/gestao_documentos_index/'); ?>"><?= lang('Voltar') ?></a>
                            
                                </div>
                                    <?php echo form_close(); ?>
                            </div>
                           
                        </div>
                    </div>
<br><br>
           

<script type="text/javascript">
    var currentLangCode = '<?= $cal_lang; ?>', moment_df = '<?= strtoupper($dateFormats['js_sdate']); ?> HH:mm', cal_lang = {},
    tkname = "<?=$this->security->get_csrf_token_name()?>", tkvalue = "<?=$this->security->get_csrf_hash()?>";
    cal_lang['add_event'] = '<?= lang('add_event'); ?>';
    cal_lang['edit_event'] = '<?= lang('edit_event'); ?>';
    cal_lang['delete'] = '<?= lang('delete'); ?>';
    cal_lang['event_error'] = '<?= lang('event_error'); ?>';
</script>
<script src='<?= $assets ?>fullcalendar/js/moment.min.js'></script>
<script src="<?= $assets ?>fullcalendar/js/fullcalendar.min.js"></script>
<script src="<?= $assets ?>fullcalendar/js/lang-all.js"></script>
<script src='<?= $assets ?>fullcalendar/js/bootstrap-colorpicker.min.js'></script>
<script src='<?= $assets ?>fullcalendar/js/main.js'></script>
