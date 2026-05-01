<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @since  2.3.3
 * Get available staff permissions, modules can use the filter too to hook permissions
 * @param  array  $data additional data passed from view role.php and member.php
 * @return array
 */
function get_available_staff_permissions($data = []) {
    $viewGlobalName = _l('permission_view') . '(' . _l('permission_global') . ')';

    $allPermissionsArray = [
        'view_own' => _l('permission_view_own'),
        'view' => $viewGlobalName,
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    $gestaoPermissionsArray = [
        'dashboarad_faturamento' => 'Dashboard Faturamento'
    ];

    $portariaPermissionsArray = [
        'portaria' => 'Portaria'
    ];
    
    $FaturaPermissionsArray = [
        'view_own' => _l('permission_view_own'),
        'view' => $viewGlobalName,
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
        'producao_aba' => _l('producao_aba'),
        'desconto_aba' => _l('desconto_aba'),
        'campo_qty' => _l('editar_campo_qty'),
        'campo_valor' => _l('editar_campo_valor'),
        'campo_desconto' => _l('editar_campo_desconto'),
        'campo_destino_desconto' => _l('editar_campo_destino_desconto'),
    ];

    $menuFaturamentoPermissionsArray = [
        'view' => 'Acessar Menu',
        'controle_guia' => 'Controle de guia',
        'receber_guia' => 'Receber Guia',
        'cancelar_receber_guia' => 'Cancelar Recebimento da Guia',
    ];

    $ReportsPermissionsArray = [
        'view' => $viewGlobalName,
        'agendas' => _l('agendas'),
        'vendas' => _l('vendas'),
        'tesouraria' => _l('tesouraria'),
        'producao' => _l('producao'),
        'faturamento' => _l('faturamento'),
    ];

    $withoutViewOwnPermissionsArray = [
        'view' => $viewGlobalName,
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    $CaixaTesourariaPermissionsArray = [
        'view' => $viewGlobalName,
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
        'open' => _l('open_caixa'),
        'close' => _l('close_caixa'),
        'close_all' => _l('close_all_caixa'),
        'repasses' => _l('repasses'),
    ];

    $MedicosPermissionsArray = [
        //    'view'     => $viewGlobalName,
        'anamnese' => _l('anamnese'),
        'agenda' => _l('agenda'),
        'producao' => _l('producao'),
        'repasses' => _l('repasses'),
        'cadastro' => _l('cadastro'),
    ];

    $withNotApplicableViewOwn = array_merge(['view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')]], $withoutViewOwnPermissionsArray);

    $corePermissions = [
        'bulk_pdf_exporter' => [
            'name' => _l('bulk_pdf_exporter'),
            'capabilities' => [
                'view' => $viewGlobalName,
            ],
        ],
        'dashboard_gestao' => [
            'name' => 'Dashboard Gestão',
            'capabilities' => $gestaoPermissionsArray,
        ],
        'portaria' => [
            'name' => 'Portaria',
            'capabilities' => $portariaPermissionsArray,
        ],
        'contracts' => [
            'name' => _l('contracts'),
            'capabilities' => $allPermissionsArray,
        ],
        'credit_notes' => [
            'name' => _l('credit_notes'),
            'capabilities' => $allPermissionsArray,
        ],
        'customers' => [
            'name' => _l('clients'),
            'capabilities' => $withNotApplicableViewOwn,
            'help' => [
                'view_own' => _l('permission_customers_based_on_admins'),
            ],
        ],
        'email_templates' => [
            'name' => _l('email_templates'),
            'capabilities' => [
                'view' => $viewGlobalName,
                'edit' => _l('permission_edit'),
            ],
        ],
        'estimates' => [
            'name' => _l('estimates'),
            'capabilities' => $allPermissionsArray,
        ],
        'expenses' => [
            'name' => _l('expenses'),
            'capabilities' => $allPermissionsArray,
        ],
        'invoices' => [
            'name' => _l('invoices'),
            'capabilities' => $FaturaPermissionsArray,
        ],
        'faturamento_menu' => [
            'name' => _l('faturamento'),
            'capabilities' => $menuFaturamentoPermissionsArray,
        ],
        'items' => [
            'name' => _l('items'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        'tesouraria' => [
            'name' => _l('tesouraria_caixa'),
            'capabilities' => $CaixaTesourariaPermissionsArray,
        ],
        'medico' => [
            'name' => _l('acesso_medico'),
            'capabilities' => $MedicosPermissionsArray,
        ],
        'knowledge_base' => [
            'name' => _l('knowledge_base'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        'payments' => [
            'name' => _l('payments'),
            'capabilities' => $withNotApplicableViewOwn,
            'help' => [
                'view_own' => _l('permission_payments_based_on_invoices'),
            ],
        ],
        'projects' => [
            'name' => _l('projects'),
            'capabilities' => $withNotApplicableViewOwn,
            'help' => [
                'view' => _l('help_project_permissions'),
                'view_own' => _l('permission_projects_based_on_assignee'),
            ],
        ],
        'proposals' => [
            'name' => _l('proposals'),
            'capabilities' => $allPermissionsArray,
        ],
        'reports' => [
            'name' => _l('reports'),
            'capabilities' => $ReportsPermissionsArray,
        ],
        'roles' => [
            'name' => _l('roles'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        'settings' => [
            'name' => _l('settings'),
            'capabilities' => [
                'view' => $viewGlobalName,
                'edit' => _l('permission_edit'),
            ],
        ],
        'staff' => [
            'name' => _l('staff'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        'departments' => [
            'name' => _l('staff'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        'subscriptions' => [
            'name' => _l('subscriptions'),
            'capabilities' => $allPermissionsArray,
        ],
        'tasks' => [
            'name' => _l('tasks'),
            'capabilities' => $withNotApplicableViewOwn,
            'help' => [
                'view' => _l('help_tasks_permissions'),
                'view_own' => _l('permission_tasks_based_on_assignee'),
            ],
        ],
        'checklist_templates' => [
            'name' => _l('checklist_templates'),
            'capabilities' => [
                'create' => _l('permission_create'),
                'delete' => _l('permission_delete'),
            ],
        ],

        'cdc' => [
            'name' => 'MODULO CDC',
            'capabilities' => [
                'create' => _l('permission_create'),
                'delete' => _l('permission_delete'),
                'publicacao_imediata' => 'Publicação Imediata',
            ],
        ],
    ];

    $addLeadsPermission = true;
    if (isset($data['staff_id']) && $data['staff_id']) {
        $is_staff_member = is_staff_member($data['staff_id']);
        if (!$is_staff_member) {
            $addLeadsPermission = false;
        }
    }

    if ($addLeadsPermission) {
        $corePermissions['leads'] = [
            'name' => _l('leads'),
            'capabilities' => [
                'view' => $viewGlobalName,
                'delete' => _l('permission_delete'),
            ],
            'help' => [
                'view' => _l('help_leads_permission_view'),
            ],
        ];
    }

    return hooks()->apply_filters('staff_permissions', $corePermissions, $data);
}

function get_available_staff_permissions_intranet($data = []) {
    

    $withoutViewOwnPermissionsArray = [
        'view' => 'Visualizar aba',
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    $withNotApplicableViewOwn = array_merge(['view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')]], $withoutViewOwnPermissionsArray);

    $corePermissions = [
        // --- APLICADO
        'home_view' => [
            'name' => 'Home',
            'capabilities' => [
                'view_feed' => 'Feed',
                'view_links_destaque' => 'Links Destaque',
                'view_banners' => 'Banners',
                'view_noticias' => 'Noticias',
                'view_aniversariantes' => 'Aniversariantes',
                'view_calendario' => 'Calendario',
            ],
        ],
        // --- APLICADO
        'modules' => [
            'name' => 'Módulos',
            'capabilities' => [
                'view_cis' => 'Comunicados Internos',
                'view_atas' => 'Atas',
                'view_planos_acao' => 'Planos de Ação',
                'view_grupos' => 'Grupos',
                'view_eventos_intra' => 'Eventos (intranet)',
                'view_docs' => 'Documentos',
                'view_projects' => 'Projetos',
                'view_tasks' => 'Tarefas',
                'view_forms' => 'Formulários',
                'view_ros' => 'Registros de Ocorrência',
                'view_geds' => 'GED',
                'view_ras' => 'Registros de Atendimento',
                'view_workflows' => 'Workflow',
                'view_work_group' => 'Grupos de trabalho',
                'view_events' => 'Eventos',
                'view_action_plan' => 'Planos de ação',
            ],
        ],
        // --- APLICADO
        'menu_lateral_view' => [
            'name' => 'Menu Lateral',
            'capabilities' => [
                'view_admin' => 'Área Administrativa',
            ],
        ],
        // --- APLICADO
        'menu_top_view' => [
            'name' => 'Menu Superior',
            'capabilities' => [
                'view_chat' => 'Chat',
                'view_contacts' => 'Contatos',
                'view_itens_task' => 'Ítens de Tarefas',
                'view_notify' => 'Notificações',
                'view_menus' => 'Menus Personalizados',
            ],
        ],
        
        'feed' => [
            'name' => 'Feed',
            'capabilities' => [
                'create' => 'Criar',
                'coment' => 'Comentar',
                'view_coments' => 'Visualizar Comentários',
                'delete' => 'Deletar(Somemnte criado pelo mesmo)',
            ],
        ],
        // --- APLICADO
        'comunicado_interno' => [
            'name' => 'Comunicados Internos',
            'capabilities' => [
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Excluir',
                'manage_all' => 'Gerenciar todos (não só os próprios)',
            ],
        ],
        'atas' => [
            'name' => 'Atas',
            'capabilities' => [
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Excluir',
            ],
        ],
        'planos_acao' => [
            'name' => 'Planos de Ação',
            'capabilities' => [
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Excluir',
            ],
        ],
        'grupos' => [
            'name' => 'Grupos',
            'capabilities' => [
                'edit' => 'Editar (admin)',
                'delete' => 'Excluir (admin)',
            ],
        ],
        'eventos' => [
            'name' => 'Eventos',
            'capabilities' => [
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Excluir',
            ],
        ],
        // --- APLICADO
        'gestao_documento' => [
            'name' => 'Gestão de Documentos',
            'capabilities' => [
                'create' => 'Criar',
            ],
        ],
        // --- APLICADO
        'registro_ocorrencia' => [
            'name' => 'Registro de Ocorrência',
            'capabilities' => [
                'create' => 'Criar',
            ],
        ],
        'registro_atendimento' => [
            'name' => 'Registro de Atendimento',
            'capabilities' => [
                'create' => 'Criar',
            ],
        ],
        'workflow' => [
            'name' => 'Workflow',
            'capabilities' => [
                'create' => 'Criar',
            ],
        ],
       
        
        // ----- ÁREA ADMINISTRATIVA ------
        // --- APLICADO
        'company' => [
            'name' => 'Empresa - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'notification' => 'Notificações',
                'list' => 'Cadastrar/Editar/Excluir Listas',
            ],
        ],
        'publicacoes' => [
            'name' => 'Publicações - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Deletar',
            ],
        ],
        // --- APLICADO
        'lisks_destaque' => [
            'name' => 'Links Destaque - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Deletar',
                'crud_categoria' => 'CRUD Categorias',
            ],
        ],
        // --- APLICADO
        'lisks_externos' => [
            'name' => 'Links Destaque - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create' => 'Criar',//
                'edit' => 'Editar',//
                'delete' => 'Deletar',//
                'crud_categoria' => 'CRUD Categorias',
            ],
        ],
        // --- APLICADO
        'eventos' => [
            'name' => 'Eventos - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',//
                'create' => 'Criar',//
                'delete' => 'Deletar',//
            ],
        ],
        // --- APLICADO
        'menus_paginas' => [
            'name' => 'Menus - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Deletar',
            ],
        ],
        // --- APLICADO
        'grupos' => [
            'name' => 'Grupos - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Deletar',
            ],
        ],
        
        'staff' => [
            'name' => 'Colaboradores - ÁREA ADMINISTRATIVA',
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        
        'departments' => [
            'name' => 'Departamentos - ÁREA ADMINISTRATIVA',
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        
        // --- APLICADO
        'tipos_usuarios' => [
            'name' => 'Tipos de Usuário - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Deletar',
            ],
            
        ],
         
        'workflow_settings' => [
            'name' => 'Workflow Configurações - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create_categoria' => 'Criar Categorias',
                'edit_categoria' => 'Editar Categorias',
                'delete_categoria' => 'Deletar Categorias',
            ],
        ],
        'ro_settings' => [
            'name' => 'RO Configurações - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create_categoria' => 'Criar Categorias',
                'edit_categoria' => 'Editar Categorias',
                'delete_categoria' => 'Deletar Categorias',
                'create_atuante' => 'Criar Atuantes',
                'edit_atuante' => 'Edit Atuantes',
                'delete_atuante' => 'Deletar Atuantes',
            ],
        ],
        
        'atendimento_settings' => [
            'name' => 'RA Configurações - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create_categoria' => 'Criar Categorias',
                'edit_categoria' => 'Editar Categorias',
                'delete_categoria' => 'Deletar Categorias'
            ],
        ],
        
        'documentos_settings' => [
            'name' => 'Documentos Configurações - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
            ],
        ],

        
        
        'announcements' => [
            'name' => 'Anúncios - ÁREA ADMINISTRATIVA',
            'capabilities' => [
                'view' => 'Visualizar Aba',
                'create' => 'Criar',
                'edit' => 'Editar',
                'delete' => 'Deletar'
            ],
        ],
        //////    GED
        /////     FORMULÁRIOS
        /////     PERMISÕES DE FEED
        //// TASKS
        
    ];

    return hooks()->apply_filters('staff_permissions_intranet', $corePermissions, $data);
}

