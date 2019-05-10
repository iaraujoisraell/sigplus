<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Novo Status Report'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

               
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("Reports/add_status_report", $attrib);
                echo form_hidden('id', $id);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                    <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Período de:", "slprojeto"); ?>
                                   <?php echo form_input('dataDe', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" requered id="dateAta"'); ?>
                        </div>
                        <div class="form-group">
                                <?= lang("Período até:", "slresponsavel"); ?>
                                   <?php echo form_input('dataAte', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" requered id="dateAta"'); ?>
                         </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <a class=" btn btn-danger" href="<?= site_url('cadastros/pesquisa_satisfacao'); ?>">
                                            <span class="text"> <?= lang('Cancelar'); ?></span>
                                        </a></div>
                        </div>
                    </div>
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>




<script type="text/javascript">
    $(document).ready(function () {
        $('#gccustomer').select2({
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        $('#genNo').click(function () {
            var no = generateCardNo();
            $(this).parent().parent('.input-group').children('input').val(no);
            return false;
        });
    });
</script>
