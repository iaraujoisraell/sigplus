<div class="box">
    
   
    <div class="box-header">
      
        
    </div>
    
    <div  class="box-content">
        
        <div class="row">
            <div class="col-lg-12">
            <p style="background-color: gray; color: #ffffff; font-size: 16px;" class="text"><?= lang('Cabeçalho'); ?> </p>
            </div>
            <div class="col-lg-12">
                <table border="1"  class="table">
                   
                     <tr><td>ASSUNTO:</td> <td> <p > <?php echo $plano_acao->assunto; ?></p> </td></tr>
                    <tr><td>PERÍODO :</td> <td> <p class="introtext"> <?php echo ' De : '. substr($this->sma->hrld($plano_acao->data_pa),0,10). ' Até :'. substr($this->sma->hrld($plano_acao->data_termino_previsto),0,10); ?></p> </td></tr>
                    <tr><td>ELABORADO POR:</td> <td> <p > <?php echo $plano_acao->responsavel; ?></p> </td></tr>
                    <tr><td>APROVADO POR:</td> <td> <p > <?php echo $plano_acao->responsavel_aprovacao; ?></p> </td></tr>
                   
                 </table>    
               
              <?php if($plano_acao->objetivos){ ?>
                 <p style="background-color: gray; color: #ffffff; font-size: 16px;" >Objetivos/ Detalhes</p> 
           <?php echo $plano_acao->objetivos; ?>
            <?php } ?>   
             
                <h3>Categorias: </h3>
               
                         <?php
                            $categorias_plano = $this->networking_model->getAllCategoriasPlanoByIdPlanoAcao($plano_acao->id);
                                    foreach ($categorias_plano as $categoria) {
                                //   $cadastro_usuario =  $this->site->getUser($participante_cadastrados->id_participante);
                                            ?>
                                        <div class="portlet-heading">
                                            <div class="portlet-title">
                                                 <p style="background-color: gray; color: #ffffff; font-size: 16px;"  class="text"> <?php echo  $categoria->categoria; ?> </p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>    
                                        
                                        <div class="portlet portlet-default">
                                            <div class="portlet-heading">
                                                <div class="portlet-title">
                                                     Ações
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-responsive">
                                                    <table border="1" id="example-table" class="table table-striped table-bordered table-hover table-green">
                                                        <thead style="width: 100%; background-color: lightgray;">
                                                            <tr style="  font-size: 14px;">
                                                                <th style="width: 5%;" ><font style="font-size: 12px;"><center>Id</center></font></th>
                                                                <th style="width: 55%;"><font style="font-size: 12px;"><center>Descrição</center></font></th>
                                                                <th style="width: 20%;"><font style="font-size: 12px;"><center>Resp.</center></font></th>
                                                                <th style="width: 10%;"><font style="font-size: 12px;"><center>Prazo</center></font></th>
                                                                <th style="width: 10%;"><font style="font-size: 12px;"><center>Status</center></font></th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                             <?php
                                                                $wu4[''] = '';
                                                                $cont = 1;
                                                                $planos = $this->networking_model->getAllAcaoPlanoAcaoByCategoriaAndPlano($plano_acao->id , $categoria->id);
                                                                foreach ($planos as $plano) {

                                                                    //$acoes = $this->atas_model->getAllAcoes($plano->idplanos);
                                                                ?>   
                                                            <tr class="odd gradeX">
                                                                    <td style="width: 5%;"><center><font style=" font-size: 12px;"><?php echo $plano->id; ?></center></p></td>
                                                                    <td style="width: 55%;"><font style="font-size: 12px;"><?php echo $plano->descricao; ?></font></td>
                                                                    <td style="width: 20%;"><font style="font-size: 10px;"><center><?php echo $plano->responsavel; ?></center></font></td>
                                                                    <td style="width: 10%; font-size: 8px;" ><center><?php  if($plano->data_termino != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_termino); }else{ echo 'Não Definida';} ?></center></td>
                                                                   <td style="width: 10%;"><font style="font-size: 12px;"><?php echo $plano->status; ?></font></td>
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

                                   

                                          <?php
                                        
                                    }
                                ?>
                           
                      
                </div>
                
                    
            
            
             <!-- /.col-lg-12 -->                             
            
                    
                    
                    
                    
           
    </div>
</div>


</div>
