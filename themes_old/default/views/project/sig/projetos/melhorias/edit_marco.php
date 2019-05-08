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
                            <div class="modal-header"><p class="introtext">EDITAR MELHORIA</p>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    <i class="fa fa-2x">&times;</i>
                                </button>
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                                <div class="error"></div>
                               <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                echo form_open_multipart("Projetos/edit_melhorias" , $attrib); 
                                echo form_hidden('id', $melhoria->id);
                                ?>
                                   
                                    <div class="form-group">
                                        <?= lang('title', 'title'); ?>
                                            <?php echo form_input('title', (isset($_POST['title']) ? $_POST['title'] : $melhoria->titulo), 'maxlength="200" class="form-control input-tip" required="required" id="title"'); ?>
                          
                                     
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang('Mês', 'mes'); ?>
                                                
                                                    
                                                    <select  name="mes" required="required" class="form-control input-md">
                                                        <option value="<?php echo $melhoria->mes; ?>"><?php echo $melhoria->mes; ?></option>
                                                        <option value="Janeiro">Janeiro</option>
                                                        <option value="Fevereiro">Fevereiro</option>
                                                        <option value="Março">Março</option>
                                                        <option value="Abril">Abril</option>
                                                        <option value="Maio">Maio</option>
                                                        <option value="Junho">Junho</option>
                                                        <option value="Julho">Julho</option>
                                                        <option value="Agosto">Agosto</option>
                                                        <option value="Setembro">Setembro</option>
                                                        <option value="Outubro">Outubro</option>
                                                        <option value="Novembro">Novembro</option>
                                                        <option value="Dezembro">Dezembro</option>
                                                    </select>
                                                    
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                             <?= lang('Ano', 'ano'); ?>
                                                   <?php echo form_input('ano', (isset($_POST['title']) ? $_POST['title'] : $melhoria->ano), 'maxlength="200" class="form-control input-tip" required="required" id="title"'); ?>
                                  </div>
                                        </div> 
                                    </div>

                                    <div class="form-group">
                                        <?= lang('description', 'description'); ?>
                                             <?php echo form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : $melhoria->melhoria), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                     
                                    </div>
                                   <div class="fprom-group">
                            <?php echo form_submit('add_projeto', lang("Editar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                       
                    </div>
                                    <?php echo form_close(); ?>
                            </div>
                           
                        </div>
                    </div>
               
           

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
