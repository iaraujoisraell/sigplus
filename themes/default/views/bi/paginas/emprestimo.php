<?php include 'conexao_oracle.php'; ?>
<?php include 'querys_tasy_oracle/query_emprestimo.php'; ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Empréstimos
        <small>Dashboard</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Empréstimo</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
          <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h4>R$ <?php echo number_format($custo_medio, 2, ',', '.'); ?></h4>
              <p><?php echo $qt_material; ?> Itens</p>
              <p>SD DEV. HMU   </p>
            </div>
            <div class="icon">
              <i class="fa fa-arrow-right"></i>
            </div>
            
           <div class="icon">
              <i class="fa fa-arrow-right"></i>
            </div>
            <a href="#" data-toggle="modal" data-target="#modal-saldo_devedor_hmu" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          
          <!-- /.box -->
       
            
          </div>
        </div>
          <?php
            /*
             * SALDO DEVEDOR HMU
             */
             $query_sd_devedor_hmu_detalhes = "select   
                 e.nr_emprestimo,
                 a.cd_material,
                 substr(TASY.obter_desc_material(a.cd_material),1,255) ds_material,
                 a.qt_emprestimo,
                 SD.VL_CUSTO_MEDIO,
          (a.qt_material * SD.VL_CUSTO_MEDIO) AS CUSTO_TOTAL
        from     emprestimo_material a
        inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
        INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
        where    TASY.obter_situacao_emprestimo_mat(a.nr_emprestimo, a.nr_sequencia) in('P','A')
                 and e.cd_local_estoque = 65
                 and e.dt_emprestimo >= '01/06/2018'
                 AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
                 AND SD.CD_LOCAL_ESTOQUE = 65
                 -- AND NOT EXISTS (SELECT CD_MATERIAL FROM SALDO_ESTOQUE SD WHERE SD.DT_MESANO_REFERENCIA = '01/07/2018' AND SD.CD_MATERIAL = A.CD_MATERIAL AND SD.CD_LOCAL_ESTOQUE = 65 )
                 and e.ie_tipo = 'E'
                 AND E.IE_SITUACAO = 'A'
        order by e.nr_emprestimo";
          
                $saldo_devedor_hmu = oci_parse($ora_conexao,$query_sd_devedor_hmu_detalhes);
                oci_execute($saldo_devedor_hmu, OCI_NO_AUTO_COMMIT);

                // $emprestimo_entrada = array();
                $soma_qt_material = 0;
                $soma_custo_medio = 0;
            ?>
            <div class="modal fade" id="modal-saldo_devedor_hmu">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Empréstimos de Outros Hospitais para o  HMU</h4>
              </div>
              <div class="modal-body">
                <table style="color: #000000;" id="example2" class="table ">
                <thead>
                <tr>
                  <th>CD EMP</th>
                  <th>DESCRIÇÃO</th>
                  <th>QTDE</th>
                  <th>C.M.</th>
                  
                </tr>
                </thead>
                <tbody>
               <?php
               $soma_tde_material_sd_dev_hmu = 0;
               while (($row_sd_hmu = oci_fetch_array($saldo_devedor_hmu, OCI_BOTH)) != false)
                    {
                    $cd_emprestimo = $row_sd_hmu[0];  
                    $cd_material = $row_sd_hmu[1];
                    $ds_material = $row_sd_hmu[2];
                    $qt_material = $row_sd_hmu[3];
                    $custo_medio = $row_sd_hmu[5];
                    $custo_medio = number_format($custo_medio, 2);
                    
                    $soma_tde_material_sd_dev_hmu += $qt_material;
                  
               ?>
                <tr>
                  <td><?php echo $cd_emprestimo; ?></td>
                  <td><?php echo $ds_material; ?></td>
                  <td><?php echo $qt_material; ?></td>
                  <td> <?php echo $custo_medio; ?></td>
                </tr>
               <?php
               }
               ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>CD EMP</th>
                  <th>CD MAT</th>
                  <th>QTDE</th>
                  <th>SD</th>
                </tr>
                </tfoot>
              </table>
              </div>
                Total Material : <?php echo $soma_tde_material_sd_dev_hmu; ?>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
          
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h4> R$ <?php echo number_format($custo_medio_sr, 2, ',', '.'); ?></h4>
              <p><?php echo $qt_material_sr; ?> itens</p>
              <p>SD À REC. HMU </p>
            </div>
            <div class="icon">
              <i class="fa fa-arrow-left"></i>
            </div>
             <a href="#" data-toggle="modal" data-target="#modal-saldo_receber_hmu" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
          
          </div>
        </div>
          
          <?php
            /*
             * SALDO DEVEDOR HMU
             */
             $query_sd_receber_hmu_detalhes = "select   
                 e.nr_emprestimo,
                 a.cd_material,
                 substr(TASY.obter_desc_material(a.cd_material),1,255) ds_material,
                 a.qt_emprestimo,
                 SD.VL_CUSTO_MEDIO,
          (a.qt_material * SD.VL_CUSTO_MEDIO) AS CUSTO_TOTAL
        from     emprestimo_material a
        inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
        INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
        where    TASY.obter_situacao_emprestimo_mat(a.nr_emprestimo, a.nr_sequencia) in('P','A')
                 and e.cd_local_estoque = 65
                 and e.dt_emprestimo >= '01/06/2018'
                 AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
                 AND SD.CD_LOCAL_ESTOQUE = 65
                 -- AND NOT EXISTS (SELECT CD_MATERIAL FROM SALDO_ESTOQUE SD WHERE SD.DT_MESANO_REFERENCIA = '01/07/2018' AND SD.CD_MATERIAL = A.CD_MATERIAL AND SD.CD_LOCAL_ESTOQUE = 65 )
                 and e.ie_tipo = 'S'
                 AND E.IE_SITUACAO = 'A'
        order by e.nr_emprestimo";
          
                $saldo_receber_hmu = oci_parse($ora_conexao,$query_sd_receber_hmu_detalhes);
                oci_execute($saldo_receber_hmu, OCI_NO_AUTO_COMMIT);

                // $emprestimo_entrada = array();
                $soma_qt_material = 0;
                $soma_custo_medio = 0;
            ?>
            <div class="modal fade" id="modal-saldo_receber_hmu">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Empréstimos Para Outros Hospitais feitos pelo  HMU</h4>
              </div>
              <div class="modal-body">
                <table style="color: #000000;" id="example1" class="table ">
                <thead>
                <tr>
                  <th>CD EMP</th>
                  <th>DESCRIÇÃO</th>
                  <th>QTDE</th>
                  <th>C.M.</th>
                  
                </tr>
                </thead>
                <tbody>
               <?php
               $soma_tde_material_sd_dev_hmu2 = 0;
               while (($row_sr_hmu = oci_fetch_array($saldo_receber_hmu, OCI_BOTH)) != false)
                    {
                    $cd_emprestimo2 = $row_sr_hmu[0];  
                    $cd_material = $row_sr_hmu[1];
                    $ds_material2 = $row_sr_hmu[2];
                    $qt_material2 = $row_sr_hmu[3];
                    $custo_medio2 = $row_sr_hmu[5];
                    $custo_medio2 = number_format($custo_medio2, 2);
                    
                    $soma_tde_material_sd_dev_hmu2 += $qt_material2;
                  
               ?>
                <tr>
                  <td><?php echo $cd_emprestimo2; ?></td>
                  <td><?php echo $ds_material2; ?></td>
                  <td><?php echo $qt_material2; ?></td>
                  <td> <?php echo $custo_medio2; ?></td>
                </tr>
               <?php
               }
               ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>CD EMP</th>
                  <th>CD MAT</th>
                  <th>QTDE</th>
                  <th>SD</th>
                </tr>
                </tfoot>
              </table>
              </div>
                Total Material : <?php echo $soma_tde_material_sd_dev_hmu2; ?>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
          
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
                <h4>R$ <?php echo number_format($custo_medio_sd_hupl, 2, ',', '.'); ?></h4>
              <p><?php echo $qt_material_sd_hupl; ?> itens</p>
              
              
              <p>SD DEV. HUPL </p>
            </div>
            <div class="icon">
              <i class="fa fa-arrow-right"></i>
            </div>
                  <a href="#" data-toggle="modal" data-target="#modal-saldo_devedor_hupl" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
        
          </div>
        </div>
         <?php
            /*
             * SALDO DEVEDOR HUPL
             */
             $query_sd_devedor_hupl_detalhes = "select   
                 e.nr_emprestimo,
                 a.cd_material,
                 substr(TASY.obter_desc_material(a.cd_material),1,255) ds_material,
                 a.qt_emprestimo,
                 SD.VL_CUSTO_MEDIO,
          (a.qt_material * SD.VL_CUSTO_MEDIO) AS CUSTO_TOTAL
        from     emprestimo_material a
        inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
        INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
        where    TASY.obter_situacao_emprestimo_mat(a.nr_emprestimo, a.nr_sequencia) in('P','A')
                 and e.cd_local_estoque = 19
                 and e.dt_emprestimo >= '01/06/2018'
                 AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
                 AND SD.CD_LOCAL_ESTOQUE = 19
                 -- AND NOT EXISTS (SELECT CD_MATERIAL FROM SALDO_ESTOQUE SD WHERE SD.DT_MESANO_REFERENCIA = '01/07/2018' AND SD.CD_MATERIAL = A.CD_MATERIAL AND SD.CD_LOCAL_ESTOQUE = 65 )
                 and e.ie_tipo = 'E'
                 AND E.IE_SITUACAO = 'A'
        order by e.nr_emprestimo";
          
                $saldo_devedor_hupl = oci_parse($ora_conexao,$query_sd_devedor_hupl_detalhes);
                oci_execute($saldo_devedor_hupl, OCI_NO_AUTO_COMMIT);

                // $emprestimo_entrada = array();
                $soma_qt_material = 0;
                $soma_custo_medio = 0;
            ?>
            <div class="modal fade" id="modal-saldo_devedor_hupl">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Empréstimos de outros Hospitais para o HUPL</h4>
              </div>
              <div class="modal-body">
                <table style="color: #000000;" id="example3" class="table ">
                <thead>
                <tr>
                  <th>CD EMP</th>
                  <th>DESCRIÇÃO</th>
                  <th>QTDE</th>
                  <th>C.M.</th>
                  
                </tr>
                </thead>
                <tbody>
               <?php
               $soma_tde_material_sd_dev_hupl2 = 0;
               while (($row_sd_hupl = oci_fetch_array($saldo_devedor_hupl, OCI_BOTH)) != false)
                    {
                    $cd_emprestimo_hupl = $row_sd_hupl[0];  
                    $cd_material_hupl = $row_sd_hupl[1];
                    $ds_material_hupl = $row_sd_hupl[2];
                    $qt_material_hupl2 = $row_sd_hupl[3];
                    $custo_medio_hupl = $row_sd_hupl[5];
                    $custo_medio_hupl = number_format($custo_medio_hupl, 2);
                    
                    $soma_tde_material_sd_dev_hupl2 += $qt_material_hupl2;
                  
               ?>
                <tr>
                  <td><?php echo $cd_emprestimo_hupl; ?></td>
                  <td><?php echo $ds_material_hupl; ?></td>
                  <td><?php echo $qt_material_hupl2; ?></td>
                  <td> <?php echo $custo_medio_hupl; ?></td>
                </tr>
               <?php
               }
               ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>CD EMP</th>
                  <th>CD MAT</th>
                  <th>QTDE</th>
                  <th>SD</th>
                </tr>
                </tfoot>
              </table>
              </div>
                Total Itens : <?php echo $soma_tde_material_sd_dev_hupl2; ?>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        
        
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h4>R$ <?php echo number_format($custo_medio_sr_hupl, 2, ',', '.'); ?></h4>
              <p><?php echo $qt_material_sr_hupl; ?> itens</p>
              <p>SD À REC. HUPL </p>
            </div>
            <div class="icon">
              <i class="fa fa-arrow-left"></i>
            </div>
               <a href="#" data-toggle="modal" data-target="#modal-saldo_receber_Hupl" class="small-box-footer">Detalhes <i class="fa fa-arrow-circle-right"></i></a>
        
          </div>
        </div>
         
          <?php
            /*
             * SALDO DEVEDOR HUPL
             */
             $query_sd_receber_HUPL_detalhes = "select   
                 e.nr_emprestimo,
                 a.cd_material,
                 substr(TASY.obter_desc_material(a.cd_material),1,255) ds_material,
                 a.qt_emprestimo,
                 SD.VL_CUSTO_MEDIO,
          (a.qt_material * SD.VL_CUSTO_MEDIO) AS CUSTO_TOTAL
        from     emprestimo_material a
        inner join emprestimo e on e.nr_emprestimo = a.nr_emprestimo
        INNER JOIN SALDO_ESTOQUE SD ON SD.CD_MATERIAL = A.CD_MATERIAL
        where    TASY.obter_situacao_emprestimo_mat(a.nr_emprestimo, a.nr_sequencia) in('P','A')
                 and e.cd_local_estoque = 19
                 and e.dt_emprestimo >= '01/06/2018'
                 AND SD.DT_MESANO_REFERENCIA = (SELECT MAX(DT_MESANO_REFERENCIA) FROM SALDO_ESTOQUE S WHERE S.CD_LOCAL_ESTOQUE = E.CD_LOCAL_ESTOQUE AND S.CD_MATERIAL = A.CD_MATERIAL) 
                 AND SD.CD_LOCAL_ESTOQUE = 19
                 -- AND NOT EXISTS (SELECT CD_MATERIAL FROM SALDO_ESTOQUE SD WHERE SD.DT_MESANO_REFERENCIA = '01/07/2018' AND SD.CD_MATERIAL = A.CD_MATERIAL AND SD.CD_LOCAL_ESTOQUE = 65 )
                 and e.ie_tipo = 'S'
                 AND E.IE_SITUACAO = 'A'
        order by e.nr_emprestimo";
          
                $saldo_receber_hupl = oci_parse($ora_conexao,$query_sd_receber_HUPL_detalhes);
                oci_execute($saldo_receber_hupl, OCI_NO_AUTO_COMMIT);

                // $emprestimo_entrada = array();
                $soma_qt_material = 0;
                $soma_custo_medio = 0;
            ?>
            <div class="modal fade" id="modal-saldo_receber_Hupl">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Empréstimos Para Outros Hospitais feitos pelo HUPL</h4>
              </div>
              <div class="modal-body">
                <table style="color: #000000;" id="example4" class="table ">
                <thead>
                <tr>
                  <th>CD EMP</th>
                  <th>DESCRIÇÃO</th>
                  <th>QTDE</th>
                  <th>C.M.</th>
                  
                </tr>
                </thead>
                <tbody>
               <?php
               $soma_tde_material_sd_dev_hupl = 0;
               while (($row_sr_hupl = oci_fetch_array($saldo_receber_hupl, OCI_BOTH)) != false)
                    {
                    $cd_emprestimo2_hupl = $row_sr_hupl[0];  
                    $cd_material_hupl = $row_sr_hupl[1];
                    $ds_material2_hupl = $row_sr_hupl[2];
                    $qt_material2_hupl = $row_sr_hupl[3];
                    $custo_medio2_hupl = $row_sr_hupl[5];
                    $custo_medio2_hupl = number_format($custo_medio2_hupl, 2);
                    
                    $soma_tde_material_sd_dev_hupl += $qt_material2_hupl;
                  
               ?>
                <tr>
                  <td><?php echo $cd_emprestimo2_hupl; ?></td>
                  <td><?php echo $ds_material2_hupl; ?></td>
                  <td><?php echo $qt_material2_hupl; ?></td>
                  <td> <?php echo $custo_medio2_hupl; ?></td>
                </tr>
               <?php
               }
               ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>CD EMP</th>
                  <th>CD MAT</th>
                  <th>QTDE</th>
                  <th>SD</th>
                </tr>
                </tfoot>
              </table>
              </div>
                Total Material : <?php echo $soma_tde_material_sd_dev_hupl; ?>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
      </div>
      <!-- /.row -->
      <!-- /.DIV EXIBE OS SALDOS DEVEDOR E A RECEBER MAIS DETALHADO -->
       
                   
      
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">
          <!-- Custom tabs (Charts with tabs)-->
           <div class="box box-success">
           <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li class="active"><a href="#saida_hmu" data-toggle="tab">HMU</a></li>
             
              <li class="pull-left header"><i class="fa fa-inbox"></i> Mov. Entrada/Saída</li>
            </ul>
            <div class="tab-content no-padding">
              <!-- Morris chart - Sales -->
              <div class="box-body chart-responsive tab-pane active" id="saida_hmu" style="position: relative; height: 300px;">
                  <div class="chart" id="emprestimos_barras_hmu" style="height: 300px;"></div>
              </div>
             
             
            </div>
          </div>
          </div>
          
         
          
          <?php
          
          ?>
         
          <!-- <div class="box box-success">
           <div class="nav-tabs-custom">
           
            <ul class="nav nav-tabs pull-right">
              <li class="active"><a href="#emprestimos_barras_hupl" data-toggle="tab">HUPL</a></li>
              <li class="pull-left header"><i class="fa fa-inbox"></i> Mov. Entrada/Saída</li>
            </ul>
            <div class="tab-content no-padding">
           
              <div class="box-body chart-responsive tab-pane active " id="emprestimos_barras_hupl" style="position: relative; height: 300px;">
                  <div class="chart" id="emprestimos_barras_hupl" ></div>
              </div>
             
            </div>
          </div>
          </div> -->

          <!-- Chat box -->
         
          <!-- /.box (chat box) -->

         

        
        </section>
        
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-6 connectedSortable">
             <div class="box box-success">
           <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
              <li class="active"><a href="#emprestimos_barras_hupl" data-toggle="tab">HUPL</a></li>
              <li class="pull-left header"><i class="fa fa-inbox"></i> Mov. Entrada/Saída</li>
            </ul>
            <div class="tab-content no-padding">
              <!-- Morris chart - Sales -->
          
              <div class="box-body chart-responsive tab-pane active " id="emprestimos_barras_hupl" style="position: relative; height: 300px;">
                  <div class="chart" id="emprestimos_barras_hupl" ></div>
              </div>
             
            </div>
          </div>
          </div>
        

        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>

