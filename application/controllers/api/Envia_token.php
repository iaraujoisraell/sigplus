<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Envia_token extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->model('Staff_model');        
    }

    public function index(){
        
        $postjson = json_decode(file_get_contents('php://input'), true);
        
        $email =  $postjson['email'];
        $usuario_api  = $postjson['usuario_api']; 
        $senha_api  = $postjson['senha_api']; 
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
        
        $_GET['email'] = $email;
        include 'rotina_email/api_token.php';
       
        echo 'ok'; exit;
        
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
