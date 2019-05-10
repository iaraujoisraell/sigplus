<div class="box">
    
   
    <div class="box-header">
        
        
    </div>
    
    <div  class="box-content">
        
        <div class="row">
            <div class="col-lg-12">
            <p style="background-color: gray; color: #ffffff; font-size: 16px;" class="text"><?= lang('ATA DE '.$ata->tipo.' No.  '); ?><?php echo $id; ?> (<?php echo $ata->projetos; ?>)</p>
            </div>
            <div class="col-lg-12">
                <table border="1"  class="table">
                   
                    
                    <tr><td>DATA :</td> <td> <p class="introtext"> <?php echo substr($this->sma->hrld($ata->data_ata),0,10); ?></p> </td></tr>
                    <tr><td>LOCAL:</td> <td> <p > <?php echo $ata->local; ?></p> </td></tr>
                    <tr><td>REDIGIDO POR:</td> <td> <p > <?php echo $ata->responsavel_elaboracao; ?></p> </td></tr>
                    <tr><td>PAUTA:</td> <td>  <?php echo $ata->pauta; ?> </td></tr>
                 </table>    
                
                
                <table style="width: 100%;" border="1"  class="table">
                  <tr>
                            <td style="width: 40%;">
                               <p style=" font-size: 16px;" class="text">PARTICIPANTES</p> 
                            </td>
                            <td style="width: 60%;">
                                <p style=" font-size: 16px;" class="text">ASSINATURA</p> 
                            </td>
                        </tr>
                 </table>
                
                
                <table border="1"  class="table">
                    <tbody style="width: 100%;">
                        
                         <?php
                            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($ata->id);
                                    foreach ($participantes_cadastrados_ata as $participante_cadastrados) {

                                        if($participante_cadastrados){
                                            $cadastro_usuario =  $this->site->getUser($participante_cadastrados->id_participante);
                                            $historico_convocado =  $this->atas_model->listaConvocadosByUsuarioAta($cadastro_usuario->id, $id);


                                            ?>
                                            <tr>
                                                <td style="width: 40%;">
                                                    <?php echo  $cadastro_usuario->first_name.' '.$cadastro_usuario->last_name; ?>
                                                </td>
                                                <td style="width: 60%;">
                                                   
                                                </td>
                                            </tr>

                                          <?php
                                        }
                                    }
                                ?>
                           
                    </tbody>
                </table>    
                </div>
                
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     <p style="background-color: gray; color: #ffffff; font-size: 16px;"  class="text">AÇÕES IMEDIATAS</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table border="1" id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead style="background-color: lightgray;">
                                            <tr style="  font-size: 14px;">
                                                <th><font style="font-size: 12px;"><center>Id</center></font></th>
                                                <th><font style="font-size: 12px;"><center>Descrição</center></font></th>
                                                <th><font style="font-size: 12px;"><center>Resp.</center></font></th>
                                                <th><font style="font-size: 12px;"><center>Prazo</center></font></th>
                                                <th><font style="font-size: 12px;"><center>Status</center></font></th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($planos as $plano) {
                                                       
                                                    //$acoes = $this->atas_model->getAllAcoes($plano->idplanos);
                                                ?>   
                                            <tr class="odd gradeX">
                                                    <td style="width: 6%;"><center><font style=" font-size: 12px;"><?php echo $plano->idplanos; ?></center></p></td>
                                                    <td><font style="font-size: 12px;"><?php echo $plano->descricao; ?></font></td>
                                                    <td style="width: 10%;"><font style="font-size: 8px;"><center><?php echo $plano->first_name.' '.$plano->last_name ; ?></center></font></td>
                                                    <td style="width: 12%; font-size: 8px;" ><center><?php  if($plano->data_termino != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_termino); }else{ echo 'Não Definida';} ?></center></td>
                                                   <td><font style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
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
            
            <div class="col-lg-12">
                <p style="background-color: gray; color: #ffffff; font-size: 16px;" >DESCRIÇÃO DA REUNIÃO</p> 
                <?php if ($ata->discussao) { 
                    $texto = $ata->discussao;
                   $linhas = explode("\n", $texto);
                   $conta = count($linhas);
                   
                // echo $linhas;
                    ?>
                 <?php 
                 
                echo $texto;
                    ?>
                    
                <?php } ?>
            </div>
            <div class="col-lg-12">
              <?php if($ata->obs){ ?>
                 <p style="background-color: gray; color: #ffffff; font-size: 16px;" >OBSERVAÇÃOO</p> 
           <?php echo $ata->obs; ?>
            <?php } ?>   
            </div>       <!-- /.col-lg-12 -->                             
            
                    
                    
                    
                    
           
    </div>
</div>


</div>
