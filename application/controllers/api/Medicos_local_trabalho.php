<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Medicos_local_trabalho extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->model('Staff_model');        
    }

    public function index(){
        
        $postjson = json_decode(file_get_contents('php://input'), true);
        
        $hash =  $postjson['hash'];
        $usuario_api  = $postjson['usuario_api']; 
        $senha_api  = $postjson['senha_api']; 
        $cpf =  $postjson['cpf'];
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
        if(!$hash){
            echo 'Informe o Hash!';
        }
        
       $dados_user = $this->Api_model->get_empresa_staff_by_hash($hash);
       $empresa_id = $dados_user->empresa_id;
       
       // retorna os dados do médico
       $dados_medico = $this->Api_model->get_medico_by_cpf($cpf,$empresa_id);
       $medico_id = $dados_medico->medicoid;
       
        $this->Api_model->get_lista_medicos_local_trabalho($medico_id,  $empresa_id);
        
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
