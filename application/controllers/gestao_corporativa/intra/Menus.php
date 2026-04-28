<?php


header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Menus extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Intranet_model');
        $this->load->model('Menu_model');
        $this->load->model('departments_model');
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Tela de cadastro
     */
    public function index($menu_pai = 0) {
        if ($menu_pai == 0) {
            $ordem = $this->Menu_model->get_next_menu();
            $view_data['ordem_menu'] = $ordem;
        } else {
            $view_data["menu_pai"] = $this->Menu_model->get_menu($menu_pai)->row();
            
            $view_data["id_pai"] = $menu_pai;
            $ordem = $this->Menu_model->get_next_submenu($menu_pai);
            $view_data['ordem_menu'] = $ordem;
        }


        $this->load->view('gestao_corporativa/intranet/menus/index.php', $view_data);
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Tela para editar;
     */
    public function edit_menu() {
        $id = $_GET['id'];
        $view_data["menu"] = $this->Menu_model->get_menu($id)->row();

        $this->load->view('gestao_corporativa/intranet/menus/index.php', $view_data);
    }

    /**
     * 26/09/2022(
     * @WannaLuiza
     * Tela de visualização de menus criados
     */
    public function menu($url = '') {
        $url = (base_url() . 'gestao_corporativa/intra/Menus/menu/' . $url);
        $menu = $this->Menu_model->get_menu_by_url($url);
        if ($menu->menu_pai != 0) {
            $view_data["menu_id"] = $menu->menu_pai;
            $submenus = $this->Menu_model->get_submenus_with_pai($menu->menu_pai);
        } else {
            $view_data["menu_id"] = $menu->id;
            $submenus = $this->Menu_model->get_submenus($menu->id);
        }
        $view_data["menu"] = $menu;
        $view_data["submenus"] = $submenus;
        $view_data["title"] = $menu->nome_menu;

        $view_data["content"] = 'menus/menu';
        // layout
        $view_data["layout"] = 'sidebar-collapse';
        $view_data["exibe_menu_esquerdo"] = 1;
        $view_data["exibe_menu_topo"] = 1;

        //sidebar-collapse   (menu encolhido)
        // layout-fixed     menu normal
        $this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * Insere um menu ou um submenu
     */
    public function salvar() {

        $id = $this->input->post('id');

        //print_r($this->input->post()); exit;
        $array['nome_menu'] = $this->input->post('nome');
        $array['ordem'] = $this->input->post('ordem');
        $array['icon'] = $this->input->post('icon');
        $array['conteudo'] = $_POST['descricao'];
        if ($this->input->post('menu_pai')) {
            $array['menu_pai'] = $this->input->post('menu_pai');
        }
        if ($this->input->post('url')) {
            $array['urk'] = $this->input->post('url');
        } else {
            $url = $this->tira_pontuacao_espaco_caractereespecial($array['nome_menu']);
            $array['urk'] = (base_url() . 'gestao_corporativa/intra/Menus/menu/' . $url);
        }

        $RESULT = $this->Menu_model->add($array, $id);

        if ($RESULT) {
            redirect('gestao_corporativa/intranet_admin/index/?group=menu');
        }
    }

    /**
     * 26/09/2022(data que botei o comentário)
     * @WannaLuiza
     * UPDATE DELETED = 1
     */
    public function delete_menu() {
        $id = $_GET['id'];
        $RESULT = $this->Menu_model->delete($id);
        if ($RESULT == true) {
            redirect('gestao_corporativa/intranet_admin/index/?group=menu');
        }
    }

    /**
     * 03/12/2022
     * @WannaLuiza
     * Tira pontuação
     */
    function tira_pontuacao_espaco_caractereespecial($string) {

        // matriz de entrada
        $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');

        // matriz de saída
        $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');

        // devolver a string
        $resultado = str_replace($what, $by, $string);

        return $resultado;
    }

}
