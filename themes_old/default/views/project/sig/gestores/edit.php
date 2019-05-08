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
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Vincular o(s) Setor(es) ao Gestor'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("gestores/edit/" . $id, $attrib); ?>
        <div class="modal-body">
            <?php
            $cadastroUsuario = $this->site->getPerfilAtualByID($id);
            $nome = $cadastroUsuario->first_name;
            $lnome = $cadastroUsuario->last_name;
            ?>
            <p>Gestor : <?php echo $nome.' '; ?> <?php echo $lnome; ?></p>

            <div class="row">
                <input type="hidden" value="<?php echo $id; ?>" name="id"/>
             
                
            </div>
            <div class="clearfix"></div>
            <div id="payments">
                <h3>Setores Vinculados:</h3>
                <div class="well well-sm well_1">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                
                               
                                <?php
                                foreach ($setores as $setor) {
                                    $gp2[$setor['id']] = $setor['nome'];
                                }
                                
                               
                                foreach ($setores_vinculados as $setor_vinculado) {
                                               
                                   $wp[$setor_vinculado->setor_id] = $setor_vinculado->setor_id;     
                                   
                                }
                                
                                echo form_dropdown('setor[]', $gp2, (isset($_POST['setor']) ? $_POST['setor'] : $wp), 'id="setor" required="required" multiple class="form-control select" style="width:500px;" data-placeholder="' . lang("Click e selecione o(s) Setores") . ' " ');
                                ?>
                           
                            </div>
                          

                        </div>
                        <div class="clearfix"></div>
                        
                      
                       
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

          <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                 <a  class="btn btn-danger" href="<?= site_url('Gestores/index'); ?>"><?= lang('Cancelar') ?></a>
                        </div>  
           

        </div>
        
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

