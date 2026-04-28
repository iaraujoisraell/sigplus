<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>




<div class="row">
    <?php
    if ($tipo == 1) {
        $this->load->model('Workflow_model');
        $statuses = $this->Workflow_model->get_status();
        ?>

        <?php
        foreach ($statuses as $status) {
            $_where = '';

            $_where = 'and status=' . $status['id'];

            $_where .= ' and user_created = ' . get_staff_user_id();
            ?>

            <div class="col-md-3 col-xs-6 mbot15 border-right">
                <a href="#" onclick="" >
                    <h3 class="bold"><?php echo $this->Workflow_model->get_count($_where); ?></h3>
                    <span style="color:<?php echo $status['color']; ?>">
                        <?php echo $status['label']; ?>
                    </span>
                </a>
            </div>
        <?php } ?>
        <?php
    } elseif ($tipo == 3) {
        $this->load->model('Workflow_model');
        $statuses = $this->Workflow_model->get_status_setor();
        ?>

        <?php
        foreach ($statuses as $status) {

            $sql = 'SELECT SQL_CALC_FOUND_ROWS count(*) as quantidade FROM tbl_intranet_workflow '
                    . 'LEFT JOIN tbl_intranet_categorias ON tbl_intranet_categorias.id = tbl_intranet_workflow.categoria_id '
                    . 'LEFT JOIN tbldepartments ON tbldepartments.departmentid = tbl_intranet_categorias.responsavel '
                    . 'LEFT JOIN tbl_intranet_workflow_cancel ON tbl_intranet_workflow_cancel.id = tbl_intranet_workflow.cancel_id '
                    . 'LEFT JOIN tbl_intranet_registro_atendimento ON tbl_intranet_registro_atendimento.id = tbl_intranet_workflow.registro_atendimento_id '
                    . 'LEFT JOIN tblclients ON tblclients.userid = tbl_intranet_registro_atendimento.client_id '
                    . 'INNER JOIN tbl_intranet_workflow_fluxo_andamento ON tbl_intranet_workflow_fluxo_andamento.workflow_id = tbl_intranet_workflow.id '
                    . 'INNER JOIN tbl_intranet_categorias_fluxo ON tbl_intranet_categorias_fluxo.id = tbl_intranet_workflow_fluxo_andamento.fluxo_id '
                    . 'INNER JOIN tbldepartments as dep_setaff ON dep_setaff.departmentid = tbl_intranet_workflow_fluxo_andamento.department_id '
                    . 'INNER JOIN tblstaff_departments on tblstaff_departments.departmentid = dep_setaff.departmentid '
                    . 'WHERE tbl_intranet_workflow.deleted = 0 '
                    . 'AND tbl_intranet_workflow.empresa_id = 4 AND tblstaff_departments.staffid = ' . get_staff_user_id() . ' AND tbl_intranet_workflow_fluxo_andamento.deleted = 0 '
                    . 'AND tbl_intranet_workflow_fluxo_andamento.atribuido_a != ' . get_staff_user_id() . ' AND tbl_intranet_workflow.status != 3 AND tbl_intranet_workflow_fluxo_andamento.concluido != 1 '
                  
                    . 'AND ' . $status['where']
                    . ' group by tbl_intranet_workflow_fluxo_andamento.id ';
            ?>

            <div class="col-md-4 col-xs-4 mbot15 border-right">
                <a href="#" onclick="reload_tables('workflow_setor', '3', '<?php echo $status['id'];?>'); reload_tables('workflow_assumidos', '2', '<?php echo $status['id'];?>');" >
                    <h3 class="bold"><?php echo $this->Workflow_model->get_count_setor($sql); ?></h3>
                    <span style="color:<?php echo $status['color']; ?>">
                        <?php echo $status['label']; ?>
                    </span>
                </a>
            </div>
        <?php } ?>
        <?php
    } elseif ($tipo == 2) {
        $this->load->model('Workflow_model');
        $statuses = $this->Workflow_model->get_status_setor();
        ?>

        <?php
        foreach ($statuses as $status) {

            $sql = 'SELECT  count(tbl_intranet_workflow.id) as quantidade FROM tbl_intranet_workflow '
                    . 'LEFT JOIN tbl_intranet_categorias ON tbl_intranet_categorias.id = tbl_intranet_workflow.categoria_id '
                    . 'LEFT JOIN tbldepartments ON tbldepartments.departmentid = tbl_intranet_categorias.responsavel '
                    . 'LEFT JOIN tbl_intranet_workflow_cancel ON tbl_intranet_workflow_cancel.id = tbl_intranet_workflow.cancel_id '
                    . 'LEFT JOIN tbl_intranet_registro_atendimento ON tbl_intranet_registro_atendimento.id = tbl_intranet_workflow.registro_atendimento_id '
                    . 'LEFT JOIN tblclients ON tblclients.userid = tbl_intranet_registro_atendimento.client_id '
                    . 'INNER JOIN tbl_intranet_workflow_fluxo_andamento ON tbl_intranet_workflow_fluxo_andamento.workflow_id = tbl_intranet_workflow.id '
                    . 'INNER JOIN tbl_intranet_categorias_fluxo ON tbl_intranet_categorias_fluxo.id = tbl_intranet_workflow_fluxo_andamento.fluxo_id '
                    . 'INNER JOIN tbldepartments as dep_setaff ON dep_setaff.departmentid = tbl_intranet_workflow_fluxo_andamento.department_id '
                    . 'INNER JOIN tblstaff_departments on tblstaff_departments.departmentid = dep_setaff.departmentid '
                    . 'WHERE tbl_intranet_workflow.deleted = 0 AND tbl_intranet_workflow.empresa_id = 4 AND tblstaff_departments.staffid = ' . get_staff_user_id() . ' AND tbl_intranet_workflow_fluxo_andamento.deleted = 0 '
                    . 'AND tbl_intranet_workflow_fluxo_andamento.atribuido_a = ' . get_staff_user_id() . ' AND tbl_intranet_workflow_fluxo_andamento.concluido != 1 '
                    . 'AND ' . $status['where']
                    . '  group by tbl_intranet_workflow_fluxo_andamento.id ';
            // echo $sql;
            ?>

            <div class="col-md-4 col-xs-4 mbot15 border-right">
                <a href="#" onclick="reload_tables('workflow_setor', '3', '<?php echo $status['id'];?>'); reload_tables('workflow_assumidos', '2', '<?php echo $status['id'];?>');" >
                    <h3 class="bold"><?php echo $this->Workflow_model->get_count_setor($sql); ?></h3>
                    <span style="color:<?php echo $status['color']; ?>">
                        <?php echo $status['label']; ?>
                    </span>
                </a>
            </div>
        <?php } ?>

        <?php
    } elseif ($tipo == 4) {
        $this->load->model('Workflow_model');
        $statuses = $this->Workflow_model->get_status();
        ?>

        <?php
        foreach ($statuses as $status) {

            $sql = 'SELECT  count(tbl_intranet_workflow.id) as quantidade FROM tbl_intranet_workflow
    LEFT JOIN tbl_intranet_categorias ON tbl_intranet_categorias.id = tbl_intranet_workflow.categoria_id LEFT JOIN tbldepartments ON tbldepartments.departmentid = tbl_intranet_categorias.responsavel LEFT JOIN tbl_intranet_workflow_cancel ON tbl_intranet_workflow_cancel.id = tbl_intranet_workflow.cancel_id LEFT JOIN tbl_intranet_registro_atendimento ON tbl_intranet_registro_atendimento.id = tbl_intranet_workflow.registro_atendimento_id LEFT JOIN tblclients ON tblclients.userid = tbl_intranet_registro_atendimento.client_id INNER JOIN tbl_intranet_workflow_fluxo_andamento ON tbl_intranet_workflow_fluxo_andamento.workflow_id = tbl_intranet_workflow.id INNER JOIN tbl_intranet_categorias_fluxo ON tbl_intranet_categorias_fluxo.id = tbl_intranet_workflow_fluxo_andamento.fluxo_id INNER JOIN tbldepartments as dep_setaff ON dep_setaff.departmentid = tbl_intranet_workflow_fluxo_andamento.department_id INNER JOIN tblstaff_departments on tblstaff_departments.departmentid = dep_setaff.departmentid
    
    WHERE  tbl_intranet_workflow.deleted = 0  AND tbl_intranet_workflow.empresa_id = 4  AND tblstaff_departments.staffid = ' . get_staff_user_id() . '  
    AND tbl_intranet_workflow_fluxo_andamento.deleted  = 0  AND tbl_intranet_workflow_fluxo_andamento.concluido = 1 and tbl_intranet_workflow.status=' . $status['id'] . ' group by tbl_intranet_workflow.id 
     order by tbl_intranet_workflow.id desc ';
            // echo $sql; 
            ?>

            <div class="col-md-3 col-xs-6 mbot15 border-right">
                <a href="#" onclick="" >
                    <h3 class="bold"><?php echo $this->Workflow_model->get_count_setor($sql); ?></h3>
                    <span style="color:<?php echo $status['color']; ?>">
                        <?php echo $status['label']; ?>
                    </span>
                </a>
            </div>
        <?php } ?>
    <?php } ?>

</div>