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
                class="fa-fw fa fa-calendar-o"></i><?=lang('Marcos do Projeto');?>
        </h2>
        
        <div id="eventos_index" class="box-icon">
           <div class="fprom-group">
            <a class="btn btn-primary" data-toggle="modal" data-target="#myModal" href="<?=site_url('projetos/add_marco')?>"> 
             <i class="fa fa-plus-circle"></i>   <?=lang('Novo Marco')?>
            </a>
          </div>
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
                                         <tr>
                                       
                                                <th>ID</th>
                                                <th>TÍTULO</th>
                                                <th>DT INÍCIO</th>
                                                <th>DT FIM</th>
                                                <th>COR</th>
                                               <th>CRIADO POR</th>
                                                
                                                
                                                <th>Editar</th>
                                                <th>Excluir</th>
                                                </a>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($eventos as $evento) {
                                                       
                                                    
                                                 $res_tec = $this->site->geUserByID($evento->user_id);
                                                
                                                 
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?></td>  
                                                        
                                                      
                                                        <td><?php echo $evento->title; ?>
                                                            <p><font style="font-size: 8px;"><?php echo $evento->description; ?></font></p>    
                                                        </td>
                                                        <th><?php echo $this->sma->hrld($evento->start); ?></th> 
                                                        <th><?php echo $this->sma->hrld($evento->end); ?></th>  
                                                        <td style="background-color: <?php if($evento->color == "default"){ echo '#333333';}else{ echo $evento->color;} ?>"> </td>     
                                                        <td><?php echo $res_tec->first_name.' '.$res_tec->last_name; ?></td>
                                                        
                                                      
                                                     
                                                    
                                                        <td class="center">
                                                            <a style="color: #D37423;" class="btn fa fa-edit" data-toggle="modal" data-target="#myModal" href="<?= site_url('projetos/edit_marco/'.$evento->id); ?>"></a>
                                                        </td>
                                                         <td class="center">
                                                           <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('projetos/delete_marco/'.$evento->id); ?>"></a>
                                                
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

