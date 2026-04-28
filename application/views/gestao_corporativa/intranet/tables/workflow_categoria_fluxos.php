<?php

defined('BASEPATH') or exit('No direct script access allowed');
global $cores_distintas;
$cores_distintas = array(
    1 => "#FF4500", // Laranja avermelhado
    2 => "#FF8C00", // Laranja
    3 => "#FFD700", // Dourado
    4 => "#32CD32", // Verde-limão
    5 => "#00FF00", // Verde
    6 => "#FF7F50", // Coral claro
    7 => "#0000FF", // Azul
    8 => "#4B0082", // Índigo escuro
    9 => "#800080", // Roxo
    10 => "#FF1493", // Rosa vibrante
    11 => "#FF69B4", // Rosa
    12 => "#FF00FF", // Magenta
    13 => "#FF6347", // Vermelho-coral
    14 => "#FF0000", // Vermelho
    15 => "#FFFACD", // Amarelo-limão
    16 => "#FFE4B5", // Bege pêssego
    17 => "#E0FFFF", // Azul-celeste
    18 => "#FAEBD7", // Branco-antigo
    19 => "#F0FFF0", // Menta
    20=> "#00FFFF", // Ciano
    
);





$aColumns = [
    'codigo_sequencial as codigo_sequencial',
    db_prefix() . 'departments.name as titulo',
    'prazo as prazo',
    'objetivo as objetivo',
    'contato_cliente as contato',
    'finaliza_cliente as finaliza',
    'user_cadastro as user_cadastro',
    'data_cadastro as date',
    'id as id',
];

$sIndexColumn = 'id';

$sTable = db_prefix() . '_intranet_categorias_fluxo';

$where = [];

$join = ['LEFT JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_categorias_fluxo.setor'];

$additionalSelect = [];

$empresa_id = $this->ci->session->userdata('empresa_id');

array_push($where, 'AND ' . db_prefix() . '_intranet_categorias_fluxo.deleted = 0');
$categoria_id = $this->ci->input->post('categoria_id');
array_push($where, 'AND ' . db_prefix() . "_intranet_categorias_fluxo.categoria_id = $categoria_id");
array_push($where, 'AND ' . db_prefix() . '_intranet_categorias_fluxo.empresa_id = ' . $empresa_id);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, $GroupBy, "", $orderby);

$output = $result['output'];

$rResult = $result['rResult'];
$this->ci->load->model('Workflow_model');
foreach ($rResult as $aRow) {

    $row = [];
    $row[] = formatarCodigoSequencial($aRow['codigo_sequencial']) . '</span>';

    $setor = $aRow['titulo'] . '<br>' . $aRow['codigo_sequencial'];
    $setor .= '<p>';
    if ($aRow['contato'] == 1) {
        $setor .= '<span class="ticket-label label label-default inline-block"><i class="fa fa-user"></i> Contato Cliente</span>';
    }
    if ($aRow['finaliza'] == 1) {
        $setor .= '<span class="ticket-label label label-success inline-block"><i class="fa fa-user"></i> Finaliza Cliente</span>';
    }
    $seguintes = $this->ci->Workflow_model->get_fluxos_seguintes($aRow['id']);
    if (count($seguintes) == 0) {
        $setor .= ' <span class="ticket-label label label-danger inline-block">END</span>';
    }
    $setor .= '</p>';
    $row[] = $setor;

    if (!$this->ci->input->post('curto')) {
        $row[] = $aRow['prazo'] . ' DIAS';
        
        $objetivo = $aRow['objetivo'];

        $row[] = $objetivo;
        $row[] = '<span class="ticket-label label label-default inline-block">' . $this->ci->Workflow_model->get_prazo_corrido($aRow['id']) . ' DIAS CORRIDOS</span>';
        
    }


    $options = '';
    //if (has_permission_intranet('registro_ocorrencia', '', 'edit_categoria') || is_admin()) {
    $options = '<a class="btn btn-info btn-xs mleft" onclick="edit(' . $aRow['id'] . ');"><i class="fa fa-pencil"></i></a>';
    //}
    //if($aRow['anonimo'] == 1){
    $options .= ' <a class="btn btn-success btn-xs mleft" onclick="campos_personalizados(' . $aRow['id'] . ');"><i class="fa fa-list-ol"></i></a>';
    $options .= ' <a class="btn btn-warning btn-xs mleft" onclick="question(' . $aRow['id'] . ');"><i class="fa fa-question"></i></a>';
    //}
    //if (has_permission_intranet('registro_ocorrencia', '', 'delete_categoria') || is_admin()) {
    //if (count($seguintes) == 0) {
    //$options .= '<a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" onclick="delete_fluxo(' . $aRow['id'] . ');"><i class="fa fa-trash"></i></a>';
    //}
    //}
    $row[] = $options;
    $row[] = 'indefinido';
    $row[] = 'indefinido';

    $output['aaData'][] = $row;
}

function formatarCodigoSequencial($codigo_sequencial) {
    
    global $cores_distintas; // Declarar a variável global dentro da função
    // Divide o código sequencial em partes
    $partes = explode('.', $codigo_sequencial);

    // Calcula o nível com base na quantidade de partes
    $nivel = count($partes);

    // Retorna o nível como a ordem formatada
    $espacos = str_repeat('&nbsp;&nbsp;', $nivel - 1);

    // Retorna o código sequencial formatado com espaços
    return $espacos . '<span>|_</span><span style="background : '.$cores_distintas[$nivel].'; color: white;" data-toggle="tooltip" data-title="" class="label label-default inline-block level-' . $nivel . '" >' . $nivel . '</span>';
}
