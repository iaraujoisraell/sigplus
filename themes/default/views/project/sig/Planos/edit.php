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
           
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Editar Plano de Ação'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("planos/edit/" . $plano->idplanos, $attrib); ?>
         <input type="hidden" value="<?php echo $plano->idplanos; ?>" name="idplano"/>
        <div class="modal-body">
            <p>PLANO <?php echo $plano->idplanos; ?></p>

            <div class="row">
                <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("Descrição", "sldescricao"); ?>
                                <?php echo form_input('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $plano->descricao), 'maxlength="200" class="form-control input-tip" required="required" id="sldescricao"'); ?>
                            </div>
                        </div>
                <?php
                
                if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                                    <?= lang("Data da Entrega", "sldate"); ?>
                                    <?php echo form_input('dateEntrega', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($plano->data_termino)), 'class="form-control input-tip datetime" id="sldate" required=$projeto"required"'); ?>
                                </div>
                    </div>
                <?php } ?>
                <div class="col-md-6">
                                        <div class="form-group">
                                            <?= lang("Responsável", "slResponsavel"); ?>
                                            
                                             
                                                <?php
                                                $wu4[''] = '';
                                                foreach ($users as $user) {
                                                    $wu4[$user->id] = $user->username;
                                                }
                                                echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $plano->responsavel), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;"  required="required"');
                                                ?>
                                                
                                               
                                            
                                        </div>
                                    </div>
                <div class="col-sm-6">
                            <div class="form-group">
                                <?= lang("Status ", "status_plano"); ?>
                                <?php $pst2[''] = '';
                                  $pst2['NO PRAZO'] = lang('NO PRAZO');
                                  $pst2['SEM RESPOSTA'] = lang('SEM RESPOSTA');
                                  $pst2['CONCLUÍDO DENTRO DO PRAZO'] = lang('CONCLUÍDO DENTRO DO PRAZO');
                                  $pst2['CONCLUÍDO FORA DO PRAZO'] = lang('CONCLUÍDO FORA DO PRAZO');
                                  $pst2['ATRASADO'] = lang('ATRASADO');
                                 // $pst['partial'] = lang('partial');
                                 
                                  
                                echo form_dropdown('status_plano', $pst2, (isset($_POST['status_plano']) ? $_POST['status_plano'] : $plano->status), 'id="status_plano" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o Status") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                
               <center>
                            <div class="col-md-12">
                            <div
                                class="fprom-group center"><?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <a  class="btn btn-danger" href="<?= site_url('planos'); ?>"><?= lang('Cancelar') ?></a>
                            </div>
                        </div>
                            </center>
            </div>
            <div class="clearfix"></div>
           
          
           

        </div>
        
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

