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
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Usuários Vinculados a Pauta'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("atas/usuarios_vinculados/" . $ata->id, $attrib); ?>
        <div class="modal-body">
            <p>ATA <?php echo $ata->id; ?></p>

            <div class="row">
                <?php
                
                if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                                    <?= lang("Data da ATA", "sldate"); ?>
                                    <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($ata->data_ata)), 'class="form-control input-tip datetime" id="sldate" required=$projeto"required"'); ?>
                                </div>
                    </div>
                <?php } ?>
                

                <input type="hidden" value="<?php echo $inv->id; ?>" name="id"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">
                <h3>Usuários:</h3>
                <div class="well well-sm well_1">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <table>
                                  
                                    <?php
                                    $wu3[''] = '';
                                    foreach ($usuarios as $usuario) {
                                        $wu3[$usuario->first_name] = $projeto->first_name;
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $usuario->first_name; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                   
                                </table>
                            </div>
                          

                        </div>
                        <div class="clearfix"></div>
                        
                      
                       
                    </div>
                    <div class="clearfix"></div>
                </div>

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

