
<body class="hold-transition skin-green sidebar-collapse  sidebar-mini">
    <div class="wrapper">

    <div class="content-wrapper">
        
            <h1>
                Requisição de Horas - Lançamentos
                <small>Lançamento de horas </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>


            <section class="content">
               

                <br>
               
                    <?php
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
                    //competencia
                    ?>
                    <h3>Competência: <?php echo $meses[$competencia->mes].'/'.$competencia->ano; ?></h3>
                
               
                
                <br>
                <div class="row">
                <!-- DIV TABLE AÇÕES PENDENTES -->  
                <div class="col-lg-3">
                <button style="margin-bottom: 20px;" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-insert">
                Nova Requisição
                </button>
                </div>
                
                <table id="example1" class="table table-striped table-bordered table-hover table-green">
                    <thead>
                        <tr style="background-color: orange;">

                            <th>DATA</th>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>H. Início</th>
                            <th>H. Fim Estimado</th>
                            <th>H. Fim Real</th>
                            
                            <th>Saldo</th>
                            <th>Ação</th>
                            
                            <th>H.Extra</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $wu4[''] = '';
                        $cont = 1;
                        foreach ($lacamentos as $periodo) {
                            $id_cript = encrypt($periodo->id,'UNIMED');
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

                            $data_registro = $competencia->ano.'-'.$periodo->mes.'-'.$periodo->dia;
                            $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
                            $diasemana_numero = date('w', strtotime($data_registro));
                            $dia_semana = $diasemana[$diasemana_numero];
                           ?>   

                            <tr  >

                                <td><?php echo $periodo->dia.'/'.substr($meses[$periodo->mes], 0, 3); ?> <small class="label pull-right <?php if(($dia_semana == "Sabado")||($dia_semana == "Domingo")){ ?> bg-orange <?php }else{ ?> bg-gray <?php } ?> "><?php echo '('.$dia_semana.')'; ?></small></td> 
                                <td><?php echo $periodo->descricao; ?> </td>
                                <td><?php echo $periodo->tipo_registro; ?> </td>
                                <td><?php echo $periodo->hora_inicio; ?></td> 
                                <td><?php echo $periodo->hora_fim_estimado; ?></td> 
                                <td><?php echo $periodo->hora_fim_confirmado; ?> </td>
                                <td><?php echo $periodo->saldo; ?> </td>    
                                <td><?php echo $periodo->id_acao; ?> </td>
                                <td><?php echo $periodo->hora_extra; ?> </td>
                                <td class="center">
                                    
                                    <a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-edit" href="<?= site_url('welcome/editarRequisicaoHorasDetalhes_teste/'.$periodo->id); ?>" data-toggle="modal" data-target="#myModal"> <?php echo $periodo->id; ?> </a>
                                </td>

                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>

                </table>
                           
                </div>
               
                <div class="modal fade" id="modal-insert">
          <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><p class="introtext">REQUISIÇÃO DE HORAS </p>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    <i class="fa fa-2x">&times;</i>
                                </button>
                                <h4 class="modal-title">Data : <?php echo $dia.'/'.$mes; ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class="error"></div>
                               <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                echo form_open_multipart("Projetos/edit_marco" , $attrib); 
                                echo form_hidden('id', $evento->id);
                                ?>
                                    <input type="hidden" value="" name="eid" id="eid">
                                    
                                    <div class="form-group">
                                        <?= lang('Descrição da atividade', 'title'); ?>
                                            <?php echo form_input('title', (isset($_POST['title']) ? $_POST['title'] : $evento->title), 'maxlength="200" class="form-control input-tip" required="required" id="title"'); ?>
                                    </div>
                                    <div class="form-group">
                                        <?= lang("Selecione a ação vinculada a requisição", "slVinculoAcao"); ?>
                                        <?php                                       
                                        $wu_acao['N/A'] = 'Banco Débito';
                                        $usuario_sessao = $this->session->userdata('user_id'); 
                                        $acoes = $this->atas_model->getAllPlanosUser($usuario_sessao);
                                        foreach ($acoes as $acao) {
                                            $wu_acao[$acao->idplanos] = $acao->idplanos .' - '. substr($acao->descricao, 0, 100);
                                        }
                                        echo form_dropdown('acoes_vinculo', $wu_acao, (isset($_POST['acoes_vinculo']) ? $_POST['acoes_vinculo'] : ""), 'id="slVinculoAcao"  class="form-control  select" data-placeholder="' . lang("Selecione a(s) Ações(es)") . ' "   style="width:100%;" ');
                                        ?>
                                        </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <?= lang('start', 'start'); ?>
                                                <input type="time" class="form-control" name="start">
                                              
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <?= lang('Término Estimado', 'end'); ?>
                                              <input type="time" class="form-control" name="end">
                                              </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <?= lang('Tipo de Hora', 'color'); ?>
                                                <select name="tipo" class="form-control ">
                                                        <option value="Crédito">Crédito</option>
                                                        <option  value="Débito">Débito</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <?= lang('Hora Extra', 'color'); ?>
                                                <select name="hora_extra" class="form-control ">
                                                        <option value="SIM">SIM</option>
                                                        <option  value="NÃO">NÃO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    
                                        
                                        
                                   
                                    
                                   <div class="fprom-group">
                            <?php echo form_submit('add_projeto', lang("Editar"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                       
                    </div>
                                    <?php echo form_close(); ?>
                            </div>
                           
                        </div>
                    </div>
        </div>
            </section>

    
    
    </div>
    </div>

</body>
