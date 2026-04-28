<?php
$cont_n_parametrizado = 0;
$soma_total_valor_s = 0;
$cont_total = 0;
foreach ($atendimentos as $dados_medico) {
  $item_id = $dados_medico['item_id'];
  $medico_id= $dados_medico['medico_id'];
  $valor_medico = $dados_medico['valor_medico'];

  
  if($valor_medico == 0.00){
  $tabela_procedimento_medico_row = $this->invoices_model->get_medico_procedimento_invoice_item($item_id, $medico_id);
  $regra_procedimento = count($tabela_procedimento_medico_row); 
  if($regra_procedimento == 0){
      $cont_n_parametrizado ++;
  }
  }
  
   $soma_total_valor_s += $valor_medico;
   $cont_total++;
}                 

?>
   
<div class="row">
   
    <div class="row">
                
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-hospital-o"></i></span>
                    <?php 
                     
                    ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Atendimentos no Período</span>
                      <span class="info-box-number"><?php echo $cont_total; ?></span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Valor para Repasse</span>
                      <span class="info-box-number"><?php echo app_format_money($soma_total_valor_s, 'R$'); ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
            </div>

            <div class="col-md-4">
                 <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-close"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Procedimentos não parametrizados</span>
                      <span class="info-box-number"><?php echo $cont_n_parametrizado; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
             </div>
            
          </div>
        
    <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Produção Faturada por Procedimento</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
    <!-- PRODUÇÃO POR CATEGORIA -->
     <div class="table-responsive">
      <table class="table dt-table scroll-responsive table table-bordered table-striped" data-order-col="2" data-order-type="desc">

          <thead>
            <tr>
              <th>#</th>
              <th>Profissional</th>
              <th>Data Atendimento</th>
              <th>Paciente</th>
              <th>Convênio</th>
              <th>Procedimento</th>
              <th>Valor </th>
              <th>Status</th> 

            </tr>
          </thead>
          <tbody>
           <?php
           $soma_total_valor = 0;
           $cont = 1;
           foreach ($atendimentos as $dados_medico) {
                  $nome_profissional= $dados_medico['nome_profissional'];
                  $data_atendimento = $dados_medico['data_atendimento'];
                  $start_hour = $dados_medico['start_hour'];
                  $convenio = $dados_medico['convenio'];
                  $procedimento = $dados_medico['procedimento'];
                  $categoria = $dados_medico['categoria'];
                  $paciente = $dados_medico['paciente'];                                  
                  $valor_medico = $dados_medico['valor_medico'];
                  $status = $dados_medico['status'];

                  if($status == 1){
                      $s_status = 'CONFIRMADO';
                  }else{
                      $s_status = 'PENDENTE';
                  }

                  $item_id = $dados_medico['item_id'];
                  $medico_id= $dados_medico['medico_id'];
                  
                  $alerta = "";
                  if($valor_medico == 0.00){
                  $tabela_procedimento_medico_row = $this->invoices_model->get_medico_procedimento_invoice_item($item_id, $medico_id);
                  $regra_procedimento = count($tabela_procedimento_medico_row); 
                  if($regra_procedimento == 0){
                      $alerta = "<i class='fa fa-exclamation-triangle btn btn-danger' title='Procedimento sem parametrização.'></i>";
                  }
                  }
                  
                  $soma_total_valor += $valor_medico;
                  
                  ?>
                  <tr>
                      <td><?php echo $cont++; ?></td>
                      <td><?php echo $nome_profissional; ?></td>
                      <td><?php echo _d($data_atendimento).' '.$start_hour; ?></td>
                      <td><?php echo $paciente; ?></td>
                      <td><?php echo $convenio; ?></td>
                      <td><?php echo $procedimento.' [ '.$categoria.' ] '. $alerta; ?></td>
                      
                      <td><?php echo app_format_money($valor_medico, 'R$'); ?></td>
                      <td><?php echo $s_status; ?></td>
                </tr>
                  <?php
              }
              ?>
          </tbody>
          <tfoot>
            <tr>

                <td colspan="6">Total</td>
              <td><?php echo app_format_money($soma_total_valor, 'R$'); ?> </td>

            </tr>    

            </tfoot>
        </table>
      </div>
      <!-- /.table-responsive -->
    </div>       
  </div>
            
   
    <?php //hooks()->do_action('after_dashboard_top_container'); ?>
</div>



<script src="<?php echo base_url() ?>assets/menu/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/menu/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>


<script>
  $(function () {
   
    
    
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      "aaSorting": [[0, "desc"]]
    })
    
    
     
  })
</script>