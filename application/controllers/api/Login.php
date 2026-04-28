<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');        
    }

    public function index(){
        $postjson = json_decode(file_get_contents('php://input'), true);
        
        $email_cpf =  $postjson['email'];
        $senha = $postjson['senha'];
        
        $usuario_api  = $postjson['usuario_api']; //$this->input->post('usuario_api ');
        $senha_api  = $postjson['senha_api']; // $this->input->post('senha_api ');
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
        if(!$email_cpf){
            echo 'Informe o E-mail ou CPF!';
        }
        
        if(!$senha){
            echo 'Informe a senha!';
        }
       
        $this->Api_model->get_login($email_cpf, $senha);
        
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
