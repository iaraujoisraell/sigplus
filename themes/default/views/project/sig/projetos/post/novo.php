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

           
                <?php $evento_selecionado = $this->projetos_model->getEventoByID($evento); ?> 
           
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><p class="introtext">Nova Postagem   </p>
                             
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                               <div class="error"></div>
                               <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                 echo form_open_multipart("Projetos/nova_postagem" , $attrib); 
                                 echo form_hidden('evento', $evento)
                                ?>
                                     <div class="form-group">
                                <?= lang("Título", "titulo"); ?>
                                <?php echo form_input('titulo', (isset($_POST['titulo']) ? $_POST['titulo'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required"  id="titulo"'); ?>
                            </div>
                               <div class="form-group">
                                <?= lang("Descrição", "descricao"); ?>
                                <?php echo form_input('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $slnumber), 'maxlength="200" class="form-control input-tip"  id="descricao"'); ?>
                            </div>
                                    <div class="form-group">
                                <?= lang("tipo", "tipo"); ?>
                                        <select class="form-control input-tip" name="tipo" required="required">
                                            <option value="1">Postagem</option>
                                            <option value="2">Aviso</option>
                                            <option value="3">Arquivo</option>
                                        </select>    
                             </div>
                               <div class="form-group">
                                        <?= lang("Documento", "document") ?> 
                                            <?php if($tap->anexo){ ?>
                                        <div class="btn-group">
                                            <a target="_blanck" href="<?= site_url('../assets/uploads/projetos/' . $tap->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                <i class="fa fa-chain"></i>
                                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                                            </a>
                                                    <?php /* <input type="checkbox"><button type="button" class="btn btn-danger" id="reset"><?= lang('REMOVER') ?> */ ?>
                                        </div>

                                        <?php } ?>
                                       <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" required="true" name="document" value="<?php echo $tap->anexo; ?>" data-show-upload="false"
                                               data-show-preview="false" class="form-control file">
                                    </div>
                               
                               
                                   <?php
                                   /*
                                    *  <div class="form-group">
                                        <?= lang("Para quais eventos irá replicar?", "slResponsavel"); ?>
                                        
                                        <?php
                                       
                                       $usuario = $this->session->userdata('user_id');
                                       $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                                       $eventos = $this->projetos_model->getAllEventosProjeto($projetos_usuario->projeto_atual,'ordem', 'asc');
                                     
                                       foreach ($eventos as $evento) {
                                            $wu4[$evento->id] = $evento->tipo.' - '.$evento->nome_evento;
                                        }
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('eventos[]', $wu4, (isset($_POST['eventos']) ? $_POST['eventos'] : $us), 'id="slResponsavel" required="required"  class="form-control  select" data-placeholder="' . lang("Selecione quem poderá acessar o documento") . ' "   style="width:100%; height: 200px;"  multiple ');
                              
                                        ?>
                                    </div>
                                    */
                                   ?>
                                   
                                    
                                    
                                   <div class="fprom-group">
                                    <?php echo form_submit('add_projeto', lang("Publicar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
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
