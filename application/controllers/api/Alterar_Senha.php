<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Alterar_senha extends App_Controller
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
        $senha_nova =  $postjson['senha_nova'];
        $usuario_api  = $postjson['usuario_api']; 
        $senha_api  = $postjson['senha_api']; 
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
       
        $dados['senha_app'] = $senha_nova;
        $this->Api_model->atualiza_senha_by_token_staff($dados, $token);
    
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
