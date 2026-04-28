
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
      <?php 
  foreach(filter_client_visible_tabs($customer_tabs) as $key => $tab){
    ?>
    <li class="<?php if($key == 'profile'){echo 'active ';} ?>customer_tab_<?php echo $key; ?>">
      <a data-group="<?php echo $key; ?>" href="<?php echo base_url('gestao_corporativa/intranet_admin/index/?group='.$key); ?>">
        <?php if(!empty($tab['icon'])){ ?>
            <i class="<?php echo $tab['icon']; ?> menu-icon" aria-hidden="true"></i>
        <?php } ?>
        <?php echo $tab['name']; ?>
      </a>
    </li>
    <?php } ?>
    <li class="<?php if($key == 'profile'){echo 'active ';} ?>customer_tab_<?php echo $key; ?>">
      <a data-group="<?php echo $key; ?>" href="<?php echo base_url('gestao_corporativa/intranet_admin/index/?group=cdc_settings'); ?>">
       
            <i class="fa fa-file menu-icon" aria-hidden="true"></i>
       
        <?php echo "CDC - Configurações"; ?>
      </a>
    </li>
 
</ul>

