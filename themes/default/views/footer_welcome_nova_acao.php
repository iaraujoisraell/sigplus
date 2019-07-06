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
 

?>

<div class="col-lg-12">
<?php 
  $this->load->view($this->theme . 'footer_rodape_theme2'); 
  ?>
</div>
</div>
  
<div class="clearfix"></div>

<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>
<div id="modal-loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>


<script type="text/javascript">
var dt_lang = <?=$dt_lang?>, dp_lang = <?=$dp_lang?>, site = <?=json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats))?>;
var lang = {paid: '<?=lang('paid');?>', pending: '<?=lang('pending');?>', completed: '<?=lang('completed');?>', ordered: '<?=lang('ordered');?>', received: '<?=lang('received');?>', partial: '<?=lang('partial');?>', sent: '<?=lang('sent');?>', r_u_sure: '<?=lang('r_u_sure');?>', due: '<?=lang('due');?>', returned: '<?=lang('returned');?>', transferring: '<?=lang('transferring');?>', active: '<?=lang('active');?>', inactive: '<?=lang('inactive');?>', unexpected_value: '<?=lang('unexpected_value');?>', select_above: '<?=lang('select_above');?>'};
</script>
  
  
  
<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>
    
<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>

<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/core.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/perfect-scrollbar.min.js"></script>



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


<?php
/*
<script src="<?= $assets ?>bi/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= $assets ?>bi/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= $assets ?>bi/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?= $assets ?>bi/bower_components/raphael/raphael.min.js"></script>
<script src="<?= $assets ?>bi/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="<?= $assets ?>bi/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?= $assets ?>bi/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?= $assets ?>bi/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?= $assets ?>bi/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?= $assets ?>bi/bower_components/moment/min/moment.min.js"></script>
<script src="<?= $assets ?>bi/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?= $assets ?>bi/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?= $assets ?>bi/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?= $assets ?>bi/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= $assets ?>bi/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= $assets ?>bi/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= $assets ?>bi/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= $assets ?>bi/dist/js/demo.js"></script>
 * 
 */
?>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#table_ata').DataTable()
    $('#minha_agenda_home').DataTable()//
    $('#example7').DataTable()  // network > acoes
    $('#example3').DataTable()
    $('#example4').DataTable()
    $('#example5').DataTable()
    $('#example6').DataTable()
    $('#minhas_atas').DataTable()
    $('#tarefas').DataTable() 
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      'iDisplayLength': <?=$Settings->rows_per_page?>
    })
  })
</script>

</body>
</html>
