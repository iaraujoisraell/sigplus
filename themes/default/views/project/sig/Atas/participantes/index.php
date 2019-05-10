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
<style>
.esquerda{text-align:left;}
.centro{text-align:center;}
.direita{text-align:right;}
</style>

<script>
$(document).ready(function() {
 
 
$('#SLData').addClass('centro');

 
 
});
</script>

<script>
    $(document).ready(function () {
          function attachment(x) {
            if (x) {
                return '<a href="' + site.base_url + 'assets/uploads/atas/' + x + '" target="_blank"><i class="fa fa-chain"></i></a>';
            }
            return x;
        }
        
        function status(x) {
            if (x == 1) {
                return '<i class="fa fa-check"></i>';
            }else{
                return '<i class="fa fa-circle-o"></i>';
            }
            return x;
        }
        var oTable = $('#SLData').dataTable({
            "aaSorting": [[0, "asc"], [1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=site_url('atas/getParticipantes/?v=1'. $v .'&'. ($warehouse_id ? '/' . $warehouse_id : ''))?>',
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
            },null,  null, null, null,   {"bSortable": false}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, paid = 0, balance = 0;
                for (var i = 0; i < aaData.length; i++) {
             //       gtotal += parseFloat(aaData[aiDisplay[i]][6]);
                  //  paid += parseFloat(aaData[aiDisplay[i]][7]);
                 //   balance += parseFloat(aaData[aiDisplay[i]][8]);
                }
                var nCells = nRow.getElementsByTagName('th');
                var nCells = nRow.ge;
                //nCells[7].innerHTML = currencyFormat(parseFloat(paid));
                //nCells[8].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('NOME');?> ]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('CARGO');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('SETOR');?>]", filter_type: "text", data: []},
           
            {column_number: 4, filter_default_label: "[<?=lang('EMAIL');?>]", filter_type: "text", data: []},
        
            
        ], "footer");

        

    

    });

</script>

<?php 


if ($Owner || $GP['bulk_actions']) {
	   echo form_open('atas/ata_actions', 'id="action-form"');
	}
        
    
           $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto;
           
           
           
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-users"></i><?=lang('CADASTRO DE PARTICIPANTES - ATAS') ;?>
        </h2>

        <div class="box-icon">
           
           
            <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?=site_url('atas/add_participante')?>"> 
             <i class="fa fa-plus-circle"></i>   <?=lang('Novo Cadastro')?>
            </a>
          
        
           
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
                    <table style="width: 100%" id="SLData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th style="width: 25%; text-align: center;" ><?php echo $this->lang->line("NOME"); ?></th>
                            
                            <th style="width: 20%; text-align: center;"><?php echo $this->lang->line("CARGO"); ?></th>
                            <th style="width: 20%; text-align: center;"><?php echo $this->lang->line("SETOR"); ?></th>
                           
                            <th style="width: 20%; text-align: center;"><?php echo $this->lang->line("EMAIL"); ?></th>
                     
                            
                            
                            <th style="width:10%; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align:center;" colspan="11"
                                class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style=" width: 5%; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th style=" width: 25%; text-align: center;"</th>
                            <th style=" width: 20%; text-align: center;"></th>
                            <th style=" width: 20%; text-align: center;"></th>
                            <th style=" width: 20%; text-align: center;"></th>
                            
                           
                     
                            <th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

