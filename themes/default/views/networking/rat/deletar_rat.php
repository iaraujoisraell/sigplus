<?php
    $projeto = $rat->projeto;
    $id_rat = $rat->id_rat;
    $idplanos = $rat->idplanos;
    $data_rat = $rat->data_rat;
    $inicio = $rat->hora_inicio;
    $termino = $rat->hora_fim;
    $data_prazo = $rat->data_termino;
    $tempo = $rat->tempo;

    $valor = $rat->valor;
    $valor = str_replace('.', ',', $valor);
    
    $rat_descricao = $rat->descricao_rat;

    $idacao = $rat->idplanos;
    $acao_desc = $rat->descricao_acao;

    $status = $rat->status_acao;

?>
  
  <div  class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <a type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </a>
           
            <h3 class="modal-title" id="myModalLabel"><?php echo ' Deletar Rat'; ?></h3>
            <br>
            <small><?php echo 'Projeto : '.strip_tags($projeto); ?></small> <br>
            <small><?php echo 'Ação : ('.$rat->sequencial.') '.strip_tags($acao_desc); ?></small>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("welcome/deletar_rat", $attrib); 
            echo form_hidden('deletar_rat', '1');
            echo form_hidden('rat_id', $id_rat);
            
            
        ?>
        <div class="modal-body">
           
            
            <div class="row">
                <div class="col-md-12">
                    
                    <div class="form-group company">
                         <?= lang("Descrição ", "detalhes"); ?>
                        <textarea  name='detalhes' id="detalhes" readonly="true" maxlength="250" required="true" class="form-control">
                            <?php echo strip_tags($rat_descricao); ?>
                        </textarea>
                        
                    </div>
                    <div class="form-group">
                          <label>Data da Rat</label>
                          <input type='date' name='data_registro' readonly="true" id="data_registro" value="<?php echo $data_rat; ?>" required="true" class="form-control">
                     </div>
                    <div class="form-group">
                          <label>Hora Início</label>
                          <input type='time' name='hora_inicio' readonly="true" id="hora_inicio" value="<?php echo $inicio; ?>" required="true" class="form-control">
                    </div>
                    <div class="form-group">
                          <label>Hora Fim</label>
                          <input type='time' name='hora_termino' readonly="true" id="hora_termino" value="<?php echo $termino; ?>" required="true" class="form-control">
                    </div>
                    <div class="form-group company">
                         <?= lang("Valor ", "valor"); ?>
                              <?php echo form_input('valor', "$valor", 'class="form-control input" onkeypress="mascara(this, mvalor);" placeholder="R$" readonly="true"  maxlength="15" id="valor"  '); ?>
                    </div>
                    
                </div>
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Apagar'), 'class="btn btn-danger"'); ?>
            <a class="btn btn-primary " data-dismiss="modal" > Fechar</a>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
     </div>       

  
