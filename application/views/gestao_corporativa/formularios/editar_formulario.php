<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet(false); ?>
<!-- MENU TOPO -->
<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

<body class="hold-transition <?php echo $layout; ?> "> 

    <div class="wrapper">
         
  <!-- Navbar -->
  <?php  if ($exibe_menu_topo){ ?>
    <?php $this->load->view('gestao_corporativa/intranet/navbar.php'); ?>
    <?php } ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
          <h1 class="m-0"> Editar Formulário <small></small></h1>
          
          </div>  
         </div>   
            <!-- /.col -->
     
          <div class="col-sm-12">
              
              <ol class="breadcrumb float-sm-left">
                
                  <li class="breadcrumb-item active"><a class="float-right btn btn-default" target="_blank" href="<?php echo base_url('formularios/web/'.$form->form_key); ?>" > <i class="fa fa-eye"></i> Visualizar </a></li>
              <li class="breadcrumb-item "><a class="float-right btn btn-default"  href="<?= base_url('gestao_corporativa/Formularios/criar_formulario_filho/'.$form->id);?>" > <i class="fa fa-bars "></i> Criar Sessão </a></li>
            </ol>
              
              <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('admin'); ?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url('gestao_corporativa/Formularios'); ?>">Formulários</a></li>
              <li class="breadcrumb-item active">Editar Formulário</li>
            </ol>
            
          </div><!-- /.col -->
        <!-- /.row -->
        
        <br><br>
      
      <section class="content">
      <div class="container-fluid">
          <div class="card card-warning">
          <?php
          $formularios_filhos = $this->Formulario_model->get_form_filhos($form->id);
          $count_filhos = count($formularios_filhos);
        
          if($count_filhos > 0){
              $total_Sessao = $count_filhos + 1;
          ?>
          
          <div class="card-header">
                  <h3 class="card-title"> <i class="fa fa-bars"></i>SESSÃO (1/ <?php echo $total_Sessao; ?>)</h3>

                  <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                      </button>
                  </div>
                  <!-- /.card-tools -->
              </div>
          
          <?php } ?>
          <div class="card-body">
              <div class="row">
                  <!-- /.col-md-6 -->
                  <div class="col-lg-12">

                      <div class="card card-primary card-outline">
                          <div style="background-color: lightgrey" class="card-header">
                              <div class="col-md-12">
                                  <input type="hidden" name="form_id" id="form_id" value="<?php echo $form->id; ?>">
                                  <input style="width: 100%;  " name="titulo" id="titulo" type="text"  value="<?php echo $form->titulo; ?>"  class="form-control">
                              </div>
                          </div>
                          <div class="card-body">
                              <h6 class="card-title">Descrição do Formulário</h6>
                              <br>
                              <textarea class="summernote" id="m_summernote_form" name="conteudo_textarea" >
                                  <?php echo $form->descricao; ?>
                              </textarea>
                              
                              <h6 class="card-title">Mensagem de Agradecimento</h6>
                              <br>
                              <textarea class="summernote" id="m_summernote_msg" name="conteudo_textarea" >
                                  <?php echo $form->success_submit_msg; ?>
                              </textarea>

                              <div class="col-lg-12">
                                  <button  onclick="atualizar_titulo(<?php echo $pergunta['id']; ?>);" class="btn btn-success">SALVAR</button>
                                  <a class="float-right btn btn-primary" onclick="add_perguntas()" ><i class="fa fa-plus"></i> NOVA PERGUNTA </a>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="col-lg-12">
                      <div id="lista_perguntas">

                      </div>
                  </div>    
                  <!-- /.fim pai -->
              </div>
          </div>
         </div>     
        
        <!-- formulário filhos a partir daqui -->
        <?php 
        if($count_filhos > 0){
        $cont_filho_1 = 2;
        foreach ($formularios_filhos as $filho){
        ?>
       
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"> <i class="fa fa-bars"></i>  SESSÃO (<?php echo $cont_filho_1; ?>/ <?php echo $total_Sessao; ?>)</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <!-- /.col-md-6 -->
                  <div class="col-lg-12">

                    <div class="card card-primary card-outline">
                      <div style="background-color: lightgrey" class="card-header">
                          <div class="col-md-12">
                              <input type="hidden" name="form_filho_id" id="form_filho_id" value="<?php echo $filho['id']; ?>">
                              <input style="width: 100%;  " name="titulo_<?php echo $filho['id']; ?>" id="titulo_<?php echo $filho['id']; ?>" type="text"  value="<?php echo $filho['titulo']; ?>"  class="form-control">
                          </div>
                      </div>
                      <div class="card-body">
                        <h6 class="card-title">Descrição do Formulário</h6>
                        <br>
                        <textarea class="summernote" id="m_summernote_form_<?php echo $filho['id']; ?>" name="conteudo_textarea_<?php echo $filho['id']; ?>" >
                        <?php echo $filho['descricao']; ?>
                        </textarea>

                        <div class="col-lg-12">
                            <button  onclick="atualizar_titulo_filho(<?php echo $filho['id']; ?>);" class="btn btn-success">Salvar</button>
                            <a class="float-right btn btn-primary" onclick="add_perguntas_filhos(<?php echo $filho['id']; ?>)" ><i class="fa fa-plus"></i> NOVA PERGUNTA </a>
                        </div>
                      </div>
                        <div class="card-footer">
                           
                        </div>
                    </div>
                  </div>



                   <div id="retorno_atualiza"></div>
                      <div class="col-lg-12">
                          <div id="lista_perguntas_<?php echo $filho['id']; ?>">

                          </div>
                      </div>    

                </div>
              </div>
              <!-- /.card-body -->
            </div>
        
            
            
        
        <?php $cont_filho_1++; } ?>
        
         <?php } ?>
        <!-- /.fim formulário filhos -->
      </div>
    </section>
      </div><!-- /.container-fluid -->
      
      
    </div>
    <!-- /.content-header -->
    <br>
    <!-- Main content -->
    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <?php $this->load->view('gestao_corporativa/intranet/footer.php'); ?>
