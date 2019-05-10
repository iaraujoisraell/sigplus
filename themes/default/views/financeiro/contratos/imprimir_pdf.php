<div class="box">
    <?php $fornecedor = $contrato->fornecedor; 
                            $dados_fornecedor = $this->site->getCompanyByID($fornecedor);
                                ?>
   
    <div class="box-header">
        <h4><center>GESTÃO DE CONTRATOS DA TI - FATURA: <?PHP ECHO $parcela->fatura; ?> </center> </h4>
        
    </div>
    <div  class="box-content">
        <div class="row">
      <div class="col-lg-12">
            <p style="background-color: gray; color: #ffffff; font-size: 12px;" class="text"><?= lang('TÍTULO '); ?></p>
            </div>
            </div>
        </div>
    
    <table style="width: 100%;" border="0"  class="table">
        <tr>
            <td style="width: 70%;">
               <p style=" font-size: 12px;" class="text">Referênte a parcela  : <?php echo $parcela->parcela_atual.'/'.$parcela->parcela.' - Vecto em : '.  substr($this->sma->hrld($parcela->date),0,10); ?></p> 
            </td>
            <td style="width: 30%;">
               <p style=" font-size: 12px;" class="text">Valor  : R$ <?php echo number_format($parcela->amount, 2, ',', '.'); ?></p> 
            </td>
        </tr>
    </table>
    <table style="width: 100%;" border="0"  class="table">
        <tr>
            <td style="width: 70%;">
               <p style=" font-size: 12px;" class="text">Fornecedor  : <?php echo $dados_fornecedor->company; ?></p> 
            </td>

        
            <td style="width: 30%;">
               <p style=" font-size: 12px;" class="text">Contrato  : <?php echo $contrato->numero_contrato; ?></p> 
            </td>

        </tr>
        </table>
    <table style="width: 100%;" border="0"  class="table">
                    <tr>
                        <td style="width: 100%;">
                           <p style=" font-size: 10px;" class="text">Obs : <?php echo $contrato->obs_titulo; ?></p> 
                        </td>
                       
                        
                    </tr>
                    
                 </table>
    
    <div  class="box-content">
        <div class="row">
             <div class="col-lg-12">
            <p style="background-color: gray; color: #ffffff; font-size: 12px;" class="text"><?= lang('DADOS DO CONTRATO '); ?></p>
            </div>
        
                  <div class="col-lg-12">
                     <table style="width: 100%;" border="0"  class="table">
                        <tr>
                            <td style="width: 70%;">
                               <p style=" font-size: 10px;" class="text">Número do Contrato  : <?php echo $contrato->numero_contrato; ?></p> 
                            </td>
                            <td style="width: 30%;">
                                <p style=" font-size: 10px;" class="text">Tipo de Contrato : <?php echo $contrato->tipo; ?></p> 
                            </td>
                        </tr>
                    </table>
                      <table style="width: 100%;" border="0"  class="table">
                        <tr>
                            <td style="width: 70%;">
                                <?php $cliente = $contrato->cliente; 
                                $dados_cliente = $this->site->getCompanyByID($cliente);
                                ?>
                               <p style=" font-size: 10px;" class="text">Empresa Contratante  : <?php echo $dados_cliente->company; ?></p> 
                            </td>
                            <td style="width: 30%;">
                                <p style=" font-size: 10px;" class="text">Renovação/Rescisão com : <?php echo $contrato->tempo_limite_renovacao; ?> (dias)</p> 
                            </td>
                        </tr>
                    </table>  
                     <table style="width: 100%;" border="0"  class="table">
                        <tr>
                            <td style="width: 70%;">
                                
                               <p style=" font-size: 10px;" class="text">Mês Reajuste  : <?php echo $contrato->mes_reajuste; ?></p> 
                            </td>
                            <td style="width: 30%;">
                                <p style=" font-size: 10px;" class="text">Tipo Renovação : <?php echo $contrato->tipo_renovacao; ?></p> 
                            </td>
                            
                        </tr>
                    </table>   
                      
                     
                      <table style="width: 100%;" border="0"  class="table">
                        <tr>
                            <td style="width: 70%;">
                               <p style=" font-size: 10px;" class="text">Início da vigência  : <?php echo substr($this->sma->hrld($contrato->inicio_vigencia),0,10); ?></p> 
                            </td>
                            
                             <td style="width: 30%;">
                                <p style=" font-size: 10px;" class="text">Valor (inicial) do Contrato  : R$ <?php echo str_replace('.', ',', $contrato->valor_original); ?></p> 
                            </td>
                        </tr>
                    </table>
                      <table style="width: 100%;" border="0"  class="table">
                        <tr>
                            
                            <td style="width: 20%;">
                                <p style=" font-size: 10px;" class="text">Status : <?php echo $contrato->status; ?></p> 
                            </td>
                            <td style="width: 30%;">
                                <p style=" font-size: 10px;" class="text">Tempo de Vigência  : <?php echo $contrato->vigencia_minima; ?> (Meses)</p> 
                            </td>
                             <td style="width: 50%;">
                                <p style=" font-size: 10px;" class="text">Seguimento  : <?php echo $contrato->seguimento; ?></p> 
                            </td>
                        </tr>
                    </table>
                    
                       <table style="width: 100%;" border="0"  class="table">
                        <tr>
                            <td style="width: 100%;">
                               <p style=" font-size: 10px;" class="text">Obs  : <?php echo $contrato->observacao; ?></p> 
                            </td>
                        </tr>
                    </table>
                       
                        
                </div> 
            </div>
         
        <div class="row">
            <div class="col-lg-12">
            <p style="background-color: gray; color: #ffffff; font-size: 12px;" class="text"><?= lang('DADOS DO FORNECEDOR '); ?></p>
            </div>
            <div class="col-lg-12">
                <table style="width: 100%;" border="0"  class="table">
                    <tr>
                        <td style="width: 70%;">
                           <p style=" font-size: 10px;" class="text">Razão Social : <?php echo $dados_fornecedor->company; ?></p> 
                        </td>
                        <td style="width: 30%;">
                            <p style=" font-size: 10px;" class="text">CNPJ : <?php echo $dados_fornecedor->vat_no; ?></p> 
                        </td>
                    </tr>
                 </table>
                <table style="width: 100%;" border="0"  class="table">
                    <tr>
                        <td style="width: 70%;">
                           <p style=" font-size: 10px;" class="text">Nome Fantasia : <?php echo $dados_fornecedor->fantasia; ?></p> 
                        </td>
                    </tr>
                 </table>
                
                <table style="width: 100%;" border="0"  class="table">
                    <tr>
                        <td style="width: 40%;">
                           <p style=" font-size: 10px;" class="text">Endereço : <?php echo $dados_fornecedor->address; ?></p> 
                        </td>
                        <td style="width: 20%;">
                           <p style=" font-size: 10px;" class="text">CEP : <?php echo $dados_fornecedor->postal_code; ?></p> 
                        </td>
                        <td style="width: 20%;">
                            <p style=" font-size: 10px;" class="text">Cidade :  <?php echo $dados_fornecedor->city; ?></p> 
                        </td>
                        <td style="width: 20%;">
                            <p style=" font-size: 10px;" class="text">UF : <?php echo $dados_fornecedor->state; ?></p> 
                        </td>
                    </tr>
                    
                 </table>
                
                 
                
                 <table style="width: 100%;" border="0"  class="table">
                    <tr>
                        <td style="width: 40%;">
                           <p style=" font-size: 10px;" class="text">E-mail : <?php echo $dados_fornecedor->email; ?></p> 
                        </td>
                        <td style="width: 30%;">
                            <p style=" font-size: 10px;" class="text">Telefone : <?php echo $dados_fornecedor->phone; ?></p> 
                        </td>
                        
                    </tr>
                    
                 </table>
                
                <table style="width: 100%;" border="0"  class="table">
                    <tr>
                        <td style="width: 100%;">
                           <p style=" font-size: 10px;" class="text">Obs : <?php echo $dados_fornecedor->cf6; ?></p> 
                        </td>
                       
                        
                    </tr>
                    
                 </table>
                
                   
                </div>
                
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     <p style="background-color: gray; color: #ffffff; font-size: 12px;"  class="text">ÚLTIMAS PARCELAS</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table style="width: 100%;"  id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead style="width: 100%;">
                                            <tr style="  font-size: 12px;">
                                                <th style="  font-size: 12px;"></th>
                                                <th style="  font-size: 12px;">PARC.</th>
                                                 <th style="  font-size: 12px;">DT VENCTO  </th>
                                              
                                                <th style="  font-size: 12px;">TITULO</th>
                                                <th style="  font-size: 12px;">FATURA</th>
                                                <th style="  font-size: 12px;">VL À PAGAR</th>
                                                  <th style="  font-size: 12px;">DT PGTO </th>
                                                <th style="  font-size: 12px;">VL PAGO</th>
                                               
                                               
                                                <th style="  font-size: 12px;">STATUS</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                               $titulos = $this->projetos_model->getTitulosAnterioresByParcela($contrato->id, $parcela->id);
                                                foreach ($titulos as $titulo) {
                                                    ?>
                                            
                                            <tr   class="odd gradeX">
                                                        <td style="  font-size: 8px;"><?php  $cont++; ?>   </td> 
                                                        <td style="  font-size: 8px;"><?php echo $titulo->parcela_atual.'/'.$titulo->parcela; ?></td>    
                                                          <td style="  font-size: 8px;"><?php echo substr($this->sma->hrld($titulo->date), 0, 10); ?></td> 
                                                        <td style="  font-size: 8px;" class="center"><?php echo $titulo->titulo; ?></td>
                                                       <td style="  font-size: 8px;" class="center"><?php echo $titulo->fatura; ?></td>
                                                       <td style="  font-size: 8px;" class="center">
                                                           <?php echo ' R$ '. number_format($titulo->amount, 2, ',', '.'); ?>
                                                         </td>
                                                             <td style="  font-size: 8px;"><?php if(($titulo->date_pagamento != null)&&($titulo->date_pagamento != "0000-00-00")){ echo substr($this->sma->hrld($titulo->date_pagamento), 0, 10); }else if($titulo->date_pagamento == '0000-00-00'){ echo '';} ?></td> 
                                                   
                                                       
                                                        <td style="  font-size: 8px;">
                                                            <?php echo ' R$ '. number_format($titulo->cr, 2, ',', '.'); ?>
                                                        </td> 
                                                        <?php
                                                        if($titulo->status == 'PAGO'){
                                                            $cor = 'GREEN';
                                                            $icone = 'check';
                                                        }else if ($titulo->status == 'ABERTO'){
                                                            $cor = 'ORANGE';
                                                            $icone = 'circle-o';
                                                        }else if ($titulo->status == 'ATRASADO'){
                                                            $cor = 'RED';
                                                            $icone = 'circle-o';
                                                        }else if ($titulo->status == 'SUSPENSO'){
                                                            $cor = 'BLACK';
                                                            $icone = 'fa-ban';
                                                        }
                                                        ?>
                                                        
                                                        <td style="  font-size: 8px;" class="center"><?php echo $titulo->status; ?></td>
                                                        
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
