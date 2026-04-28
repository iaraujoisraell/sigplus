<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <?php
            $dados_caixa = $this->caixas_model->get_caixa_registro_atual($caixa_atual); 
            $nome_caixa = $dados_caixa->caixa;
            $saldo_inicial = $dados_caixa->saldo;
            
           
          
            ?>
         
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                   <h4 class="no-margin"><h3><?php echo $title; ?>  <label class="btn btn-primary pull-right"><?php echo $nome_caixa; ?></label></h3></h4>
                  <hr class="hr-panel-heading" />
                  <h3>Registros do Caixa</h3>
                     <table class="table">
                         <tr>
                            <td><h4><b>Data</b> </h4></td>
                            <td><h4><b>Hora Início</b> </h4></td>
                            <td><h4><b>Hora Fim</b> </h4></td>
                            <td><h4><b>Usuário</b> </h4></td>
                            <td><h4><b>Saldo</b> </h4></td>
                            <td><h4><b>Ações</b> </h4></td>
                         </tr>
                        <?php
                     
                        $registros = $this->caixas_model->get_caixa_registro_by_id($caixa_atual); 
                        foreach ($registros as $aRow) {
                            $registro_id         = $aRow['id'];
                            $data_abertura       = $aRow['data_abertura'];
                            $data_tratada        = date('Y-m-d', strtotime($data_abertura) );
                            $hora_inicio_tratada = date('H:i', strtotime($data_abertura) );
                            
                            $saldo              = $aRow['saldo'];
                            
                            $usu_abertura        = $aRow['usuario_abertura'];
                            $member_abrtura = $this->staff_model->get($usu_abertura);
                            $nome_abertura = $member_abrtura->firstname.' '.$member_abrtura->lastname;
                            
                            $data_fechamento = $aRow['data_fechamento'];
                            $hora_fim_tratada = date('H:i', strtotime($data_fechamento) );
                            
                            $usu_fechamento        = $aRow['usuario_fechamento'];
                            $member_fechamento = $this->staff_model->get($usu_fechamento);
                            $nome_fechamento = $member_fechamento->firstname.' '.$member_fechamento->lastname;
                        ?>  
                        <tr>
                         <td><h4><?php echo _d($data_tratada); ?> </h4></td>
                         <td><h4><?php echo $hora_inicio_tratada; ?> </h4></td>
                         <td><h4><?php echo $hora_fim_tratada; ?> </h4></td>
                         <td><h4><?php echo $nome_fechamento; ?> </h4></td>
                         <td><h4><?php echo $saldo; ?> </h4></td>
                         <td>
                             <button onclick="filtraMovimentos(<?php echo $registro_id; ?>);" class="btn btn-warning" title="Extrato Movimentação "><i class="fa fa-rotate-right"></i></button>
                             <a href="<?php echo admin_url("caixas/detalhes_caixa/$registro_id"); ?>" target="_blank" class="btn btn-primary" title="DETALHES"><i class="fa fa-list-ul"></i></a>
                         </td>
                        </tr>
                    
                    <?php
                        
                    
                        }
                     
                     ?>
                     </table>
                  
                  
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="no-margin"><?php echo 'Movimentações de Caixa'; ?></h4>
                  <hr class="hr-panel-heading" />
                  <div id="div_table_movimentacao">
                      
                  </div>
                  
                </div>
               </div>
            </div>
         </div>
        
      </div>
      <div class="btn-bottom-pusher"></div>
   </div>

<script>
    function filtraMovimentos(registro_id) {
        var registro_id = registro_id;
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("caixas/retorno_table_movimentacao"); ?>",
        data: {
          registro_id: registro_id,
        },
        success: function(data) {
          $('#div_table_movimentacao').html(data);
        }
      });
    }
</script>

<?php init_tail(); ?>
