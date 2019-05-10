
<div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de '.$titulo.' | Deseja deletar este cadastro?'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("owner/deletarCadastro", $attrib); 
            echo form_hidden('id_cadastro', $cadastro_id); 
            echo form_hidden('tabela_id', $tabela_id); 
            echo form_hidden('tabela', $tabela); 
            echo form_hidden('menu_id', $menu_id); 
            echo form_hidden('cadastrosHabilitados', $cadastrosHabilitados);
        ?>
        <div class="modal-body">
           
            
            <div class="row">
                <div class="col-md-12">
                    <?php
                        foreach ($cadastrosHabilitados as $habilitado) {
                            $campo_id = $habilitado->id;
                            $campo_banco = $habilitado->campo;
                            $nome_campo = $habilitado->nome_campo;
                            $tipo_campo = $habilitado->tipo_campo;
                            $tipo_texto = $habilitado->tipo_texto;
                            $tamanho = $habilitado->tamanho;
                            $obrigatorio = $habilitado->obrigatorio;
                            $tabela_fk = $habilitado->fk;
                            //echo $campo_id;
                            if($obrigatorio == 1){
                                $obrigatorio_texto = 'true';
                            }else{
                                $obrigatorio_texto = 'false';
                            }
                            
                            if($tipo_texto == 'text'){
                                $input = 'input-tip';
                            }else if($tipo_texto == 'date'){
                                $input = 'date';
                            }else if($tipo_texto == 'datetime'){
                                $input = 'datetime';
                            }    
                            //class="form-control input-tip datetime"
                            // echo form_dropdown('reacao', $wu5, (isset($_POST['reacao']) ? $_POST['reacao'] : $ata->avaliacao_reacao), 'id="reacao"   class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" ');
                            //    <?php echo form_textarea($campo_banco, (isset($_POST['texto_convocacao']) ? $_POST['texto_convocacao'] : ""), 'class="form-control" id="texto_cancelamento"   style="margin-top: 10px; height: 150px;"'); 
                            //   echo form_dropdown('tipo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $ata->tipo), 'id="tipo" disabled class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Ata") . '" required="required"   style="width:100%;" ');
                            
                            
                            
                            
                        ?> 
                    <div class="form-group company">
                        <?php  if($tipo_campo != 'hidden'){  ?>
                        <?= lang("$nome_campo", "$campo_banco"); ?>
                        <?php } ?>
                        
                        <?php if($tipo_campo == 'input'){ ?>
                        <?php echo form_input($campo_banco, $dados_tabela->$campo_banco, 'class="form-control '.$input.'" maxlength="'.$tamanho.'" disabled id="'.$campo_banco.'" required="'.$obrigatorio_texto.'" '); ?>
                        <?php }else if($tipo_campo == 'textarea'){ ?>
                              <?php echo form_textarea($campo_banco, (isset($_POST['texto_convocacao']) ? $_POST['texto_convocacao'] : "$dados_tabela->$campo_banco"), 'class="form-control" disabled id="'.$campo_banco.'"   style="margin-top: 10px; height: 150px;"'); ?>
                        <?php }else if($tipo_campo == 'dropdown'){ ?>
                            <?php 
                              
                              $tabelas_fk = $this->owner_model->getTableById($tabela_fk);//tabela relacionamento
                              $nome_tabela = $tabelas_fk->tabela;//nome da tabela
                              
                              
                              $colunas_tabela_fk = $this->owner_model->getAllCamposDropdownFK($tabela_id,$tabela_fk); //colunas que sera(o) visualizadas
                              
                             
                              $dados_tabelas_fk = $this->owner_model->getDadosTables($nome_tabela);// retorna os dados da tabela escolhida
                              $pst_fk_campo['0'] = "Selecione";
                              foreach ($dados_tabelas_fk as $tabela_campo) {
                                  $cont_col = 1;
                                  foreach ($colunas_tabela_fk as $coluna) {
                                  $id_coluna = $coluna->campo_id;
                                  $campo_id_fk = $this->owner_model->getCampoById($id_coluna);
                                  $descricao_campo = $campo_id_fk->campo;
                                  
                                  if($cont_col <= 1){
                                      $pst_fk_campo[$tabela_campo->id] = $tabela_campo->$descricao_campo;
                                  }else if($cont_col > 1){
                                      $pst_fk_campo[$tabela_campo->id] .= ' - '.$tabela_campo->$descricao_campo; 
                                      
                                  }
                                  
                                   //echo $cont_col;
                                 
                                  $cont_col++; 
                                 }
                                    
                                }
                              echo form_dropdown($campo_banco, $pst_fk_campo, (isset($dados_tabela->$campo_banco) ? $dados_tabela->$campo_banco : "$dados_tabela->$campo_banco"), 'id="'.$campo_banco.'" disabled  class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" '); ?>
                        
                        <?php } ?>
                    </div>
                    
                    <?php
                        }
                        ?>
                    
                    
                    
                    
                </div>
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Deletar'), 'class="btn btn-danger"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>  
