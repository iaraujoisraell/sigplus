<?php
defined('BASEPATH') or exit('No direct script access allowed');
$aColumns = [];
$where  = [];


if (has_permission('items', '', 'delete')) {
    $aColumns[] = '1';
}

$aColumns = array_merge($aColumns, [
    db_prefix() . 'caixas_repasses.data_repasse as data_repasse',
    db_prefix() . 'conta_financeira.nome as conta',
    db_prefix() . 'caixas_repasses.valor as valor',
    db_prefix() . 'payment_modes.name as forma',
    db_prefix() . 'caixas.name as caixa',
    db_prefix() . 'caixas_repasses.observacao as observacao',
    db_prefix() .'caixas_repasses.usuario_log',
    ]);

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'caixas_repasses';

$join = [
    'INNER JOIN ' . db_prefix() . 'conta_financeira  ON ' . db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'caixas_repasses.conta_id',
    'INNER JOIN ' . db_prefix() . 'caixas            ON ' . db_prefix() . 'caixas.id = ' . db_prefix() . 'caixas_repasses.caixa_id',
    'INNER JOIN ' . db_prefix() . 'payment_modes     ON ' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'caixas_repasses.forma_id',
    ];
$additionalSelect = [
    db_prefix() .'caixas_repasses.id',
    db_prefix() .'caixas_repasses.conta_id',
    db_prefix() .'caixas_repasses.data_log',
    db_prefix() .'caixas_repasses.usuario_log',
    db_prefix() .'caixas_repasses.conta_id',
    ];

$custom_fields = get_custom_fields('items');
 
foreach ($custom_fields as $key => $field) {
    $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);

    array_push($customFieldsColumns, $selectAs);
    array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'items.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="items_pr" AND ctable_' . $key . '.fieldid=' . $field['id']);
}

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}


if ($this->ci->input->post('conta_financeira')) {
    $conta_financeira = $this->ci->input->post('conta_financeira');
   //print_r($conta_financeira); 
    $_convenios = "";
    if ($conta_financeira) {
       array_push($where, ' AND '. db_prefix() . 'caixas_repasses.conta_id IN (' . implode(', ', $conta_financeira) . ')');
    }
    
    
   
}


//FILTRO DATA REPASSE

if ($this->ci->input->post('data_de')) {
    $data_de = $this->ci->input->post('data_de');
    
    if ($this->ci->input->post('data_ate')) {
        $data_ate = $this->ci->input->post('data_ate');
    }else{
        $data_ate = $data_de;
    }
   
    $_procedimentos = [];
    if ($data_de && $data_ate) {
     array_push($where, ' AND '. db_prefix() . 'caixas_repasses.data_repasse = '. "'$data_de'" );
    }
   
}

//echo $data_de.'<br>';
//echo $data_ate.'<br>';
//exit;

// FILTRO CATEGORIAS





// array_push($where, ' AND '.db_prefix() . 'items.ativo = 1' );
//$where   = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        
        if ($aColumns[$i] == '1') {
             $item_id =  $aRow['id']; 
            $_data = "<label>$item_id</label>";  
           // $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
        } elseif ($i == '1') {
            $data_repasse = _d($aRow['data_repasse']);
            $_data = $data_repasse;
        } elseif ($i == '3') {
            $rate = $aRow['valor'];
            $rate_v = app_format_money($rate, ' R$ ');
            $_data = "$rate_v";
        }else if ($i == '7') {  
            $usuario = get_staff_full_name($aRow['usuario_log']);
            $data_log = _d($aRow['data_log']);
            $_data = "$data_log <br> $usuario";
            
        } else {
        
            if (startsWith($aColumns[$i], 'ctable_') && is_date($_data)) {
                $_data = _d($_data);
            }
        }

        $row[]              = $_data;
        $row['DT_RowClass'] = 'has-row-options';
    }


    $output['aaData'][] = $row;
}
