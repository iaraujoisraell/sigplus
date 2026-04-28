
<h4 class="customer-profile-group-heading"><?php echo 'Links Externos'; ?></h4>

<div class="mtop15">
    <div class="horizontal-scrollable-tabs">
        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
        <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                <li role="presentation" class="active">
                    <a href="#links" aria-controls="links" role="tab" data-toggle="tab">
                        Links
                        <span class="badge" style="background: red;"><?php //echo count($pendentes);           ?></span>
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#categorias" aria-controls="categorias" role="tab" data-toggle="tab">
                        Categorias
                    </a>
                </li>

            </ul>
        </div>
    </div>
    <div class="tab-content mtop15">
        <div role="tabpanel" class="tab-pane" id="categorias">
            <?php
            $data['rel_type'] = 'links';
            $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);
            ?>
        </div>
        <div role="tabpane2" class="tab-pane active" id="links">
            <div class="row">
                <div class="col-md-12">
                    <div class="_buttons">
                        <?php if (has_permission_intranet('lisks_externos', '', 'create') || is_admin()) { ?>
                            <a href="<?php echo base_url('gestao_corporativa/intra/Links/index'); ?>" class="btn btn-info pull-left" ><?php echo 'Add Link'; ?></a>
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />

                    <?php
                    $table_data = [];

                    $table_data = array_merge($table_data, array(
                        'Nome',
                        'Cadastro',
                        'Url',
                        'Categoria',
                        'Ações'
                    ));
                    render_datatable($table_data, 'links');
                    ?>





                </div>
            </div>
        </div>
    </div>




</div>

<div id="modal_wrapper"></div>
<?php init_tail(); ?>
<script>

    $(function () {
        var CustomersServerParams = {};
        initDataTable('.table-links', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_link_externo', [0], [0], CustomersServerParams, [1, 'desc']);
    });
</script>