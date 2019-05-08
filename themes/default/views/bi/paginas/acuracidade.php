<?php include 'conexao_oracle.php'; ?>
<?php //include 'querys_tasy_oracle/query_saldo_estoque.php'; ?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Acuracidade
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

    $saldo_estoque_hmu = oci_parse($ora_conexao,$query_acuracidade_hmu);
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
                   while (($row_sd_hmu = oci_fetch_array($saldo_estoque_hmu, OCI_BOTH)) != false)
                    {
                    $saldo = substr($row_sd_hmu[1],0,5);  
                    $cd_local_estoque = $row_sd_hmu[2];

                    
                    
                    if($cd_local_estoque == 93){
                        $ds_local = "ALMOXARIFADO";
                        $cor = "aqua";
                        $fa = "th";
                        $acuracidade = $saldo;
                        
                        ?>
                  <a href="#" data-toggle="modal" data-target="#modal-almoxarifado_hmu" class="small-box-footer">
                  <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="info-box">
                        <span class="info-box-icon bg-<?php echo $cor; ?>"><i class="fa fa-<?php echo $fa; ?>"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text"><?php echo $ds_local; ?></span>
                          <span class="info-box-number"><?php echo $saldo; ?> %</span>
                        </div>
                        <!-- /.info-box-content -->
                          
                      </div>
                     
                      <!-- /.info-box -->
                    </div></a>
                    
                
                <?php
                        
                    }else if($cd_local_estoque == 69){
                        $ds_local = "NUTRIÇÃO";
                        $cor = "yellow";
                        $fa = "apple";
                        $acuracidade = $saldo;
                        
                         ?>
                  <a href="#" data-toggle="modal" data-target="#modal-nutricao_hmu" class="small-box-footer">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                              <div class="info-box">
                                  <span class="info-box-icon bg-<?php echo $cor; ?>"><i class="fa fa-<?php echo $fa; ?>"></i></span>

                                  <div class="info-box-content">
                                      <span class="info-box-text"><?php echo $ds_local; ?></span>
                                      <span class="info-box-number"><?php echo $saldo; ?> %</span>
                                  </div>
                                  <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                          </div>
                  </a>
                       <?php
                      }else if($cd_local_estoque == 65){
                        $ds_local = "FARMÁCIA";
                        $cor = "green";
                        $fa = "plus-square";
                        $acuracidade = $saldo;
                        
                        
                         ?>
                  <a href="#" data-toggle="modal" data-target="#modal-farmacia_hmu" class="small-box-footer">   
                        <div class="col-md-4 col-sm-6 col-xs-12">
                              <div class="info-box">
                                  <span class="info-box-icon bg-<?php echo $cor; ?>"><i class="fa fa-<?php echo $fa; ?>"></i></span>

                                  <div class="info-box-content">
                                      <span class="info-box-text"><?php echo $ds_local; ?></span>
                                      <span class="info-box-number"> <?php echo $saldo; ?> %</span>
                                  </div>
                                  <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                          </div>
                      </a>
                          <?php
                        
                    }else if($cd_local_estoque == 157){
                        $ds_local = "HOTELARIA";
                        $cor = "red";
                        $fa = "h-square";
                        $acuracidade = $saldo;
                        
                        
                         ?>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                              <div class="info-box">
                                  <span class="info-box-icon bg-<?php echo $cor; ?>"><i class="fa fa-<?php echo $fa; ?>"></i></span>

                                  <div class="info-box-content">
                                      <span class="info-box-text"><?php echo $ds_local; ?></span>
                                      <span class="info-box-number">R$ <?php echo $saldo; ?></span>
                                  </div>
                                  <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                          </div>
                          <?php
                        
                    }
                    
                    
                $cont++;
                   }
                  ?>     
                
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
              <li class="pull-left header"><i class="fa fa-inbox"></i> Acuracidade</li>
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
         

        
        </section>
       
        
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>


    <?PHP
    /*
     * ALMOXARIFADO
     */
    $mes = date('m');
        $ano = date('Y');
             $query_sd_devedor_hmu_detalhes = "select nvl(substr(a.DS_MATERIAL,1,255), 'Não Informado') ds_base, 
                                                TASY.DIVIDIR(NVL(SUM(QT_OCOR_INVENT_OK),0)*100, NVL(SUM(QT_OCOR_INVENT),0)) nr_acum 
                                                 , a.CD_MATERIAL cd_base 
                                                from TASY.EIS_INVENT_MATERIAL_V a
                                                where 1 = 1 
                                                AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
                                                and DS_MATERIAL is not null 
                                                and DT_MESANO_REFERENCIA between TO_DATE('01/$mes/$ano','dd/mm/yyyy') and TO_DATE('31/$mes/$ano 23:59:59','dd/mm/yyyy hh24:mi:ss')
                                                and cd_local_estoque in (93)
                                                group by nvl(substr(a.DS_MATERIAL,1,255), 'Não Informado'), a.CD_MATERIAL
                                                order by 1  Asc ";
                $saldo_devedor_hmu = oci_parse($ora_conexao,$query_sd_devedor_hmu_detalhes);
                oci_execute($saldo_devedor_hmu, OCI_NO_AUTO_COMMIT);

                // $emprestimo_entrada = array();
                $soma_qt_material = 0;
                $soma_custo_medio = 0;
            ?>
            <div class="modal fade" id="modal-almoxarifado_hmu">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Acuracidade <?php echo $mes.'/'.$ano ?> Almoxarifado  HMU</h4>
              </div>
              <div class="modal-body">
                <table style="color: #000000;" id="example2" class="table ">
                <thead>
                <tr>
                  <th>CD MAT</th>
                  <th>MATERIAL</th>
                  <th>ACURACIDADE</th>
                </tr>
                </thead>
                <tbody>
                   <?php
                   $soma_tde_material_sd_dev_hmu = 0;
                   while (($row_sd_hmu = oci_fetch_array($saldo_devedor_hmu, OCI_BOTH)) != false)
                        {
                        $cd_material = $row_sd_hmu[2];
                        $ds_material = $row_sd_hmu[0];
                        $acuracidade = $row_sd_hmu[1];                  
                   ?>
                    <tr>
                      <td><?php echo $cd_material; ?></td>
                      <td><?php echo $ds_material; ?></td>
                      <td><?php echo $acuracidade; ?> %</td>
                    </tr>
                   <?php
                   }
                   ?>
                </tbody>
                <tfoot>
                <tr>
                 
                  <th>CD MAT</th>
                  <th>MATERIAL</th>
                  <th>ACURACIDADE</th>
                </tr>
                </tfoot>
              </table>
              </div>
               
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>


