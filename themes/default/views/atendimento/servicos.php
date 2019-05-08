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
                    
                        <a  href="<?= site_url('atendimento/index'); ?>" >
                            <img width="170px" height="50px" src="<?= base_url() ?>assets/uploads/logos/LogoUnimed1.png " >
                        </a>  
                      
                    <div class="box-header">
                        <center>     <h3 >Serviços Ativos</h3> </center>
                        </div>
                </nav>
        <!-- begin MAIN PAGE CONTENT -->
        <br>
            
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("atendimento/servicos_novo", $attrib);
                   ?>
                    <div class="col-md-12">
                        <h4>Novo Cadastro</h4>
                        <br>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                             <input type="text" class="form-control" required="true" placeholder="Novo Registro"  maxlength="150" name="descricao_servico" >
                          </div>
                             <?php echo form_submit('add_item', lang("Cadastrar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;"  '); ?> 
                        </div>
                    
                    <?php echo form_close(); ?>
        <div id="page-wrapper">
            
                <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                           <div class="box">
                        <!-- /.box-header -->
                          <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Descrição</th>
                                        <th>Opção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                   
                                    foreach ($servicos as $servico) {
                                        $id = $servico->id;
                                        $descricao = $servico->descricao;
                                      
                                    ?>
                                    <tr>
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo $descricao; ?></td>
                                        
                                        <td><a class="btn btn-danger" href="<?= site_url('atendimento/deletar_servico/'.$id); ?>" >Excluir</a></td>
                                    </tr>
                                    <?php } ?>
                                  
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Descrição</th>
                                        <th>Opção</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                        </div>    
                     </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                </section>

           
           
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
