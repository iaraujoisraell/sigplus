<?php

    $v = "";
/* if($this->input->post('name')){
  $v .= "&product=".$this->input->post('product');
} */ 

if ($this->input->post('provider')) {
    $v .= "&provider=" . $this->input->post('provider');
}
if ($this->input->post('contrato')) {
    $v .= "&contrato=" . $this->input->post('contrato');
}
if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->input->post('end_date');
}

?>


<script>
    $(document).ready(function () {
          function attachment(x) {
            if (x != null) {
                return '<a href="' + site.base_url + 'assets/uploads/' + x + '" target="_blank"><i class="fa fa-chain"></i></a>';
            }
            return x;
        }
        
          function status(x) {
            
            if(x == "ABERTO"){
                return '<div style="background-color : yellow; width: 100%;  height: 40px; " >'+ x +'</div>';
            }else  if(x == "PARCIAL"){
                 return '<div style="background-color : blue; width: 100%; height: 40px; " >'+ x +'</div>';
            }else  if(x == "PAGO"){
                 return '<div style="background-color : green; width: 100%; height: 40px; " >'+ x +'</div>';
            }else  if(x == "ATRASADO"){
                 return '<div style="background-color : red; color: #ffffff; width: 100%; height: 40px; " >'+ x +'</div>';
            }
            return x;
        }
        
        var oTable = $('#SLData').dataTable({
            "aaSorting": [[0, "asc"], [1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=site_url('financeiro/getSales/?v=1'. $v .'&'. ($warehouse_id ? '/' . $warehouse_id : ''))?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                //$("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                nRow.id = aData[0];
               // nRow.className = "invoice_link";
                //if(aData[7] > aData[9]){ nRow.className = "product_link warning"; } else { nRow.className = "product_link"; }
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": fld}, null, null, null, null,{"mRender": currencyFormat}, {"mRender": currencyFormat}, 
                    {"mRender": status}, {"mRender": attachment}, {"bSortable": false}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, paid = 0, balance = 0;
                for (var i = 0; i < aaData.length; i++) {
             //       gtotal += parseFloat(aaData[aiDisplay[i]][6]);
                    paid += parseFloat(aaData[aiDisplay[i]][7]);
                    balance += parseFloat(aaData[aiDisplay[i]][8]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[6].innerHTML = currencyFormat(parseFloat(paid));
                nCells[7].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('Data Vencto');?> ]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('account');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('suppliers');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('description');?>]", filter_type: "text", data: []},
      
            {column_number: 5, filter_default_label: "[<?=lang('parcela');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
        ], "footer");

      

    });

</script>

<?php 
	   echo form_open('financeiro/sale_actions', 'id="action-form"');
    
        
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-money"></i><?=lang('Títulos dos Contratos');?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?=site_url('financeiro/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('Adicionar Títulos')?>
                                
                            </a>
                        </li>
                        <li>
                            <a href="#" id="excel" data-action="export_excel">
                                <i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="pdf" data-action="export_pdf">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
                            </a>
                        </li>
                        
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="bpo"
                            title="<b><?=$this->lang->line("delete_sales")?></b>"
                            data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
                            data-html="true" data-placement="left">
                            <i class="fa fa-trash-o"></i> <?=lang('delete_sales')?>
                        </a>
                    </li>
                    </ul>
                </li>
               
            </ul>
        </div>
    </div>
    
    
    <?=form_close()?>
<?php  //}



?>
    
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                 <p class="introtext"><?= lang('Filtro_de_busca'); ?></p>

                <div id="form">

                    <?php  echo form_open('financeiro', 'id="action-form"'); ?>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group">
                                    <?= lang("Contrato", "contrato"); ?>
                                    <?php
                                    $contratos = $this->site->getAllContratos();
                                    $bl2[""] = "Selecione o Contrato";
                                    foreach ($contratos as $contrato) {
                                        $bl2[$contrato->id] = $contrato->contrato.' - '.$contrato->company;
                                    }
                                    echo form_dropdown('contrato', $bl2, (isset($_POST['contrato']) ? $_POST['contrato'] : ""), 'id="contrato" data-placeholder="' . lang("select") . ' ' . lang("contrato") . '"  class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                        </div>
                        <div class="col-md-3">
                                        <div class="form-group">
                                            <?= lang("provider", "slcustomer"); ?>
                                                <?php
                                                $providers = $this->site->getAllCompanies('supplier');
                                                $wh2[''] = '';
                                                foreach ($providers as $provider) {
                                                    $wh2[$provider->id] = $provider->company;
                                                }
                                                echo form_dropdown('provider', $wh2, (isset($_POST['provider']) ? $_POST['provider'] : ""), 'id="slcustomer" class="form-control  select" data-placeholder="' . lang("select") . ' ' . lang("provider") . '"  style="width:100%;" ');
                                                ?>
                                          </div>
                                    </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("Buscar"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>
               

                <div class="table-responsive">
                    <table style="width: 100%;" id="SLData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th style="width: 5%;"><?php echo $this->lang->line("Data Vencto"); ?></th>
                            <th style="width: 10%;"><?php echo $this->lang->line("Contrato"); ?></th>
                            <th style="width: 20%;"><?php echo $this->lang->line("suppliers"); ?></th>
                            <th style="width: 300%;"><?php echo $this->lang->line("description"); ?></th>
                           
                            <th style="width: 5%;"><?php echo $this->lang->line("parcela"); ?></th>
                            <th style="width: 5%;"><?php echo $this->lang->line("value_pagar"); ?></th>
                            <th style="width: 5%;"><?php echo $this->lang->line("value_pago"); ?></th>
                            <th style="width: 5%;"><?php echo $this->lang->line("payment_status"); ?></th>
                            <th style="width: 5%; text-align: center;"><i class="fa fa-chain"></i>
                            </th>
                            <th style="width: 5%; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="11"
                                class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="width: 5%; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th style="width: 5%;"></th>
                            <th style="width: 10%;"></th>
                            <th style="width: 20%;"></th>
                            <th style="width: 30%;"></th>
                            <th style="width: 5%;"></th>
                            <th style="width: 5%;"><?php echo $this->lang->line("paid"); ?></th>
                            <th style="width: 5%;"><?php echo $this->lang->line("balance"); ?></th>
                            <th style="width: 5%;"></th>
                             <th style="width: 5%; text-align: center;"><i class="fa fa-chain"></i>
                            </th>
                            <th style="width: 5%; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

