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
                        <center>     <h3 > Prestador <small>Especialidades / Serviços</small> </h3> </center>
                        </div>
                </nav>
        <!-- begin MAIN PAGE CONTENT -->
        <br>
        <div class="col-md-12">
        <a class="btn btn-success" href="<?= site_url('atendimento/prestador_novo/'); ?>" >Novo Cadastro</a> 
        </div>
        <br> <br>
        <div id="page-wrapper">
          
            
                <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        
                        
                      <div class="col-md-12">
                         <div class="box">
                               
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Código</th>
                                        <th>Prestador</th>
                                        <th>CRM</th>
                                        <th>Cooperado</th>
                                        <th>Especialidades</th>
                                        <th>Seriços</th>
                                        <th>Endereço</th>
                                        <th style="width: 150px; min-width: 150px;">Telefones</th>
                                        <th>Opção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                   $cont = 1;
                                    foreach ($prestadores as $prestador) {
                                        $id = $prestador->id;
                                        $codigo = $prestador->codigo;
                                        $nome = $prestador->nome;
                                        $crm = $prestador->ctp_numero_conselho;
                                        $cooperado = $prestador->cooperado;
                                        $tipo_endereco = $prestador->tipo_endereco;
                                        $endereco = $prestador->endereco;
                                        $endereco_numero = $prestador->end_numero;
                                        $end_cep = $prestador->end_cep;
                                        $bairro = $prestador->end_bairro;
                                        $municipio = $prestador->end_municipio;
                                        
                                    ?>
                                    <tr>
                                        <td><?php echo $cont++; ?></td>
                                        <td><?php echo $codigo; ?></td>
                                        <td><?php echo $nome; ?></td>
                                        <td><?php echo $crm; ?></td>
                                        <td><?php echo $cooperado; ?></td>
                                        <td style="width: 200px; min-width: 200px;" >
                                            <table>
                                            <?php 
                                            $especialidades = $this->atas_model->getAllEspecialidadesPrestadoresSai($codigo);
                                            foreach ($especialidades as $especialidade2) {
                                                $especialidade = $especialidade2->especialidade;
                                                $especialidade_valor = $especialidade2->valor;
                                                
                                            ?>
                                            <tr>
                                                <td >* <?php echo $especialidade; ?> <?php if($especialidade_valor){ echo ' R$ '. $especialidade_valor; }  ?></td>  
                                            </tr>    
                                            <?php
                                            }
                                            ?>
                                            </table>
                                        </td>
                                        <td style="width: 200px; min-width: 200px;" >
                                            <table>
                                            <?php 
                                            $servicos = $this->atas_model->getAllServicosPrestadoresSai($codigo);
                                            foreach ($servicos as $servicos2) {
                                                $servico = $servicos2->especialidade;
                                                $servico_valor = $servicos2->valor;
                                                
                                            ?>
                                            <tr>
                                                <td >* <?php echo $servico; ?> <?php if($servico_valor){ echo ' R$ '. $servico_valor; }  ?></td>  
                                            </tr>    
                                            <?php
                                            }
                                            ?>
                                            </table>
                                        </td>
                                        <td><font style="font-size: 12px;"><?php echo $tipo_endereco.' : '.$endereco.','.$endereco_numero.'. '.$bairro.'.'; ?></font></td>
                                        <td style="width: 150px; min-width: 150px;" >
                                            <table>
                                            <?php 
                                            $telefones = $this->atas_model->getAllTelefonesPrestadoresSai($codigo);
                                            foreach ($telefones as $telefone) {
                                                $ddd = $telefone->ddd;
                                                $fone = $telefone->telefone;
                                                $tipo = $telefone->tipo;
                                            ?>
                                            <tr>
                                                <td title="<?php echo $tipo; ?>"><?php echo $ddd.' '.$fone ?></td>  
                                            </tr>    
                                            <?php
                                            }
                                            ?>
                                            </table>
                                        </td>
                                        <td> <?php // if($status == 0){ ?> <a class="btn btn-primary" href="<?= site_url('atendimento/prestador_editar/'.$id); ?>" >Abrir</a> <?php // } ?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                       <tr>
                                        <th>Id</th>
                                        <th>Código</th>
                                        <th>Prestador</th>
                                        <th>CRM</th>
                                        <th>Especialidades</th>
                                        <th>Endereço</th>
                                        <th>Telefones</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                            </table>
                            </div>    
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
