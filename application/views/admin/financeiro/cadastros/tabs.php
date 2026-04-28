<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
      <?php 
  foreach(filter_client_visible_tabs($customer_tabs) as $key => $tab){
    ?>
    <li class="<?php if($key == 'profile'){echo 'active ';} ?>customer_tab_<?php echo $key; ?>">
      <a data-group="<?php echo $key; ?>" href="<?php echo admin_url('financeiro/cadastros/?group='.$key); ?>">
        <?php if(!empty($tab['icon'])){ ?>
            <i class="<?php echo $tab['icon']; ?> menu-icon" aria-hidden="true"></i>
        <?php } ?>
        <?php echo $tab['name']; ?>
      </a>
    </li>
  <?php } ?>
  <?php
  //foreach(filter_client_visible_tabs($customer_tabs) as $key => $tab){
    /*
    ?>
    <li class="active customer_tab_profile_medico">
      <a data-group="profile_medico" href="<?php echo admin_url('medicos/medico/'.$medico->medicoid.'?group=profile_medico'); ?>">
            <i class="fa fa-user-circle menu-icon" aria-hidden="true"></i>
        <?php echo 'Cadastro'; ?>
      </a>
    </li>
    
    <li class=" customer_tab_procedimentos">
      <a data-group="procedimentos" href="<?php echo admin_url('medicos/medico/'.$medico->medicoid.'?group=procedimentos'); ?>">
            <i class="fa fa-clipboard menu-icon" aria-hidden="true"></i>
        <?php echo 'Procedimentos'; ?>
      </a>
    </li>
    
     <li class=" customer_tab_procedimentos">
      <a data-group="procedimentos" href="<?php echo admin_url('medicos/medico/'.$medico->medicoid.'?group=procedimentos'); ?>">
            <i class="fa fa-clipboard menu-icon" aria-hidden="true"></i>
        <?php echo 'Procedimentos'; ?>
      </a>
    </li>
    
    
    
  <?php //} */ ?>
</ul>