<?PHP
/*
 * NUTRIÇÃO
 */
    $mes = date('m');
        $ano = date('Y');
             $query_sd_devedor_hmu_detalhes = "select nvl(substr(a.DS_MATERIAL,1,255), 'Não Informado') ds_base, 
                                                TASY.DIVIDIR(NVL(SUM(QT_OCOR_INVENT_OK),0)*100, NVL(SUM(QT_OCOR_INVENT),0)) nr_acum 
                                                 , a.CD_MATERIAL cd_base 
                                                from TASY.EIS_INVENT_MATERIAL_V a
                                                where 1 = 1 
                                                AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
                                                and DS_MATERIAL is not null 
                                                and DT_MESANO_REFERENCIA between TO_DATE('01/$mes/$ano','dd/mm/yyyy') and TO_DATE('31/$mes/$ano 23:59:59','dd/mm/yyyy hh24:mi:ss')
                                                and cd_local_estoque in (69)
                                                group by nvl(substr(a.DS_MATERIAL,1,255), 'Não Informado'), a.CD_MATERIAL
                                                order by 1  Asc ";
                $saldo_devedor_hmu = oci_parse($ora_conexao,$query_sd_devedor_hmu_detalhes);
                oci_execute($saldo_devedor_hmu, OCI_NO_AUTO_COMMIT);

                // $emprestimo_entrada = array();
                $soma_qt_material = 0;
                $soma_custo_medio = 0;
            ?>
            <div class="modal fade" id="modal-nutricao_hmu">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Acuracidade <?php echo $mes.'/'.$ano ?> Nutrição  HMU</h4>
              </div>
              <div class="modal-body">
                <table style="color: #000000;" id="example1" class="table ">
                <thead>
                <tr>
                  <th>CD MAT</th>
                  <th>MATERIAL</th>
                  <th>ACURACIDADE</th>
                </tr>
                </thead>
                <tbody>
                   <?php
                   $soma_tde_material_sd_dev_hmu = 0;
                   while (($row_sd_hmu = oci_fetch_array($saldo_devedor_hmu, OCI_BOTH)) != false)
                        {
                        $cd_material = $row_sd_hmu[2];
                        $ds_material = $row_sd_hmu[0];
                        $acuracidade = $row_sd_hmu[1];                  
                   ?>
                    <tr>
                      <td><?php echo $cd_material; ?></td>
                      <td><?php echo $ds_material; ?></td>
                      <td><?php echo $acuracidade; ?> %</td>
                    </tr>
                   <?php
                   }
                   ?>
                </tbody>
                <tfoot>
                <tr>
                 
                  <th>CD MAT</th>
                  <th>MATERIAL</th>
                  <th>ACURACIDADE</th>
                </tr>
                </tfoot>
              </table>
              </div>
               
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>


