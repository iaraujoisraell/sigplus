<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">

<?php $this->load->view('gestao_corporativa/css_background'); ?>
<input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="staffid">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - REGISTROS DE OCORRÊNCIA</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>"><i class="fa fa-backward"></i> Registros de Ocorrência </a></li>
                </ol>
            </div>
        </div>

        <div class="col-md-12">
            <?php if (has_permission_intranet('registro_ocorrencia', '', 'create') || is_admin()) { ?>
                <a href="<?php echo base_url('gestao_corporativa/registro_ocorrencia/add'); ?>" class="btn btn-info pull-left display-block mright5">
                    <i class="fa fa-plus"></i> Registro de Ocorrência
                </a>
            <?php } ?>
            <a href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia/reports'); ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" data-title="Criar Relatório de Regustros" data-placement="bottom">
                <i class="fa fa-plus"></i>
                <i class="fa fa-bar-chart"></i>
            </a>


            <br><br>

            <div class="panel_s">

                <div class="panel-body">
                    <div class="row">

                        <div class=" col-md-3">
                            <?php
                            echo render_select('categoria_id', $categorias, array('id', 'titulo'), '', json_decode($filters[0]['categoria_id']), array('data-none-selected-text' => 'CATEGORIA', 'required' => 'true', 'multiple' => 'true', 'onchange' => 'add_button();'), [], '', '', false);
                            ?>
                        </div>
                        <div class=" col-md-3" >
                            <?php
                            echo render_select('departments', $departments, array('departmentid', 'name'), '', json_decode($filters[0]['departments']), array('data-none-selected-text' => 'SETOR RESPONSÁVEL', 'required' => 'true', 'multiple' => 'true', 'onchange' => 'add_button();'), [], '', '', false);
                            ?>
                        </div>

                        <div class="col-md-3" >
                            <div class="form-group" class="control-label">
                                <select name="user_created" class="selectpicker"  required="true" multiple="true" data-width="100%" 
                                        data-none-selected-text="NOTIFICANTE" data-live-search="true">
                                            <?php
                                            foreach ($notificantes as $staf) {
                                                $selected = '';
                                                if ($filters[0]['user_created']) {
                                                    if (in_array($staf['user_created'], json_decode($filters[0]['user_created']))) {
                                                        $selected = 'selected';
                                                    }
                                                }
                                                if ($staf['user_created'] == 0) {
                                                    echo '<option  ' . $selected . ' value="0" >ANÔNIMO</option>';
                                                } else {
                                                    ?>
                                            <option  value="<?php echo $staf['user_created']; ?>" <?php echo $selected; ?> ><?php echo get_staff_full_name($staf['user_created']); ?></option>
                                            <?php
                                        }
                                    }
                                    ?>



                                </select>
                            </div>
                        </div>
                        <div class=" col-md-3">
                            <?php
                            //print_R($statuses); exit;
                            echo render_select('status', $statuses, array('id', 'label'), '', json_decode($filters[0]['status']), array('data-none-selected-text' => 'STATUS', 'required' => 'true', 'multiple' => 'true', 'onchange' => ""), [], '', '', false);
                            ?>
                        </div>






                    </div>
                </div>
            </div>

            <div class="panel_s">

                <div class="panel-body">

                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                                <?php
                                $this->load->model('registro_ocorrencia_model');
                                $staff_department = $this->registro_ocorrencia_model->get_registros_recebidos_por_departamento();
                                $staff_staff = $this->registro_ocorrencia_model->get_registros_recebidos_por_staffid();
                                ?>
                                <li role="presentation" class="<?php count($staff_department) == 0 ? 'active' : ''; ?>">
                                    <a href="#contact_info1" aria-controls="contact_info1" role="tab" data-toggle="tab">
                                        Meus registros
                                    </a>

                                </li>
                                <?php
