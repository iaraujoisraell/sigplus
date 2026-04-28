<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_items_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get invoice item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
    public function get($id = '')
    {
        $columns             = $this->db->list_fields(db_prefix() . 'items');
        $rateCurrencyColumns = '';
        foreach ($columns as $column) {
            if (strpos($column, 'rate_currency_') !== false) {
                $rateCurrencyColumns .= $column . ',';
            }
        }
        $this->db->select($rateCurrencyColumns . '' . db_prefix() . 'items.id as itemid, rate, valor2,
            tblitems.codigo_tuss, tblitems.convenio_id,
            description,long_description,group_id,' . db_prefix() . 'items_groups.name as group_name,unit');
        $this->db->from(db_prefix() . 'items');
        //$this->db->join('' . db_prefix() . 'taxes t1', 't1.id = ' . db_prefix() . 'items.tax', 'left');
        //$this->db->join('' . db_prefix() . 'taxes t2', 't2.id = ' . db_prefix() . 'items.tax2', 'left');
        $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
        $this->db->order_by('description', 'asc');
        
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where('tblitems.empresa_id', $empresa_id);
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'items.id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }
    
    public function get_ativos($id = '')
    {
        $columns             = $this->db->list_fields(db_prefix() . 'items');
        $rateCurrencyColumns = '';
        foreach ($columns as $column) {
            if (strpos($column, 'rate_currency_') !== false) {
                $rateCurrencyColumns .= $column . ',';
            }
        }
        $this->db->select($rateCurrencyColumns . '' . db_prefix() . 'items.id as itemid,rate, tblconvenio.name as convenio,
            description,long_description,group_id,' . db_prefix() . 'items_groups.name as group_name,unit');
        $this->db->from(db_prefix() . 'items');
        $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'inner');
        $this->db->join(db_prefix() . 'convenio', '' . db_prefix() . 'convenio.id = ' . db_prefix() . 'items.convenio_id', 'inner');
        $this->db->where(db_prefix() . 'items_groups.ativo', 1);
        $this->db->where(db_prefix() . 'items.ativo', 1);
        
        $this->db->order_by('description', 'asc');
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where('tblitems.empresa_id', $empresa_id);
        
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'items.id', $id);

            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }
    
    public function get_agenda($id = '')
    {
        $sql = 'SELECT '.db_prefix().'items.* FROM '.db_prefix().'items '
               . ' where tblitems.ativo = 1 and id in ('.$id.')';
     
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function get_grouped_todos()
    {
        
        $items = [];
        //if(!is_customer_admin){
        //$valor_vsocial = array('1,2,7,10');
        
        //$valor_vsocial = array('1','2','7', '10');
        
        //$this->db->where_in(db_prefix() .'items_groups.id', $valor_vsocial );
        
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where(db_prefix() .'items_groups.empresa_id', $empresa_id);
         $this->db->where(db_prefix() . 'items_groups.ativo', 1);
        //}
        $this->db->order_by('name', 'desc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();
       // print_r($groups); exit;
        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
            $this->db->where('group_id', $group['id']);
            //if(!is_customer_admin){
             $this->db->where(db_prefix() . 'items.ativo', 1);
            //}
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->order_by('description', 'desc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }
    
    public function get_grouped_particular()
    {
        
        $items = [];
        //if(!is_customer_admin){
        //$valor_vsocial = array('1,2,7,10');
        
        $valor_particular = array('5', '6', '8', '9');
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where(db_prefix() .'items_groups.empresa_id', $empresa_id);
        $this->db->where_in(db_prefix() .'items_groups.id', $valor_particular );
         $this->db->where(db_prefix() . 'items_groups.ativo', 1);
        //}
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();
       // print_r($groups); exit;
        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
            $this->db->where('group_id', $group['id']);
            //if(!is_customer_admin){
             $this->db->where(db_prefix() . 'items.ativo', 1);
            //}
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }
    
    public function get_grouped_vsocial()
    {
        
        $items = [];
        //if(!is_customer_admin){
        //$valor_vsocial = array('1,2,7,10');
        
        $valor_particular = array('1,2,7,10');
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where(db_prefix() .'items_groups.empresa_id', $empresa_id);
       // $this->db->where_in(db_prefix() .'items_groups.id', $valor_particular );
         $this->db->where(db_prefix() . 'items_groups.ativo', 1);
        //}
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();
       // print_r($groups); exit;
        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
            $this->db->where('group_id', $group['id']);
            //if(!is_customer_admin){
             $this->db->where(db_prefix() . 'items.ativo', 1);
             $this->db->where(db_prefix() . 'items.convenio_id', 1);
            //}
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }
    
    public function get_grouped($convenio = null)
    {
        
        $items = [];
        //if(!is_customer_admin){
        $this->db->where(db_prefix() . 'items_groups.ativo', 1);
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where(db_prefix() .'items_groups.empresa_id', $empresa_id);
      //  $valor_vsocial_particular = array('1','2','7', '10', '5', '6', '8', '9');           // VISION
       // $this->db->where_not_in(db_prefix() .'items_groups.id', $valor_vsocial_particular );// VISION    
        //}
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id, ' . db_prefix() .'convenio.name as convenio');
            $this->db->where('group_id', $group['id']);
            //if(!is_customer_admin){
             $this->db->where(db_prefix() . 'items.ativo', 1);
             $this->db->where(db_prefix() . 'items.convenio_id', $convenio);
            //} 
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->join(db_prefix() . 'convenio', '' . db_prefix() . 'convenio.id = ' . db_prefix() . 'items.convenio_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }
    
    public function get_grouped_exames($convenio = null)
    {
        
        $items = [];
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where(db_prefix() .'items_groups.empresa_id', $empresa_id);
        //if(!is_customer_admin){
        $this->db->where(db_prefix() . 'items_groups.ativo', 1);
        $this->db->where(db_prefix() . 'items_groups.convenio_id', $convenio);
      //  $valor_vsocial_particular = array('1','2','7', '10', '5', '6', '8', '9');           // VISION
       // $this->db->where_not_in(db_prefix() .'items_groups.id', $valor_vsocial_particular );// VISION    
        //}
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id, ' . db_prefix() .'convenio.name as convenio');
            $this->db->where('group_id', $group['id']);
            //if(!is_customer_admin){
             $this->db->where(db_prefix() . 'items.ativo', 1);
            //} 
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->join(db_prefix() . 'convenio', '' . db_prefix() . 'convenio.id = ' . db_prefix() . 'items_groups.convenio_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }
    
    public function get_grouped_procedimentos($convenio = null)
    {
        
        $items = [];
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where(db_prefix() .'items_groups.empresa_id', $empresa_id);
        //if(!is_customer_admin){
        $this->db->where(db_prefix() . 'items_groups.ativo', 1);
        $this->db->where(db_prefix() . 'items_groups.convenio_id', $convenio);
      //  $valor_vsocial_particular = array('1','2','7', '10', '5', '6', '8', '9');           // VISION
       // $this->db->where_not_in(db_prefix() .'items_groups.id', $valor_vsocial_particular );// VISION    
        //}
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id, ' . db_prefix() .'convenio.name as convenio');
            $this->db->where('group_id', $group['id']);
            //if(!is_customer_admin){
             $this->db->where(db_prefix() . 'items.ativo', 1);
            //} 
            $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->join(db_prefix() . 'convenio', '' . db_prefix() . 'convenio.id = ' . db_prefix() . 'items_groups.convenio_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }
    
    
    public function get_grouped_all_medicos($medicoid)
    {
        
        $items = [];
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where(db_prefix() .'items_groups.empresa_id', $empresa_id);
        //if(!is_customer_admin){
        $this->db->where(db_prefix() . 'items_groups.ativo', 1);            
        //$valor_vsocial_particular = array('1','2','7', '10', '5', '6', '8', '9');    
        //$this->db->where_not_in(db_prefix() .'items_groups.id', $valor_vsocial_particular );     
        //}
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            
            $sql = 'SELECT *, '. db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id'
                    . ' FROM '.db_prefix().'items '
                    . ' INNER JOIN '.db_prefix() . 'items_groups ON '.db_prefix() . 'items_groups.id = '. db_prefix() . 'items.group_id'
                    . ' where group_id = '.$group['id'] .' and '.db_prefix() . 'items.ativo = "1" '
                    . ' AND '.db_prefix().'items.id NOT IN (SELECT ITEM_ID FROM '.db_prefix().'medicos_procedimentos WHERE medicoid = '.$medicoid.' and deleted = 0 ) '
                    . ' ORDER BY '.db_prefix().'items.description ASC';
            //ECHO $sql; EXIT;
            $_items = $this->db->query($sql)->result_array();
            
            /*
         OK   $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
         OK   $this->db->where('group_id', $group['id']);  
            //if(!is_customer_admin){
         OK   $this->db->where(db_prefix() . 'items.ativo', 1);
            //}
         OK  $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            */
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }
    
    public function get_grouped_all_procedimentos()
    {
        
        $items = [];
        //if(!is_customer_admin){
        $empresa_id = $this->session->userdata('empresa_id');
        $this->db->where(db_prefix() .'items_groups.empresa_id', $empresa_id);
        $this->db->where(db_prefix() . 'items_groups.ativo', 1);            
        //$valor_vsocial_particular = array('1','2','7', '10', '5', '6', '8', '9');    
        //$this->db->where_not_in(db_prefix() .'items_groups.id', $valor_vsocial_particular );     
        //}
        $this->db->order_by('name', 'asc');
        $groups = $this->db->get(db_prefix() . 'items_groups')->result_array();

        array_unshift($groups, [
            'id'   => 0,
            'name' => '',
        ]);

        foreach ($groups as $group) {
            
            $sql = 'SELECT *, '. db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id, '. db_prefix() . 'convenio.name as convenio'
                    . ' FROM '.db_prefix().'items '
                    . ' INNER JOIN '.db_prefix() . 'items_groups ON '.db_prefix() . 'items_groups.id = '. db_prefix() . 'items.group_id'
                    . ' INNER JOIN '.db_prefix() . 'convenio ON '.db_prefix() . 'convenio.id = '. db_prefix() . 'items.convenio_id'
                    . ' where group_id = '.$group['id'] .' and '.db_prefix() . 'items.ativo = "1" '
                  
                    . ' ORDER BY '.db_prefix().'items.description ASC';
            //ECHO $sql; EXIT;
            $_items = $this->db->query($sql)->result_array();
            
            /*
         OK   $this->db->select('*,' . db_prefix() . 'items_groups.name as group_name,' . db_prefix() . 'items.id as id');
         OK   $this->db->where('group_id', $group['id']);  
            //if(!is_customer_admin){
         OK   $this->db->where(db_prefix() . 'items.ativo', 1);
            //}
         OK  $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
            $this->db->order_by('description', 'asc');
            $_items = $this->db->get(db_prefix() . 'items')->result_array();
            */
            if (count($_items) > 0) {
                $items[$group['id']] = [];
                foreach ($_items as $i) {
                    array_push($items[$group['id']], $i);
                }
            }
        }

        return $items;
    }

     /**
     * @param  integer ID
     * @param  integer Status ID
     * @return boolean
     * Update client status Active/Inactive
     */
    public function change_item_status($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items', [
            'ativo' => $status,
        ]);

        if ($this->db->affected_rows() > 0) {
         

            log_activity('Item Status Changed [ID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');

            return true;
        }

        return false;
    }
    
    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data['itemid']);
        if ($data['tax'] == '') {
            unset($data['tax']);
        }

        if (isset($data['tax2']) && $data['tax2'] == '') {
            unset($data['tax2']);
        }

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $columns = $this->db->list_fields(db_prefix() . 'items');
        $this->load->dbforge();
        foreach ($data as $column => $itemData) {
            if (!in_array($column, $columns) && strpos($column, 'rate_currency_') !== false) {
                $field = [
                        $column => [
                            'type' => 'decimal(15,' . get_decimal_places() . ')',
                            'null' => true,
                        ],
                ];
                $this->dbforge->add_column('items', $field);
            }
        }
        
        //calcula deflator
       $empresa_id = $this->session->userdata('empresa_id');
        
        $item = $data['item_select'] ;
        unset($data['item_select']);
        
        $data['data_log']      = date('Y-m-d H:i:s');
        $data['usuario_log']   = get_staff_user_id();
        $data['empresa_id'] = $empresa_id;

        $this->db->insert(db_prefix() . 'items', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            
            
            $data_preco['item_select']       = $insert_id;
            $data_preco['valor']       = $data['rate'];
            $this->add_tabela_preco($data_preco);
            
            
            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields, true);
            }

            hooks()->do_action('item_created', $insert_id);

            log_activity('New Invoice Item Added [ID:' . $insert_id . ', ' . $data['description'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update invoiec item
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data)
    {
        $itemid = $data['itemid'];
        unset($data['itemid']);
        

        if (isset($data['group_id']) && $data['group_id'] == '') {
            $data['group_id'] = 0;
        }

        if (isset($data['tax']) && $data['tax'] == '') {
            $data['tax'] = null;
        }

        if (isset($data['tax2']) && $data['tax2'] == '') {
            $data['tax2'] = null;
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $columns = $this->db->list_fields(db_prefix() . 'items');
        $this->load->dbforge();

        foreach ($data as $column => $itemData) {
            if (!in_array($column, $columns) && strpos($column, 'rate_currency_') !== false) {
                $field = [
                        $column => [
                            'type' => 'decimal(15,' . get_decimal_places() . ')',
                            'null' => true,
                        ],
                ];
                $this->dbforge->add_column('items', $field);
            }
        }

        // calcular deflator
       
        
        
        $affectedRows = 0;

        $data = hooks()->apply_filters('before_update_item', $data, $itemid);

        $this->db->where('id', $itemid);
        $this->db->update(db_prefix() . 'items', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Invoice Item Updated [ID: ' . $itemid . ', ' . $data['description'] . ']');
            $affectedRows++;
        }

        if (isset($custom_fields)) {
            if (handle_custom_fields_post($itemid, $custom_fields, true)) {
                $affectedRows++;
            }
        }

        if ($affectedRows > 0) {
            hooks()->do_action('item_updated', $itemid);
        }

        return $affectedRows > 0 ? true : false;
    }

    public function search($q)
    {
        $this->db->select('rate, id, description as name, long_description as subtext');
        $this->db->like('description', $q);
        $this->db->or_like('long_description', $q);

        $items = $this->db->get(db_prefix() . 'items')->result_array();

        foreach ($items as $key => $item) {
            $items[$key]['subtext'] = strip_tags(mb_substr($item['subtext'], 0, 200)) . '...';
            $items[$key]['name']    = '(' . app_format_number($item['rate']) . ') ' . $item['name'];
        }

        return $items;
    }

    /**
     * Delete invoice item
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'items');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'items_pr');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            log_activity('Invoice Item Deleted [ID: ' . $id . ']');

            hooks()->do_action('item_deleted', $id);

            return true;
        }

        return false;
    }

    public function get_groups()
    {
         $empresa_id = $this->session->userdata('empresa_id');
        $this->db->order_by('name', 'asc');
      //   if(!is_customer_admin){
             $this->db->where(db_prefix() . 'items_groups.ativo', 1);
             $this->db->where(db_prefix() . 'items_groups.empresa_id', $empresa_id);
     //   }
        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }
    
    public function get_tabela_tuss()
    {
      //  $this->db->order_by('nome_tuss', 'asc');
      //   if(!is_customer_admin){
             //$this->db->where(db_prefix() . 'items_groups.ativo', 1);
     //   }
        return $this->db->get(db_prefix() . 'tuss')->result_array();
    }
    
     public function get_groups_convenio($convenio)
    {
         $empresa_id = $this->session->userdata('empresa_id');
        $this->db->order_by('name', 'asc');
      //   if(!is_customer_admin){
            
            $this->db->where(db_prefix() . 'items_groups.ativo', 1);
            $this->db->where(db_prefix() . 'items_groups.empresa_id', $empresa_id);
            $this->db->where_in('convenio_id',$convenio);
     //   }
        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }

    public function add_group($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa_id'] = $empresa_id;
        $this->db->insert(db_prefix() . 'items_groups', $data);
        log_activity('Items Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }

    public function edit_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_groups', $data);
        if ($this->db->affected_rows() > 0) {
            
            log_activity('Items Group Updated [Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'items_groups')->row();

        if ($group) {
            $this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'items', [
                'group_id' => 0,
            ]);

            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'items_groups');

            log_activity('Item Group Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }
    
    
     public function get_convenios($convenio_id = '')
    {
        $columns             = $this->db->list_fields(db_prefix() . 'items');
        $rateCurrencyColumns = '';
        foreach ($columns as $column) {
            if (strpos($column, 'rate_currency_') !== false) {
                $rateCurrencyColumns .= $column . ',';
            }
        }
        $this->db->select($rateCurrencyColumns . '' . db_prefix() . 'items.id as itemid, rate,
            tblitems.codigo_tuss, tblitems.convenio_id,
            description,long_description,group_id,' . db_prefix() . 'items_groups.name as group_name,unit');
        $this->db->from(db_prefix() . 'items');
        //$this->db->join('' . db_prefix() . 'taxes t1', 't1.id = ' . db_prefix() . 'items.tax', 'left');
        //$this->db->join('' . db_prefix() . 'taxes t2', 't2.id = ' . db_prefix() . 'items.tax2', 'left');
        $this->db->join(db_prefix() . 'items_groups', '' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id', 'left');
        $this->db->order_by('description', 'asc');
        
        $this->db->where(db_prefix() . 'items.ativo', 1);
        
        if (is_numeric($convenio_id)) {
            $this->db->where(db_prefix() . 'items.convenio_id', $convenio_id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }
    
    
    public function add_tabela_preco($data)
    {
        $empresa_id = $this->session->userdata('empresa_id');
        
        $item = $data['item_select'] ;
        unset($data['item_select']);
        
        $data['item_id'] = $item;
        $data['data_log']      = date('Y-m-d H:i:s');
        //$data['data_inicio']   = date('Y-m-d');
        $data['usuario_log']   = get_staff_user_id();
        $data['empresa_id'] = $empresa_id;
        
        $valor = $data['valor'];
        $valor = str_replace('.','', $valor);
        $valor = str_replace(',','.', $valor);
        $data['valor'] = $valor;
        
        $valor2 = $data['valor2'];
        $valor2 = str_replace('.','', $valor2);
        $valor2 = str_replace(',','.', $valor2);
        $data['valor2'] = $valor2;
       
        $paymentmode = $data['paymentmode'];
        if (count($paymentmode) > 0) {
           // print_r($paymentmode);
            
                $items1 = '';
                $cont = 1;
                foreach ($paymentmode as $p) {
                    if($cont == count($paymentmode)){
                        $items1 .= $p;
                    }else{
                        $items1 .= $p.',';
                    }
                     $cont++;
                }
            }
            $data['paymentmode'] = $items1;
            
            
            
        $paymentmode2 = $data['paymentmode2'];
        if (count($paymentmode2) > 0) {
           // print_r($paymentmode);
            
                $items2 = '';
                $cont = 1;
                foreach ($paymentmode2 as $p) {
                    if($cont == count($paymentmode2)){
                        $items2 .= $p;
                    }else{
                        $items2 .= $p.',';
                    }
                     $cont++;
                }
            }    
        $data['paymentmode2'] = $items2;    
       // print_r($data);
       // exit;
       
        $this->db->insert(db_prefix() . 'items_precos', $data);
        $insert_id = $this->db->insert_id();
        
        if ($insert_id) {
            //hooks()->do_action('items_precos', $insert_id, $data);
            
            return true;
        }

        return false;
    }
    
    public function edit_tabela_preco($data, $id)
    {
        //print_R($data); exit;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'items_precos ', $data);
        
        
        if ($this->db->affected_rows() > 0) {
            log_activity('Tabela de preço Updated ');

            return true;
        }

        return false;
    }
    
    public function get_tabela_preco($id = '', $data_filtro = '')
    {
        if (is_numeric($id)) {
            $sql = "SELECT *
            FROM tblitems_precos 
            where item_id = $id and data_fim is null";
            
            $result = $this->db->query($sql)->row();
            return $result;
        }else{
            
            
        $empresa_id = $this->session->userdata('empresa_id');    
         $sql = "SELECT tp.*, i.description as procedimento, c.name as convenio, i.codigo_tuss
            FROM tblitems_precos tp
            inner join tblitems i on i.id = tp.item_id
            inner join tblconvenio c on c.id = i.convenio_id
            where tp.empresa_id = $empresa_id";
        //echo $sql; exit;
         $convenio = $data_filtro['convenio'];
            if($convenio){
                $sql .= " and c.id = $convenio"; 
            }
            
            $categoria = $data_filtro['categoria'];
            if($categoria){
                $sql .= " and i.group_id = $categoria"; 
            }
            
            $inicio_vigencia = $data_filtro['inicio_vigencia'];
            if($inicio_vigencia){
                $sql .= " and tp.data_inicio = '$inicio_vigencia'"; 
            }
            //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
        
        }
    }
    
    /*
     * Carga tabela de preço
     */
    
    public function get_tabela_procedimentos($id = '')
    {
         $sql = "SELECT *
            FROM tblitems where ativo = 1 and deleted = 0";
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
        
        
    }
    
    
    /*
     * TABELA DE REPASSE MÉDICO - VEGÊNCIA
     */
    
    public function get_tabela_repasse_preco($item_id = '', $medico_id = '')
    {
        if (is_numeric($item_id) && is_numeric($medico_id)) {
            $sql = "SELECT *
            FROM tblmedicos_procedimentos_precos 
            where item_id = $item_id and medico_id = $medico_id and data_fim is null";
          
            $result = $this->db->query($sql)->row();
            return $result;
        }else{

         $sql = "SELECT tp.*, i.description as procedimento, c.name as convenio
            FROM tblitems_precos tp
            inner join tblitems i on i.id = tp.item_id
            inner join tblconvenio c on c.id = i.convenio_id";
        //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
        
        }
    }
    
    
    public function add_tabela_repasse_medico($data)
    {
        
        $empresa_id = $this->session->userdata('empresa_id');
        $data['usuario_log']    = get_staff_user_id();
       
        $data['data_log']       = date('Y-m-d H:i:s');
        $data['empresa_id']     = $empresa_id;
        //print_r($data); exit;
        $this->db->insert(db_prefix() . 'medicos_procedimentos_precos', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
           // hooks()->do_action('repasse_medico_created', $insert_id, $data);

            return true;
        }

        return false;
    }
    
    /*
     * EDITAR TABELA REPASSE MÉDICO
     */
    public function edit_tabela_repasse_preco($data, $id)
    {
        //print_R($data); exit;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'medicos_procedimentos_precos ', $data);
        
        
        if ($this->db->affected_rows() > 0) {
            log_activity('Tabela de preço Updated ');

            //return true;
        }

        //return false;
    }
    
    
    /*
     * CARGA
     * LÊ TODA TABELA DE REPASSE MÉDICO PRODUÇÃO
     */
    
    public function get_todos_repasses_medicos()
    {
         $sql = "SELECT * FROM tblmedicos_procedimentos where deleted = 0 "; // 
       //echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function add_new_procedimento_repasse($data_item)
    {
        $this->db->insert(db_prefix() . 'medicos_procedimentos_precos', $data_item);
    }
    
    
     public function get_verifica_procedimento_repasse($item = '', $medico = '')
    {
         $sql = "SELECT * FROM tblmedicos_procedimentos_precos where item_id = $item and medico_id = $medico";
       //echo $sql; exit;
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
}
