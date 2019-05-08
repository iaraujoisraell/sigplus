<?php include 'conexao_oracle.php'; ?>
<?php //include 'querys_tasy_oracle/query_saldo_estoque.php'; ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Saldo Estoque
        <small>Dashboard</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Saldo Estoque</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <?php
/*
 * SALDO DEVEDOR HMU
 */

    $saldo_estoque_hmu = oci_parse($ora_conexao,$query_saldo_estoque_hmu);
    oci_execute($saldo_estoque_hmu, OCI_NO_AUTO_COMMIT);

    // $emprestimo_entrada = array();
    $soma_qt_material = 0;
    $soma_custo_medio = 0;
    
        ?>
      <div class="box-footer">
           <h3>HMU</h3>
              <div class="row">
                  <?php
                  $cont = 1;
                  $soma_saldo_farmacia = 0;
                  $soma_saldo_almoxarifado = 0;
                  $soma_saldo_nutri = 0;
                   while (($row_sd_hmu = oci_fetch_array($saldo_estoque_hmu, OCI_BOTH)) != false)
                    {
                    $ie_valor = $row_sd_hmu[0];   
                    $cd_local_estoque = $row_sd_hmu[3];
                    
                    if($ie_valor == 0){
                    if($cd_local_estoque == 93){
                        $ds_local_almoxarifado = "ALMOXARIFADO";
                        $cor_almoxarifado = "aqua";
                        $fa_almoxarifado = "th";
                        
                        $saldo_almoxarifado = $row_sd_hmu[4];  
                        $ds_base = $row_sd_hmu[2];  
                       
                        $soma_saldo_almoxarifado += $saldo_almoxarifado;
                        
                    }else if($cd_local_estoque == 69){
                        $ds_local_nutri = "NUTRIÇÃO";
                        $cor_nutri = "yellow";
                        $fa_nutri = "apple";
                        
                        $saldo = $row_sd_hmu[4];  
                        $ds_base = $row_sd_hmu[2];  
                        $soma_saldo_nutri += $saldo;
                    }else if (($cd_local_estoque != 69)||($cd_local_estoque != 93)){
                       $ds_local = "FARMÁCIA";
                        $cor_far = "green";
                        $fa_far = "plus-square";
                        
                         $saldo = $row_sd_hmu[4];  
                        $cd_local_estoque = $row_sd_hmu[3];
                        $soma_saldo_farmacia += $saldo;
                        
                    }
                    
                    }else{
                        $total = $row_sd_hmu[4];  
                    }
                    
                    ?>
                  
                  
                    <?php
                $cont++;
                   }
                  ?>   
                  <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-<?php echo $cor_far; ?>"><i class="fa fa-<?php echo $fa_far; ?>"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text"><?php echo $ds_local; ?></span>
                          <span class="info-box-number">R$ <?php echo number_format($soma_saldo_farmacia, 2, ',', '.'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                    
                <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-<?php echo $cor_nutri; ?>"><i class="fa fa-<?php echo $fa_nutri; ?>"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text"><?php echo $ds_local_nutri; ?></span>
                          <span class="info-box-number">R$ <?php echo number_format($soma_saldo_nutri, 2, ',', '.'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                  
                  
                  <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-<?php echo $cor_almoxarifado; ?>"><i class="fa fa-<?php echo $fa_almoxarifado; ?>"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text"><?php echo $ds_local_almoxarifado; ?></span>
                          <span class="info-box-number">R$ <?php echo number_format($soma_saldo_almoxarifado, 2, ',', '.'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                  
                  <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-h-square"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text">TOTAL</span>
                          <span class="info-box-number">R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <!-- /.info-box -->
                    </div>
                
                
              </div>
              <!-- /.row -->
            </div>
      <!-- /.row -->
      <!-- /.DIV EXIBE OS SALDOS DEVEDOR E A RECEBER MAIS DETALHADO -->
       
      <br>
      
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
           <div class="box box-success">
           <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li class="active"><a href="#saida_hmu" data-toggle="tab">HMU</a></li>
            <!--  <li><a href="#saida_hupl" data-toggle="tab">HUPL</a></li>-->
              <li class="pull-left header"><i class="fa fa-inbox"></i> Saldo Estoque</li>
            </ul>
            <div class="tab-content no-padding">
              <!-- Morris chart - Sales -->
              <div class="box-body chart-responsive tab-pane active" id="saida_hmu" style="position: relative; height: 300px;">
                  <div class="chart" id="saida_hmu" style="height: 300px;"></div>
              </div>
             <!--  <div class="box-body chart-responsive tab-pane " id="saida_hupl" style="position: relative; height: 300px;">
                  <div class="chart" id="saida_hupl" style="height: 300px;"></div>
              </div>-->
             
            </div>
          </div>
          </div>
          
          
          <?php
          
          ?>
         
          <!-- /.nav-tabs-custom -->

          <!-- Chat box -->
         
          <!-- /.box (chat box) -->

         

        
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-12 connectedSortable">

           
         
                   <?php
/*
 * SALDO DEVEDOR HMU
 */

                   
        $query_saldo_estoque_hmu_todos = "select 0 ie_valor, 0 cd_base, nvl(substr(a.DS_LOCAL_ESTOQUE,1,255), 'Não Informado')  ds_base, 
        to_char(a.CD_LOCAL_ESTOQUE) nm_drill, 
         sum( VL_ESTOQUE) VL_ESTOQUE,
         sum( VL_ESTOQUE_MEDIO) VL_ESTOQUE_MEDIO,
         sum( VL_CONSUMO) VL_CONSUMO,
        TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)) VL_3,
         sum( VL_EXCEDENTE) VL_EXCEDENTE,
        ((TASY.dividir(sum(VL_ESTOQUE), sum(VL_CONSUMO))) * 30) VL_5,
        TASY.dividir(((SUM(VL_ESTOQUE) - SUM(VL_CONSUMO)) * 100), SUM(VL_ESTOQUE)) VL_6,
        to_char( TRUNC(TASY.DIVIDIR(30,TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)))), '0' ) VL_7,
         1 from TASY.eis_saldo_estoque_v a
         where 1 = 1 
        AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
        and a.CD_LOCAL_ESTOQUE not in (1)
         and DS_LOCAL_ESTOQUE is not null 
         and DT_REFERENCIA between TO_DATE('01/08/2018','dd/mm/yyyy') and TO_DATE('31/08/2018 23:59:59','dd/mm/yyyy hh24:mi:ss')
         group by 1, a.DS_LOCAL_ESTOQUE
        ,to_char(a.CD_LOCAL_ESTOQUE)
         union select 1 ie_valor, 0 cd_base, 'Total' ds_base, 
        ' ' nm_drill, 
         sum( VL_ESTOQUE) VL_ESTOQUE,
         sum( VL_ESTOQUE_MEDIO) VL_ESTOQUE_MEDIO,

         sum( VL_CONSUMO) VL_CONSUMO,

        TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)) VL_3,
        sum( VL_EXCEDENTE) VL_EXCEDENTE,
        ((TASY.dividir(sum(VL_ESTOQUE), sum(VL_CONSUMO))) * 30) VL_5,
        TASY.dividir(((SUM(VL_ESTOQUE) - SUM(VL_CONSUMO)) * 100), SUM(VL_ESTOQUE)) VL_6,
        to_char( TRUNC(TASY.DIVIDIR(30,TASY.DIVIDIR(SUM(VL_CONSUMO),SUM(VL_ESTOQUE_MEDIO)))), '0' ) VL_7,
         1 from TASY.eis_saldo_estoque_v a
         where 1 = 1 
        AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
          and a.CD_LOCAL_ESTOQUE not in (1)
         and DS_LOCAL_ESTOQUE is not null 
         and DT_REFERENCIA between TO_DATE('01/08/2018','dd/mm/yyyy') and TO_DATE('31/08/2018 23:59:59','dd/mm/yyyy hh24:mi:ss')
         group by 1 
         order by 1, 5  Desc ";
    $saldo_estoque_hmu2 = oci_parse($ora_conexao,$query_saldo_estoque_hmu_todos);
    oci_execute($saldo_estoque_hmu2, OCI_NO_AUTO_COMMIT);

    // $emprestimo_entrada = array();
    $soma_qt_material = 0;
    $soma_custo_medio = 0;
             // echo $ds_base.'<br>';
                    
                    ?>
              
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Saldo de Estoque HMU</h3>
                </div>
              <div class="box-body">
                <table style="color: #000000;" id="example1" class="table ">
                <thead>
                <tr>
                  <th>CD </th>
                  <th>LOCAL ESTOQUE</th>
                  <th>VL ESTOQUE</th>
                </tr>
                </thead>
                <tbody>
                   <?php
                   $cont = 1;
                   while (($row_sd_hmu2 = oci_fetch_array($saldo_estoque_hmu2, OCI_BOTH)) != false)
                    {
                    $saldo = $row_sd_hmu2[4];  
                    $cd_local_estoque = $row_sd_hmu2[3];
                    $ds_base = $row_sd_hmu2[2];                
                   ?>
                    <tr>
                      <td><?php echo $cd_local_estoque; ?></td>
                      <td><?php echo $ds_base; ?></td>
                      <td>R$ <?php echo number_format($saldo, 2, ',', '.');; ?> </td>
                    </tr>
                   <?php
                   }
                   ?>
                </tbody>
                <tfoot>
                <tr>
                 
                  <th>CD </th>
                  <th>LOCAL ESTOQUE</th>
                  <th>VL ESTOQUE</th>
                </tr>
                </tfoot>
              </table>
              </div>    
                    
            </div>     
                 
                
           

        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>

 