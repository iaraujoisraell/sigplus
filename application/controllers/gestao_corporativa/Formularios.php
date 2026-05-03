<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Mantido só pra retrocompatibilidade de URLs antigas.
 * O módulo novo está em gestao_corporativa/Formulario (singular).
 */
class Formularios extends AdminController
{
    public function index()
    {
        redirect('gestao_corporativa/Formulario');
    }

    public function abrir_formulario($key)
    {
        $this->load->model('Formulario_model');
        $form = $this->Formulario_model->get_by_key($key);
        if ($form) redirect('gestao_corporativa/Formulario/edit/' . (int) $form['id']);
        redirect('gestao_corporativa/Formulario');
    }

    public function criar_formulario()
    {
        redirect('gestao_corporativa/Formulario/add');
    }

    public function _remap()
    {
        // qualquer outra ação cai na lista
        redirect('gestao_corporativa/Formulario');
    }
}
