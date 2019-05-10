<?php
$verifica_tabela = $this->owner_model->getTableById($id);
//echo 'aquuii '.$verifica_tabela->fk;
?>
<div style="width: 60%;" class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo 'Editar Campos da Tabela : '.$verifica_tabela->tabela; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/editarCamposFK", $attrib); 
         echo form_hidden('id_tabela', $id);
         echo form_hidden('menu_id', $menu_id);
         echo form_hidden('tabela_id', $tabela_id);
         
        ?>
        <div class="modal-body">
            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                 <tr style=" width: 60%;">
                     <th style="width: 5%;"><small>Id</small></th>
                    <th style="width: 15%;"><small>Campo Sig</small></th>
                    <th style="width: 20%;"><small>FK</small></th>
                    <th style="width: 20%;"><small>Tipo Campo</small></th>
                    
                </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $cont = 1;
                    $cont_campo = 0;
                    foreach ($campos as $campo) {
                        $lista = $campo->lista;
                        if($lista){
                            $lista = 1;
                        }else{
                            $lista = 0;
                        }
                       // echo $campo->fk;
                        echo form_hidden('tabela_fk'.$campo->id, $campo->fk);
                        
                    ?> 
                        <tr  >
                            <td style="width: 5%;"><small><?php echo $cont++; ?> </small></td> 
                            <td style="width: 15%;"><?php echo $campo->nome_campo; ?></small></td>
                         
                         
                            <td style="width: 20%; ">
                                <?php 
                                  $pst_fk[''] = lang('NÃO');
                                  $tabelas_sig = $this->owner_model->getAllTables();
                                  foreach ($tabelas_sig as $tabela) {
                                        $pst_fk[$tabela->id] = $tabela->tabela;
                                    }
                                  ?>
                                 <?php 
                                      echo form_dropdown('fk'.$campo->id, $pst_fk, (isset($_POST['fk'.$campo->id]) ? $_POST['fk'.$campo->id] : $campo->fk), 'id="tipo"   data-placeholder="' . lang("select") . ' ' . lang("o Relacionamento") . '"    style="width:100%;" ');
                                     ?> 
                             
                            </td>
                               <td style="width: 20%; ">
                                <?php 
                                
                                $campos_fk = $this->owner_model->getAllCamposFK($id);
                                foreach ($campos_fk as $campo_fk) {
                                    $wu_fk[$campo_fk->campo_id] = $campo_fk->campo_id;
                                }
                                  
                                  $tabelas_campos = $this->owner_model->getAllCamposTables($campo->fk);
                                 // print_r($tabelas_campos);
                                  $pst_fk_campo = "";
                                  foreach ($tabelas_campos as $tabela_campo) {
                                        $pst_fk_campo[$tabela_campo->id] = $tabela_campo->campo;
                                    }
                                  ?>
                                 <?php 
                                      echo form_dropdown('fk_campos'.$campo->id.'[]', $pst_fk_campo, (isset($_POST['fk'.$campo->id]) ? $_POST['fk'.$campo->id] : $wu_fk), 'id="tipo" multiple  data-placeholder="' . lang("select") . ' ' . lang("o Relacionamento") . '"    style="width:100%;" ');
                                     ?> 
                             
                            </td>
                           
                        </tr>
                    <?php
                    $cont_campo++;
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
