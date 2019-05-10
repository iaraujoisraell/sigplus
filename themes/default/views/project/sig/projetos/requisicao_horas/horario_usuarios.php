<?php 
    $usuario = $this->session->userdata('user_id');
    $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
 
     
?>


<script src="<?= $assets ?>sorter/RowSorter.js"></script>
<script>
$(".areAtuacaoCaractOrdem").tableDnD({

	onDrop: function() {

			serializeString = $.tableDnD.serialize();

			objeto = serializeString.split("&");

			var array_fake = "";

			for ( x = 0 ; x < objeto.length ; x++ ) {

				objeto_valor = replacex("tableNews[]=","",objeto[x]);

				if ( x  == 0 ) {

					array_fake = objeto_valor;

				} else {

					array_fake = array_fake + ',' + objeto_valor;

				}

			}

			ordenatop(array_fake);

        }	 

	});

	


})

function ordenatop(ordem){
	
	$.ajax({
	
		type: "POST",
		dataType: 'JSON',
		data: {ordenacao: ordem},
		url: "_inc/scripts/teste.php",
		success: function(data){
			var resposta = data;
		}
	});

}
</script>
    <div class="box">
        
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-calendar-o"></i><?=lang('Requisição de Horas - Usuários/ Competência');?>
        </h2>
        
        <div id="eventos_index" class="box-icon">
           
        </div>
    </div>
    
    <?php if ($Owner || $GP['bulk_actions']) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?=form_close()?>
<?php }



?>
        

        
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                        <div class="portlet portlet-default">
                         <div style="text-align: right" class="col-lg-12">
                  </div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("Projetos/Eventos_index_form", $attrib);

                            ?>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="sample_table"  class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                         <th>-</th>
                                        <th>Usuário</th>
                                        <th>Entrada</th>
                                        <th>Saída</th>
                                        <th>Saldo Inicial</th>
                                                 
                                            
                                        </thead>
                                        <tbody>
                                         <?php
                                                $cont_2 = 1;
                                                $membros = $this->projetos_model->getUsuarioHorario(); 
                                               foreach ($membros as $membro) {
                                               ?>   

                                                <tr   class="odd gradeX">
                                                    <td><?php echo $cont_2++; ?></td> 
                                                    <td><?php echo $membro->nome.' '.$membro->sobrenome; ?></td> 
                                                    <td></td> 
                                                    <td></td> 
                                                    <td></td>    

                                                </tr>
                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                    
                                    
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                           
                        </div>
                        <!-- /.portlet -->

                    </div>
        </div>
    </div>
</div>

