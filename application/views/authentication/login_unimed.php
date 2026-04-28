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



<body style="background-image: url('https://sigplus.app.br/bak.png');
            -webkit-background-size:cover;
            -moz-background-size:cover;
            -o-background-size:cover;
          
            background-position:center; 
            width: 200px;
            background-size: 100% auto;
            background-position: center top;
            background-attachment: fixed;
            
            background-repeat: no-repeat;" class="login_admin"<?php if(is_rtl()){ echo ' dir="rtl"'; } ?>>
 <div class="container">
  <div class="row">
      
   <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 authentication-form-wrapper">
       <h3><font style="color: #ffffff"><?php echo 'Seja bem vindo!!!'; ?></font></h3>
    <div class="mtop40 authentication-form">
        <div class="company-logo">
      <img src="https://sigplus.app.br/uploads/company/070c13f95e1cfa979ed59af58f35cfc2.png" >
    </div>
        
      <h1><?php echo 'Login'; ?></h1>
      <?php if($_GET['FAIL']){?>
      <div class="text-center alert alert-danger">
      Usuário e/ou senha incorretos.     
      </div>
      <?php }?>
      
      <?php $this->load->view('authentication/includes/alerts'); ?>
      <?php echo form_open($this->uri->uri_string()); ?>
      <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
      <?php hooks()->do_action('after_admin_login_form_start'); ?>
      <div class="form-group">
        <label for="email" class="control-label"><?php echo 'Usuário'; ?></label>
        <input type="text" id="email" name="email"  class="form-control" autofocus="1">
      </div>
      <div class="form-group">
        <label for="password" class="control-label"><?php echo _l('admin_auth_login_password'); ?></label>
        <input type="password" id="password" name="password" class="form-control"></div>
        <?php if(show_recaptcha()){ ?>
        <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
        <?php } ?>
        <div class="checkbox">
          <label for="remember">
           <input type="checkbox" id="remember" name="remember"> <?php echo _l('admin_auth_login_remember_me'); ?>
         </label>
       </div>
       <div class="form-group">
        <button type="submit" class="btn btn-info btn-block"><?php echo _l('admin_auth_login_button'); ?></button>
      </div>
      
        <div class="form-group">
        
      </div>

      <?php hooks()->do_action('before_admin_login_form_close'); ?>
      <?php echo form_close(); ?>


      
    </div>
          
    <div class="btn-group">
    
               
    <?php foreach ($registro_categorias as $registro) { ?>
        
          <a class="btn btn-default" target="_blanck" href="https://sigplus.app.br/Registro_ocorrencia/index/<?php echo $registro['hash']; ?>"><?php echo $registro['titulo']; ?></a>
        
        <?php } ?>
        
</div>


            
        </div>


  

</div>
</div>
</body>
 <!-- <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
      <strong>Copyright &copy; 2015-<?php echo date('Y'); ?>  <a href="https://sigplus.online"><img width="150px" height="60px" src="https://vision.sigplus.site/LOGO_SIGPLUS.png"></a> .</strong> All rights
    reserved.
  </footer> -->


</html>
