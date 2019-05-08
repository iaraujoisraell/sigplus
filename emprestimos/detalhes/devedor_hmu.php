<?php
/*
 * SALDO DEVEDOR HMU
 */
    $saldo_devedor_hmu = oci_parse($ora_conexao,$query_saldo_devedor_hmu);
    oci_execute($saldo_devedor_hmu, OCI_NO_AUTO_COMMIT);

    // $emprestimo_entrada = array();
    $soma_qt_material = 0;
    $soma_custo_medio = 0;
    
    
     
?>
<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Empréstimos feitos pelo  HMU</h4>
              </div>
              <div class="modal-body">
                <table style="color: #000000;" id="example2" class="table ">
                <thead>
                <tr>
                  <th>CD EMP</th>
                  <th>CD MAT</th>
                  <th>QTDE</th>
                  <th>C.M.</th>
                  
                </tr>
                </thead>
                <tbody>
               <?php
               while (($row_sd_hmu = oci_fetch_array($saldo_devedor_hmu, OCI_BOTH)) != false)
                    {
                    $cd_emprestimo = $row_sd_hmu[0];  
                    $cd_material = $row_sd_hmu[1];
                    $qt_material = $row_sd_hmu[2];
                    $custo_medio = $row_sd_hmu[3] * $qt_material;
                    $custo_medio = number_format($custo_medio, 2);
                  
               ?>
                <tr>
                  <td><?php echo $cd_emprestimo; ?></td>
                  <td><?php echo $cd_material; ?></td>
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
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>


