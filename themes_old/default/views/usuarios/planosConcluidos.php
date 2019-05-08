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
            if (x != null) {
                return '<a href="' + site.base_url + 'assets/uploads/' + x + '" target="_blank"><i class="fa fa-chain"></i></a>';
            }
            return x;
        }
        var oTable = $('#SLData').dataTable({
            "aaSorting": [ [0, "DESC"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=site_url('planos/getPlanosConcluidos/?v=1'. $v .'&'. ($warehouse_id ? '/' . $warehouse_id : ''))?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                
                nRow.id = aData[0];
               
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null, null, null, null, {"mRender": fld},{"mRender": fld},{"mRender": fld}, {"mRender": fld}, null, null,  {"bSortable": false}],
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
            {column_number: 1, filter_default_label: "[<?=lang('ATA');?> ]", filter_type: "text", data: []},
                {column_number: 2, filter_default_label: "[<?=lang('ID');?> ]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('O que');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('Quem');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('Dt Criação');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('Dt Prazo');?>]", filter_type: "text", data: []},
             {column_number: 7, filter_default_label: "[<?=lang('Dt Demanda');?>]", filter_type: "text", data: []},
              {column_number: 8, filter_default_label: "[<?=lang('Dt Conclusão');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('Como');?>]", filter_type: "text", data: []},
            {column_number: 10, filter_default_label: "[<?=lang('Status');?>]", filter_type: "text", data: []}
        ], "footer");

        

    

    });

</script>

<?php 


if ($Owner || $GP['bulk_actions']) {
	   echo form_open('planos/plano_actions', 'id="action-form"');
	}
        
    
 $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto;        
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-clock-o"></i><?=lang('Ações Concluídas') . ' (' .  lang($projetos_usuario->projeto) . ')';?>
        </h2>

       
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
                            <th><?php echo $this->lang->line("ATA"); ?></th>
                             <th><?php echo $this->lang->line("ID"); ?></th>
                            <th><?php echo $this->lang->line("O QUE"); ?></th>
                            <th><?php echo $this->lang->line("QUEM"); ?></th>
                            <th><?php echo $this->lang->line("Enviada em:"); ?></th>
                            <th><?php echo $this->lang->line("QUANDO"); ?></th>
                            <th><?php echo $this->lang->line("Dt Entrega Demanda"); ?></th>
                            <th><?php echo $this->lang->line("Dt Conclusão"); ?></th>
                           <th><?php echo $this->lang->line("Como"); ?></th>
                           <th><?php echo $this->lang->line("Status"); ?></th>
                            
                            
                            <th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="12"
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
                            <th></th>
                            <th></th>
                                                         
                            <th style="width:80px; text-align:center;"><?php echo $this->lang->line("Opções"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

