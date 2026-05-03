<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Formularios extends ClientsController
{
    public function index()
    {
        show_404();
    }

    /**
     * Página pública de resposta. URL: /formularios/web/{form_key}
     */
    public function web($key)
    {
        $this->load->model('Formulario_model');
        $form = $this->Formulario_model->get_by_key($key);
        if (!$form || (int) $form['form_pai'] !== 0) show_404();

        if ($form['status'] !== 'publicado') {
            $this->load->view('formularios/indisponivel', ['form' => $form, 'motivo' => $form['status']]);
            return;
        }

        if ($form['visibilidade'] === 'restrita' && !is_logged_in()) {
            redirect(base_url('admin/authentication?redirect=' . rawurlencode(current_url())));
        }

        $perguntas = $this->Formulario_model->get_perguntas($form['id']);

        if ($this->input->post()) {
            $this->_processar_envio($form, $perguntas);
            return;
        }

        $logo  = $this->Formulario_model->get_option_logo($form['empresa_id']);
        $nome  = $this->Formulario_model->get_option_nome_empresa($form['empresa_id']);

        $this->load->view('formularios/responder', [
            'form'         => $form,
            'perguntas'    => $perguntas,
            'company_logo' => $logo->value ?? '',
            'company_name' => $nome->value ?? '',
        ]);
    }

    public function obrigado($key)
    {
        $this->load->model('Formulario_model');
        $form = $this->Formulario_model->get_by_key($key);
        if (!$form) show_404();
        $logo = $this->Formulario_model->get_option_logo($form['empresa_id']);
        $nome = $this->Formulario_model->get_option_nome_empresa($form['empresa_id']);
        $this->load->view('formularios/obrigado', [
            'form'         => $form,
            'company_logo' => $logo->value ?? '',
            'company_name' => $nome->value ?? '',
        ]);
    }

    private function _processar_envio($form, $perguntas)
    {
        $hash = $this->input->post('hash') ?: app_generate_hash();
        $respostas = $this->input->post('r') ?: [];

        // valida required
        foreach ($perguntas as $p) {
            if ($p['required']) {
                $v = $respostas[$p['id']] ?? '';
                if (is_array($v)) $v = implode('', $v);
                if (trim((string) $v) === '') {
                    set_alert('warning', 'Preencha as perguntas obrigatórias.');
                    redirect(base_url('formularios/web/' . $form['form_key']));
                    return;
                }
            }
        }

        $staff_id = is_logged_in() ? (int) get_staff_user_id() : null;
        $this->Formulario_model->salvar_respostas($form['id'], $hash, $respostas, $staff_id);
        redirect(base_url('formularios/obrigado/' . $form['form_key']));
    }
}
