<?php
$verifica_tabela = $this->owner_model->getTableById($id);
?>
<div style="width: 100%;" class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo 'Editar Campos da Tabela : '.$verifica_tabela->tabela; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/editarCampos", $attrib); 
         echo form_hidden('id_tabela', $id);
         echo form_hidden('menu_id', $menu_id);
         echo form_hidden('tabela_id', $tabela_id); 
        ?>
        <div class="modal-body">
            <div class="table-responsive">
                <table  class="table table-bordered table-striped">
                <thead>
                 <tr style=" width: 110%;">
                     <th style="width: 3%;"><small>Id</small></th>
                    <th style="width: 15%;"><small>Campo</small></th>
                    <th style="width: 14%;"><small>Campo Sig</small></th>
                    <th style="width: 13%;"><small>Estrutura</small></th>
                    <th style="width: 13%;"><small>Tipo Campo</small></th>
                    <th style="width: 13%;"><small>FK</small></th>
                    <th style="width: 5%;"><small>Tamanho</small></th>
                    <th style="width: 8%;"><small>Obrigatorio</small></th>
                    <th style="width: 8%;"><small>Cadastro</small></th>
                    <th style="width: 8%;"><small>Lista</small></th>
                   <th style="width: 13%;"><small>Sessão</small></th>
                    <th style="width: 5%;"><small>Width</small></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $cont = 1;
                    foreach ($campos as $campo) {
                        $lista = $campo->lista;
                        if($lista){
                            $lista = 1;
                        }else{
                            $lista = 0;
                        }
                    ?> 
                        <tr  >
                            <td style="width: 3%;"><small><?php echo $cont++; ?> </small></td> 
                            <td style="width: 15%;"><small><?php echo $campo->campo.' ('.$campo->tipo.')'; ?> </small></td>
                            <td style="width: 14%;"><small><input type="text" value="<?php echo $campo->nome_campo; ?>" name="campo_sig<?php echo $campo->id; ?>"> </small></td>
                            <td style="width: 13%; ">
                                <?php 
                                  $pst_tipo['input'] = lang('INPUT');
                                  $pst_tipo['textarea'] = lang('TEXTAREA');
                                  $pst_tipo['dropdown'] = lang('DROPDOWN');
                                  $pst_tipo['hidden'] = lang('HIDDEN')
                                  ?>
                                 <?php 
                                      echo form_dropdown('tipo_campo'.$campo->id, $pst_tipo, (isset($_POST['tipo_campo'.$campo->id]) ? $_POST['tipo_campo'.$campo->id] : $campo->tipo_campo), 'id="tipo"   data-placeholder="' . lang("select") . ' ' . lang("o tipo de Estrutura") . '"    style="width:100%;" ');
                                  ?> 
                                </small>
                            </td>
                            <td style="width: 13%; ">
                                <?php 
                                  $pst['text'] = lang('TEXT');  
                                  $pst['number'] = lang('NUMBER');
                                  $pst['date'] = lang('DATE');
                                  $pst['datetime'] = lang('DATETIME');
                                  $pst['checkbox'] = lang('CHECKBOX');
                                  $pst['email'] = lang('EMAIL');
                                  $pst['color'] = lang('COLOR');
                                 // $pst['hidden'] = lang('HIDDEN');
                                  
                                  ?>
                                 <?php 
                                      echo form_dropdown('tipo_texto'.$campo->id, $pst, (isset($_POST['tipo_texto'.$campo->id]) ? $_POST['tipo_texto'.$campo->id] : $campo->tipo_texto), 'id="tipo"   data-placeholder="' . lang("select") . ' ' . lang("o tipo de Campo") . '"    style="width:100%;" ');
                                     ?> 
                             
                            </td>
                            <td style="width: 13%; ">
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
                            <td style="width: 5%; "><small><input style="width: 50px;" type="number" value="<?php echo $campo->tamanho; ?>" name="tamanho<?php echo $campo->id; ?>"></small></td>
                            <td style="width: 8%; ">
                                <?php 
                                  $pst_obrigatorio['1'] = lang('SIM');
                                  $pst_obrigatorio['0'] = lang('NÃO');
                                  echo form_dropdown('obrigatorio'.$campo->id, $pst_obrigatorio, (isset($_POST['obrigatorio'.$campo->id]) ? $_POST['obrigatorio'.$campo->id] : $campo->obrigatorio), 'id="tipo"  data-placeholder="' . lang("select") . ' ' . lang("o tipo de Campo") . '"    style="width:100%;" ');
                               ?> 
                            <td style="width: 8%; ">
                                <?php 
                                  $pst_cadastro['1'] = lang('SIM');
                                  $pst_cadastro['0'] = lang('NÃO');
                                  echo form_dropdown('cadastro'.$campo->id, $pst_cadastro, (isset($_POST['cadastro'.$campo->id]) ? $_POST['cadastro'.$campo->id] : $campo->cadastro), 'id="tipo"   data-placeholder="' . lang("select") . ' ' . lang("o tipo de Campo") . '"    style="width:100%;" ');
                               ?>
                              
                            </td>
                            <td style="width: 8%; ">
                                <?php 
                                  $pst_main['1'] = lang('SIM');
                                  $pst_main['0'] = lang('NÃO');
                                  echo form_dropdown('main'.$campo->id, $pst_main, (isset($_POST['main'.$campo->id]) ? $_POST['main'.$campo->id] : $campo->lista), 'id="tipo"  data-placeholder="' . lang("select") . ' ' . lang("o tipo de Campo") . '"    style="width:100%;" ');
                               ?>
                                </td>
                                
                                <td style="width: 13%; ">
                                <?php 
                                  $pst_sessao[''] = lang('NÃO');
                                  $pst_sessao['empresa'] = lang('Empresa');
                                  $pst_sessao['usuario'] = lang('Usuário');
                                  $pst_sessao['projeto'] = lang('Projeto');
                                  ?>
                                 <?php 
                                      echo form_dropdown('sessao'.$campo->id, $pst_sessao, (isset($_POST['sessao'.$campo->id]) ? $_POST['sessao'.$campo->id] : $campo->sessao), 'id="tipo"   data-placeholder="' . lang("select") . ' ' . lang("o tipo de Estrutura") . '"    style="width:100%;" ');
                                  ?> 
                                </small>
                            </td>
                                
                                <td style="width: 5%; "><small><input style="width: 50px;" type="number" value="<?php echo $campo->width; ?>" name="width<?php echo $campo->id; ?>"></small></td>    
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
