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
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('LISTA DE AÇÕES DO REGISTRO'); ?>
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
                                    <?= lang("Data da ATA", "sldate"); ?>
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
                       
                        
                        
                         $somma_acoes_pendentes = 0;
                        $somma_acoes_atrasadas_5 = 0;
                        $somma_acoes_concluidas = 0;
                        $somma_acoes_concluidas_fora_prazo = 0;
                        $somma_acoes_atrasadas_10 = 0;
                        $somma_total_acal = 0;
                        $somma_acoes_atrasadas_15 = 0;
                        $tota_atrasadas_total = 0;
                        
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
                                        
                                        $somma_acoes_concluidas += 1;
                                    }

                                    if($adata_entrega > $adata_prazo){
                                         $somma_acoes_concluidas_fora_prazo_setor +=1;
                                         $somma_acoes_concluidas_fora_prazo+=1;
                                    }

                                }else if($astatus == 'PENDENTE'){

                                    if($dataEscolhida <= $adata_prazo){
                                        $somma_acoes_pendentes_setor += 1;
                                        $somma_acoes_pendentes += 1;
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
                                                $somma_acoes_atrasadas_5 += 1;
                                            }else if(($dias_setor < '-5') && ($dias_setor >= '-10')){
                                               $somma_acoes_atrasadas_10_setor +=1;
                                               $somma_acoes_atrasadas_10 += 1;
                                            }else if($dias_setor < '-10') {
                                                $somma_acoes_atrasadas_15_setor +=1;
                                                $somma_acoes_atrasadas_15 += 1;
                                            }

                                        }
                                }
                                                    
                                 $somma_total_acal_setor+=1;    
                                 
                                 
                         } //fim for $acoes_setor
                         $tota_atrasadas_setor = $somma_acoes_atrasadas_5_setor + $somma_acoes_atrasadas_10_setor + $somma_acoes_atrasadas_15_setor;
                         
                         //aqui os inserts
                         $data_historico_setor = array(
                            'data' => $dataEscolhida,
                            'setor' => $setor_selecionado, 
                            'projeto' => $projetos_usuario,
                            'superintendente' => $setor->superintendencia,
                            'total_acoes' => $somma_total_acal_setor,
                            'total_atrasados' => $tota_atrasadas_setor,
                            'total_concluido' => $somma_acoes_concluidas_setor,
                            'total_fora_prazo' => $somma_acoes_concluidas_fora_prazo_setor,
                            'total_pendentes' => $somma_acoes_pendentes_setor,
                            'atrasado_5' => $somma_acoes_atrasadas_5_setor,
                            'atrasado_10' => $somma_acoes_atrasadas_10_setor,
                            'atrasado_15' => $somma_acoes_atrasadas_15_setor
                        );
                         
                       $this->atas_model->add_Historico_Acoes($data_historico_setor);
                        
                         /*
                         
                         $this->atas_model->add_Historico_Acoes($data_historico_concluido_fp);
                         $this->atas_model->add_Historico_Acoes($data_historico_pendente);
                         $this->atas_model->add_Historico_Acoes($data_historico_atrasado_5);
                         $this->atas_model->add_Historico_Acoes($data_historico_atrasado_10);
                         $this->atas_model->add_Historico_Acoes($data_historico_atrasado_15);
                         
                          * 
                          */
                         ?>
                         
                        <div class="col-md-12">
                            <div class="form-group">
                                <center>
                                    <h2>   <?PHP ECHO $setor_selecionado; ?> - <?PHP ECHO $setor->superintendencia; ?></h2>
                                </center>
                            </div>
                        </div>
                        <?php echo form_hidden('setor', $setor_selecionado); ?>
                        <?php echo form_hidden('superintendencia', $setor->superintendencia); ?> 
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("concluidas", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_concluidas_setor), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Conc. F. do Prazo", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_concluidas_fora_prazo_setor), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Pendentes", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_pendentes_setor), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                         <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +5", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_atrasadas_5_setor), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +10", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_atrasadas_10_setor), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +15", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_atrasadas_15_setor), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Total Ação", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_total_acal_setor), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                         
                         <?php
                     } //fim for $setores
                        
                     $tota_atrasadas_total = $somma_acoes_atrasadas_5 + $somma_acoes_atrasadas_10 + $somma_acoes_atrasadas_15;
                     //aqui os inserts
                         $data_historico_total_resumo = array(
                            'data' => $dataEscolhida,
                            'setor' => 'TODOS', 
                            'resumo' => '1', 
                            'projeto' => $projetos_usuario,
                            'superintendente' => 'TODOS',
                            'total_acoes' => $somma_total_acal,
                            'total_atrasados' => $tota_atrasadas_total,
                            'total_concluido' => $somma_acoes_concluidas,
                            'total_fora_prazo' => $somma_acoes_concluidas_fora_prazo,
                            'total_pendentes' => $somma_acoes_pendentes,
                            'atrasado_5' => $somma_acoes_atrasadas_5,
                            'atrasado_10' => $somma_acoes_atrasadas_10,
                            'atrasado_15' => $somma_acoes_atrasadas_15
                        );
                         
                         $this->atas_model->add_Historico_Acoes($data_historico_total_resumo);
                    ?>
                        
                        
                        
                         <?php echo form_close(); ?>
                   
                        <div class="col-md-12">
                            <div class="form-group">
                                <center>
                                    <h2>  TOTAL GERAL  </h2>
                                </center>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Total concluidas", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_concluidas), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Conc. F. do Prazo", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_concluidas_fora_prazo), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Pendentes", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_pendentes), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                         <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +5", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_atrasadas_5), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +10", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_atrasadas_10), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Atrasadas +15", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_acoes_atrasadas_15), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= lang("Total Ação", "slelaboracao"); ?>
                                <?php echo form_input('nome_elaboracao', (isset($_POST['nome_elaboracao']) ? $_POST['nome_elaboracao'] : $somma_total_acal), ' class="form-control input-tip" disable id="slelaboracao"'); ?>
                            </div>
                        </div> 
                        <center>
                            <div class="col-md-12">
                                <a  class="btn btn-danger" href="<?= site_url('Historico_Acoes'); ?>"><?= lang('Fechar') ?></a>
                                 </div>
                        </center>
                       

                    </div>
                    

                             
            </div>

        </div>
    </div>
</div>



