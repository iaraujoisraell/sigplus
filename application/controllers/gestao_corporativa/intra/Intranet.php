<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Intranet extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('New_model');
        $this->load->model('Intranet_model');
        $this->load->model('Comunicado_model');
        $this->load->model('Arquivo_model');
        $this->load->model('Link_model');
        $this->load->model('Departments_model');
        $this->load->model('Menu_model');
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
    }

    public function table_publicacao() {
        $this->app->get_table_data_intranet('publicacoes');
    }

    public function table_comunicado() {
        $this->app->get_table_data_intranet('comunicados');
    }

    public function table_arquivo() {
        $this->app->get_table_data_intranet('arquivos');
    }

    public function table_arquivo_tipos() {
        $this->app->get_table_data_intranet('arquivos_tipos');
    }

    public function table_link_externo() {
        $this->app->get_table_data_intranet('links');
    }

    public function table_eventos() {
        $this->app->get_table_data_intranet('eventos');
    }

    public function table_documento() {
        $this->app->get_table_data_intranet('documentos');
    }

    public function table_documento_andamento() {
        $this->app->get_table_data_intranet('documentos_andamento');
    }

    public function table_link_destaque() {
        $this->app->get_table_data_intranet('links_destaque');
    }

    public function table_menu() {
        $this->app->get_table_data_intranet('menus');
    }

    public function table_grupo() {
        $this->app->get_table_data_intranet('grupos');
    }
    /**
     * 05/11/2022
     * @WannaLuiza
     * Tabela de tipos de usuários
     */
    public function table_profile_type() {

        $this->app->get_table_data_intranet('profile_types');
    }

    /**
     * 25/06/2024
     * @Anderson
     * Tabela de empresas tercerizadas
     */
    public function table_terceiros() {

        $this->app->get_table_data_intranet('terceiros');
    }

}
