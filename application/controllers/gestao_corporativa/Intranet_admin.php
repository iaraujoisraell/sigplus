<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Intranet_admin extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');

        $this->load->model('Intranet_model');
        $this->load->model('Staff_model');
        $this->load->model('Comunicado_model');
        $this->load->model('Menu_model');
        $this->load->model('Documento_model');
        $this->load->model('Link_model');
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
    }

    public function index($id = '') {

        //VERIFICA SE TEM PENDÊNCIAS
        //$this->verifica_pendencias_obrigatorias();
       

        $data['bodyclass'] = 'customer-profile dynamic-create-groups';
        $data['title'] = 'Cadastros - Intranet';

        $data["intranet_menu"] = true;

        $group = $this->input->get('group') ? $this->input->get('group') : 'publicacoes'; //print_r($group); exit;
        $data['group'] = $group;     


       // echo $group; exit;

        $data['customer_tabs'] = get_intranet_profile_tabs();

        // $data['contacts'] = $this->clients_model->get_contacts($id);
        $data['tab'] = isset($data['customer_tabs'][$group]) ? $data['customer_tabs'][$group] : null;

        if($this->input->get('group') == 'cdc_settings'){
            $data['tab']['view'] ='gestao_corporativa/intranet/cadastros/groups/cdc_settings';
        }
      //  print_r($data['tab']); exit;

        if (!$data['tab']) {
            //   show_404();
        }
 

        if ($this->input->get('group') == 'documento_settings') {
            $documento_categorias = $this->Documento_model->get_categorias();



            if ($documento_categorias) {

                for ($y = 0; $y < count($documento_categorias); $y++) {

                    $fluxos_titulo = [];

                    $fluxos = explode(',', $documento_categorias[$y]['fluxos']);

                    for ($i = 0; $i < count($fluxos); $i++) {


                        $fluxos[$i] = $this->Documento_model->get_fluxo_by_id($fluxos[$i]);

                        $fluxos_titulo[$i] = $fluxos[$i]->titulo;

                        $fluxos[$i] = json_decode(json_encode($fluxos[$i]), true);

                        $fluxos[$i] = $fluxos[$i]['id'];
                    }

                    $documento_categorias[$y]['fluxos'] = implode(', ', $fluxos_titulo);

                    $documento_categorias[$y]['fluxos_array'] = $fluxos;
                }
            }

            $documento_fluxos = $this->Documento_model->get_fluxos();
            $data['responsavel'] = $this->Documento_model->get_categorias_responsavel();

            $data["categorias_documento"] = $documento_categorias;

            $data["documento_fluxos"] = $documento_fluxos;

            $data["fluxos"] = $documento_fluxos;
            
            

            $fluxos = $documento_fluxos;

            for ($i = 0; $i < count($fluxos); $i++) {


                $data["fluxos"][$i] = json_decode(json_encode($fluxos[$i]), true);
            }


            $this->load->model('Staff_model');

            $data['staffs'] = $this->Staff_model->get();
        } elseif ($this->input->get('group') == 'links') {
             $data['title'] = 'Cadastros - Links Externos';
        } elseif ($this->input->get('group') == 'links_destaque') {
             $data['title'] = 'Cadastros - Links Destaque';
        }

         $menus = $this->Menu_model->get_menus();
         for($i = 0; $i < count($menus); $i++){
             $menus[$i]['submenus'] = $this->Menu_model->get_submenus($menus[$i]['id']);
         }
         $data['menus'] = $menus;
         
       // print_r($data); exit;

        $this->load->view('gestao_corporativa/intranet/cadastros/cadastro', $data);
    }

    /*
     * 28/08/2022
     * @israel Araujo
     * Funcao q junta todas as funções q precisam ser validadas
     */

    public function verifica_pendencias_obrigatorias() {
        // comunicados pendentes
        $this->verifica_comunicado_pendente();

        // documentos pendentes
        $this->verifica_documentos_pendente();
    }

    // COMUNICADOS PENDENTES
    public function verifica_comunicado_pendente() {
        $comunicados_pendentes = $this->Comunicado_model->get_comunicado_nao_lido();
        for ($comunic = 0; $comunic < count($comunicados_pendentes); $comunic++) {
            if ($comunicados_pendentes[$comunic]->status == 0) {
                redirect('gestao_corporativa/intranet/comunicados_pendentes');
                exit;
            }

            $comunicados_pendentes[$comunic] = $this->Comunicado_model->get_comunicado($comunicados_pendentes[$comunic]->ci_id)->row();
            $comunicados_pendentes[$comunic]->user = $this->Intranet_model->get_one($comunicados_pendentes[$comunic]->user_create)->row();
        }
    }

    // DOCUMENTOS PENDENTES
    public function verifica_documentos_pendente() {
        $documentos_pendentes = $this->Documento_model->get_documentos_nao_lido();
        if (count($documentos_pendentes) > 0) {
            redirect('gestao_corporativa/intranet/documentos_pendentes');
        }
    }

}
