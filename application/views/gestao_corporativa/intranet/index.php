
<!DOCTYPE html>

<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet(false); ?>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">


        <?php $this->load->view('gestao_corporativa/intranet/navbar.php'); ?>   

        <div class="content-wrapper">

            <?php $this->load->view('gestao_corporativa/intranet/' . $content . '.php'); ?>   

        </div>

        <?php
        $data['current_ser'] = $current_ser;
        $this->load->view('gestao_corporativa/intranet/control_sidebar.php', $data);
        ?> 
        <?php $this->load->view('gestao_corporativa/intranet/footer.php'); ?>   



    </div>


    <?php init_tail_intranet(); ?>


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



    <!-- Summernote -->
    <script src="<?php echo base_url(); ?>assets/lte/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- CodeMirror -->
    <script src="<?php echo base_url(); ?>assets/lte/plugins/codemirror/codemirror.js"></script>
    <script src="<?php echo base_url(); ?>assets/lte/plugins/codemirror/mode/css/css.js"></script>
    <script src="<?php echo base_url(); ?>assets/lte/plugins/codemirror/mode/xml/xml.js"></script>
    <script src="<?php echo base_url(); ?>assets/lte/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>

    <!-- Select2 -->
    <script src="<?php echo base_url(); ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function () {
            $("#lista_documentos").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false, "paging": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#lista_documentos_wrapper .col-md-6:eq(0)');
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
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            // Summernote
            $('#summernote').summernote()

            // CodeMirror
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                mode: "htmlmixed",
                theme: "monokai"
            });
        })
    </script>
</body>
</html>