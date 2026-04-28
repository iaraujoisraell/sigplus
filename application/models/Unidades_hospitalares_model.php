<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Unidades_hospitalares_model extends App_Model
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
        
        $empresa_id = $this->session->userdata('empresa_id');
        
        $this->db->select( db_prefix() . 'unidades_hospitalares.*');
        $this->db->from(db_prefix() . 'unidades_hospitalares');
       
        $this->db->where(db_prefix() . 'unidades_hospitalares.empresa_id', $empresa_id);
        $this->db->where(db_prefix() . 'unidades_hospitalares.deleted', 0);
        
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'unidades_hospitalares.id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }
    
    public function gets($id = '')
    {
        //echo 'aqui'; exit;
        $this->db->select( db_prefix() . 'setores_medicos.*');
        $this->db->from(db_prefix() . 'setores_medicos');
       
        
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'setores_medicos.id', $id);
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
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'items.id', $id);

            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }
    
    public function get_agenda($id = '')
    {
        //$sql = 'SELECT '.db_prefix().'items.* FROM '.db_prefix().'unidades_hospitalares '
          //     . ' where tblitems.ativo = 1 and id in ('.$id.')';
     
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
         $this->db->where(db_prefix() . 'setores_medicos.ativo', 1);
        //}
        $this->db->order_by('nome', 'desc');
        $groups = $this->db->get(db_prefix() . 'setores_medicos')->result_array();
       //print_r($groups); exit;
        array_unshift($groups, [
            'id'   => 0,
            'nome' => '',
        ]);

        foreach ($groups as $group) {
            $this->db->select('*,' . db_prefix() . 'setores_medicos.nome as group_name,');
            $this->db->where('group_id', $group['id']);
            //if(!is_customer_admin){
             $this->db->where(db_prefix() . 'setores_medicos.ativo', 1);
            //}
            
          
        }

        return $items;
    }
         
   
    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    
    public function add($data)
    {
     //echo $data;
        //print_r($data);
     //exit;
       
        unset($data['id']);
          unset($data['color']);
       //unset($data['DataTables_Table_0_length']);
          //print_r($data);
          //exit;
        //calcula deflator
       // $empresa_id = 2;
     
          // empresa
         $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa_id'] = $empresa_id;
        
         $data['data_log']       = date('Y-m-d H:i:s');
        $data['data_inclusao']  = date('Y-m-d H:i:s');
        $data['data_alteracao'] = date('Y-m-d H:i:s');
        $data['usuario_log']    = get_staff_user_id();
        $data['empresa_id']     = $empresa_id;
       
       
           //print_r($data);
           //echo 'teste';
          //exit();
        
      
        
        $this->db->insert(db_prefix() . 'unidades_hospitalares', $data);
            
        $insert_id = $this->db->insert_id();
               //echo $insert_id;
               //exit;
          //print_r($data);
        //exit;
        if ($insert_id) {
          
              //echo 'teste';
              //print_r($data);
              //exit;
            /*
            $data_preco['item_select']       = $insert_id;
            $data_preco['valor']       = $data['rate'];
            $this->add_tabela_preco($data_preco);
            */
            
     
            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields, true);
            }

            hooks()->do_action('item_created', $insert_id);

            log_activity('New Invoice Item Added [ID:' . $insert_id . ', ' . $data['description'] . ']');

            return $insert_id;
        }

        return false;
        
        
    
        
        
        
    }/////fim add data
   
    /**
     * Update invoiec item
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data)
    {
           $itemid = $data['id'];
            unset($data['color']);       
       
            $this->db->where('id', $itemid);
            $this->db->update(db_prefix() . 'unidades_hospitalares', $data);

        
        $affectedRows = 0;

        $data = hooks()->apply_filters('before_update_item', $data, $itemid);

        //$this->db->where('id', $itemid);
       // $this->db->update(db_prefix() . 'unidades_hospitalares', $data);
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
        $this->db->update(db_prefix() . 'unidades_hospitalares', ['deleted' => 1]);
        //$this->db->where('id', $id);
        //$this->db->delete(db_prefix() . 'unidades_hospitalares');
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
    
    public function get_setores($unid = '', $id='')
   // public function get_setores()
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $data_titulo['empresa_id']   = $empresa_id;
        $this->db->select( db_prefix() . 'setores_medicos.*');
        $this->db->where(db_prefix() . 'setores_medicos.deleted',0);
        $this->db->where(db_prefix() . 'setores_medicos.empresa_id',$empresa_id);
        $this->db->where(db_prefix() . 'setores_medicos.unidade_id', $unid);
        $this->db->from(db_prefix() . 'setores_medicos');
       
       
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'setores_medicos.id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }
    
    public function get_setores_escala($unidade_id = '')
    {

       $empresa_id = $this->session->userdata('empresa_id');

       $sql = "";


       $sql = 'SELECT * FROM tblsetores_medicos sm
                where  sm.deleted = 0 and sm.empresa_id = '.$empresa_id .' and sm.unidade_id = '.$unidade_id;

               // echo $sql; exit;

            $result = $this->db->query($sql)->result_array();

       return $result;

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
        $this->db->order_by('name', 'asc');
      //   if(!is_customer_admin){
            $this->db->where(db_prefix() . 'items_groups.ativo', 1);
            $this->db->where_in('convenio_id',$convenio);
     //   }
        return $this->db->get(db_prefix() . 'items_groups')->result_array();
    }

    public function add_setor($data)
    {
        unset($data['id']);  
         
        $empresa_id = $this->session->userdata('empresa_id');
        
        $data['data_log']       = date('Y-m-d H:i:s');
        $data['data_inclusao']  = date('Y-m-d H:i:s');
        $data['data_alteracao'] = date('Y-m-d H:i:s');
        $data['usuario_log']    = get_staff_user_id();
        $data['empresa_id']     = $empresa_id;
      
        $this->db->insert(db_prefix() . 'setores_medicos', $data);
        log_activity('SETOR Created [Name: ' . $data['nome'] . ']');
        return  $this->db->insert_id();
    }

    public function edit_setor($data,$id)
    {
        hooks()->do_action('before_update_note', [
            'data' => $data,
            'id'   => $id,
        ]);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'setores_medicos', $data = [
            'nome' => nl2br($data['nome']),
        ]);

        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('setor_updated', $id, $data);

            return true;
        }

        return false;
    }

    public function delete_setor($id)
    {
        
        hooks()->do_action('before_delete_note', $id);

        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'setores_medicos')->row();

        if ($group->addedfrom != get_staff_user_id() && !is_admin()) {
            return false;
        }

        //$this->db->delete(db_prefix() . 'setores_medicos');
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'setores_medicos', ['deleted' => 1]);
          
        
        
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action('setor_deleted', $id, $group);

            return true;
        }

        return false;
        
    }
        
         
        public function get_configuracao_horario($unidade_id = '')
        {

           $empresa_id = $this->session->userdata('empresa_id');

           $sql = "";


           $sql = 'SELECT c.*, sm.nome as setor, h.hora_inicio, h.hora_fim
                    FROM tblunidades_hospitalar_configuracao c
                    inner join tblsetores_medicos sm on sm.id = c.setor_id
                    inner join tblhorario_plantao h on h.id = c.horario_id
                    where c.deleted = 0 and sm.unidade_id = '.$unidade_id.' and c.empresa_id = '.$empresa_id ;

                   // echo $sql; exit;
          
                $result = $this->db->query($sql)->result_array();
          
           return $result;

        }
         
        public function add_edit_horario_configuracao($data)
        {
       
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
    
            
            $data['empresa_id']             = $empresa_id;
            $data['user_cadastro']          = get_staff_user_id();
            $data['data_cadastro']          = $hoje;
            $data['user_ultima_alteracao']  = get_staff_user_id();
            $data['data_ultima_alteracao']  = $hoje;
            
            unset($data['id']);
            
            $this->db->insert(db_prefix() . 'unidades_hospitalar_configuracao', $data);
            $id_registro = $this->db->insert_id();
            
            /*
             * GERA A ESCALA FIXA
             */
            
            log_activity('HORÁRIO PLANTÃO insert [ ID : '.$id_registro.']', null, $data);
        
            
       
            hooks()->do_action('fin_lancamentos', $id_registro, $data);
            return true;
            
       
    }
    
    
     public function delete_configuracao($id)
    {
        
        hooks()->do_action('before_delete_note', $id);

      
        //$this->db->delete(db_prefix() . 'setores_medicos');
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'unidades_hospitalar_configuracao', ['deleted' => 1]);
          
        
        
        if ($this->db->affected_rows() > 0) {
           

            return true;
        }

        return false;
        
    }
    
    
    /*
     * CONSULTA SE JÁ TEM A ESCALA FIXA PARA UM SETOR
     */
    public function get_escala_fixa_by_config_setor_id($config_id = '', $dia_semana = '')
        {

           $empresa_id = $this->session->userdata('empresa_id');

           $sql = "";


           $sql = 'SELECT *
                    FROM tblescala_fixa 
                    where deleted = 0 and config_id = '.$config_id.' and empresa_id = '.$empresa_id ;
           
           if($dia_semana){
            $sql .= " and dia_semana = '$dia_semana'";
           }
                    $result = $this->db->query($sql)->result_array();
          
           return $result;

        }
    
    public function get_config_escala_fica($config_id)
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = 'SELECT c.*, uh.razao_social as unidade, sm.nome as setor, hp.hora_inicio, hp.hora_fim
                FROM tblunidades_hospitalar_configuracao c
                inner join tblsetores_medicos sm on sm.id = c.setor_id
                inner join tblunidades_hospitalares uh on uh.id = sm.unidade_id
                inner join tblhorario_plantao hp on hp.id = c.horario_id
                where c.deleted = 0 and c.empresa_id = '.$empresa_id;
       
       if($config_id){
           $sql .= " and c.id = $config_id";
           
       }
      //echo $sql; exit;
       
       if ($config_id) {
           $result = $this->db->query($sql)->row();
        }else{
            $result = $this->db->query($sql)->result_array();
        }
        
        return $result;
    }
    
    
    /*
     * ADD MEDICOS A HORÁRIOS DISPONÍVEIS
     */
    
    public function add_medico_escala_fixa($data)
    {
        // apaga todos os registros desse config_id
        $this->db->where('config_id', $data['config_id']);
        $this->db->delete(db_prefix() . 'escala_fixa');
        
        unset($data['unidade_id']);  
        $empresa_id = $this->session->userdata('empresa_id');
        
        // segunda - feira
        if($data['segunda'] > 0){
            foreach ($data['segunda'] as $valor) {
                $dados_seg['config_id']      = $data['config_id'];
                $dados_seg['medico_id']      = $valor;
                $dados_seg['dia_semana']     = 'segunda';
                $dados_seg['empresa_id']     = $empresa_id;
               
                $this->db->insert(db_prefix() . 'escala_fixa', $dados_seg);
            }
        }
       
        
        // segunda - feira
        if($data['terca'] > 0){
            foreach ($data['terca'] as $valor) {
                $dados_ter['config_id']      = $data['config_id'];
                $dados_ter['medico_id']      = $valor;
                $dados_ter['dia_semana']     = 'terca';
                $dados_ter['empresa_id']     = $empresa_id;
               
                $this->db->insert(db_prefix() . 'escala_fixa', $dados_ter);
            }
        }
        
        // segunda - feira
        if($data['quarta'] > 0){
            foreach ($data['quarta'] as $valor) {
                $dados_qua['config_id']      = $data['config_id'];
                $dados_qua['medico_id']      = $valor;
                $dados_qua['dia_semana']     = 'quarta';
                $dados_qua['empresa_id']     = $empresa_id;
               
                $this->db->insert(db_prefix() . 'escala_fixa', $dados_qua);
            }
        }
        
        // segunda - feira
        if($data['quinta'] > 0){
            foreach ($data['quinta'] as $valor) {
                $dados_qui['config_id']      = $data['config_id'];
                $dados_qui['medico_id']      = $valor;
                $dados_qui['dia_semana']     = 'quinta';
                $dados_qui['empresa_id']     = $empresa_id;
               
                $this->db->insert(db_prefix() . 'escala_fixa', $dados_qui);
            }
        }
        
        // segunda - feira
        if($data['sexta'] > 0){
            foreach ($data['sexta'] as $valor) {
                $dados_sex['config_id']      = $data['config_id'];
                $dados_sex['medico_id']      = $valor;
                $dados_sex['dia_semana']     = 'sexta';
                $dados_sex['empresa_id']     = $empresa_id;
               
                $this->db->insert(db_prefix() . 'escala_fixa', $dados_sex);
            }
        }
        
        // segunda - feira
        if($data['sabado'] > 0){
            foreach ($data['sabado'] as $valor) {
                $dados_sab['config_id']      = $data['config_id'];
                $dados_sab['medico_id']      = $valor;
                $dados_sab['dia_semana']     = 'sabado';
                $dados_sab['empresa_id']     = $empresa_id;
               
                $this->db->insert(db_prefix() . 'escala_fixa', $dados_sab);
            }
        }
        
        // segunda - feira
        if($data['domingo'] > 0){
            foreach ($data['domingo'] as $valor) {
                $dados_dom['config_id']      = $data['config_id'];
                $dados_dom['medico_id']      = $valor;
                $dados_dom['dia_semana']     = 'domingo';
                $dados_dom['empresa_id']     = $empresa_id;
               
                $this->db->insert(db_prefix() . 'escala_fixa', $dados_dom);
            }
        }
        
        
      // log_activity('SETOR Created [Name: ' . $data['nome'] . ']');
        return  true;
    }
    
    /*
     * COMPETENCIA ESCALA
     */
     public function get_competencia_escala($id = '')
    {

       $empresa_id = $this->session->userdata('empresa_id');

       $sql = "";


       $sql = 'SELECT * FROM tblcompetencias_plantoes 
                where deleted = 0 and empresa_id = '.$empresa_id .' and status = 1';
               // echo $sql; exit;
            
            
            if ($id) {
               $sql .= ' and id = '.$id;
              $sql .= ' order by id desc ';
                $result = $this->db->query($sql)->row();
            }else{
                $sql .= ' order by id desc ';
                $result = $this->db->query($sql)->result_array();
            }
             
       return $result;
    }
    
    /*
     * RETORNA A CONFIGURACAO DE UM DETERMINADO HORARIO E SETOR
     */
    public function get_config($horario_id = '', $setor_id = '')
    {
     
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "";
       
       
       $sql = 'SELECT *
                FROM tblunidades_hospitalar_configuracao
                where deleted = 0 and empresa_id = '.$empresa_id.' and horario_id = '.$horario_id.' and setor_id = '.$setor_id;
      
     // echo $sql; exit;
       
        $result = $this->db->query($sql)->row();
       
       
       return $result;
      
    }
    
    // retorna todos os horários configurados para este setor by setor (que ainda n foram cadastrados)
    public function get_setores_configuracao($setor_id = '')
    {
       
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = 'SELECT c.*,  h.hora_inicio, h.hora_fim FROM tblunidades_hospitalar_configuracao c
                inner join tblhorario_plantao h on h.id = c.horario_id
                where  c.deleted = 0 and c.empresa_id = '.$empresa_id .' and c.setor_id = '.$setor_id;
              //  echo $sql; exit;
      
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    public function get_horario_setores_disponivel($setor_id = '', $competencia = '')
    {
       
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = 'SELECT ef.id as ef_id, ef.medico_id, ef.dia_semana, c.*, 
                        ef.horario_id as ef_horario_id 
                
                FROM tblescala_fixa ef
                inner join tblunidades_hospitalar_configuracao c on c.id = ef.config_id
             


                where ef.deleted = 0 and c.empresa_id = '.$empresa_id .' and c.setor_id = '.$setor_id;
        //echo $sql; exit;
        /*
        $sql = 'SELECT c.*,  h.hora_inicio, h.hora_fim FROM tblunidades_hospitalar_configuracao c
                inner join tblhorario_plantao h on h.id = c.horario_id
                where c.id not in (select config_id from tblcompetencias_plantoes_setores cs where cs.config_id = c.id and cs.competencia_id ='.$competencia.')
                and c.deleted = 0 and c.empresa_id = '.$empresa_id .' and c.setor_id = '.$setor_id;
         * 
         */
             //   echo $sql.'<br>';// exit;
      
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    // retorna todos os horários configurados para este setor by setor
    public function get_horario_setores_escala($setor_id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = 'select h.id as id_horario, h.hora_inicio, h.hora_fim
                 FROM tblunidades_hospitalar_configuracao c
                inner join tblhorario_plantao h on h.id = c.horario_id
                where c.deleted = 0 and c.empresa_id = '.$empresa_id .' and c.setor_id = '.$setor_id;
              //  echo $sql; exit;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    // verifica se o setor tem configuração
    public function get_configuracao_escala_by_id($id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = 'SELECT * FROM tblunidades_hospitalar_configuracao 
                where deleted = 0 and empresa_id = '.$empresa_id .' and id = '.$id;
                
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    // verifica se o setor tem configuração
    public function get_setores_competencia($setor_id = '', $competencia = '', $config_id='')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = 'SELECT * FROM tblcompetencias_plantoes_setores 
                where deleted = 0 and empresa_id = '.$empresa_id .' and setor_id = '.$setor_id. ' and competencia_id = '.$competencia.' and config_id = '.$config_id;
             //   echo $sql.'<br>';// exit;
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    
    // retorna o horário de plantao by id
    public function get_horario_plantao_by_id($id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = "SELECT * FROM tblhorario_plantao where id = $id and deleted = 0 and empresa_id =".$empresa_id;
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    // retorna a escala fixa de uma configuração de escala (setor) e de um dia específico
    public function get_escala_fixa_by_setor_dia($config_id = '', $dia = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = "SELECT * FROM tblescala_fixa where config_id = $config_id and dia_semana = '$dia'
                and deleted = 0 and empresa_id =".$empresa_id;
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
     public function get_escala_fixa_by_id($ef_id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = "SELECT * FROM tblescala_fixa where id = $ef_id 
                and deleted = 0 and empresa_id =".$empresa_id;
        //echo $sql.'<br>';
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
     public function get_substituto_ef_id($ef_id = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "";
        $sql = "SELECT * FROM tblmedico_substituto s
                where s.id_titular = $ef_id
                and s.id = (SELECT MAX(f1.id) FROM tblmedico_substituto f1 WHERE f1.id_titular = s.id_titular)
                and s.deleted = 0 and s.empresa_id =".$empresa_id;
      //  echo $sql.'<br>'; exit;
        $result = $this->db->query($sql)->row();
        return $result;
    }
    
    
    /*
     * ESCALA
     */
    
    public function add_escala_plantao($data)
    {
        
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        
        $setor_id       = $data['setor_id'];
        $unidade_id     = $data['unidade_id'];
        $competencia_id = $data['competencia_id']; 
        $config_id      = $data['config_id'];
        $horario_id     = $data['horario_id'];
        $ef_id          = $data['ef_id'];
        $dia_semana_ef  = $data['dia_semana'];
        
        
        
      //  $verifica_config_setor = $this->Unidades_hospitalares_model->get_configuracao_escala_by_id($config_id);
        //$config_id = $verifica_config_setor->id;
        //$horario_id = $verifica_config_setor->horario_id;
      //  $segunda    = $verifica_config_setor->segunda;
       // $terca      = $verifica_config_setor->terca;
       // $quarta     = $verifica_config_setor->quarta;
       // $quinta     = $verifica_config_setor->quinta;
       // $sexta      = $verifica_config_setor->sexta;
       // $sabado     = $verifica_config_setor->sabado;
       // $domingo    = $verifica_config_setor->domingo;
        
        // HORÁRIO DE PLANTAO
        $horario_plantao = $this->Unidades_hospitalares_model->get_horario_plantao_by_id($horario_id);
        $hora_inicio     = $horario_plantao->hora_inicio;
        $hora_fim        = $horario_plantao->hora_fim;
        $qtde_plantao    = $horario_plantao->plantao;
        
        //COMPETENCIA
        $competencia    = $this->Unidades_hospitalares_model->get_competencia_escala($competencia_id);
        $ano_comp       = $competencia->ano;
        $mes_comp       = $competencia->mes;
        
        $tam_mes = strlen($mes_comp);
        if($tam_mes == 1){
            $mes_comp = '0'.$mes_comp;
        }
        $funcao = new DateTime("$ano_comp-$mes_comp");
        $numDias_mes = $funcao->format('t');
        
        
        $diasemana = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');
        
        
        for($dia=1; $dia <= $numDias_mes; $dia++){
            $tam_dia = strlen($dia);
            if($tam_dia == 1){
                $dia = '0'.$dia;
            }
            $data_cal = "$ano_comp-$mes_comp-$dia";
            $diasemana_numero = date('w', strtotime($data_cal));
            $dia_semana = $diasemana[$diasemana_numero];
            $start = "$data_cal:$hora_inicio";
            $end = "$data_cal:$hora_fim";
                        
            //echo $dia.' - '.$dia_semana.' ( '.$start.' / '.$end. ') <br>'; 
            
            if($dia_semana_ef == 'segunda'){
                if($dia_semana == 'segunda'){
                    //for($d=0; $d < $segunda; $d++){
                        $escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_id($ef_id);
                        $titular_id = $escala_fixa->medico_id;
                        
                        $substituto = $this->Unidades_hospitalares_model->get_substituto_ef_id($ef_id);
                        $substituto_med = $substituto->id_substituto;
                        if($substituto_med){
                            $substituto_id = $substituto_med;
                            $escalado_id = $substituto_med;
                        }else{
                            $substituto_id = '';
                            $escalado_id = $titular_id;
                        }

                        
                        $dados_seg['public']                 = 1;
                        
                        $dados_seg['titular_id']             = $titular_id;
                        $dados_seg['medico_escalado_id']     = $escalado_id;
                        $dados_seg['substituto']             = $substituto_id;
                        $dados_seg['userid']                 = $titular_id;
                       
                        $dados_seg['quantidade']             = $qtde_plantao;
                        $dados_seg['item_id']                = 1;
                        $dados_seg['unidade_id']             = $unidade_id;
                        $dados_seg['setor_id']               = $setor_id;
                        $dados_seg['start']                  = $start;
                        $dados_seg['end']                    = $end;
                        $dados_seg['user_cadastro']          = get_staff_user_id();
                        $dados_seg['data_cadastro']          = $hoje;
                        $dados_seg['empresa_id']             = $empresa_id;
                        $dados_seg['ano']                    = $ano_comp;
                        $dados_seg['mes']                    = $mes_comp;
                        $dados_seg['dia']                    = $dia;
                        $dados_seg['dia_semana']             = $dia_semana;
                        $dados_seg['config_id']              = $config_id;
                        $dados_seg['horario_id']             = $horario_id;
                        $dados_seg['competencia_id']         = $competencia_id;
                        
                      //  print_R($dados_seg); exit;
                        
                        $this->db->insert(db_prefix() . 'events', $dados_seg);
                    //} 
                }
            }
            
            if($dia_semana_ef == 'terca' ){
            if($dia_semana == 'terca'){
                //for($d=0; $d < $terca; $d++){
                    $escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_id($ef_id);
                    $titular_id                          = $escala_fixa->medico_id;
                  
                    $substituto = $this->Unidades_hospitalares_model->get_substituto_ef_id($ef_id);
                    $substituto_med = $substituto->id_substituto;
                    if($substituto_med){
                        $substituto_id  = $substituto_med;
                        $escalado_id    = $substituto_med;
                    }else{
                        $substituto_id = '';
                        $escalado_id = $titular_id;
                    }

                        
                    $dados_ter['public']                 = 1;
                    $dados_ter['titular_id']             = $titular_id;
                    $dados_ter['medico_escalado_id']     = $escalado_id;
                    $dados_ter['substituto']             = $substituto_id;
                    $dados_ter['userid']                 = $titular_id;
                  
                    $dados_ter['quantidade']             = $qtde_plantao;
                    $dados_ter['item_id']                = 1;
                    $dados_ter['unidade_id']             = $unidade_id;
                    $dados_ter['setor_id']               = $setor_id;
                    $dados_ter['start']                  = $start;
                    $dados_ter['end']                    = $end;
                    $dados_ter['user_cadastro']          = get_staff_user_id();
                    $dados_ter['data_cadastro']          = $hoje;
                    $dados_ter['empresa_id']             = $empresa_id;
                    $dados_ter['ano']                    = $ano_comp;
                    $dados_ter['mes']                    = $mes_comp;
                    $dados_ter['dia']                    = $dia;
                    $dados_ter['dia_semana']             = "$dia_semana";
                    $dados_ter['config_id']              = $config_id;
                    $dados_ter['horario_id']             = $horario_id;
                    $dados_ter['competencia_id']         = $competencia_id;
                    
                    
                    
                    $this->db->insert(db_prefix() . 'events', $dados_ter);
                    
                   // print_R($dados_ter); exit;
                    
                //}
            } 
            }
            if($dia_semana_ef == 'quarta'){
            if($dia_semana == 'quarta'){
                //for($d=0; $d < $quarta; $d++){
                    $escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_id($ef_id);
                    $titular_id                          = $escala_fixa->medico_id;
                    
                    $substituto = $this->Unidades_hospitalares_model->get_substituto_ef_id($ef_id);
                    $substituto_med = $substituto->id_substituto;
                    if($substituto_med){
                        $substituto_id = $substituto_med;
                        $escalado_id = $substituto_med;
                    }else{
                        $substituto_id = '';
                        $escalado_id = $titular_id;
                    }

                        
                    $dados_qua['public']                 = 1;
                    $dados_qua['titular_id']             = $titular_id;
                    $dados_qua['medico_escalado_id']     = $escalado_id;
                    $dados_qua['substituto']             = $substituto_id;
                    $dados_qua['userid']                 = $titular_id;
                    
                    $dados_qua['quantidade']             = $qtde_plantao;
                    $dados_qua['item_id']                = 1;
                    $dados_qua['unidade_id']             = $unidade_id;
                    $dados_qua['setor_id']               = $setor_id;
                    $dados_qua['start']                  = $start;
                    $dados_qua['end']                    = $end;
                    $dados_qua['user_cadastro']          = get_staff_user_id();
                    $dados_qua['data_cadastro']          = $hoje;
                    $dados_qua['empresa_id']             = $empresa_id;
                    $dados_qua['ano']                    = $ano_comp;
                    $dados_qua['mes']                    = $mes_comp;
                    $dados_qua['dia']                    = $dia;
                    $dados_qua['dia_semana']             = $dia_semana;
                    $dados_qua['config_id']              = $config_id;
                    $dados_qua['horario_id']              = $horario_id;
                    $dados_qua['competencia_id']         = $competencia_id;
                    $this->db->insert(db_prefix() . 'events', $dados_qua);
                //}
                }
            }
            
            if($dia_semana_ef == 'quinta'){
            if($dia_semana == 'quinta'){
                //for($d=0; $d < $quinta; $d++){
                   $escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_id($ef_id);
                   $titular_id                          = $escala_fixa->medico_id;
                   
                    $substituto = $this->Unidades_hospitalares_model->get_substituto_ef_id($ef_id);
                    $substituto_med = $substituto->id_substituto;
                    if($substituto_med){
                        $substituto_id = $substituto_med;
                        $escalado_id = $substituto_med;
                    }else{
                        $substituto_id = '';
                        $escalado_id = $titular_id;
                    }

                        
                    $dados_qui['public']                 = 1;
                    $dados_qui['titular_id']             = $titular_id;
                    $dados_qui['medico_escalado_id']     = $escalado_id;
                    $dados_qui['substituto']             = $substituto_id;
                    $dados_qui['userid']                 = $titular_id;
                   
                   $dados_qui['quantidade']             = $qtde_plantao;
                   $dados_qui['item_id']                = 1;
                   $dados_qui['unidade_id']             = $unidade_id;
                   $dados_qui['setor_id']               = $setor_id;
                   $dados_qui['start']                  = $start;
                   $dados_qui['end']                    = $end;
                   $dados_qui['user_cadastro']          = get_staff_user_id();
                   $dados_qui['data_cadastro']          = $hoje;
                   $dados_qui['empresa_id']             = $empresa_id;
                   $dados_qui['ano']                    = $ano_comp;
                   $dados_qui['mes']                    = $mes_comp;
                   $dados_qui['dia']                    = $dia;
                   $dados_qui['dia_semana']             = $dia_semana;
                   $dados_qui['config_id']              = $config_id;
                   $dados_qui['horario_id']              = $horario_id;
                   $dados_qui['competencia_id']         = $competencia_id;
                   $this->db->insert(db_prefix() . 'events', $dados_qui);
                //}
            }
            }
            
            if($dia_semana_ef == 'sexta'){
            if($dia_semana == 'sexta'){
                //for($d=0; $d < $sexta; $d++){
                   $escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_id($ef_id);
                   $titular_id                          = $escala_fixa->medico_id;
                   
                   $substituto = $this->Unidades_hospitalares_model->get_substituto_ef_id($ef_id);
                    $substituto_med = $substituto->id_substituto;
                    if($substituto_med){
                        $substituto_id = $substituto_med;
                        $escalado_id = $substituto_med;
                    }else{
                        $substituto_id = '';
                        $escalado_id = $titular_id;
                    }

                        
                    $dados_qui['public']                 = 1;
                    $dados_qui['titular_id']             = $titular_id;
                    $dados_qui['medico_escalado_id']     = $escalado_id;
                    $dados_qui['substituto']             = $substituto_id;
                    $dados_qui['userid']                 = $titular_id;
                   
                   $dados_qui['quantidade']             = $qtde_plantao;
                   $dados_qui['item_id']                = 1;
                   $dados_qui['unidade_id']             = $unidade_id;
                   $dados_qui['setor_id']               = $setor_id;
                   $dados_qui['start']                  = $start;
                   $dados_qui['end']                    = $end;
                   $dados_qui['user_cadastro']          = get_staff_user_id();
                   $dados_qui['data_cadastro']          = $hoje;
                   $dados_qui['empresa_id']             = $empresa_id;
                   $dados_qui['ano']                    = $ano_comp;
                   $dados_qui['mes']                    = $mes_comp;
                   $dados_qui['dia']                    = $dia;
                   $dados_qui['dia_semana']             = $dia_semana;
                   $dados_qui['config_id']              = $config_id;
                   $dados_qui['horario_id']              = $horario_id;
                   $dados_qui['competencia_id']         = $competencia_id;
                   $this->db->insert(db_prefix() . 'events', $dados_qui);
                //}
            }
            }
            
            if($dia_semana_ef == 'sabado'){ 
            if($dia_semana == 'sabado'){
                //for($d=0; $d < $sabado; $d++){
                   $escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_id($ef_id);
                   $titular_id                           = $escala_fixa->medico_id;
                   
                   $substituto = $this->Unidades_hospitalares_model->get_substituto_ef_id($ef_id);
                    $substituto_med = $substituto->id_substituto;
                    if($substituto_med){
                        $substituto_id = $substituto_med;
                        $escalado_id = $substituto_med;
                    }else{
                        $substituto_id = '';
                        $escalado_id = $titular_id;
                    }

                        
                    $dados_qui['public']                 = 1;
                    $dados_qui['titular_id']             = $titular_id;
                    $dados_qui['medico_escalado_id']     = $escalado_id;
                    $dados_qui['substituto']             = $substituto_id;
                    $dados_qui['userid']                 = $titular_id;
                   
                   $dados_qui['quantidade']             = $qtde_plantao;
                   $dados_qui['item_id']                = 1;
                   $dados_qui['unidade_id']             = $unidade_id;
                   $dados_qui['setor_id']               = $setor_id;
                   $dados_qui['start']                  = $start;
                   $dados_qui['end']                    = $end;
                   $dados_qui['user_cadastro']          = get_staff_user_id();
                   $dados_qui['data_cadastro']          = $hoje;
                   $dados_qui['empresa_id']             = $empresa_id;
                   $dados_qui['ano']                    = $ano_comp;
                   $dados_qui['mes']                    = $mes_comp;
                   $dados_qui['dia']                    = $dia;
                   $dados_qui['dia_semana']             = $dia_semana;
                   $dados_qui['config_id']              = $config_id;
                   $dados_qui['horario_id']              = $horario_id;
                   $dados_qui['competencia_id']         = $competencia_id;
                   $this->db->insert(db_prefix() . 'events', $dados_qui);
                //}
            }
            }
            
            if($dia_semana_ef == 'domingo'){
            if($dia_semana == 'domingo'){
                //for($d=0; $d < $domingo; $d++){
                   $escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_id($ef_id);
                   $titular_id                          = $escala_fixa->medico_id;
                   
                   $substituto = $this->Unidades_hospitalares_model->get_substituto_ef_id($ef_id);
                    $substituto_med = $substituto->id_substituto;
                    if($substituto_med){
                        $substituto_id = $substituto_med;
                        $escalado_id = $substituto_med;
                    }else{
                        $substituto_id = '';
                        $escalado_id = $titular_id;
                    }

                        
                    $dados_qui['public']                 = 1;
                    $dados_qui['titular_id']             = $titular_id;
                    $dados_qui['medico_escalado_id']     = $escalado_id;
                    $dados_qui['substituto']             = $substituto_id;
                    $dados_qui['userid']                 = $titular_id;
                   
                   $dados_qui['quantidade']             = $qtde_plantao;
                   $dados_qui['item_id']                = 1;
                   $dados_qui['unidade_id']             = $unidade_id;
                   $dados_qui['setor_id']               = $setor_id;
                   $dados_qui['start']                  = $start;
                   $dados_qui['end']                    = $end;
                   $dados_qui['user_cadastro']          = get_staff_user_id();
                   $dados_qui['data_cadastro']          = $hoje;
                   $dados_qui['empresa_id']             = $empresa_id;
                   $dados_qui['ano']                    = $ano_comp;
                   $dados_qui['mes']                    = $mes_comp;
                   $dados_qui['dia']                    = $dia;
                   $dados_qui['dia_semana']             = $dia_semana;
                   $dados_qui['config_id']              = $config_id;
                   $dados_qui['horario_id']              = $horario_id;
                   $dados_qui['competencia_id']         = $competencia_id;
                   $this->db->insert(db_prefix() . 'events', $dados_qui);
                //}
            }
            }
            
            
            
        }
        
            
            /*
             * GERA A ESCALA FIXA
             */
            
            log_activity('HORÁRIO PLANTÃO insert [ ID : '.$id_registro.']', null, $data);
        
      
            return true;
            
       
    }
    
    
    /*
     * Retorna os plantoes de uma competencia
     */
    
    public function get_plantoes_escala_by_competencia_id($competencia_id = '')
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = "";
       $sql = 'SELECT * FROM tblevents e
                where  e.deleted = 0 and e.empresa_id = '.$empresa_id .' and e.competencia_id = '.$competencia_id;
               
            $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    
    /*
     * Competencia setores
     */
    
    public function get_competencia_setores_by_competencia_id($competencia_id = '')
    {
       $empresa_id = $this->session->userdata('empresa_id');
       $sql = "";
       $sql = 'SELECT * FROM tblcompetencias_plantoes_setores cs
                where  cs.deleted = 0 and cs.empresa_id = '.$empresa_id .' and cs.competencia_id = '.$competencia_id;
                
            $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    
    /*
     * 19/07/22
     * Israel Araujo
     * Cria o cadstro de setor por unidade, baseado na criacao de plantao por competencia anterior 
     */
    public function add_setor_unidade_by_competencia($data)
    {
        $id_registro = $this->db->insert(db_prefix() . 'competencias_plantoes_setores', $data);
        
        log_activity('COMPETENCIA PLANTAO SETOR insert [ ID : '.$id_registro.']', null, $data);
        
        return true;
    }
    
    
    /*
     * config baseada na competencia anterios
     */
    public function get_qtde_plantoes_by_config_competencia($setor_id, $competencia_id = '')
    {
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "SELECT count(*) as quantidade, dia_semana, horario_id FROM tblevents e where e.deleted = 0 and e.empresa_id = $empresa_id
                and e.setor_id = $setor_id
                and competencia_id = $competencia_id
                group by dia_semana";
         //  echo $sql; exit;
            $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    
    
    /*
     * RETORNA A QUANTIDADE DE PLANTÕES POR DIA DA SEMANA, CONFIG E COMPETENCIA
     */
    public function get_qtde_plantoes_by_config_competencia_dia_semana($setor_id = '', $competencia_id = '', $dia_semana = '')
    {
       $empresa_id = $this->session->userdata('empresa_id');
       
       $sql = "SELECT   dia, titular_id, medico_escalado_id, substituto, quantidade, dia_semana, horario_id, config_id, start, end
                FROM tblevents e
                where e.deleted = 0
                and e.empresa_id = $empresa_id
                and e.setor_id = $setor_id
                and competencia_id = $competencia_id
                and dia_semana = '$dia_semana'

                and dia =
                (SELECT min(dia) as dia
                FROM tblevents e
                where e.deleted = 0
                and e.empresa_id = $empresa_id
                and e.setor_id = $setor_id
                and competencia_id = $competencia_id
                and dia_semana = '$dia_semana')";
            // echo $sql; exit;
            $result = $this->db->query($sql)->result_array();
       return $result;
    }
    
    
    /*
     * ADD PLANTAO A PARTIR DE OUTRA COMPETENCIA
     */
    
    public function add_planto_by_competencia_origem($data, $competencia_referencia)
    {
       $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        
        $setor_id = $data['setor_id'];
        $unidade_id = $data['unidade_id'];
        $competencia_id = $data['competencia_id']; 
       
       
        
        $verifica_config_setor = $this->Unidades_hospitalares_model->get_qtde_plantoes_by_config_competencia($setor_id, $competencia_referencia);
        foreach ($verifica_config_setor as $config_setor){
            
        
        //$config_id = $verifica_config_setor->id;
        $dia_semana_s = $config_setor['dia_semana'];
        
        $segunda = 0;
        if($dia_semana_s == 'segunda'){
            $segunda = 1;
        }
        
        $terca = 0;
        if($dia_semana_s == 'terca'){
            $terca = 1;
        }
        
        $quarta = 0;
        if($dia_semana_s == 'quarta'){
            $quarta = 1;
        }
        
        $quinta = 0;
        if($dia_semana_s == 'quinta'){
            $quinta = 1;
        }
        
        $sexta = 0;
        if($dia_semana_s == 'sexta'){
            $sexta = 1;
        }
        
        $sabado = 0;
        if($dia_semana_s == 'sabado'){
            $sabado = 1;
        }
        
        $domingo = 0;
        if($dia_semana_s == 'domingo'){
            $domingo = 1;
        }
        
         
        //COMPETENCIA
        $competencia = $this->Unidades_hospitalares_model->get_competencia_escala($competencia_id);
        $ano_comp = $competencia->ano;
        $mes_comp = $competencia->mes;
        
        $tam_mes = strlen($mes_comp);
        if($tam_mes == 1){
            $mes_comp = '0'.$mes_comp;
        }
        $funcao = new DateTime("$ano_comp-$mes_comp");
        $numDias_mes = $funcao->format('t');
        
        $diasemana = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');
        
       
        for($dia=1; $dia <= $numDias_mes; $dia++){
            $tam_dia = strlen($dia);
            if($tam_dia == 1){
                $dia = '0'.$dia;
            }
            $data_cal = "$ano_comp-$mes_comp-$dia";
            $diasemana_numero = date('w', strtotime($data_cal));
            $dia_semana = $diasemana[$diasemana_numero];
          
                     
            
            if($segunda){
                if($dia_semana == 'segunda'){
                    $dados_escala_referencia = $this->Unidades_hospitalares_model->get_qtde_plantoes_by_config_competencia_dia_semana($setor_id, $competencia_referencia,  'segunda');
                    foreach ($dados_escala_referencia as $ref){
                        $titular_id         = $ref['titular_id'];
                        $medico_escalado_id = $ref['medico_escalado_id'];
                        $substituto         = $ref['substituto'];
                        $quantidade         = $ref['quantidade'];
                        $dia_semana         = $ref['dia_semana'];
                        $horario_id         = $ref['horario_id'];
                        $config_id          = $ref['config_id'];
                        
                        $horario_plantao2 = $this->Unidades_hospitalares_model->get_horario_plantao_by_id($horario_id);
                        $hora_inicio_r     = $horario_plantao2->hora_inicio;
                        $hora_fim_r        = $horario_plantao2->hora_fim;
                        
                        $start = "$data_cal:$hora_inicio_r";
                        $end = "$data_cal:$hora_fim_r";
                        
                        if($substituto){
                            if($substituto != $medico_escalado_id){
                                $medico_escalado_id = '';
                            }
                        }else if($titular_id != $medico_escalado_id){
                            $medico_escalado_id = '';
                        }
                        
                       // $escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_setor_dia($config_id, 'segunda');
                        //$medico_id = $escala_fixa[$d]['medico_id'];
                        $dados_seg['public']                 = 1;
                        $dados_seg['medico_escalado_id']     = $medico_escalado_id;
                        $dados_seg['titular_id']             = $titular_id;
                        $dados_seg['substituto']             = $substituto;
                        $dados_seg['userid']                 = $titular_id;
                        $dados_seg['quantidade']             = $quantidade;
                        $dados_seg['item_id']                = 1;
                        $dados_seg['unidade_id']             = $unidade_id;
                        $dados_seg['setor_id']               = $setor_id;
                        $dados_seg['start']                  = $start;
                        $dados_seg['end']                    = $end;
                        $dados_seg['user_cadastro']          = get_staff_user_id();
                        $dados_seg['data_cadastro']          = $hoje;
                        $dados_seg['empresa_id']             = $empresa_id;
                        $dados_seg['ano']                    = $ano_comp;
                        $dados_seg['mes']                    = $mes_comp;
                        $dados_seg['dia']                    = $dia;
                        $dados_seg['dia_semana']             = $dia_semana;
                        $dados_seg['config_id']              = $config_id;
                        $dados_seg['horario_id']             = $horario_id;
                        $dados_seg['competencia_id']         = $competencia_id;
                        
                        //print_r($dados_seg);
                        //echo '<br><br>';
                        $this->db->insert(db_prefix() . 'events', $dados_seg);
                    } 
                }
            }
            if($terca){
                if($dia_semana == 'terca'){
                $dados_escala_referencia = $this->Unidades_hospitalares_model->get_qtde_plantoes_by_config_competencia_dia_semana($setor_id, $competencia_referencia,  'terca');
                    foreach ($dados_escala_referencia as $ref){
                        $titular_id         = $ref['titular_id'];
                        $medico_escalado_id = $ref['medico_escalado_id'];
                        $substituto         = $ref['substituto'];
                        $quantidade         = $ref['quantidade'];
                        $dia_semana         = $ref['dia_semana'];
                        $horario_id         = $ref['horario_id'];
                        $config_id          = $ref['config_id'];
                        
                        $horario_plantao2 = $this->Unidades_hospitalares_model->get_horario_plantao_by_id($horario_id);
                        $hora_inicio_r     = $horario_plantao2->hora_inicio;
                        $hora_fim_r        = $horario_plantao2->hora_fim;
                        
                        $start = "$data_cal:$hora_inicio_r";
                        $end = "$data_cal:$hora_fim_r";
                        
                        if($substituto){
                            if($substituto != $medico_escalado_id){
                                $medico_escalado_id = '';
                            }
                        } else if($titular_id != $medico_escalado_id){
                            $medico_escalado_id = '';
                        }
                        
                    $dados_ter['public']                 = 1;
                    $dados_ter['medico_escalado_id']     = $medico_escalado_id;
                    $dados_ter['titular_id']             = $titular_id;
                    $dados_ter['substituto']             = $substituto;
                    $dados_ter['userid']                 = $titular_id;
                    $dados_ter['quantidade']             = $quantidade;
                    $dados_ter['item_id']                = 1;
                    $dados_ter['unidade_id']             = $unidade_id;
                    $dados_ter['setor_id']               = $setor_id;
                    $dados_ter['start']                  = $start;
                    $dados_ter['end']                    = $end;
                    $dados_ter['user_cadastro']          = get_staff_user_id();
                    $dados_ter['data_cadastro']          = $hoje;
                    $dados_ter['empresa_id']             = $empresa_id;
                    $dados_ter['ano']                    = $ano_comp;
                    $dados_ter['mes']                    = $mes_comp;
                    $dados_ter['dia']                    = $dia;
                    $dados_ter['dia_semana']             = "$dia_semana";
                    $dados_ter['config_id']              = $config_id;
                    $dados_ter['horario_id']             = $horario_id;
                    $dados_ter['competencia_id']         = $competencia_id;
                    $this->db->insert(db_prefix() . 'events', $dados_ter);
                    //print_r($dados_ter);
                    //    echo '<br><br>';
                }
            } 
            }
            if($quarta){
                if($dia_semana == 'quarta'){
                
                    $dados_escala_referencia = $this->Unidades_hospitalares_model->get_qtde_plantoes_by_config_competencia_dia_semana($setor_id, $competencia_referencia,  'quarta');
                    foreach ($dados_escala_referencia as $ref){
                        $titular_id         = $ref['titular_id'];
                        $medico_escalado_id = $ref['medico_escalado_id'];
                        $substituto         = $ref['substituto'];
                        $quantidade         = $ref['quantidade'];
                        $dia_semana         = $ref['dia_semana'];
                        $horario_id         = $ref['horario_id'];
                        $config_id          = $ref['config_id'];
                        
                        $horario_plantao2 = $this->Unidades_hospitalares_model->get_horario_plantao_by_id($horario_id);
                        $hora_inicio_r     = $horario_plantao2->hora_inicio;
                        $hora_fim_r        = $horario_plantao2->hora_fim;
                        
                        $start = "$data_cal:$hora_inicio_r";
                        $end = "$data_cal:$hora_fim_r";
                        
                        if($substituto){
                            if($substituto != $medico_escalado_id){
                                $medico_escalado_id = '';
                            }
                        } else if($titular_id != $medico_escalado_id){
                            $medico_escalado_id = '';
                        }
                        
                        //$escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_setor_dia($config_id, 'quarta');
                        //$medico_id = $escala_fixa[$d]['medico_id'];
                        
                        $dados_qua['public']                 = 1;
                        $dados_qua['medico_escalado_id']     = $medico_escalado_id;
                        $dados_qua['titular_id']             = $titular_id;
                        $dados_qua['substituto']             = $substituto;
                        $dados_qua['userid']                 = $titular_id;
                        $dados_qua['quantidade']             = $quantidade;
                        $dados_qua['item_id']                = 1;
                        $dados_qua['unidade_id']             = $unidade_id;
                        $dados_qua['setor_id']               = $setor_id;
                        $dados_qua['start']                  = $start;
                        $dados_qua['end']                    = $end;
                        $dados_qua['user_cadastro']          = get_staff_user_id();
                        $dados_qua['data_cadastro']          = $hoje;
                        $dados_qua['empresa_id']             = $empresa_id;
                        $dados_qua['ano']                    = $ano_comp;
                        $dados_qua['mes']                    = $mes_comp;
                        $dados_qua['dia']                    = $dia;
                        $dados_qua['dia_semana']             = $dia_semana;
                        $dados_qua['config_id']              = $config_id;
                        $dados_qua['horario_id']              = $horario_id;
                        $dados_qua['competencia_id']         = $competencia_id;
                        
                        //print_r($dados_qua);
                        //echo '<br><br>';
                        $this->db->insert(db_prefix() . 'events', $dados_qua);
                    }
                    
                  
                }
            }
            if($quinta){
                if($dia_semana == 'quinta'){
                $dados_escala_referencia = $this->Unidades_hospitalares_model->get_qtde_plantoes_by_config_competencia_dia_semana($setor_id, $competencia_referencia,  'quinta');
                    foreach ($dados_escala_referencia as $ref){
                        $titular_id         = $ref['titular_id'];
                        $medico_escalado_id = $ref['medico_escalado_id'];
                        $substituto         = $ref['substituto'];
                        $quantidade         = $ref['quantidade'];
                        $dia_semana         = $ref['dia_semana'];
                        $horario_id         = $ref['horario_id'];
                        $config_id          = $ref['config_id'];
                        
                        $horario_plantao2 = $this->Unidades_hospitalares_model->get_horario_plantao_by_id($horario_id);
                        $hora_inicio_r     = $horario_plantao2->hora_inicio;
                        $hora_fim_r        = $horario_plantao2->hora_fim;
                        
                        $start = "$data_cal:$hora_inicio_r";
                        $end = "$data_cal:$hora_fim_r";
                        
                        if($substituto){
                            if($substituto != $medico_escalado_id){
                                $medico_escalado_id = '';
                            }
                        } else if($titular_id != $medico_escalado_id){
                            $medico_escalado_id = '';
                        }
                        
                        
                   //$escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_setor_dia($config_id, 'quinta');
                   //$medico_id = $escala_fixa[$d]['medico_id'];
                   $dados_qui['public']                 = 1;
                   $dados_qui['medico_escalado_id']     = $medico_escalado_id;
                   $dados_qui['titular_id']             = $titular_id;
                   $dados_qui['substituto']             = $substituto;
                   $dados_qui['userid']                 = $titular_id;
                   $dados_qui['quantidade']             = $quantidade;
                   $dados_qui['item_id']                = 1;
                   $dados_qui['unidade_id']             = $unidade_id;
                   $dados_qui['setor_id']               = $setor_id;
                   $dados_qui['start']                  = $start;
                   $dados_qui['end']                    = $end;
                   $dados_qui['user_cadastro']          = get_staff_user_id();
                   $dados_qui['data_cadastro']          = $hoje;
                   $dados_qui['empresa_id']             = $empresa_id;
                   $dados_qui['ano']                    = $ano_comp;
                   $dados_qui['mes']                    = $mes_comp;
                   $dados_qui['dia']                    = $dia;
                   $dados_qui['dia_semana']             = $dia_semana;
                   $dados_qui['config_id']              = $config_id;
                   $dados_qui['horario_id']              = $horario_id;
                   $dados_qui['competencia_id']         = $competencia_id;
                   
                   //print_r($dados_qui);
                   //     echo '<br><br>';
                   
                   $this->db->insert(db_prefix() . 'events', $dados_qui);
                }
                
            }
            }
            if($sexta){
                if($dia_semana == 'sexta'){
                $dados_escala_referencia = $this->Unidades_hospitalares_model->get_qtde_plantoes_by_config_competencia_dia_semana($setor_id, $competencia_referencia,  'sexta');
                    foreach ($dados_escala_referencia as $ref){
                        $titular_id         = $ref['titular_id'];
                        $medico_escalado_id = $ref['medico_escalado_id'];
                        $substituto         = $ref['substituto'];
                        $quantidade         = $ref['quantidade'];
                        $dia_semana         = $ref['dia_semana'];
                        $horario_id         = $ref['horario_id'];
                        $config_id          = $ref['config_id'];
                        
                        $horario_plantao2 = $this->Unidades_hospitalares_model->get_horario_plantao_by_id($horario_id);
                        $hora_inicio_r     = $horario_plantao2->hora_inicio;
                        $hora_fim_r        = $horario_plantao2->hora_fim;
                        
                        $start = "$data_cal:$hora_inicio_r";
                        $end = "$data_cal:$hora_fim_r";
                        
                        if($substituto){
                            if($substituto != $medico_escalado_id){
                                $medico_escalado_id = '';
                            }
                        } else if($titular_id != $medico_escalado_id){
                            $medico_escalado_id = '';
                        }
                   //$escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_setor_dia($config_id, 'sexta');
                   //$medico_id = $escala_fixa[$d]['medico_id'];
                        
                   $dados_sex['public']                 = 1;
                   $dados_sex['medico_escalado_id']     = $medico_escalado_id;
                   $dados_sex['titular_id']             = $titular_id;
                   $dados_sex['substituto']             = $substituto;
                   $dados_sex['userid']                = $titular_id;
                   $dados_sex['quantidade']             = $quantidade;
                   $dados_sex['item_id']                = 1;
                   $dados_sex['unidade_id']             = $unidade_id;
                   $dados_sex['setor_id']               = $setor_id;
                   $dados_sex['start']                  = $start;
                   $dados_sex['end']                    = $end;
                   $dados_sex['user_cadastro']          = get_staff_user_id();
                   $dados_sex['data_cadastro']          = $hoje;
                   $dados_sex['empresa_id']             = $empresa_id;
                   $dados_sex['ano']                    = $ano_comp;
                   $dados_sex['mes']                    = $mes_comp;
                   $dados_sex['dia']                    = $dia;
                   $dados_sex['dia_semana']             = $dia_semana;
                   $dados_sex['config_id']              = $config_id;
                   $dados_sex['horario_id']              = $horario_id;
                   $dados_sex['competencia_id']         = $competencia_id;
                   $this->db->insert(db_prefix() . 'events', $dados_sex);
                   
                   //print_r($dados_qui);
                   //     echo '<br><br>';
                }
            }
            }
            if($sabado){ 
                if($dia_semana == 'sabado'){
                $dados_escala_referencia = $this->Unidades_hospitalares_model->get_qtde_plantoes_by_config_competencia_dia_semana($setor_id, $competencia_referencia,  'sabado');
                    foreach ($dados_escala_referencia as $ref){
                        $titular_id         = $ref['titular_id'];
                        $medico_escalado_id = $ref['medico_escalado_id'];
                        $substituto         = $ref['substituto'];
                        $quantidade         = $ref['quantidade'];
                        $dia_semana         = $ref['dia_semana'];
                        $horario_id         = $ref['horario_id'];
                        $config_id          = $ref['config_id'];
                        
                        $horario_plantao2 = $this->Unidades_hospitalares_model->get_horario_plantao_by_id($horario_id);
                        $hora_inicio_r     = $horario_plantao2->hora_inicio;
                        $hora_fim_r        = $horario_plantao2->hora_fim;
                        
                        $start = "$data_cal:$hora_inicio_r";
                        $end = "$data_cal:$hora_fim_r";
                        
                        if($substituto){
                            if($substituto != $medico_escalado_id){
                                $medico_escalado_id = '';
                            }
                        } else if($titular_id != $medico_escalado_id){
                            $medico_escalado_id = '';
                        }
                        
                   //$escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_setor_dia($config_id, 'sabado');
                   //$medico_id = $escala_fixa[$d]['medico_id'];
                   $dados_sab['public']                 = 1;
                   $dados_sab['medico_escalado_id']     = $medico_escalado_id;
                   $dados_sab['titular_id']             = $titular_id;
                   $dados_sab['substituto']             = $substituto;
                   $dados_sab['userid']                 = $titular_id;
                   $dados_sab['quantidade']             = $quantidade;
                   $dados_sab['item_id']                = 1;
                   $dados_sab['unidade_id']             = $unidade_id;
                   $dados_sab['setor_id']               = $setor_id;
                   $dados_sab['start']                  = $start;
                   $dados_sab['end']                    = $end;
                   $dados_sab['user_cadastro']          = get_staff_user_id();
                   $dados_sab['data_cadastro']          = $hoje;
                   $dados_sab['empresa_id']             = $empresa_id;
                   $dados_sab['ano']                    = $ano_comp;
                   $dados_sab['mes']                    = $mes_comp;
                   $dados_sab['dia']                    = $dia;
                   $dados_sab['dia_semana']             = $dia_semana;
                   $dados_sab['config_id']              = $config_id;
                   $dados_sab['horario_id']              = $horario_id;
                   $dados_sab['competencia_id']         = $competencia_id;
                   $this->db->insert(db_prefix() . 'events', $dados_sab);
                }
            }
            }
            if($domingo){
                if($dia_semana == 'domingo'){
                $dados_escala_referencia = $this->Unidades_hospitalares_model->get_qtde_plantoes_by_config_competencia_dia_semana($setor_id, $competencia_referencia,  'domingo');
                    foreach ($dados_escala_referencia as $ref){
                        $titular_id         = $ref['titular_id'];
                        $medico_escalado_id = $ref['medico_escalado_id'];
                        $substituto         = $ref['substituto'];
                        $quantidade         = $ref['quantidade'];
                        $dia_semana         = $ref['dia_semana'];
                        $horario_id         = $ref['horario_id'];
                        $config_id          = $ref['config_id'];
                        
                        $horario_plantao2 = $this->Unidades_hospitalares_model->get_horario_plantao_by_id($horario_id);
                        $hora_inicio_r     = $horario_plantao2->hora_inicio;
                        $hora_fim_r        = $horario_plantao2->hora_fim;
                        
                        $start = "$data_cal:$hora_inicio_r";
                        $end = "$data_cal:$hora_fim_r";
                        
                        if($substituto){
                            if($substituto != $medico_escalado_id){
                                $medico_escalado_id = '';
                            }
                        } else if($titular_id != $medico_escalado_id){
                            $medico_escalado_id = '';
                        }
                   //$escala_fixa = $this->Unidades_hospitalares_model->get_escala_fixa_by_setor_dia($config_id, 'domingo');
                   //$medico_id = $escala_fixa[$d]['medico_id'];
                   $dados_dom['public']                 = 1;
                   $dados_dom['medico_escalado_id']     = $medico_escalado_id;
                   $dados_dom['userid']                 = $titular_id;
                   $dados_dom['substituto']             = $substituto;
                   $dados_dom['titular_id']             = $titular_id;
                   $dados_dom['quantidade']             = $quantidade;
                   $dados_dom['item_id']                = 1;
                   $dados_dom['unidade_id']             = $unidade_id;
                   $dados_dom['setor_id']               = $setor_id;
                   $dados_dom['start']                  = $start;
                   $dados_dom['end']                    = $end;
                   $dados_dom['user_cadastro']          = get_staff_user_id();
                   $dados_dom['data_cadastro']          = $hoje;
                   $dados_dom['empresa_id']             = $empresa_id;
                   $dados_dom['ano']                    = $ano_comp;
                   $dados_dom['mes']                    = $mes_comp;
                   $dados_dom['dia']                    = $dia;
                   $dados_dom['dia_semana']             = $dia_semana;
                   $dados_dom['config_id']              = $config_id;
                   $dados_dom['horario_id']              = $horario_id;
                   $dados_dom['competencia_id']         = $competencia_id;
                   $this->db->insert(db_prefix() . 'events', $dados_dom);
                }
            }
            }
            
        }// fim if dias do mes
        
        }// fim dias da semana que tem plantao para o setor
        
    }
    
}
