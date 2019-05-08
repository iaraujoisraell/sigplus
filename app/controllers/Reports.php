<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller
{


        function __construct()
    {
        parent::__construct();
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
         $glpi = $this->load->database('glpi', TRUE);
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
        
        
        
     public function envia_report()
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
               // $this->desempenhoInfomedTasy();
            }
            
            
            
             if (($dia_da_semana != "Sábado")||($dia_da_semana == "Domingo")) {
            
            if (($hora >= '08:00:00') && ($hora < '08:05:00')) {
            //    $this->enviaEmailControle('O SISTEMA VERIFICOU SE TEM ATA COM CONVOCAÇÃO ABERTA E REENVIOU O CONVITE DE CONVOCAÇÃO, PARA QUEM AINDA NÃO CONFIRMOU PRESENÇA');
            //    $this->reenviar_convocacao();
                $this->desempenhoInfomedTasy();
            }
            
            if (($hora >= '10:00:00') && ($hora < '10:05:00')) {
            //    $this->enviaEmailControle('O SISTEMA VERIFICOU SE TEM ATA COM CONVOCAÇÃO ABERTA E REENVIOU O CONVITE DE CONVOCAÇÃO, PARA QUEM AINDA NÃO CONFIRMOU PRESENÇA');
            //    $this->reenviar_convocacao();
                $this->desempenhoInfomedTasy();
            }
            
            if (($hora >= '12:00:00') && ($hora < '12:05:00')) {
            //    $this->enviaEmailControle('O SISTEMA VERIFICOU SE TEM ATA COM CONVOCAÇÃO ABERTA E REENVIOU O CONVITE DE CONVOCAÇÃO, PARA QUEM AINDA NÃO CONFIRMOU PRESENÇA');
            //    $this->reenviar_convocacao();
                $this->desempenhoInfomedTasy();
            }
            
            if (($hora >= '14:00:00') && ($hora < '14:05:00')) {
            //    $this->enviaEmailControle('O SISTEMA VERIFICOU SE TEM ATA COM CONVOCAÇÃO ABERTA E REENVIOU O CONVITE DE CONVOCAÇÃO, PARA QUEM AINDA NÃO CONFIRMOU PRESENÇA');
            //    $this->reenviar_convocacao();
                $this->desempenhoInfomedTasy();
            }
            
            if (($hora >= '16:00:00') && ($hora < '16:05:00')) {
            //    $this->enviaEmailControle('O SISTEMA VERIFICOU SE TEM ATA COM CONVOCAÇÃO ABERTA E REENVIOU O CONVITE DE CONVOCAÇÃO, PARA QUEM AINDA NÃO CONFIRMOU PRESENÇA');
            //    $this->reenviar_convocacao();
                $this->desempenhoInfomedTasy();
            }
            
            if (($hora >= '18:00:00') && ($hora < '18:05:00')) {
            //    $this->enviaEmailControle('O SISTEMA VERIFICOU SE TEM ATA COM CONVOCAÇÃO ABERTA E REENVIOU O CONVITE DE CONVOCAÇÃO, PARA QUEM AINDA NÃO CONFIRMOU PRESENÇA');
            //    $this->reenviar_convocacao();
                $this->desempenhoInfomedTasy();
            }
            
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
     ******************************STATUS REPORT ***************************************************************************************** 
     */

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
     **********************ELE ME ENVIA UM EMAIL TODA VEZ QUE O SERVIDOR DISPARA ALGUM EMAIL ************************************************************************************************* 
     */
    public function enviaEmailControle($texto)
    {
        $this->sma->checkPermissions();
           
        
        $date_hoje = date('Y-m-d H:i:s');
        $date_2 = date('Y-m-d');
        
        $this->ion_auth->emailControleServidor($date_hoje,$texto);
        
                   
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

    public function desempenhoInfomedTasy(){
       
        include './conexao_stage.php';
        $query = "select tasy,
        infomed,
       (tasy - infomed ) as diferenca
        from (select (select count(*) from pls_segurado@tasy_prod s
        where s.dt_rescisao is null) as tasy,
              (select count(*) from inf_beneficiarios@infomed b
        where b.ben_status = 'A' ) as INFOMED
        from dual)";
        $result_migracao = oci_parse($ora_conexao,$query);
        oci_execute($result_migracao, OCI_NO_AUTO_COMMIT);
        while (($row_q = oci_fetch_array($result_migracao, OCI_BOTH)) != false)
        {
            $infomed = $row_q[1];  
            $tasy = $row_q[0];
            $diferenca = $row_q[2];
        
          //  echo 'Infomed : '.$infomed;
        } 
        
        $data_registro = array(
            'data_registro' => date('Y-m-d H:i:s'),
            'infomed' => $infomed, 
            'tasy' => $tasy
        );
      //  print_r($data_registro); exit;
       $this->atas_model->add_Registro_Migracao($data_registro);
        
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
    
     
   /*
     **********************ATUALIZA AS ORDEM DE SERVIÇO NO SIG. Verifica no GLPI e atualiza o SIG****************************************************************** 
     */     
    
    public function verificaOrdemServicoSobreaviso()
    {
     // $date_hoje = date('Y-m-d H:i:s');
     // $date_2 = date('Y-m-d');
     
        //GLPI
        $tickets = $this->reports_model->getAllOrdemServicosSemAceite();
        $ont_dif = 0;
        $cont = 0;
       // print_r($tickets);exit;
        foreach ($tickets as $ticket) {
           $id_ticket = $ticket->id;
           $acao = $ticket->name;
           $data = $ticket->date;
           $data_conclusao = $ticket->closedate;
           $status = $ticket->status;
           $obs = $ticket->content;
           $categoria = $ticket->itilcategories_id;
          
          
           // ticket users
           $user_id = $ticket->users_id;
           $type = $ticket->type;
           
          
             
           if($categoria == 73){
            // SOBREAVISO TÉCNICO
         //      echo ' SUPORTE - SOBREAVISO TÉCNICO: '.$categoria.'<br>'; 
               $sobreavisos = $this->reports_model->getListSobreaviso(1);
                if($sobreavisos){
                    foreach ($sobreavisos as $sobreaviso) {
                        $id = $sobreaviso->id;
                        
                        $user_id = $sobreaviso->usuario;
                        $users = $this->site->geUserByID($user_id);
                       
                        $telefone = $users->phone;
                        $email = $users->email;
                        
                        
                         $texto = "ALERTA DE CHAMADO DE SOBREAVISO - ID : $id_ticket."
                            . " $acao."
                            . " OBS: Você recebeu um chamado via helpdesk. Acessar o GLPI para mais detalhes";
                    
                            $this->enviaSMSSobreAviso('993200610', $texto); //ALICE
                          //  $this->enviaSMSSobreAviso('984068481', $texto); // GABRIEL
                            $this->enviaSMSSobreAviso('984011675', $texto); // GABRIEL
                            $this->enviaSMSSobreAviso('991553632', $texto); //ISRAEL
                         //   $this->enviaSMSSobreAviso($telefone, $texto); // SOBREAVISO
                            
                            $email = 'israel.araujo@unimedmanaus.coop.br';
                            $email = 'israel.araujo@unimedmanaus.coop.br';
                            $email = 'israel.araujo@unimedmanaus.coop.br';
                            $email = 'israel.araujo@unimedmanaus.coop.br';
                            
                            
                            $this->ion_auth->emailControleSobreaviso($email,$texto, $acao, $obs, "SUPORTE TÉCNICO");
                    
                    }
                }else{
                 
                    $texto = "CHAMADO DE SOBREAVISO Fora do horário : Id Ticket : $id_ticket (SUPORTE - SOBREAVISO TÉCNICO)."
                            . " $acao.";
                //    echo $texto; exit;
                    $this->enviaSMSSobreAviso('993200610', $texto); //ALICE
                  //  $this->enviaSMSSobreAviso('984068481', $texto); // GABRIEL
                    $this->enviaSMSSobreAviso('991553632', $texto);
                    $this->enviaSMSSobreAviso('994122562', $texto);// ANDRÉ
                    $email = 'israel.araujo@unimedmanaus.coop.br';
                    $email2 = 'gabriel.rando@unimedmanaus.coop.br';
                    $email3 = 'alice.silva@unimedmanaus.coop.br';
                    $email4 = 'andre.dasilva@unimedmanaus.coop.br';
                            
                    $this->ion_auth->emailControleSobreaviso($email,$texto, $acao, $obs);
                 //   $this->ion_auth->emailControleSobreaviso($email2,$texto, $acao, $obs);
                    $this->ion_auth->emailControleSobreaviso($email3,$texto, $acao, $obs);
                    $this->ion_auth->emailControleSobreaviso($email4,$texto, $acao, $obs);
                    
                    
                }
              
               
           }else if($categoria == 80){
            // SOBREAVISO INFRAESTRUTURA
               echo ' SUPORTE - SOBREAVISO INFRA: '.$categoria; exit;
               $sobreavisos = $this->reports_model->getListSobreaviso(1);
                if($sobreavisos){
                    foreach ($sobreavisos as $sobreaviso) {
                        $id = $sobreaviso->id;
                        $user_id = $sobreaviso->usuario;

                        echo $user_id;
                    }
                }else{
                    echo 'não encontrou ou fora do horário.';
                }
           }else if($categoria == 75){
            //   IMPRESSORA HUPL
                  
           }else if($categoria == 76){
            //   IMPRESSORA HUPL
                  
           }else if($categoria == 77){
            //   IMPRESSORA NA CENTRAL DE SERVIÇO
                  
           }else if($categoria == 78){
            //   IMPRESSORA NO PRÉD. OLAVO BILAC
                  
           }else if($categoria == 79){
            //   IMPRESSORA NA UNICLIN
                  
           }
           
          /*
           
           //CONSULTA SE EXISTE UMA AÇÃO REFERENTE AO TICKET
           $plano = $this->reports_model->getPlanoByIdTicket($id, $id_user_sig);
           
           
           if($plano){
              //SE JÁ EXISTIR NO SIG, VERIFICA O STATUS, item_evento E O RESPONSÁVEL, CASO SEJAM DIFERENTES, ATUALIZA NO SIG
               $id_plano = $plano->idplanos;
               $evento = $plano->eventos;
               $status_sig = $plano->status;
               $responsvel = $plano->responsavel;
               
              
               
               //VERIFICA SE MUDOU O STATUS
                if($status_sig == "PENDENTE"){
                   $status_sig_id = 3;
               }else if($status_sig == "CANCELADO"){
                   $status_sig_id == 4;
               }else if($status_sig == "AGUARDANDO VALIDAÇÃO"){
                   $status_sig_id = 5;
               }else if($status_sig == "CONCLUÍDO" ){
                   $status_sig_id = 6;
               }
               
               
                if($status == 3){
                   $status_sig2 = "PENDENTE";
                   }else if($status == 4){
                       $status_sig2 = "CANCELADO";
                   }else if($status == 5){
                       $status_sig2 = "AGUARDANDO VALIDAÇÃO";
                   }else if($status == 6){
                       $status_sig2 = "CONCLUÍDO";
                   }
                   
               
                if ($status == $status_sig_id){
                // echo 'Status GLPI :'.$status.' Status no SIG : '.$status_sig2.'<br>';
                 $ont_dif++;
               }else{
                   
                    if($status_sig != 'CONCLUÍDO'){
                        
                       if($status_sig != 'CANCELADO'){
                        
                        
                       // echo 'Status GLPI :'.$status.' Status no SIG : '.$status_sig2.'<br>';

                        $data_acao = array('status' => $status_sig2);
                        $this->reports_model->updatePlano($id_plano, $data_acao); 
                       
                    
                       }
                    }
               }
               
               
               
               //VERIFICA SE MUDOU A CATEGORIA
                if ($categoria == $id_evento_glpi){
                 //echo 'a cat ta ok';
               }else{
                   //  echo 'a cat n ta ok';
               }    
                   
               
              //VERIFICA SE MUDOU O RESPONSAVEL
                if ($id_user_sig == $responsvel){
                 //echo 'a cat ta ok';
               }else{
                 // echo $id.' mudou de: '.$responsvel.'para: '.$id_user_sig.'<br>';
                   $data_acao = array('responsavel' => $id_user_sig);
                   $this->reports_model->updatePlano($id_plano, $data_acao); 
               }   
              
              
           
               
               
           }else{
               //NÃO TEM A AÇÃO NO SIG, FAZ O INSERT
               
               if($status == 3){
                   $status_sig = "PENDENTE";
               }else if($status == 4){
                   $status_sig = "CANCELADO";
               }else if($status == 5){
                   $status_sig = "AGUARDANDO VALIDAÇÃO";
               }else if($status == 6){
                   $status_sig = "CONCLUÍDO";
               }
               
               if(!$id_user_sig){
                  // se não tiver usuário no SIG, faz um insert
                   $data_usuario = array('email' => $email, 
                                    'group_id' => 5, 
                                    'id_glpi' => $user_id, 
                                    'active' => 1);
                   $id_user_sig = $this->reports_model->addUser( $data_usuario);
               }
               
               //SE NÃO EXISTE FAZ O INSERT
               $data_plano = array(
                'id_ticket' => $id,   
                'idatas' => 300,
                'descricao' => $acao,
                'data_termino' => $prazo,
                'responsavel' => $id_user_sig,
                'status' => $status_sig,
                'data_elaboracao' => $data,   
                'observacao' => $obs,
                'eventos' => $id_evento
            );
               $avulsa = "SIM";
               $this->atas_model->add_planoAcao($data_plano,null,$avulsa,$id_user_sig);
               
               
                 $data_user = array(
                'id_glpi' => $user_id
            );
                $this->reports_model->updateUser($id_user_sig, $data_user); 
                
           }
           
          
           $cont++;
            
           */
        }
     //  echo 'total de ações com status diferentes : '.$ont_dif;
        
       
       
        
         
    }
    
    /*
     **********************ATUALIZA AS ORDEM DE SERVIÇO NO SIG. Verifica no GLPI e atualiza o SIG****************************************************************** 
     */     
    
    public function atualizaOrdemServicos()
    {
     // $date_hoje = date('Y-m-d H:i:s');
     // $date_2 = date('Y-m-d');
     
        //GLPI
        $tickets = $this->reports_model->getAllOrdemServicos();
        $ont_dif = 0;
        $cont = 0;
        foreach ($tickets as $ticket) {
           $id = $ticket->id;
           $acao = $ticket->name;
           $data = $ticket->date;
           $data_conclusao = $ticket->closedate;
           $status = $ticket->status;
           $obs = $ticket->content;
           $categoria = $ticket->itilcategories_id;
           
           // ticket users
           $user_id = $ticket->users_id;
           $type = $ticket->type;
           
           // user
           $matricula = $ticket->matricula;
           
           // email
           $email = $ticket->email;
           
           //prazo da ação
           $prazo =  date('Y-m-d', strtotime("+30 days",strtotime($data))); 
           
           // consulta o usuário no SIG
           $user_sig = $this->reports_model->getUserbyemail($email);
           $id_user_sig = $user_sig->id;
          
           
           //CONSULTA O EVENTO
           $evento = $this->reports_model->getEventoByIdGlpi($categoria);
           $id_evento = $evento->id;
           $id_evento_glpi = $evento->id_gpli;
           
           if(!$id_evento){
               $categoria = 26;
           }
           
           //  echo $status; exit;
           
           
           
           //CONSULTA SE EXISTE UMA AÇÃO REFERENTE AO TICKET
           $plano = $this->reports_model->getPlanoByIdTicket($id, $id_user_sig);
           
           
           if($plano){
              //SE JÁ EXISTIR NO SIG, VERIFICA O STATUS, item_evento E O RESPONSÁVEL, CASO SEJAM DIFERENTES, ATUALIZA NO SIG
               $id_plano = $plano->idplanos;
               $evento = $plano->eventos;
               $status_sig = $plano->status;
               $responsvel = $plano->responsavel;
               
              
               
               //VERIFICA SE MUDOU O STATUS
                if($status_sig == "PENDENTE"){
                   $status_sig_id = 3;
               }else if($status_sig == "CANCELADO"){
                   $status_sig_id == 4;
               }else if($status_sig == "AGUARDANDO VALIDAÇÃO"){
                   $status_sig_id = 5;
               }else if($status_sig == "CONCLUÍDO" ){
                   $status_sig_id = 6;
               }
               
               
                   if($status == 3){
                   $status_sig2 = "PENDENTE";
                   }else if($status == 4){
                       $status_sig2 = "CANCELADO";
                   }else if($status == 5){
                       $status_sig2 = "AGUARDANDO VALIDAÇÃO";
                   }else if($status == 6){
                       $status_sig2 = "CONCLUÍDO";
                   }
                   
               
                if ($status == $status_sig_id){
                // echo 'Status GLPI :'.$status.' Status no SIG : '.$status_sig2.'<br>';
                 $ont_dif++;
               }else{
                   
                    if($status_sig != 'CONCLUÍDO'){
                        
                      // if($status_sig != 'CANCELADO'){
                        
                        
                       // echo 'Status GLPI :'.$status.' Status no SIG : '.$status_sig2.'<br>';

                        $data_acao = array('status' => $status_sig2);
                        $this->reports_model->updatePlano($id_plano, $data_acao); 
                       
                    
                      // }
                    }
               }
               
               
               
               //VERIFICA SE MUDOU A CATEGORIA
                if ($categoria == $id_evento_glpi){
                 //echo 'a cat ta ok';
               }else{
                   //  echo 'a cat n ta ok';
               }    
                   
               
              //VERIFICA SE MUDOU O RESPONSAVEL
                if ($id_user_sig == $responsvel){
                 //echo 'a cat ta ok';
               }else{
                 // echo $id.' mudou de: '.$responsvel.'para: '.$id_user_sig.'<br>';
                   $data_acao = array('responsavel' => $id_user_sig);
                   $this->reports_model->updatePlano($id_plano, $data_acao); 
               }   
              
              
           
               
               
           }else{
               //NÃO TEM A AÇÃO NO SIG, FAZ O INSERT
               
               if($status == 3){
                   $status_sig = "PENDENTE";
               }else if($status == 4){
                   $status_sig = "CANCELADO";
               }else if($status == 5){
                   $status_sig = "AGUARDANDO VALIDAÇÃO";
               }else if($status == 6){
                   $status_sig = "CONCLUÍDO";
               }
               
               if(!$id_user_sig){
                  // se não tiver usuário no SIG, faz um insert
                   $data_usuario = array('email' => $email, 
                                    'group_id' => 5, 
                                    'id_glpi' => $user_id, 
                                    'active' => 1);
                   $id_user_sig = $this->reports_model->addUser( $data_usuario);
               }
               
               //SE NÃO EXISTE FAZ O INSERT
               $data_plano = array(
                'id_ticket' => $id,   
                'idatas' => 300,
                'descricao' => $acao,
                'data_termino' => $prazo,
                'responsavel' => $id_user_sig,
                'status' => $status_sig,
                'data_elaboracao' => $data,   
                'observacao' => $obs,
                'eventos' => $id_evento
            );
               $avulsa = "SIM";
               $this->atas_model->add_planoAcao($data_plano,null,$avulsa,$id_user_sig);
               
               
                 $data_user = array(
                'id_glpi' => $user_id
            );
                $this->reports_model->updateUser($id_user_sig, $data_user); 
                
           }
           
          
           $cont++;
            
           
        }
     //  echo 'total de ações com status diferentes : '.$ont_dif;
        
       
       
        
         
    }
    
    /*
     **********************Verifica no GLPI se tem chamados abertos a 8 dias e envia um email de aviso. se o chamado tiver 10 dias ou mais, encerra.****************************************************************** 
     */ 
    public function atualizaStatusOrdemServicos(){
         
        
        $tickets = $this->reports_model->getAllOrdemServicos();
        $date_hoje = date('Y-m-d');
        $cont = 0;
        foreach ($tickets as $ticket) {
           $id = $ticket->id;
           $acao = $ticket->name;
           $data = $ticket->date;
           $data_conclusao = $ticket->closedate;
           $status = $ticket->status;
           $obs = $ticket->content;
           $categoria = $ticket->itilcategories_id;
           $prazo_aviso =  date('Y-m-d', strtotime("+8 days",strtotime($data)));
           $prazo_encerra =  date('Y-m-d', strtotime("+10 days",strtotime($data))); 
           
           if($status == 5){
               
               $tickets2 = $this->reports_model->getAllSolicitanteOrdemServicosSolucionado($id);
               $id_user = $tickets2->user_id;
               $matricula = $tickets2->matricula;
               $firstname = $tickets2->firstname;
               $realname = $tickets2->realname;
               $email = $tickets2->email;
               
               
               if($date_hoje == $prazo_aviso){
                   
                   if($email){
                       /*
                        * ENVIA EMAIL AVISANDO DO CHAMDO ABERTO
                        */
                       
                        $this->ion_auth->emailAvisoFechamentoOS($matricula, $firstname.' '.$realname, $email, $id);
                       //  echo $id.' - '.$id_user.' data abertura : '.$data.' - '.$matricula.' : '.$firstname.' '.$realname.' - '.$email.'<br>';
                   }
                    
                    
               } else if($date_hoje >= $prazo_encerra){
                    
                       /*
                        * MUDA O STATUS PARA FECHADO
                        */
                         $data_ticket = array(
                            'status' => 6
                        );
                        $this->reports_model->updateTicket($id, $data_ticket); 
                  //  $this->enviaEmailControle('O SIG Alterou o Status para FECHADO, dos chamados solucionado a 10 dias.');
                   
               }
               
               
               
              
               
           }
           
           
        }
        // echo $cont;
     }
     
     
    public function atualizaStatusAllOrdemServicos(){
         
        
        $tickets = $this->reports_model->getDistinctAllOrdemServicos();
        $date_hoje = date('Y-m-d');
        $cont = 0;
        foreach ($tickets as $ticket) {
           $id_user = $ticket->id_user;
          
           $tickets2 = $this->reports_model->getTicketsByUser($id_user);
           $qtde_ticket = $tickets2->quantidade;
           
           $emails = $this->reports_model->getUseremailGlpiById($id_user);
           $email = $emails->email;
           $matricula = $emails->name;
           $firstname = $emails->firstname;
           $realname = $emails->realname;
           
           if($email){
             
                //    $this->ion_auth->emailAvisoGeralFechamentoOS($matricula, $firstname.' '.$realname, $email, $qtde_ticket);
                  
                   
           // echo $id_user.' - '.$email.' - '.$matricula.' - '.$firstname.' '.$realname.' - '.$qtde_ticket.'<br>';
           }
           
           
           
           $cont++;
        }
         //echo '<br>'.$cont;
     }
     
     /*
      * PERÍODO HORA EXTRA
      */
     public function criaNovoPeriodoHoraExtra(){
         
          $dia = date('d');
          $mes = date('m');
          $ano = date('Y');
          $prox_mes = $mes+1;
          $dez_mes = 12;
          
       // if($dia == 11){
                   
          
          $equipes_projeto = $this->atas_model->getAllEquipesMembrosDistinct(4);
          foreach ($equipes_projeto as $equipe_projeto) {
                  $id_usuario =  $equipe_projeto->user_id;
                  
                //VERIFICA SE JA TEM CADASTRADO NO BANCO O PERÍODO DESEJADO.
                $priodo = $this->reports_model->getPeriodoByCompetencia($prox_mes, $ano, $id_usuario);
                $qtde = $priodo->quantidade;
                   
                if($qtde == 0){
              
                   $dados_novo_periodo = array(
                       'mes' => $prox_mes,
                       'ano' => $ano,
                       'de' => '12/'.$mes,
                       'ate' => '11/'.$prox_mes,
                       'user_id' => $id_usuario
                    );
                 
                   $id_periodo = $this->reports_model->addPeriodo_he($dados_novo_periodo);
                    
                   $data_inicial = $ano.'-'.$mes.'-'.'12';
                    $data_final = $ano.'-'.$prox_mes.'-'.'11';

                     // Calcula a diferença em segundos entre as datas
                     $diferenca = strtotime($data_final) - strtotime($data_inicial);

                     //Calcula a diferença em dias
                     $dias_data = floor($diferenca / (60 * 60 * 24));

                   
                     //verifica o número de dias do mês
                     $numero = cal_days_in_month(CAL_GREGORIAN, 12, 2018); 
                    
                     $cont = 0;
                     for($dia =12; $dia <= $numero; $dia++){
                         
                         if ($cont <= $dias_data) {
                         
                             if($dia >= 12){
                               
                                $dados_detalhe_periodo = array(
                                   'id_periodo' => $id_periodo,
                                   'dia' => $dia,
                                   'mes' => $mes
                                );
                             }else{
                         
                                 $dados_detalhe_periodo = array(
                                   'id_periodo' => $id_periodo,
                                   'dia' => $dia,
                                   'mes' => $prox_mes
                                );
                             }

                             $this->reports_model->addPeriodo_he_detalhes($dados_detalhe_periodo);
                             
                             
                            if (($dia == $numero) && ($cont <= $dias_data)) {
                                $dia = 0;
                            }
                             $cont++;

                           
                        } else {
                           // exit;
                        }
                }

                }
              //    echo 'concluído'; exit;
          }        
         
        //}
          
          
        
     }
    
     /*
      *   public function criaNovoPeriodoHoraExtra(){
         
          $dia = date('d');
          $mes = date('m');
          $ano = date('Y');
          $prox_mes = $mes+1;
          $dez_mes = 12;
          
       // if($dia == 11){
                   
          
          $equipes_projeto = $this->atas_model->getAllEquipesMembrosDistinct(4);
          foreach ($equipes_projeto as $equipe_projeto) {
                  $id_usuario =  $equipe_projeto->user_id;
                  
                //VERIFICA SE JA TEM CADASTRADO NO BANCO O PERÍODO DESEJADO.
                $priodo = $this->reports_model->getPeriodoByCompetencia($mes, $ano, $id_usuario);
                $qtde = $priodo->quantidade;
                  
                if($qtde == 0){
              
                   $dados_novo_periodo = array(
                       'mes' => $mes,
                       'ano' => $ano,
                       'de' => '12/12',
                       'ate' => '11/01',
                       'user_id' => $id_usuario
                    );
                 
                   $id_periodo =  $this->reports_model->addPeriodo_he($dados_novo_periodo);
                    
                    $data_inicial = '2018-12-12';
                    $data_final   = '2019-01-11';
                  // $data_inicial = $ano.'-'.$mes.'-'.'12';
                  //  $data_final = $ano.'-'.$prox_mes.'-'.'11';

                     // Calcula a diferença em segundos entre as datas
                     $diferenca = strtotime($data_final) - strtotime($data_inicial);

                     //Calcula a diferença em dias
                     $dias_data = floor($diferenca / (60 * 60 * 24));
                     
                   
                     //verifica o número de dias do mês
                     $numero = cal_days_in_month(CAL_GREGORIAN, 12, 2018); 
                    
                     $cont = 0;
                     for($dia =12; $dia <= $numero; $dia++){
                         
                         if ($cont <= $dias_data) {
                         
                             if($dia >= 12){
                               
                                $dados_detalhe_periodo = array(
                                   'id_periodo' => $id_periodo,
                                   'dia' => $dia,
                                   'mes' => $dez_mes
                                );
                             }else{
                         
                                 $dados_detalhe_periodo = array(
                                   'id_periodo' => $id_periodo,
                                   'dia' => $dia,
                                   'mes' => $mes
                                );
                             }

                             $this->reports_model->addPeriodo_he_detalhes($dados_detalhe_periodo);
                             
                             
                            if (($dia == $numero) && ($cont <= $dias_data)) {
                                $dia = 0;
                            }
                             $cont++;

                           
                        } else {
                           // exit;
                        }
                }

                }
               //   echo 'concluído'; exit;
          }        
         
        //}
          
          
        
     }
      */
   
    
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
     
 
     
   /*
     **********************ATUALIZA AS ORDEM DE SERVIÇO NO SIG. Verifica no GLPI e atualiza o SIG****************************************************************** 
     */     
    
    public function atualizaPontoTI()
    {
     
        include 'conexao_rh3.php';
       // echo 'teste conexao'; exit;
        //OBTEM SALDO DE BANCO DE HORAS POR DIA
        $dia = 18;// date('d');
        $hoje =  date('d');
        $mes =  date('m');
        $ano = date('Y');
        $prox_mes = $mes+1;
          
        if($hoje <= 11){
           $mes_Selecionado = $mes; 
        }else{
            $mes_Selecionado = $prox_mes; 
        }
        
       
      
        $periodos = $this->user_model->getPeriodoHEByAnoAndMes($mes_Selecionado, $ano);
            foreach ($periodos as $periodo) {
                $id_periodo = $periodo->id;
                $id_user = $periodo->user_id;
               
                $dados_user = $this->reports_model->getCPFByUser($id_user);
                $cpf = $dados_user->cpf;
                
                 $lancamentos = $this->user_model->getDetalhesPeriodoHEByIdPeriodo($id_periodo);
                 foreach ($lancamentos as $detalhes) {
                   //$mes_detalhe = strlen($detalhes->mes);  
                   
                   if(strlen($detalhes->dia) == 1){
                       $detalhe_dia = '0'.$detalhes->dia;
                   }else{
                       $detalhe_dia = $detalhes->dia;
                   }  
                     
                   if(strlen($detalhes->mes) == 1){
                       $detalhe_mes = '0'.$detalhes->mes;
                   }else{
                       $detalhe_mes = $detalhes->mes;
                   }
                   
               //    if(($detalhe_dia >= 12)&&($detalhe_dia <= 31)){
                 //      $data = "$detalhe_dia-$detalhe_mes-2018";
                 //  }else{
                       $data = "$detalhe_dia-$detalhe_mes-$periodo->ano";
                 //  }
                      // echo $data; exit;
                  // 
                   
                  //echo 'Id Período :'.$id_periodo.' - usuario: '.$id_user.' - CPF : '.$cpf.'<br>';
                  //echo 'Id dia :'.$detalhes->id.'<br>';
                  //echo 'Data :'.$data.'<br>'; exit;
                  
                 
                  
                    $query_saldo_hora = "select  (select (ep.dataponto) from pt_espelhoponto ep 
                                        where ep.idpessoa = p.id
                                        and to_char(ep.dataponto ,'dd-mm-yyyy') = '$data') data,

                                        (select to_char(min(ep.real_horarioentrada),'hh24:mi') from pt_espelhoponto ep 
                                        where ep.idpessoa = p.id
                                        and to_char(ep.dataponto ,'dd-mm-yyyy') = '$data') entrada,

                                        (select to_char(min(ep.real_interventrada),'hh24:mi') from pt_espelhoponto ep 
                                        where ep.idpessoa = p.id
                                        and to_char(ep.dataponto ,'dd-mm-yyyy') = '$data') saida_intervalo,

                                        (select to_char(min(ep.real_intervsaida),'hh24:mi') from pt_espelhoponto ep 
                                        where ep.idpessoa = p.id
                                        and to_char(ep.dataponto ,'dd-mm-yyyy') = '$data') retorno_intervalo,

                                        (select to_char(min(ep.real_horariosaida),'hh24:mi') from pt_espelhoponto ep 
                                        where ep.idpessoa = p.id
                                        and to_char(ep.dataponto ,'dd-mm-yyyy') = '$data') saida,


                                        (select to_char(min(ep.qtdhorasextras),'hh24:mi') from pt_espelhoponto ep 
                                        where ep.idpessoa = p.id
                                        and to_char(ep.dataponto ,'dd-mm-yyyy') = '$data') extra,

                                        (select to_char(min(ep.bh_qtdcredito),'hh24:mi') from pt_espelhoponto ep 
                                        where ep.idpessoa = p.id
                                        and to_char(ep.dataponto ,'dd-mm-yyyy') = '$data') BH_CREDITO,


                                         (select to_char(min(ep.bh_qtddebito),'hh24:mi') from pt_espelhoponto ep 
                                        where ep.idpessoa = p.id
                                        and to_char(ep.dataponto ,'dd-mm-yyyy') = '$data') BH_DEBITO




                                from pessoas p
                                where p.datarescisao is null 
                                and p.cpf in '$cpf' 
                                and p.tipopessoa ='F' ";

                   

                    $saldo_devedor_hmu = oci_parse($ora_conexao,$query_saldo_hora);
                    oci_execute($saldo_devedor_hmu, OCI_NO_AUTO_COMMIT);
                    $cont = 0;
                    while (($row_sd_hmu = oci_fetch_array($saldo_devedor_hmu, OCI_BOTH)) != false)
                    {

                    $data_periodo = $row_sd_hmu[0];    //DATA
                    $bt_entrada = $row_sd_hmu[1]; //  entrada
                    $entrada_intervalo = $row_sd_hmu[2]; // entrada intervalo
                    $saida_intervalo = $row_sd_hmu[3]; // saída intervalo 
                    $bt_saida = $row_sd_hmu[4]; // bat saída 
                    $extra = $row_sd_hmu[5]; // Extra
                    $sd_positivo = $row_sd_hmu[6];  // crédito
                    $sd_positivo = str_replace(",", ".", $sd_positivo);
                    $sd_negativo = $row_sd_hmu[7]; // débito
                    $sd_negativo = str_replace(",", ".", $sd_negativo);

                    if($extra){
                        $credito = $extra;
                        $hr_extra = 1;
                    }else{
                        $credito = $sd_positivo;
                        $hr_extra = 0;
                    }

                    
                     $dados_novo_periodo = array(
                                   'hora_inicio' => $bt_entrada,
                                   'hora_fim_confirmado' => $bt_saida,
                                   'saldo' => $credito,
                                   'debito' => $sd_negativo,
                                   'entrada_intervalo' => $entrada_intervalo,
                                   'saida_intervalo' => $saida_intervalo,
                                   'extra' => $hr_extra
                                );
                     
                     
                        $this->reports_model->updateDadosHoraUsuario($detalhes->id, $dados_novo_periodo);
                    
                      /*   
                    echo '-------------------<br>';
                        echo 'Entrada  : '.$bt_entrada. 
                                '<br> entrada Intervalo : '.$entrada_intervalo.' Saída Intervalo : '.$saida_intervalo.
                                '<br> Saída : '.$bt_saida.
                                '<br> Crédito : <br>'.$this->float_min($sd_positivo).' Débito : '.$this->float_min($sd_negativo);
                        
                         echo '<br> ------------------------------- <br><br><br>';
                         
                         
                      * 
                      */
                    }
        
                }
           }
           
           echo 'EXECUTADO COM SUCESSO';
    }  
     
      public  function float_min($num) {
      $num = number_format($num,2);
      $num_temp = explode('.', $num);
      $num_temp[1] = $num-(number_format($num_temp[0],2));
      $saida = number_format(((($num_temp[1]) * 60 / 100)+$num_temp[0]),2);
      $saida = strtr($saida,'.',':');
      return $saida;
      // By Alexandre Quintal - alexandrequintal@yahoo.com.br
    } 
    
    public function atualizaAcaoPeriodo(){
         $periodos = $this->user_model->getDetalhesPeriodoHEByIdPeriodo($prox_mes, $ano);
         $cont = 1;
            foreach ($periodos as $periodo) {
                $id_periodo = $periodo->id;
                $id_acao = $periodo->id_acao;
             
                if($id_acao){
                    echo $cont++.' - '.$id_periodo.'-'.$id_acao.'<br>';
                    
                    
                    $dados_novo_periodo = array(
                                   'id_acao' => $id_acao,
                                   'id_periodo_registro' => $id_periodo
                                  
                                );
                     
                        $this->reports_model->addAcaoJustificativaHora( $dados_novo_periodo);
                    
                }
            }    
    }
     
    
    public function testeEmail(){
      //  echo 'aqui'; exit;
     $this->enviaEmailControle('ENVIOU EMAIL');
    }
    
    public function testeUsuario(){
    
         $usuarios = $this->site->getAllUser();
         $cont = 1;
         foreach ($usuarios as $user) {
           $id_user = $user->id;   
           
           $nome = $user->first_name;
           $email = $user->email;  
           $matricula = $user->matricula;  
           
           $usuario = strstr($email, '@', TRUE); // Resultado: contato
           
           if($matricula){
           echo $nome.' - '.$matricula. '<br>';
           }
           /*
           $data_ata = array(
               
                'username' => $usuario
            );
            * 
            */
           
         
        //    $this->atas_model->atualizaUser($id_user, $data_ata);
           
           
         }
        
        
   //  $email = "israel.araujo@gmail.com";
  //   $dominio = strstr($email, '@');
  //  echo $dominio.'<br />'; // Resultado: @mauricioprogramador.com.br

    
     exit;
    // 
    }
    
    
}
