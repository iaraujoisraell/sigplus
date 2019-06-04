<div class="box">
    
   
    <div class="box-header">
      
        
    </div>
    
    <div  class="box-content">
        
        <div class="row">
            <div class="col-lg-12">
            <?php
                    $usuario = $this->session->userdata('user_id');
                    $res_assinar = $this->site->geUserByID($usuario);
                    $nome_emitiu = $res_assinar->first_name;
                   ?>

                     <h3><?php echo $nome_emitiu; ?></h3>
            </div>
            <div class="col-lg-12">
                
              
             
                <div class="portlet-heading">
                    <div class="portlet-title">
                         <p style="background-color: gray; color: #ffffff; font-size: 16px;"  class="text"> LANÇAMENTOS DE R.A.T. (Regitro de Atividade de Trabalho) </p>
                    </div>
                    <div class="clearfix"></div>
                </div>    

                <div class="portlet portlet-default">
                    <div class="portlet-heading">
                        <div class="portlet-title">
                             Registros
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-responsive">
                            <table border="1" id="example1" class="table table-striped table-bordered table-hover table-green">
                                <thead style="width: 100%; ">
                                    <tr style="  font-size: 10px;">
                                        <th style="width: 5%; font-size: 10px;">ID</th>
                                        <th style="width: 8%; font-size: 10px;"><center>DATA</center></th>
                                        <th style="width: 8%; font-size: 10px;"><center>INÍCIO</center></th>
                                        <th style="width: 8%; font-size: 10px;"><center>TÉRMINO</center></th>
                                        <th style="width: 11%; font-size: 10px;"><center>TEMPO</center></th>
                                        <?php if($valores_pdf == 1){ ?>
                                        <th style="width: 10%; font-size: 10px;"><center>VALOR</center></th>
                                        <th style="width: 50%; font-size: 10px;">DESCRICAO</th>
                                        <?php }else{ ?>
                                        <th style="width: 60%; font-size: 10px;">DESCRICAO</th>
                                        <?php } ?>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                     <?php
                                        $wu4[''] = '';
                                        $cont = 1;
                                        //$planos = $this->networking_model->getAllAcaoPlanoAcaoByCategoriaAndPlano($plano_acao->id , $categoria->id);
                                        foreach ($sql_rat as $rat_id) {
                                            $rat = $this->networking_model->getRatByIdAndByEmpresa($rat_id);
                                           
                                            $dataHoje = date('Y-m-d');
                                           
                                            $id_rat = $rat->id_rat;
                                            $idplanos = $rat->idplanos;
                                            $data_rat = $rat->data_rat;
                                            $inicio = $rat->hora_inicio;
                                            $termino = $rat->hora_fim;
                                            $data_prazo = $rat->data_termino;
                                            $tempo = $rat->tempo;
                                            
                                            $partes_tempo = explode(":", $tempo);
                                            $hora_tempo = $partes_tempo[0];
                                            $minuto_tempo = $partes_tempo[1];
                                            
                                            $horas_minutos = $hora_tempo * 60;
                                            
                                            $subtotal = $horas_minutos + $minuto_tempo;
                                            
                                           // echo 'Hora : '.$horas_minutos.'<br>';
                                           // echo 'Minuto : '.$minuto_tempo.'<br>';
                                           // echo 'sub_total : '.$subtotal.'<br>';
                                           // echo '---------- <br>';  13:30
                                            
                                            
                                            $soma_minutos += $minuto_tempo;
                                            
                                            $soma_hora += $subtotal; 
 
                                            $valor = $rat->valor;
                                            $total_valor += $valor;
                                            $rat_descricao = $rat->descricao_rat;
                                            
                                            $idacao = $rat->idplanos;
                                            $acao_desc = $rat->descricao_acao;
                                            
                                            $status = $rat->status_acao;
                                            
                                        ?>   
                                    <tr class="odd gradeX">
                                          <td style="width: 5%; font-size: 8px;" class="center"><?php echo $cont++; ?></td>
                                        <td style="width: 8%; font-size: 8px;" class="center"><center><?php echo date("d/m/Y", strtotime($data_rat)); ?></center></td>
                                        <td style="width: 8%; font-size: 8px;" class="center"><center><?php echo $inicio; ?></center></td>
                                        <td style="width: 8%; font-size: 8px;" class="center"><center><?php echo $termino; ?></center></td>
                                        <td style="width: 11%; font-size: 8px;" class="center"><center><?php echo $tempo; ?></center></td>
                                        <?php if($valores_pdf == 1){ ?>
                                         <td style="width: 10%; font-size: 8px;" class="center"><center><?php echo 'R$'. str_replace('.', ',', $valor); ?></center></td>
                                         <td style="width: 50%; font-size: 8px;" class="center">
                                            <?php echo 'Projeto : '. $rat->projeto.'<br>'; ?>
                                            <?php echo 'Ação : ('.$idplanos.') '. strip_tags($rat->descricao_acao).'<br>'; ?>
                                            <?php echo 'Rat : '. strip_tags($rat->descricao_rat); ?>
                                        
                                        </td>
                                        <?php }else{ ?> 
                                        <td style="width: 60%; font-size: 8px;" class="center">
                                            <?php echo 'Projeto : '. $rat->projeto.'<br>'; ?>
                                            <?php echo 'Ação : ('.$idplanos.') '. strip_tags($rat->descricao_acao).'<br>'; ?>
                                            <?php echo 'Rat : '.strip_tags($rat->descricao_rat); ?>
                                        
                                        </td>
                                         <?php } ?>                                           
                                        </tr>
                                        <?php
                                        }
                                        
                                         $horas = floor($soma_hora / 60);
                                        $minutos = $soma_hora % 60;// floor(($total - ($horas * 60)) / 60);
                                        ?>
                                    
                                    <tr class="odd gradeX">
                                        <td style="width: 5%;" class="center">Total</td>
                                        <td style="width: 8%;" class="center"></td>
                                        <td style="width: 8%;" class="center"></td>
                                        <td style="width: 8%;" class="center"></td>
                                        <td style="width: 11%;font-size: 10px;" class="center"><center><?php echo  $horas . ":" . $minutos.' h'; ?> </center></td>
                                        <?php if($valores_pdf == 1){ ?>
                                        <td style="width: 10%;font-size: 10px;" class="center"><center><?php echo 'R$ '. number_format($total_valor, 2, ',', '.');  ?></center></td>
                                        <td style="width: 50%;" class="center"></td>
                                         <?php }else{ ?> 
                                        <td style="width: 60%;" class="center"></td>         
                                        <?php } ?>  
                                        </tr>    
                                        
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.portlet-body -->
                </div>
                <!-- /.portlet -->

                                        
                                        
               <?php if($detalhes){ ?>
                 <p style="background-color: gray; color: #ffffff; font-size: 14px;" >Observação/ Detalhes</p> 
           <?php echo $detalhes; ?>
            <?php } ?>               
                      
                </div>
                
                    
            
            
             <!-- /.col-lg-12 -->                             
            
                    
                    
                    
                    
           
    </div>
</div>


</div>
