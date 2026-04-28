<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$qtde = count($agenda_disponivel_medico);

if($qtde > 0){

?>
<select name="data_hora" class="selectpicker " data-width="false" id="item_select" required="true" data-none-selected-text="<?php echo 'Selecione'; ?>" data-live-search="true">
   <option class="btn btn-default"  selected="true" value="<?php echo $history['appointment_id']; ?>" >  <?php echo _d($history['date']). ' ' . $history['start_hour']; ?> <i class="fa fa-calendar"></i></option>
  <?php  foreach($agenda_disponivel_medico as $agenda){ ?>

  <option  value="<?php echo $agenda['id']; ?>">  <?php echo _d($agenda['date']). ' ' . $agenda['start_hour']; ?></option>


 <?php } ?>
</select>
<?php
}else{
    echo 'HORÁRIOS NÃO ENCONTRADO';
}
?>

<?php init_tail(); ?>