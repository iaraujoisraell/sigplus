<?php 

           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto;        
           
           
?>
    <div class="box">
        
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-calendar-o"></i><?=lang('STATUS REPORT');?>
        </h2>
        
        <div id="eventos_index" class="box-icon">
           <div class="fprom-group">
            <a class="btn btn-primary" href="<?=site_url('Reports/add_status_report')?>"> 
             <i class="fa fa-plus-circle"></i>   <?=lang('Novo Registro')?>
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
        <script>
        $('#idTr').bind('click', function() {
  alert("Linha foi clicada");
});
        </script>
        
        <style>
            table#tableTrClick tr.trClick{background: #000; color: #fff; cursor: pointer;}
table#tableTrClick tr.trClick:hover{background: green; color: #fff; font-weight: bold;}

        </style>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                        <div class="portlet portlet-default">
                         <div style="text-align: right" class="col-lg-12">
                  </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                         <tr>
                                       
                                                <th>ID</th>
                                                <th>DE</th>
                                                <th>ATÉ</th>
                                                <th>PROJETO</th>
                                                <th>PRAZO</th>
                                                <th>CUSTO</th>
                                                <th>ESCOPO</th>
                                                <th>COMUNICAÇÃO</th>
                                                <th>Editar</th>
                                                </a>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($status_report as $status) {
                                                       
                                                    
                                                 //$res_tec = $this->site->geUserByID($evento->responsavel);
                                                 //$res_edp = $this->site->geUserByID($evento->responsavel_edp);   
                                                 
                                                // $quantidade_acoes = $this->projetos_model->getAcoesEventoByID($evento->id);
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?>   </td>  
                                                        <th><?php echo $this->sma->hrld($status->periodo_de); ?></th> 
                                                        <th><?php echo $this->sma->hrld($status->periodo_ate); ?></th>  
                                                        <td ><?php echo $status->projeto; ?></td>
                                                        <td ><?php echo $status->prazo; ?></td>     
                                                        <td><?php echo $status->custo; ?></td>
                                                        <td><?php echo $status->escopo; ?></td>
                                                        <td><?php echo $status->comunicacao; ?></td>
                                                       
                                                        <td class="center">
                                                                  <a style="color: #D37423;" class="btn fa fa-edit"  href="<?= site_url('Reports/edit_status_report/'.$status->id); ?>"></a>
                                              
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

