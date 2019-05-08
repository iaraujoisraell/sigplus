<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
     
      if (!localStorage.getItem('sldate')) {
            $("#sldate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', <?php echo $inv->date ?>);
        }
        
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
  <script type="text/javascript">
/* Máscaras ER */
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mcep(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2")         //Esse é tão fácil que não merece explicações
    return v
}
function mdata(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2");       
    v=v.replace(/(\d{2})(\d)/,"$1/$2");       
                                             
    v=v.replace(/(\d{2})(\d{2})$/,"$1$2");
    return v;
}
function mrg(v){
	v=v.replace(/\D/g,'');
	v=v.replace(/^(\d{2})(\d)/g,"$1.$2");
	v=v.replace(/(\d{3})(\d)/g,"$1.$2");
	v=v.replace(/(\d{3})(\d)/g,"$1-$2");
	return v;
}
function mvalor(v){
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
    v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões
    v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares
        
    v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}
function next( el, next )
{
	if( el.value.length >= el.maxLength ) 
		id( next ).focus(); 
}
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.maskMoney.js"></script>

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
                       
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] :  $this->sma->hrld($inv->date) ), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
                                </div>
                            </div>
                        

                        <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Contrato", "contrato"); ?>
                                    <?php
                                    $bl2[""] = "Selecione ";
                                    foreach ($contratos as $contrato) {
                                        $bl2[$contrato->id] = $contrato->contrato.' - '.$contrato->company;
                                    }
                                    echo form_dropdown('contrato', $bl2, (isset($_POST['contrato']) ? $_POST['contrato'] : $inv->id_contrato), 'id="contrato" data-placeholder="' . lang("select") . ' ' . lang("contrato") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                            
                                 
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("provider", "slcustomer"); ?>
                                                <?php
                                                $wh2[''] = '';
                                                foreach ($providers as $provider) {
                                                    $wh2[$provider->id] = $provider->company;
                                                }
                                                echo form_dropdown('provider', $wh2, (isset($_POST['provider']) ? $_POST['provider'] : $inv->payer), 'id="slcustomer" class="form-control  select" data-placeholder="' . lang("select") . ' ' . lang("provider") . '" required="required" style="width:100%;" ');
                                                ?>
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
                                    <?php echo form_input('value', (isset($_POST['value']) ? $_POST['value'] : str_replace('.', ',', $inv->amount)), 'maxlength="15" onkeypress="mascara(this, mvalor);" class="form-control input-tip" id="sldiscount" required="required"'); ?>
                                </div>
                            </div>
                        
   
                        
                          <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_status", "slpayment_status"); ?>
                                <?php $pst[''] = '';
                                  $pst1['PAGO'] = lang('paid');
                                  $pst1['ABERTO'] = lang('pending');
                                  $pst1['ATRASADO'] = lang('Atrasado');
                               
                                 
                                  
                                echo form_dropdown('payment_status', $pst1, (isset($_POST['payment_status']) ? $_POST['payment_status'] : $inv->status), 'id="slpayment_status" readonly="true" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("payment_status") . '" required="required"   style="width:100%;" '); ?>
                                           
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
                                                echo form_dropdown('categorie', $ec2, $inv->category , 'id="slexpense_categories" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("categorie") . '" required="required" style="width:100%;" ');
                                                ?>
                                            </div>
                                        </div>
                                    
                      


                        

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("document", "document") ?> 
                                    <?php if($inv->attachment){ ?>
                                <div class="btn-group">
                            <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                            </a>
                        </div>
                                <?php } ?>
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("plots", "quantidade"); ?>
                                <?php echo form_input('quantidade', (isset($_POST['quantidade']) ? $_POST['quantidade'] : $inv->parcela_atual.'/'.$inv->parcela), 'class="form-control tip" maxlength="6" readonly="true" data-trigger="focus" data-placement="top" value="1" title="' . lang('payment_term_tip') . '" id="quantidade"'); ?>

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
                                 <a  class="btn btn-danger"  href="<?= site_url('financeiro'); ?>"><?= lang('Sair') ?></a></div>
                        </div>
                    </div>
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>