/**
 * Get staff by ID or current logged in staff
 * @param  mixed $id staff id
 * @return mixed
 */
function get_staff($id = null) {
    if (empty($id) && isset($GLOBALS['current_user'])) {
        return $GLOBALS['current_user'];
    }

    // Staff not logged in
    if (empty($id)) {
        return null;
    }

    if (!class_exists('staff_model', false)) {
        get_instance()->load->model('staff_model');
    }

    return get_instance()->staff_model->get($id);
}

/**
 * Return staff profile image url
 * @param  mixed $staff_id
 * @param  string $type
 * @return string
 */
function staff_profile_image_url($staff_id, $type = 'small') {
    $url = base_url('assets/images/user-placeholder.jpg');

    if ((string) $staff_id === (string) get_staff_user_id() && isset($GLOBALS['current_user'])) {
        $staff = $GLOBALS['current_user'];
    } else {
        $CI = & get_instance();
        $CI->db->select('profile_image')
                ->where('staffid', $staff_id);

        $staff = $CI->db->get(db_prefix() . 'staff')->row();
    }

    if ($staff) {
        if (!empty($staff->profile_image)) {
            $profileImagePath = 'uploads/staff_profile_images/' . $staff_id . '/' . $type . '_' . $staff->profile_image;
            if (file_exists($profileImagePath)) {
                $url = base_url($profileImagePath);
            }
        }
    }

    return $url;
}

