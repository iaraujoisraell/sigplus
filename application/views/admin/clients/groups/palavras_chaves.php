<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(isset($client)){ ?>
<h4 class="customer-profile-group-heading"><?php echo _l('palavras_chaves'); ?></h4>
<div class="col-md-12">

 <a href="#" class="btn btn-success mtop15 mbot10" onclick="slideToggle('.userpalavra'); return false;"><?php echo _l('new_palavra'); ?></a>
 <div class="clearfix"></div>
<div class="row">
     <hr class="hr-panel-heading" />
</div>
 <div class="clearfix"></div>
 <div class="userpalavra hide">
    <?php echo form_open(admin_url( 'misc/add_palavra/'.$client->userid.'/customer')); ?>
    <?php echo render_input( 'palavra_chave', 'palavra_chave'); ?>
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
                <th width="50%">
                    <?php echo _l( 'clients_notes_table_description_heading'); ?>
                </th>
                
                
                <th>
                    <?php echo _l( 'options'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($user_palavras_chaves as $note){ ?>
            <tr>
               
                <td width="50%">
                  <div data-palavra_chave-description="<?php echo $note['id']; ?>">
                    <?php echo check_for_links($note['palavra_chave']); ?>
                </div>
               
          </td>
         
        
        <td>
            <?php //if($note['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
           
            <a href="<?php echo admin_url('misc/delete_palavra/'. $note['id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
            <?php //}  ?>
        </td>
    </tr>
    <?php } ?>
</tbody>
</table>
</div>
<?php } ?>
