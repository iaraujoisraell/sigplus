<?php defined('BASEPATH') or exit('No direct script access allowed');
$is_admin = is_admin();
$i = 0;

foreach ($statuses as $status) {
  
  $dados_status = $this->leads_model->get_status($status);
  $total_pages = ceil($this->leads_model->do_kanban_query($status,$this->input->get('search'),1,array(),true, $campanha_id)/get_option('leads_kanban_limit'));
  
  $settings = '';
  foreach(get_system_favourite_colors() as $color){
    $color_selected_class = 'cpicker-small';
    if($color == $dados_status->color){
      $color_selected_class = 'cpicker-big';
    }
    $settings .= "<div class='kanban-cpicker cpicker ".$color_selected_class."' data-color='".$color."' style='background:".$color.";border:1px solid ".$color."'></div>";
  }
  ?>
  <ul class="kan-ban-col" data-col-status-id="<?php echo $status; ?>" data-total-pages="<?php echo $total_pages; ?>">
    <li class="kan-ban-col-wrapper">
      <div class="border-right panel_s">
        <?php
        $status_color = '';
        if(!empty($dados_status->color)){
          $status_color = 'style="background:'.$dados_status->color.';border:1px solid '.$dados_status->color.'"';
        }
        ?>
        <div class="panel-heading-bg primary-bg" <?php if($dados_status->isdefault == 1){ ?>data-toggle="tooltip" data-title="<?php echo _l('leads_converted_to_client') . ' - '. _l('client'); ?>"<?php } ?> <?php echo $status_color; ?> data-status-id="<?php echo $dados_status->id; ?>">
          <div class="kan-ban-step-indicator<?php if($dados_status->isdefault == 1){ echo ' kan-ban-step-indicator-full'; } ?>"></div>
          <i class="fa fa-reorder pointer"></i>
          <span class="heading pointer" <?php if($is_admin){ ?> data-order="<?php echo $dados_status->statusorder; ?>" data-color="<?php echo $dados_status->color; ?>" data-name="<?php echo $dados_status->name; ?>" onclick="edit_status(this,<?php echo $dados_status->id; ?>); return false;" <?php } ?>><?php echo $dados_status->name; ?>
          </span>
          <a href="#" onclick="return false;" class="pull-right color-white kanban-color-picker kanban-stage-color-picker<?php if($dados_status->isdefault == 1){ echo ' kanban-stage-color-picker-last'; } ?>" data-placement="bottom" data-toggle="popover" data-content="
            <div class='text-center'>
              <button type='button' return false;' class='btn btn-success btn-block mtop10 new-lead-from-status'>
                <?php echo _l('new_lead'); ?>
              </button>
            </div>
            <?php if (is_admin()){?>
            <hr />
            <div class='kan-ban-settings cpicker-wrapper'>
              <?php echo $settings; ?>
            </div><?php } ?>" data-html="true" data-trigger="focus">
            <i class="fa fa-angle-down"></i>
          </a>
        </div>
        <div class="kan-ban-content-wrapper">
          <div class="kan-ban-content">
            <ul class="status leads-status sortable" data-lead-status-id="<?php echo $dados_status->id; ?>">
              <?php
              $leads = $this->leads_model->do_kanban_query($dados_status->id,$this->input->get('search'),1,array('sort_by'=>$this->input->get('sort_by'),'sort'=>$this->input->get('sort')), false, $campanha_id);
              $total_leads = count($leads);
              foreach ($leads as $lead) {
                $this->load->view('admin/leads/_kan_ban_card',array('lead'=>$lead, 'status'=>$status,'base_currency'=>$base_currency, 'campanha_id'=>$campanha_id));
              } ?>
              <?php if($total_leads > 0 ){ ?>
              <li class="text-center not-sortable kanban-load-more" data-load-status="<?php echo $dados_status->id; ?>">
              <a href="#" class="btn btn-default btn-block<?php if($total_pages <= 1){echo ' disabled';} ?>" data-page="1" onclick="kanban_load_more(<?php echo $dados_status->id; ?>,this,'leads/leads_kanban_load_more',315,360); return false;";>
              <?php echo _l('load_more'); ?>
              </a>
             </li>
             <?php } ?>
             <li class="text-center not-sortable mtop30 kanban-empty<?php if($total_leads > 0){echo ' hide';} ?>">
              <h4>
                <i class="fa fa-circle-o-notch" aria-hidden="true"></i><br /><br />
                <?php echo _l('no_leads_found'); ?></h4>
              </li>
            </ul>
          </div>
        </div>
      </li>
  </ul>
    <?php $i++; } ?> 
