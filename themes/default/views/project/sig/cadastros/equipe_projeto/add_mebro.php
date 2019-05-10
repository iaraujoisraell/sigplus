<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Criar Equipe para o Projeto'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("cadastros/add_equipe_mebros", $attrib);
                echo form_hidden('id', $id);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                    <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("Nome da Equipe", "slprojeto"); ?>
                                <?php echo form_input('nome', (isset($_POST['nome']) ? $_POST['nome'] : $equipe->nome), 'maxlength="200" class="form-control input-tip" required="required"  id="slprojeto"'); ?>
                            </div>
                        <div class="form-group">
                                <?= lang("Usuário", "slresponsavel"); ?>
                                <?php
                                    //$wu4[''] = '';
                                    foreach ($users as $user) {
                                        $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                    }
                                  //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                    echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $participantes_usuarios), 'id="slResponsavel" required="required"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Membro(s)") . ' "   style="width:100%;"  multiple ');

                                    ?>      
                        </div>
                        
                        
                            <div class="form-group">
                                <?= lang("Papel", "papel"); ?>

                                <?php
                                    $wu5[''] = 'Selecione';
                                    foreach ($papeis as $papel) {
                                        $wu5[$papel->id] = $papel->papel;
                                    }
                                    echo form_dropdown('papel', $wu5, (isset($_POST['papel']) ? $_POST['papel'] : " "), 'id="papel" required="required"  class="form-control  select" data-placeholder="' . lang("Selecione o Papel/ Função") . ' "   style="width:100%;"   ');

                                    ?>      
                            </div>
                        
                        
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                      
                        <div class="clearfix"></div>
                        
                      
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("Salvar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <a class=" btn btn-danger" href="<?= site_url('cadastros/equipe'); ?>">
                                            <span class="text"> <?= lang('SAIR'); ?></span>
                                        </a></div>
                        </div>
                    </div>
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>
<?php $membros = $this->atas_model->getMebrosEquipeByEquipe($id); ?>
<table id="example-table" class="table table-striped table-bordered table-hover table-green">
    <tr>
        <td>Nome</td>
        <td>Papel/ Função</td>
        <td>Excluir</td>
        
    </tr>    
    <?php
    foreach ($membros as $membro) {
        ?>
    <tr>
        <td style="text-align:left;">
            <?php echo $membro->name.' '.$membro->last.' - '.$membro->setor; ?>
        </td> 
         <td style="text-align:left;">
            <?php echo $membro->papel; ?>
        </td> 
        <td class="center">
         <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('cadastros/delete_membro_equipe/'.$membro->id); ?>"></a>
                                                

    </td>  
    </tr>
        <?php
    }
    ?>
    </table>   

<br>
<br>
<br>
<br>