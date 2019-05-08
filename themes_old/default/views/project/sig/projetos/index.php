<?php

    $v = "";
/* if($this->input->post('name')){
  $v .= "&product=".$this->input->post('product');
} */

if ($this->input->post('user')) {
    $v .= "&user=" . $this->input->post('user');
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
            
                return '<div style="background-color : '+ x +'; width: 100px; height: 40px; " ></div>';
            
            return x;
        }
        var oTable = $('#SLData').dataTable({
            "aaSorting": [[0, "asc"], [1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=site_url('projetos/getProjetos/?v=1'. $v .'&'. ($warehouse_id ? '/' . $warehouse_id : ''))?>',
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
            }, null, {"mRender": fld}, {"mRender": fld}, {"mRender": fld}, null, null,null, 
                    null, {"mRender": attachment}, {"bSortable": false}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, paid = 0, balance = 0;
                for (var i = 0; i < aaData.length; i++) {
             //       gtotal += parseFloat(aaData[aiDisplay[i]][6]);
                  //  paid += parseFloat(aaData[aiDisplay[i]][7]);
                 //   balance += parseFloat(aaData[aiDisplay[i]][8]);
                }
                var nCells = nRow.getElementsByTagName('th');
                //nCells[7].innerHTML = currencyFormat(parseFloat(paid));
                //nCells[8].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('Projeto');?> ]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('DT Início');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('DT Final');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('DT Virada');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('Gerente da Área');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('Gerente EDP');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('Gerente Fornecedor');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('Status');?>]", filter_type: "text", data: []},
            
        ], "footer");

        

    

    });

</script>

<?php 


if ($Owner || $GP['bulk_actions']) {
	   echo form_open('projetos/projeto_actions', 'id="action-form"');
	}
        
    
        
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-briefcase"></i><?=lang('Projetos') . ' (' . ($warehouse_id ? $warehouse->name : lang('Todos os Projetos')) . ')';?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                       <?php
                       $usuario = $this->session->userdata('user_id');
                       
                       if(($usuario==2)||($usuario==18)){
                       ?>
                         <li>
                            <a href="<?=site_url('projetos/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('Novo Projeto')?>
                                
                            </a>
                        </li>
                        
                       <?php } ?> 
                       
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
                        <?php
                          if(($usuario==2)||($usuario==18)){
                       ?>
                        <li>
                                <a href="#" class="bpo"
                                title="<b><?=$this->lang->line("Apagar Projeto(s)")?></b>"
                                data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
                                data-html="true" data-placement="left">
                                <i class="fa fa-trash-o"></i> <?=lang('Apagar Projeto(s)')?>
                            </a>
                        </li>
                        
                       <?php } ?> 
                        
                    </ul>
                </li>
               
            </ul>
        </div>
    </div>
    
    <?php if ($Owner || $GP['bulk_actions']) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?=form_close()?>
<?php }



?>
    
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
           
                

                <div class="table-responsive">
                    <table id="SLData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("Projeto"); ?></th>
                            <th><?php echo $this->lang->line("DT Início"); ?></th>
                            <th><?php echo $this->lang->line("DT Final"); ?></th>
                            <th><?php echo $this->lang->line("DT Virada"); ?></th>
                            <th><?php echo $this->lang->line("Gerente da Área"); ?></th>
                            <th><?php echo $this->lang->line("Gerente EDP"); ?></th>
                            <th><?php echo $this->lang->line("Gerente Fornecedor"); ?></th>
                            <th><?php echo $this->lang->line("Status"); ?></th>
                            <th>Cor do Projeto</th>
                            
                            
                            <th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
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
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                           
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                           
                             <th><i class="fa fa-chain"></i></th>
                            <th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

