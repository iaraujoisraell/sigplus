  

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
$projetos = $this->projetos_model->getProjetoAtualByID_completo();
$id_projeto = $projetos->id;
$nome_projeto = $projetos->nome_projeto;
$inicio_fase = $projetos->dt_inicio;
$fim_fase = $projetos->dt_final;
?>
<div id="blanket"></div>
<div id="aguarde">Aguarde...</div>

    <div class="col-lg-12">
    <div class="box">
    <section class="content-header">
          <h1>
            <?php echo 'Nova Ação'; ?>
              <small><?php echo $nome_projeto; ?></small>
                  
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
    
    <div class="row">    
     
    
    <div class="col-lg-12">
        <div class="box">
            <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <h2> NOVA AÇÃO  DA ATA <?php echo $ata; ?>  </h2>
                                    </center>
                                </div>
                            </div>
                              <div id="blanket"></div>
                                <div id="aguarde">Aguarde...Enviando Email</div>
                            <div class="clearfix"></div>
                            <?php
                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("Atas/plano_acao/".$ata, $attrib);
                            echo form_hidden('id', $ata);
                            echo form_hidden('plano_acao', $plano_acao);
                            
                           // echo form_hidden('avulsa', $avulsa);
                            ?>
                            <div class="col-md-12">
                            <!-- ITEM EVENTO -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Item do Evento", "slEvento"); ?>
                                         <?php
                                        $wue[''] = '';
                                        foreach ($eventos as $evento) {
                                            $wue[$evento->id] = $evento->nome_fase.' > '. $evento->nome_evento.' > '. resume($evento->descricao, 100). ' ( '.exibirData($evento->dt_inicio).' - '.exibirData($evento->dt_fim).' ) ';
                                        }
                                        echo form_dropdown('evento', $wue, (isset($_POST['evento']) ? $_POST['evento'] : ""), 'id="slEvento"  class="form-control  select" data-placeholder="' . lang("Selecione o Item do Evento") . ' " required="required"  style="width:100%;"  ');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php if($plano_acao){ ?>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Categoria ", "categoria"); ?><small>(Categoria no Plano de Ação)</small>
                                        <?php
                                        $categorias = $this->atas_model->getAllCategoriaPlanoAcaoByPlano($plano_acao);
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
                                   
                                   
                                 
                                
                            </div>
                            
                                <div class="col-md-6">
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
                                       <!-- PRAZO de -->
                                <div class="form-group">
                                <?= lang("Data Início", "sldate"); ?><small>(Quando ?)</small>
                                <i class="fa fa-info-circle" title="A Data de Início e Término, precisa estar dentro do período de datas do Item do Evento selecionado."></i>
                                <input title="O período de data da ação, não pode estar fora do período de datas do Item de evento selecionado." name="data_inicio" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control"> 

                                </div>
                         <!-- PRAZO ATE -->
                                <div class="form-group">
                                    <?= lang("Data  Término", "sldate"); ?><small>(Quando ?)</small>
                                    <i class="fa fa-info-circle" title="A Data de Início e Término, precisa estar dentro do período de datas do Item do Evento selecionado."></i>
                                    <input title="O período de data da ação, não pode estar fora do período de datas do Item de evento selecionado." name="data_termino" type="date" value="<?php echo date('Y-m-d'); ?>" class="form-control"> 

                                </div>
                                    
                               
                                   
                                    
                                    <!-- HORAS -->
                                  <div class="form-group">
                                        <?= lang("Horas Previstas", "horas"); ?>
                                       <input class="form-control input-tip" placeholder="Horas Previstas" value="<?php echo $acao->horas_previstas; ?>"  name="horas_previstas" type="number">
                                       </div>
                                    
                                   
                                   
                                   <div class="form-group">
                                        <?= lang("Peso da Ação ", "peso"); ?><small>(Nível de Importancia/Impácto para o projeto)</small>
                                        <i class="fa fa-info-circle" title="1-Normal: Não é Importante ou Urgente. 2-Pouco: Um Pouco Importante mas não é Urgente. 3-Médio: Importância e Urgência Média. 4-Grande: Importante e Urgente. 5-Muito Grande: Muito Importante e Muito Urgente "></i>
                                        <br>
                                        <input type="radio" class="form-control  "  checked="true"  value="1" name="peso">  1
                                        <input type="radio" class="form-control  "  value="2" name="peso">  2
                                        <input type="radio" class="form-control  "  value="3" name="peso">  3
                                        <input type="radio" class="form-control  "  value="4" name="peso">  4
                                        <input type="radio" class="form-control  "  value="5" name="peso">  5
                                            
                                           
                                    </div>
                                   
                                  
                                   
                                    <!-- VALOR -->  
                                    <div class="form-group">
                                        <?= lang("Custo", "custo"); ?><small> (Descrição do Custo? )</small>
                                        <?php echo form_textarea('custo', (isset($_POST['custo']) ? $_POST['custo'] : $acao->custo), 'class="form-control"   style="height: 120px;" id="custo"  '); ?>
                                    </div>
                                    <div class="form-group">
                                    <?= lang("Valor", "custo"); ?><small> (Valor do Custo? )</small>
                                    <input class="form-control" placeholder="Valor do Custo" onkeypress="mascara(this, mvalor);" value="<?php echo $acao->custo; ?>"  name="valor_custo" type="text">
                                    </div>
                                    
                                     
                                   
                                </div>
                           
                            </div>    
                            
                            
                            
                            

                            <center>

                                <div class="col-md-12">
                                      <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" '); ?>
                                        <a  class="btn btn-danger" class="close" data-dismiss="modal"  href="<?= site_url('Atas/plano_acao/'.$ata); ?>"><?= lang('Sair') ?></a>
                             
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