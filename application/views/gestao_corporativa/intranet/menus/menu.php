<!-- FIM LTE-->

<div class="col-12">

    <div class="card" style="margin-top: 10px;">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3"><?php echo $title; ?></h3>
            <ul class="nav nav-pills ml-auto p-2">
                <?php if($menu->menu_pai == 0){?>
                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Principal</a></li>
                <?php }?>
                
                <?php foreach($submenus as $submenu):?>
                <li class="nav-item"><a class="nav-link <?php if($menu->id == $submenu['id']){ echo 'active'; }?>" href="#tab_<?php echo $submenu['id'];?>" data-toggle="tab"><?php echo $submenu['nome_menu'];?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php if($menu->menu_pai == 0){?>
                <div class="tab-pane active" id="tab_1">
                    <?php echo $menu->conteudo; ?>
                </div>
                <?php }?>

                <?php foreach($submenus as $submenu):?>
                <div class="tab-pane <?php if($menu->id == $submenu['id']){ echo 'active'; }?>" id="tab_<?php echo $submenu['id'];?>">
                    <?php echo $submenu['conteudo'];?>
                </div>
                <?php endforeach;?>

            </div>

        </div>
        <div class="card-footer">
            <span class="description"><?php echo $menu->firstname.' '.$menu->lastname;?> - <?php echo date("d-m-Y", strtotime($menu->data_cadastro));?></span>
        </div>


    </div>

</div>




<!-- /.content -->

<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery/jquery.min.js"></script>

