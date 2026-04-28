
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Intranet - Lista de noticias</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/fontawesome-free/css/all.min.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/lte/dist/css/adminlte.min.css?v=3.2.0">
        <script nonce="d3c892a7-1a34-43ec-a3cb-0f690c23872a">(function(w, d){!function(a, e, t, r){a.zarazData = a.zarazData || {}; a.zarazData.executed = []; a.zaraz = {deferred:[], listeners:[]}; a.zaraz.q = []; a.zaraz._f = function(e){return function(){var t = Array.prototype.slice.call(arguments); a.zaraz.q.push({m:e, a:t})}}; for (const e of["track", "set", "ecommerce", "debug"])a.zaraz[e] = a.zaraz._f(e); a.zaraz.init = () => {var t = e.getElementsByTagName(r)[0], z = e.createElement(r), n = e.getElementsByTagName("title")[0]; n && (a.zarazData.t = e.getElementsByTagName("title")[0].text); a.zarazData.x = Math.random(); a.zarazData.w = a.screen.width; a.zarazData.h = a.screen.height; a.zarazData.j = a.innerHeight; a.zarazData.e = a.innerWidth; a.zarazData.l = a.location.href; a.zarazData.r = e.referrer; a.zarazData.k = a.screen.colorDepth; a.zarazData.n = e.characterSet; a.zarazData.o = (new Date).getTimezoneOffset(); a.zarazData.q = []; for (; a.zaraz.q.length; ){const e = a.zaraz.q.shift(); a.zarazData.q.push(e)}z.defer = !0; for (const e of[localStorage, sessionStorage])Object.keys(e || {}).filter((a => a.startsWith("_zaraz_"))).forEach((t => {try{a.zarazData["z_" + t.slice(7)] = JSON.parse(e.getItem(t))} catch {a.zarazData["z_" + t.slice(7)] = e.getItem(t)}})); z.referrerPolicy = "origin"; z.src = "/cdn-cgi/zaraz/s.js?z=" + btoa(encodeURIComponent(JSON.stringify(a.zarazData))); t.parentNode.insertBefore(z, t)}; ["complete", "interactive"].includes(e.readyState)?zaraz.init():a.addEventListener("DOMContentLoaded", zaraz.init)}(w, d, 0, "script"); })(window, document);</script></head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">


            <?php $this->load->view('gestao_corporativa/intranet/navbar.php'); ?>



            <?php $this->load->view('gestao_corporativa/intranet/asidebar.php'); ?>

            <div class="content-wrapper">
                <div class="row" style="padding: 10px;">
                    <div class="col-md-12">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Titulo:</th>
                                    <th>Data de publicação:</th>
                                    <th>Disponível até:</th>
                                    <th>Publicado por:</th>
                                    <th>Visualizar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                                date_default_timezone_set('America/Sao_Paulo');
                                foreach ($noticias as $noticia) {
                                    ?>
                                    <tr>
                                        <td><?php echo $noticia['titulo']; ?></td>
                                        <td><?php echo strftime('%d de %B de %Y', strtotime($noticia['data_cadastro']));?></td>
                                        <td><?php echo strftime('%d de %B de %Y', strtotime($noticia['fim']));?></td>
                                        <td><?php echo $noticia['firstname'] . ' ' . $noticia['lastname']; ?></td>
                                        <td><a type="button" target="blank"  href="<?php echo base_url('gestao_corporativa/intra/Pubs/ver_aviso/'.'?id=' . $noticia['id'])?>" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> Visualizar</a></td>
                                    </tr>
<?php } ?>

                            </tbody>
                            <tfoot>
                        </table>
                        <!-- /.card -->
                    </div>
                    <!-- FOOTER -->


                    <!-- Control Sidebar -->
<?php //$this->load->view('gestao_corporativa/intranet/control_sidebar.php');   ?>
                    <!-- /.control-sidebar -->
                </div>




            </div>

<?php $this->load->view('gestao_corporativa/intranet/footer.php'); ?>


        </div>


        <script src="<?php echo base_url(); ?>assets/lte/plugins/jquery/jquery.min.js"></script>

        <script src="<?php echo base_url(); ?>assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

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

        <script src="<?php echo base_url(); ?>assets/lte/dist/js/adminlte.min.js?v=3.2.0"></script>

        <script src="<?php echo base_url(); ?>assets/lte/dist/js/demo.js"></script>

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
<?php //init_tail();         ?>
    </body>
</html>
