  <?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>
<script>
     if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            
        if (!localStorage.getItem('sldate')) {
            $("#sldate").datetimepicker({
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
            
            $(document).on('change', '#sldate', function (e) {
            localStorage.setItem('sldate', $(this).val());
        });
        if (sldate = localStorage.getItem('sldate')) {
            $('#sldate').val(sldate);
        }
         
         
         
                   $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});


onload : optionCheck();
</script>
<style>
    #blanket,#aguarde {
    position: fixed;
    display: none;
}

#blanket {
    left: 0;
    top: 0;
    background-color: #f0f0f0;
    filter: alpha(opacity =         65);
    height: 100%;
    width: 100%;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
    opacity: 0.65;
    z-index: 9998;
}

#aguarde {
    width: auto;
    height: 30px;
    top: 40%;
    left: 45%;
    background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%; 
    line-height: 30px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    z-index: 9999;
    padding-left: 27px;
}
</style> 
<script type="text/javascript">
    function optionCheck(){
        var option = document.getElementById("options").value;
        if(option == "REUNIÃO CONTÍNUA"){
         //   document.getElementById("hiddenDiv").style.visibility ="visible";
            document.getElementById("hiddenDiv").style.display = "block";
        }else{
            document.getElementById("hiddenDiv").style.display = "none";
        }


    }
</script>
<?php
        $acao = $this->atas_model->getPlanoByID($idplano);
        $usuario = $this->session->userdata('user_id');
//$users = $this->site->geUserByID($acao->responsavel);                              
        ?>    