/**
 * Staff profile image with href
 * @param  boolean $id        staff id
 * @param  array   $classes   image classes
 * @param  string  $type
 * @param  array   $img_attrs additional <img /> attributes
 * @return string
 */
function staff_profile_image($id, $classes = ['staff-profile-image'], $type = 'small', $img_attrs = [], $style = '') {
    $url = base_url('assets/images/user-placeholder.jpg');

    $id = trim($id);

    $_attributes = '';
    foreach ($img_attrs as $key => $val) {
        $_attributes .= $key . '=' . '"' . html_escape($val) . '" ';
    }

    $blankImageFormatted = '<img style="'.$style.'" src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';

    if ((string) $id === (string) get_staff_user_id() && isset($GLOBALS['current_user'])) {
        $result = $GLOBALS['current_user'];
    } else {
        $CI = & get_instance();
        $result = $CI->app_object_cache->get('staff-profile-image-data-' . $id);

        if (!$result) {
            $CI->db->select('profile_image,firstname,lastname');
            $CI->db->where('staffid', $id);
            $result = $CI->db->get(db_prefix() . 'staff')->row();
            $CI->app_object_cache->add('staff-profile-image-data-' . $id, $result);
        }
    }

    if (!$result) {
        return $blankImageFormatted;
    }

    if ($result && $result->profile_image !== null) {
        $profileImagePath = 'uploads/staff_profile_images/' . $id . '/' . $type . '_' . $result->profile_image;
        if (file_exists($profileImagePath)) {
            $profile_image = '<img ' . $_attributes . ' src="' . base_url($profileImagePath) . '" class="' . implode(' ', $classes) . '" />';
        } else {
            return $blankImageFormatted;
        }
    } else {
        $profile_image = '<img  style="'.$style.'" src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';
    }

    return $profile_image;
}

