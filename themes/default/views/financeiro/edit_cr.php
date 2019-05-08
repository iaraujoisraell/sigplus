<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
        <?php if ($inv) { ?>
        localStorage.setItem('sldate', '<?= $this->sma->hrld($inv->date) ?>');
        localStorage.setItem('slcustomer', '<?= $inv->customer_id ?>');
        localStorage.setItem('slbiller', '<?= $inv->biller_id ?>');
        localStorage.setItem('slref', '<?= $inv->reference_no ?>');
        localStorage.setItem('slwarehouse', '<?= $inv->warehouse_id ?>');
        localStorage.setItem('slsale_status', '<?= $inv->sale_status ?>');
        localStorage.setItem('slpayment_status', '<?= $inv->payment_status ?>');
        localStorage.setItem('slpayment_term', '<?= $inv->payment_term ?>');
        localStorage.setItem('slnote', '<?= str_replace(array("\r", "\n"), "", $this->sma->decode_html($inv->note)); ?>');
        localStorage.setItem('slinnote', '<?= str_replace(array("\r", "\n"), "", $this->sma->decode_html($inv->staff_note)); ?>');
        localStorage.setItem('sldiscount', '<?= $inv->order_discount_id ?>');
        localStorage.setItem('sltax2', '<?= $inv->order_tax_id ?>');
        localStorage.setItem('slshipping', '<?= $inv->shipping ?>');
        localStorage.setItem('slitems', JSON.stringify(<?= $inv_items; ?>));
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        $(document).on('change', '#sldate', function (e) {
            localStorage.setItem('sldate', $(this).val());
        });
        if (sldate = localStorage.getItem('sldate')) {
            $('#sldate').val(sldate);
        }
        $(document).on('change', '#slbiller', function (e) {
            localStorage.setItem('slbiller', $(this).val());
        });
        if (slbiller = localStorage.getItem('slbiller')) {
            $('#slbiller').val(slbiller);
        }
        <?php } ?>
        ItemnTotals();
        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#slcustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#slwarehouse").val(),
                        customer_id: $("#slcustomer").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        $(window).bind('beforeunload', function (e) {
            localStorage.setItem('remove_slls', true);
            if (count > 1) {
                var message = "You will loss data!";
                return message;
            }
        });
        $('#reset').click(function (e) {
            $(window).unbind('beforeunload');
        });
        $('#edit_sale').click(function () {
            $(window).unbind('beforeunload');
            $('form.edit-so-form').submit();
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edd_despesas'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("financeiro/edit", $attrib);
                
                    echo form_hidden('id', $id);
                
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "slref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $inv->ref), 'maxlength="200" class="form-control input-tip" id="slref"'); ?>
                            </div>
                        </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("account", "saccount"); ?>
                                    <?php
                                    $bl[""] = "";
                                    foreach ($billers as $biller) {
                                        $bl[$biller->account] = $biller->account != '-' ? $biller->account : $biller->account;
                                    }
                                    echo form_dropdown('account', $bl, (isset($_POST['account']) ? $_POST['account'] : $inv->account), 'id="saccount" data-placeholder="' . lang("select") . ' ' . lang("account") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                 
                        
                                 
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("provider", "slcustomer"); ?>
                                            <div class="input-group">
                                             
                                                <?php
                                                $wh2[''] = '';
                                                foreach ($providers as $provider) {
                                                    $wh2[$provider->company] = $provider->company;
                                                }
                                                echo form_dropdown('provider', $wh2, (isset($_POST['provider']) ? $_POST['provider'] : $inv->payer), 'id="slcustomer" class="form-control  select" data-placeholder="' . lang("select") . ' ' . lang("provider") . '" required="required" style="width:100%;" ');
                                                ?>
                                                
                                                
                                            
                                                
                                                <?php if ($Owner || $Admin || $GP['suppliers-add']) { ?>
                                                <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                                    <a href="<?= site_url('suppliers/add'); ?>" id="add-customer" class="external" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-plus-circle" id="addIcon"  style="font-size: 1.2em;"></i>
                                                    </a>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                              
                           
                        <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("description", "sldescription"); ?>
                                    <?php echo form_input('description', (isset($_POST['description']) ? $_POST['description'] : $inv->description), 'maxlength="200" class="form-control input-tip" id="sldescription" required="required"'); ?>
                                </div>
                            </div>
                        
                        
                        <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("value", "sldiscount"); ?>
                                    <?php echo form_input('value', (isset($_POST['value']) ? $_POST['value'] : $inv->amount), 'maxlength="15" class="form-control input-tip" id="sldiscount" required="required"'); ?>
                                </div>
                            </div>
                        
                        <div class="clearfix"></div>
                        
                          <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_status", "slpayment_status"); ?>
                                <?php $pst[''] = '';
                                 $pst['paid'] = lang('paid');
                                  $pst['pending'] = lang('pending');
                                //  $pst['due'] = lang('due');
                                  $pst['partial'] = lang('partial');
                                 
                                  
                                echo form_dropdown('payment_status', $pst, (isset($_POST['payment_status']) ? $_POST['payment_status'] : $inv->status), 'id="slpayment_status" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("payment_status") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
               
                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang("pagament_form", "slpagament_form"); ?>
                                                <?php
                                                $wh5[''] = '';
                                               //$wh5 = array('cash' => lang('cash'), 'cheque' => lang('cheque'), 'credito' => lang('credito'), 'debito' => lang('debito'), 'transferencia' => lang('transferencia'), 'boleto' => lang('boleto'));
                                                  $wh5['cash'] = lang('cash');
                                                 $wh5['transferencia'] = lang('transferencia');
                                                  $wh5['boleto'] = lang('boleto');
                                                  $wh5['credito'] = lang('credito');
                                                  $wh5['debito'] = lang('debito');
                                                  $wh5['cheque'] = lang('cheque');
                                                  $wh5['gift_card'] = lang('gift_card');
                                                  $wh5['other'] = lang('other');
                                                  $wh5['deposit'] = lang('deposit');
                                               echo form_dropdown('pagament_form', $wh5,  (isset($_POST['pagament_form']) ? $_POST['pagament_form'] : $inv->method), 'id="slpagament_form" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("pagament_form") . '"  style="width:100%;" ');
                                           
                                                ?>
                                            </div>
                                        </div>
                           
                        
                                      <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang("categorie", "slexpense_categories"); ?>
                                                <?php
                                                $ec2[''] = '';
                                                foreach ($expense_categories as $ec) {
                                                    $ec2[$ec->name] = $ec->name;
                                                }
                                                echo form_dropdown('categorie', $ec2, (isset($_POST['categorie']) ? $_POST['categorie'] : $inv->category), 'id="slexpense_categories" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("categorie") . '" required="required" style="width:100%;" ');
                                                ?>
                                            </div>
                                        </div>
                                    
                      


                        <div class="col-md-12" id="sticker">
                                <div class="form-group" style="margin-bottom:0;">
                          
                                </div>
                                <div class="clearfix"></div>
                           
                        </div>

                       

                     

                        


                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("document", "document") ?> 
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("plots", "slpayment_term"); ?>
                                <?php echo form_input('quantidade', (isset($_POST['quantidade']) ? $_POST['quantidade'] : $inv->parcela_atual.'/'.$inv->parcela), 'class="form-control tip" maxlength="3" readonly="true" data-trigger="focus" data-placement="top" value="1" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>

                            </div>
                        </div>
                        
                        <div class="clearfix"></div>

                     
                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("note", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $inv->note), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                       


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_sale', lang("submit"), 'id="add_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>


<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>
                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="pserial" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pserial">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>

                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="mserial" class="col-sm-4 control-label"><?= lang('product_serial') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mserial">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="mdiscount" class="col-sm-4 control-label">
                                <?= lang('product_discount') ?>
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>
