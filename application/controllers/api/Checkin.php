<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Checkin extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        $this->load->model('Staff_model');        
    }

    public function index(){
        
        $postjson = json_decode(file_get_contents('php://input'), true);
        
        $hash = $postjson['hash'];
        $cpf =  $postjson['cpf'];
        $idplantao =  $postjson['idPlantao'];
        $latitude = $postjson['latitude'];
        $longitude = $postjson['longitude'];
        $data = $postjson['data'];
        $hora = $postjson['hora'];
        $ip = $postjson['ip'];
        $usuario_api = $postjson['usuario_api']; 
        $senha_api  = $postjson['senha_api']; 
        
        if($usuario_api == 'sigplus_api' && $senha_api == 'S!gplus'){
        
        if(!$hash){
            echo 'Informe o Hash!';
        }
        
       $dados_user = $this->Api_model->get_empresa_staff_by_hash($hash);
       $empresa_id = $dados_user->empresa_id;
       
       $dados_medico = $this->Api_model->get_medico_by_cpf($cpf, $empresa_id);
       $medicoid =  $dados_medico->medicoid;
      
           /*
            * REGISTRA O CHECKIN
            */
            $dados_checkin['plantaoid']     = $idplantao;
            $dados_checkin['medicoid']      = $medicoid;
            $dados_checkin['hash']          = $hash;
            $dados_checkin['latitude']      = $latitude;
            $dados_checkin['longitude']     = $longitude;
            $dados_checkin['data']          = $data;
            $dados_checkin['hora']          = $hora;
            $dados_checkin['ip']            = $ip;
            $dados_checkin['empresa_id']    = $empresa_id;
          
           
           $registra_checkin =  $this->Api_model->registra_checkin($dados_checkin);
            if($registra_checkin){
                
                /*
                 * REGISTRA O FATURAMENTO
                 */
                
                
                /*
                 * ALTERA O STATUS DO PLANTAO
                 */
                $dados_plantao['medico_plantonista_id'] = $medicoid;
                $dados_plantao['medico_creditado']      = $medicoid;
                $dados_plantao['status']                = 1;
                $dados_plantao['user_log_checkin']      = $medicoid;
                $dados_plantao['data_log_checkin']      = date('Y-m-d H:i:s');
                $registra_checkin =  $this->Api_model->atualiza_status_plantao($dados_plantao, $idplantao);
                //echo $registra_checkin; exit;
                
                $result = json_encode(array('success'=>true));
                echo $result;
            }else{
                echo 'erro ao realizar o checkin';
            }
        }else{
            echo 'Informe o parâmetro correto!';
        }
    }
    
   
  
    
}
