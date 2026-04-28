<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Atendimentos extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('Api_model');
        //$this->load->model('Staff_model');        
    }

    public function index(){
        
        $ch   = curl_init();
        
        curl_setopt_array($ch, array(
                CURLOPT_URL => 'http://189.2.128.66:8080/conectew/producao.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
              
          ));
          $resultado = curl_exec($ch);
          //$postjson = json_decode($resultado, true);
        print_r($resultado ); exit;
        $hash =  $postjson['hash'];
        $usuario_api  = $postjson['usuario_api']; 
        $senha_api  = $postjson['senha_api']; 
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
        if(!$hash){
            echo 'Informe o Hash!';
        }
        
       $dados_user = $this->Api_model->get_empresa_staff_by_hash($hash);
       $empresa_id = $dados_user->empresa_id;
       
       
        $this->Api_model->get_lista_medicos($empresa_id);
        
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
