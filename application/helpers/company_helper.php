<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @WannaLuiza
 * 08/03/2023
 * Helper para EMPRESAS
 */

/**
 * @WannaLuiza
 * 08/03/2023
 * Retorna as ações do sistema
 */
function get_actions($just_one = '') {

    $basic = [
        'sigplus',
        'email',
        'sms'
    ];

    $actions = [
        'ci_received' => [
            'name' => 'CI Recebida',
            'from' => 'COMUNICADO INTERNO', 'link_sigplus' => 'gestao_corporativa/intra/Comunicado',
            'conteudo_email' => 'Comunicado Interno Recebido CI #change_reference',
            'conteudo_sms' => 'Comunicado Interno Recebido CI #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'assunto' => 'Comunicado Interno Recebido CI #change_reference',
            'capabilities' => $basic,
        ],
        'ci_aware' => [
            'name' => 'Destinatário Ciente(CI criada pelo colaborador Logado)',
            'from' => 'COMUNICADO INTERNO', 'link_sigplus' => 'gestao_corporativa/intra/Comunicado',
            'conteudo_email' => '#change_staff_full_name deu ciência na CI #change_reference',
            'conteudo_sms' => '#change_staff_full_name deu ciência na CI #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'assunto' => '#change_staff_full_name ciente CI #change_reference',
            'capabilities' => $basic,
        ],
        'ro_received' => [
            'name' => 'Registro de Ocorrência Recebido (Setor_responsável)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_email' => 'Novo registro de ocorrência recebido para o seu setor.',
            'conteudo_sms' => 'Novo registro de ocorrência recebido para o seu setor. Acesse https://sigplus.app.br/admin e saiba mais',
            'assunto' => 'Novo RO para o seu setor.',
            'capabilities' => $basic,
        ],
        'ro_late' => [
            'name' => 'Registro de Ocorrência ATRASADO (Colaborador Responsável)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_email' => 'RO #change_reference ATRASADO',
            'conteudo_sms' => 'RO #change_reference atrasado. Acesse https://sigplus.app.br/admin e saiba mais',
            'assunto' => 'RO #change_reference atrasado.',
            'capabilities' => $basic,
        ],
        'ro_finish_cancel' => [
            'name' => 'Registro de Ocorrência FINALIZADO/CANCELADO (Solicitante)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_email' => 'RO #change_reference #change_status',
            'conteudo_sms' => 'RO #change_reference #change_status. Acesse https://sigplus.app.br/admin e saiba mais',
            'assunto' => 'RO #change_reference #change_status',
            'capabilities' => $basic,
        ],
        'ro_received_atuante' => [
            'name' => 'Atuante Adicionado/Excluído (Atuante)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_email' => 'Você foi #change_result RO #change_reference',
            'conteudo_sms' => 'Você foi #change_result RO #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'conteudo_email' => 'Você foi #change_result RO #change_reference',
            'assunto' => 'Você foi #change_result RO #change_reference',
            'capabilities' => $basic,
        ],
        'ro_new_actions' => [
            'name' => 'Nova ação vinculada ao Registro (Colaborador Responsável)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_sms' => 'Nova ação vinculada RO #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'conteudo_email' => 'Nova ação vinculada RO #change_reference',
            'assunto' => 'Nova ação vinculada RO #change_reference',
            'capabilities' => $basic,
        ],
        'ro_finish_goal_atuante' => [
            'name' => 'Atuante Concluiu o Objetivo (Colaborador Responsável)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_sms' => '#change_atuante concluiu o objetivo RO #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'conteudo_email' => '#change_atuante concluiu o objetivo RO #change_reference',
            'assunto' => '#change_atuante concluiu o objetivo RO #change_reference',
            'capabilities' => $basic,
        ],
        'ro_new_note' => [
            'name' => 'Nova Nota (Colaborador Responsável)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_sms' => '#change_staff_full_name criou uma nota RO #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'conteudo_email' => '#change_staff_full_name criou uma nota RO #change_reference',
            'assunto' => '#change_staff_full_name criou uma nota RO #change_reference',
            'capabilities' => $basic,
        ],
        'ro_new_answear' => [
            'name' => 'Nova resposta',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_sms' => '#change_staff_full_name adicionou uma resposta RO #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'conteudo_email' => '#change_staff_full_name adicionou uma resposta RO #change_reference',
            'assunto' => '#change_staff_full_name adicionou uma resposta RO #change_reference',
            'capabilities' => $basic,
        ],
        'ro_update_ishikawa' => [
            'name' => 'Ishikawa Atualizado (Colaborador Responsável)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_sms' => '#change_staff_full_name atualizou Ishikawa RO #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'conteudo_email' => '#change_staff_full_name atualizou Ishikawa RO #change_reference',
            'assunto' => '#change_staff_full_name atualizou Ishikawa RO #change_reference',
            'capabilities' => $basic,
        ],
        'ro_update_goal_atuante' => [
            'name' => 'Objetivo/Prazo Atualizado (Atuante)',
            'from' => 'REGISTRO DE OCORRÊNCIA', 'link_sigplus' => 'gestao_corporativa/Registro_ocorrencia',
            'conteudo_sms' => 'Seu Prazo/Objetivo foi atualizado RO #change_reference. Acesse https://sigplus.app.br/admin e saiba mais',
            'conteudo_email' => 'Seu Prazo/Objetivo foi atualizado RO #change_reference',
            'assunto' => 'Seu Prazo/Objetivo foi atualizado RO #change_reference',
            'capabilities' => $basic,
        ],
        'workflow_received' => [
            'name' => 'Workflow Recebido (Setor responsável)',
            'from' => 'WORKFLOW',
            //'conteudo_email' => 'Registro de Ocorrência ATRASADO',           // 'conteudo_sms' => 'Registro de ocorrência #change_reference atrasado. Acesse https://sigplus.app.br/admin e saiba mais',
            'capabilities' => $basic,
        ],
        'workflow_late' => [
            'name' => 'Workflow Atrasado (Setor Responsável)',
            'from' => 'WORKFLOW',
            //'conteudo_email' => 'Registro de Ocorrência ATRASADO',           // 'conteudo_sms' => 'Registro de ocorrência #change_reference atrasado. Acesse https://sigplus.app.br/admin e saiba mais',
            'capabilities' => $basic,
        ],
        'workflow_fluxo_finish' => [
            'name' => 'Workflow Fluxo Finalizados (Setor Responsável)',
            'from' => 'WORKFLOW',
            //'conteudo_email' => 'Registro de Ocorrência ATRASADO',           // 'conteudo_sms' => 'Registro de ocorrência #change_reference atrasado. Acesse https://sigplus.app.br/admin e saiba mais',
            'capabilities' => $basic,
        ],
        'workflow_late_fluxo' => [
            'name' => 'Workflow Atrasado (Colaborador responsável do fluxo atual)',
            'from' => 'WORKFLOW',
            //'conteudo_email' => 'Registro de Ocorrência ATRASADO',           // 'conteudo_sms' => 'Registro de ocorrência #change_reference atrasado. Acesse https://sigplus.app.br/admin e saiba mais',
            'capabilities' => $basic,
        ],
            //////    GED
/////     FORMULÁRIOS
/////     PERMISÕES DE FEED
//// TASKS
    ];
    if ($just_one != '') {
        foreach ($actions as $feature => $permission) {
            if ($just_one == $feature) {
                $action = $permission;
                return hooks()->apply_filters('company_notification', $action);
            }
        }
    }


    return hooks()->apply_filters('company_notifications', $actions);
}

