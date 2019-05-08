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
                class="fa-fw fa fa-calendar-o"></i><?=lang('Requisição de Horas');?>
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
                                                    <th>COMPETÊNCIA</th>
                                                    <th>DE</th>
                                                    <th>ATÉ</th>
                                                    <th>STATUS</th>
                                                    <th>Abrir</th>
                                            
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($periodos as $periodo) {
                                                    
                                                    $meses = array(
                                                        '1'=>'Janeiro',
                                                        '2'=>'Fevereiro',
                                                        '3'=>'Março',
                                                        '4'=>'Abril',
                                                        '5'=>'Maio',
                                                        '6'=>'Junho',
                                                        '7'=>'Julho',
                                                        '8'=>'Agosto',
                                                        '9'=>'Setembro',
                                                        '10'=>'Outubro',
                                                        '11'=>'Novembro',
                                                        '12'=>'Dezembro'
                                                    );
                                                    
                                                    $status = $periodo->status;
                                                   
                                                    if($status == 1){
                                                        $status_periodo = 'FECHADO';
                                                    }else{
                                                        $status_periodo = 'ABERTO';
                                                    }
                                                     
                                                    $id_cript = encrypt($periodo->id,'UNIMED');
                                                   ?>   

                                                    <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?></td> 
                                                        <td><?php echo $meses[$periodo->mes].'/'.$periodo->ano; ?></td> 
                                                        <td><?php echo $periodo->de; ?></td> 
                                                        <td><?php echo $periodo->ate; ?></td>    
                                                        <td><?php echo $status_periodo; ?> </td>    
                                                        <td class="center">
                                                            <a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-folder-open-o" href="<?= site_url('projetos/competencia_usuario/' . $periodo->mes.'/'.$periodo->ano); ?>"> </a>
                                                        </td>
                                                       
                                                    </tr>
                                            <?php
                                        }
                                        ?>

                                            
                                            
                                           
                                            
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

