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

</script>
    <div class="box">
        
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-calendar-o"></i><?=lang('Eventos');?>
        </h2>
        
        <div id="eventos_index" class="box-icon">
           <div class="fprom-group">
            <a class="btn btn-primary" href="<?=site_url('projetos/add_evento')?>"> 
             <i class="fa fa-plus-circle"></i>   <?=lang('Novo Evento')?>
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
        
 <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
  <script>
  $(function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
    
      
    var ul_sortable = $('.sortable');
    ul_sortable.sortable({
        revert: 100,
        placeholder: 'placeholder'
    });
    ul_sortable.disableSelection();
    var btn_save = $('li.save'),
        div_response = $('#response');
    btn_save.on('mouseup', function(e) {
        e.preventDefault();
        setTimeout(function(){ 
        var sortable_data = ul_sortable.sortable('serialize');
      //  div_response.text('aqui teste');
        $.ajax({
            data: sortable_data,
            type: 'POST',
            url: '../../../../escopo_slider/save_fase.php',
            success:function(result) {
               // location.reload();
                div_response.text(result);
            }
        });
         }, 500);
    });
    
  });






  </script>
        
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
                                                <th>ORDEM</th>
                                                <th>FASE</th>
                                                <th>EVENTO</th>
                                                <th>DT INÍCIO</th>
                                                <th>DT FIM</th>
                                              
                                                <th>Itens</th>
                                                <th>Replicar Itens</th>
                                                <th>Editar</th>
                                                <th>Excluir</th>
                                                </a>
                                            </tr>
                                            
                                        </thead>
                                        
                                       
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($eventos as $evento) {
                                                       
                                                    
                                                 $res_tec = $this->site->geUserByID($evento->responsavel);
                                                 $res_edp = $this->site->geUserByID($evento->responsavel_edp);   
                                                 
                                                 $quantidade_acoes = $this->projetos_model->getAcoesEventoByID($evento->id);
                                                 $quantidade_eventos = $this->projetos_model->getEventoByProjeto($projetos_usuario->projeto_atual);
                                                 $qtde_ev = $quantidade_eventos->qtde;
                                                 //echo $qtde_ev;
                                                ?>   
                                           
                                        <tr>
                                                        <td><?php echo $cont++; ?></td>  
                                                        <td style="width: 20px;">
                                                           <?php echo form_input('ordem'.$evento->id, (isset($_POST['ordem'.$evento->id]) ? $_POST['ordem'.$evento->id] : $evento->ordem), 'maxlength="200" class="form-control input-tip" required="required"  id="slelaboracao"'); ?> 
                                                        </td>  
                                                        <td > 
                                                            <?php echo form_input('tipo'.$evento->id, (isset($_POST['tipo'.$evento->id]) ? $_POST['ordem'.$evento->id] : $evento->tipo), 'maxlength="200" class="form-control input-tip" required="required" id="tipo"'); ?>
                                                        </td>
                                                        <td >
                                                           <?php echo form_input('nome_evento'.$evento->id, (isset($_POST['nome_evento'.$evento->id]) ? $_POST['nome_evento'.$evento->id] :  $evento->nome_evento), 'maxlength="200" class="form-control input-tip" required="required" id="nome_evento"'); ?>  
                                                        </td>
                                                        <th><input type="date" name='data_inicio<?php echo $evento->id ?>' value="<?php echo substr($evento->data_inicio,0,10); ?>"></th> 
                                                        <th><input type="date" name='data_fim<?php echo $evento->id ?>' value="<?php echo substr($evento->data_fim,0,10); ?>"></th> 
                                                       
                                                            <?php
                                                            $eventos_itens = $this->projetos_model->getAllItemEventosProjeto($evento->id);
                                                            $cont_item = 0;
                                                            foreach ($eventos_itens as $evento_item) {
                                                                $cont_item++;
                                                            }
                                                            ?>
                                                    
                                                       
                                                       
                                                        <td class="center">
                                                        <?php echo $cont_item; ?>    <a style="color: blue;" class="btn fa fa-list"  href="<?= site_url('projetos/Item_evento_index/'.$evento->id); ?>"></a>
                                                        </td>
                                                        <td class="center">
                                                            <a style="color: #D37423;" class="btn fa fa-refresh" data-toggle="modal" data-target="#myModal"  href="<?= site_url('projetos/replica_item_evento/'.$evento->id); ?>"></a>
                                                        </td>
                                                        <td class="center">
                                                            <a style="color: #D37423;" class="btn fa fa-edit"  href="<?= site_url('projetos/edit_evento/'.$evento->id); ?>"></a>
                                                        </td>
                                                         <td class="center">
                                                           <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('projetos/delete_eventos/'.$evento->id); ?>"></a>
                                                        </td>
                                                    
                                            </tr>
                                                <?php
                                                }
                                                ?>
                                       
                                        
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

