<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $locale; ?>">
    <head>
        <?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />

        <title><?php echo isset($title) ? $title : get_option('companyname'); ?></title>

        <?php echo app_compile_css(); ?>
        <?php render_admin_js_variables(); ?>

        
        <?php app_admin_head(); ?>


        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <link rel="stylesheet" href="<?php echo base_url();?>assets/lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

        <link rel="stylesheet" href="<?php echo base_url();?>assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

        <link rel="stylesheet" href="<?php echo base_url();?>assets/lte/plugins/jqvmap/jqvmap.min.css">

        <link rel="stylesheet" href="<?php echo base_url();?>assets/lte/dist/css/adminlte.min.css?v=3.2.0">

        <link rel="stylesheet" href="<?php echo base_url();?>assets/lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

        <link rel="stylesheet" href="<?php echo base_url();?>assets/lte/plugins/daterangepicker/daterangepicker.css">

        <link rel="stylesheet" href="<?php echo base_url();?>assets/lte/plugins/summernote/summernote-bs4.min.css">
        <!-- FIM LTE-->
    </head>


