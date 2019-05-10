<script>
    $(document).ready(function () {
          function attachment(x) {
            if (x) {
                return '<a href="' + site.base_url + 'assets/uploads/atas/' + x + '" target="_blank"><i class="fa fa-chain"></i></a>';
            }
            return x;
        }
        var oTable = $('#SLData').dataTable({
            "aaSorting": [[0, "asc"], [1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=site_url('atas/getAtas/?v=1'. $v .'&'. ($warehouse_id ? '/' . $warehouse_id : ''))?>',
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
            },null,null, {"mRender": fld}, null, null, null, null,null, 
                    null, {"mRender": attachment},  {"bSortable": false}],
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
            {column_number: 1, filter_default_label: "[<?=lang('ATA/Projeto');?> ]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('ATA/Projeto');?> ]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('DT ATA');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('Pauta');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('Participantes');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('Tipo');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('Responsável');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('Assinaturas');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('Status');?>]", filter_type: "text", data: []},
            
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
                class="fa-fw fa fa-briefcase"></i><?=lang('ATAS') . ' ( ' . lang( $projetos_usuario->projeto) . ')';?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?=site_url('atas/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('Nova ATA')?>
                                
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
                            title="<b><?=$this->lang->line("Apagar Ata(s)")?></b>"
                            data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
                            data-html="true" data-placement="left">
                            <i class="fa fa-trash-o"></i> <?=lang('Apagar Ata(s)')?>
                        </a>
                    </li>
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
                            <th><?php echo $this->lang->line("ATA"); ?></th>
                            
                            <th><?php echo $this->lang->line("DT ATA"); ?></th>
                            <th><?php echo $this->lang->line("Pauta"); ?></th>
                            <th><?php echo $this->lang->line("Participantes"); ?></th>
                            <th><?php echo $this->lang->line("Tipo"); ?></th>
                            <th><?php echo $this->lang->line("Resp."); ?></th>
                            <th><?php echo $this->lang->line("Assinat."); ?></th>
                             <th><?php echo $this->lang->line("Pendencias"); ?></th>
                            <th><?php echo $this->lang->line("Status"); ?></th>
                             <th><i class="fa fa-chain"></i></th>
                            
                            
                            <th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                
                                                foreach ($atas as $ata) {
                                                       $id = $ata->id;
                                                    
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td class="center"></td>
                                                <td><?php echo $id; ?></td>
                                                <td><?php echo $this->sma->hrld($ata->data_ata); ?></td>
                                                <td><?php echo $ata->pauta; ?></td>
                                                <td class="center"><?php echo $ata->participantes; ?></td>
                                                <td class="center"><?php echo $ata->tipo; ?></td>
                                                <td class="center"><?php echo $ata->responsavel_elaboracao; ?></td>
                                                 <td class="center"><?php echo $ata->assinaturas; ?></td>
                                                <td class="center"><?php echo $ata->pendencias; ?></td>
                                                <td class="center"><?php echo $ata->status; ?></td>
                                                <td class="center"></td>
                                                <td class="center"><a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('atas/deletePlano/'.$ata->idplanos.'/'.$plano->idatas); ?>"><?= lang('Apagar') ?></a></td>
                                            </tr>
                                                <?php
                                                }
                                                ?>
                                            
                                            
                                           
                                            
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

