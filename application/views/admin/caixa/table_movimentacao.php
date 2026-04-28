<?php
$movimentacoes = $this->caixas_model->get_movimentacoes_by_registro_id($registro_id); 
?>
<table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
    <thead>
        <tr>
            <th>
                <?php echo 'Data'; ?>
            </th>
            <th >
                <?php echo 'Referência'; ?>
            </th>
            <th >
                <?php echo 'Entrada'; ?>
            </th>
            <th >
                <?php echo 'Saída'; ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $soma_total_entrada = 0;
        $soma_total_saida = 0;
        foreach($movimentacoes as $movimento){
       
        
        $data_registro  = $movimento['data_registro'];
        $tipo           = $movimento['tipo'];
        $valor          = $movimento['valor'];
        
        if($tipo == 1){
            $valor_saida = 0;
            $valor_entrada = $valor;
            $soma_total_entrada += $valor_entrada;
        }else if($tipo == 2){
            $valor_entrada = 0;
            $valor_saida = $valor;
            $soma_total_saida += $valor_saida;
        }
        
        ?>
    <tr>
        <td>
          <?php echo _d($data_registro);   ?>
        </td>
        <td>
          <?php echo $movimento['referencia'];   ?>
        </td>
        
        <td>
          <?php echo app_format_money($valor_entrada, 'R$');  ?>
        </td>
        <td>
          <?php echo app_format_money($valor_saida, 'R$');  ?>
        </td>
    </tr>

<?php } ?>
    <tr>
        <td>
          Entrada
        </td>
        <td>

        </td>
        <td>
            <label class="btn btn-primary"><?php echo app_format_money($soma_total_entrada, 'R$');  ?></label>
        </td>
        
        <td>
           
        </td>
        
    </tr>
    
     <tr>
        <td>
          Saída
        </td>
        <td>

        </td>
        <td>
           
        </td>
        
        <td>
            <label class="btn btn-danger"><?php echo app_format_money($soma_total_saida, 'R$');  ?></label>
        </td>
        
    </tr>
    
     <tr>
        <td>
          Saldo
        </td>
        <td>

        </td>
        <td>
            <label class="btn btn-primary"><?php echo app_format_money($soma_total_entrada, 'R$');  ?></label>
        </td>
        
        <td>
            <label class="btn btn-danger"><?php echo app_format_money($soma_total_saida, 'R$');  ?></label>
        </td>
        
    </tr>

</tbody>
  <thead>
    <tr>
            <th>
                <?php echo 'Data'; ?>
            </th>
            <th >
                <?php echo 'Referência'; ?>
            </th>
            <th >
                <?php echo 'Entrada'; ?>
            </th>
            <th >
                <?php echo 'Saída'; ?>
            </th>
        </tr>
</thead>
<tbody>

</tbody>
</table>