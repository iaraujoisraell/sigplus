   
<div class="row">
   
  
        
    <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Agenda</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
    <!-- PRODUÇÃO POR CATEGORIA -->
     <div class="table-responsive">
      <table class="table dt-table scroll-responsive table table-bordered table-striped" data-order-col="2" data-order-type="desc">

          <thead>
            <tr>
              <th>#</th>
              <th>Hora</th>
              <th>Data </th>
              <th>Paciente</th>
              <th>Convênio</th>
              <th>Tipo</th>
              <th>Profissional</th>
              <th>Status</th>
             <!-- <th>Status Repasse</th> -->

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
                  $finished = $dados_medico['finished'];
                  $type = $dados_medico['type'];
                  $color = $dados_medico['color'];

                  $tipo = "<label class='btn btn-$color'> $type </label>";
                  
                  if($finished == 1){
                      $situacao = "<label class='btn btn-success'> ATENDIDO </label>";
                  }else{
                      $situacao = "<label class='btn btn-warning'> AGUARDANDO </label>";
                  }
                 
                  
                  ?>
                  <tr>
                      <td><?php echo $cont++; ?></td>
                      <td><?php echo $start_hour; ?></td>
                      <td><?php echo _d($data_atendimento); ?></td>
                      <td><?php echo $paciente; ?></td>
                      <td><?php echo $convenio; ?></td>
                      <td><?php echo $type; ?></td>
                      
                      <td><?php echo $nome_profissional; ?></td>
                      <td><?php echo $situacao; ?></td>

                </tr>
                  <?php
              }
              ?>
          </tbody>
          
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