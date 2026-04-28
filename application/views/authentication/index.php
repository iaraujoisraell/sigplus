<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $this->load->view('authentication/includes/head.php'); ?>

<style>
    footer {
    position: absolute;
    bottom: 0;
    background-color: blue;
    color: #FFF;
    width: 100%;
    height: 100px;    
    text-align: center;
    line-height: 100px;
}
</style>



<body style="background-image: url('https://vision.sigplus.site/fundo.png');
            -webkit-background-size:cover;
            -moz-background-size:cover;
            -o-background-size:cover;
          
            background-position:center; 
           
            background-size: 100% auto;
            background-position: center top;
            background-attachment: fixed;
            
            background-repeat: no-repeat;" class="login_admin"<?php if(is_rtl()){ echo ' dir="rtl"'; } ?>>
 <div class="container">
  <div class="row">
   <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 authentication-form-wrapper">
    
    <div class="mtop40 authentication-form">
        <div class="company-logo">
      <img width="150px" height="60px" src="https://vision.sigplus.site/LOGO_SIGPLUS.png">
    </div>
        
      <h1><?php //echo _l('admin_auth_login_heading'); ?></h1>
      <?php $this->load->view('authentication/includes/alerts'); ?>
      <?php echo form_open($this->uri->uri_string()); ?>
      <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
      <?php hooks()->do_action('after_admin_login_form_start'); ?>
      <div class="form-group">
        <label for="email" class="control-label"><?php echo 'Informe seu e-mail corporativo.'; ?></label>
        <input type="email" id="email" name="email" class="form-control" autofocus="1">
      </div>
      <div class="form-group">
        
        <?php if(show_recaptcha()){ ?>
        <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
        <?php } ?>
        <br>
       <div class="form-group">
        <button type="submit" class="btn btn-info btn-block btn-bg"><?php echo 'Validar e-mail'; ?></button>
      </div>
     

      <?php hooks()->do_action('before_admin_login_form_close'); ?>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
</div>
</body>
 <!-- <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
      <strong>Copyright &copy; 2015-<?php echo date('Y'); ?>  <a href="https://sigplus.online"><img width="150px" height="60px" src="https://vision.sigplus.site/LOGO_SIGPLUS.png"></a> .</strong> All rights 2021
    reserved.
  </footer> -->


</html>
