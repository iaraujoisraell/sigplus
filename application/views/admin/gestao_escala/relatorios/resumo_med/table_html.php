 <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
        <thead>
            <tr>
                <th width="5%">
                    <?php echo 'Competencia'; ?>
                </th>
                <th width="35%">
                    <?php echo 'Médico'; ?>
                </th>
                <th width="35%">
                    <?php echo 'Unidade Hospitalar'; ?>
                </th>
                <th width="20%">
                    <?php echo 'Setor'; ?>
                </th>
                <th width="5%">
                    <?php echo 'Quantidade'; ?>
                </th>
             </tr>
        </thead>
        <tbody>
        
                <?php foreach($resultados as $note){ ?>
            <tr>
            <td> 
                <?php echo $copetencia=$note['mes'].'/'.$note['ano'];?>
            </td>
            
            <td>
              <?php echo $note['medico'];?>
            </td>
       
            <td>
              <?php echo $note['unidade_hospitalar'];?>
            </td>
        
            <td>
              <?php echo $note['setor'];?>
            </td>
        
            <td>
              <?php echo $note['qtde_plantao_comp'];?>
            </td>
        </tr>
    <?php } ?>
</tbody>
</table>