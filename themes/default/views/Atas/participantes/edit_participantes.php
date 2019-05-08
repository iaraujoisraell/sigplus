<?php $data_hoje = date('Y-m-d H:i:s'); ?>
<script>
  localStorage.setItem('date', '<?= $this->sma->hrld($data_hoje) ?>');
  
    $("#date").datetimepicker({
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
</script>
    
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Editar Cadastro de Participante'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("atas/edit_participante/" , $attrib); ?>
          <input type="hidden" value="<?php echo $projeto; ?>" name="projeto"/>
          <input type="hidden" value="<?php echo $participante->id; ?>" name="id_participante"/>
        <div class="modal-body">
            

            <div id="payments">
               
                <div class="well well-sm well_1">
                    <div class="col-md-12">
                         <?= lang("Nome", "slelaboracao"); ?>
                                <?php echo form_input('nome', (isset($_POST['nome']) ? $_POST['nome'] : $participante->nome), 'maxlength="200" class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                      
                        <div class="clearfix"></div>
                        
                      
                       
                    </div>
                    <div class="col-md-12">
                         <?= lang("Setor", "slelaboracao"); ?>
                                <?php echo form_input('setor', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $participante->setor), 'maxlength="200" class="form-control input-tip" required="required" id="slelaboracao"'); ?>
                      
                        <div class="clearfix"></div>
                        
                      
                       
                    </div>
                    <div class="col-md-12">
                         <?= lang("Cargo", "slelaboracao"); ?>
                                <?php echo form_input('cargo', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $participante->funcao), 'maxlength="200" class="form-control input-tip"  id="slelaboracao"'); ?>
                      
                        <div class="clearfix"></div>
                        
                      
                       
                    </div>
                    <div class="col-md-12">
                         <?= lang("Email", "slelaboracao"); ?>
                                <?php echo form_input('email', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $participante->email), 'maxlength="200" class="form-control input-tip"  id="slelaboracao"'); ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div> 
                    <div class="col-md-12">
                                <div class="form-group">
                                    <?= lang("Vincular ao usuário do sistema.", "slAta_usuario"); ?>
                                    <div class="input-group">
                                        <?php
                                        $wu4[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->username;
                                        }
                                        
                                        echo form_dropdown('usuario', $wu4, (isset($_POST['usuario']) ? $_POST['usuario'] : $participante->usuario), 'id="slAta_usuario"  class="form-control selectpicker  select" data-placeholder="' . lang("Vincular ao Usuário do Sistema") . ' "  style="width:100%;" ');
                                        ?>
                                        
                                        <div class="input-group-addon no-print" style="padding: 2px 8px;">

                                        </div>
                                    </div>
                                </div>
                           

                        </div>
                    <div class="clearfix"></div>
                </div>
                <center>
                    <div class="col-md-12">
                    <div
                        class="fprom-group">
                            <?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                       
                    </div>
                </div>
                </center>
                <br><br>
                
            </div>
<br>
          
           

        </div>
        
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

