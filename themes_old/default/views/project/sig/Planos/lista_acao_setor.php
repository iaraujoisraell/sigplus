<script>
  localStorage.setItem('date', '<?= $this->sma->hrld($data_hoje) ?>');
  
    $("#date").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
            
            $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});
</script>
<style>
    #blanket,#aguarde {
    position: fixed;
    display: none;
}

#blanket {
    left: 0;
    top: 0;
    background-color: #f0f0f0;
    filter: alpha(opacity =         65);
    height: 100%;
    width: 100%;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
    opacity: 0.65;
    z-index: 9998;
}

#aguarde {
    width: auto;
    height: 30px;
    top: 40%;
    left: 45%;
    background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%; 
    line-height: 30px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    z-index: 9999;
    padding-left: 27px;
}
</style> 

<?php 
function geraTimestamp($data) {
  $partes = explode('/', $data);
  return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}
?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('LISTA DE AÇÕES POR SETOR'); ?>
           </h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
               
                <div class="row">
                    <div id="blanket"></div>
                    <div id="aguarde">Aguarde...</div>
                    
                    <div class="col-lg-12">
                          
                             <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Data", "sldate"); ?>
                                    <?php echo form_input('dateEscolhida', (isset($_POST['dateEscolhida']) ? $_POST['dateEscolhida'] : $this->sma->hrld($dataEscolhida)), 'class="form-control input-tip datetime" disabled="disabled" id="sldate" required=$projeto"required"'); ?>
                                </div>
                            </div>
                        
                    
                    </div>    
                        
                  
                    
                 
                          
                        
                    <div style="background-color: #E3E3E3" class="col-lg-12">
                        
                        <br>

                        <div class="clearfix"></div>
                        <?php
                        //$this->data['planosSetor'] = $this->atas_model->getAllitemPlanosProjetoSetor(1);
                        $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                        echo form_open_multipart("Atas/plano_acao", $attrib);
                        
                          foreach ($setores as $setor) {
                            
                            $setor_selecionado = $setor->setor_id;
                            $acoes_setor = $this->atas_model->getAllitemPlanosProjetoSetor($projetos_usuario,$setor_selecionado);
                            
                            $somma_acoes_pendentes_setor = 0;
                            $somma_acoes_atrasadas_5_setor = 0;
                            $somma_acoes_concluidas_setor = 0;
                            $somma_acoes_concluidas_fora_prazo_setor = 0;
                            $somma_acoes_atrasadas_10_setor = 0;
                            $somma_total_acal_setor = 0;
                            $somma_acoes_atrasadas_15_setor = 0;
                        
                            foreach ($acoes_setor as $a_setor) {
                                                       
                                $adata_prazo = $a_setor->data_termino;
                                $adata_entrega = $a_setor->data_retorno_usuario;
                                $astatus = $a_setor->status;

                                if($astatus == 'CONCLUÍDO'){

                            
                                    if($adata_entrega <= $adata_prazo){
                                        $somma_acoes_concluidas_setor +=1;
                                    }

                                    if($adata_entrega > $adata_prazo){
                                         $somma_acoes_concluidas_fora_prazo_setor +=1;
                                    }

                                }else if($astatus == 'PENDENTE'){

                                    if($dataEscolhida <= $adata_prazo){
                                        $somma_acoes_pendentes_setor += 1;
                                    }

                          
                                    if($dataEscolhida > $adata_prazo){
                                            $novo_status_setor = 'ATRASADO';

                                            // Usa a função criada e pega o timestamp das duas datas:
                                            $time_inicial_setor = geraTimestamp($this->sma->hrld($dataEscolhida));
                                            $time_final_setor = geraTimestamp($this->sma->hrld($adata_prazo));
                                            // Calcula a diferença de segundos entre as duas datas:
                                            $diferenca_setor = $time_final_setor - $time_inicial_setor; // 19522800 segundos
                                            // Calcula a diferença de dias
                                            $dias_setor = (int)floor( $diferenca_setor / (60 * 60 * 24)); // 225 dias



                                             if($dias_setor >= '-5'){
                                                $somma_acoes_atrasadas_5_setor +=1;
                                            }else if(($dias_setor < '-5') && ($dias_setor >= '-10')){
                                               $somma_acoes_atrasadas_10_setor +=1;
                                            }else if($dias_setor < '-10') {
                                                $somma_acoes_atrasadas_15_setor +=1;
                                            }

                                        }
                                }
                                                    
                                 $somma_total_acal_setor+=1;    
                                 
                                 
                         } //fim for $acoes_setor
                         
                         
                         ?>
                         
                        <div class="col-md-12">
                            <div class="form-group">
                                <center>
                                    <h2>  <?PHP ECHO $setor->superintendencia; ?> ( <?PHP ECHO $setor->setor;; ?> ) </h2>
                                </center>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table  id="example-table" class="table table-striped table-bordered table-hover table-green">
                                    <thead>
                                        <tr >
                                            <th style="background-color: #007700">CONCLUÍDAS</th>
                                            <th style="background-color: #99ca63">CONC. F. PRAZO</th>
                                            <th style="background-color: #CB3500">PENDENTES</th>
                                            <th style="background-color: #c7254e">ATRASADAS +5</th>
                                            <th style="background-color: #d2322d">ATRASADAS +10</th>
                                            <th style="background-color: #000000">ATRASADAS +15</th>
                                            <th style="background-color: #666">TOTAL</th>
                                            <th>VER</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    <td class="center"><?php echo $somma_acoes_concluidas_setor; ?></td>
                                    <td class="center"><?php echo $somma_acoes_concluidas_fora_prazo_setor; ?></td>
                                    <td class="center"><?php echo $somma_acoes_pendentes_setor; ?></td>
                                    <td class="center"><?php echo $somma_acoes_atrasadas_5_setor; ?></td>
                                    <td class="center"><?php echo $somma_acoes_atrasadas_10_setor; ?></td>
                                    <td class="center"><?php echo $somma_acoes_atrasadas_15_setor; ?></td>
                                    <td class="center"><?php echo $somma_total_acal_setor; ?></td>
                                    <td class="center"> <a style="color: #c7254e;" class="btn " href="<?= site_url('Planos/lista_acao_setor/'.$setor->setor_id.'#div_lista'); ?>"><?= lang('Detalhes') ?></a></td>
                                    </tbody>


                                </table>
                            </div>                
                                           
                         
                         <?php
                     } //fim for $setores
                        
                    ?>
                        
                        
                        
                         <?php echo form_close(); ?>
                    <?php    
                        $somma_acoes_pendentes = 0;
                        $somma_acoes_atrasadas_5 = 0;
                        $somma_acoes_concluidas = 0;
                        $somma_acoes_concluidas_fora_prazo = 0;
                        $somma_acoes_atrasadas_10 = 0;
                        $somma_total_acal = 0;
                        $somma_acoes_atrasadas_15 = 0;
                        
                         foreach ($planos as $plano) {
                                                       
                            $data_prazo = $plano->data_termino;
                            $data_entrega = $plano->data_retorno_usuario;
                            $status = $plano->status;

                            if($status == 'CONCLUÍDO'){

                            /*
                          * SE A DATA DE CONLUSÃO FOR <= A DATA DO PRAZO
                          * CONCLUÍDO
                          */
                                if($data_entrega <= $data_prazo){
                                    $somma_acoes_concluidas +=1;
                                }

                          /*
                          * SE A DATA DE CONLUSÃO FOR > A DATA DO PRAZO
                             * CONCLUÍDO FORA DO PRAZO
                          */
                            if($data_entrega > $data_prazo){
                                     $somma_acoes_concluidas_fora_prazo +=1;
                                }

                            }else

                             if($status == 'PENDENTE'){

                          /*
                          * SE A DATA ATUAL FOR < A DATA DO PRAZO
                          * PENDENTE
                          */
                                if($dataEscolhida <= $data_prazo){
                                    $somma_acoes_pendentes += 1;
                                }

                          /*
                          * SE A DATA ATUAL FOR > A DATA DO PRAZO
                          * ATRASADO (X DIAS)
                           * +5 DIAS
                           * +10 DIAS
                           * 
                          */
                            if($dataEscolhida > $data_prazo){
                                    $novo_status = 'ATRASADO';

                                    // Usa a função criada e pega o timestamp das duas datas:
                                    $time_inicial = geraTimestamp($this->sma->hrld($dataEscolhida));
                                    $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                                    // Calcula a diferença de segundos entre as duas datas:
                                    $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                    // Calcula a diferença de dias
                                    $dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias



                                     if($dias >= '-5'){
                                        $somma_acoes_atrasadas_5 +=1;
                                    }else if(($dias < '-5') && ($dias >= '-10')){
                                       $somma_acoes_atrasadas_10 +=1;
                                    }else if($dias < '-10') {
                                        $somma_acoes_atrasadas_15 +=1;
                                    }

                                }


                            }
                                                    
                                 $somma_total_acal+=1;                   
                         }
                        ?>
                         <br>
                        <center>
                            <div class="col-md-12">
                                <a style="background-color: #0088cc" class="btn btn-danger" href="<?= site_url('Planos/lista_acao_setor'); ?>"><?= lang('EXIBIR TODOS OS SETORES') ?></a>
                                  </div>
                        </center>
                       
                        <br><br><br>
                    </div>
                    

                    <div <?php if($setore_selecionado){ ?> id="div_lista" <?php } ?> class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     <h3>TODAS AS AÇÕES <?php if($setore_selecionado){ echo ' - '. $setor_id->nome; } ?></h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-md-12">
                            <div class="form-group">
                                <center>
                                    <h2>  RESUMO  </h2>
                                </center>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Total concluidas :", "slelaboracao"); ?>
                                <?php echo $somma_acoes_concluidas; ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Conc. F. do Prazo :", "slelaboracao"); ?>
                                <?php echo $somma_acoes_concluidas_fora_prazo; ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Pendentes :", "slelaboracao"); ?>
                                <?php echo $somma_acoes_pendentes; ?>
                            </div>
                        </div> 
                         <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +5 :", "slelaboracao"); ?>
                                <?php echo $somma_acoes_atrasadas_5; ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +10 : ", "slelaboracao"); ?>
                                <?php echo $somma_acoes_atrasadas_10; ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +15 :", "slelaboracao"); ?>
                                <?php echo $somma_acoes_atrasadas_15; ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Total Ação : ", "slelaboracao"); ?>
                                <?php echo $somma_total_acal; ?>
                            </div>
                        </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>DATA PRAZO</th>
                                                <th>DATA CONCLUSÃO</th>
                                                <th>STATUS</th>
                                                <th>RESPONSÁVEL</th>
                                                <th>SETOR</th>
                                                <th>GESTOR</th>
                                                <th>SUPERINTENDENTE</th>
                                                <th>VER</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             

                                             $wu4[''] = '';
                                                $cont = 1;
                                                //$hoje = date('Y-m-d H:i:s');    
                                                foreach ($planos as $plano) {
                                                       
                                                    $data_prazo = $plano->data_termino;
                                                    $data_entrega = $plano->data_retorno_usuario;
                                                    $status = $plano->status;
                                                    
                                                    if($status == 'CONCLUÍDO'){
                                                        
                                                    /*
                                                  * SE A DATA DE CONLUSÃO FOR <= A DATA DO PRAZO
                                                  * CONCLUÍDO
                                                  */
                                                        if($data_entrega <= $data_prazo){
                                                            $novo_status = 'CONCLUÍDO';
                                                        }
                                                   
                                                  /*
                                                  * SE A DATA DE CONLUSÃO FOR > A DATA DO PRAZO
                                                     * CONCLUÍDO FORA DO PRAZO
                                                  */
                                                    if($data_entrega > $data_prazo){
                                                            $novo_status = 'CONCLUÍDO FORA DO PRAZO';
                                                        }
                                                        
                                                    }else
                                                    
                                                     if($status == 'PENDENTE'){
                                                       
                                                  /*
                                                  * SE A DATA ATUAL FOR < A DATA DO PRAZO
                                                  * PENDENTE
                                                  */
                                                        if($dataEscolhida <= $data_prazo){
                                                            $novo_status = 'PENDENTE';
                                                        }
                                                   
                                                  /*
                                                  * SE A DATA ATUAL FOR > A DATA DO PRAZO
                                                  * ATRASADO (X DIAS)
                                                   * +5 DIAS
                                                   * +10 DIAS
                                                   * 
                                                  */
                                                    if($dataEscolhida > $data_prazo){
                                                            $novo_status = 'ATRASADO';
                                                           
                                                            // Usa a função criada e pega o timestamp das duas datas:
                                                            $time_inicial = geraTimestamp($this->sma->hrld($dataEscolhida));
                                                            $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                                                            // Calcula a diferença de segundos entre as duas datas:
                                                            $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                                            // Calcula a diferença de dias
                                                            $dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
                                                            
                                                             if($dias >= '-5'){
                                                                $qtde_dias = '+5';
                                                            }else if(($dias < '-5') && ($dias >= '-10')){
                                                                $qtde_dias = '+10';
                                                            }else if($dias < '-10') {
                                                                $qtde_dias = '+15';
                                                            }
                                                        }
                                                    }
                                                    
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td><?php echo $plano->idplanos; ?></td>
                                                <td class="center"><?php echo $this->sma->hrld($plano->data_termino); ?></td>
                                                <td class="center"><?php if($plano->status == 'CONCLUÍDO'){ echo $this->sma->hrld($plano->data_retorno_usuario); } ?></td>
                                                
                                                <?php
                                                 
                                                if($novo_status == 'CONCLUÍDO'){ ?>
                                                <td style="background-color: #007700;color: #ffffff" class="center"><?php echo $novo_status; ?></td>
                                                <?php }
                                                    
                                                else if($novo_status == 'CONCLUÍDO FORA DO PRAZO'){ ?>
                                                <td style="background-color: #99ca63" class="center"><?php echo $novo_status; ?></td>
                                                <?php }
                                                
                                                else if( $novo_status == 'PENDENTE'){?>
                                               <td style="background-color: #CB3500;color: #ffffff" class="center"><?php echo $novo_status; ?></td>
                                               
                                                <?php } else if( $novo_status == 'ATRASADO'){
                                                    
                                                    if($dias >= '-5'){
                                                    ?>
                                                <td style=" background-color: #c7254e; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias. ' dias ) '; ?></td>
                                               <?php }else if(($dias < '-5') && ($dias >= '-10')){ ?> 
                                                  <td style=" background-color: #d2322d; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias. ' dias ) '; ?></td>
                                             
                                                <?php }else if ($dias < '-10'){ ?> 
                                                 <td style=" background-color: #000000; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias. ' dias ) '; ?></td>
                                             
                                                <?php } ?> 
                                               
                                                
                                                <?php } ?> 
                                               
                                                <td class="center"><?php echo $plano->username; ?></td>
                                                <td class="center"><?php echo $plano->setor ?> </td>
                                                <td class="center"><?php echo $plano->gestor; ?></td>
                                                <td class="center"><?php echo $plano->superintendencia ?> </td>
                                               <td class="center"><a style="color: blue;" class="btn fa fa-folder-open-o" data-toggle="modal" data-target="#myModal" href="<?= site_url('Planos/manutencao_acao_concluidos_lista_setores/'.$plano->idplanos.'/'.$setore_selecionado); ?>"><?= lang('Abrir') ?></a></td>
                                             
                                                
                                                
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

              
                             
            </div>

        </div>
    </div>
</div>



