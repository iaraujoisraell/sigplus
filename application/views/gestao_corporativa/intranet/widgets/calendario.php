<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
     <div class="card">
        
                  <div class="card-header">
                    <h3 class="card-title">Calendário</h3>

                    <div class="card-tools">
                     
                      
                    </div>
                  </div>  
        
        <div class="box-body">
        <div class="card card-calendar h-100" id="events">
                            <div class="card-body p-3">
                                <div class="calendar" data-bs-toggle="calendar" id="calendar_intranet"></div>
                            </div>
                        </div>
        </div>    
    </div>
      
</div>


