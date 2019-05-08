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
                        <center>     <h3 > Novo Registro de Prestador<br>  <b></b> </h3> </center>
                        </div>
                </nav>
        <!-- begin MAIN PAGE CONTENT -->
        <br>
        <div id="page-wrapper">
          <div class="col-md-12">   
           <div class="col-md-12"> 
            <div class="nav-tabs-custom">
            <ul style="background-color: Silver; " class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Dados Cadastrais</a></li>
             
              
              
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <br><br>   
                <div class="row">
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("atendimento/prestador_novo/", $attrib);
                   
                   ?>
                    <div class="col-md-12">

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Código</label>
                            <input type="text"  class="form-control" required="true" name="codigo" >
                          </div>
                        </div> 

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Nome Prestador</label>
                            <input type="text" class="form-control" required="true"  name="prestador" >
                          </div>
                        </div>

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>CRM</label>
                             <input type="text" class="form-control"   name="crm" >
                          </div>
                        </div> 
                     </div>        
                    <div class="col-md-12">

                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Tipo Endereço</label>
                            <select name="tipo_endereco"  class="form-control select2" style="width: 100%;">
                              <option value="" selected="selected">Selecione</option>
                              <option value="Comercial" >Comercial</option>
                              <option value="Residencial" >Residencial</option>
                            </select>
                          </div>
                        </div> 

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Endereço</label>
                            <input type="text" class="form-control"  name="endereco" >
                          </div>
                        </div>

                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Número </label>
                            <input type="text" class="form-control"  name="end_numero" >
                          </div>
                        </div> 

                        <div class="col-md-2">
                          <div class="form-group">
                            <label>CEP </label>
                            <input type="text" class="form-control"  name="end_cep" >
                          </div>
                        </div>  

                       <div class="col-md-4">
                          <div class="form-group">
                            <label>Bairro </label>
                            <input type="text" class="form-control"   name="bairro" >
                          </div>
                        </div> 

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Cidade </label>
                            <input type="text" class="form-control"   name="cidade" >
                          </div>
                        </div>  

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Prestador Cooperado?</label>
                            <select name="cooperado"  class="form-control select2" style="width: 100%;">
                               <option value="SIM" >SIM</option>
                              <option value="NÃO" >NÃO</option>
                            </select>
                          </div>
                        </div>  
                     </div>    
                    <div class="col-md-12">
                        <center>  
                            <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;"  '); ?> 
                            <a class="btn btn-primary" href="<?= site_url('atendimento/prestadores'); ?>" >Voltar</a>
                        </center>

                    </div>    
                    <?php echo form_close(); ?>
                  </div>    
              </div>
             
            </div>
            <!-- /.tab-content -->
            </div>
          <!-- nav-tabs-custom -->
           </div> 
          </div>  
           
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
