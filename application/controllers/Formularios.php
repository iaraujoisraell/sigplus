<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Formularios extends ClientsController
{
    public function index()
    {
        show_404();
    }

    /**
     * Web to lead form
     * User no need to see anything like LEAD in the url, this is the reason the method is named wtl
     * @param  string $key web to lead form key identifier
     * @return mixed
     */
    public function web($key, $hash='')
    {
        $this->load->model('Formulario_model');
        // VERIFICA SE JA TEM UMA RESPOSTA SALVA NA TABELA FINAL DE RESPOSTA PARA ESSE HASH E PARA ESSA KEY
        $get_respostas = $this->Formulario_model->get_resposta_key_hash($hash, $key);
        
        
        $form = $this->Formulario_model->get_form_externo_by_hash($key);
        $form_id = $form->id;
        $empresa_id = $form->empresa_id;
       
        $form_get_option_logo = $this->Formulario_model->get_option_logo($empresa_id);
        $company_logo = $form_get_option_logo->value;
        $form_get_option_nome_empresa = $this->Formulario_model->get_option_nome_empresa($empresa_id);
        $company_name = $form_get_option_nome_empresa->value;

        
        if (!$form) {
            show_404();
        }
        
        $perguntas = $this->Formulario_model->get_form_perguntas($form_id);
       
        // Change the locale so the validation loader function can load
        // the proper localization file
       

        if ($this->input->post('key')) {
           
            if ($this->input->post('key') == $key) {
                $post_data = $this->input->post();
                $required  = [];
                
                foreach ($data['form_fields'] as $field) {
                    if (isset($field->required)) {
                        $required[] = $field->name;
                    }
                }
                if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_lead_form') == 1) {
                    $required[] = 'accept_terms_and_conditions';
                }

                foreach ($required as $field) {
                    if ($field == 'file-input') {
                        continue;
                    }
                    if (!isset($post_data[$field]) || isset($post_data[$field]) && empty($post_data[$field])) {
                        $this->output->set_status_header(422);
                        die;
                    }
                }

                if (show_recaptcha() && $form->recaptcha == 1) {
                    if (!do_recaptcha_validation($post_data['g-recaptcha-response'])) {
                        echo json_encode([
                            'success' => false,
                            'message' => _l('recaptcha_error'),
                            ]);
                        die;
                    }
                }

                if (isset($post_data['g-recaptcha-response'])) {
                    unset($post_data['g-recaptcha-response']);
                }
                
                $sessao = $this->input->post('sessao');
                $total_sessao = $this->input->post('total_sessao');
                $hash = $this->input->post('hash');
                
                // DADOS DO FORM
                $dados_form = $this->Formulario_model->get_form_externo_by_hash($key);
                $form_pai   = $dados_form->form_pai;
                $form_key   = $dados_form->form_key;
                $form_id   = $dados_form->id;
                
               
               
                
                if ($sessao == $total_sessao) {
                   
                    // VERIFICA SE JA TEM UM REGISTRO PARA ESSE HASH E PARA ESSA SESSAO
                    $verifica_sessao_hash = $this->Formulario_model->get_resposta_sessao_hash($hash, $sessao);
                    if(!$verifica_sessao_hash){
                        
                        // SALVA AQUI AS RESPOSTAS TEMPORÁRIAS NA TABELA DEFINITIVA
                        // FAZER O SELECT 
                        $get_respostas = $this->Formulario_model->get_resposta_temporarias_by_hash($hash);
                        foreach ($get_respostas as $val) {
                            $form_key_temp       = $val['form_key'];
                            $name       = $val['pergunta'];
                            $val_respostas          = $val['value'];
                            $data_cadastro  = $val['data_cadastro'];
                            $ip             = $val['ip'];
                            $sessao         = $val['sessao'];
                            $total_sessao   = $val['total_sessao'];
                            $hash           = $val['hash'];
                            
                            $regular_respostas['form_key']         = $form_key_temp;   
                            $regular_respostas['pergunta']         = $name;
                            $regular_respostas['value']            = $val_respostas;
                            $regular_respostas['data_cadastro']    = $data_cadastro;
                            $regular_respostas['ip']               = $ip;
                            $regular_respostas['sessao']           = $sessao;
                            $regular_respostas['total_sessao']     = $total_sessao;
                            $regular_respostas['hash']             = $hash;
                            $this->db->insert(db_prefix() . '_intranet_formularios_respostas', $regular_respostas);
                        }
                        
                        // APAGA AS PERGUNTAS TEMPORÁRIAS PARA ESSE HASH
                            $this->db->where('hash', $hash);
                            $this->db->delete(db_prefix() . '_intranet_formularios_respostas_temp');
                         /************************* FIM TEMPORÁRIA ***********************************************/
                        //print_r($this->input->post());
                            
                            
                        unset($post_data['key']);
                        unset($post_data['sessao']);
                        unset($post_data['total_sessao']);
                        unset($post_data['hash']);

                        $regular_respostas = [];
                        foreach ($post_data as $name => $val) {

                            if(is_array($val)){
                            $total_array = count($val);
                            $cont_array = 1;
                            $val_respostas = '';
                            foreach ($val as $valor) {

                                if($cont_array == $total_array){
                                    $val_respostas .= $valor;
                                }else{
                                    $val_respostas .= $valor.',';
                                }
                                $cont_array++;
                            }
                            
                                $regular_respostas['form_key']         = $key;   
                                $regular_respostas['pergunta']         = $name;
                                $regular_respostas['value']            = $val_respostas;
                                $regular_respostas['data_cadastro']    = date('Y-m-d H:i:s');
                                $regular_respostas['ip']               = $_SERVER['REMOTE_ADDR'];
                                $regular_respostas['sessao']           = $sessao;
                                $regular_respostas['total_sessao']     = $total_sessao;
                                $regular_respostas['hash']             = $hash;
                                $this->db->insert(db_prefix() . '_intranet_formularios_respostas', $regular_respostas);
                            
                        }else{
                           
                                $regular_respostas['form_key']         = $key;   
                                $regular_respostas['pergunta']         = $name;
                                $regular_respostas['value']            = $val;
                                $regular_respostas['data_cadastro']    = date('Y-m-d H:i:s');
                                $regular_respostas['ip']               = $_SERVER['REMOTE_ADDR'];
                                $regular_respostas['sessao']           = $sessao;
                                $regular_respostas['total_sessao']     = $total_sessao;
                                $regular_respostas['hash']             = $hash;
                                $this->db->insert(db_prefix() . '_intranet_formularios_respostas', $regular_respostas);
                            
                        }    
                        }

                    }
                    
                    if($form_pai == 0){
                        // é o pai
                        $msg = $form->success_submit_msg;
                    }else{
                        // é o filho, retorna os dados do form pai
                        $form_pai = $this->Formulario_model->get_form_externo_by_id($form_pai);
                        $msg = $form_pai->success_submit_msg;
                       
                    }
                    
                    echo json_encode([
                    'success' => true,
                    'message' => $msg
                    ]);
                die;
                } else{
                    // continua para a proxima sessao
                    // 
                    // aslva os dados do formulário em uma tab temporária
                    // VERIFICA SE JA TEM UM REGISTRO PARA ESSE HASH E PARA ESSA SESSAO
                    $verifica_sessao_hash = $this->Formulario_model->get_resposta_sessao_hash($hash, $sessao);
                    if(!$verifica_sessao_hash){
                       
                        //print_r($this->input->post());
                        unset($post_data['key']);
                        unset($post_data['sessao']);
                        unset($post_data['total_sessao']);
                        unset($post_data['hash']);

                        $regular_respostas = [];
                        foreach ($post_data as $name => $val) {


                        if(is_array($val)){
                            $total_array = count($val);
                            $cont_array = 1;
                            $val_respostas = '';
                            foreach ($val as $valor) {

                                if($cont_array == $total_array){
                                    $val_respostas .= $valor;
                                }else{
                                    $val_respostas .= $valor.',';
                                }
                                $cont_array++;
                            }

                            $regular_respostas['form_key']         = $key;   
                            $regular_respostas['pergunta']         = $name;
                            $regular_respostas['value']            = $val_respostas;
                            $regular_respostas['data_cadastro']    = date('Y-m-d H:i:s');
                            $regular_respostas['ip']               = $_SERVER['REMOTE_ADDR'];
                            $regular_respostas['sessao']           = $sessao;
                            $regular_respostas['total_sessao']     = $total_sessao;
                            $regular_respostas['hash']             = $hash;
                            $this->db->insert(db_prefix() . '_intranet_formularios_respostas_temp', $regular_respostas);

                        }else{
                            $regular_respostas['form_key']         = $key;   
                            $regular_respostas['pergunta']         = $name;
                            $regular_respostas['value']            = $val;
                            $regular_respostas['data_cadastro']    = date('Y-m-d H:i:s');
                            $regular_respostas['ip']               = $_SERVER['REMOTE_ADDR'];
                            $regular_respostas['sessao']           = $sessao;
                            $regular_respostas['total_sessao']     = $total_sessao;
                            $regular_respostas['hash']             = $hash;
                            $this->db->insert(db_prefix() . '_intranet_formularios_respostas_temp', $regular_respostas);
                        }    





                        }

                    }
                    
                     
                    // verifica qual o próximo formulário filho; retorna a ultma sessao registrada
                    $sessao_atual_registrada = $this->Formulario_model->get_ultima_sessao_hash($hash);
                    $sessao_atual = $sessao_atual_registrada->sessao;
                   
                    if($form_pai == 0){
                        // form atual é o pai
                        $prox_sessao = $sessao_atual + 1;
                       
                        // retorna o proximo formulario
                        $proximo_form = $this->Formulario_model->get_form_filhos_sessao_externo($form_id, $prox_sessao);
                        $form_key_prox = $proximo_form->form_key;
                        $url = base_url('formularios/web/'.$form_key_prox.'/'.$hash);
                        
                    }else{
                       
                        $prox_sessao = $sessao_atual + 1;
                        //form atual é um filho
                        $proximo_form = $this->Formulario_model->get_form_filhos_sessao_externo($form_pai, $prox_sessao);
                        $form_key_prox = $proximo_form->form_key;
                        $url = base_url('formularios/web/'.$form_key_prox.'/'.$hash);
                    }
                    
                    
                    
                    echo json_encode([
                    'redirect_url' => $url                    
                    ]);
                die;
                }
                
                
               
                
               
            }
        }

        
        $data['hash'] = $hash; 
        $data['form'] = $form; 
        $data['perguntas'] = $perguntas;
        $data['company_logo'] = $company_logo;
        $data['company_name'] = $company_name;
        $this->load->view('formularios/web_form', $data);
    }

    
    public function msgObrigado(){
       // $this->view('forms/msg');        
       // $this->layout(true);
        $this->load->view('forms/msg');
    }
    /**
     * Web to lead form
     * User no need to see anything like LEAD in the url, this is the reason the method is named eq lead
     * @param  string $hash lead unique identifier
     * @return mixed
     */
    public function l($hash)
    {
        if (get_option('gdpr_enable_lead_public_form') == '0') {
            show_404();
        }
        $this->load->model('leads_model');
        $this->load->model('gdpr_model');
        $lead = $this->leads_model->get('', ['hash' => $hash]);

        if (!$lead || count($lead) > 1) {
            show_404();
        }

        $lead = array_to_object($lead[0]);
        load_lead_language($lead->id);

        if ($this->input->post('update')) {
            $data = $this->input->post();
            unset($data['update']);
            $this->leads_model->update($data, $lead->id);
            redirect($_SERVER['HTTP_REFERER']);
        } elseif ($this->input->post('export') && get_option('gdpr_data_portability_leads') == '1') {
            $this->load->library('gdpr/gdpr_lead');
            $this->gdpr_lead->export($lead->id);
        } elseif ($this->input->post('removal_request')) {
            $success = $this->gdpr_model->add_removal_request([
                'description'  => nl2br($this->input->post('removal_description')),
                'request_from' => $lead->name,
                'lead_id'      => $lead->id,
            ]);
            if ($success) {
                send_gdpr_email_template('gdpr_removal_request_by_lead', $lead->id);
                set_alert('success', _l('data_removal_request_sent'));
            }
            redirect($_SERVER['HTTP_REFERER']);
        }

        $lead->attachments = $this->leads_model->get_lead_attachments($lead->id);
        $this->disableNavigation();
        $this->disableSubMenu();
        $data['title'] = $lead->name;
        $data['lead']  = $lead;
        $this->view('forms/lead');
        $this->data($data);
        $this->layout(true);
    }

    public function public_ticket($key)
    {
        $this->load->model('tickets_model');

        if (strlen($key) != 32) {
            show_error('Invalid ticket key.');
        }

        $ticket = $this->tickets_model->get_ticket_by_id($key);

        if (!$ticket) {
            show_404();
        }

        if (!is_client_logged_in() && $ticket->userid) {
            load_client_language($ticket->userid);
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('message', _l('ticket_reply'), 'required');

            if ($this->form_validation->run() !== false) {
                $replyData = ['message' => $this->input->post('message')];

                if ($ticket->userid && $ticket->contactid) {
                    $replyData['userid']    = $ticket->userid;
                    $replyData['contactid'] = $ticket->contactid;
                } else {
                    $replyData['name']  = $ticket->from_name;
                    $replyData['email'] = $ticket->ticket_email;
                }

                $replyid = $this->tickets_model->add_reply($replyData, $ticket->ticketid);

                if ($replyid) {
                    set_alert('success', _l('replied_to_ticket_successfully', $ticket->ticketid));
                }

                redirect(get_ticket_public_url($ticket));
            }
        }

        $data['title']          = $ticket->subject;
        $data['ticket_replies'] = $this->tickets_model->get_ticket_replies($ticket->ticketid);
        $data['ticket']         = $ticket;
        hooks()->add_action('app_customers_footer', 'ticket_public_form_customers_footer');
        $data['single_ticket_view'] = $this->load->view($this->createThemeViewPath('single_ticket'), $data, true);

        $navigationDisabled = hooks()->apply_filters('disable_navigation_on_public_ticket_view', true);
        if($navigationDisabled) {
            $this->disableNavigation();
        }

        $this->disableSubMenu();

        $this->data($data);

        $this->view('forms/public_ticket');
        no_index_customers_area();
        $this->layout(true);
    }

    public function ticket()
    {
        $form            = new stdClass();
        $form->language  = get_option('active_language');
        $form->recaptcha = 1;

        $this->lang->load($form->language . '_lang', $form->language);
        if (file_exists(APPPATH . 'language/' . $form->language . '/custom_lang.php')) {
            $this->lang->load('custom_lang', $form->language);
        }

        $form->success_submit_msg = _l('success_submit_msg');

        $form = hooks()->apply_filters('ticket_form_settings', $form);

        if ($this->input->post() && $this->input->is_ajax_request()) {
            $post_data = $this->input->post();

            $required = ['subject', 'department', 'email', 'name', 'message', 'priority'];

            if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_ticket_form') == 1) {
                $required[] = 'accept_terms_and_conditions';
            }

            foreach ($required as $field) {
                if (!isset($post_data[$field]) || isset($post_data[$field]) && empty($post_data[$field])) {
                    $this->output->set_status_header(422);
                    die;
                }
            }

            if (show_recaptcha() && $form->recaptcha == 1) {
                if (!do_recaptcha_validation($post_data['g-recaptcha-response'])) {
                    echo json_encode([
                            'success' => false,
                            'message' => _l('recaptcha_error'),
                            ]);
                    die;
                }
            }

            $post_data = [
                    'email'      => $post_data['email'],
                    'name'       => $post_data['name'],
                    'subject'    => $post_data['subject'],
                    'department' => $post_data['department'],
                    'priority'   => $post_data['priority'],
                    'service'    => isset($post_data['service']) && is_numeric($post_data['service'])
                    ? $post_data['service']
                    : null,
                    'custom_fields' => isset($post_data['custom_fields']) && is_array($post_data['custom_fields'])
                    ? $post_data['custom_fields']
                    : [],
                    'message' => $post_data['message'],
            ];

            $success = false;

            $this->db->where('email', $post_data['email']);
            $result = $this->db->get(db_prefix() . 'contacts')->row();

            if ($result) {
                $post_data['userid']    = $result->userid;
                $post_data['contactid'] = $result->id;
                unset($post_data['email']);
                unset($post_data['name']);
            }

            $this->load->model('tickets_model');

            $post_data = hooks()->apply_filters('ticket_external_form_insert_data', $post_data);
            $ticket_id = $this->tickets_model->add($post_data);

            if ($ticket_id) {
                $success = true;
            }

            if ($success == true) {
                hooks()->do_action('ticket_form_submitted', [
                        'ticket_id' => $ticket_id,
                     ]);
            }

            echo json_encode([
                    'success' => $success,
                    'message' => $form->success_submit_msg,
                    ]);

            die;
        }

        $this->load->model('tickets_model');
        $this->load->model('departments_model');
        $data['departments'] = $this->departments_model->get();
        $data['priorities']  = $this->tickets_model->get_priority();

        $data['priorities']['callback_translate'] = 'ticket_priority_translate';
        $data['services']                         = $this->tickets_model->get_service();

        $data['form'] = $form;
        $this->load->view('forms/ticket', $data);
    }
}
