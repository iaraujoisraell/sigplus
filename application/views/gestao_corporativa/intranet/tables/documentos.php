<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [];
$where = [];

//if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
//}

$aColumns = array_merge($aColumns, [
    db_prefix() . '_intranet_documento.codigo as codigo',
    db_prefix() . '_intranet_documento.titulo as titulo',
    db_prefix() . '_intranet_documento.data_publicacao as date',
    db_prefix() . '_intranet_documento_categoria.titulo as categoria',
    db_prefix() . 'departments.abreviado as setor',
    db_prefix() . '_intranet_documento.numero_versao as versao',
    db_prefix() . '_intranet_documento.file as file'
        ]);
$sIndexColumn = 'id';
$sTable = db_prefix() . '_intranet_documento';

$join = [
    'LEFT JOIN ' . db_prefix() . '_intranet_documento_categoria ON ' . db_prefix() . '_intranet_documento_categoria.id = ' . db_prefix() . '_intranet_documento.categoria_id',
    'LEFT JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . '_intranet_documento.setor_id',
];

$additionalSelect = [
    'tbl_intranet_documento.id',
    'tbl_intranet_documento.user_cadastro',
    'tbl_intranet_documento.pdf_principal as pdf_principal',
    'tbl_intranet_documento.sequencial as sequencial',
    'tbl_intranet_documento.pasta_destino as pasta_destino',
    'tbl_intranet_documento.file as doc'
];

// Fix for big queries. Some hosting have max_join_limit
// FILTRO CONVÊNIOS
$empresa_id = $this->ci->session->userdata('empresa_id');
$staff_id = get_staff_user_id();
/* FILTRO CATEGORIAS
  if ($this->ci->input->post('categorias')) {
  $categorias = $this->ci->input->post('categorias');
  if (is_array($categorias)) {
  array_push($where, ' AND '. db_prefix() . 'fin_plano_contas.categoria_id IN (' . implode(', ', $categorias) . ')');
  }

  }

  //FILTRO PROCEDIMENTOS

  if ($this->ci->input->post('natureza')) {
  $natureza = $this->ci->input->post('natureza');
  if ($natureza) {
  array_push($where, ' AND '. db_prefix() . 'fin_plano_contas.natureza = '.$natureza);
  }

  } */

array_push($where, 'AND ' . db_prefix() . '_intranet_documento.deleted = 0');
array_push($where, 'AND ' . db_prefix() . '_intranet_documento.publicado = 1');
array_push($where, 'AND ' . db_prefix() . '_intranet_documento.versao_atual = 1');
//array_push($where, 'AND ' . db_prefix() . '_intranet_documento.id_principal is null');
array_push($where, ' AND ' . db_prefix() . '_intranet_documento.empresa_id = ' . $empresa_id);

//$where   = [];

$orderby = " order by tbl_intranet_documento.sequencial desc";

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect, "", "", $orderby);
$output = $result['output'];
$rResult = $result['rResult'];
//print_r($rResult);
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
       
        if ($aColumns[$i] == '1') {
            $item_id = $aRow['sequencial'];
            $_data = "<label>$item_id</label>";
            // $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
        } else if ($i == '1') {
            $_data = '<a href="' . base_url('gestao_corporativa/intra/documentos/see?id=' . $aRow['id']) . '">' . $aRow['codigo'] . '</a>';
            $_data .= '<div class="row-options">';
            if ($staff_id == $aRow['user_cadastro']) {
                $_data .= ' <a href="' . base_url('gestao_corporativa/intra/Documentos/delete_doc' . '?id=' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        } else {

            if (startsWith($aColumns[$i], 'ctable_') && is_date($_data)) {
                $_data = _d($_data);
            }
        }
        
        $row[] = $_data;
        $row['DT_RowClass'] = 'has-row-options';
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        $aRow['date'] = implode('/', array_reverse(explode('-', $aRow['date'])));
        $caminho = '';
        
        $aRow['file'] = '<a type="button" href="' . base_url('gestao_corporativa/intra/documentos/destinatarios?id=' . $aRow['id']) . '" class="btn btn-xs btn-success"  ><i class="fa fa-users"></i></a>'
                . '<a type="button" href="' . base_url('gestao_corporativa/intra/documentos/historico_versao?id=' . $aRow['id']) . '" class="btn btn-xs btn-warning" style="margin-left: 2px;"><i class="fa fa-list"></i></a>';
                
        if ($aRow['pdf_principal'] == 1) {
            $documento = $aRow['doc'];
            $destino = $aRow['pasta_destino'];
            //echo ('<a  target="_blank" href="'. base_url() . 'media' . $destino . $documento.'" class="btn btn-xs btn-primary" style="margin-left: 2px;"><i class="fa fa-file-pdf-o"></i></a>'); exit;;
            $aRow['file'] .= '<a type="button" target="_blank" href="'. base_url() . 'media' . $destino. $documento.'" class="btn btn-xs btn-primary" style="margin-left: 2px;"><i class="fa fa-file-pdf-o"></i></a>';
            //echo base_url() . 'media' . $destino . $documento; exit;
            
        } else {
            $caminho = base_url('gestao_corporativa/intra/documentos/visualizar_conteudo?id='.$aRow['id']);
            $aRow['file'] .= '<a type="button" target="_blank" href="'.$caminho.'" class="btn btn-xs btn-primary" style="margin-left: 2px;"><i class="fa fa-file-pdf-o"></i></a>';
        }
    }


    $output['aaData'][] = $row;
}
?>