<?php
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
?>
<div id="blanket"></div>
<div id="aguarde">Aguarde...</div>

    
    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Editar Ação '.$acao->sequencial; ?>
              <small>ATA  <?PHP ECHO $acao->idatas; ?></small>
                  
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('project'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Ata</li>
          </ol>

        </section>
        <br>
    </div> 
    </div>
    
    <div class="row">  
    <div class="col-lg-12">
            <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    </div>
    </div>
    
    <section  class="content">
    
        <div  class="row">
                <div class="col-md-12">
                                   
                        <div  class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                              <li class="active"><a href="#cadastro" data-toggle="tab">Dados Cadastro</a></li>  
                              <li ><a href="#activity" data-toggle="tab">Ações Vinculadas</a></li>                         
                              <li><a href="#settings" data-toggle="tab">Arquivos</a></li>
                             
                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="cadastro">
                                <div class="box">
                                <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <h2>  Cadastro  </h2>
                                    </center>
                                </div>
                            </div>
                              <div id="blanket"></div>
                                <div id="aguarde">Aguarde...Enviando Email</div>
                            <div class="clearfix"></div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("Atas/manutencao_acao_pendente_form", $attrib); 
                            echo form_hidden('id', $acao->idplanos);
                            echo form_hidden('idatas', $acao->idatas);
                            ?>
                            <div class="col-md-12">
                            <!-- ITEM EVENTO -->
                            <div class="col-md-12">
                                  <div class="form-group">
                                        <?= lang("Item do Evento", "slEvento"); ?>
                                         <?php
                                        $wue[''] = '';
                                      foreach ($eventos as $evento) {
                                            $wue[$evento->id] = $evento->nome_fase.' > '. $evento->nome_evento.' > '. resume($evento->descricao, 100);
                                           
                                        }
                                        echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : $acao->eventos), 'id="slEvento"  class="form-control  select" data-placeholder="' . lang("Selecione o Item do Evento") . ' " required="required"  style="width:100%;"  ');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                
                                <div class="col-md-6">
                                  <!-- DESCRIÇÃO DA AÇÃO -->  
                                  
                                  <div  class="form-group">
                                        <?= lang("Descrição ", "sldescricao"); ?><small>(O que ?)</small>
                                        <?php echo form_textarea('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao), 'class="form-control  input-tip "   style="height: 120px;" id="sldescricao" required="true" '); ?>
                                  </div>
                              
                                    <!-- ONDE -->  
                                  <div  class="form-group">
                                        <?= lang("Local ", "onde"); ?><small>(Onde ?)</small>
                                        <?php echo form_textarea('onde', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->onde), 'class="form-control"   style="height: 120px;" id="onde"  '); ?>
                                  </div>
                                    <!-- PRAZO de -->
                                  <div class="form-group">
                                            <?= lang("Data Início e Término", "sldate"); ?><small>(Quando ?)</small>
                                            <i class="fa fa-info-circle" title="A Data de Início e Término, precisa estar dentro do período de datas do Item do Evento selecionado."></i>
                                                     <input type="text" value="<?php echo exibirData($acao->data_entrega_demanda) ?> - <?php echo exibirData($acao->data_termino) ?>" title="O período de data da ação, não pode estar fora do período de datas do Item de evento selecionado." name="periodo_acao" class="form-control pull-right" id="reservation">
                
                                        </div>
                                    <!-- HORAS -->
                                  <div class="form-group">
                                        <?= lang("Horas Previstas", "horas"); ?>
                                       <input class="form-control input-tip" placeholder="Horas Previstas" value="<?php echo $acao->horas_previstas; ?>"  name="horas_previstas" type="number">
                                       </div>
                                   <!-- QUEM -->
                                  <div class="form-group">
                                        <?= lang("Responsável ", "slResponsavel"); ?><small>(Quem ?)</small>
                                        <?php
                                        //$wu4[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                        }
                                        $id_usu_setor =  $this->atas_model->getUserSetorByUsuarioAndSetor($acao->responsavel, $acao->setor);
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $id_usu_setor->id), 'id="slResponsavel" required="true"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"   ');
                              
                                        ?>
                                    </div>
                                   <div class="form-group">
                                        <?= lang("Peso da Ação ", "peso"); ?><small>(Nível de Importancia/Impácto para o projeto)</small>
                                        <i class="fa fa-info-circle" title="1-Normal: Não é Importante ou Urgente. 2-Pouco: Um Pouco Importante mas não é Urgente. 3-Médio: Importância e Urgência Média. 
                                                                            4-Grande: Importante e Urgente. 5-Muito Grande: Muito Importante e Muito Urgente "></i>
                                        <?php
                                        //$wu4[''] = '';
                                          $wu_peso['1'] = lang('1-Pouco');
                                          $wu_peso['2'] = lang('2-Normal');
                                          $wu_peso['3'] = lang('3-Médio');
                                          $wu_peso['4'] = lang('4-Grande');
                                          $wu_peso['5'] = lang('5-Muito Grande');
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('peso', $wu_peso, (isset($_POST['peso']) ? $_POST['peso'] : $acao->peso), 'id="peso" required  class="form-control  select" data-placeholder="' . lang("Selecione o Peso da Ação") . ' "   style="width:100%;"   ');
                              
                                        ?>
                                    </div>
                                
                            </div>
                            
                                <div class="col-md-6">
                                    <!-- PORQUE -->  
                                    <div class="form-group">
                                        <?= lang("Motivo, Justificativa", "porque"); ?><small>(Por Quê? )</small>
                                        <?php echo form_textarea('porque', (isset($_POST['porque']) ? $_POST['porque'] : $acao->porque), 'class="form-control"   style="height: 120px;" id="porque"  '); ?>
                                    </div>
                                    <!-- COMO -->  
                                    <div class="form-group">
                                        <?= lang("Detalhes", "como"); ?><small>(Como? )</small>
                                        <?php echo form_textarea('como', (isset($_POST['como']) ? $_POST['como'] : $acao->como), 'class="form-control"   style="height: 120px;" id="como"  '); ?>
                                    </div>
                                    <!-- VALOR -->  
                                    <div class="form-group">
                                        <?= lang("Custo", "custo"); ?><small> (Descrição do Custo? )</small>
                                        <?php echo form_textarea('custo', (isset($_POST['custo']) ? $_POST['custo'] : $acao->custo), 'class="form-control"   style="height: 120px;" id="custo"  '); ?>
                                    </div>
                                    <?= lang("Valor", "custo"); ?><small> (Valor do Custo? )</small>
                                    <input class="form-control" placeholder="Valor do Custo para esta ação" onkeypress="mascara(this, mvalor);" value="<?php echo str_replace('.', ',', $acao->valor_custo); ?>"  name="valor_custo" type="text">
                                    
                                    
                                </div>
                           
                            </div>    
                            
                            <br>
                            
                            <center>

                                <div class="col-md-12">
                                      <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                        <a  class="btn btn-danger" class="close" data-dismiss="modal"  href="<?= site_url('Atas/plano_acao/'.$acao->idatas); ?>"><?= lang('Sair') ?></a>
                             
                                </div>
                                 </center>
                             <?php echo form_close(); ?>
                       
       
                <br><br><br>
                </div>
            
            
                                <br>
            
            
                                </div>        
                                </div>  
                              <div class=" tab-pane" id="activity">
                                  
                                      <?php
                                        $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                        echo form_open_multipart("project/manutencao_acao_vinculo_ata", $attrib); 
                                        echo form_hidden('id', $acao->idplanos);
                                        echo form_hidden('idatas', $acao->idplano);
                                      ?>
                                      <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang("Ação predecessora ", "slVinculoAcao"); ?> <i class="fa fa-info-circle" title="Ações que tem vinculo com a ação atual. Ações do qual se tem alguma dependencia."></i>
                                                <?php
                                                $wua[''] = '';
                                                foreach ($acoes_vinculadas as $user_ata) {
                                                     $wua[$user_ata->id_vinculo] = $user_ata->id_vinculo;
                                                }
                                                $wu_acao[''] = '';
                                                foreach ($acoes as $acaov) {
                                                    $wu_acao[$acaov->idplanos] = $acaov->sequencial.' : ' . $acaov->nome_fase.' > '.$acaov->nome_evento.' > '.$acaov->item.' > '. substr($acaov->descricao, 0, 100).' ( '.exibirData($acaov->dt_inicio).' - '.exibirData($acaov->dt_termino).' )';
                                                }
                                                echo form_dropdown('acoes_vinculo', $wu_acao, (isset($_POST['acoes_vinculo']) ? $_POST['acoes_vinculo'] : ""), 'id="slVinculoAcao"  class="form-control  select" data-placeholder="' . lang("Selecione a(s) Ações(es)") . ' "   style="width:100%;"   ');
                                                ?>
                                            </div>
                                        </div>
                                      <div class="col-md-6">
                                            <div class="form-group">
                                                <?= lang("Tipo Vinculo ", "tipo_vinculo"); ?> <i class="fa fa-info-circle" title="INÍCIO-INÍCIO: A ação começa junto com a ação vinculada. INÍCIO-FIM: A ação Inícia após o término da ação vinculada."></i>
                                                <?php $pst[''] = '';
                                                  $pst['II'] = lang('INÍCIO - INÍCIO');
                                                  $pst['IF'] = lang('INÍCIO - FIM');


                                                echo form_dropdown('tipo_vinculo', $pst, (isset($_POST['tipo']) ? $_POST['tipo'] : ""), 'id="tipo"  class="form-control " data-placeholder="' . lang("select") . ' ' . lang("o tipo de Vinculo") . '"   style="width:100%;" ');

                                          ?>

                                            </div>
                                         </div>
                                      <center>

                                        <div class="col-md-12">
                                        <?php echo form_submit('add_item', lang("Adcionar Vinculo"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;" onclick="alertas(); " '); ?>
                                            <?php echo form_close(); ?>
                                        </div>
                                         </center>
                                      <div class="portlet-body">

                                        <table id="example1" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                                <thead>
                                                    <tr>
                                                        <th><font style="font-size: 10px;">ID</font></th>
                                                        <th><font style="font-size: 10px;">AÇÃO</th>
                                                        <th><font style="font-size: 10px;">PRAZO</th>
                                                        <th><font style="font-size: 10px;">TIPO</th>
                                                        <th><font style="font-size: 10px;">OPÇÃO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $wu4[''] = '';
                                                    $cont_planoContinuo = 1;
                                                    foreach ($acoes_vinculadas as $user_ata) {
                                                       $acao_detalhes = $this->atas_model->getPlanoByID($user_ata->id_vinculo);
                                                       $tipo = $user_ata->tipo;
                                                       if($tipo == 'II'){
                                                           $desc_tipo = 'INÍCIO - INÍCIO';
                                                       }else if($tipo == 'IF'){
                                                           $desc_tipo = 'INICIO - FIM';
                                                       }

                                                    ?>   
                                                            <tr class="odd gradeX">
                                                                <td><font style="font-size: 10px;"><?php echo $user_ata->id_vinculo; ?></font></td>
                                                                <td><font style="font-size: 10px;"><?php echo $acao_detalhes->descricao; ?></font></td>
                                                                <td><font style="font-size: 10px;"><?php echo '( '. exibirData($acao_detalhes->data_entrega_demanda).' - '.exibirData($acao_detalhes->data_termino).' ) '; ?></font></td>
                                                                <td><font style="font-size: 10px;"><?php echo $desc_tipo; ?></font></td>  

                                                        <td class="center">
                                                            <a style="color: red;" title="Apagar Vinculo" href="<?= site_url('project/remove_vinculo_acao_ata/' . $user_ata->id . '/' . $idplano); ?>"><font style="font-size: 10px;"><i class="fa fa-trash-o"></i> Deletar</font></a>
                                                        </td>


                                                        </tr>
                                                        <?php

                                                }
                                                ?>




                                                </tbody>
                                     </table>

                                    </div>  

                              </div>
                              <div class="tab-pane" id="settings">
                                  <?php
                                        $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                        echo form_open_multipart("project/manutencao_acao_arquivos_ata", $attrib); 
                                        echo form_hidden('id', $acao->idplanos);
                                        echo form_hidden('idatas', $acao->idplano);
                                      ?>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <?= lang("Descrição", "descricao"); ?>
                                          <?php echo form_input('descricao_arquivo', (isset($_POST['descricao']) ? $_POST['descricao'] : ""), 'class="form-control input" maxlength="250"   id="descricao"  '); ?>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <?= lang("Arquivo", "descricao"); ?>
                                          <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" value="<?php echo $projeto->anexo; ?>" data-show-upload="false"
                                                 data-show-preview="false" class="form-control file">
                                      </div>
                                  </div>
                                  <center>
                                      <div class="col-md-12">
                                          <?php echo form_submit('add_acao_arquivo', lang("Adcionar Arquivo"), 'id="add_item" class="btn btn-primary " style="padding: 6px 15px; margin:15px 0;"  " '); ?>
                                          <?php echo form_close(); ?>
                                      </div>
                                  </center>
                                      <div class="portlet-body">
                                        <table id="example2" class="table table-striped sorting_asc_disabled table-bordered table-hover table-green">
                                                <thead>
                                                    <tr>
                                                        <th><font style="font-size: 10px;">ID</font></th>
                                                        <th><font style="font-size: 10px;">DESCRIÇÃO</th>
                                                        <th><font style="font-size: 10px;">ARQUIVO</th>
                                                        <th><font style="font-size: 10px;">DOWNLOAD</th>
                                                        <th><font style="font-size: 10px;">OPÇÃO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $wu4[''] = '';
                                                    $cont_arquivo = 1;
                                                    foreach ($acoes_arquivos as $arquivo) {
                                                     
                                                    ?>   
                                                            <tr class="odd gradeX">
                                                                <td><font style="font-size: 10px;"><?php echo $cont_arquivo++; ?></font></td>
                                                                <td><font style="font-size: 10px;"><?php echo $arquivo->descricao; ?></font></td>
                                                                <td><font style="font-size: 10px;"><?php echo $arquivo->anexo; ?></font></td>
                                                                <td><font style="font-size: 10px;"><a target="_blank" href="assets/uploads/planos/arquivos/<?php echo $arquivo->anexo; ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>"><i class="fa fa-download"></i></a></font></td>  
                                                                <td class="center">
                                                                    <a style="color: red;" title="Apagar Arquivo" href="<?= site_url('project/remove_arquivo_acao_ata/' . $arquivo->id); ?>"><font style="font-size: 10px;"><i class="fa fa-trash-o"></i> Excluir</font></a>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                    }
                                                    ?>




                                                </tbody>
                                     </table>

                                    </div> 
                              </div>
                            </div>
                        </div>
                   
                </div>
                
                </div>
    
   
        
    </section>    

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    //$('#reservation').daterangepicker();
    
     $(function() { $("#reservation").daterangepicker({
            locale: { format: 'DD/MM/YYYY' } ,  language: 'pt-BR',
            minDate: '<?php echo exibirData($inicio_fase) ?>',
            maxDate: '<?php echo exibirData($fim_fase) ?>'
        
        }); });
     

    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'DD/MM/YYYY' })
    
    
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',                
    language: 'pt-BR'
    })
    

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })
</script>