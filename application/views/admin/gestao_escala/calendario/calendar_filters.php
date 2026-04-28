<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="calendar_filters" style="<?php if(!$this->input->post('calendar_filters')){ echo 'display:none;'; } ?>">
    <?php echo form_open(); ?>
    <?php echo form_hidden('calendar_filters',true); ?>
    <div class="row">
        <div class="col-md-3">
            <?php hooks()->do_action('before_calendar_filters'); ?>
           
            <h3><?php echo $unidades_hospitalares->razao_social; ?></h3>
           
        </div>
        <div class="col-md-3">
                <label>Setores</label>
                <?php foreach ($setores as $plano){
                    ?>
                    <div class="checkbox">
                        <input type="checkbox" value="<?php echo $plano['id']; ?>" name="unidade_hospitalar<?php echo $plano['id']; ?>" id="unidade_hospitalar<?php echo $plano['id']; ?>"<?php if($this->input->post('unidade_hospitalar'.$plano['id'])){echo ' checked';} ?>>
                        <label for="cf_tasks"><?php echo $plano['nome']; ?></label>
                    </div>
                <?php } ?>
                   <br><br>
        </div>
        <div class="col-md-3">
            <label>Horários</label>
    
                <?php foreach ($horarios as $plano){?>
                <div class="checkbox">
                        <input type="checkbox" value="<?php echo $plano['id']; ?>" name="horario<?php echo $plano['id']; ?>" id="horario<?php echo $plano['id']; ?>"<?php if($this->input->post('horario'.$plano['id'])){echo ' checked';} ?>>
                        <label for="cf_tasks"><?php echo $plano['hora_inicio'].' - '.$plano['hora_fim']; ?></label>
                    </div>
                
                
                <?php } ?>
            
        </div>
    
    <div class="col-md-3 text-right">
        <a class="btn btn-default" href="<?php echo site_url($this->uri->uri_string()); ?>"><?php echo _l('clear'); ?></a>
        <button class="btn btn-success" type="submit"><?php echo _l('apply'); ?></button>
    </div>

</div>
<hr class="mbot15" />
<div class="clearfix"></div>
<?php echo form_close(); ?>
</div>

