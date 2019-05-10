<?php 
    $usuario = $this->session->userdata('user_id');
    $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           
?>

    <div class="box">
        
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-calendar-o"></i><?=lang('Gestão de documentação do Projeto');?>
        </h2>
        
        <div id="eventos_index" class="box-icon">
           <div class="fprom-group">
            <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?=site_url('projetos/add_documentacao')?>"> 
             <i class="fa fa-plus-circle"></i>   <?=lang('Criar novo documento')?>
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
                                                <th>DOCUMENTO</th>
                                                <th>VERSÃO</th>
                                                <th>STATUS</th>
                                                <th>ELABORADO POR</th>
                                                <th>VERIFICADO POR</th>
                                                <th>APROVADO POR</th>
                                                
                                                <th>ANEXO DOC</th>
                                                <th>VISUALIZAR</th>
                                                <th>NOVA VERSÃO</th>
                                                
                                                
                                               
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($eventos as $evento) {
                                                       
                                                    
                                                 $res_tec = $this->site->geUserByID($evento->quem_criou);
                                                 
                                                 $res_assinar = $this->site->geUserByID($evento->quem_assinou);
                                                 
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?></td>  
                                                        
                                                      
                                                        <td><?php echo $evento->nome_documento; ?></td>
                                                        <td><?php echo $evento->versao; ?></td>
                                                        <td>
                                                            <p><?php echo $evento->status; ?></p>
                                                            <p style="font-size: 12px;"><?php if ($evento->data_finalizacao){ echo $this->sma->hrld($evento->data_finalizacao); } ?></p>
                                                        </td>
                                                        <td>
                                                            <p><?php echo $res_tec->first_name.' '.$res_tec->last_name; ?></p>
                                                            <p><?php echo $this->sma->hrld($evento->data_criacao); ?></p>
                                                        </td>
                                                        <td><?php echo $evento->revisado_por; ?></td>
                                                        <td><?php echo $evento->quem_assinou; ?></td>
                                                        
                                                       <th>
                                                           <?php if($evento->anexo){ ?>
                                                           <a target='_blank' href="<?= site_url('../assets/uploads/projetos/' . $evento->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                                <i class="fa fa-chain"></i>
                                                                <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                                                           </a>
                                                           <?php } ?>
                                                       </th> 
                                                      
                                                        <td class="center">
                                                            <a style="color: #FFFFFF;" class="btn btn-primary" href="<?= site_url('projetos/tap/'.$evento->id); ?>">ABRIR</a>
                                                        </td>
                                                     <td class="center">
                                                           
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