</div>
    
    



<?php //init_tail_intranet(); ?>
   
    
    
<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/lte/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes 
<script src="<?php echo base_url(); ?>assets/lte/dist/js/demo.js"></script>
-->


<!-- Summernote -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/summernote/summernote-bs4.min.js"></script>
<!-- CodeMirror -->
 
<!-- Select2 -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>

<!-- Sparkline -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url(); ?>assets/lte/dist/js/pages/dashboard.js"></script>

<!-- Page specific script -->
<script>
  $(function () {
      
    lista_perguntas();
    
    <?php 
        foreach ($formularios_filhos as $filho){
    ?>
        lista_perguntas_filhos(<?php echo $filho['id']; ?>);
    <?php } ?>
  });
  
  
  function listar_perguntas(){
      lista_perguntas();
    
    <?php 
        foreach ($formularios_filhos as $filho){
    ?>
        lista_perguntas_filhos(<?php echo $filho['id']; ?>);
    <?php } ?>
      
  }
  
</script>
<script>
  $(function () {
        var SummernoteDemo={init:function(){$(".summernote").summernote({height:100})}};jQuery(document).ready(function(){SummernoteDemo.init()}); 
      //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    
    // Summernote
    

    // CodeMirror
    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
      mode: "htmlmixed",
      theme: "monokai"
    });
  });
  
  
    function add_perguntas() {
       //  alert('cdjnn'); exit;
         var form_id = document.getElementById('form_id');
         var form_id_value = form_id.value;
        
         $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/add_pergunta'); ?>",
             data: {
                 form_id: form_id_value
             },
             success: function (data) {
                 lista_perguntas();
             }
         });
        // lista_perguntas();
     }
     
    function lista_perguntas() {
     
     var form_id = document.getElementById('form_id');
         var form_id_value = form_id.value;
        
         $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/lista_perguntas'); ?>",
             data: {
                 form_id: form_id_value
             },
             success: function (data) {
                 $('#lista_perguntas').html(data);
             }
         });
    }
    
    
    /*
    * FILHOS
     */
     function add_perguntas_filhos(form_id_value) {
       //  alert('cdjnn'); exit;
        // var form_id = document.getElementById('form_filho_id');
         var form_id_value = form_id_value;
         
         $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/add_pergunta_filho'); ?>",
             data: {
                 form_id: form_id_value,
                 form_pai: <?php echo $form->id; ?>
             },
             success: function (data) {
                 lista_perguntas_filhos(form_id_value);
               //  $('#lista_perguntas_'+form_id_value).html(data);
             }
         });
         lista_perguntas();
     }
     
    function lista_perguntas_filhos(form_id_value) {
     
     
        
         $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/lista_perguntas'); ?>",
             data: {
                 form_id: form_id_value
             },
             success: function (data) {
                 $('#lista_perguntas_'+form_id_value).html(data);
             }
         });
    }
    /************************************************************************/
    
    function apagar_perguntas(form_id_value) {
       //  alert('cdjnn'); exit;
        // var form_id = document.getElementById('form_filho_id');
         var form_id_value = form_id_value;
         
         $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/deletar_pergunta'); ?>",
             data: {
                 pergunta_id: form_id_value,
                 form_pai: <?php echo $form->id; ?>
             },
             success: function (data) {
                 listar_perguntas();
               //  $('#lista_perguntas_'+form_id_value).html(data);
             }
         });
        // lista_perguntas();
     }
     
     /************************************************************************************/
    
     
    function atualizar_titulo() {
     
        var form_id = document.getElementById("form_id");
        var form_id_Valor = form_id.value;
        
        var titulo = document.getElementById("titulo");
        var titulo_Valor = titulo.value;
       
       var descricao = document.getElementById("m_summernote_form");
        var descricao_valor = descricao.value;
        
        var msg_obrigado = document.getElementById("m_summernote_msg");
        var msg_obrigado_valor = msg_obrigado.value;
        
        
        
      $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/atualiza_formulario'); ?>",
             data: {
                 form_id: form_id_Valor,
                 title: titulo_Valor,
                 descricao: descricao_valor,
                 msg_obrigado: msg_obrigado_valor
             },
             success: function (data) {
                 alert('Atualizado com sucesso!');
              //  $('#retorno_atualiza').html(data);
             }
         });
  }
  
  function atualizar_titulo_filho(form_id) {
     
        var titulo = document.getElementById("titulo_"+form_id);
        var titulo_Valor = titulo.value;
       
       var descricao = document.getElementById("m_summernote_form_"+form_id);
        var descricao_valor = descricao.value;
        
        
      $.ajax({
             type: "POST",
             url: "<?php echo base_url('gestao_corporativa/Formularios/atualiza_formulario'); ?>",
             data: {
                 form_id: form_id,
                 title: titulo_Valor,
                 descricao: descricao_valor
             },
             success: function (data) {
                 alert('Atualizado com sucesso!');
                $('#retorno_atualiza').html(data);
             }
         });
  }
</script>
</body>
</html> 