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
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Confirmar FATURA'); ?></h4>
            <p><?php echo 'Referente : '.$inv->description; ?></p>
             <p><?php echo 'Parcela : '.$inv->parcela_atual.'/'.$inv->parcela; ?></p>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("financeiro/add_payment/" . $inv->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
              
                
                <input type="hidden" value="<?php echo $inv->id; ?>" name="id"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">

                <div class="well well-sm well_1">
                    <div class="col-md-12">
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                <?= lang("fatura", "fatura"); ?>
                                    <input name="fatura" type="text" id="fatura"
                                           value="<?php echo $inv->fatura; ?>"
                                           class="pa form-control kb-pad amount" required="required"/>    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Valor Pagar", "sldiscount"); ?>
                                    <?php echo form_input('value', (isset($_POST['value']) ? $_POST['value'] : str_replace('.', ',', $inv->amount)), 'maxlength="15" onkeypress="mascara(this, mvalor);" class="form-control input-tip" id="sldiscount" required="required"'); ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                <?= lang("Valor Pago", "valor_pago"); ?>
                                            <?php echo form_input('valor_pago', (isset($_POST['valor_pago']) ? $_POST['valor_pago'] : str_replace('.', ',', $inv->cr)), 'maxlength="15" class="form-control input-tip" onkeypress="mascara(this, mvalor);"  id="valor_pago" '); ?>                              
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="payment">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <?= lang("Sit.Pgto", "slpayment_status"); ?>
                                            <?php 
                                                $pst['ABERTO'] = lang('ABERTO');
                                                $pst['PAGO'] = lang('PAGO');
                                                $pst['ATRASADO'] = lang('ATRASADO');
                                                echo form_dropdown('payment_status', $pst, (isset($_POST['payment_status']) ? $_POST['payment_status'] : $inv->status), 'id="slpayment_status" readonly="true" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("payment_status") . '" required="required"   style="width:100%;" '); ?>                                                                                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang("Data Recebimento", "data_recebimento"); ?>
                                    <input value="<?php echo $inv->data_recebimento; ?>"  class="form-control input-tip datetime" id="data_recebimento" required="required" type="date" name="data_recebimento">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang("Data Envio", "data_envio"); ?>
                                    <input value="<?php echo $inv->data_envio; ?>"  class="form-control input-tip datetime" name="data_envio" id="data_envio"  type="date" >
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <?= lang("Data Pagamento", "data_pagamento"); ?>
                                    <input value="<?php echo $inv->date_pagamento; ?>"  class="form-control input-tip datetime" id="data_pagamento"  type="date" name="data_pagamento">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                <?= lang("Título", "titulo"); ?>
                                    <input name="titulo" type="text" id="titulo"
                                           value="<?php echo $inv->titulo; ?>"
                                           class="form-control samount" />    
                                </div>
                            </div>
                        </div>
                        
                      
                       
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

          
           

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_payment', lang('add_payment'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

