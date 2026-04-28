<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (!$intranet) { ?>
    <?php init_head(); ?>
<?php } else { ?>
    <?php init_head_intranet_novo(); ?>
    <style>
        body {
            font-family:'Open Sans';
            background:#f1f1f1;
        }
        h3 {
            margin-top: 7px;
            font-size: 18px;
        }

        .install-row.install-steps {
            margin-bottom:15px;
            box-shadow: 0px 0px 1px #d6d6d6;
        }

        .control-label {
            font-size:13px;
            font-weight:600;
        }
        .padding-10 {
            padding:10px;
        }
        .mbot15 {
            margin-bottom:15px;
        }
        .bg-default {
            background: #03a9f4;
            border:1px solid #03a9f4;
            color:#fff;
        }
        .bg-success {
            border: 1px solid #dff0d8;
        }
        .bg-not-passed {
            border:1px solid #f1f1f1;
            border-radius:2px;
        }
        .bg-not-passed {
            border-right:0px;
        }
        .bg-not-passed.finish {
            border-right:1px solid #f1f1f1 !important;
        }
        .bg-not-passed h5 {
            font-weight:normal;
            color:#6b6b6b;
        }
        .form-control {
            box-shadow:none;
        }
        .bold {
            font-weight:600;
        }
        .col-xs-5ths,
        .col-sm-5ths,
        .col-md-5ths,
        .col-lg-5ths {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }
        .col-xs-5ths {
            width: 20%;
            float: left;
        }
        b {
            font-weight:600;
        }
        .bootstrap-select .btn-default {
            background: #fff !important;
            border: 1px solid #d6d6d6 !important;
            box-shadow: none;
            color: #494949 !important;
            padding: 6px 12px;
        }
    </style>
<?php } ?>
<?php if (!$intranet) { ?>
    <div id="wrapper">
    <?php } ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (has_permission('staff', '', 'create')) { ?>
                            <div class="_buttons">
                                <a href="<?php
                                if ($intranet) {
                                    echo admin_url('staff/member?intranet=intranet');
                                } else {
                                    echo admin_url('staff/member');
                                }
                                ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_staff'); ?></a>
                            </div>
                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading" />
                        <?php } ?>
                        <div class="clearfix"></div>
                        <?php if ($intranet != '') { ?>
                        <input name="intranet" value="intranet" type="hidden">
                    <?php } ?>
                    <?php
                    $table_data = array(
                        _l('staff_dt_name'),
                        _l('staff_dt_email'),
                        _l('role'),
                        _l('staff_dt_last_Login'),
                        _l('staff_dt_active'),
                    );
                    $custom_fields = get_custom_fields('staff', array('show_on_table' => 1));
                    foreach ($custom_fields as $field) {
                        array_push($table_data, $field['name']);
                    }
                    render_datatable($table_data, 'staff');
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!$intranet) { ?>
    </div>
<?php } ?>
<div class="modal fade" id="delete_staff" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">

        <?php
        if ($intranet) {
            echo form_open(admin_url('staff/delete?intranet=intranet', array('delete_staff_form')));
        } else {
            echo form_open(admin_url('staff/delete', array('delete_staff_form')));
        }
        ?>

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_staff'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_staff_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', $staff_members, array('staffid', array('firstname', 'lastname')), 'staff_member', get_staff_user_id(), array(), array(), '', '', false);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-danger _delete"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>
<script>
    $(function () {
        var Params = {};
        <?php if($intranet != ''){?>
        Params['intranet'] = '[name="intranet"]';
        <?php }?>
        initDataTable('.table-staff', window.location.href, [0], [0], Params);
    });
    function delete_staff_member(id) {
        $('#delete_staff').modal('show');
        $('#transfer_data_to').find('option').prop('disabled', false);
        $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
        $('#delete_staff .delete_id input').val(id);
        $('#transfer_data_to').selectpicker('refresh');
    }
</script>
</body>
</html>
