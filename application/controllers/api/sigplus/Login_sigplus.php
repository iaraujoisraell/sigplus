<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login_sigplus extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_sigplus_model');        
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
       
        $data = $this->Api_sigplus_model->get_login($email_cpf, $senha);
        if ($data)
        {
            // Set the response and exit
            echo json_encode($data);
        }
        else
        {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No data were found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
