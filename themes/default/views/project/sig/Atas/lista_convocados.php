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



                   $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});
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
<style>
    #blanket,#aguarde {
    position: fixed;
    display: none;
}

#blanket {
    left: 0;
    top: 0;
    background-color: #f0f0f0;
    filter: alpha(opacity =         65);
    height: 100%;
    width: 100%;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
    opacity: 0.65;
    z-index: 9998;
}

#aguarde {
    width: auto;
    height: 30px;
    top: 40%;
    left: 45%;
    background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%; 
    line-height: 30px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    z-index: 9999;
    padding-left: 27px;
}
</style> 
  
                                        
<div style="width: 70%" class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Lista de Convocados '); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("atas/edit_participantes/$ata" , $attrib); ?>
          <input type="hidden" value="<?php echo $projeto; ?>" name="projeto"/>
        <div class="modal-body">
            <div id="blanket"></div>
            <div id="aguarde">Aguarde...Enviando Email</div>

            <div id="payments">
               
                <div class="well well-sm well_1">
                
                        <div class="portlet portlet-green">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Histórico de convocação para esta ATA</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Participante</th>
                                                <th>Presença Confirmada?</th>
                                                <th>Dt Convocação</th>
                                                <th>Dt Confirmação</th>
                                                <th>Tipo</th>
                                                <th>Reenviar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cont = 1;
                                            foreach ($participantes as $participante) {
                       
                                             //$participantes_cadastrados_ata = $this->atas_model->getAtaUserParticipante_ByID_ATA($ata, $participante->id_user );
                                                $status = $participante->status;
                                                if($status == 0){
                                                    $novo_status = "Sem Retorno";
                                                }else if($status == 1){
                                                    $novo_status = "SIM";
                                                }else if($status == 2){
                                                    $novo_status = "NÃO";
                                                }
                                                ?>
                                            <tr>
                                                <td><?php echo $cont++; ?></td>
                                                <td><?php echo '  ' . $participante->first_name . '  ' . $participante->last_name; ?></td>
                                                <td class="text-center"><?php echo $novo_status; ?></td>
                                                <td><?php echo $this->sma->hrld($participante->data_convocacao)?></td>
                                                <td><?php if($participante->data_confirmacao){ echo  $this->sma->hrld($participante->data_confirmacao); }?></td>
                                                <td class="text-center"><?php echo $participante->tipo; ?></td>
                                                <td class="text-center" ><a style="margin-left:10px;"  href="<?= site_url('atas/reenviar_convocacao/'.$participante->usuario.'/'.$participante->ata) ?>" onclick="javascript:document.getElementById('blanket').style.display = 'block';document.getElementById('aguarde').style.display = 'block';"> 
                                                    <i class="fa fa-repeat"></i>  
                                                    </a>
                                                </td>
                                            </tr>
                                        <div class="clearfix"></div>
                        
                                        <?php
                                            }
                                        ?>
                                         </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.portlet -->
                   
                                    
              
                </div>
                <div class="col-md-12">
                    <h2> Texto da convocação: </h2>
                            <br>
                            <?php $ata_selecionada =  $this->atas_model->getAtaByID($ata); ?>
                            <?php
                            echo $ata_selecionada->texto_convocacao;
                        ?>
                             </div>
                <br><br>
                 <br><br>
                  <br><br>
                   <br><br>
                    <br><br>
                     <br><br>
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