<?PHP
//FARMÁCIA
    $mes = date('m');
        $ano = date('Y');
             $query_sd_devedor_hmu_detalhes3 = "select nvl(substr(a.DS_MATERIAL,1,255), 'Não Informado') ds_base, 
                                                TASY.DIVIDIR(NVL(SUM(QT_OCOR_INVENT_OK),0)*100, NVL(SUM(QT_OCOR_INVENT),0)) nr_acum 
                                                 , a.CD_MATERIAL cd_base 
                                                from TASY.EIS_INVENT_MATERIAL_V a
                                                where 1 = 1 
                                                AND CD_ESTABELECIMENTO = a.CD_ESTABELECIMENTO
                                                and DS_MATERIAL is not null 
                                                and DT_MESANO_REFERENCIA between TO_DATE('01/$mes/$ano','dd/mm/yyyy') and TO_DATE('31/$mes/$ano 23:59:59','dd/mm/yyyy hh24:mi:ss')
                                                and cd_local_estoque NOT IN (93,69,157)
                                                group by nvl(substr(a.DS_MATERIAL,1,255), 'Não Informado'), a.CD_MATERIAL
                                                order by 1  Asc ";
                $saldo_devedor_hmu3 = oci_parse($ora_conexao,$query_sd_devedor_hmu_detalhes3);
                oci_execute($saldo_devedor_hmu3, OCI_NO_AUTO_COMMIT);

                // $emprestimo_entrada = array();
                $soma_qt_material = 0;
                $soma_custo_medio = 0;
            ?>
            <div class="modal fade" id="modal-farmacia_hmu">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Acuracidade <?php echo $mes.'/'.$ano ?> Farmácia  HMU</h4>
              </div>
              <div class="modal-body">
                <table style="color: #000000;" id="example3" class="table ">
                <thead>
                <tr>
                  <th>CD MAT</th>
                  <th>MATERIAL</th>
                  <th>ACURACIDADE</th>
                </tr>
                </thead>
                <tbody>
                   <?php
                   $soma_tde_material_sd_dev_hmu = 0;
                   while (($row_sd_hmu3 = oci_fetch_array($saldo_devedor_hmu3, OCI_BOTH)) != false)
                        {
                        $cd_material = $row_sd_hmu3[2];
                        $ds_material = $row_sd_hmu3[0];
                        $acuracidade = $row_sd_hmu3[1];                  
                   ?>
                    <tr>
                      <td><?php echo $cd_material; ?></td>
                      <td><?php echo $ds_material; ?></td>
                      <td><?php echo $acuracidade; ?> %</td>
                    </tr>
                   <?php
                   }
                   ?>
                </tbody>
                <tfoot>
                <tr>
                 
                  <th>CD MAT</th>
                  <th>MATERIAL</th>
                  <th>ACURACIDADE</th>
                </tr>
                </tfoot>
              </table>
              </div>
               
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>