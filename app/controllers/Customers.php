<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Customer || $this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('customers', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
         $this->load->model('auth_model');
        $this->load->library('ion_auth');
        $this->load->model('atas_model');
        $this->load->model('projetos_model');
        $this->load->model('site');
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('customers')));
        $meta = array('page_title' => lang('customers'), 'bc' => $bc);
        $this->page_construct('customers/index', $meta, $this->data);
    }

    function getCustomers()
    {
        $this->sma->checkPermissions('index');
        $this->load->library('datatables');
        $this->datatables
            ->select("id, company, name, email, phone, city, customer_group_name, vat_no, deposit_amount, award_points")
            ->from("companies")
            ->where('group_name', 'customer')
            ->add_column("Actions", "<div class=\"text-center\"> "
                    . "<a class=\"tip\" title='" . lang("list_deposits") . "' href='" . site_url('customers/deposits/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-money\"></i></a> "
                    . "<a class=\"tip\" title='" . lang("edit_customer") . "' href='" . site_url('customers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_customer") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();
    }

    function view($id = NULL)
    {
        $this->sma->checkPermissions('index', true);
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['customer'] = $this->companies_model->getCompanyByID($id);
        $this->load->view($this->theme.'customers/view',$this->data);
    }

    function add()
    {
        $this->sma->checkPermissions(false, true);

        //$this->form_validation->set_rules('email', lang("email_address"), 'is_unique[companies.email]');
         $this->form_validation->set_rules('company', lang("company"), 'required');
         $this->form_validation->set_rules('name', lang("name"), 'required');
         
         
        if ($this->form_validation->run('companies/add') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
            $ie = $this->input->post('cf1');
            
            if($ie){
                $indicacaoCliente = '1';
            }else{
                $indicacaoCliente = '9';
            }
    
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->input->post('customer_group'),
                'customer_group_name' => $cg->name,
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'cf1' => $this->input->post('cf1'),  //INSCRIÇÃO ESTADUAL
                'cf2' => $this->input->post('cf2'),   // NUMERO
                'cf3' => $this->input->post('cf3'),   //COMPLEMENTO
                'cf4' => $this->input->post('cf4'),   //TIPO DE PESSOA
                'cf5' => $this->input->post('cf5'),   //BAIRRO
                'cf6' => $this->input->post('cf6'),   // MARKETING COMO FICOU SABENDO
                'indicacaoCliente' => $indicacaoCliente,
                'codigoMunicipio' => '1302603',
            );
        } elseif ($this->input->post('add_customer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $cid = $this->companies_model->addCompany($data)) {
            $this->session->set_flashdata('message', lang("customer_added"));
            $ref = isset($_SERVER["HTTP_REFERER"]) ? explode('?', $_SERVER["HTTP_REFERER"]) : NULL;
            redirect($ref[0] . '?customer=' . $cid);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->load->view($this->theme . 'customers/add', $this->data);
        }
    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions(false, true);
        $this->form_validation->set_rules('company', lang("company"), 'required');
         $this->form_validation->set_rules('name', lang("name"), 'required');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $company_details = $this->companies_model->getCompanyByID($id);
        if ($this->input->post('email') != $company_details->email) {
            $this->form_validation->set_rules('code', lang("email_address"), 'is_unique[companies.email]');
        }

        if ($this->form_validation->run('companies/add') == true) {
            $cg = $this->site->getCustomerGroupByID($this->input->post('customer_group'));
               $ie = $this->input->post('cf1');
            
            if($ie){
                $indicacaoCliente = '1';
            }else{
                $indicacaoCliente = '9';
            }
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => '3',
                'group_name' => 'customer',
                'customer_group_id' => $this->input->post('customer_group'),
                'customer_group_name' => $cg->name,
                'company' => $this->input->post('company'),
                'address' => $this->input->post('address'),
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'award_points' => $this->input->post('award_points'),
                'indicacaoCliente' => $indicacaoCliente,
                'codigoMunicipio' => '1302603',
            );
        } elseif ($this->input->post('edit_customer')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateCompany($id, $data)) {
            $this->session->set_flashdata('message', lang("customer_updated"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->data['customer'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['customer_groups'] = $this->companies_model->getAllCustomerGroups();
            $this->load->view($this->theme . 'customers/edit', $this->data);
        }
    }

    function users($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }


        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
        $this->data['users'] = $this->companies_model->getCompanyUsers($company_id);
        $this->load->view($this->theme . 'customers/users', $this->data);

    }

    function add_user($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        $this->form_validation->set_rules('email', lang("email_address"), 'is_unique[users.email]');
        $this->form_validation->set_rules('password', lang('password'), 'required|min_length[8]|max_length[20]|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', lang('confirm_password'), 'required');

        if ($this->form_validation->run('companies/add_user') == true) {
            $active = $this->input->post('status');
            $notify = $this->input->post('notify');
            list($username, $domain) = explode("@", $this->input->post('email'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'gender' => $this->input->post('gender'),
                'company_id' => $company->id,
                'company' => $company->company,
                'group_id' => 3
            );
            $this->load->library('ion_auth');
        } elseif ($this->input->post('add_user')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $active, $notify)) {
            $this->session->set_flashdata('message', lang("user_added"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'customers/add_user', $this->data);
        }
    }

    function import_csv()
    {
        $this->sma->checkPermissions();
        $this->load->helper('security');
        $this->form_validation->set_rules('csv_file', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if (isset($_FILES["csv_file"])) /* if($_FILES['userfile']['size'] > 0) */ {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '2000';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('csv_file')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("customers");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("assets/uploads/csv/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5001, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('company', 'name', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country', 'vat_no', 'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv) {
                    if ($this->companies_model->getCompanyByEmail($csv['email'])) {
                        $this->session->set_flashdata('error', lang("check_customer_email") . " (" . $csv['email'] . "). " . lang("customer_already_exist") . " (" . lang("line_no") . " " . $rw . ")");
                        redirect("customers");
                    }
                    $rw++;
                }
                foreach ($final as $record) {
                    $record['group_id'] = 3;
                    $record['group_name'] = 'customer';
                    $record['customer_group_id'] = 1;
                    $record['customer_group_name'] = 'General';
                    $data[] = $record;
                }
                //$this->sma->print_arrays($data);
            }

        } elseif ($this->input->post('import')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && !empty($data)) {
            if ($this->companies_model->addCompanies($data)) {
                $this->session->set_flashdata('message', lang("customers_added"));
                redirect('customers');
            }
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'customers/import', $this->data);
        }
    }

    function delete($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->input->get('id') == 1) {
            $this->session->set_flashdata('error', lang('customer_x_deleted'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }

        if ($this->companies_model->deleteCustomer($id)) {
            echo lang("customer_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('customer_x_deleted_have_sales'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        // $this->sma->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->getCustomerSuggestions($term, $limit);
        $this->sma->send_json($rows);
    }

    function getCustomer($id = NULL)
    {
        // $this->sma->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        $this->sma->send_json(array(array('id' => $row->id, 'text' => ($row->company != '-' ? $row->company : $row->name))));
    }

    function get_customer_details($id = NULL)
    {
        $this->sma->send_json($this->companies_model->getCompanyByID($id));
    }

    function get_award_points($id = NULL)
    {
        $this->sma->checkPermissions('index');
        $row = $this->companies_model->getCompanyByID($id);
        $this->sma->send_json(array('ca_points' => $row->award_points));
    }

    function customer_actions()
    {
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    $this->sma->checkPermissions('delete');
                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->companies_model->deleteCustomer($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('customers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', lang("customers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('customer'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('address'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('city'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('state'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('postal_code'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('country'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('vat_no'));
                    $this->excel->getActiveSheet()->SetCellValue('K1', lang('deposit_amount'));
                    $this->excel->getActiveSheet()->SetCellValue('L1', lang('ccf1'));
                    $this->excel->getActiveSheet()->SetCellValue('M1', lang('ccf2'));
                    $this->excel->getActiveSheet()->SetCellValue('N1', lang('ccf3'));
                    $this->excel->getActiveSheet()->SetCellValue('O1', lang('ccf4'));
                    $this->excel->getActiveSheet()->SetCellValue('P1', lang('ccf5'));
                    $this->excel->getActiveSheet()->SetCellValue('Q1', lang('ccf6'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->phone);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->address);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->city);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->state);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $customer->postal_code);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $customer->country);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, $customer->vat_no);
                        $this->excel->getActiveSheet()->SetCellValue('K' . $row, $customer->deposit_amount);
                        $this->excel->getActiveSheet()->SetCellValue('L' . $row, $customer->cf1);
                        $this->excel->getActiveSheet()->SetCellValue('M' . $row, $customer->cf2);
                        $this->excel->getActiveSheet()->SetCellValue('N' . $row, $customer->cf3);
                        $this->excel->getActiveSheet()->SetCellValue('O' . $row, $customer->cf4);
                        $this->excel->getActiveSheet()->SetCellValue('P' . $row, $customer->cf5);
                        $this->excel->getActiveSheet()->SetCellValue('Q' . $row, $customer->cf6);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'customers_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_customer_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    function deposits($company_id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['company'] = $this->companies_model->getCompanyByID($company_id);
        $this->load->view($this->theme . 'customers/deposits', $this->data);

    }

    function get_deposits($company_id = NULL)
    {
        $this->sma->checkPermissions('deposits');
        $this->load->library('datatables');
        $this->datatables
            ->select("deposits.id as id, date, amount, paid_by, CONCAT({$this->db->dbprefix('users')}.first_name, ' ', {$this->db->dbprefix('users')}.last_name) as created_by", false)
            ->from("deposits")
            ->join('users', 'users.id=deposits.created_by', 'left')
            ->where($this->db->dbprefix('deposits').'.company_id', $company_id)
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . lang("deposit_note") . "' href='" . site_url('customers/deposit_note/$1') . "' data-toggle='modal' data-target='#myModal2'><i class=\"fa fa-file-text-o\"></i></a> <a class=\"tip\" title='" . lang("edit_deposit") . "' href='" . site_url('customers/edit_deposit/$1') . "' data-toggle='modal' data-target='#myModal2'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . lang("delete_deposit") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('customers/delete_deposit/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id")
        ->unset_column('id');
        echo $this->datatables->generate();
    }

    function add_deposit($company_id = NULL)
    {
        $this->sma->checkPermissions('deposits', true);

        if ($this->input->get('id')) {
            $company_id = $this->input->get('id');
        }
        $company = $this->companies_model->getCompanyByID($company_id);

        if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
        
        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $data = array(
                'date' => $date,
                'amount' => $this->input->post('amount'),
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note'),
                'company_id' => $company->id,
                'created_by' => $this->session->userdata('user_id'),
            );

            $cdata = array(
                'deposit_amount' => ($company->deposit_amount+$this->input->post('amount'))
            );

        } elseif ($this->input->post('add_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->companies_model->addDeposit($data, $cdata)) {
            $this->session->set_flashdata('message', lang("deposit_added"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->load->view($this->theme . 'customers/add_deposit', $this->data);
        }
    }

    function edit_deposit($id = NULL)
    {
        $this->sma->checkPermissions('deposits', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $deposit = $this->companies_model->getDepositByID($id);
        $company = $this->companies_model->getCompanyByID($deposit->company_id);

        if ($this->Owner || $this->Admin) {
            $this->form_validation->set_rules('date', lang("date"), 'required');
        }
        $this->form_validation->set_rules('amount', lang("amount"), 'required|numeric');
        
        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = $deposit->date;
            }
            $data = array(
                'date' => $date,
                'amount' => $this->input->post('amount'),
                'paid_by' => $this->input->post('paid_by'),
                'note' => $this->input->post('note'),
                'company_id' => $deposit->company_id,
                'updated_by' => $this->session->userdata('user_id'),
                'updated_at' => $date = date('Y-m-d H:i:s'),
            );

            $cdata = array(
                'deposit_amount' => (($company->deposit_amount-$deposit->amount)+$this->input->post('amount'))
            );

        } elseif ($this->input->post('edit_deposit')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateDeposit($id, $data, $cdata)) {
            $this->session->set_flashdata('message', lang("deposit_updated"));
            redirect("customers");
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['company'] = $company;
            $this->data['deposit'] = $deposit;
            $this->load->view($this->theme . 'customers/edit_deposit', $this->data);
        }
    }

    public function delete_deposit($id)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->companies_model->deleteDeposit($id)) {
            echo lang("deposit_deleted");
        }
    }

    public function deposit_note($id = null)
    {
        $this->sma->checkPermissions('deposits', true);
        $deposit = $this->companies_model->getDepositByID($id);
        $this->data['customer'] = $this->companies_model->getCompanyByID($deposit->company_id);
        $this->data['deposit'] = $deposit;
        $this->data['page_title'] = $this->lang->line("deposit_note");
        $this->load->view($this->theme . 'customers/deposit_note', $this->data);
    }

}
