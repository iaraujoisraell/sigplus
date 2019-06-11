<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller
{


        function __construct()
    {
        parent::__construct();
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
     
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        
        $this->load->library('ion_auth');
        
        $this->load->model('projetos_model');
        $this->load->model('atas_model');
        $this->load->model('user_model');
        $this->load->model('reports_model');
        $this->digital_upload_path = 'assets/uploads/projetos';
        $this->upload_path = 'assets/uploads/projetos';
        $this->thumbs_path = 'assets/uploads/thumbs/projetos';
         $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    
    
    function diasemana($data){  // Traz o dia da semana para qualquer data informada
        $dia =  substr($data,0,2);
        $mes =  substr($data,3,2);
        $ano =  substr($data,6,9);
        $diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
        switch($diasemana){  		
            case"0": $diasemana = "Domingo";break;  				
            case"1": $diasemana = "Segunda-Feira"; break;  	
            case"2": $diasemana = "Terça-Feira";   break;  		
            case"3": $diasemana = "Quarta-Feira";  break;  		
            case"4": $diasemana = "Quinta-Feira";  break;  		
            case"5": $diasemana = "Sexta-Feira";   break;  		
            case"6": $diasemana = "Sábado";	break;  	
            }
            return "$diasemana";
                        
        }
        
        
        
    public function rotina_envio()
    {
       $dia = date('d'); 
       $date_cadastro = date('Y-m-d');   
       $hora =  date('H:i:s');
       $data_hoje_tratada = date('d/m/Y',  strtotime($date_cadastro));//$this->sma->hrld($date_cadastro); 
       $dia_da_semana = $this->diasemana($data_hoje_tratada);


       /* 
        * *************************************************************************************************************************
        *  1 - ENVIA E-MAILS PENDENTES
        * 
        * A CADA 1 MINUTO
        */
       $emails_pendente = $this->reports_model->getAllEmailsPendentes();
       
       foreach ($emails_pendente as $email) {
           $email_id = $email->id;
           $user_de = $email->id_from;
           $user_para = $email->id_to;
           $titulo = $email->title;
           $texto = $email->text;
           $acao_id = $email->idplano;
           $convite = $email->convite;
           $enviado = $email->enviado;
           
           if($enviado == 0){
           
               if($acao_id > 0){
                   // QUANDO É EMAIL REFERENTE A AÇÃO: NOVA AÇÃO, RETORNO DE AÇÃO, ETC.
                   $this->ion_auth->enviaEmailComAcao($titulo, $texto, $user_para, $acao_id, $email_id);
                   $data_status_report = array(
                    'data_envio' => date('Y-m-d H:i:s'),
                    'enviado' => 1                       
                );
                 //UPDATE O STATUS DE ENVIADO
                $this->site->updateStatusEmailEnviado($email_id, $data_status_report);   
                   
               }
              

               if($convite == 1){
                   // QUANDO VEM DE CONVITE DE ATAS: NETWORKING OU PROJECT
                   $this->ion_auth->enviaEmailComAcao($titulo, $texto, $user_para, $acao_id);  
               }
           
           }
           
       }
           
           
            
            
     }
     
      public function rotinas()
    {
           $dia = date('d'); 
           $date_cadastro = date('Y-m-d');   
           $hora =  date('H:i:s');
           $data_hoje_tratada = date('d/m/Y',  strtotime($date_cadastro));//$this->sma->hrld($date_cadastro); 
           $dia_da_semana = $this->diasemana($data_hoje_tratada);
           
           
           /* 
            * *************************************************************************************************************************
            *  1 - GERA O STATUS_REPORT SEMANAL DOS PROJETOS
            * 
            * TODA QUARTA-FEIRA
            */
           if($dia_da_semana == "Quarta-Feira"){
                if (($hora >= '10:00:00') && ($hora < '10:05:00')) {
                //$this->enviaEmailControle('GEROU STATUS REPORT');
              // $this->enviar_gera_status_report();
            }
                 
           }
           
           /* 
            **************************************************************************************************************************
            * 
            * 2 - ENVIA EMAIL PARA OS USUÁRIOS FINAIS COM AÇÕES ATRASADAS
            */
           if ($dia_da_semana == "Segunda-Feira") {
            if (($hora >= '06:00:00') && ($hora < '06:01:00')) {
                $this->enviaEmailControle('FOI ENVIADO E-MAILS PARA OS USUÁRIOS DOS PROJETOS COM AÇÕES ATRASADAS');
                $this->enviaEmailAcoesAtrasadas();
                //$this->SalvaDesempenhoAcoesProjetos();
            }
            }
            
            /* 
            * *************************************************************************************************************************
            * 
            * 3 - ENVIA EMAIL PARA OS GESTORES QUE ESCTÃO VINCULADO AOS PROJETOS
            */
           if ($dia_da_semana == "Sexta-Feira") {
            if (($hora >= '18:00:00') && ($hora < '18:05:00')) {
                $this->enviaEmailControle('FOI ENVIADO EMAIL PARA OS GESTORES QUES TEM ALGUÉM DA SUA EQUIPE VINCULADO AOS PROJETOS');
                $this->enviaEmailGestores();
            }
           }
           
            /* 
            * *************************************************************************************************************************
            * 
            * 4 - ENVIA EMAIL PARA OS GESTORES QUE ESCTÃO VINCULADO AOS PROJETOS
            */
           if ($dia_da_semana == "Sexta-Feira") {
            if (($hora >= '12:00:00') && ($hora < '12:01:00')) {
                $this->enviaEmailControle('O SISTEMA SALVOU O DESEMPENHO DAS AÇÕES ESTA SEMANA');
                $this->SalvaDesempenhoAcoesProjetos();
            }
           }
           
           
           /*
            *  5 - ENVIA EMAIL TODO DIA PARA TODAS AS ATAS DE CONVOCAÇÃO ABERTAS QUE POSSUEM PARTICIPANTES QUE AINDA NÃO CONFIRMARAM PRESENÇA
            */
           if (($hora >= '08:00:00') && ($hora < '08:05:00')) {
            //    $this->enviaEmailControle('O SISTEMA VERIFICOU SE TEM ATA COM CONVOCAÇÃO ABERTA E REENVIOU O CONVITE DE CONVOCAÇÃO, PARA QUEM AINDA NÃO CONFIRMOU PRESENÇA');
                $this->reenviar_convocacao();
                
            }
            
            
            /*
             * 6 - ENVIA EMAIL PARA QUEM PARTICIPOU DO TREINAMENTO E AINDA NÃO RESPONDEU O FORMULÁRIO
             */   
             if (($hora >= '08:00:00') && ($hora < '08:05:00')) {
               // $this->enviaEmailControle('O SISTEMA REENVIOU FORMULÁRIO DE AVALIAÇÃO DE TREINAMENTO PARA QUEM AINDA NÃO PREENCHEU.');
               // $this->reenviar_avaliacao_treinamento();
            }
           /*
            * 7 - ATUALIZA AS ORDEM DE SERVIÇO DO GLPI NO SIG
            */
            if (($hora >= '00:00:00') && ($hora < '23:59:00')) {
                $this->atualizaOrdemServicos();
                $this->verificaOrdemServicoSobreaviso();
              //  $this->enviaEmailControle('O SISTEMA VERIFICOU SE EXISTE ALGUMA ORDEM DE SERVIÇO NOVA OU COM STATUS DIFERENTE NO GLPI E ATUALIZOU O SIG');   
            }
            
           /*
            * 8 - ENVIA EMAIL DE ALERTA DE TICKET SOLUCIONADO COM 8 DIAS E ATUALIZA O STATUS PARA FECHADO, OS TICKETS COM 10 DIAS OU +;
            */
            if (($hora >= '00:00:00') && ($hora < '00:00:03')) {
                $this->atualizaStatusOrdemServicos();
             //   $this->enviaEmailControle('O SISTEMA VERIFICOU AS AÇÕES SOLUCIONADAS A 8 DIAS E NOTIFICOU OS USUÁRIOS. VERIFICOU SE TEM OS SOLUCIONADA A 10 DIAS E ATUALIZOU O STATUS');   
            }
            
           //  $this->enviaEmailControle('Acessou o SIG');
            
           /*
            * 9 - CRIA O PERÍODO PARA O LANÇAMENTO DE HORA EXTA
            */ 
             if (($dia == 11) && ($hora < '00:00:05')) {
                $this->criaNovoPeriodoHoraExtra() ;
            }
            
           /*
            * ATUALIZA O PONTO DA TI
            */
              if (($hora >= '10:00:00') && ($hora < '10:01:00')) {
                $this->atualizaPontoTI();
              //  $this->enviaEmailControle('O SISTEMA VERIFICOU SE EXISTE ALGUMA ORDEM DE SERVIÇO NOVA OU COM STATUS DIFERENTE NO GLPI E ATUALIZOU O SIG');   
            }
            
            
     }
     
     
     /*
     **********************ELE ME ENVIA UM EMAIL TODA VEZ QUE O SERVIDOR DISPARA ALGUM EMAIL ************************************************************************************************* 
     */
    public function enviaEmailControle()
    {
      //  $this->sma->checkPermissions();
           
        
        $date_hoje = date('Y-m-d H:i:s');
        $date_2 = date('Y-m-d');
        
               // echo 'aqui'; exit;
      //  $this->ion_auth->emailControleServidor($date_hoje,"teste");
        
                   
    }
     
     
     /*
     ******************************STATUS REPORT ***************************************************************************************** 
     */

     public function status_report()
    {
        $this->sma->checkPermissions();
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        // $this->load->library("phpmailer_library");
        //$objMail = $this->phpmailer_library->load();
        
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        $this->data['status_report'] = $this->reports_model->getAllStatusReportByProjeto($projetos_usuario->projeto_atual);
        
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('reports')));
        $meta = array('page_title' => lang('reports'), 'bc' => $bc);
        $this->page_construct('reports/status_report/index', $meta, $this->data);

    }
     
    public function enviar_gera_status_report()
    {
       // $this->sma->checkPermissions();

           
           $date_cadastro = date('Y-m-d');               
        
           $usuario = $this->session->userdata('user_id');
           
           $data_hoje_tratada = date('d/m/Y',  strtotime($date_cadastro));//$this->sma->hrld($date_cadastro); 
           $dia_da_semana = $this->diasemana($data_hoje_tratada);
           
           
                /*
                 * FAZ O STATUS REPORT DE TODOS OS PROJETOS
                 */
                $allProjetos = $this->reports_model->getAllProjetos();
                foreach ($allProjetos as $projeto) {
                   
                
               /* 
                 * Verifica se tem registro no BD para a quarta atual
                 */
                $status_report_quarta = $this->reports_model->getAllStatusReportByDataProjeto($projeto->id, $date_cadastro); 
                $quantidade_report = $status_report_quarta->quantidade;
                //SE TIVER
                if($quantidade_report > 0){
                   
                    echo 'ja tem registro';
                    exit;
                }else{
                // SE NÃO FAZ O REGISTRO
                   
                    $dia =  substr($data_hoje_tratada,0,2);
                    $mes =  substr($data_hoje_tratada,3,2);
                    $ano =  substr($data_hoje_tratada,6,9);
                    
                     //$date_cadastro_com_hora = date('Y-m-d H:i:s');  
                    $data_de = date('d/m/Y', strtotime('-4 days', strtotime($dia.'-'.$mes.'-'.$ano)));
                    $data_ate = date('d/m/Y', strtotime('+2 days', strtotime($dia.'-'.$mes.'-'.$ano)));
                 
                    $data_de_bd = $this->sma->fld(trim($data_de.' 00:00:00'));   //ÚLTIMO SÁBADO
                    $data_ate_bd = $this->sma->fld(trim($data_ate.' 00:00:00')); // PRÓXIMA SEXTA-FEIRA
               
                    $usuario = $this->session->userdata('user_id');
                    //$projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                    
                    $data_status_report = array(
                        'periodo_de' => $data_de_bd,
                        'periodo_ate' => $data_ate_bd,
                        'projeto' => $projeto->id,
                        'autor' => $usuario,
                        'data_registro' => date('Y-m-d H:i:s'),
                        'prazo' => 'RISCOS GERENCIÁVEIS',
                        'custo' => 'RISCOS GERENCIÁVEIS',
                        'escopo' => 'RISCOS GERENCIÁVEIS',
                        'comunicacao' => 'RISCOS GERENCIÁVEIS'
                    );
                    
                     //INSERT STATUS REPORT
                    $id_status_report = $this->reports_model->addStatusReport($data_status_report);
                  //print_r($data_status_report);exit;
                    
                    /*
                     * PEGA TODOS OS EVENTOS ATÉ O PERÍODO SELECIONADO
                     */
                    $eventos_Status = $this->reports_model->getAllEventosStatusReport($projeto->id,$data_ate_bd);
                    foreach ($eventos_Status as $evento_status) {
                        
                   /*
                    * AÇÃO PENDENTE PARA ESTE EVENTO, PRA DEFINIR O STATUS DO EVENTO
                    */
                        $acao_evento = $this->reports_model->getAcaoPendenteByEventoID($evento_status->id);
                        $acao_pendente_evento = $acao_evento->quantidade;
                        
                        if($acao_pendente_evento > 0){
                            $status_evento = 'PENDENTE';
                        }else{
                            $status_evento = 'CONCLUÍDO';
                        }
                        
                        $data_status_evento = array(
                        'status' => $status_evento,
                        'evento' => $evento_status->id,
                        'status_report' => $id_status_report
                    );
                        
                       
                        /*
                         * EVENTOS - STATUS REPORT
                         */
                      $evento_id = $this->reports_model->addEventoStatusReport($data_status_evento); 
                        
                        
                     /*
                     * AÇÕES CONCLUÍDAS DOS EVENTOS DO PERÍODO SELECIONADO
                     */
               
                    $acoes_concluidas = $this->reports_model->getAllAcoesByEvento($evento_status->id,$data_ate_bd);
                    
                    
                    foreach ($acoes_concluidas as $acao_concluida) {
                        $data_status_acao = array(
                        'status' => 1,
                        'acao' => $acao_concluida->idplanos,
                        'evento' => $evento_id,
                    );
                        
                    /*
                     *   INSERT AÇÕES CONCLUÍDAS DO STATUS REPORT
                     */
                        
                       $this->reports_model->addAcoesStatusReport($data_status_acao);    
                    }  

                    
                    /*
                     * AÇÕES PENDENTES DOS EVENTOS DO PERÍODO SELECIONADO
                     */
               
                    $acoes_pendente = $this->reports_model->getAllAcoesPendenteByEvento($evento_status->id,$data_ate_bd);
                   
                    foreach ($acoes_pendente as $acao_pendente) {
                        $data_status_acao_pendente = array(
                        'status' => 0,
                        'acao' => $acao_pendente->idplanos,
                        'evento' => $evento_id,
                    );
                        
                    /*
                     *   INSERT AÇÕES PENDENTES DO STATUS REPORT
                     */
                       $this->reports_model->addAcoesStatusReport($data_status_acao_pendente);    
                    }  

           
                    }
                    
                
                }
                
               // echo 'ok projeto : '.$projeto->id;
                /*
                 * Tratar Envio de Email para cada gerente de projeto
                 */
               
              }
              
       
            //  $this->ion_auth->emailAvisoGerouStatuRepor();
             
             //redirect("Reports/status_report");
              
          
           
          
            
            
        
    }  
    

    public function edit_status_report($id = null)
    {
        $this->sma->checkPermissions();

        $this->form_validation->set_rules('escopo', lang("Escopo"), 'required'); 
        $this->form_validation->set_rules('prazo', lang("Prazo"), 'required');
        $this->form_validation->set_rules('custo', lang("Custo"), 'required');
        $this->form_validation->set_rules('comunicacao', lang("Comunicação"), 'required');
        
        $date_cadastro = date('Y-m-d H:i:s');               
        
        if ($this->form_validation->run() == true) {
           
            $id = $this->input->post('id');
            
            $prazo = $this->input->post('prazo');
            $custo = $this->input->post('custo');
            $escopo = $this->input->post('escopo');
            $comunicacao = $this->input->post('comunicacao');
            
            $comentarios_indicadores = $this->input->post('comentarios_indicadores');
            $observacoes_acoes = $this->input->post('observacoes_acoes');
            $observacoes_adicionais = $this->input->post('observacoes_adicionais');
            
            $data_pergunta = array(
                'prazo' => $prazo,
                'custo' => $custo,
                'escopo' => $escopo,
                'comunicacao' => $comunicacao,
                'comentarios_indicadores' => $comunicacao,
                'observacoes_acoes' => $observacoes_acoes,
                'observacoes_adicionais' => $observacoes_adicionais 
               
            );
             
            print_r($data_pergunta);exit;
            //$this->atas_model->addPerguntaPesquisaSatisfacao($data_pergunta);
            $this->session->set_flashdata('message', lang("Pergunta Criada com Sucesso!!!"));
            
            redirect("Cadastros/pesquisa_satisfacao_add_pergunta/".$id);
            
        } else {

             $this->data['id'] = $id;
           
            
            if($id){
            
            $this->data['status_report'] = $this->reports_model->getStatusReportByID($id);
            $this->data['grupo_perguntas'] = $this->atas_model->getGrupoByIDPesquisa($id);
            $this->data['perguntas'] = $this->atas_model->getAllPerguntas($id);
            
            $bc = array(array('link' => base_url(), 'page' => lang('Status Report')), array('link' => '#', 'page' => lang('Editar')));
            $meta = array('page_title' => lang('Editar Status Report'), 'bc' => $bc);
              $this->page_construct('reports/status_report/edit', $meta, $this->data);
              
            }else{
                redirect("Reports/add_status_report");
            }
            
      
        }
    }  
    
    
    
    
    
    /*
     **********************USUÁRIOS COM AÇÕES ATRASADAS - TODOS OS PROJETO ************************************************************************************************* 
     */
    public function enviaEmailAcoesAtrasadas()
    {
      //  $this->sma->checkPermissions();
           
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        
         $hora =  date('H:i:s');
         
         //echo $hora;exit;
                /*
                 * FAZ O STATUS REPORT DE TODOS OS PROJETOS
                 */
                $allProjetos = $this->reports_model->getAllProjetos();
                foreach ($allProjetos as $projeto) {
                   
        
                    $usuarios = $this->projetos_model->usuariosComAcoesAtrasadas($projeto->id);
                    $date_hoje = date('Y-m-d H:i:s');
                    $date_2 = date('Y-m-d');

                    $data_atualizacao = array('ultimo_aviso_email' => $date_hoje);

                    foreach ($usuarios as $usuario) {
                           $id = $usuario->responsavel; 
                           $dt_ultimo_aviso_email = $usuario->ultimo_aviso_email;
                           $t_data  =  substr("$dt_ultimo_aviso_email", 0, 10);
                           if($t_data != $date_2){
                             $this->ion_auth->emailUsuarioAcoesAtrasadas($id,$projeto->id, $projeto->projeto);
                           //  $this->projetos_model->updateDataNotificacaoUsuario($id,$data_atualizacao);
                           }

                       }
          
                }
            
     //   $this->session->set_flashdata('message', lang("Emails Enviados com Sucesso!!!"));   
      //  redirect("Historico_Acoes/usuariosComAcoesAtrasadas");
        
                   
    }
        
    /*
     **********************GESTORES QUE ESTÃO LIGADOS AO PROJETO  - TODOS OS PROJETO ************************************************************************************************* 
     */
    public function enviaEmailGestores()
    {
      //  $this->sma->checkPermissions();
        
        //TODOS OS PROJETOS
         $allProjetos = $this->reports_model->getAllProjetosComGestores();
         //print_r($allProjetos); exit;
                foreach ($allProjetos as $projeto) {
                  
                   
                  //GESTORES QUE ESTÃO NO PROJETO
                    $gestores = $this->reports_model->getListGestoresProjetos($projeto->id);
                      
                       
                     foreach ($gestores as $gestor) {
                    
                       //ENVIA EMAIL PARA CADA GESTOR POR PROJETO
                      
                       
                      // print_r($gestores);
                      // echo '<br>';
                       $usuarios = $gestor->user_id;
                       // echo 'idi usu :'.$usuarios.'<br>'.$projeto->id.'<br>';
                        $this->ion_auth->emailAcosGestores($usuarios, $projeto->id, 2);
                       
                   }
                    
                }
        
     
        
                   
    }
    
    /*
     **********************GRAVA O DESEMPENHO DAS AÇÕES POR PROJETO - TODA SEXTA ************************************************************************************************* 
     */
        function geraTimestamp($data) {
    $partes = explode('/', $data);
    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
    }

    public function salvaDesempenhoAcoesProjetos()
    {
      //  $this->sma->checkPermissions();
       
        //TODOS OS PROJETOS
         $allProjetos = $this->reports_model->getAllProjetos();
         //print_r($allProjetos); exit;
         $dataEscolhida = date('Y-m-d H:i:s');   //
         
         
                foreach ($allProjetos as $projeto) {
                  
                   
                  //GESTORES QUE ESTÃO NO PROJETO
                    
                      
                     
                        $somma_acoes_pendentes = 0;
                        $somma_acoes_atrasadas_5 = 0;
                        $somma_acoes_concluidas = 0;
                        $somma_acoes_concluidas_fora_prazo = 0;
                        $somma_acoes_atrasadas_10 = 0;
                        $somma_total_acal = 0;
                        $somma_acoes_atrasadas_15 = 0;
                        $tota_atrasadas_total = 0;
                        
                        echo $projeto->id.'<br>';
                        
                        $setores = $this->atas_model->getAllSetor();
                        foreach ($setores as $setor) {
                            
                            $setor_selecionado = $setor->setor_id;
                            
                            $acoes_setor = $this->atas_model->getAllitemPlanosProjetoSetor($projeto->id,$setor_selecionado);
                            
                            $somma_acoes_pendentes_setor = 0;
                            $somma_acoes_atrasadas_5_setor = 0;
                            $somma_acoes_concluidas_setor = 0;
                            $somma_acoes_concluidas_fora_prazo_setor = 0;
                            $somma_acoes_atrasadas_10_setor = 0;
                            $somma_total_acal_setor = 0;
                            $somma_acoes_atrasadas_15_setor = 0;
                        
                       
                        
                            foreach ($acoes_setor as $a_setor) {
                                                       
                                $adata_prazo = $a_setor->data_termino;
                                $adata_entrega = $a_setor->data_retorno_usuario;
                                $astatus = $a_setor->status;
                              
                                if($astatus == 'CONCLUÍDO'){

                            
                                    if($adata_entrega <= $adata_prazo){
                                        $somma_acoes_concluidas_setor +=1;
                                        
                                        $somma_acoes_concluidas += 1;
                                    }

                                    if($adata_entrega > $adata_prazo){
                                         $somma_acoes_concluidas_fora_prazo_setor +=1;
                                         $somma_acoes_concluidas_fora_prazo+=1;
                                    }

                                }else if(($astatus == 'PENDENTE')||($astatus == 'AGUARDANDO VALIDAÇÃO')){

                                    if($dataEscolhida <= $adata_prazo){
                                        $somma_acoes_pendentes_setor += 1;
                                        $somma_acoes_pendentes += 1;
                                    }
                                    
                                    if($dataEscolhida > $adata_prazo){
                                        
                                            $novo_status_setor = 'ATRASADO';

                                            // Usa a função criada e pega o timestamp das duas datas:
                                            $time_inicial_setor = $this->geraTimestamp($this->sma->hrld($dataEscolhida));
                                            $time_final_setor = $this->geraTimestamp($this->sma->hrld($adata_prazo));
                                           
                                            
                                            
                                            // Calcula a diferença de segundos entre as duas datas:
                                            $diferenca_setor = $time_final_setor - $time_inicial_setor; // 19522800 segundos
                                            // Calcula a diferença de dias
                                            $dias_setor = (int)floor( $diferenca_setor / (60 * 60 * 24)); // 225 dias



                                             if($dias_setor >= '-5'){
                                                $somma_acoes_atrasadas_5_setor +=1;
                                                $somma_acoes_atrasadas_5 += 1;
                                            }else if(($dias_setor < '-5') && ($dias_setor >= '-10')){
                                               $somma_acoes_atrasadas_10_setor +=1;
                                               $somma_acoes_atrasadas_10 += 1;
                                            }else if($dias_setor < '-10') {
                                                $somma_acoes_atrasadas_15_setor +=1;
                                                $somma_acoes_atrasadas_15 += 1;
                                            }

                                        }
                                }
                                                    
                                 $somma_total_acal_setor+=1;    
                                 
                                
                         } //fim for $acoes_setor
                         
                         $tota_atrasadas_setor = $somma_acoes_atrasadas_5_setor + $somma_acoes_atrasadas_10_setor + $somma_acoes_atrasadas_15_setor;
                         
                         //aqui os inserts
                         if($somma_total_acal_setor > 0){
                         $data_historico_setor = array(
                            'data' => $dataEscolhida,
                            'setor' => $setor_selecionado, 
                            'projeto' => $projeto->id,
                            'superintendente' => $setor->superintendencia,
                            'total_acoes' => $somma_total_acal_setor,
                            'total_atrasados' => $tota_atrasadas_setor,
                            'total_concluido' => $somma_acoes_concluidas_setor,
                            'total_fora_prazo' => $somma_acoes_concluidas_fora_prazo_setor,
                            'total_pendentes' => $somma_acoes_pendentes_setor,
                            'atrasado_5' => $somma_acoes_atrasadas_5_setor,
                            'atrasado_10' => $somma_acoes_atrasadas_10_setor,
                            'atrasado_15' => $somma_acoes_atrasadas_15_setor
                        );
                        
                       $this->atas_model->add_Historico_Acoes($data_historico_setor);
                         }
                         /*
                         
                         $this->atas_model->add_Historico_Acoes($data_historico_concluido_fp);
                         $this->atas_model->add_Historico_Acoes($data_historico_pendente);
                         $this->atas_model->add_Historico_Acoes($data_historico_atrasado_5);
                         $this->atas_model->add_Historico_Acoes($data_historico_atrasado_10);
                         $this->atas_model->add_Historico_Acoes($data_historico_atrasado_15);
                         
                          * 
                          */
                        
                     } //fim for $setores
                        
                     $tota_atrasadas_total = $somma_acoes_atrasadas_5 + $somma_acoes_atrasadas_10 + $somma_acoes_atrasadas_15;
                     //aqui os inserts
                         $data_historico_total_resumo = array(
                            'data' => $dataEscolhida,
                            'setor' => 'TODOS', 
                            'resumo' => '1', 
                            'projeto' => $projeto->id,
                            'superintendente' => 'TODOS',
                            'total_acoes' => $somma_total_acal,
                            'total_atrasados' => $tota_atrasadas_total,
                            'total_concluido' => $somma_acoes_concluidas,
                            'total_fora_prazo' => $somma_acoes_concluidas_fora_prazo,
                            'total_pendentes' => $somma_acoes_pendentes,
                            'atrasado_5' => $somma_acoes_atrasadas_5,
                            'atrasado_10' => $somma_acoes_atrasadas_10,
                            'atrasado_15' => $somma_acoes_atrasadas_15
                        );
                         
                         $this->atas_model->add_Historico_Acoes($data_historico_total_resumo);
                   
                    
                 
                    
                }
        
     
        
                   
    }
    /***************************FIM DESEMPENHO DAS AÇÕES**************************************************************************************/
    
    
    /*
     **********************EMAIL PARA OS USUÁRIOS COM AÇÕES ATRASADAS - TODA SEGUNDA-FEIRA 6H ************************************************************************************************* 
     */
     public function usuariosComAcoesAtrasadas()
    {
        $this->sma->checkPermissions();
           
        $usuario = $this->session->userdata('user_id');
        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        
        $this->data['usuarios'] = $this->projetos_model->usuariosComAcoesAtrasadas($projetos_usuario->projeto_atual);
           
        $bc = array(array('link' => base_url(), 'page' => lang('Histórico de ações')), array('link' => '#', 'page' => lang('Usuários com Ações Atrasadas')));
        $meta = array('page_title' => lang('Novo Registro'), 'bc' => $bc);
        $this->page_construct('reports/UsuariosAcoesAtrasadas/usuariosComAcoesAtrasadas', $meta, $this->data);
                   
    }
   
    /*
     **********************LINK PARA IMPRIMIR O DASHBOARD ************************************************************************************************* 
     */
    public function dashboard_pdf($projeto = null, $usuario = null, $perfil, $view = 1)
    {
        
        
        
        //  $this->sma->checkPermissions();
      
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        /*
         * VERIFICA O PERFIL DO USUÁRIO
         */
       $id_perfil_atual = $perfil;
        
       $id = $projeto;
        /*
         * VERIFICA O PERFIL DO USUÁRIO
         */
        
         
        $id_perfil_atual = $perfil;
        
        
        $this->data['usuario'] =  $usuario;
        $this->data['projetos'] =  $this->reports_model->getProjetoByID($projeto);
        
        $this->data['perfil_atual'] =  $id_perfil_atual;
        
        $this->data['usuario'] =  $usuario;
        $this->data['projetos'] =  $this->site->getProjetoAtualByID_completo($usuario);
        
        /*
         * CONSULTAS PARA TODOS OS PERFIS
         */
        //Qtde de Atas DO PROJETO. SERVE PARA TODOS OS PERFIS
        $this->data['ata'] =  $this->projetos_model->getAtaByProjeto($id);
        /*
         * EVENTOS - TIMELINE
         */
        $this->data['eventos']=$this->projetos_model->getAllEventosProjeto($id,'data_inicio','asc');
        /*
         * GRÁFICO PIE - PRESIDENCIA
         */
        $this->data['areas_projeto'] =  $this->projetos_model->getAreasByProjeto($id);
        //GRÁFICO PIE - total_acoes_areas
        $this->data['total_acoes_areas'] =  $this->projetos_model->getAcoesTodasSuperintendenciaByProjeto($id);
        /*
         * PROJETO
         */
        $this->data['projeto_selecionado'] = $id;
        
       
        
        
        /*
         * PERFIL EDP
         */
        if($id_perfil_atual == 1){
        //Qtde de pessoas na equipe
            $equipe = $this->projetos_model->getEquipeByProjeto($id);
         $this->data['equipe'] =  $equipe->responsavel;
       
         //Qtde de AÇÕES
         $total_acoes =  $this->projetos_model->getQtdeAcoesByProjeto($id);
        $this->data['total_acoes'] = $total_acoes->total_acoes;
        //Qtde de Ações concluídas
        $concluido = $this->projetos_model->getStatusAcoesByProjeto($id, 'CONCLUÍDO');
        $this->data['concluido'] =  $concluido->status;
        //Qtde de ações Pendentes
        $pendente = $this->projetos_model->getAcoesPendentesByProjeto($id, 'PENDENTE');
        $avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByProjeto($id, 'AGUARDANDO VALIDAÇÃO');
        $this->data['pendente'] =  $pendente->pendente + $avalidacao->avalidacao;
        $atrasadas = $this->projetos_model->getAcoesAtrasadasByProjeto($id, 'PENDENTE');
        //Qtde de Ações Atrasadas
        $this->data['atrasadas'] =  $atrasadas->atrasadas;
        
        /* 
         * PEGA AS ÁREAS QUE TEM AÇÕES
         */
        // SE FOR SUPERINTENDENTE
        $this->data['areas_usuario_projeto'] = $this->projetos_model->getAreasByProjeto($id);
        //SE FOR GESTOR
        
        
         //GRÁFICO AÇOES NA LINHA DO TEMPO
        $this->data['acoes_tempo'] =  $this->projetos_model->getAllitemStatusPlanosLinhaTempo($id);
        
        
        /*
         * PERFIL DE GESTOR
         */
        }else  if($id_perfil_atual == 2){
         /*
          * GESTOR
          */   
         $soma_qtde_equipe_superintendencia = 0;
         $soma_qtde_acoes_superintendencia = 0;
         $soma_qtde_acoes_concluidas_superintendencia = 0;
         $soma_qtde_acoes_pendentes_superintendencia = 0;
         $soma_qtde_acoes_avalidacao_superintendencia = 0;
         $soma_qtde_acoes_atrasadas_superintendencia = 0;
         $cont_acoes_tempo = 1;
         $user_superintendencias = $this->projetos_model->getSuperintenciaByUser($id_perfil_atual,$id,$usuario);
       
         foreach ($user_superintendencias as $user_superintendencia) {
         $id_superintendencia =  $user_superintendencia->setor;   
         
         /*
          * EQUIPE POR SETOR E PROJETO
          */
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SETOR E PROJETO
          */
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          */
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          */
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          */
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
        
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          */
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          */
         $id_superintendencia_data[$cont_acoes_tempo++] = $id_superintendencia;
         
        //$soma_qtde_acoes_tempo += $qtde_acoes_tempo;
         
        }
        
        //Qtde de pessoas na equipe
         $this->data['equipe'] =  $soma_qtde_equipe_superintendencia;
        //Qtde de Ações
        $this->data['total_acoes'] =  $soma_qtde_acoes_superintendencia;
        //Qtde de Ações concluídas
        $this->data['concluido'] =  $soma_qtde_acoes_concluidas_superintendencia;
        //Qtde de ações Pendentes
        $this->data['pendente'] =  ($soma_qtde_acoes_pendentes_superintendencia + $soma_qtde_acoes_avalidacao_superintendencia);
           //Qtde de Ações Atrasadas
        $this->data['atrasadas'] =  $soma_qtde_acoes_atrasadas_superintendencia;
        
         //GRÁFICO AÇOES NA LINHA DO TEMPO
       // print_r($id_superintendencia_data);exit;
        $qtde_acoes_tempo = $this->projetos_model->getAllitemPlanosLinhaTempoSuperintendencia($id_perfil_atual,$id,$id_superintendencia_data);
        $this->data['acoes_tempo'] =   $qtde_acoes_tempo;
        
        $this->data['areas_usuario_projeto'] =  $this->projetos_model->getSetoresByUsuarioProjeto($id,$usuario);
        
        
        
        
        /*
         * SUPERINTENDENTE
         */
        } if($id_perfil_atual == 3){
        
       /*
        * SUPERINTENDENCIAS LIGADA AO USUÁRIO
        */
         
         $soma_qtde_equipe_superintendencia = 0;
         $soma_qtde_acoes_superintendencia = 0;
         $soma_qtde_acoes_concluidas_superintendencia = 0;
         $soma_qtde_acoes_pendentes_superintendencia = 0;
         $soma_qtde_acoes_avalidacao_superintendencia = 0;
         $soma_qtde_acoes_atrasadas_superintendencia = 0;
         $cont_acoes_tempo = 1;
         $user_superintendencias = $this->projetos_model->getSuperintenciaByUser($id_perfil_atual,$id,$usuario);
         
         foreach ($user_superintendencias as $user_superintendencia) {
         $id_superintendencia =  $user_superintendencia->superintendencia;   
         
         /*
          * EQUIPE POR SUPERINTENDENCIA E PROJETO
          */
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SUPERINTENDENCIA E PROJETO
          */
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          */
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          */
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          */
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          */
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          */
         $id_superintendencia_data[$cont_acoes_tempo++] = $id_superintendencia;
         
        //$soma_qtde_acoes_tempo += $qtde_acoes_tempo;
         
        }
        
        //Qtde de pessoas na equipe
         $this->data['equipe'] =  $soma_qtde_equipe_superintendencia;
        //Qtde de Ações
        $this->data['total_acoes'] =  $soma_qtde_acoes_superintendencia;
        //Qtde de Ações concluídas
        $this->data['concluido'] =  $soma_qtde_acoes_concluidas_superintendencia;
        //Qtde de ações Pendentes
        $this->data['pendente'] =  ($soma_qtde_acoes_pendentes_superintendencia + $soma_qtde_acoes_avalidacao_superintendencia);
           //Qtde de Ações Atrasadas
        $this->data['atrasadas'] =  $soma_qtde_acoes_atrasadas_superintendencia;
        
         //GRÁFICO AÇOES NA LINHA DO TEMPO
       // print_r($id_superintendencia_data);exit;
        $qtde_acoes_tempo = $this->projetos_model->getAllitemPlanosLinhaTempoSuperintendencia($id_perfil_atual,$id,$id_superintendencia_data);
        $this->data['acoes_tempo'] =   $qtde_acoes_tempo;
        
        $this->data['areas_usuario_projeto'] =  $this->projetos_model->getAreasByUsuarioProjeto($id,$usuario);
        
        
        }
        
        //data_atual
        $this->data['data_hoje'] = date('Y-m-d H:i:s');
        
       
            $name = lang("STATUS_REPORT") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'email_templates/dashboard_pdf', $this->data, true);

        if ($view) {
            $this->load->view($this->theme . 'email_templates/dashboard_pdf', $this->data);
           // redirect("Projetos/dashboard/".$id);
        } else{
            
            $this->sma->generate_pdf($html, $name, false, $this->session->userdata('user_id'));
        }
    }
    
    /*
     **********************EMAIL PARA OS GESTORES COM O DESEMPENHO DAS EQUIPES - TODA SEXTA-FEIRA 18H ************************************************************************************************* 
     */
    public function dashboard_projetos_gestor($projeto = null, $usuario = null, $perfil,  $view = 1)
    {
        
        //  $this->sma->checkPermissions();
      
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        $id = $projeto;
        /*
         * VERIFICA O PERFIL DO USUÁRIO
         */
        
         
        $id_perfil_atual = $perfil;
        
        
        $this->data['usuario'] =  $usuario;
        $this->data['projetos'] =  $this->reports_model->getProjetoByID($projeto);
        
        $this->data['perfil_atual'] =  $id_perfil_atual;
        /*
         * CONSULTAS PARA TODOS OS PERFIS
         */
        //Qtde de Atas DO PROJETO. SERVE PARA TODOS OS PERFIS
        $this->data['ata'] =  $this->projetos_model->getAtaByProjeto($id);
        /*
         * EVENTOS - TIMELINE
         */
        $this->data['eventos']=$this->projetos_model->getAllEventosProjeto($id,'data_inicio','asc');
        /*
         * GRÁFICO PIE - PRESIDENCIA
         */
        $this->data['areas_projeto'] =  $this->projetos_model->getAreasByProjeto($id);
        //GRÁFICO PIE - total_acoes_areas
        $this->data['total_acoes_areas'] =  $this->projetos_model->getAcoesTodasSuperintendenciaByProjeto($id);
        /*
         * PROJETO
         */
        $this->data['projeto_selecionado'] = $id;
        
       
        
         if($id_perfil_atual == 2){
         /*
          * GESTOR
          */   
         $soma_qtde_equipe_superintendencia = 0;
         $soma_qtde_acoes_superintendencia = 0;
         $soma_qtde_acoes_concluidas_superintendencia = 0;
         $soma_qtde_acoes_pendentes_superintendencia = 0;
         $soma_qtde_acoes_avalidacao_superintendencia = 0;
         $soma_qtde_acoes_atrasadas_superintendencia = 0;
         $cont_acoes_tempo = 1;
         $user_superintendencias = $this->projetos_model->getSuperintenciaByUser($id_perfil_atual,$id,$usuario);
       
         foreach ($user_superintendencias as $user_superintendencia) {
         $id_superintendencia =  $user_superintendencia->setor;   
         
         /*
          * EQUIPE POR SETOR E PROJETO
          */
         $qtde_equipe_superintendencia = $this->projetos_model->getEquipeByProjetoSuperintendencia($id_perfil_atual,$id, $id_superintendencia);
         $soma_qtde_equipe_superintendencia += $qtde_equipe_superintendencia->responsavel; 
         /*
          * QUANTIDADE DE AÇÕES POR SETOR E PROJETO
          */
         $quantidade_acoes_superintendencia = $this->projetos_model->getQtdeAcoesByProjetoSuperintendencia($id_perfil_atual,$id,$id_superintendencia);
         $soma_qtde_acoes_superintendencia += $quantidade_acoes_superintendencia->total_acoes;
         /*
          * QUANTIDADE DE AÇÕES CONCLUÍDAS
          */
         $qtde_acoes_concluida_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'CONCLUÍDO',$id_superintendencia);
         $soma_qtde_acoes_concluidas_superintendencia += $qtde_acoes_concluida_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES PENDENTES
          */
         $qtde_acoes_pendentes_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'PENDENTE',$id_superintendencia);
         $soma_qtde_acoes_pendentes_superintendencia += $qtde_acoes_pendentes_superintendencia->quantidade;
         /*
          * QUANTIDADE DE AÇÕES AGUARDANDO VALIDAÇÃO
          */
         $qtde_acoes_aguardando_validacao_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual, $id, 'AGUARDANDO VALIDAÇÃO',$id_superintendencia);
         $soma_qtde_acoes_avalidacao_superintendencia += $qtde_acoes_aguardando_validacao_superintendencia->quantidade;
        
         /*
          * QUANTIDADE DE AÇÕES ATRASADAS
          */
         $qtde_acoes_atrasadas_superintendencia = $this->projetos_model->getAcoesByProjetoSuperintendenciaStatus($id_perfil_atual,$id, 'ATRASADO',$id_superintendencia);
         $soma_qtde_acoes_atrasadas_superintendencia += $qtde_acoes_atrasadas_superintendencia->quantidade;
         /*
          * AÇÕES NA LINHA DO TEMPO
          */
         $id_superintendencia_data[$cont_acoes_tempo++] = $id_superintendencia;
         
        //$soma_qtde_acoes_tempo += $qtde_acoes_tempo;
         
        }
        
        //Qtde de pessoas na equipe
         $this->data['equipe'] =  $soma_qtde_equipe_superintendencia;
        //Qtde de Ações
        $this->data['total_acoes'] =  $soma_qtde_acoes_superintendencia;
        //Qtde de Ações concluídas
        $this->data['concluido'] =  $soma_qtde_acoes_concluidas_superintendencia;
        //Qtde de ações Pendentes
        $this->data['pendente'] =  ($soma_qtde_acoes_pendentes_superintendencia + $soma_qtde_acoes_avalidacao_superintendencia);
           //Qtde de Ações Atrasadas
        $this->data['atrasadas'] =  $soma_qtde_acoes_atrasadas_superintendencia;
        
         //GRÁFICO AÇOES NA LINHA DO TEMPO
       // print_r($id_superintendencia_data);exit;
        $qtde_acoes_tempo = $this->projetos_model->getAllitemPlanosLinhaTempoSuperintendencia($id_perfil_atual,$id,$id_superintendencia_data);
        $this->data['acoes_tempo'] =   $qtde_acoes_tempo;
        
        $this->data['areas_usuario_projeto'] =  $this->projetos_model->getSetoresByUsuarioProjeto($id,$usuario);
        
        
        
        
        
        } 
        
        //data_atual
        $this->data['data_hoje'] = date('Y-m-d H:i:s');
        
       
            $name = lang("STATUS_REPORT") . "_" . str_replace('/', '_', $id) . ".pdf";
            $html = $this->load->view($this->theme . 'email_templates/dashboard_gestor', $this->data, true);

        if ($view) {
            $this->load->view($this->theme . 'email_templates/dashboard_gestor', $this->data);
           // redirect("Projetos/dashboard/".$id);
        } else{
            
            $this->sma->generate_pdf($html, $name, false, $this->session->userdata('user_id'));
        }
    }
    
    
    
   /*
     **********************LINK PARA CONFIRMAR PRESENÇA NA REUNIÃO ************************************************************************************************* 
     */     
    
    public function linkConfirmaConvocacao($id_convocacao)
    {
        
        $date_hoje = date('Y-m-d H:i:s');
        $date_2 = date('Y-m-d');
        
        $convocado = $this->atas_model->getConvocadoByUsuarioAta($id_convocacao);
        
        if($convocado->status == 0){
            $data_historico_confirmacao = array(
                            'data_confirmacao' => $date_hoje,
                            'status' => 1
            );
            $this->atas_model->updateStatusConvocado($id_convocacao, $data_historico_confirmacao);
           
            //aqui é o envio de invite
             ?>
               <script>
                   alert('Sua presença foi confirmada com Sucesso!');
                window.close();
                </script>
        <?php
            
        }else{
           
            ?>
               <script>
                   alert('A resposta não pode ser alterada!');
                window.close();
                </script>
        <?php
        }
        
       
                   
    }
    
    /*
     **********************LINK PARA NÃO CONFIRMAR PRESENÇA NA REUNIÃO ************************************************************************************************* 
     */
    public function linkNaoConfirmaConvocacao($id_convocacao)
    {
      
     //  echo '<br>';
     //  echo $usuario_Descriptografado;
     //  echo '<br>';
        $date_hoje = date('Y-m-d H:i:s');
        $date_2 = date('Y-m-d');
        
        $convocado = $this->atas_model->getConvocadoByUsuarioAta($id_convocacao);
        
        if($convocado->status == 0){
            $data_historico_confirmacao = array(
                            'data_confirmacao' => $date_hoje,
                            'status' => 2
            );
            $this->atas_model->updateStatusConvocado($id_convocacao, $data_historico_confirmacao);
           
            /*
             * AQUI ENVIA EMAIL
             */
            
            ?>
               <script>
                   alert('Seu retorno foi registrado com Sucesso!');
                window.close();
                </script>
        <?php          
        }else{        
            ?>
               <script>
                   alert('A resposta não pode ser alterada!');
                window.close();
                </script>
        <?php
        }                
    }
    
    
     /*
     **********************REENVIO DE CONFIRMAÇÃO DE CONVITE DE CONVOCAÇÃO ************************************************************************************************* 
     */
     public function reenviar_convocacao()
    {
       // $this->sma->checkPermissions();
        
       // echo 'to aqui'; exit;
        $date_cadastro = date('Y-m-d H:i:s');  
        $atasConvocacao = $this->atas_model->getAtasComConvocacao();
        foreach ($atasConvocacao as $atas) {
            $id_ata = $atas->id;
           
            $participantes = $this->atas_model->listaConvocadosNaoConfirmados($id_ata);
            foreach ($participantes as $participante) {
                $id_participante = $participante->user_id;
                $id_hist_convocacao = $participante->id_hc;
                
                $data_historico_convocacao = array(
                                    'ata' => $id_ata,
                                    'usuario' => $id_participante,
                                    'data_convocacao' => $date_cadastro,
                                    'status' => 0
                );
              // echo $id_participante.'-'.$id_hist_convocacao.'<br>'; 
                $id_convocacao = $id_hist_convocacao;//$this->atas_model->addHistorico_convocacao($data_historico_convocacao);

                $this->ion_auth->emailAtaConvocacao($id_participante, $id_ata, $id_convocacao);
                $this->session->set_flashdata('message', lang("Convocação enviada com Sucesso!!!"));
                
            }
            exit;
            
        } 
       
    }
    
    
    
     /*
        **********************REENVIO DE FORMULÁRIO DE AVALIAÇÃO DE REAÇÃO DE TREINAMENTO ************************************************************************************************* 
     */
    
    function encrypt($str, $key)
        {
           
            for ($return = $str, $x = 0, $y = 0; $x < strlen($return); $x++)
            {
                $return{$x} = chr(ord($return{$x}) ^ ord($key{$y}));
                $y = ($y >= (strlen($key) - 1)) ? 0 : ++$y;
            }

            return $return;
        }
        
     public function reenviar_avaliacao_treinamento()
    {
       
          $allProjetos = $this->reports_model->getAllProjetos();
         //print_r($allProjetos); exit;
         $dataEscolhida = date('Y-m-d H:i:s');   //
         
                foreach ($allProjetos as $projeto) {
                 
                
                $dados_ata = $this->atas_model->getAllAtasByIdProjeto($projeto->id);
                 foreach ($dados_ata as $atas) {
                     
                 
                
                $id_ata = $atas->id;
                $tipo = $atas->tipo;
                $tipo_ava_reacao = $atas->avaliacao_reacao;
                
            
         
                     if($tipo == 'TREINAMENTO'){
                      
                         if($tipo_ava_reacao == 1){
                                
                            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($id_ata);
                           //print_r($participantes_cadastrados_ata); exit;
                                foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
                                    
                                    
                                    $avaliacao = $participante_cadastrados->avaliacao;
                                   //echo $avaliacao;
                                   
                                    if(!$avaliacao){
                                        //echo $participante_cadastrados->id.'<br>';
                                               $this->ion_auth->emailAvaliacaoReacaoTreinamento($participante_cadastrados->id, $participante_cadastrados->id_participante);
                              
                                         
                                    }
                                   
                                  
                                  
                                }
                               
                         }
                     }// FIM IF TIPO == TREINAMENTO
                     
                }//FIM FOR TODAS AS ATAS
                }// FIM FOR TODOS OS PROJETOS
        
    }
    
     
   
    
    
     
     
     
      
   
    
    public function enviaSMSSobreAviso($numero, $texto){
         
         
    ini_set('memory_limit','512M');
    ini_set('display_errors', true);
    error_reporting(-1);
    /**
     * Load autoload
     */
    require_once 'TWW/TWWAutoload.php';
    /**
     * TWW Informations
     */
    define('TWW_WSDL_URL','http://webservices2.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL');
    define('TWW_USER_LOGIN','UNIMEDAM');
    define('TWW_USER_PASSWORD','unimed@123');
    /**
     * Wsdl instanciation infos
     */
    $wsdl = array();
    $wsdl[TWWWsdlClass::WSDL_URL] = TWW_WSDL_URL;
    $wsdl[TWWWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
    $wsdl[TWWWsdlClass::WSDL_TRACE] = true;
    if(TWW_USER_LOGIN !== '')
            $wsdl[TWWWsdlClass::WSDL_LOGIN] = TWW_USER_LOGIN;
    if(TWW_USER_PASSWORD !== '')
            $wsdl[TWWWsdlClass::WSDL_PASSWD] = TWW_USER_PASSWORD;
    // etc....
    /**
     * Examples
     */


    /*****************************
     * Example for TWWServiceEnvia
     */
    $tWWServiceEnvia = new TWWServiceEnvia($wsdl);

    // sample call for TWWServiceEnvia::EnviaSMS()
    if($tWWServiceEnvia->EnviaSMS(new TWWStructEnviaSMS('UNIMEDAM','unimed@123','SIG','5592'.$numero, $texto))){
            echo '<pre>'; print_r($tWWServiceEnvia->getResult());
    }else{
            echo '<pre>'; print_r($tWWServiceEnvia->getLastError());
    }

     }
     
 

}
