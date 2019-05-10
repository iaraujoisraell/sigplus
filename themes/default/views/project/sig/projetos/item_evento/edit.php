<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
     
      if (!localStorage.getItem('dateInicial')) {
            $("#dateInicial").datetimepicker({
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
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('Editar Item Evento'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                 <div class="row">
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("projetos/edit_item_evento", $attrib);
               
                echo form_hidden('id', $evento->id);
                ?>
               
                    <div class="col-lg-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Evento", "slProjeto"); ?>
                                    <?php
                                    $wu3[''] = '';
                                          echo form_dropdown('Evento_descricao', $projetos->nome_evento.' - De :'.$this->sma->hrld($projetos->data_inicio).'  Até :'.$this->sma->hrld($projetos->data_fim), (isset($_POST['projeto']) ? $_POST['projeto'] : $projetos->projeto .' - Dt Início :'.$projetos->dt_inicio), 'id="slProjeto" required="required" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                             
                                      echo form_hidden('evento', $projetos->id);
                                   // echo $projetos->projeto;
                                    ?>
                            </div>
                        </div>                        
                    </div>
                    <div class="col-lg-12">
                    <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Nome do Evento", "slprojeto"); ?>
                                <?php echo form_input('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $evento->descricao), 'maxlength="200" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="col-sm-4">
                                <div class="form-group">
                                 <?= lang("Data Início", "start_date"); ?>
                                       <input type="date" name="dateInicial" value="<?php echo substr($evento->dt_inicio,0,10); ?>" id='dateFim' class="form-control">
                                
                                 </div>
                                   
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                 <?= lang("Data Término", "dateEntregaDemanda"); ?>
                                               <input type="date" name="dateFim"  id='dateFim' class="form-control" value="<?php echo substr($evento->dt_fim,0,10); ?>">
                                
                                  </div>
                                    
                                </div>
                        
                        </div>
                    
                     
                     <div class="col-md-12">    
                     <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Horas Previstas", "sltipo"); ?>
                                <input type="number" name="horas" value="<?php echo $evento->horas_previstas; ?>" id='horas' class="form-control">
                            </div>
                        </div>
                    </div>
                    
                  
                    <div class="col-md-12">    
                    

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <?= lang("Observação", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $evento->observacoes), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                       


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                  <a  class="btn btn-danger"   href="<?= site_url('Projetos/Item_evento_index/'.$evento->evento); ?>"> <div ><?= lang('Sair ') ?></div>  </a>  
                             </div>
                        </div>
                    </div>
                
                

                <?php echo form_close(); ?>

          
    </div>
</div>


