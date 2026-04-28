<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Link_model extends App_Model {

    public function __construct() {
        parent::__construct();
        $this->empresa_id = $this->session->userdata('empresa_id');
    }

    /**
     * Get lead
     * @param  string $id Optional - leadid
     * @return mixed
     */
    public function add($data = null, $id = 0) {
        if ($data) {
            $data['dt_created'] = date('Y-m-d');
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            $data['user_create'] = get_staff_user_id();

            if ($id) {
                $departments = $data['departments'];
                unset($data['departments']);
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_link", $data)) {
                    $this->db->where('rel_type', 'links');
                    $this->db->where('rel_id', $id);
                    $this->db->delete('tbl_intranet_send');
                    $array['rel_id'] = $id;
                    $array['dt_send'] = date('Y-m-d');
                    $array['rel_type'] = 'links';

                    if ($departments) {

                        for ($i = 0; $i < count($departments); $i++) {
                            $array['destino'] = $departments[$i];
                            $this->db->insert("tbl_intranet_send", $array);
                        }
                    }
                }
                return $id;
            } else {

                $departments = $data['departments'];
                unset($data['departments']);

                if ($this->db->insert("tbl_intranet_link", $data)) {

                    $id_insert = $this->db->insert_id();
                    $array['rel_id'] = $id_insert;
                    $array['dt_send'] = date('Y-m-d');
                    $array['rel_type'] = 'links';

                    if ($departments) {

                        for ($i = 0; $i < count($departments); $i++) {
                            $array['destino'] = $departments[$i];
                            //print_r($array); exit;
                            $this->db->insert("tbl_intranet_send", $array);
                        }
                    }
                    return $id_insert;
                }
            }
        }
    }

    public function add_categoria($data = null, $id = 0) {
        if ($data) {
            $data['empresa_id'] = $this->session->userdata('empresa_id');
            if ($id) {
                $data['user_ultima_alteracao'] = get_staff_user_id();
                $data['data_ultima_alteracao'] = date('Y-m-d');
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_link_categoria", $data)) {
                    return $id;
                } else {
                    return false;
                }
            } else {

                $data['user_cadastro'] = get_staff_user_id();
                $data['data_cadastro'] = date('Y-m-d');
                $data['user_ultima_alteracao'] = get_staff_user_id();
                $data['data_ultima_alteracao'] = date('Y-m-d');
                if ($this->db->insert("tbl_intranet_link_categoria", $data)) {
                    $id_insert = $this->db->insert_id();
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }

    public function get_link_categorias() {
        $sql = "SELECT * from tbl_intranet_link_categoria

        where empresa_id = $this->empresa_id and deleted = 0";

        return $this->db->query($sql);
    }

    public function get_link_staffs($id = 0) {
        $sql = "SELECT * from tbl_intranet_link_send

        where link_id = $id and deleted = 0";

        return $this->db->query($sql)->result();
    }

    public function get_recebidos($staff_id = 0) {

        $sql = "SELECT * from tbl_intranet_link_send

        where deleted = 0 and staff_id = $staff_id limit 12";

        return $this->db->query($sql);
    }

    public function get($empresa_id = 0) {
        $sql = "SELECT * from tbl_intranet_link

        where empresa_id = $empresa_id and deleted = 0 limit 12";
        return $this->db->query($sql);
    }

    public function get_link($id = 0) {
        $sql = "SELECT * from tbl_intranet_link
        where id = $id and deleted = 0 and empresa_id = $this->empresa_id";
        return $this->db->query($sql)->row();
    }

    /*
     * 16/01/2022
     * @AUTOR: Israel Araujo
     * Retorna as categorias que o usuário tem links disponíveis
     */

    public function get_categorias_links_by_user_id() {
        $empresa_id = $this->session->userdata('empresa_id');
        $staffid = get_staff_user_id();
        $sql = "SELECT c.id, c.titulo as categoria 
                FROM tbl_intranet_link_send ld
                inner join tbl_intranet_link l on l.id = ld.link_id
                inner join tbl_intranet_link_categoria c on c.id = l.categoria_id
                where ld.staff_id = $staffid and l.empresa_id = $empresa_id and ld.deleted = 0 and l.deleted = 0
                group by c.id";

        return $this->db->query($sql);
    }

    /*
     * 16/01/2022
     * @AUTOR: Israel Araujo
     * Retorna os links destaques de uma categorias que o usuário tem links disponíveis
     */

    public function get_links_destaques_by_categoria_user_id($categoria_id) {
        $empresa_id = $this->session->userdata('empresa_id');
        $staffid = get_staff_user_id();
        $sql = "SELECT l.*
                FROM tbl_intranet_link_send ld
                inner join tbl_intranet_link l on l.id = ld.link_id
                where ld.staff_id = $staffid and l.empresa_id = $empresa_id and ld.deleted = 0 and l.deleted = 0 and l.categoria_id = $categoria_id
                group by l.id";

        return $this->db->query($sql);
    }

    public function get_ways($id = 0, $only_ways = false) {
        $sql = "SELECT * from tbl_intranet_send

        where rel_id = $id and deleted = 0 and rel_type = 'links'";
        $result = $this->db->query($sql)->result_array();
        
        $result_ways = [];
        if ($only_ways == true) {
            foreach ($result as $result) {
                $result_ways[] = $result['destino'];
            }
            $result = $result_ways;
            

            return $result;
        }
    }
    
    public function get_link_department_for_user_id() {
        $empresa_id = $this->session->userdata('empresa_id');

        $tbl_link = 'tbl_intranet_link';
        $tbl_send = 'tbl_intranet_send';
        $tbl_cat = 'tbl_intranet_categorias';
        $tblstaff_dep= 'tblstaff_departments';
        $sql = "SELECT $tbl_link.*, $tbl_cat.titulo FROM $tbl_link
            INNER JOIN $tbl_cat ON $tbl_cat.id = $tbl_link.categoria_id
            INNER JOIN $tbl_send ON $tbl_send.rel_id = $tbl_link.id
            INNER JOIN $tblstaff_dep ON $tblstaff_dep.departmentid = $tbl_send.destino
            WHERE $tbl_cat.rel_type = 'links' and $tbl_link.deleted = 0 and $tbl_link.empresa_id = $empresa_id and $tblstaff_dep.staffid = ".get_staff_user_id()."
            GROUP BY $tbl_link.id";
       // echo $sql; exit;

        return $this->db->query($sql)->result_array();
    }

}
