<?php defined('BASEPATH') or exit('No direct script access allowed');

$table_data = array(
  _l('invoice_dt_table_heading_number'),
    array(
    'name'=>_l('invoice_dt_table_heading_client'),
    'th_attrs'=>array('class'=>(isset($client) ? 'not_visible' : ''))
  ),
  'Competência', 
  'Data Envio',
  'Data Vencimento',  
  'Subtotal',
  'Glosa Antes',  
  'Subtotal antes do imposto',
  'Impostos',  
  'Glosa Pós',
  'Total',  
  //'Valor Pago',
  //'Valor Aberto',
//  _l('invoice_total_tax'),
/*  array(
    'name'=>_l('invoice_estimate_year'),
    'th_attrs'=>array('class'=>'not_visible')
  ),*/
  

  //_l('invoice_dt_table_heading_duedate'),
  'Status'
    );
$custom_fields = get_custom_fields('invoice',array('show_on_table'=>1));
foreach($custom_fields as $field){
  array_push($table_data,$field['name']);
}
$table_data = hooks()->apply_filters('invoices_table_columns', $table_data);
render_datatable($table_data, (isset($class) ? $class : 'invoices_receber'));
?>
