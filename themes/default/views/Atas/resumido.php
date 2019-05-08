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
 
 
$('#example-table').addClass('centro');

 
 
});
</script>



<?php 


if ($Owner || $GP['bulk_actions']) {
	   echo form_open('financeiro/sale_actions', 'id="action-form"');
	}
        
     $usuario = $this->session->userdata('user_id');
           $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
           $projetos_usuario->projeto;
        
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-list"></i><?=lang('LISTA DE ATAS - RESUMIDO') . ' (' . (lang($projetos_usuario->projeto)) . ')';?>
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
    
                
                    <div class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     <h3>ATAS</h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-lg-12">
                                <div style="text-align: right" class="col-lg-12">
                                    <font style="color: red">LEGENDA: </font><i class="fa fa-check"></i>: ATA FINALIZADA    /  <i class="fa fa-circle-o"></i>: ATA ABERTA
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                
                                                <th><?php echo $this->lang->line("ATA"); ?></th>
                                                <th><?php echo $this->lang->line("Responsável"); ?></th>
                                                <th><?php echo $this->lang->line("Dt ATA"); ?></th>
                                                <th><?php echo $this->lang->line("Status"); ?></th>
                                                <th><?php echo $this->lang->line("Total de Ações"); ?></th>
                                                <th><?php echo $this->lang->line("Ações Concluídas"); ?></th>
                                                <th><?php echo $this->lang->line("Ações Pendentes"); ?></th>
                                                <th><?php echo $this->lang->line("% Conclusão"); ?></th>
                                               


                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $soma_acoes = 0;
                                                $soma_pendentes = 0;
                                                $soma_concluidos = 0;
                                                
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($atas as $ata) {
                                                       
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td><?php echo $ata->id; ?></td>
                                                <td><?php echo $ata->responsavel_elaboracao; ?>      </td>
                                                <td class="center"> <?php echo $ata->data_ata; ?> </td>
                                                
                                                <?php if( $ata->status == '1'){ ?>
                                                <td><i class="fa fa-check"></i></td>
                                                <?php }else if($ata->status == '0'){ ?>
                                                 <td><i class="fa fa-circle-o"></i></td>
                                                <?php } ?>
                                                <td>
                                                    <?php $totalacoes = $this->atas_model->getPlanoByAtaID($ata->id);
                                                    echo $totalacoes->totalplanos; 
                                                    $soma_acoes += $totalacoes->totalplanos;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php $totalconcluidas = $this->atas_model->getPlanoConcluidoByAtaID($ata->id,'CONCLUÍDO');
                                                    echo $totalconcluidas->totalConcluidos;
                                                    $soma_concluidos += $totalconcluidas->totalConcluidos;?>
                                                </td>
                                                
                                                <td class="center"><?php $totalpendentes = $this->atas_model->getPlanoConcluidoByAtaID($ata->id,'PENDENTE');
                                                    echo $totalpendentes->totalConcluidos; 
                                                    $soma_pendentes += $totalpendentes->totalConcluidos;?></td>
                                                <td class="center">
                                                    <?php
                                                    $percentual = (($totalconcluidas->totalConcluidos * 100) / $totalacoes->totalplanos);
                                                    if($percentual){
                                                    echo substr($percentual, 0, 4). ' %'; 
                                                    }else{
                                                    echo  'N/A';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                                <?php
                                                }
                                                ?>
                                            <tr class="odd gradeX">
                                                <td> </td>
                                                <td> </td>
                                                <td> TOTAL </td>
                                                <td> </td>
                                                <td> <?php echo $soma_acoes; ?> </td>
                                                <td> <?php echo $soma_concluidos; ?></td>
                                                <td> <?php echo $soma_pendentes; ?></td>
                                                <td>
                                                    <?php
                                                    $percentual = (($soma_concluidos * 100) / $soma_acoes);
                                                    if($percentual){
                                                    echo substr($percentual, 0, 4). ' %'; 
                                                    }else{
                                                    echo  'N/A';
                                                    }
                                                    ?>
                                                   
                                                </td>
                                                <td> </td>
                                            </tr>
                                            
                                           
                                            
                                        </tbody>
                                        
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col-lg-12 -->

                
            
