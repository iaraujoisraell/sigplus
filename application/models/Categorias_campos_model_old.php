<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 18/10/2022
 * @WannaLuiza
 * INTRANET - Model para Categorias - Campos
 */
class Categorias_campos_model extends App_Model {

    public function __construct() {
        parent::__construct();
        $this->empresa_id = $this->session->userdata('empresa_id');
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retorna todas as categorias da empresa conectada 
     */
    public function get_categorias($rel_type, $responsable = false, $empresa_id = '', $portal = false) {
        $empresa_id = ($empresa_id) ? $empresa_id : $this->session->userdata('empresa_id');
        $tbl_ro_categorias = 'tbl_intranet_categorias';
        $sql = "SELECT $tbl_ro_categorias.*, tbldepartments.name FROM $tbl_ro_categorias ";
        $sql .= "LEFT JOIN tbldepartments ON tbldepartments.departmentid = $tbl_ro_categorias.responsavel ";
        $where .= "WHERE ";
        $_where = [];

        if ($responsable == true) {
            if ($rel_type == 'workflow') {
                $_where[] = "(
            CASE 
                WHEN $tbl_ro_categorias.confidential = 1 THEN 
                    tbldepartments.departmentid IN (
                        SELECT departmentid 
                        FROM tblstaff_departments 
                        WHERE tblstaff_departments.staffid = " . get_staff_user_id() . " 
                        AND tblstaff_departments.departmentid = $tbl_ro_categorias.responsavel
                    )
                WHEN $tbl_ro_categorias.confidential = 0 THEN 
                    (
                        $tbl_ro_categorias.id IN (
                            SELECT categoria_id 
                            FROM tbl_intranet_categorias_fluxo 
                            INNER JOIN tblstaff_departments ON tblstaff_departments.departmentid =  tbl_intranet_categorias_fluxo.setor 
                            WHERE tblstaff_departments.staffid = " . get_staff_user_id() . " AND tbl_intranet_categorias_fluxo.deleted = 0
                        )
                        OR 
                        tbldepartments.departmentid IN (SELECT departmentid 
                            FROM tblstaff_departments 
                            WHERE tblstaff_departments.staffid = " . get_staff_user_id() . "
                        )
                    )
            END
        )";
            } else {
                $_where[] = "tbldepartments.departmentid IN (
                            SELECT departmentid 
                            FROM tblstaff_departments 
                            WHERE tblstaff_departments.staffid = " . get_staff_user_id() . "
                        )";
            }
        }
        $_where[] = "$tbl_ro_categorias.empresa_id = $empresa_id";
        $_where[] = "$tbl_ro_categorias.rel_type = '$rel_type'";
        $_where[] = "$tbl_ro_categorias.deleted = 0";
        $_where[] = "$tbl_ro_categorias.active = 1";
        if ($portal == true) {
            $_where[] = "$tbl_ro_categorias.portal = 1";
        }

        $sql .= $where . implode(' AND ', $_where);
        $sql .= "  ORDER BY titulo ASC";

        //echo $sql; exit;

        return $this->db->query($sql)->result_array();
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retorna uma categoria
     */
    public function get_categoria($id = 0, $rel_type = '', $attrs = array(), $array = false) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_categoria = 'tbl_intranet_categorias';
        $sql = "SELECT * from $tbl_categoria
        where $tbl_categoria.deleted = 0 and $tbl_categoria.id = $id  ";

        if ($rel_type != '') {
            $sql .= "and $tbl_categoria.rel_type = '$rel_type' ";
        }
        if ($empresa_id != '') {
            $sql .= "and $tbl_categoria.empresa_id = $empresa_id ";
        }
        if (is_array($attrs)) {
            foreach ($attrs as $column => $value) {
                $sql .= "and $tbl_categoria.$column = $value ";
            }
        }
        //echo $sql; exit;
        if ($array == true) {
            return $this->db->query($sql)->row_array();
        }
        return $this->db->query($sql)->row();
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retorna os campos de um determinado tipo(pela empresa_conectada) - NECESSÁRIO ID DO TIPO PARA RETORNAR OS CAMPOS DO MESMO 
     */
    public function get_categoria_campos_all($tipo_id = 0, $rel_type, $in_out = '', $not_category = false) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_campos = 'tbl_intranet_categorias_campo';
        $sql = "SELECT $tbl_campos.* ";
        if ($rel_type == 'r.o') {
            $sql .= ', pr.titulo ';
        }
        $sql .= "from $tbl_campos ";
        if ($rel_type == 'r.o') {
            $sql .= "LEFT JOIN tbl_intranet_registro_ocorrencia_atuantes pr ON pr.id = $tbl_campos.preenchido_por ";
        }

