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
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Editar Projeto'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("projetos/edit", $attrib);
                
                    echo form_hidden('id', $id);
                
                ?>
                <div class="row">
                    <div class="col-lg-12">
                    <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Nome do Projeto", "slprojeto"); ?>
                                <?php echo form_input('nome_projeto', (isset($_POST['nome_projeto']) ? $_POST['nome_projeto'] : $projeto->projeto), 'maxlength="200" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                       
                        
                            
                       

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Data de Início", "sldateIni"); ?>
                                    <?php echo form_input('dateInicial', (isset($_POST['dateInicial']) ? $_POST['dateInicial'] : $this->sma->hrld($projeto->dt_inicio)), 'class="form-control input-tip datetime" id="sldateIni" required="required"'); ?>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Data Final", "sldateFim"); ?>
                                    <?php echo form_input('dateFim', (isset($_POST['dateFim']) ? $_POST['dateFim'] : $this->sma->hrld($projeto->dt_final)), 'class="form-control input-tip datetime" id="sldateFim" required="required"'); ?>
                                </div>
                            </div>
                        
                        <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Data da Virada", "sldateVirada"); ?>
                                    <?php echo form_input('dateVirada', (isset($_POST['dateVirada']) ? $_POST['dateVirada'] : $this->sma->hrld($projeto->dt_virada)), 'class="form-control input-tip datetime" id="sldateVirada" '); ?>
                                </div>
                            </div>
                        
                                 
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Gerente da Área", "slGerenteArea"); ?>
                                            <div class="input-group">
                                             
                                                <?php
                                                $wu1[''] = '';
                                                foreach ($users as $user1) {
                                                    $wu1[$user1->first_name. ' '.$user1->last_name] = $user1->first_name. ' '.$user1->last_name;
                                                }
                                                echo form_dropdown('gerenteArea', $wu1, (isset($_POST['provider']) ? $_POST['provider'] : $projeto->gerente_area), 'id="slGerenteArea" class="form-control  select" data-placeholder="' . lang("Selecione") . ' " required="required" style="width:100%;" ');
                                                ?>
                                                
                                                
                                             <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                                    
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                              
                           
                        <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Gerente EDP", "slEDP"); ?>
                                            <div class="input-group">
                                             
                                                <?php
                                               $wu2[''] = '';
                                                foreach ($users as $user1) {
                                                    $wu2[$user1->first_name. ' '.$user1->last_name] = $user1->first_name. ' '.$user1->last_name;
                                                }
                                                echo form_dropdown('gerenteEDP', $wu2, (isset($_POST['gerenteEDP']) ? $_POST['gerenteEDP'] : $projeto->gerente_edp), 'id="slEDP" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                                ?>
                                                
                                                
                                             <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                                    
                                                </div>
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                        
                        
                        <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Gerente Fornecedor", "slGerenteFornecedor"); ?>
                                            <div class="input-group">
                                             
                                                <?php
                                                $wu3[''] = '';
                                                foreach ($users as $user1) {
                                                    $wu3[$user1->first_name. ' '.$user1->last_name] = $user1->first_name. ' '.$user1->last_name;
                                                }
                                                echo form_dropdown('gerenteFornecedor', $wu3, (isset($_POST['gerenteFornecedor']) ? $_POST['gerenteFornecedor'] : $projeto->gerente_fornecedor), 'id="slGerenteFornecedor" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                                ?>
                                                
                                                 <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        
                        <div class="clearfix"></div>
                        
                          <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Fornecedor", "slFornecedor"); ?>
                                            <div class="input-group">
                                             
                                                <?php
                                                $wh2[''] = '';
                                                foreach ($providers as $provider) {
                                                    $wh2[$provider->company] = $provider->company;
                                                }
                                                echo form_dropdown('fornecedor', $wh2, (isset($_POST['fornecedor']) ? $_POST['fornecedor'] : $projeto->fornecedor), 'id="slFornecedor" class="form-control  select" data-placeholder="' . lang("Selecione o Fornecedor") . ' "  style="width:100%;" ');
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
                                            <?= lang("Fornecedor 2", "slFornecedor2"); ?>
                                            <div class="input-group">
                                             
                                                <?php
                                                $wh2[''] = '';
                                                foreach ($providers as $provider) {
                                                    $wh2[$provider->company] = $provider->company;
                                                }
                                                echo form_dropdown('fornecedor2', $wh2, (isset($_POST['fornecedor2']) ? $_POST['fornecedor2'] : $projeto->fornecedor2), 'id="slFornecedor2" class="form-control  select" data-placeholder="' . lang("Selecione o Fornecedor") . ' "  style="width:100%;" ');
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
                                            <?= lang("Fornecedor 3", "slFornecedor3"); ?>
                                            <div class="input-group">
                                             
                                                <?php
                                                $wh2[''] = '';
                                                foreach ($providers as $provider) {
                                                    $wh2[$provider->company] = $provider->company;
                                                }
                                                echo form_dropdown('fornecedor3', $wh2, (isset($_POST['fornecedor3']) ? $_POST['fornecedor3'] : $projeto->fornecedor3), 'id="slFornecedor3" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
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
                                    
                      


                        <div class="col-md-12" id="sticker">
                                <div class="form-group" style="margin-bottom:0;">
                          
                                </div>
                                <div class="clearfix"></div>
                           
                        </div>

                       

                     

                        


                        <div class="col-md-4">
                           <div class="form-group">
                                <?= lang("document", "document") ?> 
                                    <?php if($projeto->anexo){ ?>
                                <div class="btn-group">
                            <a href="<?= site_url('assets/uploads/projetos/' . $projeto->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                            </a>
                                    <?php /* <input type="checkbox"><button type="button" class="btn btn-danger" id="reset"><?= lang('REMOVER') ?> */ ?>
                        </div>
                               
                                <?php } ?>
                               <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" value="<?php echo $projeto->anexo; ?>" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                            
                        </div>

                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Status do Projeto", "projeto_status"); ?>
                                <?php $pst[$projeto->status] = $projeto->status;
                                  $pst['ATIVO'] = lang('ATIVO');
                                  $pst['CANCELADO'] = lang('CANCELADO');
                                  $pst['CONCLUÍDO DENTRO DO PRAZO'] = lang('CONCLUÍDO DENTRO DO PRAZO');
                                  $pst['CONCLUÍDO FORA DO PRAZO'] = lang('CONCLUÍDO FORA DO PRAZO');
                                  $pst['ATRASADO'] = lang('ATRASADO');
                                 // $pst['partial'] = lang('partial');
                                 
                                  
                                echo form_dropdown('status', $pst, (isset($_POST['status']) ? $_POST['status'] : $projeto->status), 'id="projeto_status" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("payment_status") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>

                      

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("Observação", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $projeto->obs), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                       


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                               <a  class="btn btn-danger" href="<?= site_url('projetos'); ?>"><?= lang('Cancelar') ?></a>
                        </div>
                    </div>
                </div>
                
                
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>


