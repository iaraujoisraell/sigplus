<script>

    $("#dateInicial").datetimepicker({
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
            
            $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
    
});

 $(document).on('change', '#dateInicial', function (e) {
            localStorage.setItem('dateInicial', $(this).val());
        });
</script>
<script>
     if (localStorage.getItem('dateFim')) {
                localStorage.removeItem('dateFim');
            }
            
        if (!localStorage.getItem('dateFim')) {
            $("#dateFim").datetimepicker({
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
        }
            
            $(document).on('change', '#dateFim', function (e) {
            localStorage.setItem('dateFim', $(this).val());
        });
        if (sldate = localStorage.getItem('dateFim')) {
            $('#dateFim').val(sldate);
        }
            
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Criar Evento'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                 <div class="row">
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("projetos/add_evento", $attrib);
                echo form_hidden('data_inicio_projeto', $projetos->dt_inicio);
                echo form_hidden('data_fim_projeto', $projetos->dt_final);
                ?>
               
                    <div class="col-lg-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Projeto", "slProjeto"); ?>
                                    <?php
                                    $wu3[''] = '';
                                    echo form_dropdown('projeto_nome', $projetos->projeto.' - De :'.$this->sma->hrld($projetos->dt_inicio).'  Até :'.$this->sma->hrld($projetos->dt_final), (isset($_POST['projeto']) ? $_POST['projeto'] : $projetos->projeto .' - Dt Início :'.$projetos->dt_inicio), 'id="slProjeto" required="required" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                    echo form_hidden('projeto', $projetos->id);
                                   // echo $projetos->projeto;
                                    ?>
                            </div>
                        </div>                        
                    </div>
                    <div class="col-lg-12">
                    <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Nome do Evento", "slprojeto"); ?>
                                <?php echo form_input('nome_evento', (isset($_POST['nome_evento']) ? $_POST['nome_evento'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("Prazo", "dateEntrega"); ?><font style="color: red; font-size: 20px; margin-left: 1px;"> *</font>
                                        <input type="date" name="dateInicial" class="form-control">
                                            
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("Data Término da demanda", "dateEntregaDemanda"); ?><font style="color: red; font-size: 20px; margin-left: 1px;"> *</font>
                                        <input type="date" name="dateFim" id='dateFim' class="form-control">
                                        </div>
                                </div>
                        
                        </div>
                    
                    <div class="col-lg-12">
                        <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Responsável Técnico", "slGerenteArea"); ?>
                                           
                                             
                                                <?php
                                                $wu1[''] = '';
                                                foreach ($users as $user1) {
                                                    $wu1[$user1->id] = $user1->first_name. ' '.$user1->last_name;
                                                }
                                                echo form_dropdown('responsavel_tecnico', $wu1, (isset($_POST['responsavel_tecnico']) ? $_POST['responsavel_tecnico'] : $Settings->default_supplier), 'id="slGerenteArea" class="form-control  select" data-placeholder="' . lang("Selecione") . ' " required="required" style="width:100%;" ');
                                                ?>
                                                
                                                
                                             
                                            
                                            
                                        </div>
                                    </div>
                        <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("Responsável Validação", "slEDP"); ?>
                                            
                                             
                                                <?php
                                               $wu2[''] = '';
                                                foreach ($users as $user2) {
                                                    $wu2[$user2->id] = $user2->first_name. ' '.$user2->last_name;
                                                }
                                                echo form_dropdown('responsavel_edp', $wu2, (isset($_POST['responsavel_edp']) ? $_POST['responsavel_edp'] : $Settings->default_supplier), 'id="slEDP" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                                ?>
                                                
                                           
                                            
                                        </div>
                                    </div>
                    </div>
                    <div class="col-md-12">
                        
                        <div class="clearfix"></div>
                        
                        <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Responsável da Área", "slRespArea"); ?>
                                <?php echo form_input('responsavel_area', (isset($_POST['responsavel_area']) ? $_POST['responsavel_area'] : $slnumber), 'maxlength="200" class="form-control input-tip" required="required" id="slRespArea"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">  
                     <div class="col-lg-8">
                    
                        <div class="form-group">
                                <?= lang("Setor(es) do Evento", "group"); ?>
                           
                                <?php
                                foreach ($setores as $setor) {
                                    $gp[$setor->id_setor] = $setor->setor .' - '. $setor->superintendencia ;
                                }
                                echo form_dropdown('setores[]', $gp, (isset($_POST['setores']) ? $_POST['setores'] : ''), 'id="group"  multiple class="form-control select" style="width:100%;" data-placeholder="' . lang("Selecione o(s) Setor(es)") . ' " ');
                                ?>
                               
                            </div>
                        
                        
                         </div>
                        <div class="col-lg-8">
                    
                        <div class="form-group">
                                <?= lang("Módulo(s)", "slModulos"); ?>
                           
                                <?php
                                foreach ($modulos as $modulo) {
                                    $gp2[$modulo->id] = $modulo->descricao;
                                }
                                echo form_dropdown('modulos[]', $gp2, (isset($_POST['modulos']) ? $_POST['modulos'] : ''), 'id="slModulos"  multiple class="form-control select" style="width:100%;" data-placeholder="' . lang("Selecione o(s) Módulo(s)") . ' " ');
                                ?>
                                
                               
                            </div>
                        
                        
                         </div>
            
                    </div>
                    <div class="col-md-12">    
                     <div class="col-md-8">
                            <div class="form-group">
                                <?= lang("Tipo (Agrupa os eventos)", "sltipo"); ?>
                                <?php echo form_input('tipo', (isset($_POST['tipo']) ? $_POST['tipo'] : $slnumber), 'maxlength="100" class="form-control input-tip" required="required" id="sltipo"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">    
                    

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <?= lang("Observação", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                       


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('sair') ?></div>
                        </div>
                    </div>
                
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>


