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
if ($this->input->post('status')) {
    $v .= "&status=" . $this->input->post('status');
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
            'sAjaxSource': '<?=site_url('Historico_Acoes/getPlanos/?v=1'. $v .'&'. ($warehouse_id ? '/' . $warehouse_id : ''))?>',
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
                
            },  {"mRender": fld}, null, null, null, null],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
              
                var nCells = nRow.getElementsByTagName('th');
                //nCells[7].innerHTML = currencyFormat(parseFloat(paid));
                //nCells[8].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('ATA');?> ]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('ID');?> ]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('O que');?> ]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('Quem');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('Por que');?>]", filter_type: "text", data: []},
     
        ], "footer");

        

    

    });

</script>

<?php 

$Owner = true;
                     
if ($Owner || $GP['bulk_actions']) {
	   echo form_open('planos/plano_actions', 'id="action-form"');
	}
        
    
 $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto;        
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-list"></i><?=lang('Histórico das Ações') . ' (' .  lang($projetos_usuario->projeto) . ')';?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                       
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
           <p class="introtext"><?= lang('Filtro_de_busca'); ?></p>

                <div id="form">

                    <?php echo form_open("planos"); ?>
                    <div class="row">

                        <div class="col-sm-3">
                           <div class="form-group">
                                    <?= lang("Responsável", "slResponsavel"); ?>
                                    <?php
                                    $wu4[''] = '';
                                    foreach ($users as $user) {
                                        $wu4[$user->id] = $user->username;
                                    }
                                    echo form_dropdown('user', $wu4, (isset($_POST['user']) ? $_POST['user'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;"  ');
                                    ?>
                                </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Prazo DE", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Prazo ATÉ", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime"  id="end_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Status", "tipo"); ?>
                                <?php $pst[''] = ' ';
                                
                                  $pst['PENDENTE'] = lang('PENDENTE');
                                  $pst['CONCLUÍDO'] = lang('CONCLUÍDO   ');
                                  $pst['AGUARDANDO VALIDAÇÃO'] = lang('AGUARDANDO VALIDAÇÃO');
                                  
                                echo form_dropdown('status', $pst, (isset($_POST['status']) ? $_POST['status'] : ""), 'id="tipo" class="form-control "  data-placeholder="' . lang("select") . ' ' . lang("o Status") . '"    style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> 
                                <?php echo form_submit('submit_report', $this->lang->line("Pesquisar"), 'class="btn btn-primary"'); ?> 
                            <a  class="btn btn-danger" href="<?= site_url('Historico_Acoes/novoRegistro'); ?>"><?= lang('Novo Registro') ?></a>
                        </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>
                

                <div class="table-responsive">
                    <table id="SLData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th >
                               <?php echo $this->lang->line("ID"); ?>
                            </th>
                            <th ><?php echo $this->lang->line("DATA"); ?></th>
                            <th><?php echo $this->lang->line("SETOR"); ?></th>
                            <th><?php echo $this->lang->line("SUPERINTENDENCIA"); ?></th>
                            <th><?php echo $this->lang->line("QTDE"); ?></th>
                            <th><?php echo $this->lang->line("STATUS"); ?></th>
                            
                            
                            
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="7"
                                class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th >
                              
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            
                            
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

