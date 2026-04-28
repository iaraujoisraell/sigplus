<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Modal de Revisar Pessoas no Fluxo -->
<div class="modal fade" id="modalRevisarPessoa">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Revisar Pessoas no Fluxo</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('gestao_corporativa/cdc/edita_pessoas_cdc'); ?>
            <div class="modal-body">
               


                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">


                <div class="row">

                    <?php if ($flows_cdc[0]) { ?>

                        <input type="hidden" name="existe_pessoas" value="1">

                        <?php foreach ($flows_cdc as $flow) {    ?>
                            <div class="col-md-4">

                                <?php echo render_select(
                                    'flow_id_' . $flow['id'],
                                    $staffs,
                                    array('staffid', array('firstname', 'lastname')),
                                    $flow['fluxo_sequencia'] . '° ' . $flow['fluxo_nome'],
                                    $flow['staff_id'], // <- valor selecionado por padrão
                                    array('required' => true, 'data-live-search' => 'true'),
                                    [],
                                    '',
                                    'flow_'
                                ); ?>

                            </div>
                        <?php } ?>


                        <?php } else {
                        foreach ($flows as $flow) {  ?>
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
                                } else if ($flow['codigo_sequencial'] == 2) {

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
                                } else {
                                    echo render_select(
                                        'flow_' . $flow['id'],
                                        $staffs,
                                        array('staffid', array('firstname', 'lastname')),
                                        $flow['codigo_sequencial'] . '° ' . $flow['titulo'],
                                        [],
                                        array('required' => true, 'data-live-search' => 'true'),
                                        [],
                                        '',
                                        'flow_'
                                    );
                                }
                                ?>
                            </div>
                    <?php }
                    } ?>



                </div>
                <div class="modal-footer">
                    <button group="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>


            </div>

            <?php echo form_close(); ?>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            init_selectpicker();
        });
    </script>