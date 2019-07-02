
<?php
//$this->load->view($this->theme . 'usuarios/new/footer_rodape');
       $this->load->view($this->theme . 'menu_direito_networking'); 
?>



<?php 
 $usuario = $this->session->userdata('user_id');
 $users_dados = $this->site->geUserByID($usuario);
         
 $modulo_atual_id = $users_dados->modulo_atual;
 $empresa = $users_dados->empresa_id;
 
 $modulo = $this->owner_model->getModuloById($modulo_atual_id);
 $nome_modulo = $modulo->descricao;
 $cor_modulo = $modulo->cor;
 
 $empresa = $this->owner_model->getEmpresaById($empresa);
 $nome_empresa = $empresa->razaoSocial;
 
// $this->load->view($this->theme . 'footer_rodape_theme2'); 
?>

<div class="col-lg-12">
<?php 
 // $this->load->view($this->theme . 'footer_rodape_theme2'); 
  ?>
</div>
</div>
  
  <div class="clearfix"></div>



<script src="<?= $assets ?>bi/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= $assets ?>bi/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= $assets ?>bi/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Slimscroll -->
<script src="<?= $assets ?>bi/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= $assets ?>bi/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= $assets ?>bi/dist/js/adminlte.min.js"></script>
<script src="<?= $assets ?>bi/dist/js/demo.js"></script>
<!-- fullCalendar -->
<script src="<?= $assets ?>bi/bower_components/moment/moment.js"></script>
<script src="<?= $assets ?>bi/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>



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
<!-- iCheck 1.0.1 -->
<script src="<?= $assets ?>bi/plugins/iCheck/icheck.min.js"></script>



<!-- Page script -->
<script>
  $(function () {
    
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
