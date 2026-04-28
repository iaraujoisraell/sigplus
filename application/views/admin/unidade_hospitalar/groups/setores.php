<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(isset($unidade)){ ?>
<h4 class="customer-profile-group-heading"><?php echo 'Setores'; ?></h4>
<div class="col-md-12">

 <a href="#" class="btn btn-success mtop15 mbot10" onclick="slideToggle('.userpalavra'); return false;"><?php echo _l('novo setor'); ?></a>
 <div class="clearfix"></div>
<div class="row">
     <hr class="hr-panel-heading" />
</div>
 <div class="clearfix"></div>
 <div class="userpalavra hide">
    <?php //echo form_open(admin_url( 'unidades_hospitalares/add_setor/'.$client->userid.'/customer')); ?>
   <?php echo form_open(admin_url( 'unidades_hospitalares/add_setor/')); ?>
      <?php $id_unidade = $unidade->id; ?>
     <input type="hidden" id="id" name="unidade_id" class="form-control" <?php echo $id_unidade?>" value=" <?php echo $id_unidade?> <?php echo $group->unidade_id; ?>">
  
 <?php echo render_input( 'nome', 'nome setor'); ?>
    <button class="btn btn-info pull-right mbot15">
        <?php echo _l( 'submit'); ?>
    </button>
    <?php echo form_close(); ?>
</div>
<div class="clearfix"></div>
<div class="mtop15">
    <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
        <thead>
            <tr>
                 <th>
                    <?php echo _l( '#'); ?>
                </th>
                <th width="50%">
                    <?php echo _l( 'clients_notes_table_description_heading'); ?>
                </th>
                
                
                <th>
                    <?php echo _l( 'options'); ?>
                </th>
                
                <th>
                    <?php echo _l( 'unidade'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            $setores_medicos = $this->Unidades_hospitalares_model->get_setores($id_unidade);
            foreach($setores_medicos as $group){ ?>  
            <tr>
                     
                <td>
              
                    <?php echo check_for_links($group['id']); ?>
        
          </td>
         
          <td width="50%">
                 
                    <?php //echo check_for_links($group['nome']); ?>
              
               <div data-note-description="<?php echo $group['id']; ?>">
                    <?php echo check_for_links($group['nome']); ?>
                   <?php //echo check_for_links($note['description']); ?>
                </div>
               
                      <div data-note-edit-textarea="<?php echo $group['id']; ?>" class="hide">
                    <textarea name="nome" class="form-control" rows="4"><?php echo clear_textarea_breaks($group['nome']); ?></textarea>
                    <div class="text-right mtop15">
                      <button type="button" class="btn btn-default" onclick="toggle_edit_setores(<?php echo $group['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                      <button type="button" class="btn btn-info" onclick="edit_setor(<?php echo $group['id']; ?>);"><?php echo _l('update_note'); ?></button>
                  </div>
              </div>
                
                 </td>
          
        <td>
            <?php //if($note['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
           
             
            <?php if($group['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
            <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_setores(<?php echo $group['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
            <?php } ?>
        
            
            <a href="<?php echo admin_url('Unidades_hospitalares/delete_setor/'. $group['id'].'/'.$id_unidade); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
            <?php //}  ?>
        </td>
        
        
        
                  <td>
              
                    <?php echo check_for_links($group['unidade_id']); ?>
        
          </td>
    </tr>
    <?php } ?>
</tbody>
</table>
</div>
<?php } ?>
