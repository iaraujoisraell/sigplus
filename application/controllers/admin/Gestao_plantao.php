<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gestao_plantao extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('announcements_model');
        $this->load->model('Unidades_hospitalares_model');
        $this->load->model('Medicos_model');
        $this->load->model('Horario_model');
        $this->load->model('Utilities_model');
        $this->load->model('Relatorio_escala_model');
    }

     public function index($id = '')
    {
        $this->list_escalas($id);
          
        $this->load->model("Unidades_hospitalares_model");
        $data['unidades_hospitalares'] = $this->Unidades_hospitalares_model->get();
        $data['competencias'] = $this->Unidades_hospitalares_model->get_competencia_escala();        
           
        $this->load->view('admin/gestao_escala/configuracao_rotina/manage', $data);
    }
    
    public function rodizio()
    {
        redirect(admin_url('rodizio'));
    }
    
    public function list_escalas($id = '')
    {
      //echo $id; exit;
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
        
        close_setup_menu();
        $data['invoiceid']            = $id;
        // categorias de despesas
        
        //$data['categorias_financeira'] = $this->Financeiro_model->get_categorias(1);
       
        $data['horarios'] = $this->Horario_model->get_horario();
        $data['unidades_hospitalares'] = $this->Unidades_hospitalares_model->get();
        $data['competencias'] = $this->Unidades_hospitalares_model->get_competencia_escala();        

        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/gestao_escala/plantoes/manage', $data);
    }
    
    public function manage_escala()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                $setor_id = $data['setor_id'];
                $competencia_id = $data['competencia_id'];
            
                $unidades_hospitalares = $this->Unidades_hospitalares_model->get();
                foreach ($unidades_hospitalares as $unidade){
                    $unidade_id = $unidade['id'];
                   
                    $setores = $this->Unidades_hospitalares_model->get_setores_escala($unidade_id);
                    foreach ($setores as $setor){
                        $setor_id = $setor['id'];
                        
                        $horarios = $this->Unidades_hospitalares_model->get_horario_setores_disponivel($setor_id, $competencia_id);
                        foreach ($horarios as $horario){
                            $config_id = $horario['id'];
                           
                            // 1 - Verifica se tem configuração para este setor
                               // $verifica_config_setor = $this->Unidades_hospitalares_model->get_setores_configuracao($setor_id);
                                //if($verifica_config_setor){
                                   // $verifica_competencia_setor = $this->Unidades_hospitalares_model->get_setores_competencia($setor_id, $competencia_id, $config_id);
                                   // if(!$verifica_competencia_setor){
                                       
                                        $ef_id                      = $horario['ef_id'];
                                        $dia_semana                 = $horario['dia_semana'];        
                                        $horario_horiginal_id       = $horario['horario_id'];
                                        $h_ef_horario_id            = $horario['ef_horario_id'];    
                                        
                                            if($h_ef_horario_id){
                                                $horario_id = $h_ef_horario_id;
                                            }else{
                                                $horario_id = $horario_horiginal_id;
                                            }
                                            
                                        $data_add['horario_id'] = $horario_id;
                                        
                                        
                                        $data_add['ef_id'] = $ef_id;
                                        $data_add['dia_semana'] = $dia_semana;
                                        $data_add['competencia_id'] = $competencia_id;
                                        $data_add['unidade_id'] = $unidade_id;
                                        $data_add['setor_id'] = $setor_id;
                                        $data_add['config_id'] = $config_id;
                                        
                                        
                                        $this->Unidades_hospitalares_model->add_escala_plantao($data_add);
                                        
                                   // }
                               // }
                        }
                    }// setor
                    
                    
                }// unidade
              
                
                
                
                
                $success = true;
                    $message = _l('added_successfully', 'Escala de Plantão');
                    
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                    
            }
        }
    }
    
    public function table_escala_setores()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('escala_setores_competencia');
    }
    
    public function retorno_setores(){
       $unidade_id = $this->input->post("unidade_id");
       $data['setores'] = $this->Unidades_hospitalares_model->get_setores_escala($unidade_id);
       $data['unidade_id'] = $unidade_id;
       $this->load->view('admin/gestao_escala/plantoes/retorno_setores', $data);
    }
    
    //retorna os horários disponível para aquele setor
    public function retorno_horarios_plantao(){
       $setor_id= $this->input->post("setor_id");
       $competencia_id = $this->input->post("competencia_id");
       $data['horarios'] = $this->Unidades_hospitalares_model->get_horario_setores_disponivel($setor_id, $competencia_id);
       $data['setor_id'] = $setor_id;
       $this->load->view('admin/gestao_escala/plantoes/retorno_horarios', $data);
    }
    
    public function retorno_setores_gestao_escala(){
       $unidade_id = $this->input->post("unidade_id");
       $data['setores'] = $this->Unidades_hospitalares_model->get_setores_escala($unidade_id);
       $data['unidade_id'] = $unidade_id;
       $this->load->view('admin/gestao_escala/configuracao_rotina/retorno_setores', $data);
    }
    
    /* 28/07/2022
     * Larissa Oliveira
     * Retorna os médicos duplicados para o filtro por meio da competencia
    */
    public function retorno_duplicados_gestao_escala(){
       
       $competencia_id = $this->input->post("competencia_id");
       $duplicados = $this->Medicos_model->get_se_duplicado($competencia_id);
       $j=0;
       
       for($i=0; $i < count($duplicados); $i++){
           if($duplicados[$i]==$duplicados[$i+1]){
                   $medicos[$j] = $duplicados[$i];
                   $j++;
               }
       }
       $data['medicos'] = $medicos;
       $this->load->view('admin/gestao_escala/configuracao_rotina/retorno_med_duplicados', $data);
       
    }
    
    public function retorno_horarios_plantao_gestao_escala(){
       $setor_id= $this->input->post("setor_id");
       
       $data['horarios'] = $this->Unidades_hospitalares_model->get_horario_setores_escala($setor_id);
       $data['setor_id'] = $setor_id;
       $this->load->view('admin/gestao_escala/configuracao_rotina/retorno_horarios', $data);
    }
    
    public function retorno_setores_gestao_escala_add_plantonista(){
       $unidade_id = $this->input->post("unidade_id");
       $data['setores'] = $this->Unidades_hospitalares_model->get_setores_escala($unidade_id);
       $data['unidade_id'] = $unidade_id;
       $this->load->view('admin/gestao_escala/configuracao_rotina/retorno_setores_add_plantonista', $data);
    }
    
    public function retorno_horarios_plantao_gestao_escala_add_plantonista(){
       $setor_id= $this->input->post("setor_id");
       $data['horarios'] = $this->Unidades_hospitalares_model->get_horario_setores_escala($setor_id);
       $data['setor_id'] = $setor_id;
       $this->load->view('admin/gestao_escala/configuracao_rotina/retorno_horarios_add_plantonista', $data);
    }
    
    /*
     * CALENDÁRIO
     */
    public function calendar($unidade_id)
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            $data    = $this->input->post();
            $success = $this->utilities_model->event($data);
            $message = '';
            if ($success) {
                if (isset($data['eventid'])) {
                    $message = _l('event_updated');
                } else {
                    $message = _l('utility_calendar_event_added_successfully');
                }
            }
            echo json_encode([
                'success' => $success,
                'message' => $message,
            ]);
            die();
        }
        $data['horarios'] = $this->Horario_model->get_horario();
        $data['unidades_hospitalares'] = $this->Unidades_hospitalares_model->get($unidade_id);
        $data['setores'] = $this->Unidades_hospitalares_model->get_setores_escala($unidade_id);
        $data['google_ids_calendars'] = $this->misc_model->get_google_calendar_ids();
        $data['google_calendar_api']  = get_option('google_calendar_api_key');
        $data['title']                = _l('calendar');
        add_calendar_assets();

        $this->load->view('admin/gestao_escala/calendario/calendar', $data);
    }
    
    public function view_event($id)
    {
        
        $data['event'] = $this->utilities_model->get_event_plantao($id);
        if ($data['event']->public == 1 && !is_staff_member()
            || $data['event']->public == 0 && $data['event']->userid != get_staff_user_id()) {
        } else {
            $this->load->view('admin/gestao_escala/calendario/event', $data);
        }
    }
    
    public function retorno_setores_filtro(){
       $unidade_id = $this->input->post("unidade_id");
       $data['setores'] = $this->Unidades_hospitalares_model->get_setores_escala($unidade_id);
       $data['unidade_id'] = $unidade_id;
       $this->load->view('admin/gestao_escala/calendario/retorno_setores', $data);
    }
    
    /*
     * ROTINA
     */
    
    public function listagem_escala($id = '')
    {
      //echo $id; exit;
        
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
        
        close_setup_menu();
        
        $data['invoiceid']            = $id;
        // categorias de despesas
        
        //$data['categorias_financeira'] = $this->Financeiro_model->get_categorias(1);
        $data['horarios'] = $this->Horario_model->get_horario();
        $data['horarios_quebrar'] = $this->Horario_model->get_horario_quebrar();
        
        $data['unidades_hospitalares'] = $this->Unidades_hospitalares_model->get();
        
        $data['competencias'] = $this->Unidades_hospitalares_model->get_competencia_escala();        
        $data['titulares'] = $this->Medicos_model->get();
        $data['escalados'] = $this->Medicos_model->get();
        $data['substitutos'] = $this->Medicos_model->get();
        $data['especialistas'] = $this->Medicos_model->get();
        
        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/gestao_escala/configuracao_rotina/manage', $data);
        
    }
  
    public function table_escala_gestao()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        //echo 'aqui'; exit;
        $this->app->get_table_data('escala_gestao');
    }


    
    public function bulk_action_trocar_escala()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $horario_troca_id      = $this->input->post('horario_troca_id');
            if($horario_troca_id){
                $dados_horario = $this->Horario_model->get_horario($horario_troca_id);
                $hora_inicio = $dados_horario->hora_inicio;
                $hora_fim = $dados_horario->hora_fim;
                $quantidade = $dados_horario->plantao;
            }
            
           
            
            $has_permission_delete = has_permission('items', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($horario_troca_id) {
                        if ($has_permission_delete) {
                           
                            $dados_event = $this->Utilities_model->get_event_by_id($id);
                           
                            $ano = $dados_event->ano;
                            $mes = $dados_event->mes;
                            $tam_mes = strlen($mes);
                            if($tam_mes == 1){
                                $mes = '0'.$mes;
                            }
                            $dia = $dados_event->dia;
                            $tam_dia = strlen($dia);
                            if($tam_dia == 1){
                                $dia = '0'.$dia;
                            }
                            $nova_data_start = "$ano-$mes-$dia $hora_inicio";
                            $nova_data_end = "$ano-$mes-$dia $hora_fim";
                           
                            $data_update['start']       = $nova_data_start;
                            $data_update['end']         = $nova_data_end;
                            $data_update['quantidade']  = $quantidade;
                            $data_update['color']       = '#fb8c00';
                            $data_update['troca_horario']  = 1;
                           
                            $this->db->where('eventid', $id);
                            $this->db->update(db_prefix() . 'events', $data_update);
                            
                            
                            //if ($this->invoice_items_model->delete($id)) {
                                $total_deleted++;
                           // }
                        }
                    }
                }
            }
        }

        if ($total_deleted) {
            set_alert('success', _l('total_items_updated', $total_deleted));
        }
    }

    // quebrar horário
    public function bulk_action_quebra_escala()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        $empresa_id = $this->session->userdata('empresa_id');
        $hoje = date('Y-m-d H:i:s');
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $horario_quebra_1      = $this->input->post('horario_quebra_1');
            $horario_quebra_2      = $this->input->post('horario_quebra_2');
            
            $has_permission_delete = has_permission('items', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($horario_quebra_1 && $horario_quebra_2) {
                           
                       // echo 'ID original : '.$id.'<br>';
                       // echo 'Horário 1 : '.$horario_quebra_1.'<br>';
                       // echo 'Horário 2 : '.$horario_quebra_2.'<br>';
                       //exit; 
                        
                            $dados_event = $this->Utilities_model->get_event_by_id($id);
                            $ano = $dados_event->ano;
                            $mes = $dados_event->mes;
                            $tam_mes = strlen($mes);
                            if($tam_mes == 1){
                                $mes = '0'.$mes;
                            }
                            $dia = $dados_event->dia;
                            $tam_dia = strlen($dia);
                            if($tam_dia == 1){
                                $dia = '0'.$dia;
                            }
                            
                           $data_base =  $ano.'-'.$mes.'-'.$dia;
                           
                           $titular_id          = $dados_event->titular_id;
                           $medico_escalado_id  = $dados_event->medico_escalado_id;
                           $quantidade          = $dados_event->quantidade;
                           $unidade_id          = $dados_event->unidade_id;
                           $setor_id            = $dados_event->setor_id;
                           //$start               = $dados_event->start;
                           //$end                 = $dados_event->end;
                           $dia_semana          = $dados_event->dia_semana;
                           $config_id           = $dados_event->config_id;
                           //$horario_id          = $dados_event->horario_id;
                           $competencia_id      = $dados_event->competencia_id;
                           
                            /*
                             * 1 - DELETA HORÁRIO SELECIONADO
                             */    
                            $data_update['deleted']  = 1;
                            $this->db->where('eventid', $id);
                            $this->db->update(db_prefix() . 'events', $data_update);
                            
                            // REGISTRO DO LOG
                                $data_log['event_id']  = $id;
                                $data_log['user_log']  = get_staff_user_id();
                                $data_log['data_log']  = date('Y-m-d H:i:s');
                                $data_log['acao']  = 'DELETOU HORÁRIO';
                                $this->db->insert(db_prefix() . 'events_log', $data_update);
                            
                            /*
                             * 2 - INSERT HORÁRIO 1
                             */
                           $dados_horario = $this->Horario_model->get_horario($horario_quebra_1);
                           $hora_inicio_1 = $data_base.' '.$dados_horario->hora_inicio;
                           $hora_fim_1 = $data_base.' '.$dados_horario->hora_fim;
                           $quantidade_1 = $dados_horario->plantao;
                           
                            $dados_horario1['public']                 = 1;
                            $dados_horario1['titular_id']             = $titular_id;
                            $dados_horario1['medico_escalado_id']     = $medico_escalado_id;
                            $dados_horario1['userid']                 = $medico_escalado_id;
                            $dados_horario1['quantidade']             = $quantidade_1;
                            $dados_horario1['item_id']                = 1;
                            $dados_horario1['unidade_id']             = $unidade_id;
                            $dados_horario1['setor_id']               = $setor_id;
                            $dados_horario1['start']                  = $hora_inicio_1;
                            $dados_horario1['end']                    = $hora_fim_1;
                            $dados_horario1['user_cadastro']          = get_staff_user_id();
                            $dados_horario1['data_cadastro']          = $hoje;
                            $dados_horario1['empresa_id']             = $empresa_id;
                            $dados_horario1['ano']                    = $ano;
                            $dados_horario1['mes']                    = $mes;
                            $dados_horario1['dia']                    = $dia;
                            $dados_horario1['dia_semana']             = $dia_semana;
                            $dados_horario1['config_id']              = $config_id;
                            $dados_horario1['horario_id']             = $horario_quebra_1;
                            $dados_horario1['competencia_id']         = $competencia_id;
                            $dados_horario1['color']                  = '#B833FF';
                         //   echo '<br> -------------------------------------------------- <br>';
                            //$dados_horario1['escalado_avulso']        = 1;
                            $this->db->insert(db_prefix() . 'events', $dados_horario1);
                                
                            
                            /*
                             * 3 - INSERT HORÁRIO 2
                             */
                           $dados_horario_2 = $this->Horario_model->get_horario($horario_quebra_2);
                           $hora_inicio_2 = $data_base.' '.$dados_horario_2->hora_inicio;
                           $hora_fim_2 = $data_base.' '.$dados_horario_2->hora_fim;
                           $quantidade_2 = $dados_horario_2->plantao;
                          
                          
                            $dados_horario2['public']                 = 1;
                            $dados_horario2['titular_id']             = $titular_id;
                            
                            $dados_horario2['medico_escalado_id']     = $medico_escalado_id;
                            $dados_horario2['userid']                 = $medico_escalado_id;
                            $dados_horario2['quantidade']             = $quantidade_2;
                            $dados_horario2['item_id']                = 1;
                            $dados_horario2['unidade_id']             = $unidade_id;
                            $dados_horario2['setor_id']               = $setor_id;
                            $dados_horario2['start']                  = $hora_inicio_2;
                            $dados_horario2['end']                    = $hora_fim_2;
                            
                            
                            
                            $dados_horario2['user_cadastro']          = get_staff_user_id();
                            $dados_horario2['data_cadastro']          = $hoje;
                            $dados_horario2['empresa_id']             = $empresa_id;
                            $dados_horario2['ano']                    = $ano;
                            $dados_horario2['mes']                    = $mes;
                            $dados_horario2['dia']                    = $dia;
                            $dados_horario2['dia_semana']             = $dia_semana;
                            $dados_horario2['config_id']              = $config_id;
                            $dados_horario2['horario_id']             = $horario_quebra_2;
                            $dados_horario2['competencia_id']         = $competencia_id;
                            $dados_horario2['color']                  = '#B833FF';
                            //$dados_horario1['escalado_avulso']        = 1;
                            $this->db->insert(db_prefix() . 'events', $dados_horario2);
                          // print_r($dados_horario2); exit;
                            
                            //if ($this->invoice_items_model->delete($id)) {
                                $total_deleted++;
                           // }
                        
                    }
                }
            }
        }

        if ($total_deleted) {
            set_alert('success', _l('total_items_updated', $total_deleted));
        }
    }

    
    
    public function bulk_action_add_plantonista()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        
       
        if ($this->input->post()) {
            $competencia_id     = $this->input->post('competencia');
            $dia_semana_post         = $this->input->post('dia_Semana');
            $unidade            = $this->input->post('unidades');
            $setor              = $this->input->post('setores');
            $horario            = $this->input->post('horario');
            $horario_exec       = $this->input->post('horario_exec');
            $medico             = $this->input->post('medico');
            $escalado           = $this->input->post('escalado');
           
            $empresa_id = $this->session->userdata('empresa_id');
            $hoje = date('Y-m-d H:i:s');
        
            if($medico){
                if($competencia_id){
                    if($dia_semana_post){
                
                        $dados_config = $this->Unidades_hospitalares_model->get_config($horario, $setor);    
                        $id_config = $dados_config->id;


                        $dados_horario = $this->Horario_model->get_horario($horario_exec);
                        $hora_inicio = $dados_horario->hora_inicio;
                        $hora_fim = $dados_horario->hora_fim;
                        $quantidade = $dados_horario->plantao;

                        $competencia = $this->Unidades_hospitalares_model->get_competencia_escala($competencia_id);
                        $ano_comp = $competencia->ano;
                        $mes_comp = $competencia->mes;

                        $tam_mes = strlen($mes_comp);
                        if($tam_mes == 1){
                            $mes_comp = '0'.$mes_comp;
                        }
                        $funcao = new DateTime("$ano_comp-$mes_comp");
                        $numDias_mes = $funcao->format('t');

                        $diasemana = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');


                        for($dia=1; $dia <= $numDias_mes; $dia++){
                            $tam_dia = strlen($dia);
                            if($tam_dia == 1){
                                $dia = '0'.$dia;
                            }
                            $data_cal = "$ano_comp-$mes_comp-$dia";
                            $diasemana_numero = date('w', strtotime($data_cal));
                            $dia_semana = $diasemana[$diasemana_numero];
                            $start = "$data_cal $hora_inicio";
                            $end = "$data_cal $hora_fim";


                            if($dia_semana == $dia_semana_post){
                                $dados_seg['public']                 = 1;
                                $dados_seg['titular_id']             = $medico;
                                $dados_seg['medico_escalado_id']     = $escalado;
                                $dados_seg['userid']                 = $escalado;
                                $dados_seg['quantidade']             = $quantidade;
                                $dados_seg['item_id']                = 1;
                                $dados_seg['unidade_id']             = $unidade;
                                $dados_seg['setor_id']               = $setor;
                                $dados_seg['start']                  = $start;
                                $dados_seg['end']                    = $end;
                                $dados_seg['user_cadastro']          = get_staff_user_id();
                                $dados_seg['data_cadastro']          = $hoje;
                                $dados_seg['empresa_id']             = $empresa_id;
                                $dados_seg['ano']                    = $ano_comp;
                                $dados_seg['mes']                    = $mes_comp;
                                $dados_seg['dia']                    = $dia;
                                $dados_seg['dia_semana']             = $dia_semana;
                                $dados_seg['config_id']              = $id_config;
                                $dados_seg['horario_id']             = $horario;
                                $dados_seg['competencia_id']         = $competencia_id;
                                $dados_seg['color']                  = '#84C529';
                                $dados_seg['escalado_avulso']        = 1;
                                
                              //  echo '-------<br><br>';
                                $this->db->insert(db_prefix() . 'events', $dados_seg);
                                $total_deleted++;
                            }
                        }
                
                    }// if dia semana
                
                }// if competencia
            }// if medico
             
        }// if post

        if ($total_deleted) {
            set_alert('success', _l('total_events_created', $total_deleted));
        }
    }
    
    // deleta o plantao
    public function bulk_action_deletar_horario()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $substituto_id      = $this->input->post('substituto_id');
            
            
            $has_permission_delete = has_permission('items', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                        if ($has_permission_delete) {
                           
                            if (!$substituto_id) {
                                $substituto_id = "";
                            }
                                $data_update['deleted']  = 1;
                                //$data_update['troca_escalado']      = 1;

                                $this->db->where('eventid', $id);
                                $this->db->update(db_prefix() . 'events', $data_update);
                            
                            // REGISTRO DO LOG
                                $data_log['event_id']  = $id;
                                $data_log['user_log']  = get_staff_user_id();
                                $data_log['data_log']  = date('Y-m-d H:i:s');
                                $data_log['acao']  = 'DELETOU HORÁRIO';
                                $this->db->insert(db_prefix() . 'events_log', $data_update);
                                //$this->db->update(db_prefix() . 'events_log', $data_update);
                            
                            
                            //if ($this->invoice_items_model->delete($id)) {
                                $total_deleted++;
                           // }
                                
                                /* LOG
                                 * $data_update['quantidade']  = $quantidade;
                            $data_update['color']       = '#03a9f4';
                            $data_update['troca_horario']  = 1;
                           
                            $this->db->where('eventid', $id);
                            $this->db->update(db_prefix() . 'events', $data_update);
                                 */
                        }
                    
                }
            }
        }

        if ($total_deleted) {
            set_alert('success', _l('total_items_updated', $total_deleted));
        }
    }

    public function bulk_action_trocar_titular()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $titular_id      = $this->input->post('titular_id');
            
            
            $has_permission_delete = has_permission('items', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                        if ($has_permission_delete) {
                           
                            if (!$titular_id) {
                                $titular_id = "";
                            }
                                $data_update['titular_id']  = $titular_id;
                                //$data_update['color']               = '#03a9f4';
                               

                                $this->db->where('eventid', $id);
                                $this->db->update(db_prefix() . 'events', $data_update);
                            
                            
                                // REGISTRO DO LOG
                                $data_log['event_id']  = $id;
                                $data_log['user_log']  = get_staff_user_id();
                                $data_log['data_log']  = date('Y-m-d H:i:s');
                                $data_log['acao']      = 'TROCOU O TITULAR';
                                $this->db->insert(db_prefix() . 'events_log', $data_update);
                            
                            
                            //if ($this->invoice_items_model->delete($id)) {
                                $total_deleted++;
                           // }
                                
                                /* LOG
                                 * $data_update['quantidade']  = $quantidade;
                            $data_update['color']       = '#03a9f4';
                            $data_update['troca_horario']  = 1;
                           
                            $this->db->where('eventid', $id);
                            $this->db->update(db_prefix() . 'events', $data_update);
                                 */
                        }
                    
                }
            }
        }

        if ($total_deleted) {
            set_alert('success', _l('total_items_updated', $total_deleted));
        }
    }
    
    public function bulk_action_trocar_escalado()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $escalado_id      = $this->input->post('escalado_id');
            
            
            $has_permission_delete = has_permission('items', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                        if ($has_permission_delete) {
                           
                            if (!$escalado_id) {
                                $escalado_id = "";
                            }
                                $data_update['medico_escalado_id']  = $escalado_id;
                                $data_update['color']               = '#03a9f4';
                                $data_update['troca_escalado']      = 1;

                                $this->db->where('eventid', $id);
                                $this->db->update(db_prefix() . 'events', $data_update);
                            
                            
                            
                            
                            
                            //if ($this->invoice_items_model->delete($id)) {
                                $total_deleted++;
                           // }
                                
                                /* LOG
                                 * $data_update['quantidade']  = $quantidade;
                            $data_update['color']       = '#03a9f4';
                            $data_update['troca_horario']  = 1;
                           
                            $this->db->where('eventid', $id);
                            $this->db->update(db_prefix() . 'events', $data_update);
                                 */
                        }
                    
                }
            }
        }

        if ($total_deleted) {
            set_alert('success', _l('total_items_updated', $total_deleted));
        }
    }
    
    // substituto
    public function bulk_action_trocar_substituto()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
            $substituto_id      = $this->input->post('substituto_id');
            
            
            $has_permission_delete = has_permission('items', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                        if ($has_permission_delete) {
                           
                            if (!$substituto_id) {
                                $substituto_id = "";
                            }
                                $data_update['substituto']  = $substituto_id;
                                $data_update['user_log_substituto']  = get_staff_user_id();
                                $data_update['color']               = '#BDBDBD';
                                $data_update['troca_substituto']      = 1;

                                $this->db->where('eventid', $id);
                                $this->db->update(db_prefix() . 'events', $data_update);
                            
                            
                            
                            
                            
                            //if ($this->invoice_items_model->delete($id)) {
                                $total_deleted++;
                           // }
                                
                                /* LOG
                                 * $data_update['quantidade']  = $quantidade;
                            $data_update['color']       = '#03a9f4';
                            $data_update['troca_horario']  = 1;
                           
                            $this->db->where('eventid', $id);
                            $this->db->update(db_prefix() . 'events', $data_update);
                                 */
                        }
                    
                }
            }
        }

        if ($total_deleted) {
            set_alert('success', _l('total_items_updated', $total_deleted));
        }
    }
    
    
    
    public function add_setor_competencia()
    {
        $competencia_referencia = 9;
        $competencia_destino = 10;
        
        $competencia_setores = $this->Unidades_hospitalares_model->get_competencia_setores_by_competencia_id($competencia_referencia);
        foreach ($competencia_setores as $setor){
            
            $data_comp_setor['competencia_id']          = $competencia_destino;
            $data_comp_setor['unidade_id']              = $setor['unidade_id'];
            $data_comp_setor['setor_id']                = $setor['setor_id'];
            $data_comp_setor['empresa_id']              = $setor['empresa_id'];
            $data_comp_setor['config_id']               = $setor['config_id'];
            $data_comp_setor['user_cadastro']           = get_staff_user_id();
            $data_comp_setor['data_cadastro']           = date('Y-m-d H:i:s');
            $data_comp_setor['user_ultima_alteracao']   = get_staff_user_id();
            $data_comp_setor['data_ultima_alteracao']   = date('Y-m-d H:i:s');
            
           
            $setor_comp = $this->Unidades_hospitalares_model->add_setor_unidade_by_competencia($data_comp_setor);
            echo $setor_comp.'<br>'; //exit;
        }
        
    }  
    
    
    
    /*
     * 19/07/22
     * Israel Araujo
     * GERA ESCALA A PARTIR DE UMA COMPETENCIA
     */
    public function add_escala_competencia()
    {
        $competencia_referencia = 8;
        $competencia_destino = 9;
        
        $unidades_hospitalares = $this->Unidades_hospitalares_model->get();
        foreach ($unidades_hospitalares as $unidade){
            $unidade_id = $unidade['id'];
            
            $setores = $this->Unidades_hospitalares_model->get_setores_escala($unidade_id);
            foreach ($setores as $setor){
                $setor_id = $setor['id'];
                    $data['competencia_id'] = $competencia_destino;
                    $data['unidade_id'] = $unidade_id;
                    $data['setor_id'] = $setor_id;
                    $this->Unidades_hospitalares_model->add_planto_by_competencia_origem($data, $competencia_referencia);
                    
               
            }
        }
        
        
        $competencia_setores = $this->Unidades_hospitalares_model->get_competencia_setores_by_competencia_id($competencia_referencia);
        foreach ($competencia_setores as $setor){
            
            $data_comp_setor['competencia_id']          = $competencia_destino;
            $data_comp_setor['unidade_id']              = $setor['unidade_id'];
            $data_comp_setor['setor_id']                = $setor['setor_id'];
            $data_comp_setor['empresa_id']              = $setor['empresa_id'];
            $data_comp_setor['config_id']               = $setor['config_id'];
            $data_comp_setor['user_cadastro']           = get_staff_user_id();
            $data_comp_setor['data_cadastro']           = date('Y-m-d H:i:s');
            $data_comp_setor['user_ultima_alteracao']   = get_staff_user_id();
            $data_comp_setor['data_ultima_alteracao']   = date('Y-m-d H:i:s');
            
           
            $setor_comp = $this->Unidades_hospitalares_model->add_setor_unidade_by_competencia($data_comp_setor);
            echo $setor_comp.'<br>'; //exit;
        }
         
         
        
    }

    public function pdf()
    {
        $competencia_id = $this->input->post("competencia_id");
        
        $relatorio_geral['escala']= $this->Relatorio_escala_model->get_todas_unidades($competencia_id);
    
          try {
            $rel_geral = rel_geral_pdf($relatorio_geral);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'I';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $rel_geral->Output(mb_strtoupper(slug_it(_l('rel_geral') . '-' . $relatorio_geral->escalaid)) . '.pdf', $type);
    }
    
    
  
    
}