        $sql .= "where $tbl_campos.deleted = 0 and $tbl_campos.categoria_id = $tipo_id";

        if ($not_category == true) {

            $sql .= " and $tbl_campos.rel_type = '$rel_type' ";
        } else {
            $category = $this->get_categoria($tipo_id);
            $rel_type = $category->rel_type;
            $sql .= " and (tbl_intranet_categorias_campo.rel_type = '' or tbl_intranet_categorias_campo.rel_type is null or $tbl_campos.rel_type = '$rel_type') ";
        }

        if ($rel_type == 'api') {
            if ($in_out != '') {
                $sql .= " and $tbl_campos.in_out = $in_out ";
            }
        }
        if ($empresa_id != '') {
            $sql .= " and $tbl_campos.empresa_id = $empresa_id ";
        }
        $sql .= " ORDER BY $tbl_campos.ordem asc";
        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retorna todas as categorias da empresa conectada 
     */
    public function get_categorias_without_ra($rel_type, $empresa = '', $sql = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        if ($empresa_id == '') {
            $empresa_id = $empresa;
        }
        $tbl_ro_categorias = 'tbl_intranet_categorias';
        $sql = "SELECT $tbl_ro_categorias.*, tbldepartments.name from $tbl_ro_categorias
            left join tbldepartments on tbldepartments.departmentid = $tbl_ro_categorias.responsavel
        where  $tbl_ro_categorias.empresa_id = $empresa_id and $tbl_ro_categorias.rel_type = '$rel_type' and $tbl_ro_categorias.deleted = 0 and ($tbl_ro_categorias.ra = 0 or $tbl_ro_categorias.ra is null) and active = 1 $sql order by ordem asc";

        //echo $sql; exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_categorias_with_ra($rel_type, $parametro = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_ro_categorias = 'tbl_intranet_categorias';
        $sql = "SELECT $tbl_ro_categorias.*, tbldepartments.name from $tbl_ro_categorias
            inner join tbldepartments on tbldepartments.departmentid = $tbl_ro_categorias.responsavel
        where tbldepartments.empresa_id = $empresa_id and $tbl_ro_categorias.empresa_id = $empresa_id and $tbl_ro_categorias.rel_type = '$rel_type' and $tbl_ro_categorias.deleted = 0 and active = 1 ";
        if ($parametro == '') {
            $sql .= " and $tbl_ro_categorias.ra = 1";
        } else {
            $sql .= " and $tbl_ro_categorias.$parametro = 1";
        }
        $sql .= " order by ordem asc";
//echo $sql; exit;

        return $this->db->query($sql)->result_array();
    }

    /**
     * 26/10/2022
     * @WannaLuiza
     * Retorna o campo
     */
    public function get_campo($id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl = 'tbl_intranet_categorias_campo';
        $sql = "SELECT * from $tbl
        where $tbl.empresa_id = $empresa_id and $tbl.deleted = 0 and $tbl.id = $id";

        return $this->db->query($sql)->row();
    }

