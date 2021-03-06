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
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}
window.onload = function(){
	id('telefone').onkeyup = function(){
		mascara( this, mtel );
	}
}
</script>
<div style="background-color: #f2f2f2" class="content-wrapper">
     <section  class="content">
    <div class="col-lg-12">
    <div class="row">    
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Editar Cadastro '; ?>
              <small><?php echo $dados->first_name; ?></small>
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('admin'); ?>"><i class="fa fa-home"></i> Admin</a></li>
            <li class="active">Editar Usuários</li>
          </ol>

        </section>
        <br>
    </div>    
    
        
    <div class="col-lg-12">
            <div class="row">
    <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
      </div>
    </div> 
   
    <div class="box">
        <div class="modal-header">
            
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Usuário'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("admin/editar_usuario", $attrib); 
            echo form_hidden('id_cadastro', '1'); 
            echo form_hidden('usuario_id', $usuario_id); 

            
        ?>
            <div class="row">
                <div class="col-sm-12">              
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("Nome", "nome"); ?>
                            <input name="nome" value="<?php echo $dados->first_name; ?>" required="true" id="nome" class="form-control" type="text" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("E-mail ", "email"); ?>
                            <input name="email" value="<?php echo $dados->email; ?>" id="email" required="true" class="form-control" type="text" >
                        </div>
                     </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("Tipo", "tipo_func"); ?>
                            <?php 
                            if($dados->consultor){
                                $consultor = 1;
                            }else{
                                $consultor = 0;
                            }
                            ?>
                            <br>
                            <input type="radio" <?php if($consultor == 0){ ?> checked="true" <?php } ?> value="0" name="consultor" id="consultor" class="form-control" > Funcionário 
                            <input type="radio" <?php if($consultor == 1){ ?> checked="true" <?php } ?> value="1" name="consultor" id="consultor" class="form-control " > Consultor
                           
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("Cargo", "cargo"); ?>
                            <?php echo form_input('cargo', (isset($_POST['cargo']) ? $_POST['cargo'] : $dados->cargo), 'maxlength="200" class="form-control input-tip"  id="cargo"'); ?>
                        </div>
                    </div>     

                    <div class="col-md-6" >
                        <div class="form-group">
                            <?= lang("Setor", "setores"); ?>
                            
                            <?php
                            $wu_setores[''] = '';
                            $setores = $this->owner_model->getAllSetorByEmpresa();
                            $usuario_setor = $this->atas_model->getUserSetorByUserID($dados->id);
                            $setor_usuario = $usuario_setor->setores_id;
                            foreach ($setores as $setor) {
                                $wu_setores[$setor->id] = $setor->nome;
                            }
                            echo form_dropdown('setor', $wu_setores, (isset($_POST['setor']) ? $_POST['setor'] : "$setor_usuario"), 'id="setores" class="form-control selectpicker  select" data-placeholder="' . lang("Selecione um Setor") . ' "  style="width:100%;" ');
                            ?>



                        </div>
                    </div>    
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("Gênero", "genero"); ?>
                            <?php
                            $pst[''] = '';
                            $pst['male'] = lang('MASCULINO');
                            $pst['female'] = lang('FEMININO');

                            echo form_dropdown('genero', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $dados->gender), 'id="genero"  class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o Gênero") . '" required="required"   style="width:100%;" ');
                            ?>

                        </div>
                    </div>                            

                    <div class="col-md-6"> 
                        <div class="form-group">
                            <label for="data_admissao"  class="control-label">Dt Admissão</label>
                            <input type="date" name="data_admissao" class="form-control" value="<?php echo $dados->data_admissao; ?>" id="data_admissao" placeholder="Data de Admissão">
                        </div>
                    </div>     
                    <!-- DATA NASCIMENTO DO COLABORADOR -->
                    <div class="col-md-6"> 
                        <div class="form-group">
                            <label for="data_nascimento"  class="control-label">Dt Nascim.</label>
                            <input type="date" name="data_nascimento" class="form-control" value="<?php echo $dados->data_aniversario; ?>" id="data_nascimento" placeholder="Data de Nascimento">
                        </div>
                    </div>     
                
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Ramal", "ramal"); ?>
                                <input type="number" name="ramal" class="form-control" maxlength="9" value="<?php echo $dados->ramal; ?>" id="ramal" placeholder="Ramal">
                            </div>
                        </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Celular", "celular1"); ?>
                                <input type="text"  name="celular1" onkeyup="mascara( this, mtel );" maxlength="15" class="form-control"  value="<?php echo $dados->phone; ?>" id="celular1" placeholder="(99) 99999-9999">
                            </div>
                        </div>
                    <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("Tel. Corporativo", "celular_corportativo"); ?>
                                <input type="text" title="Celular Corporativo" onkeyup="mascara( this, mtel );" maxlength="15" name="celular_corportativo" class="form-control"  value="<?php echo $dados->corporativo; ?>" id="celular_corportativo" placeholder="(99) 99999-9999">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div> 
            </div>
        
        
        <hr style=" border-top: 1px dashed #000;
                color: #fff;
                background-color: #fff;" >
                
            <div class="row">
                <div class="modal-header">
                    <div class="col-lg-12">
                    <h4 class="modal-title" id="myModalLabel"><?php echo lang('Acessos do Usuário'); ?></h4>
                    </div>
                </div>
                <div class="col-lg-12">
                    
                    
                    <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Acesso ao Módulo Networking ? ", "networking"); ?>
                                <?php
                               
                                $pi_networking['1'] = lang('SIM');
                                $pi_networking['0'] = lang('NÃO');
                                echo form_dropdown('networking', $pi_networking, (isset($_POST['networking']) ? $_POST['networking'] : $dados->networking), 'id="networking" readonly="true" class="form-control " data-placeholder="' . lang("Pode Acessar") . ' ' . lang("o Módulo Networking ?") . '" required="required"   style="width:100%;" ');
                                ?>
                            </div>
                        </div>
                    
                    <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Acesso ao Módulo Project ? ", "project"); ?>
                                <?php
                               
                                $pi_project['0'] = lang('NÃO');
                                $pi_project['1'] = lang('SIM');

                                echo form_dropdown('project', $pi_project, (isset($_POST['project']) ? $_POST['project'] : $dados->project), 'id="project"  class="form-control " data-placeholder="' . lang("Pode Acessar") . ' ' . lang("o Módulo Project ?") . '" required="required"   style="width:100%;" ');
                                ?>
                            </div>
                        </div>
                    
                    <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Acesso ao Módulo Admin ? ", "admin"); ?>
                                <?php
                               
                                $pi_admin['0'] = lang('NÃO');
                                $pi_admin['1'] = lang('SIM');

                                echo form_dropdown('admin', $pi_admin, (isset($_POST['admin']) ? $_POST['admin'] : $dados->admin), 'id="admin"  class="form-control " data-placeholder="' . lang("Pode Acessar") . ' ' . lang("o Módulo Admin ?") . '" required="required"   style="width:100%;" ');
                                ?>
                            </div>
                        </div>
                    
                    <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Tipo Perfil  ", "administrador"); ?>
                                <?php
                                $pi_tipo['0'] = lang('USUÁRIO');
                                $pi_tipo['1'] = lang('ADMINISTRADOR');

                                echo form_dropdown('administrador', $pi_tipo, (isset($_POST['administrador']) ? $_POST['administrador'] : $dados->administrador), 'id="administrador"  class="form-control " data-placeholder="' . lang("Pode fazer publicações") . ' ' . lang("Inscontitucionais") . '" required="required"   style="width:100%;" ');
                                ?>
                            </div>
                        </div>
                    
                    <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Pode criar projetos?  ", "criar_projetos"); ?>
                                <?php
                               
                                $pi_criar['0'] = lang('NÃO');
                                $pi_criar['1'] = lang('SIM');

                                echo form_dropdown('criar_projetos', $pi_criar, (isset($_POST['criar_projetos']) ? $_POST['criar_projetos'] : $dados->criar_projetos), 'id="criar_projetos"  class="form-control " data-placeholder="' . lang("Pode fazer publicações") . ' ' . lang("Inscontitucionais") . '" required="required"   style="width:100%;" ');
                                ?>
                            </div>
                        </div>
                    
                    <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Pode fazer publicações institucionais ", "publicacoes_institucionais"); ?>
                                <?php
                               
                                $pi['0'] = lang('NÃO');
                                $pi['1'] = lang('SIM');

                                echo form_dropdown('publicacoes_institucionais', $pi, (isset($_POST['publicacoes_institucionais']) ? $_POST['publicacoes_institucionais'] : $dados->publicacoes_institucionais), 'id="publicacoes_institucionais"  class="form-control " data-placeholder="' . lang("Pode fazer publicações") . ' ' . lang("Inscontitucionais") . '" required="required"   style="width:100%;" ');
                                ?>
                            </div>
                        </div>
                    
                    </div>
            </div>    
        
        <div class="modal-footer">
            <div class="col-lg-12">                          
                <center>
                    <div class="col-md-12">
                    <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                <a  class="btn btn-danger"  onclick="history.go(-1)"><?= lang('Voltar') ?></a>
                     </div>
                </center> 
            </div>
        </div>
    
        <?php echo form_close(); ?>
            <!-- /.modal-content -->
        </div>        
   
   
    
    </div>
    </div>     
    </section> 
    
</div>    



