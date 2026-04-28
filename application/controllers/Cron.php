<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends App_Controller
{
    public function index($key = '')
    {
        update_option('cron_has_run_from_cli', 1);

        if (defined('APP_CRON_KEY') && (APP_CRON_KEY != $key)) {
            header('HTTP/1.0 401 Unauthorized');
            die('Passed cron job key is not correct. The cron job key should be the same like the one defined in APP_CRON_KEY constant.');
        }

        $last_cron_run                  = get_option('last_cron_run');
        $seconds = hooks()->apply_filters('cron_functions_execute_seconds', 300);

        if ($last_cron_run == '' || (time() > ($last_cron_run + $seconds))) {
            $this->load->model('cron_model');
            $this->cron_model->run();
        }
    }
    
    //encerra automaticamente os atendimentos do dia que ficaram abertos
    public function envia_codigo_barras_boleto(){
        $this->load->model('Comunicacao_model');

        $sql = "SELECT * FROM tbl_intranet_sms  where deleted = 0 and rel_type = 'linha_digitavel' and status = 0  ";
        $resultado =  $this->db->query($sql)->result_array();
       
        foreach($resultado as $res){

            echo $res['id'].'<br>';
            $result = $this->Comunicacao_model->send_sms_boleto($res);
           
        }
        //$result = $this->Comunicacao_model->get_sms_($id);
        //    $result = $this->Comunicacao_model->send_sms($result);
            
        
        
    }
    
    /*
     * VERIFICA SE TEM UMA TABELA DE PREÇO PARA UM PROCEDIMENTO QUE DEVE SE INICIAR NESTE DIA, E ATUALIZA O PREÇO DO PROCEDIMENTO
     * 1 x ao dia
     */
    public function atualiza_tabela_preco(){
        $this->load->model('cron_model');
        $tabelas_hoje = $this->cron_model->get_tabela_preco_dia();
        
        foreach ($tabelas_hoje as $tabela) {
            $id = $tabela['id'];
            $valor = $tabela['valor'];
            $valor2 = $tabela['valor2'];
            $paymentmode2 = $tabela['paymentmode2'];
            $item_id = $tabela['item_id'];
            $data_ajuste['rate']            = $valor;
            $data_ajuste['valor2']          = $valor2;
            $data_ajuste['forma_pgto2']     = $paymentmode2;
            
            $this->cron_model->edit_procedimento($data_ajuste, $item_id);
        }
    }
    
    
    /*
     * VERIFICA SE TEM UMA TABELA DE PREÇO PARA UM PROCEDIMENTO QUE DEVE SE INICIAR NESTE DIA, E ATUALIZA O PREÇO DO PROCEDIMENTO
     * 1 x ao dia
     */
    public function atualiza_tabela_preco_repasse_medico(){
        $this->load->model('cron_model');
        
        
        $medicos_hoje = $this->cron_model->get_medico_tabela_preco_repasse_dia();
        foreach ($medicos_hoje as $tabelam) {
        $medico_id = $tabelam['medico_id'];
        //echo '<br>'.$medico_id.'<br>';
        
        $tabelas_hoje = $this->cron_model->get_tabela_preco_repasse_medico_dia($medico_id);
        foreach ($tabelas_hoje as $tabela) {
            $id                  = $tabela['id'];
            $valor               = $tabela['valor'];
            $tipo                = $tabela['tipo'];
            $item_id             = $tabela['item_id'];
           // $medico_id           = $tabela['medico_id'];
            $data_inicio         = $tabela['data_inicio'];
            $empresa_id          = $tabela['empresa_id'];
            $usuario_log         = $tabela['usuario_log'];
            $data_log            = $tabela['data_log'];
            
          
            
            // 1 - verifica se tem o registro desse procedimento para o médico na tabela principal
            $consulta_registro = $this->cron_model->get_item_medico_tabela_repasse($item_id, $medico_id);
            $id_registro = $consulta_registro->id;
            if($id_registro){
            // se tiver faz o update
            $data_ajusteu['valor']       = $valor;
            $data_ajusteu['tipo']        = $tipo;
            $data_ajusteu['usuario_alteracao'] = $usuario_log;
            $data_ajusteu['data_alteracao']    = date('Y-m-d H:i:s');
           
            //echo 'Edita : '.$item_id.'<br>';
            $this->cron_model->edit_repasse_medico_procedimento_novo($data_ajusteu, $id_registro);
           
            }else{
            
            
            $data_ajuste['item_id']     = $item_id;
            $data_ajuste['medicoid']    = $medico_id;
            $data_ajuste['valor']       = $valor;
            $data_ajuste['tipo']        = $tipo;
            $data_ajuste['empresa_id']  = $empresa_id;
            $data_ajuste['usuario_log'] = $usuario_log;
            $data_ajuste['data_log']    = date('Y-m-d H:i:s');
            
            
            $this->cron_model->add_new_repasse_medico_procedimento($data_ajuste);
          
            
            
            }
            
           
            // se n tiver faz o insert
        }
        
        
         
        }
    }
    
    
    
    /*
     * VERIFICA SE TEM ATENDIMENTO FINALIZADO E ATUALIZA A PRODUÇÃO DO MÉDICO
     * a cada 5 min
     * 
     */
    
     public function atualiza_producao_medica(){
        $this->load->model('cron_model');
        $atendimentos_hoje = $this->cron_model->get_atendimentos_sem_producao();
       
        foreach ($atendimentos_hoje as $tabela) {
            $atemdimento_item_id = $tabela['atemdimento_item_id'];
            $atendimento_id = $tabela['atendimento_id'];
            $item_id = $tabela['item_id'];
            $medico_id = $tabela['medico_id'];
            $data_atendimento = $tabela['data_atendimento'];
            $convenio = $tabela['convenio'];
            $particular = $tabela['particular'];
            $repasse = $tabela['repasse'];
            $valida_fatura_producao = $tabela['valida_fatura_producao'];
            $atendimento_item_id = $tabela['atendimento_item_id'];
            $empresa_id = $tabela['empresa_id'];
            
            
            
            /*
             *  1 - VERIFICA SE P ESSE CONVÊNO TEM REPASSE
             *  SE SIM, CONTINUA, SE NÃO, IDENTIFICA O PROCEDIMENTO E ENCERRA.
             */
            if($repasse == 1){
               
                //2 - VERIFICA SE É PARTICULAR OU CONVÊNIO
              //  if($particular == 1){
                    
                        // 3.1 - Gera o repasse baseado na tabela de repasses médicos
                       
                        // retorna os procedimentos do atendimento
                        $procedimentos = $this->cron_model->get_tabela_items_agenda($atemdimento_item_id);
                        foreach ($procedimentos as $items){
                            $item_id = $items['item_id'];
                            $quantidade = $items['quantidade'];
                            $rate = $items['rate'];
                           
                            // verifica os valores de repasse
                            $dados_item = $this->cron_model->get_dados_item_medico($medico_id, $item_id);
                            if($dados_item->valor){
                                $tipo_repasse = $dados_item->tipo;
                                
                                if($tipo_repasse == 1){
                                $valor_repasse = $dados_item->valor;
                                $valor_repasse = $valor_repasse * $quantidade;
                                $valor_tesouraria = $rate - $valor_repasse;
                                }else{
                                    $porc_repasse = $dados_item->valor;
                                    $valor_repasse = ($rate * $porc_repasse) / 100;
                                   
                                }
                            }else{
                                $valor_repasse = 0;
                                $valor_tesouraria = 0;
                            }
                            
                            
                            // add produção
                            $data_producao['agenda_id']             = $atendimento_id;
                            $data_producao['medico_id']             = $medico_id;
                            $data_producao['item_id']               = $item_id;
                            $data_producao['empresa_id']            = $empresa_id;
                            $data_producao['valor_medico']          = $valor_repasse;
                            $data_producao['valor_empresa']         = $valor_tesouraria;
                            $data_producao['valor_procedimento']    = $rate;
                            $data_producao['data_atendimento']      = $data_atendimento;
                            $data_producao['quantidade']            = $quantidade;
                            $data_producao['data_log']              = date('Y-m-d H:i:s');
                            
                            $this->cron_model->add_producao_medica($data_producao);
                            
                            $data_procedimento['producao_medica']       = 1;
                            $this->cron_model->edit_atendimento_procedimento($data_procedimento, $atendimento_item_id);
                            
                        }
                        
                        
                   // }
                    
                    
                    
                
                //3 - VERIFICA SE PARA ESTE CONVÊNIO, VERIFICA A PRODUÇÃO NA FATURA (CONFIRMADO PAGAMENTO) OU NO ATENDIMENTO 
                
                
                // 1.1 0 convenio q n gera repasse
                // EDITA A TABELA DE PROCEDIMENTOS DO AGENDAMENTO
                $data_procedimento['producao_medica']       = 1;
                $this->cron_model->edit_atendimento_procedimento($data_procedimento, $atendimento_item_id);
                
            }
                
                
            
            
            
            //echo $atendimento_id.'<br>';
            
            //exit;
            
           // $this->cron_model->edit_procedimento($data_ajuste, $item_id);
        }
    }
    
    
    
    /*
     * ATUALIZA FATURAMENTO E PRODUÇÃO COM PENDENCIA DE PARAMETRIZAÇÃO DE REPASSE
     * VERIFICA SE JA TEM REPASSE CADASTRADO E ATUALIZA
     */
    public function atualiza_valor_repasse_medico_producao_faturamento(){
        $this->load->model('cron_model');
        $this->load->model('Invoices_model');
        
        
        $procedimentos = $this->cron_model->get_procedimentos_sem_repasse_cadastrados();
        
        foreach ($procedimentos as $tabela) {
            $id_tab = $tabela['id'];
            $rel_id = $tabela['rel_id'];
            $medicoid = $tabela['medicoid'];
            $item_id = $tabela['item_id'];
            $rate_desconto = $tabela['rate_desconto'];
          
            // verifica se tem cadastrado esse procedimento para esse medico
            $tabela_procedimento_medico_row = $this->Invoices_model->get_medico_procedimento_invoice_item_conta_selecionada($item_id, $medicoid);
            $regra_procedimento = count($tabela_procedimento_medico_row); 
           
            if($regra_procedimento == 1){
                $valor_medico = $tabela_procedimento_medico_row->valor;
                $valor_empresa = $rate_desconto - $valor_medico;
                
                // verifica se tem na produção
                $valida_procedimento_producao = $this->cron_model->get_valida_procedimento_repasse($rel_id, $item_id);
                $t_valida = count($valida_procedimento_producao);
                if($t_valida == 1){
                   // echo $id_tab.'<Br>'; exit; 
                   // echo $valor_medico.'<br>';
                   // echo $rel_id.'<br>';
                   // echo $item_id.'<br>';
                    
                    $data_producao['valor_empresa'] = $valor_empresa;
                    $data_producao['valor_medico'] = $valor_medico;
                    $atualiza_producao = $this->cron_model->edit_repasse_medico_producao($data_producao, $rel_id, $item_id);
                   
                    if($atualiza_producao){
                      // echo 'tem';
                        
                        $data_fat['valor_tesouraria'] = $valor_empresa;
                        $data_fat['valor_medico'] = $valor_medico;
                        $atualiza_producao = $this->cron_model->edit_repasse_medico_faturamento($data_fat, $id_tab);
                        
                    }
                
                   
                }
                
                
                
            }
            
            
          
    
          
        }
    }
    
    
    
    /*
     * VERIFICA SE TEM UMA TABELA DE PREÇO PARA UM PROCEDIMENTO QUE DEVE SE INICIAR NESTE DIA, E ATUALIZA O PREÇO DO PROCEDIMENTO
     * 1 x ao dia
     */
    public function atualiza_tabela_de_emprestimos(){
        $this->load->model('cron_model');
        $tabelas_hoje = $this->cron_model->get_emprestimos_dia();
        
        foreach ($tabelas_hoje as $tabela) {
            $id                  = $tabela['id'];
           
            
            $data_ajuste['quitado']       = 1;
            $this->cron_model->edit_emprestimo($data_ajuste, $id);
            
            // se n tiver faz o insert
        }
    }
    
    
    /*
     * VERIFICA SE TEM UMA TABELA DE PREÇO PARA UM PROCEDIMENTO QUE DEVE SE INICIAR NESTE DIA, E ATUALIZA O PREÇO DO PROCEDIMENTO
     * 1 x ao dia
     */
    public function atualiza_status_faturas_abertas(){
        $this->load->model('cron_model');
        $qtde_dias = get_option('dias_para_cancelar_fatura_aberta');
        $tabelas_hoje = $this->cron_model->get_faturas_abertas($qtde_dias);
     
        foreach ($tabelas_hoje as $tabela) {
            $id                  = $tabela['id'];
           
            
            $data_ajuste['status'] = 5;
            $this->cron_model->edit_fatura($data_ajuste, $id);
            
            // se n tiver faz o insert
        }
    }
    
    
    
    
    /*
     * VERIFICA SE TEM UMA TABELA DE PREÇO PARA UM PROCEDIMENTO QUE DEVE SE INICIAR NESTE DIA, E ATUALIZA O PREÇO DO PROCEDIMENTO
     * 1 x ao dia
     */
    public function atualiza_integracao_dossie(){
        $this->load->model('cron_model');
        
        $this->db->where('rel_id', 313218);
        $this->db->where('rel_type', 'customer');
        $this->db->where('dossie', 0);
        $files = $this->db->get(db_prefix() . 'files')->result_array();
       
        $upload_path = get_upload_path_by_type('customer');
    
        foreach ($files as $file) {
            $id              = $file['id'];
            $rel_id          = $file['rel_id'];
            $file_name       = $file['file_name'];
            $filetype       = $file['filetype'];
            $hexadecimal       = $file['hexadecimal'];
            
            $arquivo = $upload_path . $rel_id . '/' .$file_name;
            $path = file_get_contents($arquivo);
            
            $data_hexadessimal = base64_encode($path); 
            echo $data_hexadessimal.'<br>';
            
          
           
            $data_file['hexadecimal'] = "$data_hexadessimal";
            $this->cron_model->edit_file_hexadecimal($data_file, $id);
            /*
           ?> 
            
            <img src="data:<?php echo $filetype ?>;base64, <?php echo $hexadecimal ?> ">;
          
         <?php   
             * 
             */
            // se n tiver faz o insert
        }
        echo 'aqui'; exit;
    }
    
    
    
    public function dossie_episodio_integracao($user_id = null){
        $posts = "{\r\n"
                . "\"userid\": \"$user_id\",\r\n    "
                . "\"description\": \"true\",\r\n    "  
                . "\"category\": \"Assinatura V-SOCIAL : $title\",\r\n    "
                . "\"start\": $valor_desconto,\r\n    "
                . "\"resourceid\": \"$data_vencimento\",\r\n    "
                . "\"resid\": 1\r\n " ;
        
       // $req .= "&$key=$value";
       // echo $req; exit;
         print_R($posts); exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.juno.com.br/charges",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "$posts",

            CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $access_token",
            "cache-control: no-cache",
            "content-type: application/json",
            "x-api-version: 2",
            "x-resource-token: 8D6C1041CA0B503E61BB852B09A832219013F004BD7B10D2D96F053E8AE04B69"
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
       /* if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }exit;*/
    }



    public function atualiza_pacientes(){
       
        $this->load->model('cron_model');
        $pacientes = $this->cron_model->get_pacienes_doctos();
      
        foreach($pacientes as $pac){
            $id_paciente = $pac['id'];

            $codigo_paciente = $pac['codigo_paciente'];
            $dados = $this->cron_model->get_dados_pacienes_doctos($codigo_paciente);
           
            $sexo = $dados->Sexo;

            $AnoNascimento = $dados->AnoNascimento;
            $MesDiaNascimento = $dados->MesDiaNascimento;
            $count_dia = strlen($MesDiaNascimento);
            if($count_dia == 3){
                $dia = substr($MesDiaNascimento,0,1);
                $dia = '0'.$dia;
                $mes = substr($MesDiaNascimento,1,2);
                
            }else if($count_dia == 4){
                $dia = substr($MesDiaNascimento,0,2);
                $dia = '0'.$dia;
                $mes = substr($MesDiaNascimento,2,2);
            }
            $data_nascimento =  $AnoNascimento.'-'.$mes.'-'.$dia. '<br>';
           
            $Endereco = $dados->Endereco;
            $Estado = $dados->Estado;
            $Cidade = $dados->Cidade;
            $CEP = $dados->CEP;
            $Fone = $dados->Fone;
            $Profissao = $dados->Profissao;
            $Pai = $dados->Pai;
            $Mae = $dados->Mae;
            $TemObs = $dados->TemObs;
            $FoneAdicional = $dados->FoneAdicional;


          //  $data_ajuste['sexo']                = "$sexo";
           // $data_ajuste['data_nascimento']     = $data_nascimento;
           // $data_ajuste['Endereco']            = $Endereco;
           // $data_ajuste['Estado']              = $Estado;
           // $data_ajuste['Cidade']              = $Cidade;
           // $data_ajuste['CEP']                 = $CEP;
           // $data_ajuste['Fone']                = $Fone;
            //$data_ajuste['FoneAdicional']       = $FoneAdicional;
          //  $data_ajuste['Profissao']           = $Profissao;
           // $data_ajuste['Pai']                 = $Pai;
           // $data_ajuste['Mae']                 = $Mae;
          //  $data_ajuste['TemObs']              = $TemObs;

            
            $this->cron_model->edit_dados_paciente($id_paciente, $sexo, $data_nascimento, $Endereco, $Estado, $Cidade, $CEP, $Fone, $FoneAdicional, $Profissao, $Pai, $Mae, $TemObs);

            echo $id_paciente.'-';
            echo $codigo_paciente.'<br>';

         //   print_r($data_ajuste); exit;

        }
    }
    
}
