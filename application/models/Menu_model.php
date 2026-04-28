<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 26/09/2022(data que botei o comentário)
 * @WannaLuiza
 * Intranet - Model para Menus, Submenus e Páginas
 */
class Menu_model extends App_Model {

    public function __construct() {
        $this->empresa_id = $this->session->userdata('empresa_id');

        parent::__construct();
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Insere um menu(ou submenu, caso tenha um menu_pai)
     */
    public function add($data = null, $id = 0) {
        if ($data) {

            if ($id) {
                $this->db->where('id', $id);
                if ($this->db->update("tbl_intranet_menus", $data)) {
                    return $id;
                } else {
                    return false;
                }
            } else {
                $data['empresa_id'] = $this->session->userdata('empresa_id');
                $data['data_cadastro'] = date('Y-m-d H:i:s');
                $data['user_cadastro'] = get_staff_user_id();
                if ($this->db->insert("tbl_intranet_menus", $data)) {
                    $id_insert = $this->db->insert_id();
                    return $id_insert;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Retorna todos os menus principais(não retorna submenu)
     */
    public function get() {
        $this->db->where('empresa_id', $this->empresa_id);
        $this->db->where('deleted', '0');
        $this->db->where('menu_pai', '0');
        $this->db->order_by('id', 'desc');

        $menus = $this->db->get('tbl_intranet_menus')->result_array();

        return hooks()->apply_filters('get', $menus, ['empresa_id' => $this->empresa_id, 'deleted' => '0']);
    }
    
    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Retorna todos os menus principais(não retorna submenu) --- criei essa função pra não mexer em outra, aqui tá mais simplificado e eu consigo modificar melhor
     */
    public function get_menus() {
        $tbl_menu = 'tbl_intranet_menus';
        $sql = "SELECT $tbl_menu.id, $tbl_menu.nome_menu, $tbl_menu.urk, $tbl_menu.icon from  $tbl_menu "
                . "where $tbl_menu.menu_pai = 0 and $tbl_menu.deleted = 0 and $tbl_menu.empresa_id = $this->empresa_id ORDER BY $tbl_menu.ordem asc";
        return $this->db->query($sql)->result_array();
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Retorna um menu pelo id do mesmo
     */
    public function get_menu($id = 0) {
        $tbl_menu = 'tbl_intranet_menus';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_menu.*, $tbl_staff.firstname, $tbl_staff.lastname from $tbl_menu "
                . "LEFT JOIN $tbl_staff on $tbl_staff.staffid = $tbl_menu.user_cadastro "
                . "where $tbl_menu.id = $id and $tbl_menu.deleted = 0 and $tbl_menu.empresa_id = $this->empresa_id";
        return $this->db->query($sql);
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Retorna submenus de um determinado menu
     */
    public function get_submenus($id = 0) {
        $sql = "SELECT * from tbl_intranet_menus

        where menu_pai = $id and deleted = 0 and empresa_id = $this->empresa_id";
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Retorna submenus de um determinado menu e o próprio menu
     */
    public function get_submenus_with_pai($id = 0) {
        $sql = "SELECT * from tbl_intranet_menus

        where (menu_pai = $id or id = $id) and deleted = 0 and empresa_id = $this->empresa_id ORDER BY id asc";
        return $this->db->query($sql)->result_array();
    }
    

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Update deleted=0 
     */
    public function delete($id = 0) {
        $sql = "UPDATE tbl_intranet_menus SET deleted = 1 WHERE id= $id or menu_pai = $id";
        //echo $sql; exit;
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Retorna um menu pelo id do mesmo
     */
    public function get_menu_by_url($url = '') {
        $tbl_menu = 'tbl_intranet_menus';
        $tbl_staff = 'tblstaff';
        $sql = "SELECT $tbl_menu.*, $tbl_staff.firstname, $tbl_staff.lastname from $tbl_menu "
                . "LEFT JOIN $tbl_staff on $tbl_staff.staffid = $tbl_menu.user_cadastro "
                . "where $tbl_menu.urk = '$url' and $tbl_menu.deleted = 0 and $tbl_menu.empresa_id = $this->empresa_id";
        //echo $sql; exit;
        return $this->db->query($sql)->row();
    }

    /**
     * 15/12/2022
     * @WannaLuiza
     * Retorna o ultimo sequencial de ordem submenu
     */
    public function get_next_submenu($id = 0) {
        $sql = "SELECT ordem from tbl_intranet_menus where menu_pai = $id and deleted = 0 and empresa_id = $this->empresa_id order by ordem desc limit 1";
        $resultado = $this->db->query($sql)->row();
        if(isset($resultado)){
            $resultado = $resultado->ordem + 1;
            return $resultado;
        } 
        return '0';
    }
    
    /**
     * 15/12/2022
     * @WannaLuiza
     * Retorna o ultimo sequencial de ordem menu
     */
    public function get_next_menu() {
        $sql = "SELECT ordem from tbl_intranet_menus where deleted = 0 and empresa_id = $this->empresa_id and  menu_pai = 0 order by ordem desc limit 1";
        $resultado = $this->db->query($sql)->row();
        if(isset($resultado)){
            $resultado = $resultado->ordem + 1;
            return $resultado;
        } 
        return '0';
    }
}
