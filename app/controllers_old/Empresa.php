<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa extends MY_Controller
{
    
        function __construct()
    {
        parent::__construct();
        $this->lang->load('auth', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('auth_model');
        $this->load->library('ion_auth');
    }

    function index()
    {

        if (!$this->loggedIn) {
            redirect('login');
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    function profile_empresa($id = NULL)
    {
       
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$id || empty($id)) {
            redirect('auth');
        }
        
        $this->data['title'] = lang('profile');

        $user = $this->ion_auth->user($id)->row();
        $id_empresa = $user->empresa_id;
        
        $empresa = $this->ion_auth->getDadosEmpresa($id_empresa);

                
        
        $this->data['empresa'] = $empresa;
        
        
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
       
        

        
        $this->data['user'] = $user;
                
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
       
        $this->data['id'] = $id_empresa;
       
       
      
        $this->data['id_user'] = $id;
        
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('auth/users'), 'page' => lang('users')), array('link' => '#', 'page' => lang('profile')));
        $meta = array('page_title' => lang('profile'), 'bc' => $bc);
        $this->page_construct('auth/empresa', $meta, $this->data);
    }
    
     public function edit($id = null)
    {
        $this->sma->checkPermissions();
      
       // $this->form_validation->set_rules('quantidade', lang("quantity_plots"), 'required');
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
       
        $this->form_validation->set_rules('razaoSocial', lang("razao_social"), 'required');
        $this->form_validation->set_rules('cnpj', lang("cnpj"), 'required');
        $this->form_validation->set_rules('endereco', lang("endereco"), 'required');
        $this->form_validation->set_rules('numero', lang("numero"), 'required');
        $this->form_validation->set_rules('bairro', lang("bairro"), 'required');
        $this->form_validation->set_rules('cidade', lang("cidade"), 'required');
        $this->form_validation->set_rules('uf', lang("uf"), 'required');
        $this->form_validation->set_rules('cep', lang("cep"), 'required');

        $this->form_validation->set_rules('telefone', lang("telefone"), 'required');
        $this->form_validation->set_rules('email', lang("email"), 'required');
        $this->form_validation->set_rules('ambiente', lang("ambiente"), 'required');
        $this->form_validation->set_rules('numeroNfe', lang("numeroNfe"), 'required');
        
        
         if ($this->form_validation->run() == true) {
            $nome = $this->input->post('razaoSocial');
            $cnpj = $this->input->post('cnpj');
            $ie = $this->input->post('ie');
            $im = $this->input->post('im'); 
            //endereco            
            $endereco = $this->input->post('endereco');
            $numero = $this->input->post('numero');
            $complemento = $this->input->post('complemento');
            $bairro = $this->input->post('bairro');
            $cidade = $this->input->post('cidade');
            $uf = $this->input->post('uf');
            $cep = $this->input->post('cep');
            //contato
            $telefone = $this->input->post('telefone');
            $celular = $this->input->post('celular');
            $skype = $this->input->post('skype');
            $email = $this->input->post('email');
            
            $ambiente = $this->input->post('ambiente');
            $numeroNfe = $this->input->post('numeroNfe');
            $natureza = $this->input->post('natureza');
            $tipo = $this->input->post('tipo');
            $finalidade = $this->input->post('finalidade');
            
            $id_user = $this->input->post('id_user');
      
             $data = array(
                'razaoSocial' => $nome,
                'cnpj' => $cnpj,
                'inscricaoEstadual' => $ie,
                'inscricaoMunicipal' => $im,
                'endereco' => $endereco,
                'numero' => $numero,
                
                'complementoEndereco' => $complemento,
                'bairro' => $bairro,
                'cidade' => $cidade,
                'uf' => $uf,
                'cep' => $cep,
                'telefone' => $telefone,
                'celular' => $celular,
                'skype' => $skype,
                'emailResponsavel' => $email,
                'ambiente' => $ambiente,
                'numeroNfeAtual' => $numeroNfe,
                'natureza' => $natureza,
                'tipoNota' => $tipo,
                'finalidade' => $finalidade
            );
           
             /*
             if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
              * 
              */
          
        }
        if ($this->form_validation->run() == true && $this->auth_model->updateEmpresa($id, $data)) {
           
            $this->session->set_flashdata('message', lang("empresa_updated"));
            redirect("empresa/profile_empresa");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            redirect("empresa/profile_empresa/".$id_user);

            }
    }
 
    function update_avatar_empresa($id = NULL, $id_user = NULL)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        
         if ($this->input->post('id_user')) {
            $id_user = $this->input->post('id_user');
        }
        

        if (!$this->ion_auth->logged_in() || !$this->Owner && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        //validate form input
        $this->form_validation->set_rules('avatar', lang("avatar"), 'trim');

        if ($this->form_validation->run() == true) {

            if ($_FILES['avatar']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/uploads/avatars';
                $config['allowed_types'] = 'gif|jpg|png';
                //$config['max_size'] = '500';
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('avatar')) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                $photo = $this->upload->file_name;
                
                $this->load->helper('file');
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/uploads/avatars/' . $photo;
                $config['new_image'] = 'assets/uploads/avatars/thumbs/' . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 150;
                $config['height'] = 150;;

                $this->image_lib->clear();
                $this->image_lib->initialize($config);

                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
               
            } else {
                $this->form_validation->set_rules('avatar', lang("avatar"), 'required');
            }
        }

        if ($this->form_validation->run() == true && $this->auth_model->updateAvatarEmpresa($id, $photo)) {
          
            //$this->session->set_userdata('avatar', $photo);
            $this->session->set_flashdata('message', lang("avatar_updated"));
            redirect("empresa/profile_empresa/" . $id_user);
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect("empresa/profile_empresa/" . $id_user);
        }
    }
    
        function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }
    
}