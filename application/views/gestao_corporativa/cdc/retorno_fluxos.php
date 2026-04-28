<div class="panel_s">
    <div class="panel-heading">
        Fluxos Obrigatórios da Categoria
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-12 mbot20 before-ticket-message">

                <div class="row">
                    <?php foreach ($flows as $flow) { ?>
                        <div class="col-md-4">
                            <?php


                            if ($flow['codigo_sequencial'] == 1) {

                              //  echo "aqui"; 
                                $user = get_staff_user_id();
                                echo render_select(
                                    'flow_' . $flow['id'],
                                    $staffs,
                                    array('staffid', array('firstname', 'lastname')),
                                    $flow['codigo_sequencial'] . '° ' . $flow['titulo'],
                                    $user, // <- valor selecionado por padrão
                                    array('required' => true, 'data-live-search' => 'true'),
                                    [],
                                    '',
                                    'flow_'
                                );
                            } else if($flow['codigo_sequencial'] == 2){

                                $user = $staff_responsavel;
                                echo render_select(
                                    'flow_' . $flow['id'],
                                    $staffs,
                                    array('staffid', array('firstname', 'lastname')),
                                    $flow['codigo_sequencial'] . '° ' . $flow['titulo'],
                                    $user, // <- valor selecionado por padrão
                                    array('required' => true, 'data-live-search' => 'true'),
                                    [],
                                    '',
                                    'flow_'
                                );


                            } else  {
                                echo render_select(
                                    'flow_' . $flow['id'],
                                    $staffs, 
                                    array('staffid', array('firstname', 'lastname')), 
                                    $flow['codigo_sequencial'] . '° ' . $flow['titulo'], 
                                    [], 
                                    array('data-live-search' => 'true'), 
                                    [], 
                                    '', 
                                    'flow_'
                                );
                            }


                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        init_selectpicker();
    });
</script>