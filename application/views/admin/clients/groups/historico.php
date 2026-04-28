<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(isset($client)){ ?>
<h4 class="customer-profile-group-heading"><?php echo _l('historico'); ?></h4>
<div class="col-md-12">

 <a href="#" class="btn btn-success mtop15 mbot10" onclick="slideToggle('.usernote'); return false;"><?php echo 'Nova Anamnese'; ?></a>
 <div class="clearfix"></div>
<div class="row">
     <hr class="hr-panel-heading" />
</div>
 <div class="clearfix"></div>
 <div class="usernote hide">
    <?php echo form_open(admin_url( 'misc/add_historico/'.$client->userid.'/customer')); ?>
    <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     <?php echo render_textarea( 'historico', 'note_description', '',array( 'rows'=>5)); ?>
     
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
                    <?php echo _l( 'clients_notes_table_dateadded_heading'); ?>
                </th>
                <th width="50%">
                    <?php echo _l( 'clients_notes_table_description_heading'); ?>
                </th>
                
                
                
            </tr>
        </thead>
        <tbody>
            <?php foreach($user_historico as $note){ ?>
            <tr>
            <td data-order="<?php echo $note['id']; ?>">
             <?php  if(!empty($note['data_visita'])){ ?>
               <span data-toggle="tooltip" data-title="Data da consulta">
                  <i class="fa fa-calendar text-success font-medium valign" aria-hidden="true"></i>
              </span>
              <?php } ?>
              <?php echo date("d/m/Y", strtotime($note[ 'data_visita']));   ?>
            </td>
              
         
        
        <td>
            <?php //if($note['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
            <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_historico(<?php echo $note['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
            <a href="<?php echo admin_url('misc/delete_historico/'. $note['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
            <?php //}  ?>
        </td>
    </tr>
    <?php } ?>
</tbody>
</table>
</div>
<?php } ?>
