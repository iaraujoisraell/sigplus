<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Editar Usuário'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('editar_user'); ?></p>

                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open("auth/edit_user", $attrib);
                 echo form_hidden('id', $user->id);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php echo lang('first_name', 'first_name'); ?>
                                <div class="controls">
                                    <?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('last_name', 'last_name'); ?>
                                <div class="controls">
                                    <?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name" '); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= lang('gender', 'gender'); ?>
                                <?php
                                $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>

                           
                            
                            <div class="form-group">
                                <?php echo lang('Setor', 'company'); ?>
                                <?php
                                    foreach ($setores as $setor) {
                                        $a_setor[$setor['id']] = $setor['nome'];
                                    }
                             
                                    foreach ($setores_usuario as $setor_usuario) {
                                        
                                        $s_setor_usuario[$setor_usuario->setor] = $setor_usuario->setor;           
                                    } 
                                
                                ?>
                                <?php echo form_dropdown('setor[]', $a_setor, (isset($_POST['setor']) ? $_POST['setor'] : $s_setor_usuario), 'id="group"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Click e selecione o(s) Setor(es)  do usuario") . ' "  style="width:100%;" ');?>
                         
                            </div>
                            
                            <div class="form-group">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', $user->phone, 'class="form-control" id="phone" '); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo lang('email', 'email'); ?>
                                <div class="controls">
                                    <input type="email" id="email" name="email" value="<?php echo $user->email; ?>" class="form-control"
                                           required="required"/>
                                    <?php /* echo form_input('email', '', 'class="form-control" id="email" required="required"'); */ ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5 col-md-offset-1">
                            <div class="form-group">
                                <?php echo lang('username', 'username'); ?>
                                <div class="controls">
                                    <input type="text" id="username" name="username" value="<?php echo $user->username; ?>" class="form-control"
                                           required="required" pattern=".{4,20}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo lang('Mudar Senha ( se não for alterar, deixe em branco)', 'password'); ?>
                                <div class="controls">
                                    <?php echo form_password('password', '', 'class="form-control" id="confirm_password" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= lang('status', 'status'); ?>
                                <?php
                                $opt = array(1 => lang('active'), 0 => lang('inactive'));
                                echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : $user->active), 'id="status" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>
                            <div class="form-group">
                                <?= lang("Perfil", "group"); ?>
                                <?php
                                foreach ($groups as $group) {
                                         $gp[$group['id']] = $group['name'];
                                  
                                }
                              
                                foreach ($perfil_usuario as $user_perfil) {
                                    $wp[$user_perfil['grupo_id']] = $user_perfil['grupo_id'];                
                                   
                                   
                                } 
                              
                                 ?>
                                 <?php echo form_dropdown('group[]', $gp, (isset($_POST['group']) ? $_POST['group'] : $wp), 'id="group"  multiple class="form-control selectpicker  select" data-placeholder="' . lang("Click e selecione o(s) Perfil(is) do usuario") . ' "  style="width:100%;" ');?>
                                              
                                
                            </div>
                            
                           

                            <div class="clearfix"></div>
                            

                            

                        </div>
                    </div>
                </div>

                <p><?php echo form_submit('add_user', lang('edit_user'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('.no').slideUp();
        $('#group').change(function (event) {
            var group = $(this).val();
            if (group == 1 || group == 2) {
                $('.no').slideUp();
            } else {
                $('.no').slideDown();
            }
        });
    });
</script>
