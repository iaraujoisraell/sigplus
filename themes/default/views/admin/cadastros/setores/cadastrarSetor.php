
<div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastrar novo Setor'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("admin/cadastrarSetor", $attrib); 
         echo form_hidden('id_cadastro', '1');
         echo form_hidden('tabela_id', $tabela_id); 
         echo form_hidden('menu_id', $menu_id); 
        
           $id = $setor->id;
           $descricao = $setor->nome;
           $pai = $id;// $setor->pai;
           $raiz = $setor->raiz;
           
           if(($pai == 0)||($pai == '')){
               $pai = $id;
           }
          
           if(($raiz == 0)||($raiz == '')){
               $raiz = $id;
           }
           
        //    echo $pai.'<br>';
         //  echo $raiz;
           
           echo form_hidden('pai', $pai);
           echo form_hidden('raiz', $raiz);
          
          
        ?>
        <div class="modal-body">
           

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                        <?= lang("Setor pai", "descricao"); ?>
                        <?php echo form_input('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $descricao), 'maxlength="200" class="form-control input-tip" disabled required="required" id="descricao"'); ?>
                    </div>
                    <div class="form-group company">
                        <?= lang("Sub-Setor", "subsetor"); ?>
                        <?php echo form_input('subsetor', $icon, 'class="form-control tip" maxlength="150" id="subsetor" '); ?>
                    </div>
                    
                    
                </div>
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>  
