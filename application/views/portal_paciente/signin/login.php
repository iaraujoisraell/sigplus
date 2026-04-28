<!DOCTYPE html>
<html lang="en">
<?php //$this->load->view('includes/head'); ?>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url();?>assets/portal/img/apple-icon.png">
  <link rel="icon" type="image/png" href="<?php echo base_url();?>assets/portal/images/favicon_v.png">
  <title>
    V-social
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="<?php echo base_url();?>assets/portal/css/nucleo-icons.css" rel="stylesheet" />
  <link href="<?php echo base_url();?>assets/portal/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="<?php echo base_url();?>assets/portal/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
</head>

<body class="">
  
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('<?php echo base_url();?>assets/portal/img/illustrations/illustration-signup.jpg'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                <div class="app-auth-branding mb-4"><img class="logo-icon me-2" src="<?php echo base_url();?>assets/portal/portal/assets/images/vsocial.png" alt="logo"></div>
              <div class="card card-plain">
                <div class="card-header">
                    <h4 class="font-weight-bolder">Olá <b><?php echo $info_perfil_user->company; ?></b><br> Bem-vindo ao seu Portal!</h4>
                    
                </div>
                <div class="card-body">
                     <?php if ($msg) { ?>
                        <div class="alert alert-danger" style="color: white;" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <?php echo $msg;?>
                        </div>
                    <?php } ?>
                  <?php echo form_open(('portal/signin/login/' . $info_perfil_user->hash_client), array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>
                    <div class="input-group input-group-outline mb-3">
                      <label class="sr-only" for="signin-email">Email</label>
                      <input id="email" name="email" type="email" value="<?php echo $info_perfil_user->email;?>" readonly="true" class="form-control signin-email" placeholder="Email" required="required">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="sr-only" for="signin-password">Senha</label>
                      <input id="password" name="password" type="password" class="form-control signin-password" placeholder="Senha" required="required" minlength="6">
                    </div>
                    <div class="form-check form-check-info text-start ps-0" >
                      <input class="form-check-input " style="background: #00BFFF" type="checkbox" value="" id="flexCheckDefault" checked>
                      <label class="form-check-label" for="flexCheckDefault">
                        Lembrar de mim
                      </label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-lg btn-lg w-100 mt-4 mb-0 text-white" style="background: #00BFFF">Entrar</button>
                          <br><br>
                          <a style="color: #ffffff;" href="<?php echo base_url();?>portal/Signin/index" class="btn btn-warning w-100 theme-btn mx-autow-100 theme-btn mx-auto">VOLTAR</a>
                                                             
                    </div>
                  <?php echo form_close(); ?>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Esqueceu sua senha?
                    <a href="<?php echo base_url('portal/signin/redefinir_senha')?>" class=" text-black font-weight-bold link" style="color: #00BFFF">Clique aqui</a>
                  </p>
                  <p class="mb-0">Qualquer dúvida entre em contato com seu consultor!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="<?php echo base_url();?>assets/portal/js/core/popper.min.js"></script>
  <script src="<?php echo base_url();?>assets/portal/js/core/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?php echo base_url();?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?php echo base_url();?>assets/portal/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>