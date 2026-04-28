<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
    <div class="items-select-wrapper">
        <select name="procedimentos[]" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?>
            <?php if(has_permission('items','','create')){ echo ' _select_input_group'; } ?>" data-width="false" id="item_select" multiple="true" data-none-selected-text="<?php echo 'Add Procedimento'; ?>" data-live-search="true">
          <option value=""></option>
          <?php foreach($procedimentos as $group_id=>$_items){ ?>
          <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?><?php echo ' ( '. $_items[0]['convenio'].' )'; ?>">
           <?php foreach($_items as $item){ ?>
           <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>">(<?php echo app_format_number($item['rate']); ; ?>) <?php echo $item['description']; ?></option>
           <?php } ?>
         </optgroup>
         <?php } ?>
       </select>
     </div>
      
 <?php /* if(has_permission('items','','create')){ ?>
 <div class="input-group-addon">
   <a href="#" data-toggle="modal" data-target="#sales_item_modal">
    <i class="fa fa-plus"></i>
  </a>
</div>
<?php } */ ?>




