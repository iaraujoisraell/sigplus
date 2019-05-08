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
            
            
            function verificaStatus(nome){
	if(nome.form.tudo.checked == 0)
		{
			nome.form.tudo.checked = 1;
			marcarTodos(nome);
		}
	else
		{
			nome.form.tudo.checked = 0;
			desmarcarTodos(nome);
		}
}
 
function marcarTodos(nome){
   for (i=0;i<nome.form.elements.length;i++)
	  if(nome.form.elements[i].type == "checkbox")
		 nome.form.elements[i].checked=1
}
 
function desmarcarTodos(nome){
   for (i=0;i<nome.form.elements.length;i++)
	  if(nome.form.elements[i].type == "checkbox")
		 nome.form.elements[i].checked=0
}
</script>

<style>
    input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari and Chrome */
  -o-transform: scale(2); /* Opera */
  padding: 10px;
  margin-right: 10px;

}

/* Might want to wrap a span around your checkbox text */
.checkboxtext
{
  /* Checkbox text */
  font-size: 110%;
  display: inline;
}
</style>

  
                                        
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Lista de Participantes'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("atas/edit_participantes/$ata" , $attrib); ?>
          <input type="hidden" value="<?php echo $projeto; ?>" name="projeto"/>
        <div class="modal-body">
            

            <div id="payments">
               
                <div class="well well-sm well_1">

                    <?= lang("Nomes", "slelaboracao"); ?>
                    <br>
                    
   
                    <div class="col-md-12">
                        <input type='checkbox' name='tudo' onclick='verificaStatus(this)' />  Todos
                    </div>
                    <br>
                    <br>
                    <?php
                    foreach ($participantes as $participante) {
                       
                            $participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($ata, $participante->id_user );
                            // foreach ($participantes_cadastrados_ata as $participante_cadastrados) {
                                // echo '';
                                 
                                     ?>
                                     <div class="col-md-12">
                                        <input type="checkbox" name="participantes[]" <?php if($participantes_cadastrados_ata){ ?> checked <?php } ?>  id="participantes" value="<?php echo $participante->id_user; ?>"><?php echo '  ' . $participante->fname . ' - ' . $participante->lname . ' - ' . $participante->setor . ';'; ?>
                                    </div>
                                    <div class="clearfix"></div>
                        
                        <?php
                    }
                    ?>


                    <div class="clearfix"></div> 
                    <br>
                    <div class="col-md-12">
                        <input type='checkbox' name='tudo' onclick='verificaStatus(this)' /> Todos
                    </div>   

                    <div class="clearfix"></div>
                </div>
                <center>
                    <div class="col-md-12">
                    <div
                        class="fprom-group">
                            <?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                       
                    </div>
                </div>
                </center>
                <br><br>
                
            </div>
<br>
          
           

        </div>
        
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

