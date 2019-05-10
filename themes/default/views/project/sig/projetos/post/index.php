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
                class="fa-fw fa fa-calendar-o"></i><?=lang('Postagens');?>
        </h2>
        
        <div id="eventos_index" class="box-icon">
           <div class="fprom-group">
               <a  class="btn btn-primary" data-toggle="modal" data-target="#myModal"  href="<?= site_url('projetos/nova_postagem'); ?>">
            
             <i class="fa fa-plus-circle"></i>   <?=lang('Nova Postagem')?>
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
                                                <th>Titulo</th>
                                                <th>Descrição</th>
                                                <th>Tipo</th>
                                                <th>Arquivo</th>
                                                <th>Dt post</th>
                                              
                                                <th>Excluir</th>
                                               
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                $postagens = $this->projetos_model->getAllPostagemByProjeto($projetos_usuario->projeto_atual);
                                                foreach ($postagens as $post) {
                                                       
                                                 
                                                 //echo $qtde_ev;
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?></td>  
                                                        <td style="width: 20px;">
                                                           <?php echo $post->titulo; ?> 
                                                        </td>  
                                                        <td > 
                                                            <?php echo $post->descricao; ?> 
                                                        </td>
                                                        <td >
                                                           <?php echo $post->tipo; ?>   
                                                        </td>
                                                        
                                                        <th>
                                                            <?php if(($post->tipo == 1)||($post->tipo == 2)){ ?>
                                                                <img width="200px;" height="100px;" src="<?php echo base_url().'assets/uploads/projetos/'.$post->anexo; ?>">
                                                            <?php }else{ ?>
                                                                <a target="_blank" href="<?php echo base_url().'assets/uploads/projetos/'.$post->anexo; ?>"><i class="fa fa-download"> VER </i></a>
                                                            <?php } ?>    
                                                        </th> 
                                                        <th><?php echo $post->data_postagem; ?> </th> 
                                                       
                                                         <td class="center">
                                                           <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('projetos/delete_post/'.$post->id); ?>"></a>
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
                            <center>
                            <div class="col-md-12">
                            <?php echo form_submit('add_item', lang("Atualizar Ordem"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="javascript:document.getElementById('."blanket".').style.display = "block"; document.getElementById('."aguarde".').style.display = "block";" '); ?>
                                        
                             </div>
                       
                        </center> 
                        </div>
                        <!-- /.portlet -->

                    </div>
        </div>
    </div>
</div>

