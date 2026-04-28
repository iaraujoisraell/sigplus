<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Formularios extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('Formulario_model');
        $this->load->model('Intranet_model');
       
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
    }

    public function index() {
       
       
        $view_data["title"] = 'FORMULÁRIOS';
        $view_data["content"] = 'home';
        // layout
        $view_data["layout"] = 'layout-top-nav';
        $view_data["exibe_menu_esquerdo"] = 0;
        $view_data["exibe_menu_topo"] = 1;
        
        $view_data['formularios'] = $this->Formulario_model->get_formulario_by_user_id();

        //sidebar-collapse   (menu encolhido)
        // layout-fixed     menu normal
         //$this->load->view('gestao_corporativa/intranet/summer.php', $view_data);
        $this->load->view('gestao_corporativa/formularios/index.php', $view_data);
        //$this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    /*
     * 28/08/2022
     * @israel Araujo
     * Funcao q junta todas as funções q precisam ser validadas
     */

   

    public function criar_formulario() {

      
        $empresa_id = $this->session->userdata('empresa_id');
        $staffid = get_staff_user_id();
        
        
        $data['empresa_id']         = $empresa_id;
        $data['user_created']       = $staffid;
        $data['data_created']       = date('Y-m-d');
        $data['form_key']           = app_generate_hash();
        

        $retorno = $this->Formulario_model->add($data);
        if ($retorno) {
            
            $dados = $this->Formulario_model->get_formulario_by_id($retorno);
            $key = $dados->form_key;
            
            set_alert('success', 'Formulário Criado com Sucesso');
            redirect('gestao_corporativa/Formularios/abrir_formulario/'.$key); 
          
        }else{
            redirect('gestao_corporativa/Formularios/index');
        }
    }
    
    public function criar_formulario_filho($form_pai_id = '') {

        $empresa_id = $this->session->userdata('empresa_id');
        $staffid = get_staff_user_id();
        
        // consulta qual a sessao
        $total_filhos = 0;
        $dados_filhos = $this->Formulario_model->get_form_filhos($form_pai_id);
        $total_filhos = count($dados_filhos);
        $t_sessao = $total_filhos + 2;
        
        $data['sessao']             = $t_sessao;
        $data['form_pai']           = $form_pai_id;
        $data['empresa_id']         = $empresa_id;
        $data['user_created']       = $staffid;
        $data['data_created']       = date('Y-m-d');
        $data['form_key']           = app_generate_hash();
       

        $retorno = $this->Formulario_model->add($data);
        if ($retorno) {
            
            $dados = $this->Formulario_model->get_formulario_by_id($form_pai_id);
            $key = $dados->form_key;
            
            //set_alert('success', 'Formulário Criado com Sucesso');
            redirect('gestao_corporativa/Formularios/abrir_formulario/'.$key); 
          
        }else{
            redirect('gestao_corporativa/Formularios/index');
        }
    }
    
    public function abrir_formulario($key) {
      
        $view_data['form'] = $this->Formulario_model->get_form_pai($key);
        
        //print_r($data['departamentos']); exit;
        $view_data["layout"] = 'layout-top-nav';
        $view_data["exibe_menu_esquerdo"] = 0;
        $view_data["exibe_menu_topo"] = 1;
        
        $this->load->view('gestao_corporativa/formularios/editar_formulario.php', $view_data);
    }

     public function add_pergunta() {
        
        $form_id = $this->input->post('form_id');
        $empresa_id = $this->session->userdata('empresa_id');
        $staffid = get_staff_user_id();
        
        
        $data['empresa_id']         = $empresa_id;
        $data['user_created']       = $staffid;
        $data['data_created']       = date('Y-m-d');
        $data['form_key']           = app_generate_hash();
        $data['form_id']            = $form_id;
        $data['tipo']               = 'text';
        $pergunta_id = $this->Formulario_model->manage_pergunta($data);
        
        if($pergunta_id){
            
            redirect('gestao_corporativa/Formularios/abrir_formulario/'.$form_id); 
        }else{
            $this->abrir_formulario($form_id);  
        }
        
    }

    public function add_pergunta_filho() {
        
        $form_id = $this->input->post('form_id');
        $form_pai = $this->input->post('form_pai');
        $empresa_id = $this->session->userdata('empresa_id');
        $staffid = get_staff_user_id();
        
        
        $data['empresa_id']         = $empresa_id;
        $data['user_created']       = $staffid;
        $data['data_created']       = date('Y-m-d');
        $data['form_key']           = app_generate_hash();
        $data['form_id']            = $form_id;
        $data['tipo']               = 'text';
        $pergunta_id = $this->Formulario_model->manage_pergunta($data);
        
        if($pergunta_id){
            return true;
           // redirect('gestao_corporativa/Formularios/abrir_formulario/'.$form_pai); 
        }else{
            $this->abrir_formulario($form_pai);  
        }
        
    }
    
    public function deletar_pergunta() {
        
        $pergunta_id= $this->input->post('pergunta_id');
        $form_pai = $this->input->post('form_pai');
        $data['deleted']               = 1;
        $retorno = $this->Formulario_model->delete_pergunta($data, $pergunta_id);
        
        if($retorno){
            return true;
        }
        
    }
    
    public function lista_perguntas() {
            $form_id = $this->input->post('form_id');
            $view_data['form_id']       = $form_id;
            $perguntas = $this->Formulario_model->get_perguntas_formulario_by_formid($form_id);
            $view_data['perguntas']       = $perguntas;
            $this->load->view('gestao_corporativa/formularios/lista_perguntas.php', $view_data);
    }
    
    /*
     * MULTIPLA ESCOLHA
     * RÁDIO BUTTOM
     */
    
    public function add_item_multipla_escolha() {
        $perg_id = $this->input->post('perg_id'); 
        $item_name = $this->input->post('item_name');
        $staff_id = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        
        
        $data['pergunta_id']            = $perg_id;
        $data['name']                   = $item_name;
        $data['empresa_id']             = $empresa_id;
        $data['datecreated']            = date('Y-m-d H:i:s');
        $data['user_created']           = $staff_id;
        $this->Formulario_model->add_item_multiplaescolha($data);
        
        echo json_encode([
        'success' => true,
        'message' => 'Add com Sucesso'
        ]);
    }
    
    // RADIO BUTOM - LISTAGEM
    public function lista_multipla_escolha() {
            
            $perg_id = $this->input->post('perg_id');
            $view_data['perg_id']       = $perg_id;
            $itens = $this->Formulario_model->get_item_multiplaescolha_by_pergunta_id($perg_id);
            $view_data['itens']       = $itens;
            $this->load->view('gestao_corporativa/formularios/lista_multipla_escolha.php', $view_data);
    }
    
    public function deletar_item_multipla_escolha() {
        
        $item_id                        = $this->input->post('item_id');
        $data['deleted']                = 1;
        $retorno = $this->Formulario_model->delete_item_multiplaescolha($data, $item_id);
        
        echo json_encode([
        'success' => true,
        'message' => 'Add com Sucessp'
        ]);
        
    }
    
    
    /*
     * FIM MULTIPLA ESCOLHA
     */
    
    /*
     * CAIXA SELEÇÃO
     * CHECKBOX
     */
    
    public function lista_caixa_selecao() {
            
            $perg_id = $this->input->post('perg_id');
            $view_data['perg_id']       = $perg_id;
            $itens = $this->Formulario_model->get_item_multiplaescolha_by_pergunta_id($perg_id);
            $view_data['itens']       = $itens;
            $this->load->view('gestao_corporativa/formularios/lista_caixa_selecao.php', $view_data);
    }
    

    /*
     * FIM CAIXA SELEÇÃO
     */
    
    public function lista_select() {
            
            $perg_id = $this->input->post('perg_id');
            $view_data['perg_id']       = $perg_id;
            $itens = $this->Formulario_model->get_item_multiplaescolha_by_pergunta_id($perg_id);
            $view_data['itens']       = $itens;
            $this->load->view('gestao_corporativa/formularios/lista_select.php', $view_data);
    }
    

    public function atualiza_perguntas() {
        
        $form_id = $this->input->post('form_id');
       
        $title = $this->input->post('title'); 
        $tipo = $this->input->post('tipo');
       
        $name = $title;
        $name = str_replace(" ", "_", $name);
        $name = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$name);
        $name = strtolower($name);
        
        $data['title']           = $title;
        $data['name']            = $name;
        $data['tipo']            = $tipo;
        $pergunta_id = $this->Formulario_model->manage_pergunta($data, $form_id);
        if($pergunta_id){
          //  echo 'ok'; 
        }
    }
    
    public function atualiza_formulario() {
        
        $form_id = $this->input->post('form_id');
        $title = $this->input->post('title'); 
        $descricao = $this->input->post('descricao');
        $msg_obrigado = $this->input->post('msg_obrigado');
        
        $data['titulo']           = $title;
        $data['descricao']            = $descricao;
        $data['success_submit_msg']            = $msg_obrigado;
        $pergunta_id = $this->Formulario_model->atualiza_form($data, $form_id);
        if($pergunta_id){
          //  echo 'ok'; 
        }
    }
   

    public function delete_data() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_eventos', $dados);
        redirect('gestao_corporativa/intranet/cadastros');
    }

    
    
}
