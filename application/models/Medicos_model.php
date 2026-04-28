<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Medicos_model extends App_Model {

    /**

     * Add new employee role

     * @param mixed $data

     */
    public function add($data) {

        $data['datecreated'] = date('Y-m-d H:i:s');

        $empresa_id = $this->session->userdata('empresa_id');

        $data['empresa_id'] = $empresa_id;

        unset($data['id']);

        $this->db->insert(db_prefix() . 'medicos', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {



            $data_fin['nome'] = $data['nome_profissional'];

            $data_fin['medico_id'] = $insert_id;

            $empresa_id = $this->session->userdata('empresa_id');

            $data_fin['empresa_id'] = $empresa_id;

            $this->db->insert(db_prefix() . 'conta_financeira', $data_fin);

            log_activity('New medico Added [ID: ' . $insert_id . '.' . $data['nome_profissional'] . ']');

            return $insert_id;
        }



        return false;
    }

    /**

     * Update employee role

     * @param  array $data role data

     * @param  mixed $id   role id

     * @return boolean

     */
    public function update($data, $id) {

        $affectedRows = 0;

        unset($data['id']);

        $this->db->where('medicoid', $id);

        $this->db->update(db_prefix() . 'medicos', $data);

        if ($this->db->affected_rows() > 0) {

            $affectedRows++;
        }







        if ($affectedRows > 0) {

            log_activity('Medico Updated [ID: ' . $id . ', Name: ' . $data['nome_profissional'] . ']');

            return true;
        }



        return false;
    }

    /**

     * Get employee role by id

     * @param  mixed $id Optional role id

     * @return mixed     array if not id passed else object

     */
    public function get($id = '') {

        $empresa_id = $this->session->userdata('empresa_id');

        if (is_numeric($id)) {



            $this->db->where(db_prefix() . 'medicos.medicoid', $id);

            $medico = $this->db->get(db_prefix() . 'medicos')->row();

            return $medico;
        } else {



            $this->db->where(db_prefix() . 'medicos.active', 1);

            $this->db->where(db_prefix() . 'medicos.deleted', 0);

            $this->db->where(db_prefix() . 'medicos.empresa_id', $empresa_id);

            $this->db->order_by('nome_profissional', 'asc');

            return $this->db->get(db_prefix() . 'medicos')->result_array();
        }
    }

    public function get_conta_financeira() {

        $sql = 'SELECT *

                    from tblconta_financeira f';

        //             . 'SELECT * from tblmedicos '
        //      . ' where active = 1 and medicoid in ('.$where.')' ;   



        $result = $this->db->query($sql)->result_array();

        return $result;
    }
    
    
    public function get_substituto_atual($ef_id) {
        $sql = "SELECT * FROM tblmedico_substituto where id_titular = $ef_id and deleted = 0 order by id desc";
        //echo $sql; exit;
         $result = $this->db->query($sql)->row();
         return $result;
    }
    public function get_substitutos_escala($ef_id) {
        $sql = "SELECT tblmedico_substituto.*, tblmedicos.nome_profissional FROM tblmedico_substituto "
                . "LEFT JOIN tblmedicos on tblmedicos.medicoid = tblmedico_substituto.id_substituto "
                . " where tblmedico_substituto.id_titular = $ef_id and tblmedico_substituto.deleted = 0 order by tblmedico_substituto.id desc";
        //echo $sql; exit;
         $result = $this->db->query($sql)->result_array();
         return $result;
    }

    public function get_escala_fixa_by_id($ef_id) {
        $sql = "SELECT * FROM tblescala_fixa where id = $ef_id and deleted = 0";
        
         $result = $this->db->query($sql)->row();
         return $result;
    }
    
    /*

     * Larissa Oliveira

     * 01/08/2022

     * Descrição: retorna o nome dos médicos escalados caso estejam duplicados (horário e data) por meio da competência     

     */

    public function get_se_duplicado($competencia_id = '') {
        $empresa_id = $this->session->userdata('empresa_id');

        $sql = "SELECT tblevents.medico_escalado_id as medico_escalado_id, tblevents.dia as dia,tblevents.horario_id as horario_id, esc.nome_profissional as escalado,
                    tblevents.start , tblevents.end, h.hora_inicio, h.hora_fim
                    FROM tblevents INNER JOIN tblmedicos tit on tit.medicoid = tblevents.titular_id
                    LEFT JOIN tblmedicos esc on esc.medicoid = tblevents.medico_escalado_id
                    INNER JOIN tblunidades_hospitalares ON tblunidades_hospitalares.id = tblevents.unidade_id
                    INNER JOIN tblsetores_medicos ON tblsetores_medicos.id = tblevents.setor_id
                    INNER JOIN tblhorario_plantao h on h.id = tblevents.horario_id
                    WHERE tblevents.empresa_id = $empresa_id AND tblevents.competencia_id = $competencia_id AND medico_escalado_id !=0 AND tblevents.deleted = 0
                    order by medico_escalado_id, dia ";
        //echo $sql; exit; 
        $result = $this->db->query($sql)->result_array();

        return $result;
    }

    public function get_where_in($where = '') {

        $sql = 'SELECT m.medicoid as medicoid, m.nome_profissional, f.id as conta_id, f.nome as conta_nome

                    from tblconta_financeira f

                    LEFT JOIN tblmedicos m ON m.medicoid = f.medico_id

                    where f.id in (' . $where . ')';

        //             . 'SELECT * from tblmedicos '
        //      . ' where active = 1 and medicoid in ('.$where.')' ;   
        // echo $sql; exit;

        $result = $this->db->query($sql)->result_array();

        return $result;
    }

    /**

     * Delete employee role

     * @param  mixed $id role id

     * @return mixed

     */
    public function delete($id) {

        $current = $this->get($id);

        // Check first if role is used in table

        if (is_reference_in_table('role', db_prefix() . 'staff', $id)) {

            return [
                'referenced' => true,
            ];
        }



        $affectedRows = 0;

        $this->db->where('roleid', $id);

        $this->db->delete(db_prefix() . 'roles');

        if ($this->db->affected_rows() > 0) {

            $affectedRows++;
        }



        if ($affectedRows > 0) {

            log_activity('Role Deleted [ID: ' . $id);

            return true;
        }



        return false;
    }

    public function get_contact_permissions($id) {

        $this->db->where('userid', $id);

        return $this->db->get(db_prefix() . 'contact_permissions')->result_array();
    }

    public function get_role_staff($role_id) {

        $this->db->where('role', $role_id);

        return $this->db->get(db_prefix() . 'staff')->result_array();
    }

    /**
     * 10/10/2022
     * @WannaLuiza
     * Função que adiciona ou deleta um substituto
     */
    public function add_edit_substituto($data = null, $id = '') {

        if ($data) {
            //print_r($data); exit;
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            if ($id) {
                //echo 'aqui'; exit;
                $data['user_last_change'] = get_staff_user_id();
                $data['date_last_change'] = date('Y-m-d');
                $this->db->where('id', $id);
                if ($this->db->update("tblmedico_substituto", $data)) {
                    return $id;
                } else {
                    return false;
                }
            } else {

                $data['user_created'] = get_staff_user_id();
                $data['date_created'] = date('Y-m-d');
                $data['user_last_change'] = get_staff_user_id();
                $data['date_last_change'] = date('Y-m-d');
                if ($this->db->insert("tblmedico_substituto", $data)) {
                    $id_insert = $this->db->insert_id();
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * 10/10/2022
     * @WannaLuiza
     * Retorna todos os susbstitutos não deletados e por empresa
     */
    public function get_substitutos() {

        $empresa_id = $this->session->userdata('empresa_id');

        $this->db->where(db_prefix() . 'medico_substituto.deleted', 0);

        $this->db->where(db_prefix() . 'medico_substituto.empresa_id', $empresa_id);

        return $this->db->get(db_prefix() . 'medico_substituto')->result_array();
    }
    /**
     * 10/10/2022
     * @WannaLuiza
     * Retorna todos os susbstitutos não deletados e por empresa
     */
    public function get_escala_com_substituto() {

        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->join(db_prefix() . 'medico_substituto', '' . db_prefix() . 'medico_substituto.id_titular = ' . db_prefix() . 'escala_fixa.id', 'inner');

        $this->db->where(db_prefix() . 'escala_fixa.deleted', 0);
        $this->db->where(db_prefix() . 'medico_substituto.deleted', 0);

        $this->db->where(db_prefix() . 'escala_fixa.empresa_id', $empresa_id);
        $this->db->group_by('medico_substituto.id_titular'); 

        return $this->db->get(db_prefix() . 'escala_fixa')->result_array();
    }

      public function get_escala_fixa_resumo()
    {
         
       $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT m.medicoid, hierarquia, nome_profissional,
                (SELECT count(*) FROM tblescala_fixa where medico_id = m.medicoid and deleted = 0) as lancamentos
                FROM tblmedicos m
                where m.empresa_id = $empresa_id and m.hierarquia > 0   order by m.hierarquia asc";
        //echo $sql; exit;
        $return = $this->db->query($sql)->result_array();
        
       // print_R($return); exit;
        return $return;
    }
    
    
     public function get_planotes_escala_fixa_resumo($medico_id)
    {
         
       $empresa_id = $this->session->userdata('empresa_id');
        $sql = "select  IF(ef.horario_id > 0, h_medico.plantao, h.plantao) as plantao
                FROM tblescala_fixa ef
                inner join tblunidades_hospitalar_configuracao uc on uc.id = ef.config_id
                left join tblhorario_plantao h on h.id = uc.horario_id
                left join tblhorario_plantao h_medico on h_medico.id = ef.horario_id
                where ef.medico_id = $medico_id and ef.deleted = 0";
        //echo $sql; exit;
        $return = $this->db->query($sql)->result_array();
        
       // print_R($return); exit;
        return $return;
    }
    
}
