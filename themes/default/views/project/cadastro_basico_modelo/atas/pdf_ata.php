<div class="box">
    
   
    <div style="" class="box-header">
        
    </div>
    
    <div  class="box-content">
        
        <div class="row">
            <div class="col-lg-12">
            <p style="background-color: gray; color: #ffffff; font-size: 16px;" class="text"><?= lang('ATA No.  '); ?><?php echo $ata->sequencia; ?> </p>
            </div>
            <div class="col-lg-12">
                <table style="width: 80%;" class="table">
                    <tr><td style="width: 15%;">PROJETO:</td>      <td style="width: 60%;"> <p > <?php echo $projeto->projeto; ?></p> </td></tr>
                    <tr><td style="width: 15%;">LOCAL:</td>        <td style="width: 60%;"> <p > <?php echo $ata->local; ?></p> </td></tr>
                    <tr><td style="width: 15%;">RESPONSÁVEL:</td>  <td style="width: 60%;"> <p > <?php echo $ata->responsavel_elaboracao; ?></p> </td></tr>
                    <tr><td style="width: 15%;">PAUTA:</td>        <td style="width: 60%;">  <?php echo $ata->pauta; ?> </td></tr>
                    <tr><td style="width: 15%;">DATA :</td>        <td style="width: 60%;"> <p class="introtext"> <?php echo date("d/m/Y", strtotime($ata->data_ata)); ?> </p> </td></tr>
                    <tr><td style="width: 15%;">HORA :</td>        <td style="width: 60%;"> <p class="introtext">  <?php echo ''.$ata->hora_inicio.' - '.$ata->hora_termino ?></p> </td></tr>
                </table>    
                
                <br><br>
                
                <p style="background-color: gray; color: #ffffff; font-size: 16px;" class="text"><?= lang('REGISTRO DE PRESENÇA '); ?> </p>
                
                <table style="width: 100%;"  class="table">
                  <tr>
                            <td style="width: 50%; min-width: 50%; max-width: 50%;">
                               <p style=" font-size: 16px;" class="text">Participantes</p> 
                            </td>
                            <td style="width: 50%; min-width: 50%; max-width: 50%;;">
                                <p style=" font-size: 16px;" class="text">Assinaturas</p> 
                            </td>
                        </tr>
                 </table>
                
                
                <table  class="table">
                    <tbody style="width: 100%;">
                        
                         <?php
                            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($ata->id);
                                    foreach ($participantes_cadastrados_ata as $participante_cadastrados) {

                                        if($participante_cadastrados){
                                        //    $cadastro_usuario =  $this->site->getUser($participante_cadastrados->id_participante);
                                          //  $historico_convocado =  $this->atas_model->listaConvocadosByUsuarioAta($cadastro_usuario->id, $id);
                                            ?>
                                            <tr>
                                                <td style="width: 50%;">
                                                    <?php echo  $participante_cadastrados->nome .' - '.$participante_cadastrados->setor; ?>
                                                </td>
                                                <td style="width: 50%; ">
                                                   <?php echo '______________________________________________________________'; ?>
                                                </td>
                                            </tr>

                                          <?php
                                        }
                                    }
                                ?>
                           
                    </tbody>
                </table>    
                </div>
                
            <br>
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
                                    <table style="width:100%;" id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead style="background-color: lightgray;">
                                            <tr style="  font-size: 14px;">
                                                <th><font style="font-size: 12px;"><center>Id</center></font></th>
                                                <th><font style="font-size: 12px;">Descrição</font></th>
                                                <th><font style="font-size: 12px;"><center>Responsável</center></font></th>
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
                                                    <td style="width: 10%;"><center><font style=" font-size: 12px;"><?php echo $plano->sequencial; ?></center></td>
                                                    <td style="width: 50%;"><font style="font-size: 12px;"><?php echo $plano->descricao; ?></font></td>
                                                    <td style="width: 20%;"><font style="font-size: 12px;"><center><?php echo $plano->first_name.' '.$plano->last_name ; ?></center></font></td>
                                                    <td style="width: 10%; font-size: 12px;" ><center><?php  if($plano->data_termino != '0000-00-00 00:00:00'){ echo date("d/m/Y", strtotime($plano->data_termino)); }else{ echo 'Não Definida';} ?></center></td>
                                                   <td style="width: 10%;"><font style="font-size: 12px;"><center><?php echo $plano->status; ?></center></font></td>
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
            <br>
            <div class="col-lg-12">
                <p style="background-color: gray; color: #ffffff; font-size: 16px;" ><?php if($ata->titulo_discussao){ echo $ata->titulo_discussao; }else{ echo 'Descrição'; }  ; ?></p> 
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
