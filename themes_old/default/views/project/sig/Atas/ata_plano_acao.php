
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('PLANO DE AÇÃO DA ATA '); ?><?php echo $id; ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
               
                <div class="row">
                    
                    
                    <div class="col-lg-12">
                           <div class="col-md-4">
                               
                                        <div class="form-group">
                                            <?= lang("Projeto", "slProjeto"); ?>
                                                <?php
                                                $wu3[''] = '';
                                                foreach ($projetos as $projeto) {
                                                    $wu3[$projeto->id] = $projeto->projeto;
                                                }
                                                echo form_dropdown('projeto', $wu3, (isset($_POST['projeto']) ? $_POST['projeto'] : $ata->projetos), 'id="slProjeto"  disabled="disabled" required="required" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                                ?>
                                        </div>
                                    </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Data da ATA", "sldate"); ?>
                                    <?php echo form_input('dateAta', (isset($_POST['dateAta']) ? $_POST['dateAta'] : $this->sma->hrld($ata->data_ata)), 'class="form-control input-tip datetime" disabled="disabled" id="sldate" required=$projeto"required"'); ?>
                                </div>
                            </div>
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Tipo", "tipo"); ?>
                                <?php $pst[''] = '';
                                  $pst['REUNIÃO'] = lang('REUNIÃO');
                                  $pst['TREINAMENTO'] = lang('TREINAMENTO');
                                  $pst['EMAIL'] = lang('EMAIL');
                                  $pst['PORTARIA'] = lang('PORTARIA');
                                  
                                 
                                  
                                echo form_dropdown('tipo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : $ata->tipo), 'id="tipo" class="form-control " disabled="disabled" data-placeholder="' . lang("select") . ' ' . lang("o tipo de Ata") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                    </div>    
                        
                    <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Pauta", "slpauta"); ?>
                                        <?php echo $ata->pauta; ?>
                                    </div>
                                </div>
                            </div>
                    
                    <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Participantes", "slparticipantes"); ?>
                                         <?php echo $ata->participantes; ?>
                                    </div>
                                </div>
                    </div>
                    
                    
                        
                        <div class="col-lg-12">
                            <div class="col-md-12">
                            <div class="form-group">
                                <center>
                                <h3>------------------- CADASTRAR PLANO DE AÇÃO -------------------</h3>
                                </center>
                            </div>
                        </div>
                            
                    <div class="clearfix"></div>
                             <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("atas/plano_acao", $attrib);
                echo form_hidden('id', $id);
                ?>
                            <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("Descrição", "sldescricao"); ?>
                                <?php echo form_input('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="sldescricao"'); ?>
                            </div>
                        </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Data da Entrega", "sldateEntrega"); ?>
                                    <?php echo form_input('dateEntrega', (isset($_POST['dateEntrega']) ? $_POST['dateEntrega'] : ""), 'class="form-control input-tip datetime" id="sldateEntrega" required="required"'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Responsável", "slResponsavel"); ?>
                                            
                                             
                                                <?php
                                                $wu4[''] = '';
                                                foreach ($users as $user) {
                                                    $wu4[$user->id] = $user->username;
                                                }
                                                echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $Settings->default_supplier), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;"  required="required"');
                                                ?>
                                                
                                               
                                            
                                        </div>
                                    </div>
                            <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("Status ", "status_plano"); ?>
                                <?php $pst2[''] = '';
                                  $pst2['NO PRAZO'] = lang('NO PRAZO');
                                  $pst2['SEM RESPOSTA'] = lang('SEM RESPOSTA');
                                  $pst2['CONCLUÍDO DENTRO DO PRAZO'] = lang('CONCLUÍDO DENTRO DO PRAZO');
                                  $pst2['CONCLUÍDO FORA DO PRAZO'] = lang('CONCLUÍDO FORA DO PRAZO');
                                  $pst2['ATRASADO'] = lang('ATRASADO');
                                 // $pst['partial'] = lang('partial');
                                 
                                  
                                echo form_dropdown('status_plano', $pst2, (isset($_POST['status_plano']) ? $_POST['status_plano'] : $Settings->default_slpayment_status), 'id="status_plano" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o Status") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                    <center>
                            <div class="col-md-12">
                            <div
                                class="fprom-group center"><?php echo form_submit('add_item', lang("adicionar"), 'id="add_item" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <a  class="btn btn-danger" href="<?= site_url('atas'); ?>"><?= lang('Concluir') ?></a>
                            </div>
                        </div>
                            </center>
                            <?php echo form_close(); ?>
                    </div>
                    
                     

                    <div class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     <h3>ITENS DO PLANO DE AÇÃO</h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ITEM</th>
                                                <th>DESCRIÇÃO</th>
                                                <th>RESPONSÁVEL</th>
                                                <th>DATA DO PRAZO</th>
                                                <th>STATUS</th>
                                                <th>AÇÃO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                foreach ($planos as $plano) {
                                                       
                                                    $acoes = $this->atas_model->getAllAcoes($plano->idplanos);
                                                ?>   
                                                <tr class="odd gradeX">
                                                <td><?php echo $cont++; ?></td>
                                                <td><?php echo $plano->descricao; ?>
                                                    <?php
                                                    
                                                    foreach ($acoes as $acao) {
                                                    ?>   
                                                    <p>  <?php echo $acao->oque; ?> </p>
                                                     <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $plano->first_name. ' '.$plano->last_name; ?></td>
                                                <td class="center"><?php echo $this->sma->hrld($plano->data_termino); ?></td>
                                                <td class="center"><?php echo $plano->status; ?></td>
                                                <td class="center"><a style="color: red;" class="btn fa fa-trash-o" href="<?= site_url('atas/deletePlano/'.$plano->idplanos.'/'.$plano->idatas); ?>"><?= lang('Apagar') ?></a></td>
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
                    <!-- /.col-lg-12 -->

              
                             
            </div>

        </div>
    </div>
</div>



