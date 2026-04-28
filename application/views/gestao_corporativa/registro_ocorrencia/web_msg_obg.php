<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo; ?></title>

        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">


        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/dist/css/adminlte.min.css?v=3.2.0">

    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">


        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="<?php echo  base_url('uploads/company/' . $logo); ?>" alt="AdminLTELogo" height="80" width="80">
            <h4><?php echo $msg;?></h4><br><!-- comment -->
            <h5><?php echo $submsg;?></h5>
        </div>

    </body>

</html>
<?php exit; ?>
