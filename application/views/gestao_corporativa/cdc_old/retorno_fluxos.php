
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
                                echo render_select('flow_' . $flow['id'], $staffs, array('staffid', array('firstname', 'lastname')), $flow['codigo_sequencial'] . '° ' . $flow['titulo'], [], array('required' => true, 'data-live-search' => 'true'), [], '', 'flow_');
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        init_selectpicker();
        });
</script>