function has_notification_action($capability, $feature) {
    $CI = &get_instance();
    $empresa_id = $CI->session->userdata('empresa_id');

    $CI->db->where("empresa_id", $empresa_id);
    $CI->db->where("feature", $feature);
    $CI->db->where("capability", $capability);
    $exist = $CI->db->get(db_prefix() . 'empresas_notifications')->row();
    if (isset($exist)) {
        return true;
    }

//foreach ($permissions as $appliedFeature => $capabilities) {
//if ($feature == $appliedFeature && in_array($capability, $capabilities)) {
//return true;
// }
//}

    return false;
}

/**
 * 27/03/2023
 * @WannaLuiza
 * Retorna a logo da empresa de acordo com tbloptions
 */
function get_company_option($saas = '', $info = '', $empresa_id = '') {

    $CI = & get_instance();

    $sql = "select value from " . db_prefix() . "options where name = '" . $info . "'";

    if ($saas == '') {
        if ($empresa_id == '') {
            $empresa_id = $CI->session->userdata('empresa_id');
        }
        if ($empresa_id != '') {
        $sql .= " and empresa_id = " . $empresa_id;
        }
    } 
    if ($saas != '') {
        $sql .= " and saas_tenant_id = '" . $saas . "'";
    }

    //return $sql;
    $value = $CI->db->query($sql)->row();
    if (isset($value)) {
        $value = $value->value;
    }
// echo  'jsjs'; exit;


    return html_escape($value);
}

