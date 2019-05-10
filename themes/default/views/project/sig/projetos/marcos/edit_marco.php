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
                            <div class="modal-header"><p class="introtext">EDITAR MARCO <?php echo $id; ?></p>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    <i class="fa fa-2x">&times;</i>
                                </button>
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                                <div class="error"></div>
                               <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("Projetos/edit_marco" , $attrib); 
                    echo form_hidden('id', $evento->id);
                    ?>
                                    <input type="hidden" value="" name="eid" id="eid">
                                    <div class="form-group">
                                        <?= lang('title', 'title'); ?>
                                            <?php echo form_input('title', (isset($_POST['title']) ? $_POST['title'] : $evento->title), 'maxlength="200" class="form-control input-tip" required="required" id="title"'); ?>
                          
                                     
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang('start', 'start'); ?>
                                               
                                                <?php echo form_input('start', (isset($_POST['start']) ? $_POST['start'] : $this->sma->hrld($evento->start)), 'class="form-control datetime" id="start_date"'); ?>
                               
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang('end', 'end'); ?>
                                              
                                                  <?php echo form_input('end', (isset($_POST['end']) ? $_POST['end'] : $this->sma->hrld($evento->end)), 'class="form-control datetime" id="dateEntregaDemanda" '); ?>
                             
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang('event_color', 'color'); ?>
                                                <div class="input-group">
                                                    
                                                    <select style="background-color: <?php if($evento->color == "default"){ echo '#333333';}else{ echo $evento->color;} ?>" name="color" class="form-control input-md">
                                                        <option value="<?php echo $evento->color ?>"><?php echo $evento->color ?></option>
                                                        <option style="background-color: green" value="green">green</option>
                                                        <option style="background-color: orange" value="orange">orange</option>
                                                        <option style="background-color: blue" value="blue">blue</option>
                                                        <option style="background-color: red" value="red">red</option>
                                                        <option style="background-color: purple" value="purple">purple</option>
                                                        <option style="background-color: #333333" value="default">default</option>
                                                        <option style="background-color: white" value="white">white</option>
                                                    </select>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <?= lang('description', 'description'); ?>
                                             <?php echo form_textarea('description', (isset($_POST['description']) ? $_POST['description'] : $evento->description), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                     
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