function staff_profile_image_caminho($id, $type = 'small') {
    $url = base_url('assets/images/user-placeholder.jpg');

    $id = trim($id);
    if ((string) $id === (string) get_staff_user_id() && isset($GLOBALS['current_user'])) {
        $result = $GLOBALS['current_user'];
    } else {
        $CI = & get_instance();
        $result = $CI->app_object_cache->get('staff-profile-image-data-' . $id);

        if (!$result) {
            $CI->db->select('profile_image,firstname,lastname');
            $CI->db->where('staffid', $id);
            $result = $CI->db->get(db_prefix() . 'staff')->row();
            $CI->app_object_cache->add('staff-profile-image-data-' . $id, $result);
        }
    }

    if ($result && $result->profile_image !== null) {
        $url = base_url('uploads/staff_profile_images/' . $id . '/' . $type . '_' . $result->profile_image);
    }

    return $url;
}

/**
 * Get staff full name
 * @param  string $userid Optional
 * @return string Firstname and Lastname
 */
function get_staff_full_name($userid = '') {
    $tmpStaffUserId = get_staff_user_id();
    if ($userid == '' || $userid == $tmpStaffUserId) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->firstname . ' ' . $GLOBALS['current_user']->lastname;
        }
        $userid = $tmpStaffUserId;
    }

    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('staff-full-name-data-' . $userid);

    if (!$staff) {
        $CI->db->where('staffid', $userid);
        $staff = $CI->db->select('firstname,lastname')->from(db_prefix() . 'staff')->get()->row();
        $CI->app_object_cache->add('staff-full-name-data-' . $userid, $staff);
    }

    return html_escape($staff ? $staff->firstname . ' ' . $staff->lastname : '');
}

