
<div class="form-group select-placeholder col-md-3" >
    <label for="assigned" class="control-label"> 
        <?php echo $atuante->titulo; ?>: 
    </label>
    <select name="assigned" data-live-search="true" id="assigned" onchange="atualizar_etapa_2(this.value, '<?php echo $atuante->atuante_id; ?>')" class="form-control selectpicker" data-none-selected-text="atribuido_a">

        <option disabled value="">Selecione</option>
        <?php
        foreach ($staff as $member) {
            $selected = '';
            if ($atuante_selecionado == $member['staffid']) {
                $selected = 'selected';
            }
            ?>
            <option value="<?php echo $member['staffid']; ?>" <?php echo $select; ?> <?php echo $selected; ?>>
                <?php echo $member['firstname'] . ' ' . $member['lastname']; ?>
            </option>
        <?php } ?>
    </select>

</div>
<?php
$this->load->model('Departments_model');
$deps = $this->Departments_model->get_staff_departments($atuante->staff_id);

echo render_select('dep', $deps, array('departmentid', 'name'), 'Departamento', $atuante->department_id, ['onchange' => "edit_department('" . $atuante->id . "', this.value)"], [], 'col-md-3');
?>

<div class="form-group col-md-2" app-field-wrapper="prazo">
    <label for="prazo" class="control-label">Data Prazo:</label>
    <input type="date" id="prazo" name="prazo" class="form-control"  value="<?php echo $atuante->prazo; ?>" onchange="edit_prazo('<?php echo $atuante->id; ?>', this.value);">
</div>
<div class="form-group col-md-2" style="text-align: center;">
    <label  class="control-label">Atuante Limitado:</label>
    <div class="icheck-primary d-inline">
        <input type="radio" id="atuante__<?php echo $atuante->id; ?>" name="atuante__<?php echo $atuante->id; ?>" <?php
        if ($atuante->limitado == 1) {
            echo 'checked';
        }
        ?> onclick="limitar('<?php echo $atuante->id; ?>', '1');">
        <label for="atuante__<?php echo $atuante->id; ?>" >
            Sim
        </label>
        <input style="margin-left: 5px;" type="radio" id="atuante__<?php echo $atuante->id; ?>" name="atuante__<?php echo $atuante->id; ?>" <?php
        if ($atuante->limitado == 0) {
            echo 'checked';
        }
        ?> onclick="limitar('<?php echo $atuante->id; ?>', '0');">
        <label for="atuante__<?php echo $atuante->id; ?>">
            Não
        </label>
    </div>
</div>

<div class=" col-md-2" style="align-items: center;">
    <label for="objetivo" class="control-label">Objetivo do atuante:</label>
    <a href="#" class="btn btn-info w-100 h-100" onclick="return_objetivo('<?php echo $atuante->atuante_id; ?>');"><i class="fa fa-pencil"></i> OBJETIVO</a>
</div>
<script>

    $(document).ready(function () {
        init_selectpicker();
    });

</script>
