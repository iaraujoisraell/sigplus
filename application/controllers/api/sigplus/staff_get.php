<?php

defined('BASEPATH') or exit('No direct script access allowed');

class staff_get extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_sigplus_model');
             
    }

    public function index(){
        
        $postjson = json_decode(file_get_contents('php://input'), true);
        
        $hash =  $postjson['hash'];
       
        $usuario_api  = $postjson['usuario_api']; 
        $senha_api  = $postjson['senha_api']; 
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
        if(!$hash){
            echo 'Informe o Hash!';
        }
        
       $data = $this->Api_sigplus_model->get_staff_by_hash($hash);
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
