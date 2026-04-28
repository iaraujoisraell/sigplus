<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- MENU TOPO -->
<?php 
$this->load->view('gestao_corporativa/intranet/head.php'); ?>

<body class="hold-transition <?php echo $layout; ?> "> 

    <div class="wrapper">

  <!-- Navbar -->
  <?php if ($exibe_menu_topo){ ?>
    <?php $this->load->view('gestao_corporativa/intranet/navbar.php'); ?>
    <?php } ?>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"> Lista de Formulários <small></small></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url('admin'); ?>">Home</a></li>
              <li class="breadcrumb-item active">Formulários</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        
            <div class="row">
            <div class="col-12">     
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Lista de Formulários</h3>
                <a class="float-right btn btn-primary" href="<?= base_url('gestao_corporativa/Formularios/criar_formulario');?>"><i class="fa fa-plus"></i> NOVO FORMULÁRIO </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Título</th>
                    <th>Formulário</th>
                    <th>Qtde Respostas</th>
                    <th>Link web</th>
                    <th>Criado por</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($formularios as $form){
                      $key = $form->form_key;
                       $total_respostas = $this->Formulario_model->get_total_resposta_formulario_by_key($key);
                       $qtde_resp = $total_respostas->total;
                      ?>
                  <tr>
                    <td><?php echo $form->titulo; ?>
                        <br> 
                        <?php echo $form->descricao; ?>
                    </td>
                    
                    <td>
                        
                        <a class="btn btn-warning"  href="<?= base_url('gestao_corporativa/Formularios/abrir_formulario/'.$key);?>"><i class="fa fa-edit"></i> ABRIR </a></td>
                    <td> <?php echo $qtde_resp; ?></td>
                    <td><a class="btn btn-success" target="_blank" href="<?php echo base_url('formularios/web/'.$key); ?>"><i class="fa fa-eye"></i>  </a></td>
                    <td><?php echo $form->firstname.' '.$form->lastname.'<br>'._d($form->data_created); ?> </td>
                  </tr>
                  <?php } ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Título</th>
                    <th>Formulário</th>
                    <th>Qtde Respostas</th>
                    <th>Link web</th>
                    <th>Criado por</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
          <!-- /.col-md-6 -->
        </div>
        </div><!-- comment -->   
      
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    
    
    <!-- Main Footer -->
  <?php $this->load->view('gestao_corporativa/intranet/footer.php'); ?>
    <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  </div>
  <!-- /.content-wrapper -->

  
  <!-- /.control-sidebar -->

  
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



  <!-- DataTables  & Plugins -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>assets/lte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>


 
<!-- Select2 -->
<script src="<?php echo base_url(); ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>