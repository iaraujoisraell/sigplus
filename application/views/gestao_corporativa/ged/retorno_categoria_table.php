<div class="col-md-12" >
    <?php
    $table_data = [];

    $table_data = array_merge($table_data, array(
        'ID',
        'ID',
        'ID',
    ));
    foreach ($campos as $campo){
        array_push($table_data, $campo['nome']);
    }
    render_datatable($table_data, 'table');
    
    ?>
    
</div>