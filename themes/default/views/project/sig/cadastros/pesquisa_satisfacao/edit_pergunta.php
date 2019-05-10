<?php $data_hoje = date('Y-m-d H:i:s'); ?>
<script>
  localStorage.setItem('date', '<?= $this->sma->hrld($data_hoje) ?>');
  
    $("#date").datetimepicker({
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
</script>
    
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Editar Pergunta'); ?></h4>
        </div>
        
        <div class="modal-body">

            <center>  <p class="introtext"> <font style="font-size: 20px;"> <?php echo $pesquisa->titulo; ?> </font></p></center>
            <?php
            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
            echo form_open_multipart("Cadastros/edit_pergunta", $attrib);
            echo form_hidden('id_pergunta', $pergunta->id);
            echo form_hidden('id_pesquisa', $pesquisa->id);
            ?>



            <br><br>

            <div class="row ">

                <div class="col-md-12">


                    <div class="form-group">
                        <?= lang("Grupo de Perguntas", "slGrupoPergunta"); ?>
                        <?php
                        //$wu3[''] = '';
                        foreach ($grupo_perguntas as $grupo) {
                            $wu4[$grupo->id] = $grupo->nome;
                        }
                        echo form_dropdown('grupoPergunta', $wu4, (isset($_POST['grupoPergunta']) ? $_POST['grupoPergunta'] : $pergunta->grupo_pergunta), 'id="slGrupoPergunta"  class="form-control selectpicker  select" data-placeholder="' . lang("Selecione o Grupo que pertence a Pergunta") . ' "  style="width:100%;" ');
                        ?>


                    </div>

                </div>
            </div>       
            <div class="row ">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= lang("Pergunta", "slpergunta"); ?>
                        <?php echo form_textarea('pergunta', (isset($_POST['pergunta']) ? $_POST['pergunta'] : $pergunta->pergunta), 'class="form-control" id="slpauta" required="required" style="margin-top: 10px; height: 150px;"'); ?>
                    </div>
                </div>

                <center>
                    <div class="col-lg-12">
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="fprom-group"><?php echo form_submit('add_projeto', lang("Salvar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                </div>
                        </div>
                    </div>
                </center>
            </div>


            <?php echo form_close(); ?>


        </div>
        
    </div>
   
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

