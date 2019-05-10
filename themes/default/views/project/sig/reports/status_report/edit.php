<div class="box">
    
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <center>  <p class="introtext"> <font style="font-size: 20px;"> STATUS REPORT  - <font style="font-size: 20px;">IMPLANTAÇÃO TASY PRESTADORA </font>  </font></p>
                        
                </center>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("Reports/edit_status_report/".$id, $attrib);
                echo form_hidden('id', $id);
                ?>
                
                <div class="row ">
                    <div class="col-md-12">
                        <div class="col-md-4">
                        <font style="font-size: 16px;">Período de: <?php echo $this->sma->hrld($status_report->periodo_de) ?> </font>
                        </div>
                        <div class="col-md-4">
                        <font style="font-size: 16px;">Período Até: <?php echo $this->sma->hrld($status_report->periodo_ate) ?> </font>
                        </div>
                        <div class="col-md-4">
                        <font style="font-size: 16px;">Autor: <?php $usuario = $this->site->getUser($status_report->autor); echo $usuario->first_name.' '.$usuario->last_name;?>  </font>
                        </div>
                </div>
                </div>
                 <br><br> <br><br>
                <div class="row ">
                    <div class="col-md-12">
                       <font style="font-size: 16px;">Informe o  Status dos Indicadores :</font>
                       
                </div>
                </div>
                 <br>
                 
                 <div class="row ">
                     
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Prazo", "prazo"); ?>
                                <?php $pst[''] = '';
                                  $pst['CONFORME PLANEJADO'] = lang('CONFORME PLANEJADO');
                                  $pst['RISCOS GERENCIÁVEIS'] = lang('RISCOS GERENCIÁVEIS');
                                  $pst['SITUAÇÃO CRÍTICA'] = lang('SITUAÇÃO CRÍTICA');
                                  
                                echo form_dropdown('prazo', $pst, (isset($_POST['prazo']) ? $_POST['prazo'] : $status_report->prazo), 'id="prazo" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o Status") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                     <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Custo", "custo"); ?>
                                <?php $pst[''] = '';
                                  $pst['CONFORME PLANEJADO'] = lang('CONFORME PLANEJADO');
                                  $pst['RISCOS GERENCIÁVEIS'] = lang('RISCOS GERENCIÁVEIS');
                                  $pst['SITUAÇÃO CRÍTICA'] = lang('SITUAÇÃO CRÍTICA');
                                  
                                echo form_dropdown('custo', $pst, (isset($_POST['custo']) ? $_POST['custo'] : $status_report->custo), 'id="custo" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o Status") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                     <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Escopo", "escopo"); ?>
                                <?php $pst[''] = '';
                                  $pst['CONFORME PLANEJADO'] = lang('CONFORME PLANEJADO');
                                  $pst['RISCOS GERENCIÁVEIS'] = lang('RISCOS GERENCIÁVEIS');
                                  $pst['SITUAÇÃO CRÍTICA'] = lang('SITUAÇÃO CRÍTICA');
                                  
                                echo form_dropdown('escopo', $pst, (isset($_POST['prazo']) ? $_POST['prazo'] : $status_report->escopo), 'id="escopo" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o Status") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                     <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("Comunicação", "comunicacao"); ?>
                                <?php $pst[''] = '';
                                  $pst['CONFORME PLANEJADO'] = lang('CONFORME PLANEJADO');
                                  $pst['RISCOS GERENCIÁVEIS'] = lang('RISCOS GERENCIÁVEIS');
                                  $pst['SITUAÇÃO CRÍTICA'] = lang('SITUAÇÃO CRÍTICA');
                                  
                                echo form_dropdown('comunicacao', $pst, (isset($_POST['comunicacao']) ? $_POST['comunicacao'] : $status_report->comunicacao), 'id="comunicacao" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o Status") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                   
                 </div>
                 
                 <div class="row ">
                     <div class="col-md-12">
                        <div class="form-group">
                            <?= lang("Comentário sobre os Status dos Indicadores", "slcomentarioIndicadores"); ?>
                                     <?php echo form_textarea('comentarios_indicadores', (isset($_POST['comentarios_indicadores']) ? $_POST['comentarios_indicadores'] : $status_report->comentarios_indicadores), 'class="form-control" id="slcomentarioIndicadores" required="required" style="margin-top: 10px; height: 150px;"'); ?>
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
                                     <h3>EVENTOS ENVOLVIDOS</h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>EVENTO</th>
                                                <th>INÍCIO</th>
                                                <th>FIM</th>
                                                <th>STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                                $wu4[''] = '';
                                                $cont = 1;
                                                $data_fim_status = $status_report->periodo_ate;
                                                $Eventos_Status = $this->reports_model->getAllEventoStatusReportByStatusReport($status_report->id);
                                                
                                                foreach ($Eventos_Status as $evento) {
                                                       
                                                    //$acoes = $this->Atas_Model->getAllAcoes($plano->idplanos);
                                                ?>   
                                                <tr class="odd gradeX">
                                                    <td><?php echo $cont++; ?></td>
                                                    <td><?php echo $evento->nome_evento; ?></td>
                                                    <td><?php echo $this->sma->hrld($evento->data_inicio); ?></td>
                                                    <td><?php echo $this->sma->hrld($evento->data_fim); ?></td>
                                                    <td><font><?php echo $evento->status; ?></font></td>
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
                 <div class="row ">
                    <div  class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                   
                                     <h3>AÇÕES CONCLUÍDAS </h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>AÇÃO</th>
                                                <th>INÍCIO</th>
                                                <th>FIM</th>
                                                <th>RESPONSAVEL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             $cont = 1;
                                                foreach ($Eventos_Status as $evento) {
                                                
                                               
                                               
                                                
                                                $data_fim_status = $status_report->periodo_ate;
                                                $acoes_concluidas = $this->reports_model->getAllAcoesByEventoStatusReport($evento->id_sr);
                                        
                                                
                                                foreach ($acoes_concluidas as $acao_concluida) {
                                                       
                                                   
                                                ?>   
                                                <tr class="odd gradeX">
                                                    <td><?php echo $cont++; ?></td>
                                                    <td><?php echo $acao_concluida->descricao; ?></td>
                                                    <td><?php echo $this->sma->hrld($acao_concluida->data_termino); ?></td>
                                                    <td><?php echo $this->sma->hrld($acao_concluida->data_retorno_usuario); ?></td>
                                                    <td><?php echo $acao_concluida->first_name.' '.$acao_concluida->last_name; ?></td>
                                               </tr>
                                                <?php
                                                    }
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
                 
                 <div class="row ">
                    <div  class="col-lg-12">

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                   
                                     <h3>AÇOES EM ANDAMENTO </h3>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>AÇÃO</th>
                                                <th>INÍCIO</th>
                                                <th>FIM</th>
                                                <th>RESPONSAVEL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php
                                             $cont = 1;
                                                foreach ($Eventos_Status as $evento) {
                                                
                                               
                                               
                                                
                                                $data_fim_status = $status_report->periodo_ate;
                                                $acoes_concluidas= $this->reports_model->getAllAcoesPendentesByEventoStatusReport($evento->id_sr);
                                        
                                                
                                                foreach ($acoes_concluidas as $acao_concluida) {
                                                       
                                                   
                                                ?>   
                                                <tr class="odd gradeX">
                                                    <td><?php echo $cont++; ?></td>
                                                    <td><?php echo $acao_concluida->descricao; ?></td>
                                                    <td><?php echo $this->sma->hrld($acao_concluida->data_termino); ?></td>
                                                    <td><?php echo $this->sma->hrld($acao_concluida->data_retorno_usuario); ?></td>
                                                    <td><?php echo $acao_concluida->first_name.' '.$acao_concluida->last_name; ?></td>
                                               </tr>
                                                <?php
                                                    }
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
                 <div class="row ">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang("Observações Sobre as Ações ", "slpergunta"); ?>
                                     <?php echo form_textarea('observacoes_acoes', (isset($_POST['observacoes_acoes']) ? $_POST['observacoes_acoes'] : $status_report->observacoes_acoes), 'class="form-control" id="slpergunta"  style="margin-top: 10px; height: 150px;"'); ?>
                          </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= lang("Observações Adicionais", "slobs_adicionais"); ?>
                                     <?php echo form_textarea('observacoes_adicionais', (isset($_POST['observacoes_adicionais']) ? $_POST['observacoes_adicionais'] : $status_report->observacoes_adicionais), 'class="form-control" id="slobs_adicionais" style="margin-top: 10px; height: 150px;"'); ?>
                          </div>
                    </div>
                    
                    <center>
                        <div class="col-lg-12">
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <div class="fprom-group"><?php echo form_submit('add_projeto', lang("Salvar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                    <button type="button" class="btn btn-danger" id="reset"><?= lang('Fechar') ?></div>
                            </div>
                        </div>
                    </center>
                </div>
               
                 <div
                     class="fprom-group"><?php echo form_submit('add_status', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                     <a  class="btn btn-danger" href="<?= site_url('Reports/status_report'); ?>"><?= lang('Cancelar') ?></a>
                 </div>  
                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>
           
