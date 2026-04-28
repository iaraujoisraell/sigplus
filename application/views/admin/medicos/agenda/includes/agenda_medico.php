<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="active" id="medicos-parametrizacao-agenda">
  <?php if($mysqlVersion && strpos($mysqlVersion->version,'5.6') !== FALSE && $sqlMode && strpos($sqlMode->mode,'ONLY_FULL_GROUP_BY') !== FALSE){ ?>
    <div class="alert alert-danger">
      Sales Report may not work properly because ONLY_FULL_GROUP_BY is enabled, consult with your hosting provider to disable ONLY_FULL_GROUP_BY in sql_mode configuration. In case the items report is working properly you can just ignore this message.
    </div>
  <?php } ?>
  

<div class="col-md-12">

 <a href="#" class="btn btn-success mtop15 mbot10" onclick="slideToggle('.usernote'); return false;"><?php echo 'Nova Agenda'; ?></a>
 <div class="clearfix"></div>
<div class="row">
     <hr class="hr-panel-heading" />
</div>
 <div class="clearfix"></div>
 <div class="usernote hide">
    <?php echo form_open(admin_url( 'misc/add_agenda/'.$medico->medicoid)); ?>
     <div class="col-md-3">
         <label>Data de</label>
         <input style="font-size: 16px" type="date" name="date_de" class="form-control" required="true"> 
     </div>
     <div class="col-md-3">
         <label>Hora Início</label>
         <input style="font-size: 16px" type="time" name="hora_de" class="form-control" required="true"> 
     </div>
     <div class="col-md-3">
         <label>Data Até</label>
         <input style="font-size: 16px" type="date" name="data_ate" class="form-control" required="true"> 
     </div>
     <div class="col-md-3">
         <label>Hora Término</label>
         <input style="font-size: 16px" type="time" name="hora_ate" class="form-control" required="true"> 
     </div>
     
     
     <div class="col-md-3">
        <label>Tempo da Consulta </label>
        <input style="font-size: 16px" type="time" name="tempo_consulta" class="form-control" required="true"> 
     </div>
     
     <div class="col-md-3">
        <label>Dias</label>
        <select style="font-size: 16px" multiple="true" name="dias_semana[]" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" required="true">
            <option selected value="1">Segunda-Feira</option>
            <option selected value="2">Terça-Feira</option>
            <option selected value="3">Quarta-Feira</option>
            <option selected value="4">Quinta-Feira</option>
            <option selected value="5">Sexta-Feira</option>
            <option selected value="6">Sábado</option>
            <option selected value="0">Domingo</option>
        </select>
     </div>
     
     <div class="col-md-3">
      <?php
        $selected = '';
        foreach($centro_custo as $custo){
         if(isset($invoice)){
           if($invoice->centrocustoid == $custo['id']) {
             $selected = $custo['id'];
           }
         }
        }
        echo render_select_required_true('consultorio',$centro_custo,array('id',array('nome')),'consultorio',$selected);
        ?>
        
     </div>
     
     <br>
     <div class="col-md-12">
         <br>
     </div>
     
     
   <div class="col-md-12">
        <button class="btn btn-info pull-left mbot15">
        <?php echo _l( 'submit'); ?>
    </button>
     </div>
    
    <?php echo form_close(); ?>
         <div class="row">
     <hr class="hr-panel-heading" />
</div>
 
</div>
<div class="clearfix"></div>
</div>
<br><br><br>

    
  <?php if(count($invoices_sale_agents) > 0 ) { ?>
    <div class="row">
   <div class="col-md-4">
          <div class="form-group">
           <label for="medicos"><?php echo _l('medico'); ?></label>
           <select class="selectpicker"
                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                   name="medicos"
                   data-actions-box="true"
                   multiple="true"
                   data-width="100%"
                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       <?php
                       foreach ($medicos as $medico) {
                          $selected = ' selected';
                           ?>
                         <option  value="<?php echo $medico['medicoid']; ?>" <?php echo $selected; ?>><?php echo get_medico_full_name($medico['medicoid']); ?></option>

                <?php } ?>
           </select>
        </div>
      </div>  
  
  <div class="col-md-4">
      <div class="form-group">
       <label for="convenios"><?php echo _l('convenio'); ?></label>
       <select name="convenios" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
        <?php foreach($convenios as $convenio){ ?>
          <option value="<?php echo $convenio['id']; ?>"><?php echo $convenio['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>     
  <div class="col-md-4">
      <div class="form-group">
       <label for="categorias"><?php echo _l('expense_dt_table_heading_category'); ?></label>
       <select name="categorias" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
        <?php foreach($categorias as $categoria){ ?>
          <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['name']; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>  
        
</div>
<?php }  ?>

<input type="hidden" name="medicoid" value="<?php echo $medico->medicoid; ?>">


<table class="table table-agenda-medico-report scroll-responsive">
  <thead>
    <tr>
      <th><?php echo 'Data'; ?></th>
      <th><?php echo 'Dia da Semana '; ?></th>        
      <th><?php echo 'Horário'; ?></th>
      <th><?php echo 'Consultório'; ?></th>
      <th><?php echo 'Tempo da consulta'; ?></th>
    </tr>
  </thead>
  <tbody>

  </tbody>
  <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tfoot>
</table>
</div>
