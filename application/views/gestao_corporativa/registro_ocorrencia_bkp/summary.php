<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>




<div class="row">
    <?php
    $this->load->model('Registro_ocorrencia_model');
    $statuses = $this->Registro_ocorrencia_model->get_ro_status();
    ?>


    <div class="col-md-12">

        <h4 class="no-margin">Resumo de registros</h4>
    </div>
    <?php
    foreach ($statuses as $status) {
        $_where = '';

        $_where = 'and status=' . $status['id'];

        if ($tipo == 1) {
            $_where .= ' and user_created = ' . get_staff_user_id();
        } elseif ($tipo == 2) {
            $this->load->model('Registro_ocorrencia_model');
            $dep = $this->Registro_ocorrencia_model->get_staff_department();

            $part = ' and id IN (Select r.id as id_2 FROM tbl_intranet_registro_ocorrencia r '
                    . 'LEFT JOIN tbl_intranet_categorias c ON c.id = r.categoria_id '
                    . 'WHERE (';
            for ($i = 0; $i < count($dep); $i++) {
                $part .= 'c.responsavel = ' . $dep[$i]['departmentid'] . ' ';
                if (($i + 1) != count($dep)) {
                    $part .= ' OR ';
                }
            }
            $part .= '))';

            $_where .= $part;
            //echo $_where; 
        } elseif ($tipo == 3) {
            $staff = get_staff_user_id();
            $_where .= " and id IN (SELECT tbl_intranet_registro_ocorrencia_atuantes_por_registro.registro_id from tbl_intranet_registro_ocorrencia_atuantes_por_registro "
                    . "where tbl_intranet_registro_ocorrencia_atuantes_por_registro.deleted = 0 "
                    . "and tbl_intranet_registro_ocorrencia_atuantes_por_registro.staff_id = $staff)";
        } elseif ($tipo == 4) {
            $staff = get_staff_user_id();
            $_where .= " and id IN (SELECT tbl_intranet_registro_ocorrencia_atuantes_por_registro.registro_id
                from tbl_intranet_registro_ocorrencia_atuantes_por_registro
                inner join tblstaff_departments on tblstaff_departments.staffid = tbl_intranet_registro_ocorrencia_atuantes_por_registro.staff_id
                where tbl_intranet_registro_ocorrencia_atuantes_por_registro.deleted = 0
                and tblstaff_departments.departmentid in(select departmentid from tblstaff_departments where staffid = $staff))";
        }
        ?>

        <div class="col-md-3 col-xs-6 mbot15 border-right">
            <a href="#" onclick=" novo_change('status_<?php echo $status['id']; ?>');
                                            reload_tables('registro_ocorrencia', '2');
                                            reload_tables('registro_ocorrencia_vinculado', '3');
                                            reload_tables('registro_ocorrencia_my', '1');
                                            reload_tables('registro_ocorrencia_vinculado_setor', '4');
                                            dt_custom_view('status_<?php echo $status['id']; ?>', '.tickets-table', 'status_<?php echo $status['id']; ?>');
                                            return false;" >
                <h3 class="bold"><?php echo $this->Registro_ocorrencia_model->get_count_ro($_where); ?></h3>
                <span style="color:<?php echo $status['color']; ?>">
    <?php echo $status['label']; ?>
                </span>
            </a>
        </div>
<?php } ?>
</div>