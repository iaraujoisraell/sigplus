<?php 

           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto;        
           
           
?>
    <div class="box">
        
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-list"></i><?=lang('Minhas Ações') . ' ( ' .  lang("Pendentes") . ')';?>
               
        </h2>
        
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
                    <font style="color: red">LEGENDA: </font><i class="fa fa-exclamation-triangle"></i>: PENDENTE    /  <i class="fa fa-clock-o"></i>: AGUARDANDO VALIDAÇÃO
                </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                         <tr>
                                       
                                                <th>ID</th>
                                                <th>PROJETO</th>
                                                <th>ATA</th>
                                                <th><i class="fa fa-download"></i></th>
                                                <th>DESCRIÇÃO</th>
                                                <th>DATA QUE EU RECEBI</th>
                                                <th>DATA DO PRAZO</th>
                                                <th>STATUS</th>
                                                <th>Ações Vinculadas</th>
                                                <th>Ver Ação</th>
                                                <th>Retorno</th>
                                                
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                //$cont = 1;
                                                foreach ($planos as $plano) {
                                                    
                                                    $ata_user = $this->atas_model->getAtaUserByAtaUser($plano->idatas, $usuario);
                                                    $result = $ata_user->id;
                                                    
                                                    //
                                                    
                                                      $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($plano->idatas);
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $plano->idplanos; ?>   </td> 
                                                        <td><?php echo $projetos_usuario->projetos; ?></td> 
                                                        <td><?php echo $plano->idatas; ?></td>     
                                                        <th><?php if($result){ ?> <a title="Download ATA" href="<?= site_url('welcome/pdf/'.$plano->idatas); ?> "><i class="fa fa-file-pdf-o"></i></a> <?php }else{ ?> - <?php } ?></th> 
                                                        <td><?php echo $plano->descricao; ?>
                                                         <p><font  style="font-size: 10px;"><?php echo $plano->tipo; ?> <?php echo $plano->processo; ?> <?php echo $plano->item_roteiro; ?></font></p>
                                               </td>     
                                                        <td>                <?php if($this->sma->hrld($plano->data_elaboracao) != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_elaboracao); }else{ echo $this->sma->hrld($ata->data_ata); } ?> </td>    
                                                        <td class="center"> <?php if($plano->data_termino    != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_termino); } ?></td>     


                                                        <?php if(($plano->status == 'PENDENTE')||($plano->status == 'ABERTO')){ ?>
                                                        <td class="center ">  <i style="color: brown" class="fa fa-exclamation-triangle"></i> </td>    
                                                        <?php } else if( $plano->status == 'AGUARDANDO VALIDAÇÃO'){?>
                                                        <td style="background-color: #CCA940; color: #ffffff;" class="center "> <i class="fa fa-clock-o"></i> </td>    
                                                        <?php } ?>
                                                        <td class="center">
                                                            <table>
                                                                    <?php
                                                                    $acoes_vinculadas = $this->atas_model->getAllAcoesVinculadas($plano->idplanos);
                                                                    foreach ($acoes_vinculadas as $acao_vinculada) {
                                                                        IF($acao_vinculada){
                                                                            if($acao_vinculada->id_vinculo == 0){
                                                                                $acao_vinculada->id_vinculo = "";
                                                                            }
                                                                        ?>
                                                                        <tr>
                                                                            <td class="center">
                                                                                <a  class="btn " data-toggle="modal" data-target="#myModal" href="<?= site_url('welcome/manutencao_acao_vinculo/' . $acao_vinculada->id_vinculo); ?>"> <?php echo $acao_vinculada->id_vinculo; ?> </a>
                                                                            </td>
                                                                        </tr>
                                                                    
                                                             <?php 
                                                             
                                                                        }
                                                                    }
                                                             ?>
                                                            </table>            
                                                        </td>
                                                        <td class="center">
                                                            <a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-refresh" data-toggle="modal" data-target="#myModal" href="<?= site_url('welcome/manutencao_acao/'.$plano->idplanos); ?>"> </a>
                                                        </td>
                                                         <td class="center">
                                                            <?php if(($plano->status == 'PENDENTE')||($plano->status == 'ABERTO')){ ?>
                                                            <a style="color: #ffffff; background-color: crimson; background-color: darkcyan; " class="btn fa fa-exchange" data-toggle="modal" data-target="#myModal" href="<?= site_url('welcome/retorno/'.$plano->idplanos); ?>"> Retornar</a>
                                                            <?php } else if(  $plano->status == 'AGUARDANDO VALIDAÇÃO'){?>
                                                              <a style="color: blue;" class="btn fa fa-clock-o"  href="" ></a>
                                                            <?php } ?>
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

