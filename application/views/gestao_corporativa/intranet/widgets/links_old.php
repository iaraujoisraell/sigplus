<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
      <div class="widget-dragger"></div>
      
      <div class="box box-primary">
        <div class="box-header">
          <i class="fa fa-external-link"></i>

          <h3 class="box-title">Links Externos</h3>

          <div class="box-tools pull-right">
            <ul class="pagination pagination-sm inline">

            </ul>
          </div>
        </div>
        <div class="box-body">
        <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
            <?php
                $cont_cat = 1;
                foreach ($categorias_links as $cat_link){ ?>
                <li <?php if($cont_cat == 1){ ?> class="active" <?php } ?>><a href="#tab_link_<?php echo $cat_link->id; ?>" data-toggle="tab"><?php echo $cat_link->categoria; ?></a></li>
                
            <?php $cont_cat++; } ?>    
              
            </ul>  
        <div class="tab-content">
          <?php
                $cont_cat2 = 1;
                foreach ($categorias_links as $cat_link){  ?>
                
                <div class="tab-pane <?php if($cont_cat2 == 1){ ?> active <?php } ?>" id="tab_link_<?php echo $cat_link->id; ?>">
                    
                    <?php
                    $links_destaques = $this->Link_model->get_links_destaques_by_categoria_user_id($cat_link->id)->result();
                    foreach ($links_destaques as $links){
                        $icon = '';
                        $backgroud = '';
                        if($links->icon){
                            $icon = $links->icon;
                        }else if($links->img){
                            $img = $links->img;
                            $url = base_url().'assets/intranet/img/links/'.$img;
                            
                        }
                        
                        if ((!$links->icon) && (!$links->img)){
                            $icon = 'fa fa-link';
                        }
                        
                        if($links->color){
                            $backgroud = 'background-color: '.$links->color;
                        }else{
                            $backgroud = 'background-color: blue';
                        }
                        
                    ?>
                    
                     <div class="col-md-1 col-12">
                        <div class="card card-plain text-center">
                            <div class="card-body">
                                <a href="<?php echo $links->url; ?>" target="_blank">
                                    
                                    <?php if($icon){?>
                                    <div class="icon icon-shape  text-center border-radius-md mb-2" style="margin-left: auto; margin-right: auto; <?php echo $backgroud; ?>" >
                                        <i class="fa <?php echo $icon; ?>"></i> 
                                    </div>    
                                    <?php } else if($img){  ?>
                                    <div class="icon icon-shape  text-center border-radius-md mb-2" style="margin-left: auto; margin-right: auto; " >    
                                        <img style="width: 50px; height: 50px;" src="<?php echo $url; ?>"   > 
                                    </div>    
                                    <?php } ?>
                                    
                                </a>
                                <p class="text-sm font-weight-normal mb-2"><?php echo $links->nome; ?></p>
                            </div>
                        </div>
                    </div>    
                        
                    
                    <?php } ?>  
                  
                </div>
            <?php $cont_cat2 ++; } ?> 
            
            
          
          
        </div>
            <!-- /.tab-content -->
        </div>
        </div>    
    </div>
      
</div>