function get_company_option_old($saas, $info, $empresa_id = '') {

    $CI = & get_instance();
    if ($saas == '') {
        if ($empresa_id == '') {
            $empresa_id = $CI->session->userdata('empresa_id');
        }

        $CI->db->where('empresa_id', $empresa_id);
    } else {
        $CI->db->where('saas_tenant_id', $saas);
    }


    $CI->db->where('name', $info);
//echo $info; exit;
    $value = $CI->db->select('value')->from(db_prefix() . 'options')->get()->row();
    $value = $value->value;

    return html_escape($value);
}

/**
 * 27/03/2023
 * @WannaLuiza
 * Retorna autosservicos da empresa
 */
function get_company_client_self() {

    $CI = & get_instance();
    if ($saas == '') {
        if ($empresa_id == '') {
            $empresa_id = $CI->session->userdata('empresa_id');
        }

        $CI->db->where('empresa_id', $empresa_id);
    } else {
        $CI->db->where('saas_tenant_id', $saas);
    }


    $CI->db->where('rel_type', 'autosservico');

    $CI->db->where('deleted', '0');

    $CI->db->group_by('linked_to');

    return $CI->db->select('linked_to')->from(db_prefix() . '_intranet_categorias')->get()->result_array();
}

/**
 * 31/07/2023
 * @WannaLuiza
 * Retorna um value de acordo com o tipo 
 */
function get_value($rel_type, $value, $tipo, $link = true, $sub_ = false, $sub = 'campo_file/') {

    $CI = & get_instance();
    if ($tipo == 'multiselect' || $tipo == 'select') {
        $values = explode(',', $value);
        $CI->load->model('Registro_ocorrencia_model');
//print_r($values); exit;
        if (count($values) > 0) {
            for ($i = 0; $i < count($values); $i++) {
                if ($values[$i] != '' and is_numeric($values[$i])) {
                    $row = $CI->Registro_ocorrencia_model->get_option($values[$i]);
                    $values_select[$i] = $row->option;
                } else {
                    $values_select[$i] = $values[$i];
                }
            }
            if ($values_select) {
                return implode(', ', $values_select);
            }
        } else {
            return $values[0];
        }
    } elseif ($tipo == 'setores') {
        $setores = explode(',', $value);
        $deps = [];
        if (is_array($setores)) {
            foreach ($setores as $setor) {
                if (is_numeric($setor)) {
                    $deps[] = get_departamento_nome($setor);
                }
            }
            echo implode(' | ', $deps);
        } else {
            if ($value) {
                return get_departamento_nome($value);
            }
        }
    } elseif ($tipo == 'funcionarios') {
        if ($value) {
            return get_staff_full_name($value);
        }
    } elseif ($tipo == 'file') {
        if ($link == true) {
            if ($value) {
                $address = ($sub_ == false) ? 'campo_file/' : $sub;
                return '<a href="' . base_url() . 'assets/intranet/arquivos/' . $rel_type . '_arquivos/' . $address . $value . '" target="_blank"><i class="fa fa-file-o"></i> ' . $value . '</a>';
            }
        } else {
            return $value;
        }
    } elseif ($tipo == 'list') {

        $values = explode(',', $value);
        $value_ = [];
        foreach ($values as $v) {
            if (is_numeric($v)) {
                $CI->load->model('Company_model');
                $option = $CI->Company_model->get_list_option('', $v);
                if ($option) {
                    $value_[] = $option->option;
                }
            } else {
                $value_[] = $v;
            }
        }
        return implode(' | ', $value_);
    } elseif ($tipo == 'date') {
        if ($value) {
            return date('d/m/Y', strtotime($value));
        }
    } else {
        return $value;
    }
}

function get_company_colors() {

    $cores = [
        '#1acfe5',
        '#c41723',
        '#28B8DA',
        '#e3b813',
        '#260303',
        '#140101',
        '#0e98d1',
        '#b25d9b',
        '#e0e308',
        '#20d1ce',
        '#b29b2c',
        '#f2d912',
        '#f20b0b',
        '#11e825',
        '#0fe596',
        '#e3155d',
        '#2508e5',
        '#ea0bc9',
        '#06ea36',
        '#1ce808',
        '#7cb342',
        '#fc2d42'
    ];
    return $cores;
}

function tira_pontuacao_espaco_caractereespecial($string) {

    // matriz de entrada
    $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', ' ', '-', '(', ')', ',', ';', ':', '|', '!', '"', '#', '$', '%', '&', '/', '=', '?', '~', '^', '>', '<', 'ª', 'º');

    // matriz de saída
    $by = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_', '_');

    // devolver a string
    return str_replace($what, $by, $string);
}