    /**
     * 18/10/2022
     * @WannaLuiza
     * Retorna os campos de um determinado tipo(pela empresa_conectada) - NECESSÁRIO ID DO TIPO PARA RETORNAR OS CAMPOS DO MESMO 
     */
    public function get_categoria_campos($tipo_id = 0, $preenchido_por = 0, $not_category = false, $rel_type = '', $id = '', $in_out = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_campos = 'tbl_intranet_categorias_campo';
        $sql = "SELECT * from $tbl_campos ";
        if ($id) {
            $sql .= " LEFT JOIN tbl_intranet_categorias_campo_values on tbl_intranet_categorias_campo_values.campo_id = $tbl_campos.id AND tbl_intranet_categorias_campo_values.rel_id = $id ";
        }

        $sql .= " where  $tbl_campos.deleted = 0 and $tbl_campos.categoria_id = $tipo_id ";

        if ($not_category == true) {

            $sql .= " and $tbl_campos.rel_type = '$rel_type' ";
            if($rel_type == 'api'){
                if($in_out){
                     $sql .= " and $tbl_campos.in_out = $in_out ";
                } else {
                     $sql .= " and $tbl_campos.in_out = '1' ";
                }
            }
        } else {
            $category = $this->get_categoria($tipo_id);
            $rel_type = $category->rel_type;
            $sql .= " and ($tbl_campos.rel_type = '$rel_type' || $tbl_campos.rel_type is null) ";
            if (!$preenchido_por or $preenchido_por == 0) {
                $sql .= "and ($tbl_campos.preenchido_por = '$preenchido_por' or $tbl_campos.preenchido_por = '') ";
            } else {
                $sql .= "and $tbl_campos.preenchido_por = '$preenchido_por' ";
            }
        }
        $sql .= "ORDER BY $tbl_campos.ordem asc";

        //echo $sql;
        //exit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 19/10/2022
     * @WannaLuiza
     * Retorna os campos já preenchidos de um registro já salvo
     */
    public function get_values($id = '', $rel_type = '', $preenchido_por = 0, $chave = false, $solicitante = false, $config = false) {

        //echo $preenchido_por;
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_values = 'tbl_intranet_categorias_campo_values';
        $tbl_campos = 'tbl_intranet_categorias_campo';

        $sql = "SELECT $tbl_campos.categoria_id as categoria, $tbl_campos.nome as nome_campo, $tbl_campos.color as color, $tbl_campos.obrigatorio as obrigatorio, $tbl_campos.id as id_campo, $tbl_campos.type as tipo_campo, $tbl_campos.name as name_campo, "
                . "$tbl_campos.options as opcoes_select, $tbl_campos.tam_coluna as tamanho, $tbl_values.value, $tbl_values.id as value_id from $tbl_values "
                . "LEFT JOIN $tbl_campos ON $tbl_campos.id = $tbl_values.campo_id "
                . "WHERE $tbl_values.empresa_id = $empresa_id AND $tbl_values.deleted = 0 AND $tbl_values.rel_id = $id and $tbl_values.rel_type = '$rel_type'  ";

        if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'cdc') {

            $preenchido_por_ro = $preenchido_por;

            if ($chave == false) {
                if ($preenchido_por == true || $preenchido_por == 0 || $preenchido_por == '') {


                    if (!is_numeric($preenchido_por)) {

                        if ($preenchido_por == '' || $preenchido_por == true) {

                            $preenchido_por = '0';
                        }
                    }

                    $preenchido_sql = " and $tbl_campos.preenchido_por = '$preenchido_por' ";
                }
                if ($rel_type == 'r.o') {
                    if ($preenchido_por_ro == 'setor_responsavel' || $preenchido_por_ro == 'classificacao') {
                        //echo $preenchido_sql;
                        $preenchido_sql = " and $tbl_campos.preenchido_por = '$preenchido_por_ro' ";
                    }
                }
            }
        }
        if ($solicitante == true) {
            $preenchido_sql = '';
        }
        $sql .= $preenchido_sql;

        if ($chave == true) {
            $sql .= " and $tbl_campos.chave = 1 ";
        }
        if ($solicitante == true) {
            $sql .= " and $tbl_campos.portal = 1 ";
        }
        $sql .= " ORDER BY $tbl_campos.ordem asc ";
        //return $sql;
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();

        if ($config == true) {
            for ($i = 0; $i < count($result); $i++) {
                //$result[$i]['value'] = $rel_type.$result[$i]['value'].$result[$i]['tipo_campo'];
                $result[$i]['value'] = get_value($rel_type, $result[$i]['value'], $result[$i]['tipo_campo']);
            }
        }

        return $result;
    }

