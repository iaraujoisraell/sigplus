

<?php
$i = 0;
foreach ($fluxos as $fluxo) {
    ?>

    <div class="col-md-6">
    <div class="form-group">
        <label class="" ><?php echo $fluxo->titulo; ?>: </label>
        <select class="form-control select2<?php echo $fluxo->id ?>" style="height: 50px;" name="processo[]" required>
            <option value="" selected disabled>Selecione</option>
            <?php foreach ($staffs as $staff): ?>
            <option value="<?php echo ($fluxo->id . ':' . $staff->staffid . ':' . $fluxo->titulo . ':' . ($i + 1)); ?>"><?php echo $staff->firstname . ' ' . $staff->lastname.' ('.$staff->name.')'; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    </div>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2<?php echo $fluxo->id; ?>').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

        })
    </script>
    <?php
    $i++;
}
?>
    <input name="formato_codigo" value="<?php echo $formato_codigo;?>" type="hidden">
    <input name="nome_categoria" value="<?php echo $nome_categoria;?>" type="hidden">
