
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/Ionicons/css/ionicons.min.css">
  
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?= $assets ?>bi/plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?= $assets ?>bi/plugins/timepicker/bootstrap-timepicker.min.css">
  
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= $assets ?>bi/bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/skins/_all-skins.min.css">
  
<?php 
    
    $usuario =  $this->session->userdata('user_id'); 
      function exibirData($data){
	$rData = explode("-", $data);
	$rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
	return $rData;
   }
?>
  
<div  class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <a type="button" class="close" onclick="history.go(-1)" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </a>
            <?php $dadosEvento  = $this->projetos_model->getEventoByID($fase);
            $nome_evento = $dadosEvento->nome_evento;
            $inicio_evento = $dadosEvento->data_inicio;
            $fim_evento = $dadosEvento->data_fim;
            ?>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang($nome_evento).'  | Novo Item de Evento'; ?></h4>
            
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("project/novoItemEvento", $attrib); 
            echo form_hidden('id_itemEvento', '1'); 
            echo form_hidden('fase', $fase); 
      
            
        ?>
        <div class="modal-body">
           
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                         <?= lang("Descrição do Item", "evento"); ?>
                          <?php echo form_input('evento', $dados_tabela->$campo_banco, 'class="form-control input" title="Nome do Item do Evento" maxlength="250" id="evento"  required="required" '); ?>
                    
                    </div>
                    <div class="form-group company">
                         <?= lang("Período do Evento", "periodo"); ?>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            
                            <input type="text" value="<?php echo exibirData($inicio_evento) ?> - <?php echo exibirData($fim_evento) ?>" required="true" title="O período de data do item, não pode estar fora do período de datas do Evento."  name="periodo_evento" class="form-control pull-right" id="reservation">
                        </div>
                    </div>
                    
                    <div class="form-group company">
                       <?= lang("Responsável ", "responsavel"); ?>
                            <?php 
                              $dados_tabelas_fk = $this->owner_model->getDadosTablesUsers();// retorna os dados da tabela escolhida
                              
                              $pst_fk_campo = "";
                              $pst_fk_campo[''] = "Selecione";
                              foreach ($dados_tabelas_fk as $tabela_campo) {
                                  
                                  $pst_fk_campo[$tabela_campo->id] = $tabela_campo->first_name.' - '. $tabela_campo->funcao;
                                 
                                    
                                }
                              echo form_dropdown('responsavel', $pst_fk_campo, (isset($_POST['reacao']) ? $_POST['reacao'] : ""), 'id="responsavel"  required="true" title="O Responsável pelo item, é a pessoa que irá responder ou representar por este Item do evento. Irá fornecer informações sobre o anadamento do Item." class="form-control selectpicker  select" data-placeholder="' . lang("N/A") . ' "  style="width:100%;" ');
                              
                              ?>
                        
                    </div>
                    <div class="form-group company">
                       <?= lang("O responsável irá validar as ações deste Item?", "validar_acoes"); ?>
                        <br>
                        <input type="radio" name="validar_acoes" value="1" checked="true" >SIM
                        
                        <input type="radio" name="validar_acoes" value="0" >NÃO
                    </div>
                    
                    <div class="form-group company">
                         <?= lang("Horas Previstas", "evento"); ?>
                        <input name="horas_previstas" class="form-control" type="number" maxlength="6" title="Horas previstas para a execução do item."  >
                    </div>
                    
                </div>
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
            <a class="btn btn-danger " onclick="history.go(-1)" > Fechar</a>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
</div>


  <script src="<?= $assets ?>bi/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= $assets ?>bi/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="<?= $assets ?>bi/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="<?= $assets ?>bi/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?= $assets ?>bi/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?= $assets ?>bi/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="<?= $assets ?>bi/bower_components/moment/min/moment.min.js"></script>
<script src="<?= $assets ?>bi/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="<?= $assets ?>bi/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="<?= $assets ?>bi/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="<?= $assets ?>bi/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="<?= $assets ?>bi/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="<?= $assets ?>bi/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="<?= $assets ?>bi/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= $assets ?>bi/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= $assets ?>bi/dist/js/demo.js"></script>
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
            minDate: '<?php echo exibirData($inicio_evento) ?>',
            maxDate: '<?php echo exibirData($fim_evento) ?>'
        
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
