<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Editar Pesquisa de Satisfação'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <center>  <p class="introtext"> <font style="font-size: 20px;"> <?php echo $pesquisa->titulo; ?> </font></p></center>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("Cadastros/pesquisa_satisfacao_add_pergunta/".$id, $attrib);
                echo form_hidden('id', $id);
                ?>
                
                
                <div class="col-md-12">
                    <center>
                        <font style="font-size: 16px;">Cadastrar Nova Pergunta </font>
                    </center>
                </div>
                <br><br>
                
                <div class="row ">
                  
                    
                    
                        <div class="col-md-8">
                            <?= lang("Grupo de Perguntas", "slpergunta"); ?>
                        </div>
                        <div class="col-md-12">
                           
                               
                                <div class="input-group">
                                             
                                                <?php
                                                //$wu3[''] = '';
                                                foreach ($grupo_perguntas as $grupo) {
                                                    $wu4[$grupo->id] = $grupo->nome;
                                                }
                                                echo form_dropdown('grupo', $wu4, (isset($_POST['grupo']) ? $_POST['grupo'] : $Settings->default_supplier), 'id="slgrupo"  class="form-control selectpicker  select" data-placeholder="' . lang("Selecione o Grupo que pertence a Pergunta") . ' "  style="width:100%;" ');
                                                ?>
                                                
                                                 <div class="input-group-addon no-print" style="padding: 0px 8px;">
                                                     <i class="fa fa-chevron-down"></i>
                                                </div>
                                            </div>
                        
                            </div>
                            
                   
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang("Pergunta", "slpergunta"); ?>
                                     <?php echo form_textarea('pergunta', (isset($_POST['pergunta']) ? $_POST['pergunta'] : $pergunta->pergunta), 'class="form-control" id="slpergunta" required="required" style="margin-top: 10px; height: 150px;"'); ?>
                          </div>
                    </div>
                    
                    <center>
                        <div class="col-lg-12">
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <div class="fprom-group"><?php echo form_submit('add_projeto', lang("Adcionar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                    <button type="button" class="btn btn-danger" id="reset"><?= lang('Fechar') ?></div>
                            </div>
                        </div>
                    </center>
                </div>
               

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>
              
 <div class="row ">
       <div  class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <?php
                                                $wu4[''] = '';
                                                $cont2 = 0;
                                                foreach ($planos as $plano2) {
                                                       
                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                $cont2++;
                                                }
                                                ?>   
                                     <h3>PERGUNTAS DA PESQUISA</h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>PERGUNTA</th>
                                                <th>GRUPO</th>
                                                 <th>Respostas</th>
                                                <th>Editar</th>
                                                <th>Excluir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($perguntas as $pergunta) {
                                                       
                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td><?php echo $cont++; ?></td>
                                                <td><?php echo $pergunta->pergunta; ?></td>
                                                <td><?php echo $pergunta->grupo; ?></td>
                                                
                                                
                                                <td class="center">
                                                     <a style="color: gray;" class="btn fa fa-list" data-toggle="modal" data-target="#myModal" href="#"></a>
                                                </td>
                                               <td class="center">
                                                     <a style="color: #128f76;" class="btn fa fa-edit" data-toggle="modal" data-target="#myModal" href="<?= site_url('Cadastros/edit_pergunta/'.$pergunta->id.'/'.$id); ?>"></a>
                                                </td>
                                                <td class="center">
                                                    <a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('Cadastros/delete_pergunta/'.$pergunta->id.'/'.$id); ?>"></a>
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
 <br><br><br>


