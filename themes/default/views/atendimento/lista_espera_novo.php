<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">
<?php  $this->load->view($this->theme . 'atendimento/head'); ?>
 
<body>

    <div id="wrapper">

         
                <!-- begin TOP NAVIGATION  style="background-color: seagreen; position: relative; width: 100%; height: 50px;"-->
                
                <nav style="background-color: <?php echo $projetos->botao; ?>;" class="navbar-top"  role="navigation">
                    
                        <a  href="<?= site_url('atendimento'); ?>" >
                            <img width="170px" height="50px" src="<?= base_url() ?>assets/uploads/logos/LogoUnimed1.png " >
                        </a>  
                     
                    <div class="box-header">
                        <center>     <h3 > Novo Registro na Lista de Espera</h3> </center>
                        </div>
                </nav>
        <!-- begin MAIN PAGE CONTENT -->
        <br>
        <div id="page-wrapper">
            
             <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("atendimento/lista_espera_novo", $attrib);
           ?>
            
            <div class="col-md-12">
            
            <div class="col-md-12">
                 
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Beneficiário</label>
                    <input type="text" class="form-control" required="true" name="beneficiario" >
                  </div>
                </div> 
                 
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Número Carteirinha</label>
                    <input type="text" class="form-control"  name="cateirinha" >
                  </div>
                </div>
                 
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Número Protocolo</label>
                     <input type="text" class="form-control"  name="protocolo" >
                  </div>
                </div> 
             </div>        
                
             <div class="col-md-12">
                 
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Serviços</label>
                    <select name="servicos" class="form-control select2" style="width: 100%;">
                      <option selected="selected">Selecione</option>
                      <?php
                       foreach ($servicos as $servico) {
                        $nome_servico = $servico->descricao;
                      ?>
                      <option value="<?php echo $nome_servico; ?>" ><?php echo $nome_servico; ?></option>
                       <?php } ?>
                    </select>
                  </div>
                </div> 
                 
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Especialidades/ Consulta</label>
                    <select name="especialidade" class="form-control select2" style="width: 100%;">
                      <option selected="selected">Selecione</option>
                       <?php
                       foreach ($especialidades as $especialidade) {
                        $nome_especialidade = $especialidade->especialidade;
                      ?>
                      <option value="<?php echo $nome_especialidade; ?>" ><?php echo $nome_especialidade; ?></option>
                       <?php } ?>
                    </select>
                  </div>
                </div>
                 
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Prestador </label>
                    <select name="prestador" class="form-control select2" style="width: 100%;">
                      <option selected="selected">Selecione</option>
                      <?php
                       foreach ($prestadores as $prestador) {
                        $id = $prestador->id;
                        $codigo = $prestador->codigo;
                        $nome = $prestador->nome;
                        $crm = $prestador->ctp_numero_conselho;
                      ?>
                      <option ><?php echo $crm.' - '. $nome; ?></option>
                       <?php } ?>
                      
                    </select>
                  </div>
                </div> 
             </div>    
             
                <div class="col-md-12">    
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control select2" style="width: 100%;">
                      <option value="0" selected="selected">Pendente</option>
                      <option value="1">Concluído</option>
                      <option value="2">Cancelado</option>
                    </select>
                  </div>
                </div> 
                
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Observações/ Anotações</label>
                    <textarea name="observacoes" class="form-control"></textarea>
                  </div>
                </div> 
                </div>       
             </div>     
            <div class="col-md-12">
                <center>  
                    <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;"  '); ?> 
                    <a class="btn btn-primary" href="<?= site_url('atendimento/lista_espera'); ?>" >Voltar</a>
                </center>
                                        
            </div>    
            
               
            <?php echo form_close(); ?>
            
                

           
           
        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->

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
    
    
    <script src="<?= $assets ?>bi/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= $assets ?>bi/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
   
    
    
        
        <script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>


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
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
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
      autoclose: true
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

</body>

</html>
