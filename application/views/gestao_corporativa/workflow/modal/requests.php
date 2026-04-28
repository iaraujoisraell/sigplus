<div class="modal fade" id="requests" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">
                        SOLICITAÇÕES DE PARECER
                    </span>
                </h4>
            </div>

            <div class="modal-body">
                <div class="row ">
                    <div class="col-md-12">
                        <?php
                        $table_data = [];

                        $table_data = array_merge($table_data, array(
                            'WORKFLOW',
                            'SOLICITADO POR',
                            'DATA',
                            'DESCRIÇÃO',
                            'STATUS'
                        ));
                        render_datatable($table_data, 'internal_requests');
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(function () {
        initDataTable('.table-internal_requests', '<?php echo base_url(); ?>' + 'gestao_corporativa/workflow/table_internal_request', [5], [5]);
    });
</script>