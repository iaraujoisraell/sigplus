<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Campaign_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get lead
     * @param  string $id Optional - leadid
     * @return mixed
     */
    
    public function add($data = null, $id = 0) {
        
        $empresa_id = $this->session->userdata('empresa_id');
        
        $data['status'] = implode(",",$data['status']);
        $data['fontes'] = implode(",",$data['fontes']);
        $data['equipe'] = implode(",",$data['equipe']);
        $data['dateadded']   = date('Y-m-d');
        $data['addedfrom']   = get_staff_user_id();
        $data['empresa_id']   = $empresa_id;
        
        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
           // unset($data['tags']);
        }
       
        unset($data['id']);
        //echo $id.'<br>';
        //PRINT_R($data);
       // exit;
    
		if ($data) {
			if ($id) {
                            
                                unset($data['equipe']);
				$this->db->where('id', $id);
				if ($this->db->update("tblcampaign", $data)) {
                                       //handle_tags_save($data['tags'], $id, 'lead');
                                    	return true;
				} else {
					return false;
				}
			} else {
				if ($this->db->insert("tblcampaign", $data)) {
                                    $insert_id = $this->db->insert_id();
                                    //handle_tags_save($tags, $insert_id, 'lead');
					return true;
				} else {
					return false;
				}
			}
		}
		
	}
    
        
    public function get_campaign($id = '') 
    {
        $user_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $sql = "SELECT * from tblcampaign "
             . " where deleted = 0 and empresa_id = $empresa_id";
        
        if($id){
           $sql .= " and id = $id";
        }
        
       // if(!is_admin()){
           $sql .= " and equipe like '%$user_id%'"; 
       // }
        
        
     //   ECHO $sql; EXIT;
       return $this->db->query($sql);
    
    }
    
    public function get_fonte($id) 
    {
       $sql = "SELECT * from tblleads_sources where id = $id";
       //echo $sql; exit;
       return $this->db->query($sql);
    
    }
    public function get_status($id) 
    {
       $sql = "SELECT * from tblleads_status where id = $id";
       return $this->db->query($sql);
    
    }

    
}
