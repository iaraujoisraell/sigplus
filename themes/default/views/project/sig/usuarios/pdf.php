<div class="box">
    
   
    <div class="box-header">
        
        
    </div>
    
    <div  class="box-content">
        
        <div class="row">
            <div class="col-lg-12">
            <p style="background-color: gray; color: #ffffff; font-size: 16px;" class="text-center"><?= lang('ATA DE '.$ata->tipo.' -  '); ?><?php echo $id; ?> (<?php echo $ata->projetos; ?>)</p>
            </div>
            <div class="col-lg-12">
                <p class="introtext">DATA : <?php echo substr($this->sma->hrld($ata->data_ata),0,10); ?></p>
                <p class="introtext">PAUTA: <?php echo $ata->pauta; ?></p>
                <p style="background-color: gray; color: #ffffff; font-size: 16px;" class="text-center">PARTICIPANTES</p> 
                <table border="1"  class="table">
                    <tbody style="width: 100%;">
                            <?php echo str_replace(';', '<tr > <th>  ',  $ata->participantes); ?>
                            
                        </tr>
                    </tbody>
                    
                </table>    
                    
                
                
                
                <div class="row">
                    <p class="text-right"><font style="font-size: 12px;">LEGENDA  (P: PENDENTE;  C: CONCLUÍDO)</font></p>
                </div>
                </div>
                
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     <p style="background-color: gray; color: #ffffff; font-size: 16px;"  class="text-center">AÇÕES</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table border="1" id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead style="background-color: lightgray;">
                                            <tr style="  font-size: 14px;">
                                                <th><font style="font-size: 12px;"><center>ID</center></font></th>
                                                <th><font style="font-size: 12px;"><center>DESCRIÇÃO</center></font></th>
                                                <th><font style="font-size: 12px;"><center>RESP.</center></font></th>
                                                <th><font style="font-size: 12px;"><center>DATA</center></font></th>
                                                <th><font style="font-size: 12px;">STATUS</font></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($planos as $plano) {
                                                       
                                                    $acoes = $this->atas_model->getAllAcoes($plano->idplanos);
                                                ?>   
                                            <tr class="odd gradeX">
                                                    <td style="width: 6%;"><center><font style=" font-size: 12px;"><?php echo $plano->idplanos; ?></center></p></td>
                                                    <td><font style="font-size: 12px;"><?php echo $plano->descricao; ?></font></td>
                                                    <td style="width: 10%;"><font style="font-size: 12px;"><center><?php echo $plano->first_name ; ?></center></font></td>
                                            <td style="width: 12%;" ><center><?php  if($plano->data_termino != '0000-00-00 00:00:00'){ echo substr($this->sma->hrld($plano->data_termino), 0, 10); }else{ echo 'Não Definida';} ?></center></td>
                                                    <td ><center><?php if ($plano->status == 'PENDENTE'){ echo 'P'; }ELSE IF($plano->status == 'CONCLUÍDO'){ echo 'P'; } ?></center></td>
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
                    <!-- /.col-lg-12 -->                             
            
            <?php if($ata->observacao){ ?>
            <p class="introtext">OBSERVAÇÃO: <?php echo $ata->observacao; ?></p>
            <?php } ?>
    </div>
</div>


</div>
