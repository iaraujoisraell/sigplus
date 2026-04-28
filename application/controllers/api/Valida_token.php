<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Valida_token extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->model('Staff_model');        
    }

    public function index(){
        
        $postjson = json_decode(file_get_contents('php://input'), true);
        

        $token =  $postjson['token'];
        $usuario_api  = $postjson['usuario_api']; 
        $senha_api  = $postjson['senha_api']; 
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
        if(!$hash){
            echo 'Informe o Hash!';
        }
        
       $dados_user = $this->Api_model->get_empresa_staff_by_hash($hash);
       $empresa_id = $dados_user->empresa_id;
       $staffid = $dados_user->staffid;
     
       
       $this->Api_model->get_staff_token($empresa_id, $staffid);
       
        
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