function get_staff_first_name($userid = '') {
    $tmpStaffUserId = get_staff_user_id();
    if ($userid == '' || $userid == $tmpStaffUserId) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->firstname . ' ' . $GLOBALS['current_user']->lastname;
        }
        $userid = $tmpStaffUserId;
    }

    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('staff-full-name-data-' . $userid);

    if (!$staff) {
        $CI->db->where('staffid', $userid);
        $staff = $CI->db->select('firstname,lastname')->from(db_prefix() . 'staff')->get()->row();
        $CI->app_object_cache->add('staff-full-name-data-' . $userid, $staff);
    }

    return html_escape($staff ? $staff->firstname : '');
}

/**
 * Get staff full name
 * @param  string $userid Optional
 * @return string Firstname and Lastname
 */
function get_departamento_nome($departmentid = '') {
    $CI = & get_instance();

    if ($departmentid == '') {
        return '';
    }


    $CI->db->where('departmentid', $departmentid);
    $department = $CI->db->select('name')->from(db_prefix() . 'departments')->get()->row();

    return html_escape($department ? $department->name : '');
}

function get_staff_department($staff = '') {
    $CI = & get_instance();


//echo 'select d.name from tblstaff_departments sd left join tbldepartments d on d.departmentid = sd.departmentid where sd.staffid = '.$staff; exit;
    $department = $CI->db->query('select d.name from tblstaff_departments sd left join tbldepartments d on d.departmentid = sd.departmentid where sd.staffid = '.$staff)->row();

    return html_escape($department ? $department->name : '');
}

