<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla==8 || tecla==0) return true;
	else  return false;
    }
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
<?php
$ataAtual = $this->atas_model->getAtaByID($id);
                                                 $statusAta = $ataAtual->status;
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i>
            <?php if($statusAta == 1){ ?>
                <?= lang('Esta ATA não pode ser Editada, pois se encontra FINALIZADA.'); ?>
            <?php }else{ ?>
             <?= lang('DISCUSSÃO DA ATA'); ?>
            <?php } ?>
        </h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("atas/edit_discussao", $attrib);
                 echo form_hidden('id', $id);
                ?>
                <div class="row">
                    
                
                        
                            <div  class="col-md-12">
                                <div  class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Anotações : ", "slpauta"); ?>*
                                         <?php if($statusAta == 1){ ?>
                                        <?php echo $ata->discussao; ?>
                                         <?php }else{ ?>
                                        <textarea name="discussao" rows="50" class="form-control" id="slpauta" equired="required"  >
                                           <?php echo $ata->discussao; ?>  
                                        </textarea>
                                       
                                       <?php } ?>
                                    </div>
                                </div>
                                
                            </div>
                    
                           
                    <div class="col-lg-12">

                        <div class="clearfix"></div>


                        <div class="col-md-12">
                            <?php if ($statusAta == 1) { ?>
                                <div
                                    class="fprom-group">

                                    <a  class="btn btn-danger" href="<?= site_url('atas/plano_acao/'.$id); ?>"><?= lang('Fechar') ?></a>
                                </div>
                            <?php } else { ?>
                                <div
                                    class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                    <a  class="btn btn-danger" href="<?= site_url('atas/plano_acao/'.$id); ?>"><?= lang('Sair') ?></a>
                                </div>     <?php } ?> 
                        </div>
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>



