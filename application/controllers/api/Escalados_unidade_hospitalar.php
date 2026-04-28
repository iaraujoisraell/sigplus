<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Escalados_unidade_hospitalar extends App_Controller
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
        $unidadeid =  $postjson['unidadeid'];
        $dia =  $postjson['dia'];
        $mes =  $postjson['mes'];
        $ano =  $postjson['ano'];
        $usuario_api  = $postjson['usuario_api']; 
        $senha_api  = $postjson['senha_api']; 
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
        if(!$hash){
            echo 'Informe o Hash!';
        }
        
       $dados_user = $this->Api_model->get_empresa_staff_by_hash($hash);
       $empresa_id = $dados_user->empresa_id;
       
     
        $this->Api_model->get_escalados_unidade_hospitalar($empresa_id, $unidadeid, $dia, $mes, $ano );
        
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