//echo 'aqui'; exit;
                                ?>
                                <?php if (count($staff_department) > 0) { ?>
                                    <li role="presentation" class="active">
                                        <a href="#contact_info2" aria-controls="contact_info2" role="tab" data-toggle="tab">
                                            Setor responsável
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if (count($staff_staff) > 0) { ?>
                                    <li role="presentation" class="">
                                        <a href="#contact_info3" aria-controls="contact_info3" role="tab" data-toggle="tab">
                                            Vinculados a mim
                                        </a>
                                    </li>
                                <?php } ?>
                                <li role="presentation" class="">
                                    <a href="#contact_info4" aria-controls="contact_info4" role="tab" data-toggle="tab">
                                        Vinculados (Setor)
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>

                    <div class="tab-content mtop15">

                        <div role="tabpanel" class="tab-pane <?php count($staff_department) == 0 ? 'active' : ''; ?>" id="contact_info1">
                            <div class="row">

                                <div class="col-md-12 col-sm-12 "  >
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 "  >
                                            <div class="form-group">
                                                <input type="number"  class="form-control" value="<?php echo $filters[1]['id']; ?>" name="1-id" required="true" onkeyup="" placeholder="ID">
                                            </div>

                                        </div>

                                        <div class="col-md-3 col-sm-6 "  >
                                            <div class="form-group">
                                                <input class="form-control" type="number" name="1-protocolo" value="<?php echo $filters[1]['protocolo']; ?>" onkeyup="" placeholder="PROTOCOLO">
                                            </div>
                                        </div>
                                        <div class=" col-md-3 col-sm-6" >
                                            <div class="form-group">
                                                <input class="form-control"  type="text" name="1-client" value="<?php echo $filters[1]['client']; ?>" placeholder="NOME CLIENTE">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6"  >
                                            <div class="form-group">
                                                <input class="form-control"  type="number" name="1-carteirinha" value="<?php echo $filters[1]['carteirinha']; ?>" onkeyup="" placeholder="CARTEIRINHA">
                                            </div>
                                        </div>

                                        <div class=" col-md-8 col-sm-12" >
                                            <div class="form-group">
                                                <input class="form-control"  type="text" name="1-subject" value="<?php echo $filters[1]['subject']; ?>" placeholder="ASSUNTO DO REGISTRO">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="select-placeholder form-group" app-field-wrapper="1-period">
                                                <select id="1-period" name="1-period" class="selectpicker" required="true" onchange="" data-width="100%" 
                                                        data-none-selected-text="PERÍODO" data-live-search="true">
                                                    <option value="d">Registrados hoje</option>
                                                    <option value="s" selected>Registrados na semana</option>
                                                    <option value="m" >Registrados no mês</option>
                                                    <option value="Y">Registrados no ano</option>
                                                    <option value="-">Todos</option>

                                                </select>
                                            </div>                    
                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-12 col-sm-12 ">                    
                                    <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="reload_tables('registro_ocorrencia_my', '1');">
                                        Buscar Registros <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>


                                </div>
                                <div class="col-md-12">

                                    <?php
                                    $this->load->model('Registro_ocorrencia_model');
                                    $data['tipo'] = 1;
                                    //$this->load->view('gestao_corporativa/registro_ocorrencia/summary', $data);
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <input name="1-type" type="hidden" value="1">
                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        '<strong style="font-weight: bold">ID</strong>',
                                        '<strong style="font-weight: bold">ASSUNTO</strong>',
                                        '<strong style="font-weight: bold">CATEGORIA</strong>',
                                        '<strong style="font-weight: bold">NOTIFICANTE</strong>',
                                        '<strong style="font-weight: bold">VALIDADE</strong>',
                                        '<strong style="font-weight: bold">STATUS</strong>',
                                        //'<strong style="font-weight: bold">SETOR RESPONSÁVEL</strong>',
                                            //'Inicio/Fim',
                                    ));
                                    render_datatable($table_data, 'registro_ocorrencia_my');
                                    ?>

                                </div>
                            </div>
                        </div>
                        <?php if (count($staff_department) > 0) { ?>
                            <div role="tabpane2" class="tab-pane active" id="contact_info2">
                                <div class="row ">

                                    <div class="col-md-12 col-sm-12 "  >
                                        <div class="row">
                                            <div class="col-md-2 col-sm-6 "  >
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">#</span>
                                                        <input type="number"  class="form-control" value="<?php echo $filters[2]['id']; ?>" name="2-id" required="true" onkeyup="" placeholder="ID">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-sm-6 "  >
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="2-protocolo" value="<?php echo $filters[2]['protocolo']; ?>" onkeyup="" placeholder="PROTOCOLO">
                                                </div>
                                            </div>
                                            <div class=" col-md-3 col-sm-6" >
                                                <div class="form-group">
                                                    <input class="form-control"  type="text" name="2-client" value="<?php echo $filters[2]['client']; ?>" placeholder="NOME CLIENTE">
                                                </div>
                                            </div>

                                            <div class="col-md-2 col-sm-6"  >
                                                <div class="form-group">
                                                    <input class="form-control"  type="number" name="2-carteirinha" value="<?php echo $filters[2]['carteirinha']; ?>" onkeyup="" placeholder="CARTEIRINHA">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select  name="2-atribuido_a" class="selectpicker"   multiple="true" 
                                                             onchange="add_button();" data-width="100%" 
                                                             data-none-selected-text="ATRIBUÍDO" data-live-search="true">

                                                        <?php
                                                        foreach ($atribuidos as $staf) {
                                                            $selected = '';
                                                            if($filters[2]['atribuido_a']){
                                                            if (in_array($staf['atribuido_a'], json_decode($filters[2]['atribuido_a']))) {
                                                                $selected = 'selected';
                                                            }
                                                            }
                                                            if ($staf['atribuido_a'] == 0) {
                                                                //echo '<option  value="0" >VAZIO</option>';
                                                            } else {
                                                                ?>
                                                                <option  value="<?php echo $staf['atribuido_a']; ?>" <?php echo $selected; ?>><?php echo get_staff_full_name($staf['atribuido_a']); ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>


                                                    </select>
                                                </div>
                                            </div>
                                            <div class=" col-md-8 col-sm-12" >
                                                <div class="form-group">
                                                    <input class="form-control"  type="text" name="2-subject" value="<?php echo $filters[2]['subject']; ?>" placeholder="ASSUNTO DO REGISTRO">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                            <div class="select-placeholder form-group" app-field-wrapper="2-period">
                                                <select id="2-period" name="2-period" class="selectpicker" required="true" onchange="" data-width="100%" 
                                                        data-none-selected-text="PERÍODO" data-live-search="true">
                                                    <option value="d">Registrados hoje</option>
                                                    <option value="s" selected>Registrados na semana</option>
                                                    <option value="m">Registrados no mês</option>
                                                    <option value="Y">Registrados no ano</option>
                                                    <option value="-">Todos</option>

                                                </select>
                                            </div>                    
                                        </div>
                                        </div>

                                    </div>
                                    <div class="col-md-12 col-sm-12 mx-auto my-auto" style="">                    
                                        <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="reload_tables('registro_ocorrencia', '2');">
                                            Buscar Registros <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>


                                    </div>
                                    <div class="col-md-12">
                                        <?php
                                        $data['tipo'] = 2;
                                        //$this->load->view('gestao_corporativa/registro_ocorrencia/summary', $data);
                                        ?>
                                        <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" dat                 a-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                        <div class="clearfix"></div>
                                        <input name="2-type" type="hidden" value="2">
                                        <?php
                                        $table_data = [];

                                        $table_data = array_merge($table_data, array(
                                            '<strong style="font-weight: bold">ID</strong>',
                                            '<strong style="font-weight: bold">ASSUNTO</strong>',
                                            '<strong style="font-weight: bold">CATEGORIA</strong>',
                                            '<strong style="font-weight: bold">NOTIFICANTE</strong>',
                                            
                                            '<strong style="font-weight: bold">ATUANTES</strong>',
                                            '<strong style="font-weight: bold">VALIDADE</strong>',
                                            
                                            '<strong style="font-weight: bold">ISHIKAWA/AÇÕES</strong>',
                                            
                                            //'<strong style="font-weight: bold">SETOR RESPONSÁVEL</strong>',
                                            '<strong style="font-weight: bold">ATRIBUÍDO</strong>',
                                            '<strong style="font-weight: bold">STATUS</strong>',
                                                //'Inicio/Fim',
                                        ));
                                        render_datatable($table_data, 'registro_ocorrencia');
                                        ?>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (count($staff_staff) > 0) { ?>
                            <div role="tabpane3" class="tab-pane" id="contact_info3">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 "  >
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 "  >
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">#</span>
                                                        <input type="number"  class="form-control" value="<?php echo $filters[3]['id']; ?>" name="3-id" required="true" onkeyup="" placeholder="ID">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6 "  >
                                                <div class="form-group">
                                                    <input class="form-control" type="number" name="3-protocolo" value="<?php echo $filters[3]['protocolo']; ?>" onkeyup="" placeholder="PROTOCOLO">
                                                </div>
                                            </div>
                                            <div class=" col-md-3 col-sm-6" >
                                                <div class="form-group">
                                                    <input class="form-control"  type="text" name="3-client" value="<?php echo $filters[3]['client']; ?>" placeholder="NOME CLIENTE">
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-6"  >
                                                <div class="form-group">
                                                    <input class="form-control"  type="number" name="3-carteirinha" value="<?php echo $filters[3]['carteirinha']; ?>" onkeyup="" placeholder="CARTEIRINHA">
                                                </div>
                                            </div>

                                            <div class=" col-md-9 col-sm-12" >
                                                <div class="form-group">
                                                    <input class="form-control"  type="text" name="3-subject" value="<?php echo $filters[3]['subject']; ?>" placeholder="ASSUNTO DO REGISTRO">
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="select-placeholder form-group" app-field-wrapper="3-period">
                                                    <select id="3-period" name="3-period" class="selectpicker" required="true" onchange="" data-width="100%" 
                                                            data-none-selected-text="PERÍODO" data-live-search="true">
                                                        <option value="d" <?php
                                                        if ($filters[3]['period'] == 'd') {
                                                            //echo 'selected';
                                                        }
                                                        ?>>Recebidos hoje</option>
                                                        <option value="s" selected <?php
                                                        if ($filters[3]['period'] == 's') {
                                                            //echo 'selected';
                                                        }
                                                        ?>>Recebidos na semana</option>
                                                        <option value="m" <?php
                                                        if ($filters[3]['period'] == 'm' || $filters[3]['period'] == '') {
                                                           // echo 'selected';
                                                        }
                                                        ?>>Recebidos no mês</option>
                                                        <option value="Y" <?php
                                                        if ($filters[3]['period'] == 'Y') {
                                                            //echo 'selected';
                                                        }
                                                        ?>>Recebidos no ano</option>
                                                        <option value="-" <?php
                                                        if ($filters[3]['period'] == '-') {
                                                            //echo 'selected';
                                                        }
                                                        ?>>Todos</option>

                                                    </select>
                                                </div>                    
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-12 col-sm-12 ">                    
                                        <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="reload_tables('registro_ocorrencia_vinculado', '3');">
                                            Buscar Registros <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>


                                    </div>

                                    <div class="col-md-12">

                                        <?php
                                        $data['tipo'] = 3;
                                        //$this->load->view('gestao_corporativa/registro_ocorrencia/summary', $data);
                                        ?>
                                        <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                        <div class="clearfix"></div>
                                        <input name="3-type" type="hidden" value="3">
                                        <?php
                                        $table_data = [];

                                        $table_data = array_merge($table_data, array(
                                            '<strong style="font-weight: bold">ID</strong>',
                                            '<strong style="font-weight: bold">ASSUNTO</strong>',
                                            '<strong style="font-weight: bold">CATEGORIA</strong>',
                                            '<strong style="font-weight: bold">NOTIFICANTE</strong>',
                                            '<strong style="font-weight: bold">VALIDADE</Strong>',
                                            //'<strong style="font-weight: bold">SETOR RESPONSÁVEL</strong>',
                                            '<strong style="font-weight: bold">RESPONSABILIDADE</strong>',
                                            '<strong style="font-weight: bold">RESPOSTA</strong>',
                                            '<strong style="font-weight: bold">STATUS</strong>',
                                                //'Inicio/Fim',
                                                //'Responsabilidade',
                                        ));
                                        render_datatable($table_data, 'registro_ocorrencia_vinculado');
                                        ?>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div role="tabpane4" class="tab-pane" id="contact_info4">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 "  >
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6 "  >
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">#</span>
                                                    <input type="number"  class="form-control" value="<?php echo $filters[4]['id']; ?>" name="4-id" required="true" onkeyup="" placeholder="ID">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 "  >
                                            <div class="form-group">
                                                <input class="form-control" type="number" name="4-protocolo" value="<?php echo $filters[4]['protocolo']; ?>" onkeyup="" placeholder="PROTOCOLO">
                                            </div>
                                        </div>
                                        <div class=" col-md-3 col-sm-6" >
                                            <div class="form-group">
                                                <input class="form-control"  type="text" name="4-client" value="<?php echo $filters[4]['client']; ?>" placeholder="NOME CLIENTE">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6"  >
                                            <div class="form-group">
                                                <input class="form-control"  type="number" name="4-carteirinha" value="<?php echo $filters[4]['carteirinha']; ?>" onkeyup="" placeholder="CARTEIRINHA">
                                            </div>
                                        </div>

                                        <div class=" col-md-9 col-sm-12" >
                                            <div class="form-group">
                                                <input class="form-control"  type="text" name="4-subject" value="<?php echo $filters[4]['subject']; ?>" placeholder="ASSUNTO DO REGISTRO">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="select-placeholder form-group" app-field-wrapper="4-period">
                                                <select id="4-period" name="4-period" class="selectpicker" required="true" onchange="" data-width="100%" 
                                                        data-none-selected-text="PERÍODO" data-live-search="true">
                                                    <option value="d" <?php
                                                    if ($filters[4]['period'] == 'd' || $filters[4]['period'] == '') {
                                                        //echo 'selected';
                                                    }
                                                    ?>>Recebidos hoje</option>
                                                    <option value="s" selected <?php
                                                    if ($filters[4]['period'] == 's' || $filters[4]['period'] == '') {
                                                        //echo 'selected';
                                                    }
                                                    ?>>Recebidos na semana</option>
                                                    <option value="m" <?php
                                                    if ($filters[4]['period'] == 'm') {
                                                        //echo 'selected';
                                                    }
                                                    ?>>Recebidos no mês</option>
                                                    <option value="Y" <?php
                                                    if ($filters[4]['period'] == 'Y') {
                                                        //echo 'selected';
                                                    }
                                                    ?>>Recebidos no ano</option>
                                                    <option value="-" <?php
                                                    if ($filters[4]['period'] == '-') {
                                                        //echo 'selected';
                                                    }
                                                    ?>>Todos</option>

                                                </select>
                                            </div>                    
                                        </div>

                                    </div>

                                </div>
                                <div class="col-md-12 col-sm-12 ">                    
                                    <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="reload_tables('registro_ocorrencia_vinculado_setor', '4');">
                                        Buscar Registros <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>


                                </div>

                                <div class="col-md-12">

                                    <?php
                                    $data['tipo'] = 4;
                                    //$this->load->view('gestao_corporativa/registro_ocorrencia/summary', $data);
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <input name="4-type" type="hidden" value="4">
                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        '<strong style="font-weight: bold">ID</strong>',
                                        '<strong style="font-weight: bold">ASSUNTO</strong>',
                                        '<strong style="font-weight: bold">CATEGORIA</strong>',
                                        '<strong style="font-weight: bold">NOTIFICANTE</strong>',
                                        '<strong style="font-weight: bold">VALIDADE</Strong>',
                                        //'<strong style="font-weight: bold">SETOR RESPONSÁVEL</strong>',
                                        '<strong style="font-weight: bold">RESPONSABILIDADE</strong>',
                                        '<strong style="font-weight: bold">RESPOSTA</strong>',
                                        '<strong style="font-weight: bold">STATUS</strong>',
                                            //'Inicio/Fim',
                                            //'Responsabilidade',
                                    ));
                                    render_datatable($table_data, 'registro_ocorrencia_vinculado_setor');
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>



<div id="modal_wrapper"></div>



<script>

    $(function () {
       // reload_tables_all();
    });

    function reload_tables(table, type, filter = true) {

        if ($.fn.DataTable.isDataTable('.table-' + table)) {
            $('.table-' + table).DataTable().destroy();
        }

        var Params = {};

        Params['type'] = '[name="' + type + '-type"]';

        Params['categoria_id'] = '[name="categoria_id"]';
        Params['status'] = '[name="status"]';
        Params['departments'] = '[name="departments"]';
        Params['user_created'] = '[name="user_created"]';

        Params['id'] = '[name="' + type + '-id"]';
        Params['subject'] = '[name="' + type + '-subject"]';
        Params['protocolo'] = '[name="' + type + '-protocolo"]';
        Params['client'] = '[name="' + type + '-client"]';
        Params['carteirinha'] = '[name="' + type + '-carteirinha"]';

        if (type == '2') {
                Params['atribuido_a'] = '[name="' + type + '-atribuido_a"]';
            
        } 
        Params['period'] = '[name="' + type + '-period"]';
        if (filter == true) {
            Params['filter'] = '[name="staffid"]';
        }

        initDataTable('.table-' + table, '<?php echo base_url(); ?>' + 'gestao_corporativa/Registro_ocorrencia/table', [4], [4], Params, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0, 'desc'))); ?>);

    }

    function reload_tables_all() {
        reload_tables('registro_ocorrencia_my', '1', false);
        reload_tables('registro_ocorrencia', '2', false);
        reload_tables('registro_ocorrencia_vinculado', '3', false);
        reload_tables('registro_ocorrencia_vinculado_setor', '4', false);

    }

</script>
</body>
</html>
