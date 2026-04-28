<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>



<div class="content">
    <div class="row">

        <div class="col-md-12">

            <h5 style="font-weight: bold;">INTRANET - REGISTROS DE ATENDIMENTO</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li class="active"><a href="#"><i class="fa fa-user"></i> Registro de Atendimento </a></li>
                </ol>
            </div>


            <div class="row" >
                <div class="col-md-12">
                    <?php if (has_permission_intranet('registro_atendimento', '', 'create') || is_admin()) { ?>
                        <a href="<?php echo base_url('gestao_corporativa/Atendimento/add'); ?>" class="btn btn-info pull-rigth btn-with-tooltip">
                            <i class="fa fa-plus"></i> Novo Atendimento
                        </a>
                    <?php } ?>
                </div>
            </div> 
            <br>
            <div class="panel_s mbot10">

                <div class="panel-heading" style="padding-top: 0px; padding-bottom: 0px;">
                    <div class="checkbox" style="">
                        <?php
                        echo form_hidden('meus_atendimentos', '1');
                        ?>
                        <input type="checkbox" id="my" checked>
                        <label for="user_created">Somente os meus atendimentos</label>
                    </div>

                </div>
                <div class="panel-body ">

                    <div class="row">

                        <?php
                        ?>
                        <div class=" col-md-11 col-sm-12"  >
                            <div class="row"  >
                                <div class=" col-md-2 col-sm-6"  >
                                    <?php
                                    echo render_select('categoria_id', $categorias, array('id', 'titulo'), '', json_decode($filters['categoria_id']), array('data-none-selected-text' => 'CATEGORIA', 'required' => 'true', 'multiple' => 'true', 'onchange' => 'add_button();'), [], '', '', false);
                                    ?>
                                </div>
                                <div class=" col-md-2 col-sm-6"  >
                                    <?php
                                    echo render_select('canal_atendimento_id', $canais_atendimentos, array('id', 'canal'), '', json_decode($filters['canal_atendimento_id']), array('data-none-selected-text' => 'CANAL', 'required' => 'true', 'multiple' => 'true', 'onchange' => 'add_button();'), [], '', '', false);
                                    ?>
                                </div>

                                <div class=" col-md-2 col-sm-6" >
                                    <div class="form-group">
                                        <input class="form-control"  type="text" name="client" value="<?php echo $filters['client']; ?>" placeholder="NOME CLIENTE">
                                    </div>
                                </div>

                                <div class=" col-md-1 col-sm-6"  id="id_div" >
                                    <div class="form-group">
                                        <input type="number" class="form-control" value="<?php echo $filters['id']; ?>" name="id" required="true" onkeyup="add_button();" placeholder="#WFID">

                                    </div>
                                </div>

                                <div class=" col-md-2 col-sm-6"  >
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="protocolo" value="<?php echo $filters['protocolo']; ?>" onkeyup="add_button();" placeholder="PROTOCOLO">
                                    </div>
                                </div>

                                <div class=" col-md-2 col-sm-6"  >
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="carteirinha" value="<?php echo $filters['carteirinha']; ?>" onkeyup="add_button();" placeholder="CARTEIRINHA">
                                    </div>
                                </div>    





                                <div class="col-md-1 col-sm-6">
                                    <div class="select-placeholder form-group" app-field-wrapper="period">

                                        <select id="period" name="period" class="selectpicker"  required="true"  onchange="" data-width="100%" 
                                                data-none-selected-text="PERÍODO" data-live-search="true">

                                            <option value="d" <?php
                                            if ($filters['period'] == 'd' || $filters['period'] == '') {
                                                echo 'selected';
                                            }
                                            ?>>HOJE</option>
                                            <option value="s" <?php
                                            if ($filters['period'] == 's') {
                                                echo 'selected';
                                            }
                                            ?>>SEMANA</option>
                                            <option value="m" <?php
                                            if ($filters['period'] == 'm') {
                                                echo 'selected';
                                            }
                                            ?>>MÊS</option>
                                             <option value="Y" <?php
                                            if ($filters['period'] == 'Y') {
                                                echo 'selected';
                                            }
                                            ?>>MÊS</option>
                                            <option value="-" <?php
                                            if ($filters['period'] == '-') {
                                                echo 'selected';
                                            }
                                            ?>>TODOS</option>
                                        </select>
                                    </div>                    
                                </div>


                            </div>
                        </div>
                        <div class="col-md-1 col-sm-12 ">                    
                            <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  onclick="reload();">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>


                        </div> 
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs " style="width: 100%;">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#interno" aria-controls="interno" role="tab" data-toggle="tab">
                                        ATENDIMENTOS INTERNOS
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#portal" aria-controls="portal" role="tab" data-toggle="tab">
                                        ATENDIMENTOS VIA PORTAL
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>     


                    <div class="tab-content mtop15">
                        <div role="interno" class="tab-pane active" id="interno">
                            <?php
                            $table_data = array(
                                '<strong style="font-weight: bold">#</strong>',
                                '<strong style="font-weight: bold">PROTOCOLO</strong>',
                                '<strong style="font-weight: bold">REQUISIÇÕES</strong>',
                                '<strong style="font-weight: bold">CANAL</strong>',
                                '<strong style="font-weight: bold">CLIENTE</strong>',
                                '<strong style="font-weight: bold">CATEGORIA</strong>',
                                '<strong style="font-weight: bold">CONTATO</strong>',
                                '<strong style="font-weight: bold">INF. CHAVE</strong>',
                                '<strong style="font-weight: bold">CADASTRO</strong>',
                            );

                            render_datatable($table_data, 'registro_atendimentos');
                            ?>
                        </div>
                        <div role="portal" class="tab-pane" id="portal">
                            <?php
                            $table_data = [];

                            $table_data = array_merge($table_data, array(
                                '<strong style="font-weight: bold">#</strong>',
                                '<strong style="font-weight: bold">PROTOCOLO</strong>',
                                '<strong style="font-weight: bold">REQUISIÇÕES</strong>',
                                '<strong style="font-weight: bold">CLIENTE</strong>',
                                '<strong style="font-weight: bold">CONTATO</strong>',
                                '<strong style="font-weight: bold">INF. CHAVE</strong>',
                                '<strong style="font-weight: bold">CADASTRO</strong>',
                            ));
                            render_datatable($table_data, 'registro_atendimentos_portal');
                            ?>
                        </div>
                    </div>






                </div>

            </div>
        </div>
    </div>
