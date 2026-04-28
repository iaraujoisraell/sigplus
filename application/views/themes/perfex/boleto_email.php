<?php //echo "$hash"; exit;
//$url = $_SERVER[HTTP_HOST]; 
//$dominio = str_replace('.sigplus.app.br', '', $url);
$dominio = 'unimedmanaus';

$this->load->model('Company_model');

$row = $this->Company_model->get_company($dominio);



$logo = get_company_option($dominio, 'company_logo');
$company = get_company_option($dominio, 'companyname');
$portal_message = get_company_option($dominio, 'portal_message');
$portal_image = get_company_option($dominio, 'portal_image');
?>
<!DOCTYPE html>
<html lang="en">


    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/portal/img/apple-icon.png">
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>">
        <title>
            <?php echo $category['p_title']; ?>
        </title>


        <link rel="canonical" href="https://www.creative-tim.com/product/material-dashboard-pro" />


        <link href="<?php echo base_url(); ?>assets/portal/css/nucleo-icons.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>assets/portal/css/nucleo-svg.css" rel="stylesheet" />



        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

        <link id="pagestyle" href="<?php echo base_url(); ?>assets/portal/css/material-dashboard.min.css?v=3.0.6" rel="stylesheet" />
    </head>

    <body class>
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">

                    <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                        <div class="container-fluid ps-2 pe-0">
                            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../../../pages/dashboards/analytics.html">
                                <!--<img class="logo-icon me-1" style="max-height: 60px;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"><!--><?php echo $company; ?>
                            </a>
                            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon mt-2">
                                    <span class="navbar-toggler-bar bar1"></span>
                                    <span class="navbar-toggler-bar bar2"></span>
                                    <span class="navbar-toggler-bar bar3"></span>
                                </span>
                            </button>
                            <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0" id="navigation">
                                <ul class="navbar-nav navbar-nav-hover mx-auto">
                                    <?php
                                    
                                  //  print_r($result); exit;
                                    
                                    foreach ($result as $cat) { ?>
                                        <li class="nav-item dropdown dropdown-hover mx-2">
                                            <a href="<?php echo base_url('authentication/login/') . $cat['id']; ?>" class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" id="dropdownMenuDocs" aria-expanded="false">
                                                <?php echo $cat['p_title']; ?>
                                                <?php //echo "aqui"; ?>
                                               <!-- <img src="<?php echo base_url(); ?>assets/portal/img/down-arrow-dark.svg" alt="down-arrow" class="arrow ms-1 d-lg-block d-none">
                                                <img src="<?php echo base_url(); ?>assets/portal/img/down-arrow-dark.svg" alt="down-arrow" class="arrow ms-1 d-lg-none d-block"> -->
                                            </a>
                                           <!-- <div class="dropdown-menu dropdown-menu-animation dropdown-lg mt-0 mt-lg-3 p-3 border-radius-lg" aria-labelledby="dropdownMenuDocs">
                                                <div class="d-none d-lg-block">

                                                    <span class="text-sm opacity-8"><?php echo $cat['p_description']; ?></span>
                                                </div>
                                                <div class="d-lg-none">
                                                    <div class="col-md-12 text-dark">
                                                        <span class="text-sm "><?php echo $cat['p_description']; ?></span>
                                                    </div>
                                                </div>
                                            </div>-->
                                        </li>
                                    <?php } ?>
               
                                </ul>

                            </div>
                        </div>
                    </nav>

                </div>
            </div>
        </div>
        <main class="main-content  mt-0">
            <section>
                <div class="page-header min-vh-100">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column me-auto ms-auto me-lg-auto ms-lg-5">
                                <div class="card card-plain">

                               
                                        <div class="card-header text-center">
                                            <div class="app-auth-branding mb-3 "><a href=""><img class="logo-icon me-1" style="max-height: 60px;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a></div>
                                            <h4 class="font-weight-bolder"><?php echo $category['p_salutation']; ?></h4>
                                            <p class="mb-0"><?php echo $category['p_msg']; ?></p>
                                        </div>
                                        <div class="card-body">

                                        
                                            



                                            <?php echo form_open("authentication/login_cpf_boleto/$cpf_codificado/$titulo", array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); //echo 'aqui'; ?>
                                            
                                            
                                            <input name="cpf" value="<?php echo $cpf_codificado; ?>" type="hidden"><!-- comment -->
                                            <input name="titulo" value="<?php echo $titulo; ?>" type="hidden"> 
                                            <div class="col-sm-<?php echo $campo['tam_coluna']; ?> mt-2">
                                           
                                           <div class="input-group input-group-outline ">
                                                <input required="true" name="cpf" id="cpf"  class=" form-control" type="number" placeholder="INFORME SEU CPF" maxlength="11" minlength="11"/>
                                            </div>
                                        </div>
                                            <?php if ($error == 2) { ?>
                                                <p style="color: red; font-size: 10px;">
                                                    Falha de acesso.
                                                </p>
                                            <?php } ?>
                                            <?php if ($error == 1) { ?>
                                                <p style="color: red; font-size: 10px;">
                                                    O CPF Informado não é o mesmo do Email, Informe o CPF correto!
                                                </p>
                                            <?php } ?>


                                            <div class="text-center">
                                                <button type="submit" class="btn btn-xs bg-gradient-success w-100 mt-4 mb-0">ACESSAR MEU BOLETO</button>
                                                
                                            </div>

                                            <?php echo form_close(); ?>


                                          
                                        </div>
                                   

                                </div>
                            </div>
                            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                                <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('<?php if ($category['id'] == '272') {
                                                                                                                                                                                                echo 'https://unimedmanaus.sigplus.app.br/assets/portal/images/background/background-2.png';
                                                                                                                                                                                            } else {
                                                                                                                                                                                                echo $portal_image;
                                                                                                                                                                                            } ?>'); background-size: cover;"></div>
                            </div>
                        </div>
                    </div
                        </div>
            </section>
        </main>

        <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.0"></script>
        <script>
            function master(check_change) {
                if (check_change.checked == true) {
                    document.getElementById('type_login').value = '1';

                } else {
                    document.getElementById('type_login').value = '';
                }                

            }
            function rapido(check_change) {
                if (check_change.checked == true) {
                    document.getElementById('type_login').value = '2';

                } else {
                    document.getElementById('type_login').value = '';
                }

            }

            function enviarComVariavel() {
            // Adiciona uma variável ao formulário antes de enviar
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'type_login';
            input.value = '2';
            document.getElementById('reset-password-form').appendChild(input);
            
            // Envia o formulário
            document.getElementById('reset-password-form').submit();
        }
        </script>
    </body>



</html>