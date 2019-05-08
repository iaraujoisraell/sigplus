<?php
$verifica_tabela = $this->owner_model->getTableById($id);
?>

  

<div style="width: 70%;" class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo 'Cadastrar Funções na Lista de Cadastro : '.$verifica_tabela->tabela; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/editarBotoes", $attrib); 
         echo form_hidden('id_tabela', $id);
         echo form_hidden('menu_id', $menu_id);
         echo form_hidden('tabela_id', $tabela_id);
        ?>
        <div class="modal-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                 <tr style=" width: 50%;">
                    <th style="width: 5%;">Check</th>
                    <th style="width: 15%;">Descrição</th>
                    <th style="width: 20%;">Controle</th>
                    <th style="width: 20%;">Função</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $cont = 1;
                     foreach ($botoes as $botao) {
                        $botao_tb = $this->owner_model->getBotaoTabelaById($botao->id,$id);
                       // echo $botao_tb->id;
                        
                    ?> 
                        <tr  >
                            <td style="width: 5%;"><input type="checkbox" name="botao<?php echo $botao->id; ?>"  value="<?php echo $botao->id; ?>" class="flat-red" <?php if($botao_tb->id){ ?> checked <?php } ?> ></td> 
                            <td style="width: 15%;"><?php echo $botao->descricao; ?> </td>
                             
                            <td style="width: 15%;">
                            <?php 
                                  $pst_fk_controle[''] = lang('Selecione');
                                  $controles = $this->owner_model->getAllControle();
                                  foreach ($controles as $controle) {
                                        $pst_fk_controle[$controle->id] = $controle->descricao;
                                    }
                                  ?>
                                 <?php 
                                      echo form_dropdown('controle'.$botao->id, $pst_fk_controle, (isset($_POST['controle'.$botao->id]) ? $_POST['controle'.$botao->id] : $botao_tb->controle), 'id="controle"   data-placeholder="' . lang("select") . ' ' . lang("") . '"    style="width:100%;" ');
                                     ?> 
                            </td>
                            <td style="width: 15%;">
                                <?php 
                                  $pst_fk_funcao[''] = lang('SELECIONE');
                                  $funcoes = $this->owner_model->getAllfuncionalidades();
                                  foreach ($funcoes as $funcao) {
                                        $pst_fk_funcao[$funcao->id] = $funcao->funcao;
                                    }
                                  ?>
                                 <?php 
                                      echo form_dropdown('funcao'.$botao->id, $pst_fk_funcao, (isset($_POST['funcao'.$botao->id]) ? $_POST['funcao'.$botao->id] : $botao_tb->funcao), 'id="funcao"   data-placeholder="' . lang("select") . ' ' . lang("") . '"    style="width:100%;" ');
                                     ?>
                                 </td>
                           
                            
                        </tr>
                    <?php
                    }
                    ?>
               </tbody>
            </table> 
            </div>  
           

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>  
    