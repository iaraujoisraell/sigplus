<?php
$active = false;
$have = false;
?>
<h4 class="customer-profile-group-heading"><?php echo 'Empresa - Configurações'; ?></h4>

<div class="mtop15">
    <div class="horizontal-scrollable-tabs">
        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
        <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                <?php
                if (has_permission_intranet('company', '', 'notification') || is_admin()) {
                    $have = true;
                    $active = true;
                    ?>
                    <li role="presentation" class="active">
                        <a href="#notify" aria-controls="notify" role="tab" data-toggle="tab">
                            Notificações
                        </a>
                    </li>
                <?php } ?>
                <?php
                if (has_permission_intranet('company', '', 'list') || is_admin()) {
                    $have = true;
                    $active = true;
                    ?>
                    <li role="presentation" class="<?php
                    if ($active == false) {
                        echo 'active';
                    }
                    ?>">
                        <a href="#list" aria-controls="list" role="tab" data-toggle="tab">
                            Listas
                        </a>
                    </li>
                <?php } ?>
                <?php
                if (has_permission_intranet('company', '', 'apis') || is_admin()) {
                    $have = true;
                    $active = true;
                    ?>
                    <li role="presentation" class="<?php
                    if ($active == false) {
                        echo 'active';
                    }
                    ?>">
                        <a href="#api" aria-controls="api" role="tab" data-toggle="tab">
                            API's/Integrações
                        </a>
                    </li>
                <?php } ?>
                <?php
                if (has_permission_intranet('company', '', 'type_login') || is_admin()) {
                    $have = true;
                    $active = true;
                    ?>
                    <li role="presentation" class="<?php
                    if ($active == false) {
                        echo 'active';
                    }
                    ?>">
                        <a href="#type_login" aria-controls="type_login" role="tab" data-toggle="tab">
                            Tipos de acesso
                        </a>
                    </li>
                <?php } ?>
                <?php if (has_permission_intranet('company', '', 'cancel') || is_admin()) { ?>
                    <li role="presentation" class="">
                        <a href="#cancel" aria-controls="cancel" role="tab" data-toggle="tab">
                            Cancelamentos Workflow
                        </a>
                    </li>
                <?php } ?>
                <?php if (has_permission_intranet('company', '', 'macs') || is_admin()) { ?>
                    <li role="presentation" class="">
                        <a href="#macs" aria-controls="macs" role="tab" data-toggle="tab">
                            MAC
                        </a>
                    </li>
                <?php } ?>
                <?php if (has_permission_intranet('company', '', 'assignature') || is_admin()) { ?>
                    <!--<li role="presentation" class="">
                        <a href="#assignature" aria-controls="assignature" role="tab" data-toggle="tab">
                            Assinatura de email
                        </a>
                    </li>-->
                <?php } ?>
                <!--<li role="presentation" class="">
                    <a href="#tipos_fast" aria-controls="tipos_fast" role="tab" data-toggle="tab">
                        Tipos de Solicitações Rápidas
                    </a>
                </li>-->
            </ul>
        </div>
    </div>
    <?php $active = false; ?>
    <div class="tab-content mtop15">
        <?php if (has_permission_intranet('company', '', 'notification') || is_admin()) {
            ?>
            <div role="tabpanel" class="tab-pane active" id="notify">
                <?php $active = true;     ?>
                <div class="table-responsive">

                    <table class="table table-bordered roles no-margin">
                        <thead>
                            <tr>
                                <th>AÇÃO</th>
                                <th>SISTEMA</th>
                                <th>EMAIL</th><!-- comment -->
                                <th>SMS</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach (get_actions() as $feature => $permission) {
//                            /print_r($permission);
                                ?>
                                <tr data-name="<?php echo $feature; ?>">
                                    <td>
                                        <!--<?php if ($permission['assunto'] != '') { ?> 
                                                                                                                                                                                                                    <b><span  class="label label-sm label-success"><i class="fa fa-check"></i></span></b>
                                        <?php } else { ?>
                                                                                                                                                                                                                    <b><span  class="label label-sm label-danger"><i class="fa fa-times"></i></span></b>
                                        <?php } ?>-->

                                        <b><span  class="label label-sm label-info"><?php echo $permission['from']; ?></span> <?php echo $permission['name']; ?> <a onclick="edit_notify('<?php echo $feature; ?>');"><i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo $permission['assunto']; ?>"></i></a></b>
                                    </td>
                                    <?php
                                    foreach ($permission['capabilities'] as $name) {
                                        $checked = '';
                                        ?>
                                        <td>
                                            <div class="onoffswitch">
                                                <?php
                                                if (has_notification_action($name, $feature)) {
                                                    $checked = 'checked';
                                                }
                                                ?>
                                                <input type="checkbox" <?php echo $checked; ?> class="onoffswitch-checkbox" onchange="teste('<?php echo $feature; ?>_<?php echo $name; ?>', '<?php echo $feature; ?>', '<?php echo $name; ?>');" id="<?php echo $feature; ?>_<?php echo $name; ?>">
                                                <label class="onoffswitch-label" for="<?php echo $feature; ?>_<?php echo $name; ?>"></label>
                                            </div>
                                        </td>
                                    <?php } ?>


                                </tr>
                            <?php } ?>


                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        <?php
        if (has_permission_intranet('company', '', 'api') || is_admin()) {
            $active = true;
            ?>
            <div role="tabpanel" class="tab-pane <?php
            if ($active == false) {
                echo 'active';
            }
            ?>" id="api">
                <div class="row">
                    <div class="col-md-12">
                        <h4>CREDENCIAIS TWW</h4>

                    </div>
                    <div class="form-input col-md-5">
                        <input type="text" name="twwuser" id="twwuser" class="form-control" placeholder="Usuário/Login" value="<?php echo get_option('twwuser');?>">

                    </div>
                    <div class="form-input col-md-5">
                        <input type="text" name="twwpassword" id="twwpassword" class="form-control" placeholder="Senha" value="<?php echo get_option('twwpassword');?>">

                    </div>


                    <div class="col-md-2">
                        <button class="btn btn-info w-100" onclick="save_tww_info();" id="new-categoria-insert"><?php echo 'Salvar'; ?></button>

                    </div>

                </div>
                <div class="row" style='margin-top: 10px;'>
                    <hr>
                    <div class="col-md-12">
                        <h4>API's - Cadastro de API</h4>

                    </div>
                </div>
                <?php //echo get_actions();    ?>

                <?php
                $data['rel_type'] = 'api';
                $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);
                ?>

            </div>
        <?php } ?>
        <?php
        if (has_permission_intranet('company', '', 'type_login') || is_admin()) {
            $active = true;
            ?>
            <div role="tabpanel" class="tab-pane <?php
            if ($active == false) {
                echo 'active';
            }
            ?>" id="type_login">

                <?php //echo get_actions();     ?>
                <div class="mtop15">
                    <div class="_buttons">
                        <button onclick="Acess_type('');" class="btn btn-info pull-left" ><?php echo 'Add Tipo de acesso'; ?></button>    

                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />

                    <?php
                    $table_data = [];
                    array_push($table_data, 'Nome');
                    array_push($table_data, 'Orientação');
                    array_push($table_data, 'API');
                    array_push($table_data, 'Opções');
                    render_datatable($table_data, 'acess_type');
                    ?>

                </div>

            </div>
        <?php } ?>
        <?php if (has_permission_intranet('company', '', 'list') || is_admin()) { ?>
            <div role="tabpane2" class="tab-pane <?php
            if ($active == false) {
                echo 'active';
            }
            ?>" id="list">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" name="_list" id="_list" class="form-control" placeholder="Digite o tipo de lista">
                            <span class="input-group-btn">
                                <button class="btn btn-info p7" onclick="save_list();" id="new-categoria-insert"><?php echo 'Add'; ?></button>
                            </span>
                        </div>


                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading" />

                        <?php
                        $table_data = [];
                        array_push($table_data, 'Lista');
                        array_push($table_data, 'Cadastro');
                        array_push($table_data, 'Opções');
                        render_datatable($table_data, 'list');
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (has_permission_intranet('portal', '', 'cancel') || is_admin()) { ?>
            <div role="tabpane2" class="tab-pane " id="cancel">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" name="cancellation" id="cancellation" class="form-control" placeholder="Digite o tipo de cancelamento">
                            <span class="input-group-btn">
                                <button class="btn btn-info p7" onclick="save();" id="new-categoria-insert"><?php echo 'Add Tipo'; ?></button>
                            </span>
                        </div>


                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading" />

                        <?php
                        $table_data = [];
                        array_push($table_data, 'Data');
                        array_push($table_data, 'Cadastro');
                        array_push($table_data, 'Ativo');
                        array_push($table_data, 'Deletar');
                        render_datatable($table_data, 'cancel');
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (has_permission_intranet('company', '', 'macs') || is_admin()) { ?>
            <div role="tabpane2" class="tab-pane " id="macs">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-input col-md-6">
                                <input type="text" name="mac" id="mac" class="form-control" placeholder="Mac">

                            </div>

                            <div class="form-input col-md-6">
                                <?php echo render_datetime_input('date_mac', '', '', array("placeholder" => "Limite")); ?>

                            </div>
                            <div class="form-input col-md-12">
                                <br>
                                <textarea name="desc_mac" id="desc_mac" class="form-control" placeholder="Escreva a descrição"></textarea>
                            </div>

                            <div class="col-md-12">
                                <div class="_buttons">
                                    <br>
                                    <button class="btn btn-info pull-right" onclick="save_mac();" id="new-categoria-insert"><?php echo 'Salvar'; ?></button>

                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading" />

                        <?php
                        $table_data = [];
                        array_push($table_data, 'Mac');
                        array_push($table_data, 'Descrição');
                        array_push($table_data, 'Data');
                        array_push($table_data, 'Cadastro');
                        array_push($table_data, 'Ativo');
                        array_push($table_data, 'Deletar');
                        render_datatable($table_data, 'macs');
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (has_permission_intranet('company', '', 'assignature') || is_admin()) { ?>
            <!--<div role="tabpane2" class="tab-pane " id="assignature">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
            <?php echo form_open_multipart('gestao_corporativa/Company/add_assignature', array('id' => 'formAssignature')); ?>
                            <div class="col-md-5">

                                <h4>Assinatura de Email Principal (Imagem)</h4>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Título">

                                <br>
                                <input data-name_value="ASSIGNATURE" type="file" name="file" id="file" class="form-control" placeholder="Imagem" data-target="assets/intranet/arquivos/assignature_arquivos/assignature/" accept=".jpg, .jpeg">

                                <br>
                                <button class="btn btn-info  w-100" id="new-categoria-insert"><?php echo 'Salvar'; ?></button>

                            </div>
            <?php echo form_close(); ?>


                            <div class="col-md-7">



            <?php
            $table_data = [];
            array_push($table_data, 'Mac');
            array_push($table_data, 'Descrição');
            array_push($table_data, 'Data');
            array_push($table_data, 'Cadastro');
            array_push($table_data, 'Ativo');
            array_push($table_data, 'Deletar');
            render_datatable($table_data, 'assignature');
            ?>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="row">
            <?php echo form_open_multipart('gestao_corporativa/Company/add_assignature_other', array('id' => 'formAssignature_other')); ?>
                            <div class="col-md-5">

                                <h4>Imagem Secundária</h4>
                                <input type="text" name="title_" id="title_" class="form-control" placeholder="Título">

                                <br>
                                <input data-name_value="ASSIGNATURE_" type="file" name="file" id="file" class="form-control" placeholder="Imagem" data-target="assets/intranet/arquivos/assignature_arquivos/assignature_/" accept=".jpg, .jpeg">

                                <br>
                                <button class="btn btn-info  w-100"  id="new-categoria-insert"><?php echo 'Salvar'; ?></button>

                            </div>

            <?php echo form_close(); ?>
                            <div class="col-md-7">
            <?php
            $table_data = [];
            array_push($table_data, 'Mac');
            array_push($table_data, 'Descrição');
            array_push($table_data, 'Data');
            array_push($table_data, 'Cadastro');
            array_push($table_data, 'Ativo');
            array_push($table_data, 'Deletar');
            render_datatable($table_data, 'assignature_others');
            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
        <?php } ?>


        <?php if ($have == false) { ?>
            SEM PERMISSÕES INTERNAS
        <?php } ?>
    </div>

</div>
<div id="modal_wrapper"></div>

<?php init_tail(); ?>
<script>


    $(document).ready(function () {
        $('#formAssignature').submit(function (e) {
            e.preventDefault();
            var uploadForm = $('#formAssignature');
            var formData = new FormData($('#formAssignature')[0]);
            var fieldToTargetMap = {};
            var fieldValueMap = {};
            uploadForm.find('input[type="file"]').each(function () {
                var field = $(this);
                var fieldName = field.attr('name');
                var target = field.data('target');
                var value = field.data('name_value');
                fieldToTargetMap[fieldName] = target;
                fieldValueMap[fieldName] = value;
            });

            // Adicione o mapeamento ao FormData
            formData.append('fieldToTargetMap', JSON.stringify(fieldToTargetMap));
            formData.append('fieldValueMap', JSON.stringify(fieldValueMap));
            formData.append('target', '0');
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('controle_upload/All.php'); ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var obj = JSON.parse(response);
                    var file = obj['file'];
                    var title = document.getElementById("title").value;
                    if (file != '') {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('gestao_corporativa/Company/add_assignature'); ?>",
                            data: {
                                title: title,
                                file: file,
                                target: 'assets/intranet/arquivos/assignature_arquivos/assignature/',
                                rel_type: 'assignature'
                            },
                            success: function (data) {
                                reload_assignature();
                                var obj = JSON.parse(data);
                                alert_float(obj.alert, obj.message);
                            }
                        });
                    }
                }
            });
        });
    });

    $(document).ready(function () {
        $('#formAssignature_other').submit(function (e) {
            e.preventDefault();
            var uploadForm = $('#formAssignature_other');
            var formData = new FormData($('#formAssignature_other')[0]);
            var fieldToTargetMap = {};
            var fieldValueMap = {};

            uploadForm.find('input[type="file"]').each(function () {
                var field = $(this);
                var fieldName = field.attr('name');
                var target = field.data('target');
                var value = field.data('name_value');
                fieldToTargetMap[fieldName] = target;
                fieldValueMap[fieldName] = value;
            });

            formData.append('fieldToTargetMap', JSON.stringify(fieldToTargetMap));
            formData.append('fieldValueMap', JSON.stringify(fieldValueMap));
            formData.append('target', '0');
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('controle_upload/All.php'); ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var obj = JSON.parse(response);
                    var file = obj['file'];
                    var title = document.getElementById("title_").value;
                    if (file != '') {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('gestao_corporativa/Company/add_assignature_others'); ?>",
                            data: {
                                title: title,
                                file: file,
                                target: 'assets/intranet/arquivos/assignature_arquivos/assignature_/',
                                rel_type: 'assignature_others'
                            },
                            success: function (data) {
                                reload_assignature_others();
                                var obj = JSON.parse(data);
                                alert_float(obj.alert, obj.message);
                            }
                        });
                    }

                }
            });
        });
    });
    function edit_notify(key) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Company/edit_notify", {
            key: key
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#edit_notify').is(':hidden')) {
                $('#edit_notify').modal({
                    show: true
                });
            }
        });
    }

    function teste(id, feature, capability) {
        let checkbox = document.getElementById(id);
        var active = 0;
        if (checkbox.checked) {
            active = 1;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/update_notifications'); ?>",
            data: {
                active: active, feature: feature, capability: capability
            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });


    }

    function Api(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Company/api", {
            slug: 'update_categoria',
            id: el
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#api_modal').is(':hidden')) {
                $('#api_modal').modal({
                    show: true
                })
                        ;
            }
        });
    }

    function Acess_type(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Company/acess_type", {
            id: el
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#acess_type').is(':hidden')) {
                $('#acess_type').modal({
                    show: true
                })
                        ;
            }
        });
    }

    $(function () {
        var Params = {};

        initDataTable('.table-api', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_api', [0], [0], Params, [1, 'desc']);
    });

    $(function () {
        var Params = {};

        initDataTable('.table-acess_type', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_acess_type', [0], [0], Params, [1, 'desc']);
    });

    function reload() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-acess_type')) {
            $('.table-acess_type').DataTable().destroy();
        }
        initDataTable('.table-acess_type', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_acess_type', [0], [0], Params, [1, 'desc']);
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-api')) {
            $('.table-api').DataTable().destroy();
        }
        initDataTable('.table-api', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_api', [0], [0], Params, [1, 'desc']);

    }

    function reload_assignature() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-acess_type')) {
            $('.table-acess_type').DataTable().destroy();
        }
        initDataTable('.table-assignature', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_assignature', [0], [0], Params, [1, 'desc']);

    }
    function reload_assignature_others() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-acess_type')) {
            $('.table-acess_type').DataTable().destroy();
        }
        initDataTable('.table-assignature_others', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_assignature_others', [0], [0], Params, [1, 'desc']);

    }

    function Campos(el, rel_type) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Categorias_campos/modal", {
            slug: 'campos_categoria',
            id: el,
            type: rel_type
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#Campos').is(':hidden')) {
                $('#Campos').modal({
                    show: true
                });
            }
        });
    }

    function Op_list(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Company/modal", {
            slug: 'list',
            id: el
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#list_modal').is(':hidden')) {
                $('#list_modal').modal({
                    show: true
                });
            }
        });
    }

    $(function () {
        var Params = {};

        initDataTable('.table-cancel', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_cancel', [0], [0], Params, [1, 'desc']);
    });

    $(function () {
        var Params = {};

        initDataTable('.table-macs', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_macs', [0], [0], Params, [1, 'desc']);
    });



    $(function () {
        var Params = {};

        initDataTable('.table-list', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_list', [0], [0], Params, [1, 'desc']);
    });

    $(function () {
        var Params = {};

        initDataTable('.table-assignature', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_assignature', [0], [0], Params, [1, 'desc']);
    });

    $(function () {
        var Params = {};

        initDataTable('.table-assignature_others', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_assignature_others', [0], [0], Params, [1, 'desc']);
    });


    function reload() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-cancel')) {
            $('.table-cancel').DataTable().destroy();
        }
        initDataTable('.table-cancel', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_cancel', [0], [0], Params, [1, 'desc']);
    }

    function reload_list() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-list')) {
            $('.table-list').DataTable().destroy();
        }
        initDataTable('.table-list', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_list', [0], [0], Params, [1, 'desc']);
    }

    function reload_macs() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-macs')) {
            $('.table-macs').DataTable().destroy();
        }
        initDataTable('.table-macs', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_macs', [0], [0], Params, [1, 'desc']);
    }

    function save() {
        var cancellation = document.getElementById("cancellation").value;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/add_cancel'); ?>",
            data: {
                cancellation: cancellation
            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload();
                document.getElementById("cancellation").value = '';
            }
        });
    }

    function save_mac() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/add_mac'); ?>",
            data: {
                mac: document.getElementById("mac").value,
                date_limit: document.getElementById("date_mac").value,
                description: document.getElementById("desc_mac").value

            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload_macs();
                document.getElementById("mac").value = '';
                document.getElementById("date_mac").value = '';
                document.getElementById("desc_mac").value = '';
            }
        });
    }

    function save_tww_info() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/add_tww_info'); ?>",
            data: {
                twwuser: document.getElementById("twwuser").value,
                twwpassword: document.getElementById("twwpassword").value

            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }

    function save_list() {
        var list = document.getElementById("_list").value;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/add_list'); ?>",
            data: {
                list: list
            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload_list();
                document.getElementById("_list").value = '';
            }
        });
    }
    function active(active, id) {

        if (active.checked) {
            var active = 1;
        } else {
            var active = 0;
        }
        //alert(active);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/cancel_active'); ?>",
            data: {
                active: active, id: id
            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }


    function _delete(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/delete_cancel'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }

    function delete_list(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/delete_list'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload_list();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }

    function delete_mac(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/delete_mac'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload_macs();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }

    function active_mac(active, id) {

        if (active.checked) {
            var active = 1;
        } else {
            var active = 0;
        }
        //alert(active);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/mac_active'); ?>",
            data: {
                active: active, id: id
            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }


</script>



