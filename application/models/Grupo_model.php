<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Grupo_model extends App_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * 25/07/2022
     * @AUTOR: Wanna Silva
     * Salva o grupo em tbl_intranet_grupo e o relacionamento do grupo com os staffs em outra tabela.
     */

    public function add($data = null, $id = 0) {
        if ($data) {
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            $staffs = $data['staff'];
            
            unset($data['staff']);
            
            if ($id) {
                
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_grupo", $data)) {
                    
                    $this->db->query("delete from tbl_intranet_grupo_staff where grupo_id = $id");
                    
                    //print_r($staffs); exit;
                    if ($staffs) {
                    $array['grupo_id'] = $id;
                    
                    for ($i = 0; $i < count($staffs); $i++) {
                        $array['staff_id'] = $staffs[$i][0];
                        $array['origem'] = $staffs[$i][1];
                        $this->db->insert("tbl_intranet_grupo_staff", $array);
                    }
                    }  
                    return $id;
                } else {
                    return false;
                }
            } else {
                $data['dt_created'] = date('Y-m-d');
                $data['user_create'] = get_staff_user_id();
                if ($this->db->insert("tbl_intranet_grupo", $data)) {
                    $id_insert = $this->db->insert_id();
                    $array['grupo_id'] = $id_insert;
                    for ($i = 0; $i < count($staffs); $i++) {
                        $array['staff_id'] = $staffs[$i][0];
                        $array['origem'] = $staffs[$i][1];
                        $this->db->insert("tbl_intranet_grupo_staff", $array);
                    }
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }

    /*
     * 25/07/2022
     * @AUTOR: Wanna Silva
     * Retorna todos os grupos não deletados pelo id da empresa
     */

    public function get($empresa_id = 0) {
        $sql = "SELECT * from tbl_intranet_grupo

        where empresa_id = $empresa_id and deleted = 0 limit 12";
        return $this->db->query($sql);
    }

    /*
     * 25/07/2022
     * @AUTOR: Wanna Silva
     * Retorna um grupo específico por id(apenas da tabela d grupo, sem staffs)
     */

    public function get_grupo($id = 0) {
        $sql = "SELECT * from tbl_intranet_grupo

        where id = $id and deleted = 0";
        return $this->db->query($sql);
    }

    /*
     * 25/07/2022
     * @AUTOR: Wanna  Silva
     * Retorna um grupo específico. JOIN COM OS STAFFS
     */

    public function get_grupo_staffs($id = 0) {
        $sql = "SELECT s.staffid from tbl_intranet_grupo_staff gf
                LEFT JOIN tbl_intranet_grupo g on g.id = gf.grupo_id
                LEFT JOIN tblstaff s on s.staffid = gf.staff_id
        where grupo_id = $id and g.deleted = 0";
        return $this->db->query($sql)->result_array();
    }

}
