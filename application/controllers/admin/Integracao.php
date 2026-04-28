<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Integracao extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Clients_model');
        $this->load->model('Appointly_model');
        $this->load->model('payments_model');
        $this->load->model('payment_modes_model');
        $this->load->model('Contas_financeiras_model');
        $this->load->model('Invoice_items_model');
    }


    public function lourenco_carga($id = '')
    {
       
        
        $row = 1;
        if (($handle = fopen("lourenco/BD1.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";   
                $row++;
                for ($c=0; $c < $num; $c++) {
                    $blackpowder = $data;
                    $dynamit = implode(";", $blackpowder);
                    $pieces = explode(";", $dynamit);
                    $medico         = $pieces[0]; //ok
                    $tipo           = $pieces[1]; //ok
                    $codigo         = $pieces[2]; //ok
                    $data           = $pieces[3]; //ok
                    $paciente       = $pieces[4]; //ok
                    $convenio       = $pieces[5]; // ok
                    $procedimento   = $pieces[6];
                    
                    /*
                     * 1o - verifica o paciente
                     */
                    $where['company'] = $paciente;
                    $cliente = $this->Clients_model->get(null, $where);
                    foreach ($cliente as $cli){
                        $userid = $cli['userid'];
                       
                    }
                    // se n tiver o cliente, tem q cadastrar
                    if(!$userid){
                        $data_n['company'] = $paciente;
                        $data_n['datecreated'] = date('Y-m-d H:i:s');
                        if (is_staff_logged_in()) {
                            $data_n['addedfrom'] = get_staff_user_id();
                        }
                        //$empresa_id = $this->session->userdata('empresa_id');
                        //$data_n['empresa_id'] = $empresa_id;
                       
                        $userid = $this->clients_model->add($data_n);
                    }
                    
                    /*
                     * 2 - CRIA A AGENDA
                     */
                    if($userid){
                        $data_a['date'] = $data;
                        $data_a['start_hour'] = '00:00';
                        $data_a['approved'] = 1;
                        $data_a['finished'] = 1;
                        $data_a['cancelled'] = 0;
                        
                        $data_a['type_id'] = $tipo;
                        $data_a['medico_id'] = $medico;
                        $data_a['convenio'] = $convenio;
                        $data_a['procedimentos'] = $procedimento;
                        
                        $data_a['integracao_up'] = $codigo;
                        
                        $data_a['contact_id'] = $userid;
                        $data_a['datecreated'] = date('Y-m-d H:i:s');
                        $data_a['rel_type'] = 'internal';
                        
                       
                        
                        $agenda_id = $this->Appointly_model->insert_appointment($data_a);
                        
                       
                    }
                    
                    echo $agenda_id.'<br>'; 
                }
            }
        }
        
    }
    
   

    public function add_repasse()
    {
        
        $saldo = $this->input->post('saldo');
        $valor = $this->input->post('valor');
        $valor = str_replace(',','.', $valor);
        
        if($valor == 0){
             set_alert('danger', 'Informe o valor do repasse.');
             redirect($_SERVER['HTTP_REFERER']);
        }else if($valor > $saldo){
            set_alert('danger', 'O valor do repasse não pode ser maior que o saldo do caixa.');
             redirect($_SERVER['HTTP_REFERER']);
        }
        
      
        if ($this->input->post()) {
            $success = $this->Repasses_model->add($this->input->post());
            if ($success) {
                set_alert('success', _l('added_successfully', _l('repasse')));
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function atualiza_producao_procedimentos()
    {
        $atendimentos = $this->Appointly_model->get_todos_agendamentos();
        
        foreach ($atendimentos as $atend){
            $atendimento_id = $atend['id'];
            $procedimentos = $atend['procedimentos'];
            
            
            if($procedimentos){
               
                //echo $procedimentos.'<br>';
                
                $array_procedimentos = explode(',', $procedimentos);
                foreach($array_procedimentos as $procedimento_id)
                {
                 
                 //verifica se ja tem na tabela de procedimentos
                $atendimentos = $this->Appointly_model->get_verifica_procedimento($atendimento_id);
                $registros = count($atendimentos);
                
                
                if($registros == 0){
                    // insert na tabela de procedimentos
                    $empresa_id = $this->session->userdata('empresa_id');
                    $dados_proc['agenda_id'] = $atendimento_id;
                    $dados_proc['item_id'] = $procedimento_id;
                    $dados_proc['empresa_id'] = $empresa_id;
                    $dados_proc['quantidade'] = 1;
                    
                   // echo $atendimento_id;
                   // print_r($dados_proc);
                   // echo '<br>';
                    
                    // adiciona na tabela de procedimentos
                    $this->Appointly_model->new_procedimento_atendimento($dados_proc);
                    
                    
                }
                 
                 
                }
                
               
                
               // echo $registros;
                // exit;
            }
            
            
           
        }
        
        
    }
    
    
    public function atualiza_repasse_medico_procedimentos()
    {
        $atendimentos = $this->Invoice_items_model->get_todos_repasses_medicos();
        
        foreach ($atendimentos as $atend){
            $item_id = $atend['item_id'];
            $medico_id = $atend['medicoid'];
            $valor= $atend['valor'];
            $tipo = $atend['tipo'];
            $empresa = $atend['empresa_id'];
            $data_inicio = '2021-01-01';
          
           
            
            if($item_id){
                $atendimentos = $this->Invoice_items_model->get_verifica_procedimento_repasse($item_id, $medico_id);
                $registros = count($atendimentos);
                //echo $registros; exit;
                if(!$registros){
                    $empresa_id = $this->session->userdata('empresa_id');
                    $dados_proc['medico_id']    = $medico_id;
                    $dados_proc['item_id']      = $item_id;
                    $dados_proc['empresa_id'] = $empresa_id;
                    $dados_proc['valor'] = $valor;
                    $dados_proc['tipo'] = $tipo;
                    $dados_proc['data_inicio'] = $data_inicio;
                    $dados_proc['data_log'] = date('Y-m-d H:i:s');
                    $dados_proc['usuario_log'] = 1;
                   // echo $atendimento_id;
                    //print_r($dados_proc);
                   // echo '<br>';
                   //exit; 
                    // adiciona na tabela de procedimentos
                    $this->Invoice_items_model->add_new_procedimento_repasse($dados_proc);
                }    
              
            }
            
            
           
        }
        
        
    }

}
