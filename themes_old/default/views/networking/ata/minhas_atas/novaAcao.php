<?php
    function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
              if (strlen($var) > $limite)	{
                    $var = substr($var, 0, $limite);		
                    $var = trim($var) . "...";	

              }return $var;

              }
          ?>

<?php
$plano_acao = $this->atas_model->getPlanoAcaoByID($registro);
?>

    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
             <?php
             if($tipo == 2){ 
             ?>
            <?php echo 'Nova Ação'; ?><small><?php echo $plano_acao->assunto;  ?></small>
             <?php }else{ ?>
            <?php echo 'Nova Ação'; ?>
            <?php } ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= site_url('Networking'); ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Nova Ação</li>
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
    <?php
        //$plano_acao = $this->atas_model->getPlanoAcaoByID($id_pa);
    ?>
    <section  class="content">
    <div class="row">    
    <div class="col-lg-12">
        <div class="box">
            <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <h2> CADASTRO DE NOVA AÇÃO </h2>
                                    </center>
                                </div>
                            </div>                              
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("welcome/plano_acao/".$registro, $attrib);
                            echo form_hidden('id', $registro);
                            echo form_hidden('tipo', $tipo);
                            ?>
                            <div class="col-md-12">
                            <!-- ITEM EVENTO -->
                            <?php if($tipo == 2){ ?>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Categoria ", "categoria"); ?><small>(Categoria no Plano de Ação)</small>
                                        <?php
                                        $categorias = $this->networking_model->getAllCategoriaPlanoAcaoByPlano($registro);
                                        foreach ($categorias as $categoria) {
                                            $wu_cat[$categoria->id] = $categoria->descricao;
                                        }
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('categoria', $wu_cat, (isset($_POST['categoria']) ? $_POST['categoria'] : ""), 'id="categoria"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"   ');
                              
                                        ?>
                                    </div>
                                </div>    
                            </div>  
                            <?php } ?>
                            <div class="col-md-12">
                                
                                <div class="col-md-6">
                                  <!-- DESCRIÇÃO DA AÇÃO -->  
                                  
                                  <div  class="form-group">
                                        <?= lang("Descrição ", "sldescricao"); ?><small> (O que ?)</small>
                                        <?php echo form_textarea('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao), 'class="form-control  input-tip "   style="height: 120px;" id="sldescricao" required="true" '); ?>
                                  </div>
                              
                                    <!-- ONDE -->  
                                  <div  class="form-group">
                                        <?= lang("Local ", "onde"); ?><small>(Onde ?)</small>
                                        <?php echo form_textarea('onde', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao), 'class="form-control"   style="height: 120px;" id="onde"  '); ?>
                                  </div>
                                    <!-- PRAZO de -->
                                    <div class="form-group">
                                        <?= lang("Data Início", "sldate"); ?><small>(De Quando ?)</small>
                                        <input type="date" value="<?php echo date('Y-m-d'); ?>" title="O período de data da ação, não pode estar fora do período de datas do Item de evento selecionado." name="data_inicio" class="form-control pull-right" id="reservation">
                                    </div>
                                    <!-- PRAZO ATE -->
                                    <div class="form-group">
                                        <?= lang("Data Término", "sldate"); ?><small>(Até Quando ?)</small>
                                        <input type="date" value="<?php echo date('Y-m-d'); ?>" title="O período de data da ação, não pode estar fora do período de datas do Item de evento selecionado." name="data_termino" class="form-control pull-right" id="reservation">
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
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $participantes_usuarios), 'id="slResponsavel" required  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"  multiple ');
                                        ?>
                                    </div>
                                   
                                   <div class="form-group">
                                        <?= lang("Peso da Ação ", "peso"); ?><small>(Nível de Importancia/Impácto para o projeto)</small>
                                        <i class="fa fa-info-circle" title="1-Normal: Não é Importante ou Urgente. 2-Pouco: Um Pouco Importante mas não é Urgente. 3-Médio: Importância e Urgência Média. 4-Grande: Importante e Urgente. 5-Muito Grande: Muito Importante e Muito Urgente "></i>
                                         <br>
                                        <input type="radio" class="form-control  " checked="true"  value="1" name="peso">  1
                                        <input type="radio" class="form-control  "  value="2" name="peso">  2
                                        <input type="radio" class="form-control  "  value="3" name="peso">  3
                                        <input type="radio" class="form-control  "  value="4" name="peso">  4
                                        <input type="radio" class="form-control  "  value="5" name="peso">  5
                                           
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
                                    <input class="form-control" placeholder="Valor do Custo" onkeypress="mascara(this, mvalor);" value="<?php echo $acao->custo; ?>"  name="valor_custo" type="text">
                                    
                                   
                                </div>
                           
                            </div>    
                            
                            
                          
                            

                            <center>

                                <div class="col-md-12">
                                      <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                         <button  class="btn btn-danger" onclick="history.go(-1)"><?= lang('Sair') ?></button>
                             
                                </div>
                                 </center>
                             <?php echo form_close(); ?>
                       
       
                <br><br><br>
                </div>
            
            
            <div class="row">
                <br>
                <BR>
            </div>
            
        </div>        
    </div>
        
   
    
    
        
    </div>
        
    </section>    


<!-- InputMask -->

<!-- Page script -->
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
            locale: { format: 'DD/MM/YYYY' } ,  language: 'pt-BR'
        
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



