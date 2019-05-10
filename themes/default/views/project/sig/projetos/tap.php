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
<?php
    $projeto_doc = $documentacao->projeto;
    $projeto = $this->projetos_model->getProjetoByID($projeto_doc);
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang($documentacao->nome_documento. ' - '. $projeto->projeto); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                
               
               
                <div class="row">
                    <div class="col-lg-12">
                    <div class="col-md-4">
                            <div class="form-group">
                               
                               </div>
                        </div>
                        
                        
                       
                    </div>
                    
                    
                    
                    
                    <?php
                       
                        $cont_sessao = 0;
                        //print_r($taps);
                        foreach ($taps as $tap) {

                            $cont_sessao++;
                          
                        }    
                    ?>   
                    <div style="margin-top: 10px; margin-bottom: 10px;" class="col-md-6">
                    
                    <?php if($documentacao->status != "FINALIZADO"){ ?>
                        <a style="background-color: orange; color: #ffffff;" class="btn btn-orange"  href="<?=site_url('projetos/add_tap_sessao/'.$id)?>"> 
                        <i class="fa fa-plus"></i>   <?=lang('Nova Sessão')?>
                    </a>
                      
                      <?php if($cont_sessao > 0){ ?>   
                        <a class="btn btn-success"  data-toggle="modal" data-target="#myModal" href="<?=site_url('projetos/concluir_documentacao/'.$id)?>"> 
                            <i class="fa fa-check"></i>   <?=lang('Concluir Documento')?>
                        </a>
                     <?php } ?>   
                    <?php } ?>         
                     <?php if((!$documentacao->anexo)&&($documentacao->status != "RASCUNHO")){ ?>
                    <a class="btn btn-default"  data-toggle="modal" data-target="#myModal" href="<?=site_url('projetos/anexar_documentacao/'.$id)?>"> 
                        <i class="fa fa-paperclip"></i>   <?=lang('Anexar Documento Assinado')?>
                    </a>
                    <?php } ?>       
                    <a class="btn btn-danger"  href="<?=site_url('projetos/ver_tap/'.$id)?>"> 
                        <i class="fa fa-download"></i>   <?=lang('Visualizar PDF')?>
                    </a>
                        <a class="btn btn-primary"  href="<?=site_url('projetos/gestao_documentacao_index')?>"> 
                        <i class="fa fa-backward"></i>   <?=lang('Voltar')?>
                    </a>
                        
                </div>
                   <table style="width:100%; background-color: lightgray" id="example-table" class="table table-striped table-bordered table-hover table-striped">
                                                                
                         <thead>
                           
                                <td style="width:5%;"><font style="font-size: 18px;">ID</font></td>
                                <td style="width:20%;"><font style="font-size: 18px;">Título</font></td>
                                <td><font style="width:45%; font-size: 18px;">Descrição</font></td>
                                 <td><font style="width:20%;font-size: 18px;">Anexo</font></td>
                                 <?php if($documentacao->status != "FINALIZADO"){ ?>
                                 <td><font style="width:5%;font-size: 18px;">Editar</font></td>
                                 <td><font style="width:5%;font-size: 18px;">Excluir</font></td>
                                 <?php } ?>
                         </thead>
                    <?php
                        $wu4[''] = '';
                        $cont = 1;
                        //print_r($taps);
                        foreach ($taps as $tap) {


                         //$res_tec = $this->site->geUserByID($evento->responsavel);
                         //$res_edp = $this->site->geUserByID($evento->responsavel_edp);   

                    ?>   
                     
                         <tr>
                              <td style="width:5%;"><font style="font-size: 18px;"><?php echo $cont++; ?></font></td>
                                <td style="width:20%;"><font style="font-size: 18px;"><?php echo $tap->titulo; ?></font></td>
                                <td><font style="width:45%; font-size: 18px;"><?php echo $tap->descricao; ?></font></td>
                                 <td>
                                     <?php if($tap->anexo){ ?>
                                     <img width="100%;" height="20%;" src="assets/uploads/projetos/<?php echo $tap->anexo; ?>">
                                     <?php } ?>
                                 </td>
                                 <?php if($documentacao->status != "FINALIZADO"){ ?>
                                 <td style="width:5%;" class="center">
                                    <a style="color: #D37423;" class="btn fa fa-edit"  href="<?= site_url('projetos/edit_tap_sessao/'.$tap->id.'/'.$documentacao->id); ?>"></a>
                                </td>
                                <td style="width:5%;" class="center">
                                   <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('projetos/delete_tap/'.$tap->id.'/'.$documentacao->id); ?>"></a>
                                </td>
                                <?php } ?>
                          </tr>
                         
                    
                                                                            
                        
                    <?php
                        }    
                    ?>    
                     </table>        
                       
                        
                                           

                      

            </div>

        </div>
    </div>
</div>


