<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Medicos extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoices_model');
        $this->load->model('credit_notes_model');
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Clients_model');
        $this->load->model('invoice_items_model');
        $this->load->model('appointly_model');
        $this->load->model('Centro_custo_model');
        $this->load->model('Indicadores_model'); 
    }
    
    /* List all clients */
    public function index()
    {
        if (!has_permission('customers', '', 'view')) {
            if (!have_assigned_customers() && !has_permission('customers', '', 'create')) {
                access_denied('customers');
            }
        }

         $data['title']          = _l('medicos');

       
        $this->load->view('admin/medicos/manage', $data);
    }

    public function table()
    {
        if (!has_permission('customers', '', 'view')) {
            if (!have_assigned_customers() && !has_permission('customers', '', 'create')) {
                ajax_access_denied();
            }
        }

        $this->app->get_table_data('medicos');
    }

    public function all_contacts()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('all_contacts');
        }

        if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') {
            $this->load->model('gdpr_model');
            $data['consent_purposes'] = $this->gdpr_model->get_consent_purposes();
        }

        $data['title'] = _l('customer_contacts');
        $this->load->view('admin/clients/all_contacts', $data);
    }

    /* Edit client or add new client*/
    public function medico($id = '')
    {
       
        
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }


        if ($this->input->post() && !$this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            
            if ($id == '') {
               // if (!has_permission('customers', '', 'create')) {
               //     access_denied('customers');
              //  }

                $data = $this->input->post();
                
                $id = $this->Medicos_model->add($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('medico')));
                    redirect(admin_url('medicos/medico/' . $id));
                }
            } else {
                if (!has_permission('customers', '', 'edit')) {
                   // if (!is_customer_admin($id)) {
                    //    access_denied('customers');
                  //  }
                }
                $success = $this->Medicos_model->update($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfully', _l('medico')));
                }
                redirect(admin_url('medicos/medico/' . $id));
            }
        }
     
        $group         = $this->input->get('group') ? $this->input->get('group') : 'profile_medico';
        $data['group'] = $group;

          

        // Customer groups
      
        if ($id == '') {
            $title = _l('add_new', _l('medico'));
        } else {
             
            $client                = $this->Medicos_model->get($id);
            $data['customer_tabs'] = get_medico_profile_tabs();
            if (!$client) {
                show_404();
            }
           // print_r($data['customer_tabs'][$group]); exit;
           // $data['contacts'] = $this->clients_model->get_contacts($id);
            $data['tab']      = isset($data['customer_tabs'][$group]) ? $data['customer_tabs'][$group] : null;
            
            
            if (!$data['tab']) {
             //   show_404();
            }
           
            // Fetch data based on groups
            if ($group == 'profile_medico') {
              //  $data['customer_groups'] = $this->clients_model->get_customer_groups($id);
              //  $data['customer_admins'] = $this->clients_model->get_admins($id);
            } elseif ($group == 'procedimento_medico') {
                //$pacienteid = $client['codpaciente'];
              
              //  $data['user_historico'] = $this->misc_model->get_historico(110, 'customer');
            } elseif ($group == 'procedimentos') {
            
                //$pacienteid = $client['codpaciente'];
               $data['medico_procedimentos'] = $this->misc_model->get_medico_procedimento($id);
               $data['items'] = $this->invoice_items_model->get_grouped_all_medicos($id);    
               $data['convenios']             = $this->Convenios_model->get();
                $data['categorias']            = $this->invoice_items_model->get_groups();
               $data['medicoid'] = $id;
            }elseif ($group == 'historico') {
               
                //$pacienteid = $client['codpaciente'];
                $data['user_historico'] = $this->misc_model->get_historico($id, 'customer'); 
            }elseif ($group == 'anamnese') {
               
                $data['anamnese'] = $this->misc_model->get_anamnese_medico($id); 
            } elseif ($group == 'palavras_chaves') {
                //$pacienteid = $client['codpaciente'];
                $data['user_palavras_chaves'] = $this->misc_model->get_palavras($id, 'customer');
                
            } elseif ($group == 'anotacoes') {
                $data['user_notes'] = $this->misc_model->get_notes($id, 'customer');
                
            } elseif ($group == 'agenda') {
                $this->load->model('invoices_model');
                $data['invoice_statuses'] = $this->invoices_model->get_statuses();
                $data['centro_custo']   = $this->Centro_custo_model->get();
            } 

          
            $data['medico'] = $client;
            $title          = $client->nome_profissional;

            // Get all active staff members (used to add reminder)
            $data['members'] = $data['staff'];

            if (!empty($data['client']->nome_profissional)) {
                // Check if is realy empty client company so we can set this field to empty
                // The query where fetch the client auto populate firstname and lastname if company is empty
                if (is_empty_customer_company($data['client']->medicoid)) {
                    $data['client']->nome_profissional = '';
                }
            }
        }

     //   echo 'aqui '.$group; exit;
        
        $data['bodyclass'] = 'customer-profile dynamic-create-groups';
        $data['title']     = $title;
        
        $this->load->view('admin/medicos/medico', $data);
    }

    public function table_medico_procedimento()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('medicos_procedimentos');
    }
    
    public function export($contact_id)
    {
        if (is_admin()) {
            $this->load->library('gdpr/gdpr_contact');
            $this->gdpr_contact->export($contact_id);
        }
    }

    // Used to give a tip to the user if the company exists when new company is created
    public function check_duplicate_customer_name()
    {
        if (has_permission('customers', '', 'create')) {
            $companyName = trim($this->input->post('company'));
            $response    = [
                'exists'  => (bool) total_rows(db_prefix() . 'clients', ['company' => $companyName]) > 0,
                'message' => _l('company_exists_info', '<b>' . $companyName . '</b>'),
            ];
            echo json_encode($response);
        }
    }

    public function save_longitude_and_latitude($client_id)
    {
        if (!has_permission('customers', '', 'edit')) {
            if (!is_customer_admin($client_id)) {
                ajax_access_denied();
            }
        }

        $this->db->where('userid', $client_id);
        $this->db->update(db_prefix() . 'clients', [
            'longitude' => $this->input->post('longitude'),
            'latitude'  => $this->input->post('latitude'),
        ]);
        if ($this->db->affected_rows() > 0) {
            echo 'success';
        } else {
            echo 'false';
        }
    }

    public function form_contact($customer_id, $contact_id = '')
    {
        if (!has_permission('customers', '', 'view')) {
            if (!is_customer_admin($customer_id)) {
                echo _l('access_denied');
                die;
            }
        }
        $data['customer_id'] = $customer_id;
        $data['contactid']   = $contact_id;
        if ($this->input->post()) {
            $data             = $this->input->post();
            $data['password'] = $this->input->post('password', false);

            unset($data['contactid']);
            if ($contact_id == '') {
                if (!has_permission('customers', '', 'create')) {
                    if (!is_customer_admin($customer_id)) {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
                        echo json_encode([
                            'success' => false,
                            'message' => _l('access_denied'),
                        ]);
                        die;
                    }
                }
                $id      = $this->clients_model->add_contact($data, $customer_id);
                $message = '';
                $success = false;
                if ($id) {
                    handle_contact_profile_image_upload($id);
                    $success = true;
                    $message = _l('added_successfully', _l('contact'));
                }
                echo json_encode([
                    'success'             => $success,
                    'message'             => $message,
                    'has_primary_contact' => (total_rows(db_prefix() . 'contacts', ['userid' => $customer_id, 'is_primary' => 1]) > 0 ? true : false),
                    'is_individual'       => is_empty_customer_company($customer_id) && total_rows(db_prefix() . 'contacts', ['userid' => $customer_id]) == 1,
                ]);
                die;
            }
            if (!has_permission('customers', '', 'edit')) {
                if (!is_customer_admin($customer_id)) {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
                    echo json_encode([
                            'success' => false,
                            'message' => _l('access_denied'),
                        ]);
                    die;
                }
            }
            $original_contact = $this->clients_model->get_contact($contact_id);
            $success          = $this->clients_model->update_contact($data, $contact_id);
            $message          = '';
            $proposal_warning = false;
            $original_email   = '';
            $updated          = false;
            if (is_array($success)) {
                if (isset($success['set_password_email_sent'])) {
                    $message = _l('set_password_email_sent_to_client');
                } elseif (isset($success['set_password_email_sent_and_profile_updated'])) {
                    $updated = true;
                    $message = _l('set_password_email_sent_to_client_and_profile_updated');
                }
            } else {
                if ($success == true) {
                    $updated = true;
                    $message = _l('updated_successfully', _l('contact'));
                }
            }
            if (handle_contact_profile_image_upload($contact_id) && !$updated) {
                $message = _l('updated_successfully', _l('contact'));
                $success = true;
            }
            if ($updated == true) {
                $contact = $this->clients_model->get_contact($contact_id);
                if (total_rows(db_prefix() . 'proposals', [
                        'rel_type' => 'customer',
                        'rel_id' => $contact->userid,
                        'email' => $original_contact->email,
                    ]) > 0 && ($original_contact->email != $contact->email)) {
                    $proposal_warning = true;
                    $original_email   = $original_contact->email;
                }
            }
            echo json_encode([
                    'success'             => $success,
                    'proposal_warning'    => $proposal_warning,
                    'message'             => $message,
                    'original_email'      => $original_email,
                    'has_primary_contact' => (total_rows(db_prefix() . 'contacts', ['userid' => $customer_id, 'is_primary' => 1]) > 0 ? true : false),
                ]);
            die;
        }
        if ($contact_id == '') {
            $title = _l('add_new', _l('contact_lowercase'));
        } else {
            $data['contact'] = $this->clients_model->get_contact($contact_id);

            if (!$data['contact']) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
                echo json_encode([
                    'success' => false,
                    'message' => 'Contact Not Found',
                ]);
                die;
            }
            $title = $data['contact']->firstname . ' ' . $data['contact']->lastname;
        }

        $data['customer_permissions'] = get_contact_permissions();
        $data['title']                = $title;
        $this->load->view('admin/clients/modals/contact', $data);
    }

    public function confirm_registration($client_id)
    {
        if (!is_admin()) {
            access_denied('Customer Confirm Registration, ID: ' . $client_id);
        }
        $this->clients_model->confirm_registration($client_id);
        set_alert('success', _l('customer_registration_successfully_confirmed'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function update_file_share_visibility()
    {
        if ($this->input->post()) {
            $file_id           = $this->input->post('file_id');
            $share_contacts_id = [];

            if ($this->input->post('share_contacts_id')) {
                $share_contacts_id = $this->input->post('share_contacts_id');
            }

            $this->db->where('file_id', $file_id);
            $this->db->delete(db_prefix() . 'shared_customer_files');

            foreach ($share_contacts_id as $share_contact_id) {
                $this->db->insert(db_prefix() . 'shared_customer_files', [
                    'file_id'    => $file_id,
                    'contact_id' => $share_contact_id,
                ]);
            }
        }
    }

    public function delete_contact_profile_image($contact_id)
    {
        $this->clients_model->delete_contact_profile_image($contact_id);
    }

    public function mark_as_active($id)
    {
        $this->db->where('userid', $id);
        $this->db->update(db_prefix() . 'clients', [
            'active' => 1,
        ]);
        redirect(admin_url('clients/client/' . $id));
    }

    public function consents($id)
    {
        if (!has_permission('customers', '', 'view')) {
            if (!is_customer_admin(get_user_id_by_contact_id($id))) {
                echo _l('access_denied');
                die;
            }
        }

        $this->load->model('gdpr_model');
        $data['purposes']   = $this->gdpr_model->get_consent_purposes($id, 'contact');
        $data['consents']   = $this->gdpr_model->get_consents(['contact_id' => $id]);
        $data['contact_id'] = $id;
        $this->load->view('admin/gdpr/contact_consent', $data);
    }

    public function update_all_proposal_emails_linked_to_customer($contact_id)
    {
        $success = false;
        $email   = '';
        if ($this->input->post('update')) {
            $this->load->model('proposals_model');

            $this->db->select('email,userid');
            $this->db->where('id', $contact_id);
            $contact = $this->db->get(db_prefix() . 'contacts')->row();

            $proposals = $this->proposals_model->get('', [
                'rel_type' => 'customer',
                'rel_id'   => $contact->userid,
                'email'    => $this->input->post('original_email'),
            ]);
            $affected_rows = 0;

            foreach ($proposals as $proposal) {
                $this->db->where('id', $proposal['id']);
                $this->db->update(db_prefix() . 'proposals', [
                    'email' => $contact->email,
                ]);
                if ($this->db->affected_rows() > 0) {
                    $affected_rows++;
                }
            }

            if ($affected_rows > 0) {
                $success = true;
            }
        }
        echo json_encode([
            'success' => $success,
            'message' => _l('proposals_emails_updated', [
                _l('contact_lowercase'),
                $contact->email,
            ]),
        ]);
    }

    public function assign_admins($id)
    {
        if (!has_permission('customers', '', 'create') && !has_permission('customers', '', 'edit')) {
            access_denied('customers');
        }
        $success = $this->clients_model->assign_admins($this->input->post(), $id);
        if ($success == true) {
            set_alert('success', _l('updated_successfully', _l('client')));
        }

        redirect(admin_url('clients/client/' . $id . '?tab=customer_admins'));
    }

    public function delete_customer_admin($customer_id, $staff_id)
    {
        if (!has_permission('customers', '', 'create') && !has_permission('customers', '', 'edit')) {
            access_denied('customers');
        }

        $this->db->where('customer_id', $customer_id);
        $this->db->where('staff_id', $staff_id);
        $this->db->delete(db_prefix() . 'customer_admins');
        redirect(admin_url('clients/client/' . $customer_id) . '?tab=customer_admins');
    }

    public function delete_contact($customer_id, $id)
    {
        if (!has_permission('customers', '', 'delete')) {
            if (!is_customer_admin($customer_id)) {
                access_denied('customers');
            }
        }
        $contact      = $this->clients_model->get_contact($id);
        $hasProposals = false;
        if ($contact && is_gdpr()) {
            if (total_rows(db_prefix() . 'proposals', ['email' => $contact->email]) > 0) {
                $hasProposals = true;
            }
        }

        $this->clients_model->delete_contact($id);
        if ($hasProposals) {
            $this->session->set_flashdata('gdpr_delete_warning', true);
        }
        redirect(admin_url('clients/client/' . $customer_id . '?group=contacts'));
    }

    public function contacts($client_id)
    {
        $this->app->get_table_data('contacts', [
            'client_id' => $client_id,
        ]);
    }

    public function upload_attachment($id)
    {
        handle_client_attachments_upload($id);
    }

    public function add_external_attachment()
    {
        if ($this->input->post()) {
            $this->misc_model->add_attachment_to_database($this->input->post('clientid'), 'customer', $this->input->post('files'), $this->input->post('external'));
        }
    }

    public function delete_attachment($customer_id, $id)
    {
        if (has_permission('customers', '', 'delete') || is_customer_admin($customer_id)) {
            $this->clients_model->delete_attachment($id);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /* Delete client */
    public function delete($id)
    {
        if (!has_permission('customers', '', 'delete')) {
            access_denied('customers');
        }
        if (!$id) {
            redirect(admin_url('clients'));
        }
        $response = $this->clients_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('customer_delete_transactions_warning', _l('invoices') . ', ' . _l('estimates') . ', ' . _l('credit_notes')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('client')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('client_lowercase')));
        }
        redirect(admin_url('medicos/medico/' . $id));
    }

    /* Staff can login as client */
    public function login_as_client($id)
    {
        if (is_admin()) {
            login_as_client($id);
        }
        hooks()->do_action('after_contact_login');
        redirect(site_url());
    }

    public function get_customer_billing_and_shipping_details($id)
    {
        echo json_encode($this->clients_model->get_customer_billing_and_shipping_details($id));
    }

    /* Change client status / active / inactive */
    public function change_contact_status($id, $status)
    {
        if (has_permission('customers', '', 'edit') || is_customer_admin(get_user_id_by_contact_id($id))) {
            if ($this->input->is_ajax_request()) {
                $this->clients_model->change_contact_status($id, $status);
            }
        }
    }

    /* Change client status / active / inactive */
    public function change_client_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->clients_model->change_client_status($id, $status);
        }
    }

    /* Zip function for credit notes */
    public function zip_credit_notes($id)
    {
        $has_permission_view = has_permission('credit_notes', '', 'view');

        if (!$has_permission_view && !has_permission('credit_notes', '', 'view_own')) {
            access_denied('Zip Customer Credit Notes');
        }

        if ($this->input->post()) {
            $this->load->library('app_bulk_pdf_export', [
                'export_type'       => 'credit_notes',
                'status'            => $this->input->post('credit_note_zip_status'),
                'date_from'         => $this->input->post('zip-from'),
                'date_to'           => $this->input->post('zip-to'),
                'redirect_on_error' => admin_url('clients/client/' . $id . '?group=credit_notes'),
            ]);

            $this->app_bulk_pdf_export->set_client_id($id);
            $this->app_bulk_pdf_export->in_folder($this->input->post('file_name'));
            $this->app_bulk_pdf_export->export();
        }
    }

    public function zip_invoices($id)
    {
        $has_permission_view = has_permission('invoices', '', 'view');
        if (!$has_permission_view && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('Zip Customer Invoices');
        }

        if ($this->input->post()) {
            $this->load->library('app_bulk_pdf_export', [
                'export_type'       => 'invoices',
                'status'            => $this->input->post('invoice_zip_status'),
                'date_from'         => $this->input->post('zip-from'),
                'date_to'           => $this->input->post('zip-to'),
                'redirect_on_error' => admin_url('clients/client/' . $id . '?group=invoices'),
            ]);

            $this->app_bulk_pdf_export->set_client_id($id);
            $this->app_bulk_pdf_export->in_folder($this->input->post('file_name'));
            $this->app_bulk_pdf_export->export();
        }
    }

    /* Since version 1.0.2 zip client estimates */
    public function zip_estimates($id)
    {
        $has_permission_view = has_permission('estimates', '', 'view');
        if (!$has_permission_view && !has_permission('estimates', '', 'view_own')
            && get_option('allow_staff_view_estimates_assigned') == '0') {
            access_denied('Zip Customer Estimates');
        }

        if ($this->input->post()) {
            $this->load->library('app_bulk_pdf_export', [
                'export_type'       => 'estimates',
                'status'            => $this->input->post('estimate_zip_status'),
                'date_from'         => $this->input->post('zip-from'),
                'date_to'           => $this->input->post('zip-to'),
                'redirect_on_error' => admin_url('clients/client/' . $id . '?group=estimates'),
            ]);

            $this->app_bulk_pdf_export->set_client_id($id);
            $this->app_bulk_pdf_export->in_folder($this->input->post('file_name'));
            $this->app_bulk_pdf_export->export();
        }
    }

    public function zip_payments($id)
    {
        $has_permission_view = has_permission('payments', '', 'view');

        if (!$has_permission_view && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('Zip Customer Payments');
        }

        $this->load->library('app_bulk_pdf_export', [
                'export_type'       => 'payments',
                'payment_mode'      => $this->input->post('paymentmode'),
                'date_from'         => $this->input->post('zip-from'),
                'date_to'           => $this->input->post('zip-to'),
                'redirect_on_error' => admin_url('clients/client/' . $id . '?group=payments'),
            ]);

        $this->app_bulk_pdf_export->set_client_id($id);
        $this->app_bulk_pdf_export->set_client_id_column(db_prefix() . 'clients.userid');
        $this->app_bulk_pdf_export->in_folder($this->input->post('file_name'));
        $this->app_bulk_pdf_export->export();
    }

    public function import()
    {
        if (!has_permission('customers', '', 'create')) {
            access_denied('customers');
        }

        $dbFields = $this->db->list_fields(db_prefix() . 'contacts');
        foreach ($dbFields as $key => $contactField) {
            if ($contactField == 'phonenumber') {
                $dbFields[$key] = 'contact_phonenumber';
            }
        }

        $dbFields = array_merge($dbFields, $this->db->list_fields(db_prefix() . 'clients'));

        $this->load->library('import/import_customers', [], 'import');

        $this->import->setDatabaseFields($dbFields)
                     ->setCustomFields(get_custom_fields('customers'));

        if ($this->input->post('download_sample') === 'true') {
            $this->import->downloadSample();
        }

        if ($this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
            $this->import->setSimulation($this->input->post('simulate'))
                          ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                          ->setFilename($_FILES['file_csv']['name'])
                          ->perform();


            $data['total_rows_post'] = $this->import->totalRows();

            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
            }
        }

        $data['groups']    = $this->clients_model->get_groups();
        $data['title']     = _l('import');
        $data['bodyclass'] = 'dynamic-create-groups';
        $this->load->view('admin/clients/import', $data);
    }

   
    public function bulk_action()
    {
        hooks()->do_action('before_do_bulk_action_for_customers');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids    = $this->input->post('ids');
            $groups = $this->input->post('groups');

            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_delete')) {
                        if ($this->clients_model->delete($id)) {
                            $total_deleted++;
                        }
                    } else {
                        if (!is_array($groups)) {
                            $groups = false;
                        }
                        $this->client_groups_model->sync_customer_groups($id, $groups);
                    }
                }
            }
        }

        if ($this->input->post('mass_delete')) {
            set_alert('success', _l('total_clients_deleted', $total_deleted));
        }
    }

    public function vault_entry_create($customer_id)
    {
        $data = $this->input->post();

        if (isset($data['fakeusernameremembered'])) {
            unset($data['fakeusernameremembered']);
        }

        if (isset($data['fakepasswordremembered'])) {
            unset($data['fakepasswordremembered']);
        }

        unset($data['id']);
        $data['creator']      = get_staff_user_id();
        $data['creator_name'] = get_staff_full_name($data['creator']);
        $data['description']  = nl2br($data['description']);
        $data['password']     = $this->encryption->encrypt($this->input->post('password', false));

        if (empty($data['port'])) {
            unset($data['port']);
        }

        $this->clients_model->vault_entry_create($data, $customer_id);
        set_alert('success', _l('added_successfully', _l('vault_entry')));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function vault_entry_update($entry_id)
    {
        $entry = $this->clients_model->get_vault_entry($entry_id);

        if ($entry->creator == get_staff_user_id() || is_admin()) {
            $data = $this->input->post();

            if (isset($data['fakeusernameremembered'])) {
                unset($data['fakeusernameremembered']);
            }
            if (isset($data['fakepasswordremembered'])) {
                unset($data['fakepasswordremembered']);
            }

            $data['last_updated_from'] = get_staff_full_name(get_staff_user_id());
            $data['description']       = nl2br($data['description']);

            if (!empty($data['password'])) {
                $data['password'] = $this->encryption->encrypt($this->input->post('password', false));
            } else {
                unset($data['password']);
            }

            if (empty($data['port'])) {
                unset($data['port']);
            }

            $this->clients_model->vault_entry_update($entry_id, $data);
            set_alert('success', _l('updated_successfully', _l('vault_entry')));
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function vault_entry_delete($id)
    {
        $entry = $this->clients_model->get_vault_entry($id);
        if ($entry->creator == get_staff_user_id() || is_admin()) {
            $this->clients_model->vault_entry_delete($id);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function vault_encrypt_password()
    {
        $id            = $this->input->post('id');
        $user_password = $this->input->post('user_password', false);
        $user          = $this->staff_model->get(get_staff_user_id());

        if (!app_hasher()->CheckPassword($user_password, $user->password)) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['error_msg' => _l('vault_password_user_not_correct')]);
            die;
        }

        $vault    = $this->clients_model->get_vault_entry($id);
        $password = $this->encryption->decrypt($vault->password);

        $password = html_escape($password);

        // Failed to decrypt
        if (!$password) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
            echo json_encode(['error_msg' => _l('failed_to_decrypt_password')]);
            die;
        }

        echo json_encode(['password' => $password]);
    }

    public function get_vault_entry($id)
    {
        $entry = $this->clients_model->get_vault_entry($id);
        unset($entry->password);
        $entry->description = clear_textarea_breaks($entry->description);
        echo json_encode($entry);
    }

    public function statement_pdf()
    {
        $customer_id = $this->input->get('customer_id');

        if (!has_permission('invoices', '', 'view') && !has_permission('payments', '', 'view')) {
            set_alert('danger', _l('access_denied'));
            redirect(admin_url('clients/client/' . $customer_id));
        }

        $from = $this->input->get('from');
        $to   = $this->input->get('to');

        $data['statement'] = $this->clients_model->get_statement($customer_id, to_sql_date($from), to_sql_date($to));

        try {
            $pdf = statement_pdf($data['statement']);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';
        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(slug_it(_l('customer_statement') . '-' . $data['statement']['client']->company) . '.pdf', $type);
    }

    public function send_statement()
    {
        $customer_id = $this->input->get('customer_id');

        if (!has_permission('invoices', '', 'view') && !has_permission('payments', '', 'view')) {
            set_alert('danger', _l('access_denied'));
            redirect(admin_url('clients/client/' . $customer_id));
        }

        $from = $this->input->get('from');
        $to   = $this->input->get('to');

        $send_to = $this->input->post('send_to');
        $cc      = $this->input->post('cc');

        $success = $this->clients_model->send_statement_to_email($customer_id, $send_to, $from, $to, $cc);
        // In case client use another language
        load_admin_language();
        if ($success) {
            set_alert('success', _l('statement_sent_to_client_success'));
        } else {
            set_alert('danger', _l('statement_sent_to_client_fail'));
        }

        redirect(admin_url('clients/client/' . $customer_id . '?group=statement'));
    }

    public function statement()
    {
        if (!has_permission('invoices', '', 'view') && !has_permission('payments', '', 'view')) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad error');
            echo _l('access_denied');
            die;
        }

        $customer_id = $this->input->get('customer_id');
        $from        = $this->input->get('from');
        $to          = $this->input->get('to');

        $data['statement'] = $this->clients_model->get_statement($customer_id, to_sql_date($from), to_sql_date($to));

        $data['from'] = $from;
        $data['to']   = $to;

        $viewData['html'] = $this->load->view('admin/clients/groups/_statement', $data, true);

        echo json_encode($viewData);
    }
    
    /*
     * PARAMETRIZAÇÃO DA AGENDA
     */
    
     public function horario_agenda_medico()
    {
   
        
        if ($this->input->is_ajax_request()) {
            $this->load->model('appointly_model');
            
            $custom_date_select = $this->get_where_report_period(db_prefix() . 'appointly_appointments.date');
           
            $aColumns = [
                        db_prefix() . 'appointly_appointments.date as date',
                        db_prefix() . 'appointly_appointments.tempo_consulta as tempo_consulta',
                        db_prefix() . 'appointly_appointments.start_hour as start_hour',
                        db_prefix() . 'appointly_appointments.consultorio_id as consultorio_id',
                       ];
           // }
            
            $sIndexColumn = 'id';
            $sTable       = db_prefix() . 'appointly_appointments';
            $join         = [''];
            
            $medicoid = $this->input->post('medicoid');
            $where = [' AND '.db_prefix() . 'appointly_appointments.medico_id = '.$medicoid]; //' AND '.db_prefix() . 'invoices.status IN (2)',    fat pagas

            
            if ($custom_date_select != '') {
                array_push($where, $custom_date_select);
            }
            
            
            
          
            
            /*
            // FILTRO CONVÊNIOS
            if ($this->input->post('convenios')) {
                $convenios = $this->input->post('convenios');
                $_convenios = [];
                if (is_array($convenios)) {
                    foreach ($convenios as $convenio) {
                        if ($convenio != '') {
                            array_push($_convenios, $this->db->escape_str($convenio));
                        }
                    }
                }
                if (count($_convenios) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'invoices.convenio IN (' . implode(', ', $_convenios) . ')');
                }
            }
            
            // FILTRO CATEGORIAS
            if ($this->input->post('categorias')) {
                $categorias = $this->input->post('categorias');
                $_categorias = [];
                if (is_array($categorias)) {
                    foreach ($categorias as $categoriaa) {
                        if ($categoriaa != '') {
                            array_push($_categorias, $this->db->escape_str($categoriaa));
                        }
                    }
                }
                if (count($_categorias) > 0) {
                    array_push($where, ' AND '. db_prefix() . 'items_groups.id IN (' . implode(', ', $_categorias) . ')');
                }
            } */
            
           
        
           // $GroupBy = 'GROUP BY tblappointly_appointments.date';
            $GroupBy = "";
            $orderby = "Order by start_hour, date asc ";
            $result = data_tables_init_order($aColumns, $sIndexColumn, $sTable, $join, $where, [],$GroupBy, $orderby);
            //  $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [], 'GROUP by ' . db_prefix() . 'invoicepaymentrecords.invoiceid');   EXEMPLO COM GROUP BY
            $output  = $result['output'];
            $rResult = $result['rResult'];

            $footer_data = [];

            foreach ($rResult as $aRow) {
                $row = [];
             //   $_data_invoice = '<a href="' . admin_url('invoices/list_invoices/' . $aRow['number']) . '" target="_blank">' . format_invoice_number($aRow['number']) . '</a>';
                $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado');

                // Aqui podemos usar a data atual ou qualquer outra data no formato Ano-mês-dia (2014-02-28)
                $data_agenda = $aRow['date'];

                // Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
                $diasemana_numero = date('w', strtotime($data_agenda));

                // Exibe o dia da semana com o Array
                
                
                $row[] = _d($aRow['date']); 
                $row[] = $diasemana[$diasemana_numero];
                $row[] = $aRow['start_hour'];
                $row[] = $aRow['consultorio_id'];
                $row[] = $aRow['tempo_consulta'];
                
                $output['aaData'][] = $row;
            }
           // print_r($row); exit;
          
            echo json_encode($output);
            die();
        }
    }
    
     private function get_where_report_period($field = 'date')
    {
        $months_report      = $this->input->post('report_months');
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $this->db->escape_str($from_date) . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '")';
                }
            }
        }

        return $custom_date_select;
    }
    
    
    
    /***************************************************************************
     ************************ MENU PRODUÇÃO ************************************
     **************************************************************************/
    
    public function producao_medica()
    {
        
        close_setup_menu();
        
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('Invoices_model');
        
        $data['medicos']               = $this->Medicos_model->get();
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        $data['title']                 = 'Produção Médica';
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/medicos/producao/producao_medico', $data);
    }
    
    public function retorno_producao_medico()
    {
        
        $medicos_faturamento = $this->input->post("medicos_faturamento");
       // $convenios_faturamento = $this->input->post("convenios_faturamento");
       // $categorias_faturamento = $this->input->post("categorias_faturamento");
       // $procedimento_faturamento = $this->input->post("procedimento_faturamento");
        $data_de = $this->input->post("data_de");
        $data_ate = $this->input->post("data_ate");
        
      // echo $data_de.'<Br>';
       //echo $data_ate.'<Br>';
       
       //exit;
        /*
         * MEDICOS
        */
        $where_medico = "";
        $total = count($medicos_faturamento);
        $cont = 1;
        foreach ($medicos_faturamento as $medico){
            if($cont == $total){
                $where_medico .= "$medico";
            }else{
                $where_medico .= "$medico,";
            }
         $cont++;   
        }
       
       // $medicos = $this->Medicos_model->get_where_in($where_medico);
       
        /*
         * CONVENIOS
         
        $where_convenio = "";
        $total_convenio = count($convenios_faturamento);
        $cont_conv = 1;
        foreach ($convenios_faturamento as $convenio){
            if($cont_conv == $total_convenio){
                $where_convenio .= "$convenio";
            }else{
                $where_convenio .= "$convenio,";
            }
         $cont_conv++;   
        }
       
        /*
         * CATEGORIAS
         
        $where_categoria = "";
        $total_categoria = count($categorias_faturamento);
        $cont_cat = 1;
        foreach ($categorias_faturamento as $categoria){
             if($cont_cat == $total_categoria){
                $where_categoria .= "$categoria";
            }else{
                $where_categoria .= "$categoria,";
            }
         $cont_cat++;   
        }
         */
       
      // $data['medicos']   = $medicos;
      //  $data['convenios_faturamento']   = $where_convenio;
      //  $data['categorias_faturamento']   = $where_categoria;
      //  $data['procedimento_faturamento']   = $procedimento_faturamento;
      //  $data['medicos_faturamento']   = $where_medico;
        $data['data_de']   = $data_de;
        $data['data_ate']   = $data_ate;
        
        /**********************************************************************/
        
        $this->load->model('Indicadores_model');
        
        /*
         * 1- TOTAL DE ATENDIMENTOS NO PERÍODO
         */
        $total_atendidos = $this->Indicadores_model->get_total_atendidos($data_de, $data_ate);
        $data['total_atendidos'] = $total_atendidos;
        
        /*
         * 2- TOTAL DE VALOR EM REPASSE
         */
        $total_atendimento = $this->Indicadores_model->get_total_em_atendimentos($data_de, $data_ate);
        $data['total_atendimento'] = $total_atendimento;
        
        /*
         * 3- TOTAL DE PROCEDIMENTOS NÃO PARAMETRIZADOS
         */
        $total_falta = $this->Indicadores_model->get_total_falta($data_de, $data_ate);
        $data['total_falta'] = $total_falta;
        
         
        /*
         * 4- LISTA ATENDIMENTO
         */
        
        $atendimentos = $this->Indicadores_model->get_todos_atendimento_producao($where_medico, $data_de, $data_ate);
        $data['atendimentos'] = $atendimentos;
        
        $this->load->view('admin/dashboard/medicos/producao/retorno_producao_medico', $data);
       
    }
    
    /**************************************************************************/
    
   
    
    
    /***************************************************************************
     ************************ MENU AGENDA PRODUÇÃO *****************************
     **************************************************************************/
    
    public function agenda_medica()
    {
        close_setup_menu();
        
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('Invoices_model');
        
        $data['medicos']               = $this->Medicos_model->get();
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        $data['title']                 = 'Agenda Médica';
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/medicos/agenda/agenda_medico', $data);
    }
    
    public function retorno_agenda_medico()
    {
        $medicos_faturamento = $this->input->post("medicos_faturamento");
       // $convenios_faturamento = $this->input->post("convenios_faturamento");
       // $categorias_faturamento = $this->input->post("categorias_faturamento");
       // $procedimento_faturamento = $this->input->post("procedimento_faturamento");
        $data_de = $this->input->post("data_de");
        $data_ate = $this->input->post("data_ate");
        
      // echo $data_de.'<Br>';
       //echo $data_ate.'<Br>';
       
       //exit;
        /*
         * MEDICOS
        */
        $where_medico = "";
        $total = count($medicos_faturamento);
        $cont = 1;
        foreach ($medicos_faturamento as $medico){
            if($cont == $total){
                $where_medico .= "$medico";
            }else{
                $where_medico .= "$medico,";
            }
         $cont++;   
        }
       
       // $medicos = $this->Medicos_model->get_where_in($where_medico);
       
        /*
         * CONVENIOS
         
        $where_convenio = "";
        $total_convenio = count($convenios_faturamento);
        $cont_conv = 1;
        foreach ($convenios_faturamento as $convenio){
            if($cont_conv == $total_convenio){
                $where_convenio .= "$convenio";
            }else{
                $where_convenio .= "$convenio,";
            }
         $cont_conv++;   
        }
       
        /*
         * CATEGORIAS
         
        $where_categoria = "";
        $total_categoria = count($categorias_faturamento);
        $cont_cat = 1;
        foreach ($categorias_faturamento as $categoria){
             if($cont_cat == $total_categoria){
                $where_categoria .= "$categoria";
            }else{
                $where_categoria .= "$categoria,";
            }
         $cont_cat++;   
        }
         */
       
      // $data['medicos']   = $medicos;
      //  $data['convenios_faturamento']   = $where_convenio;
      //  $data['categorias_faturamento']   = $where_categoria;
      //  $data['procedimento_faturamento']   = $procedimento_faturamento;
      //  $data['medicos_faturamento']   = $where_medico;
        $data['data_de']   = $data_de;
        $data['data_ate']   = $data_ate;
        
        /**********************************************************************/
        
        $this->load->model('Indicadores_model');
         
        /*
         * 4- LISTA ATENDIMENTO
         */
       
        $atendimentos = $this->Indicadores_model->get_todos_agendamento_medico($where_medico, $data_de, $data_ate);
        $data['atendimentos'] = $atendimentos;
        
        
        
        
        $this->load->view('admin/dashboard/medicos/agenda/retorno_agenda_medico', $data);
       
    }
    
    /**************************************************************************/
    
    public function agenda_medica_individual($medico_id)
    {
        close_setup_menu();
        
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('Invoices_model');
        
        $data['medico_id']             = $medico_id;
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->Invoice_items_model->get_groups();
        $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        $data['title']                 = 'Agenda Médica';
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/portal_medico/agenda/agenda_medico', $data);
    }
    
    public function retorno_agenda_medico_individual()
    {
        $medicos_faturamento = $this->input->post("medicos_faturamento");
       // $convenios_faturamento = $this->input->post("convenios_faturamento");
       // $categorias_faturamento = $this->input->post("categorias_faturamento");
       // $procedimento_faturamento = $this->input->post("procedimento_faturamento");
        $data_de = $this->input->post("data_de");
        $data_ate = $this->input->post("data_ate");
        
       
        /*
         * MEDICOS
        */
        $where_medico = $medicos_faturamento;
        
       
       // $medicos = $this->Medicos_model->get_where_in($where_medico);
       
        /*
         * CONVENIOS
         
        $where_convenio = "";
        $total_convenio = count($convenios_faturamento);
        $cont_conv = 1;
        foreach ($convenios_faturamento as $convenio){
            if($cont_conv == $total_convenio){
                $where_convenio .= "$convenio";
            }else{
                $where_convenio .= "$convenio,";
            }
         $cont_conv++;   
        }
       
        /*
         * CATEGORIAS
         
        $where_categoria = "";
        $total_categoria = count($categorias_faturamento);
        $cont_cat = 1;
        foreach ($categorias_faturamento as $categoria){
             if($cont_cat == $total_categoria){
                $where_categoria .= "$categoria";
            }else{
                $where_categoria .= "$categoria,";
            }
         $cont_cat++;   
        }
         */
       
      // $data['medicos']   = $medicos;
      //  $data['convenios_faturamento']   = $where_convenio;
      //  $data['categorias_faturamento']   = $where_categoria;
      //  $data['procedimento_faturamento']   = $procedimento_faturamento;
      //  $data['medicos_faturamento']   = $where_medico;
        $data['data_de']   = $data_de;
        $data['data_ate']   = $data_ate;
        
        /**********************************************************************/
        
        $this->load->model('Indicadores_model');
         
        /*
         * 4- LISTA ATENDIMENTO
         */
       
        $atendimentos = $this->Indicadores_model->get_todos_agendamento_medico($where_medico, $data_de, $data_ate);
        $data['atendimentos'] = $atendimentos;
        
        
        
        
        $this->load->view('admin/dashboard/portal_medico/agenda/retorno_agenda_medico', $data);
       
    }
    
    /**************************************************************************/
    
    public function producao_medica_individual($medico_id)
    {
        close_setup_menu();
        
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Invoice_items_model');
        $this->load->model('Invoices_model');
        
        $data['medico_id']             = $medico_id;
       // $data['convenios']             = $this->Convenios_model->get();
       // $data['categorias']            = $this->Invoice_items_model->get_groups();
       // $data['procedimentos']         = $this->Invoice_items_model->get_ativos();
        $data['title']                 = 'Produção Médica';
        //$data = hooks()->apply_filters('before_dashboard_render', $data);
        $this->load->view('admin/dashboard/portal_medico/producao/producao_medico', $data);
    }
    
    public function retorno_producao_medico_individual()
    {
        $medicos_faturamento = $this->input->post("medicos_faturamento");
        $data_de = $this->input->post("data_de");
        $data_ate = $this->input->post("data_ate");
        
      
       //exit;
        /*
         * MEDICOS
        */
        $where_medico = $medicos_faturamento;
        $data['data_de']   = $data_de;
        $data['data_ate']   = $data_ate;
        
        /**********************************************************************/
        
        
        
       
        /*
         * 1- TOTAL DE ATENDIMENTOS NO PERÍODO
         */
        $total_atendidos = $this->Indicadores_model->get_total_atendidos_medico($where_medico, $data_de, $data_ate);
        $data['total_atendidos'] = $total_atendidos;
        
         
        /*
         * 2- TOTAL DE VALOR EM REPASSE
         */
        $total_atendimento = $this->Indicadores_model->get_total_em_atendimentos_medico($where_medico, $data_de, $data_ate);
        $data['total_atendimento'] = $total_atendimento;
        
        /*
         * 3- TOTAL DE PROCEDIMENTOS NÃO PARAMETRIZADOS
         */
        $total_falta = $this->Indicadores_model->get_total_falta($data_de, $data_ate);
        $data['total_falta'] = $total_falta;
        
         
        /*
         * 4- LISTA ATENDIMENTO
         */
       
        $atendimentos = $this->Indicadores_model->get_todos_atendimento_producao($where_medico, $data_de, $data_ate);
        $data['atendimentos'] = $atendimentos;
        
        
       
        
        $this->load->view('admin/dashboard/portal_medico/producao/retorno_producao_medico', $data);
       
    }
    
}
