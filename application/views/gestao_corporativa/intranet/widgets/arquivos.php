<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
      <div class="card">
              <div class="card-header">
                <h3 class="card-title">Arquivos (10 últimos)</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  
                </div>
              </div>
               <?php
                    foreach ($arquivos_recebidos as $a){
                        $time = date($a->dt_created);
                        $now = strtotime(date('m/d/Y H:i:s'));
                        $time = strtotime($time);
                        $diff = $now - $time;
                        
                        $seconds = $diff;
                        $minutes = round($diff / 60);
                        $hours = round($diff / 3600);
                        $days = round($diff / 86400);
                        $weeks = round($diff / 604800);
                        $months = round($diff / 2419200);
                        $years = round($diff / 29030400);

                        if ($seconds <= 60) {
                            $ago = '1 min atrás';
                        } else if ($minutes <= 60) {
                            $ago = ($minutes == 1 ? '1 min atrás' : $minutes . ' min atrás');
                        } else if ($hours <= 24) {
                            $ago = ($hours == 1 ? '1 hrs atrás' : $hours . ' hrs atrás');
                            $new = true;
                        } else if ($days <= 7) {
                            $ago = ($days == 1 ? '1 dia atras' : $days . ' dias atrás');
                        } else if ($weeks <= 4) {
                            $ago = ($weeks == 1 ? '1 semana atrás' : $weeks . ' semanas atrás');
                        } else if ($months <= 12) {
                            $ago = ($months == 1 ? '1 mês atrás' : $months . ' meses atrás');
                        } else {
                            $ago = ($years == 1 ? 'um ano atrás' : $years . ' anos atrás');
                        }
                        
                        $create_id = $a->user_create;
                        $user = $this->Staff_model->get_one($create_id)->row();
                        $name = $user->firstname.' '.$user->lastname;
                ?>  
              <div class="card-body p-0">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  <li class="item">
                    <div class="product-img">
                      <img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                    </div>
                    <div class="product-info">
                      <a  href="<?php echo base_url("assets/intranet/img/docs/$a->file"); ?>" class="product-title" target="_blank">  
                      <?php echo $a->titulo; ?>
                        <span class="badge badge-warning float-right"><?php echo $ago; ?></span></a>
                      <span class="product-description">
                       <?php echo $name; ?>
                      </span>
                    </div>
                  </li>
                 
                </ul>
              </div>
              <?php } ?>

              <div class="card-footer text-center">
                <a href="javascript:void(0)" class="uppercase">Ver Todos</a>
              </div>
              <!-- /.card-footer -->
            </div>
      
      
      
</div>