    public function get_campos_file_values($id = '', $rel_type = '', $type = 'file') {

        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_values = 'tbl_intranet_categorias_campo_values';
        $tbl_campos = 'tbl_intranet_categorias_campo';

        $sql = "SELECT $tbl_campos.categoria_id as categoria, $tbl_campos.nome as nome_campo, $tbl_campos.obrigatorio as obrigatorio, $tbl_campos.id as id_campo, $tbl_campos.type as tipo_campo, $tbl_campos.name as name_campo, "
                . "$tbl_campos.options as opcoes_select, $tbl_campos.tam_coluna as tamanho, $tbl_values.value, $tbl_values.id as value_id from $tbl_values "
                . "LEFT JOIN $tbl_campos ON $tbl_campos.id = $tbl_values.campo_id "
                . "WHERE $tbl_values.empresa_id = $empresa_id AND $tbl_values.deleted = 0 AND $tbl_values.rel_id = $id and $tbl_values.rel_type = '$rel_type'  and $tbl_campos.type = 'file'";
//echo $sql;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 26/10/2022
     * @WannaLuiza
     * Retorna the option
     */
    public function get_option($id) {
        $sql = "SELECT * from tbl_intranet_categorias_campo_options WHERE tbl_intranet_categorias_campo_options.id = $id  ";

        //echo $sql;
        return $this->db->query($sql)->row();
    }

    public function get_doctos($categoria_id = '', $id = '', $intranet = false, $portal = false, $active = false) {

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . '_intranet_categorias_doctos.id', $id);
            return $this->db->get(db_prefix() . '_intranet_categorias_doctos')->row();
        } else {
            $this->db->where(db_prefix() . '_intranet_categorias_doctos.deleted', 0);
            $this->db->where(db_prefix() . '_intranet_categorias_doctos.categoria_id', $categoria_id);
            $this->db->where(db_prefix() . '_intranet_categorias_doctos.empresa_id', $this->session->userdata('empresa_id'));
            if ($intranet == true) {
                $this->db->where(db_prefix() . '_intranet_categorias_doctos.intranet', 1);
            }
            if ($portal == true) {
                $this->db->where(db_prefix() . '_intranet_categorias_doctos.portal', 1);
            }
            if ($active == true) {
                $this->db->where(db_prefix() . '_intranet_categorias_doctos.active', 1);
                return $this->db->get(db_prefix() . '_intranet_categorias_doctos')->row();
            }

            return $this->db->get(db_prefix() . '_intranet_categorias_doctos')->result_array();
        }
    }

    public function get_categoria_fluxos($categoria_id = '', $group_by = '') {

        if (is_numeric($categoria_id)) {

            $this->db->where(db_prefix() . '_intranet_categorias_fluxo.categoria_id', $categoria_id);
            $this->db->where(db_prefix() . '_intranet_categorias_fluxo.deleted', '0');

            $this->db->where(db_prefix() . '_intranet_categorias_fluxo.empresa_id', $this->session->userdata('empresa_id'));

            if ($group_by) {
                $this->db->group_by($group_by);
            }

            $this->db->order_by('codigo_sequencial', 'asc');
            return $this->db->get(db_prefix() . '_intranet_categorias_fluxo')->result_array();
        }
    }

    public function get_categoria_atuantes_campos($categoria_id = '') {

        if (is_numeric($categoria_id)) {

            $this->db->join(db_prefix() . '_intranet_registro_ocorrencia_atuantes', db_prefix() . '_intranet_registro_ocorrencia_atuantes.id = ' . db_prefix() . '_intranet_categorias_campo.preenchido_por', 'inner');
            $this->db->where(db_prefix() . '_intranet_categorias_campo.categoria_id', $categoria_id);
            $this->db->where(db_prefix() . '_intranet_categorias_campo.deleted', '0');

            $this->db->where(db_prefix() . '_intranet_categorias_campo.empresa_id', $this->session->userdata('empresa_id'));

            $this->db->order_by('preenchido_por', 'asc');
            return $this->db->get(db_prefix() . '_intranet_categorias_campo')->result_array();
        }
    }

    public function get_campos_for_department($tipo_id = 0, $departmentid = '') {
        $empresa_id = $this->session->userdata('empresa_id');
        $tbl_campos = 'tbl_intranet_categorias_campo';
        $sql = "SELECT * FROM tbl_intranet_categorias_campo
                INNER JOIN tbl_intranet_categorias_fluxo on tbl_intranet_categorias_fluxo.id = tbl_intranet_categorias_campo.preenchido_por 
                WHERE tbl_intranet_categorias_campo.categoria_id = $tipo_id and tbl_intranet_categorias_campo.deleted = 0 and tbl_intranet_categorias_fluxo.setor = $departmentid GROUP BY tbl_intranet_categorias_campo.nome "
                . "order by tbl_intranet_categorias_campo.nome asc";

// echo $sql;
//      exit;
        return $this->db->query($sql)->result_array();
    }

    public function get_campo_value($campo_id = '', $rel_id = '') {

        $sql = 'select value,type from tbl_intranet_categorias_campo_values '
                . 'INNER JOIN tbl_intranet_categorias_campo on tbl_intranet_categorias_campo.id = tbl_intranet_categorias_campo_values.campo_id '
                . "WHERE tbl_intranet_categorias_campo_values.rel_id = $rel_id and tbl_intranet_categorias_campo.id = $campo_id";

        return $this->db->query($sql)->row();
    }

    public function get_campo_value_similar($label = '', $rel_id = '', $dep = '') {

        $sql = 'select value,type from tbl_intranet_categorias_campo_values '
                . 'INNER JOIN tbl_intranet_categorias_campo on tbl_intranet_categorias_campo.id = tbl_intranet_categorias_campo_values.campo_id '
                . 'LEFT JOIN tbl_intranet_categorias_fluxo on tbl_intranet_categorias_fluxo.id = tbl_intranet_categorias_campo.preenchido_por '
                . "WHERE tbl_intranet_categorias_campo_values.rel_id = $rel_id and tbl_intranet_categorias_campo.nome = '$label'";
        if ($dep) {
            $sql .= " and tbl_intranet_categorias_fluxo.setor = $dep";
        }

        $valores = $this->db->query($sql)->result_array();

        return $valores;
    }
}
