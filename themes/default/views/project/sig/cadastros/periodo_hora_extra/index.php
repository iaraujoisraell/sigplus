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




<?php 


if ($Owner || $GP['bulk_actions']) {
	   echo form_open('projetos/projeto_actions', 'id="action-form"');
	}
        
    
        
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-briefcase"></i><?=lang('Cadastro da Equipe do Projeto');?>
        </h2>

        <div class="box-icon">
            <div class="fprom-group">
            <a class="btn btn-primary" href="<?=site_url('cadastros/add_equipe')?>"> 
             <i class="fa fa-plus-circle"></i>   <?=lang('Nova Equipe')?>
            </a>
          </div>
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
           
                

                <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                
                                                <th>Id</th>
                                                <th>Equipe</th>
                                                <th>Membros</th>
                                              
                                                <th>Editar</th>
                                              
                                               


                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($equipes as $equipe) {
                                                       
                                                ?>   
                                            <tr class="odd gradeX">
                                                <td><?php echo $cont++; ?></td>
                                                <td><?php echo $equipe->nome; ?></td>
                                                
                                                <?php $membros = $this->atas_model->getMebrosEquipeByEquipe($equipe->id); ?>
                                                <td class="center"> 
                                                    <table style="width: 100%;" id="example-table" class="table table-striped table-bordered table-hover table-green">
                                                        <?php
                                                        foreach ($membros as $membro) {
                                                            ?>
                                                        <tr>
                                                            <td style="text-align:left; width: 60%;">
                                                                <?php echo $membro->name.' '.$membro->last.' - '.$membro->setor; ?>
                                                            </td> 
                                                             <td style="text-align:left; width: 30%;">
                                                                <?php echo $membro->papel; ?>
                                                                 <p><?php echo $membro->descricao; ?></p>
                                                            </td> 
                                                            <td class="center" style="text-align:left; width: 10%;">
                                                           <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('cadastros/delete_membro_equipe/'.$membro->id); ?>"></a>
                                                
                                                        </td>  
                                                        </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </table>    
                                                </td>
                                               
                                                      <td class="center">
                                                            <a style="color: #D37423;" class="btn fa fa-edit"  href="<?= site_url('cadastros/add_equipe_mebros/'.$equipe->id); ?>"></a>
                                                        </td>
                                                         
                                                
                                            </tr>
                                                <?php
                                                }
                                                ?>
                                            
                                            
                                           
                                            
                                        </tbody>
                                        
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
            </div>
        </div>
    </div>
</div>

