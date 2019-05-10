<script>
     if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            
        if (!localStorage.getItem('sldate')) {
            $("#sldate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
            
            $(document).on('change', '#sldate', function (e) {
            localStorage.setItem('sldate', $(this).val());
        });
        if (sldate = localStorage.getItem('sldate')) {
            $('#sldate').val(sldate);
        }
            
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Criar Novo Registro de Ações'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("Historico_Acoes/ListaNovoRegistro", $attrib);
              
                ?>
                <div class="row">
                    
                    
                    <div class="col-lg-12">
                       
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Data da Ação", "dateAta"); ?>
                                <?php echo form_input('dateAta', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" requered id="dateAta"'); ?>
                            </div>
                        </div>
                        
                        
                    </div>    
                         

                    <style>
                        
textarea.form-control {
  height: 100%;
}
textarea { 
   min-height: 100%;
}
                    </style>

                                           
                          
                             
                    <div class="col-lg-12">
                        

                      

                        
                        
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("Avançar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>