/**
 * Get médico full name
 * @param  string $medicoid Optional
 * @return string nome_profissional
 */
function get_medico_full_name($medicoid = '') {
    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('medico-full-name-data-' . $medicoid);

    if (!$staff) {
        $CI->db->where('medicoid', $medicoid);
        $staff = $CI->db->select('nome_profissional')->from(db_prefix() . 'medicos')->get()->row();
        $CI->app_object_cache->add('medico-full-name-data-' . $medicoid, $staff);
    }

    return html_escape($staff ? $staff->nome_profissional : '');
}

/**
 * Get médico full name
 * @param  string $medicoid Optional
 * @return string nome_profissional
 */
function get_convenio_full_name($convenioid = '') {
    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('convenio-full-name-data-' . $convenioid);

    if (!$staff) {
        $CI->db->where('id', $convenioid);
        $staff = $CI->db->select('name')->from(db_prefix() . 'convenio')->get()->row();
        $CI->app_object_cache->add('convenio-full-name-data-' . $convenioid, $staff);
    }

    return html_escape($staff ? $staff->name : '');
}

/**
 * Get centro de custo full name
 * @param  string $medicoid Optional
 * @return string nome_profissional
 */
function get_centro_custo_full_name($custoid = '') {
    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('custo-full-name-data-' . $custoid);

    //if (!$staff) {
    $CI->db->where('id', $custoid);
    $staff = $CI->db->select('nome')->from(db_prefix() . 'centrocusto')->get()->row();
    $CI->app_object_cache->add('custo-full-name-data-' . $custoid, $staff);
    //}

    return html_escape($staff ? $staff->nome : '');
}

function get_centro_custo_financiero_full_name($custoid = '') {
    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('custo-full-name-data-' . $custoid);

    //if (!$staff) {
    $CI->db->where('id', $custoid);
    $staff = $CI->db->select('descricao')->from(db_prefix() . 'fin_centro_custo')->get()->row();
    $CI->app_object_cache->add('custo-full-name-data-' . $custoid, $staff);
    //}

    return html_escape($staff ? $staff->descricao : '');
}

/**
 * Get staff default language
 * @param  mixed $staffid
 * @return mixed
 */
function get_staff_default_language($staffid = '') {
    if (!is_numeric($staffid)) {
        // checking for current user if is admin
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->default_language;
        }

        $staffid = get_staff_user_id();
    }
    $CI = & get_instance();
    $CI->db->select('default_language');
    $CI->db->from(db_prefix() . 'staff');
    $CI->db->where('staffid', $staffid);
    $staff = $CI->db->get()->row();
    if ($staff) {
        return $staff->default_language;
    }

    return '';
}

function get_staff_recent_search_history($staff_id = null) {
    $recentSearches = get_staff_meta($staff_id ? $staff_id : get_staff_user_id(), 'recent_searches');

    if ($recentSearches == '') {
        $recentSearches = [];
    } else {
        $recentSearches = json_decode($recentSearches);
    }

    return $recentSearches;
}

function update_staff_recent_search_history($history, $staff_id = null) {
    $totalRecentSearches = hooks()->apply_filters('total_recent_searches', 5);
    $history = array_reverse($history);
    $history = array_unique($history);
    $history = array_splice($history, 0, $totalRecentSearches);

    update_staff_meta($staff_id ? $staff_id : get_staff_user_id(), 'recent_searches', json_encode($history));

    return $history;
}

/**
 * Check if user is staff member
 * In the staff profile there is option to check IS NOT STAFF MEMBER eq like contractor
 * Some features are disabled when user is not staff member
 * @param  string  $staff_id staff id
 * @return boolean
 */
function is_staff_member($staff_id = '') {
    $CI = & get_instance();
    if ($staff_id == '') {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->is_not_staff === '0';
        }
        $staff_id = get_staff_user_id();
    }

    $CI->db->where('staffid', $staff_id)
            ->where('is_not_staff', 0);

    return $CI->db->count_all_results(db_prefix() . 'staff') > 0 ? true : false;
}
