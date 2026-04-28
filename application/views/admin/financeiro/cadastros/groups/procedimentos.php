<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
 
if(isset($medico)){ ?>
<h4 class="customer-profile-group-heading"><?php echo _l('procedimento_repasse_medico'); ?></h4>
<div class="col-md-12">

 <a href="#" class="btn btn-success mtop15 mbot10" onclick="slideToggle('.userpalavra'); return false;"><?php echo _l('new_repasse_medico'); ?></a>
 <div class="clearfix"></div>
<div class="row">
     <hr class="hr-panel-heading" />
</div>
 <div class="clearfix"></div>
 <div class="userpalavra hide">
    <?php echo form_open(admin_url( 'misc/add_repasse')); ?>
     <input type="hidden" name="medicoid" value="<?php echo $medico->medicoid; ?>">
    <div class="panel-body mtop10">
    <div class="row">
        <div class="col-md-6">
            <label>Procedimentos</label>
            <div style="width: 100%" class="items-wrapper select-placeholder<?php if(has_permission('items','','create')){ echo ' input-group-select'; } ?>">
                <div class="items-select-wrapper">
                    <select required="true" style="width: 100%" name="item_id" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?><?php if(has_permission('items','','create')){ echo ' _select_input_group'; } ?>" data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                      <option value=""></option>
                      <?php foreach($items as $group_id=>$_items){ ?>
                      <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                       <?php foreach($_items as $item){ ?>
                       <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>">(<?php echo app_format_number($item['rate']); ; ?>) <?php echo $item['description']; ?></option>
                       <?php } ?>
                     </optgroup>
                     <?php } ?>
                   </select>
                 </div>
            </div>        
        </div>
        
        <div class="col-md-3">
             <?php $attrs = array('required'=>true); ?>
             <?php echo render_input( 'valor', 'valor_repasse','','text',$attrs); ?>
        </div>     
       
        <div class="col-md-3">
         <div class="form-group select-placeholder">
            <label  class="control-label"><?php echo _l('tipo_repasse_medico'); ?></label>
            <select name="tipo" class="selectpicker" data-width="100%" >
               <option value="1" selected> <?php echo 'R$ '; ?> </option>
               <option value="2"><?php echo '% '; ?></option>
              
            </select>
         </div>
        </div>
        
    
        
    </div> 
      <button class="btn btn-info pull-right mbot15">
        <?php echo _l( 'submit'); ?>
    </button>  
    </div>
    
    
    <?php echo form_close(); ?>
</div>
 </div>
<div class="clearfix"></div>
<div class="mtop15">
    <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
        <thead>
            <tr>
                <th width="5%">
                    <?php echo '#'; ?>
                </th>
                <th width="10%">
                    <?php echo 'Convênio'; ?>
                </th>
                 <th width="10%">
                    <?php echo _l( 'expense_dt_table_heading_category'); ?>
                </th>
                <th width="40%">
                    <?php echo _l( 'clients_notes_table_description_heading'); ?>
                </th>
                <th width="10%">
                    <?php echo _l('valor_procedimento'); ?>
                </th>
                <th width="10%">
                    <?php echo _l('repasse_medico'); ?>
                </th>
                <th width="10%">
                    <?php echo 'Tipo' ?>
                </th>
                <th width="10%">
                    <?php echo _l('valor_repasse'); ?>
                </th>
                
                <th>
                    <?php echo _l( 'options'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($medico_procedimentos as $note){
            
                $valor_repasse = 0;
                
                if($note['tipo'] == 1){
                    $tipo = 'R$';
                    $valor_repasse = $note['valor'];
                }else if($note['tipo'] == 2){
                    $tipo = '%';
                    $valor_repasse = ($note['valor'] / 100) * $note['rate'];
                }
                
                $ativo = $note['ativo'];
                
                
            ?>
            <tr>
                <td width="5%">
                   <div data-palavra_chave-description="<?php echo $note['mp_id']; ?>">
                    <?php echo $note['items_id']; ?>
                    </div>
                </td>
                <td width="10%">
                   <div data-palavra_chave-description="<?php echo $note['mp_id']; ?>">
                    <?php echo check_for_links($note['convenio']); ?>
                    </div>
                </td>
                <td width="10%">
                   <div data-palavra_chave-description="<?php echo $note['mp_id']; ?>">
                    <?php echo check_for_links($note['categoria']); ?>
                    </div>
                </td>
                <td width="40%">
                   <div data-palavra_chave-description="<?php echo $note['mp_id']; ?>">
                    <?php echo check_for_links($note['description']); 
                    if($ativo == 0){ ?>
                       <label class="label label-danger">Inativo</label>
                   <?php }?>
                    </div>
                </td>
                <td width="10%">
                    <?php echo app_format_money($note['rate'], ' R$'); // app_format_money($note['rate'], ' R$'); ?>
                </td>
                <td width="10%">
                    <div data-repasse-valor="<?php echo $note['mp_id']; ?>">
                    <label class="label label-success"> <?php echo $note['valor']; ?></label>   
                   </div>
                   
                   <div data-repasse-edit-valor="<?php echo $note['mp_id']; ?>" class="hide">
                        <?php $attrs = array('required'=>true); ?>
                        <?php echo render_input( 'valor', 'valor_repasse',$note['valor'],'number',$attrs); ?>
                        <div class="text-right mtop15">
                          <button type="button" class="btn btn-default" onclick="toggle_edit_historico(<?php echo $note['mp_id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                          <button type="button" class="btn btn-info" onclick="edit_repasse_medico(<?php echo $note['mp_id']; ?>);"><?php echo _l('update_valor'); ?></button>
                      </div>
                  </div>
                  
                    
                      
                </td>
                <td width="10%">
                    <?php echo $note['tipo']; ?>
                </td>
                
                <td width="10%">
                   <?php echo app_format_money($valor_repasse, ' R$'); //app_format_money(, ' R$'); ?>
                </td>
         
        
        <td>
            <?php //if($note['addedfrom'] == get_staff_user_id() || is_admin()){ ?>
            <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_repasse_medico(<?php echo $note['mp_id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
            <a href="<?php echo admin_url('misc/delete_repasse/'. $note['mp_id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-ban"></i></a>
            <?php //}  ?>
        </td>
    </tr>
    <?php } ?>
</tbody>
</table>
</div>
<?php } ?>
