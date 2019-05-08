<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    
     <?php
        include './conexao_stage.php';
        $query = "select tasy,
       BEN_ATIVO_CONT_ATIVO as infomed,
       (tasy - BEN_ATIVO_CONT_ATIVO) as diferenca,
       BEN_ATIVO_CONT_INATIVO as contratos_inativos

from (select (select count(*) 
     from pls_segurado@tasy_prod s
     where s.dt_rescisao is null) as tasy,
      (select count(*) 
      from inf_beneficiarios@infomed b 
      where b.ben_status = 'A' 
      and b.ben_plc_cbn_cod_contrato in (select cb.cbn_cod_contrato from inf_contratos_de_beneficiario@infomed cb where cb.cbn_status = 'A' and cb.cbn_tipo_contrato = 'L')
      ) as BEN_ATIVO_CONT_ATIVO,
      (select count(*) 
      from inf_beneficiarios@infomed b 
      where b.ben_status = 'A' 
      and b.ben_plc_cbn_cod_contrato in (select cb.cbn_cod_contrato from inf_contratos_de_beneficiario@infomed cb where cb.cbn_status = 'R' and cb.cbn_tipo_contrato = 'L')
      ) as BEN_ATIVO_CONT_INATIVO
from dual)";
        $result_migracao = oci_parse($ora_conexao,$query);
        oci_execute($result_migracao, OCI_NO_AUTO_COMMIT);
        while (($row_q = oci_fetch_array($result_migracao, OCI_BOTH)) != false)
        {
            $tasy = $row_q[0];
            $infomed = $row_q[1];  
            $diferenca = $row_q[2];
            $contratos = $row_q[3];
        
          //  echo 'Infomed : '.$infomed;
        }
      
        
         
    ?>
    
    
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $infomed; ?></h3>

              <p>INFOMED</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a  class="small-box-footer">Beneficiários Ativos</a>
          </div>
        </div>  
          
         <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $contratos; ?></h3>

              <p>Beneficiários Ativos em Contratos Inativos </p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a  class="small-box-footer">Infomed</a>
          </div>
        </div> 
          
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $tasy; ?></h3>

              <p>TASY (<?php echo $diferenca; ?>)</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a  class="small-box-footer">Beneficiários Ativos</a>
          </div>
        </div>
      
          
        
        <!-- ./col -->
      </div>
  
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-12 connectedSortable">
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
               google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

              function drawChart() {
                var data = google.visualization.arrayToDataTable([
                  ['Data', 'Infomed', 'Tasy'],
                  <?php
                  foreach ($registros as $registro) {
                    $data_registro = $registro->data_registro;
                    $infomed2 = $registro->infomed;
                    $tasy2 = $registro->tasy;
                   ?>
      
                   ['<?php echo date('d/m/Y H:i:s', strtotime($data_registro)) ; ?>',  <?php echo $infomed2; ?>,      <?php echo $tasy2; ?>],  
                           
                <?php } ?>
                 
                 
                ]);

                var options = {
                  title: 'Performance Infomed vs Tasy',
                  hAxis: {title: 'Acompanhamento Paralelo Tasy x Infomed',  titleTextStyle: {color: '#333'}},
                  vAxis: {minValue: 0}
                };

                 var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
              }
            </script>
         
    
        <div id="curve_chart" style="width: 100%; height: 500px;"></div>

        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>