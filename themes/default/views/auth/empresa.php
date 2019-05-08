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
<div class="row">

    <div class="col-sm-2">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div style="max-width:200px; margin: 0 auto;">
                    <?=
                    $user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $empresa->avatar . '" class="avatar">' :
                            '<img alt="" src="' . base_url() . 'assets/images/' . $empresa->avatar . '.png" class="avatar">';
                    ?>
                </div>
                <h4><?= lang('login_email'); ?></h4>

                <p><i class="fa fa-envelope"></i> <?= $user->email; ?></p>
            </div>
        </div>
    </div>

    <div class="col-sm-10">

        <ul id="myTab" class="nav nav-tabs">
            <li class=""><a href="#edit" class="tab-grey"><?= lang('cadastroEmpresa') ?></a></li>
          
           <!-- <li class=""><a href="#cpassword" class="tab-grey"><?= lang('mensalidades') ?></a></li> -->
            <li class=""><a href="#avatar" class="tab-grey"><?= lang('logo_empresa') ?></a></li>
        </ul>

        <div class="tab-content">
            <div id="edit" class="tab-pane fade in">

                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-edit nb"></i><?= lang('atualizar_cadastro'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">

                                <?php
                                $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                                echo form_open('empresa/edit/' . $empresa->id, $attrib);
                                ?>
                                <input type="hidden" name="id_empresa" value="<?php echo $empresa->id; ?>">
                                <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('razao_social', 'razaoSocial'); ?>
                                                <div class="controls">
                                                    <?php echo form_input('razaoSocial', $empresa->razaoSocial, 'class="form-control" id="razao_social" required="required"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('cnpj', 'cnpj'); ?>

                                                <div class="controls">
                                                    <?php echo form_input('cnpj', $empresa->cnpj, 'class="form-control" id="cnpj" required="required"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">

                                                <?php echo lang('ie', 'ie'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="ie" class="form-control" id="ie"
                                                            value="<?= $empresa->inscricaoEstadual ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <?php echo lang('im', 'im'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="im" class="form-control" id="im"
                                                            value="<?= $empresa->inscricaoMunicipal ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <?php echo lang('endereco', 'endereco'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="endereco" class="form-control" id="endereco"
                                                           required="required" value="<?= $empresa->endereco ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <?php echo lang('numero', 'numero'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="numero" class="form-control" id="numero"
                                                           required="required" value="<?= $empresa->numero ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <?php echo lang('complemento', 'complemento'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="complemento" class="form-control" id="complemento"
                                                          value="<?= $empresa->complementoEndereco ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <?php echo lang('bairro', 'bairro'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="bairro" class="form-control" id="bairro"
                                                           required="required" value="<?= $empresa->bairro ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <?php echo lang('cidade', 'cidade'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="cidade" class="form-control" id="cidade"
                                                           required="required" value="<?= $empresa->cidade ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <?php echo lang('uf', 'uf'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="uf" maxlength="2" class="form-control" id="uf"
                                                           required="required" value="<?= $empresa->uf ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <?php echo lang('cep', 'cep'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="cep" maxlength="9" class="form-control" id="cep"
                                                           required="required" onkeypress="mascara(this, mcep);" value="<?= $empresa->cep ?>"/>
                                                </div>
                                            </div>




                                        </div>
                                        <div class="col-md-6 col-md-offset-1">
                                         <div class="panel-heading"><?= lang('opcoesContato') ?></div>

                                            <div class="row">
                                                <div class="panel panel-warning">
                                                    <div class="panel-body" style="padding: 5px;">
                                                        <div class="col-md-12">
                                                            <div class="col-md-12">
                                                                <div class="form-group">

                                                                    <?php echo lang('telefone', 'telefone'); ?>
                                                                    <div class="controls">
                                                                        <input type="tel" name="telefone" class="form-control" id="telefone"
                                                                               required="required" value="<?= $empresa->telefone ?>"/>
                                                                    </div>
                                                                </div>

                                                                <div class="clearfix"></div>
                                                                <div >
                                                                    <div class="form-group">

                                                                        <?php echo lang('celular', 'celular'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="celular" class="form-control" id="celular"
                                                                                    value="<?= $empresa->celular ?>"/>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">

                                                                        <?php echo lang('skype', 'skype'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="skype" class="form-control" id="skype"
                                                                                    value="<?= $empresa->skype ?>"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">

                                                                        <?php echo lang('email', 'email'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="email" class="form-control" id="email"
                                                                                   required="required" value="<?= $empresa->emailResponsavel ?>"/>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="panel-heading"><?= lang('opcoesNfe') ?></div>
                                                                    <div class="form-group">

                                                                        <?php echo lang('ambiente', 'ambiente'); ?>
                                                                        <div class="controls">
                                                                            <select name="ambiente" class="form-control" id="ambiente"
                                                                                   required="required" >
                                                                                <option value="1" <?php if($empresa->ambiente == 1){ ?> selected="true" <?php } ?> >PRODUÇÃO</option>
                                                                                <option value="2" <?php if($empresa->ambiente == 2){ ?> selected="true" <?php } ?> >HOMOLOGAÇÃO</option>
                                                                            </select>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">

                                                                        <?php echo lang('numeroNfe', 'numeroNfe'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="numeroNfe" class="form-control" id="numeroNfe"
                                                                                   required="required" onkeypress="return SomenteNumero(event)" value="<?= $empresa->numeroNfeAtual ?>"/>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">

                                                                        <?php echo lang('Natureza da Operação', 'Natureza da Operação'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="natureza" class="form-control "  id="natureza"
                                                                                    value="<?= $empresa->natureza ?>"/>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">

                                                                        <?php echo lang('Tipo da NFe', 'Tipo da NFe'); ?>
                                                                        <div class="controls">
                                                                            <select name="tipo" class="form-control" id="tipo"
                                                                                   required="required" >
                                                                                <option value="1" <?php if($empresa->tipoNota== 1){ ?> selected="true" <?php } ?> >SAÍDA</option>
                                                                                <option value="0" <?php if($empresa->tipoNota == 0){ ?> selected="true" <?php } ?> >ENTRADA</option>
                                                                            </select>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">

                                                                        <?php echo lang('Finalidade da NFe', 'Finalidade da NFe'); ?>
                                                                        <div class="controls">
                                                                            <select name="finalidade" class="form-control" id="tipo"
                                                                                   required="required" >
                                                                                <option value="1" <?php if($empresa->finalidade== 1){ ?> selected="true" <?php } ?> >NF-e NORMAL</option>
                                                                                <option value="2" <?php if($empresa->finalidade== 2){ ?> selected="true" <?php } ?> >NF-e COMPLEMENTAR</option>
										<option value="3" <?php if($empresa->finalidade== 3){ ?> selected="true" <?php } ?> > NF-e de AJUSTE </option>

                                                                                <option value="4" <?php if($empresa->finalidade== 4){ ?> selected="true" <?php } ?> > DEVOLUÇÃO / RETORNO</option>
                                                                            </select>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php //}  ?>
                                                                    <br>
                                                                    <div class="panel-heading"><?= lang('infoEmpresa') ?></div>
                                                                    <div class="form-group">

                                                                        <?php echo lang('dataCadastro', 'dataCadastro'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="dataCadastro" class="form-control input-tip datetime" id="dataCadastro"
                                                                                   readonly="true" value="<?= $this->sma->hrld($empresa->data_cadastro) ?>"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <?php echo lang('diaVencimento', 'diaVencimento'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="diaVencimento" class="form-control "  id="diaVencimento"
                                                                                   readonly="true" value="<?= $empresa->diaPagamento ?>"/>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <?php echo lang('valorMensalidade', 'valorMensalidade'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="valorMensalidade" class="form-control "  id="valorMensalidade"
                                                                                   readonly="true" value="R$ <?= $empresa->valorMensalidade ?>"/>
                                                                        </div>
                                                                    </div>
                                                                     <div class="form-group">
                                                                        <?php echo lang('status', 'status'); ?>
                                                                        <div class="controls">
                                                                            <input type="tel" name="status" class="form-control" id="status"
                                                                                   readonly="true" value="<?php if($empresa->status == 1){ echo 'ATIVO'; } ?>"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php //} ?>
                                            <?php echo form_hidden('id', $id); ?>
                                            <?php echo form_hidden($csrf); ?>
                                        </div>
                                    </div>
                                </div>
                                <p><?php echo form_submit('update', lang('update'), 'class="btn btn-primary"'); ?></p>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
            <div id="cpassword" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-key nb"></i><?= lang('change_password'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo form_open("auth/change_password", 'id="change-password-form"'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('old_password', 'curr_password'); ?> <br/>
                                                <?php echo form_password('old_password', '', 'class="form-control" id="curr_password" required="required"'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label
                                                    for="new_password"><?php echo sprintf(lang('new_password'), $min_password_length); ?></label>
                                                <br/>
                                                <?php echo form_password('new_password', '', 'class="form-control" id="new_password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-regexp-message="' . lang('pasword_hint') . '"'); ?>
                                                <span class="help-block"><?= lang('pasword_hint') ?></span>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('confirm_password', 'new_password_confirm'); ?> <br/>
                                                <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" required="required" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>

                                            </div>
                                            <?php echo form_input($user_id); ?>
                                            <p><?php echo form_submit('change_password', lang('change_password'), 'class="btn btn-primary"'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="avatar" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('change_avatar'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-5">
                                    <div style="position: relative;">
                                        <?php if ($user->avatar) { ?>
                                            <img alt=""
                                                 src="<?= base_url() ?>assets/uploads/avatars/<?= $empresa->avatar ?>"
                                                 class="profile-image img-thumbnail">
                                            <a href="#" class="btn btn-danger btn-xs po"
                                               style="position: absolute; top: 0;" title="<?= lang('delete_avatar') ?>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-block btn-danger po-delete' href='<?= site_url('auth/delete_avatar/' . $id . '/' . $user->avatar) ?>'> <?= lang('i_m_sure') ?></a> <button class='btn btn-block po-close'> <?= lang('no') ?></button>"
                                               data-html="true" rel="popover"><i class="fa fa-trash-o"></i></a><br>
                                            <br><?php } ?>
                                    </div>
                                    <?php echo form_open_multipart("empresa/update_avatar_empresa"); ?>
                                    <input type="hidden" name="id" value="<?php echo $empresa->id; ?>">
                                    <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                    <div class="form-group">
                                        <?= lang("change_avatar", "change_avatar"); ?>
                                        <input type="file" data-browse-label="<?= lang('browse'); ?>" name="avatar" id="product_image" required="required"
                                               data-show-upload="false" data-show-preview="false" accept="image/*"
                                               class="form-control file"/>
                                    </div>
                                    <div class="form-group">
                                        <?php echo form_hidden('id', $empresa->id); ?>
                                        <?php echo form_hidden($csrf); ?>
                                        <?php echo form_submit('update_avatar', lang('update_avatar'), 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#change-password-form').bootstrapValidator({
                message: 'Please enter/select a value',
                submitButtons: 'input[type="submit"]'
            });
        });
    </script>
    <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
        <script type="text/javascript" charset="utf-8">
            $(document).ready(function () {
                $('#group').change(function (event) {
                    var group = $(this).val();
                    if (group == 1 || group == 2) {
                        $('.no').slideUp();
                    } else {
                        $('.no').slideDown();
                    }
                });
                var group = <?= $user->group_id ?>;
                if (group == 1 || group == 2) {
                    $('.no').slideUp();
                } else {
                    $('.no').slideDown();
                }
            });
        </script>
    <?php } ?>