</div>



<?php init_tail(); ?>
<script>



    $(function () {
        reload(false);
    });

    function reload(filter = true) {
        reload_one(filter, 'registro_atendimentos');
        reload_one(filter, 'registro_atendimentos_portal');
    }

    function reload_one(filter = true, table = '') {

        if ($.fn.DataTable.isDataTable('.table-' + table)) {
            $('.table-' + table).DataTable().destroy();
        }

        var Params = {};

        Params['client'] = '[name="client"]';
        Params['carteirinha'] = '[name="carteirinha"]';
        Params['id'] = '[name="id"]';
        Params['protocolo'] = '[name="protocolo"]';
        Params['categoria_id'] = '[name="categoria_id"]';

        if (document.getElementById('my').checked) {
            Params['meus_atendimentos'] = '[name="meus_atendimentos"]';
        }
        Params['canal_atendimento_id'] = '[name="canal_atendimento_id"]';
        Params['period'] = '[name="period"]';

        if (filter == true) {
            Params['filter'] = '[name="meus_atendimentos"]';
        }
        if (table == 'registro_atendimentos_portal') {
            Params['portal'] = '[name="meus_atendimentos"]';
        }

        initDataTable('.table-' + table, '<?php echo base_url(); ?>' + 'gestao_corporativa/Atendimento/table', [0], [0], Params, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0, 'desc'))); ?>);

    }

</script>
</body>
</html>