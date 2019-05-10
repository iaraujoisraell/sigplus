<?php 
    $usuario = $this->session->userdata('user_id');
    $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
?>

    <div class="box">
        
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-calendar-o"></i><?=lang('Gestão de documentos do Projeto');?>
        </h2>
        
        <div id="eventos_index" class="box-icon">
           <div class="fprom-group">
            <a class="btn btn-primary"  href="<?=site_url('projetos/add_documento')?>"> 
             <i class="fa fa-plus-circle"></i>   <?=lang('Cadastrar novo documento')?>
            </a>
          </div>
        </div>
    </div>
    
    <?php if ($Owner || $GP['bulk_actions']) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?=form_close()?>
<?php }



?>
        

        
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                        <div class="portlet portlet-default">
                         <div style="text-align: right" class="col-lg-12">
                  </div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("Projetos/Eventos_index_form", $attrib);

                            ?>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="sample_table"  class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                         <tr>
                                       
                                                <th>ID</th>
                                                <th>CÓD.DOC.</th>
                                                <th>DOCUMENTO</th>
                                                <th>TIPO</th>
                                                <th>SETOR</th>                                           
                                                <th>REVISÃO</th>
                                                <th>DATA REVISÃO</th>
                                                <th>DATA VALIDADE</th>
                                                <th>ANEXO DOC</th>
                                                <th>QUEM PODE VER</th>
                                                <th>ELABORADO POR</th>
                                                <th>STATUS</th>     
                                                <th>EDITAR</th>
                                                
                                                
                                                
                                               
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($eventos as $evento) {
                                                       
                                                    
                                                 $res_tec = $this->site->geUserByID($evento->user);
                                                 
                                                // $res_assinar = $this->site->geUserByID($evento->quem_assinou);
                                                 
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?></td>  
                                                        
                                                        <td><?php echo $evento->codigo_documento; ?></td>
                                                        <td><?php echo $evento->nome_documento; ?></td>
                                                        <td><?php echo $evento->grupo_documento; ?></td>
                                                        <td class="center">
                                                            <?php
                                                              $documento_setor  = $this->projetos_model->getAllDocumentoSetor($evento->id);
                                                              
                                                                foreach ($documento_setor as $documento_setor) {
                                                                    
                                                                    $id_setor = $documento_setor->setor;
                                                                    
                                                                    if($id_setor == 'TODOS S/A'){
                                                                        $setor = 'TODOS S/A';
                                                                    }else if($id_setor == 'TODOS OPERADORA'){
                                                                        $setor = 'TODOS OPERADORA';
                                                                        
                                                                    }else{
                                                                    
                                                                   // $setor_user = $this->atas_model->getUserSetorBYid($id_usu_set_doc);
                                                                    $setor_pesq = $this->atas_model->getSetorByID($id_setor);
                                                                    $setor = $setor_pesq->nome;
                                                                    }
                                                                ?>
                                                            
                                                            <table class="table table-striped table-bordered table-hover table-green" >
                                                                <tr><td><font style="font-size: 12px;"><?php  echo $setor; ?></font></td></tr>
                                                            </table>
                                                                
                                                                <?php  
                                                                }
                                        
                                                            ?>
                                                        </td>   
                                                        <td>
                                                           <?php echo $evento->revisao; ?>
                                                        </td>
                                                        <td><?php echo $this->sma->hrld($evento->data_revisao); ?></td>
                                                        <td><?php echo $this->sma->hrld($evento->data_validade); ?></td>
                                                        
                                                       <th>
                                                           <?php if($evento->anexo){ ?>
                                                           <a target='_blank' href="<?= site_url('../assets/uploads/projetos/' . $evento->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                                <i class="fa fa-chain"></i>
                                                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                                                           </a>
                                                           <?php } ?>
                                                       </th> 
                                                        <td class="center">
                                                            <?php
                                                              $usuarios_setor  = $this->projetos_model->getAllUserSetor($evento->id);
                                                                foreach ($usuarios_setor as $usuario_setor) {
                                                                    
                                                                    $id_usu_set_doc = $usuario_setor->usuario_setor;
                                                                    
                                                                    if($id_usu_set_doc == 0){
                                                                        $nome = 'PÚBLICO';
                                                                    }else{
                                                                    
                                                                    $setor_user = $this->atas_model->getUserSetorBYid($id_usu_set_doc);
                                                                    $setor = $this->atas_model->getSetorByID($setor_user->setor);
                                                                    
                                                                    $usu_setor = $this->site->geUserByID($setor_user->usuario);
                                                                    $nome = $usu_setor->first_name.' '.$usu_setor->last_name.' - '.$setor->nome;
                                                                    }
                                                                ?>
                                                            
                                                            <table class="table table-striped table-bordered table-hover table-green" >
                                                                <tr><td><font style="font-size: 12px;"><?php  echo $nome; ?></font></td></tr>
                                                            </table>
                                                                
                                                                <?php  
                                                                }
                                        
                                                            ?>
                                                        </td>    
                                                        <td>
                                                            <p><?php echo $res_tec->first_name.' '.$res_tec->last_name; ?></p>
                                                            <p><?php echo $this->sma->hrld($evento->date_criaca); ?></p>
                                                        </td>
                                                        <td <?php if ($evento->status == "ATUALIZADO"){ ?> style='background-color: green; color: #ffffff;'   <?php }else{ ?> style='background-color: orange;'   <?php } ?>>
                                                            <p><?php echo $evento->status; ?></p>
                                                        </td>
                                                          <td class="center">
                                                              <a style="color: #FFFFFF;" class="btn orange" href="<?= site_url('projetos/edit_documento/'.$evento->id); ?>"><i class="fa fa-edit"></i></a>
                                                        </td>
                                            </tr>
                                                <?php
                                                
                                                }
                                                ?>
                                            
                                            
                                           
                                            
                                        </tbody>
                                    </table>
                                    
                                    
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                           
                        </div>
                        <!-- /.portlet -->

                    </div>
        </div>
    </div>
</div>

