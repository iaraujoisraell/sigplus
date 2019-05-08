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
                        <center>     <h3 > Editar Registro de Prestador<br>  <b><?php echo $dados->nome; ?></b> </h3> </center>
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
              <li><a  href="#tab_2" data-toggle="tab">Telefones</a></li>
              <li><a href="#tab_3" data-toggle="tab">Especialidades</a></li>
              <li><a href="#tab_4" data-toggle="tab">Serviços</a></li>
              
              
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <br><br>   
                <div class="row">
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("atendimento/prestador_editar/$id", $attrib);
                    echo form_hidden('id', $id);
                    echo form_hidden('editar_cadastro', $id);
                   ?>
                    <div class="col-md-12">

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Código</label>
                            <input type="text"  class="form-control" value="<?php echo $dados->codigo; ?>" required="true" name="codigo" >
                          </div>
                        </div> 

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Nome Prestador</label>
                            <input type="text" class="form-control" value="<?php echo $dados->nome; ?>"  name="prestador" >
                          </div>
                        </div>

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>CRM</label>
                             <input type="text" class="form-control" value="<?php echo $dados->ctp_numero_conselho; ?>"  name="crm" >
                          </div>
                        </div> 
                     </div>        
                    <div class="col-md-12">

                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Tipo Endereço</label>
                            <select name="tipo_endereco"  class="form-control select2" style="width: 100%;">
                              <option value="<?php echo $dados->tipo_endereco; ?>" selected="selected"><?php echo $dados->tipo_endereco; ?></option>
                              <option value="Comercial" >Comercial</option>
                              <option value="Residencial" >Residencial</option>
                            </select>
                          </div>
                        </div> 

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Endereço</label>
                            <input type="text" class="form-control" value="<?php echo $dados->endereco; ?>"  name="endereco" >
                          </div>
                        </div>

                        <div class="col-md-2">
                          <div class="form-group">
                            <label>Número </label>
                            <input type="text" class="form-control" value="<?php echo $dados->end_numero; ?>"  name="end_numero" >
                          </div>
                        </div> 

                        <div class="col-md-2">
                          <div class="form-group">
                            <label>CEP </label>
                            <input type="text" class="form-control" value="<?php echo $dados->end_cep; ?>"  name="end_cep" >
                          </div>
                        </div>  

                       <div class="col-md-4">
                          <div class="form-group">
                            <label>Bairro </label>
                            <input type="text" class="form-control" value="<?php echo $dados->end_bairro; ?>"  name="end_bairro" >
                          </div>
                        </div> 

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Cidade </label>
                            <input type="text" class="form-control" value="<?php echo $dados->end_municipio; ?>"  name="end_cidade" >
                          </div>
                        </div>  

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Prestador Cooperado?</label>
                            <select name="cooperado"  class="form-control select2" style="width: 100%;">
                              <?php if($dados->cooperado){ ?>  
                              <option value="<?php echo $dados->cooperado; ?>" selected="selected"><?php echo $dados->cooperado; ?></option>
                              <?php } ?>
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
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <br>                 
                  <div class="row">
                       <h4>Novo Registro</h4>
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("atendimento/telefone_prestador_novo", $attrib);
                    echo form_hidden('id_prestador', $dados->id);
                    echo form_hidden('codigo', $dados->codigo);
                 
                   ?>
                    <div class="col-md-12">

                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo"  class="form-control select2" style="width: 100%;">
                              <option value="Comercial" >Comercial</option>
                              <option value="Residencial" >Residencial</option>
                            </select>
                          </div>
                        </div> 

                        <div class="col-md-2">
                          <div class="form-group">
                            <label>DDD</label>
                            <input type="text" class="form-control" required="true"  maxlength="2" name="ddd" >
                          </div>
                        </div>

                        <div class="col-md-7">
                          <div class="form-group">
                            <label>Telefone</label>
                             <input type="text" class="form-control" required="true"  maxlength="10"  name="telefone" >
                          </div>
                        </div> 
                     </div>        
                    <div class="col-md-12">
                        <center>  
                            <?php echo form_submit('add_item', lang("Cadastrar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;"  '); ?> 
                            <a class="btn btn-primary" href="<?= site_url('atendimento/prestadores'); ?>" >Voltar</a>
                        </center>

                    </div>    
                    <?php echo form_close(); ?>
                      
                      
                      <div class="col-md-12">
                          <br><br>
                     <h4>Lista de Telefones</h4>
                       <!-- /.box-header -->
                        <div class="col-md-12">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Tipo</th>
                                        <th>DDD</th>
                                        <th>Telefone</th>
                                        <th>Opção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                   $cont = 1;
                                   $telefones = $this->atas_model->getAllTelefonesPrestadoresSai($dados->codigo);
                                    foreach ($telefones as $telefone) {
                                                $ddd = $telefone->ddd;
                                                $fone = $telefone->telefone;
                                                $tipo = $telefone->tipo;
                                        
                                    ?>
                                    <tr>
                                        <td><?php echo $cont++; ?></td>
                                        <td><?php echo $tipo; ?></td>
                                        <td><?php echo $ddd; ?></td>
                                        <td><?php echo $fone; ?></td>
                                        <td> <?php // if($status == 0){ ?> <a class="btn btn-danger" href="<?= site_url('atendimento/deletar_telefone_prestador/'.$telefone->id.'/'.$dados->id); ?>" >Excluir</a> <?php // } ?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                       <tr>
                                        <th>Id</th>
                                        <th>Tipo</th>
                                        <th>DDD</th>
                                        <th>Telefone</th>
                                        <th>Opção</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        </div>
                       </div>
                       
                   </div>    
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                <br>
                  <div class="row">
                       
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("atendimento/especialidade_prestador_novo", $attrib);
                    echo form_hidden('id_prestador', $dados->id);
                    echo form_hidden('codigo', $dados->codigo);
                 
                   ?>
                    <div class="col-md-12">
                        <h4>Nova Especialidade</h4>
                        <br>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Especialidade</label>
                            <select name="especialidades" class="form-control select2" style="width: 100%;">
                              <option value="" selected="selected">Selecione</option>
                              <?php
                              $especialidades = $this->atas_model->getAllEspecialidadesSai();
                               foreach ($especialidades as $especialidade) {
                                $nome_especialidade = $especialidade->especialidade;
                              ?>
                              <option value="<?php echo $nome_especialidade; ?>" ><?php echo $nome_especialidade; ?></option>
                               <?php } ?>
                            </select>
                            <br><br>
                            <input type="text" class="form-control"  placeholder="Novo Registro"  maxlength="250" name="especialidade_prestador" >
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Valor Consulta</label>
                             <input type="text" class="form-control" placeholder="R$" required="true"  maxlength="10"  name="valor" >
                          </div>
                        </div> 
                     </div>        
                    <div class="col-md-12">
                        <center>  
                            <?php echo form_submit('add_item', lang("Cadastrar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;"  '); ?> 
                            <a class="btn btn-primary" href="<?= site_url('atendimento/prestadores'); ?>" >Voltar</a>
                        </center>

                    </div>    
                    <?php echo form_close(); ?>
                      
                      
                      <div class="col-md-12">
                          <br><br>
                     <h4>Lista de Especialidades do Prestador</h4>
                       <!-- /.box-header -->
                        <div class="col-md-12">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Especialidade</th>
                                        <th>Valor</th>
                                        <th>Data Registro</th>
                                        <th>Opção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $cont = 1;
                                    $especialidades_prestador = $this->atas_model->getAllEspecialidadesPrestadoresSai($dados->codigo);
                                    foreach ($especialidades_prestador as $esp) {
                                                $id = $esp->id;
                                                $especialidade = $esp->especialidade;
                                                $valor = $esp->valor;
                                                $data_registro = $esp->data_registro;
                                        
                                    ?>
                                    <tr>
                                        <td><?php echo $cont++; ?></td>
                                        <td><?php echo $especialidade; ?></td>
                                        <td><?php echo $valor; ?></td>
                                        <td><?php if($data_registro){ echo date('d/m/Y', strtotime($data_registro)); } ?></td>
                                        <td> <?php // if($status == 0){ ?> <a class="btn btn-danger" href="<?= site_url('atendimento/deletar_especialidade_prestador/'.$esp->id.'/'.$dados->id); ?>" >Excluir</a> <?php // } ?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                       <tr>
                                        <th>Id</th>
                                        <th>Especialidade</th>
                                        <th>Valor</th>
                                        <th>Data Registro</th>
                                        <th>Opção</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        </div>
                       </div>
                       
                   </div>  
              </div>
              
              <div class="tab-pane" id="tab_4">
                <br>
                  <div class="row">
                       
                    <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("atendimento/servico_prestador_novo", $attrib);
                    echo form_hidden('id_prestador', $dados->id);
                    echo form_hidden('codigo', $dados->codigo);
                 
                   ?>
                    <div class="col-md-12">
                        <h4>Novo Serviço</h4>
                        <br>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Serviço</label>
                            <select name="servicos" required="true" class="form-control select2" style="width: 100%;">
                              <option value="" selected="selected">Selecione</option>
                              <?php
                              $servicos = $this->atas_model->getAllServicosSai();
                               foreach ($servicos as $servico) {
                                $nome_servico = $servico->descricao;
                              ?>
                              <option value="<?php echo $nome_servico; ?>" ><?php echo $nome_servico; ?></option>
                               <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Valor Consulta</label>
                             <input type="text" class="form-control" placeholder="R$" required="true"  maxlength="10"  name="valor" >
                          </div>
                        </div> 
                     </div>        
                    <div class="col-md-12">
                        <center>  
                            <?php echo form_submit('add_item', lang("Cadastrar"), 'id="add_item" class="btn btn-success" style="padding: 6px 15px; margin:15px 0;"  '); ?> 
                            <a class="btn btn-primary" href="<?= site_url('atendimento/prestadores'); ?>" >Voltar</a>
                        </center>

                    </div>    
                    <?php echo form_close(); ?>
                      
                      
                      <div class="col-md-12">
                          <br><br>
                     <h4>Lista de Serviços do Prestador</h4>
                       <!-- /.box-header -->
                        <div class="col-md-12">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Serviço</th>
                                        <th>Valor</th>
                                        <th>Data Registro</th>
                                        <th>Opção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $cont = 1;
                                    $servicos_prestador = $this->atas_model->getAllServicosPrestadoresSaiByPrestador($dados->codigo);
                                    foreach ($servicos_prestador as $esp) {
                                                $id = $esp->id;
                                                $especialidade = $esp->especialidade;
                                                $valor = $esp->valor;
                                                $data_registro = $esp->data_registro;
                                        
                                    ?>
                                    <tr>
                                        <td><?php echo $cont++; ?></td>
                                        <td><?php echo $especialidade; ?></td>
                                        <td><?php echo $valor; ?></td>
                                        <td><?php if($data_registro){ echo date('d/m/Y', strtotime($data_registro)); } ?></td>
                                        <td> <?php // if($status == 0){ ?> <a class="btn btn-danger" href="<?= site_url('atendimento/deletar_servico_prestador/'.$esp->id.'/'.$dados->id); ?>" >Excluir</a> <?php // } ?></td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                       <tr>
                                        <th>Id</th>
                                        <th>Serviço</th>
                                        <th>Valor</th>
                                        <th>Data Registro</th>
                                        <th>Opção</th>
                                    </tr>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        </div>
                       </div>
                       
                   </div>  
              </div>
              <!-- /.tab-pane -->
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
