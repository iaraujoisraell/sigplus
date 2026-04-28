<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$url = $_SERVER[HTTP_HOST]; 
$dominio = str_replace('.sigplus.app.br', '', $url);
//$dominio = 'sigplus';

$this->load->model('Company_model');

$row = $this->Company_model->get_company($dominio);

$logo = get_company_option($dominio, 'company_logo');
$icon = get_company_option($dominio, 'favicon');
$company = get_company_option($dominio, 'companyname');
$portal_message = get_company_option($dominio, 'portal_message');
$portal_image = get_company_option($dominio, 'portal_image');
?>

<?php if (isset($row)) { ?>
    <?php if (!$hash) { ?>
        <html lang="en"> 
            <head>
                <title><?php echo $company; ?></title>

                <!-- Meta -->
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <meta name="description" content="Portal - Vsocial">
                <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
                <link rel="icon" type="image/png" href="<?php echo base_url(); ?>uploads/company/<?php echo $icon; ?>">

                <link rel="manifest" href="<?php echo base_url(); ?>manifest.json">
                <script defer="defer" src="<?php echo base_url(); ?>main.js"></script>

                <!-- FontAwesome JS-->
                <script defer src="<?php echo base_url(); ?>assets/portal/plugins/fontawesome/js/all.min.js"></script>

                <!-- App CSS -->  
                <link id="theme-style" rel="stylesheet" href="<?php echo base_url(); ?>assets/portal/css/portal.css">


            </head> 
            <?php
//registro de seguranca
            ?> 
            <body class="app app-login p-0">    	
                <div class="row g-0 app-auth-wrapper">
                    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center">
                        <div class="" style="text-align: left; margin-left: 3px;">
                            <div class="form-check form-switch ">
                                <input class="form-check-input" type="checkbox" id="check_change" onchange="master(this);">
                                <label class="form-check-label" for="check_change">Acesso Master</label>
                                
                            </div>

                        </div>
                        <div class="d-flex flex-column align-content-end  p-5">

                            <div class="app-auth-body mx-auto my-auto">	

                                <div class="app-auth-branding mb-3 mt-3"><a  href="index.php"><img class="logo-icon me-1" style="max-width: 100%;" style="max-width: 100%;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a></div>
                                <h1 class=" text-center mb-3">Seja bem vindo!!</h1>
                                <br>
                                <h2 class="auth-heading text-center mb-5">Informe o número da carteirinha para iniciar.</h2>
                                <?php if ($_GET['error'] == 2) { ?>
                                    <div class="alert alert-danger" role="alert">
                                       
                                        Falha de acesso.
                                    </div>
                                <?php } ?>
                                <?php if ($_GET['error'] == 1) { ?>
                                    <div class="alert alert-danger" role="alert">
                                       
                                        Registro não encontrado ou inativo!
                                    </div>
                                <?php } ?>


                                <div class="auth-form-container text-start">
                                    <?php echo form_open("portal/signin/valida", array("id" => "reset-password-form", "class" => "general-form", "role" => "form", "autocomplete" => "on")); ?>  


                                    <input type="hidden" name="company" value="<?php echo $dominio; ?>">
                                    <input type="hidden" name="type_login" id="type_login" >
                                    <div class="row mb-3">
                                        <div class="col-md-12" id="div_client">
                                            <input style="height: 50px; font-size: 20px;" id="carteirinha" name="carteirinha" type="text" maxlength="20"  class="form-control" placeholder="INFORME A CARTEIRINHA" required="required" >
                                        </div>

                                    </div>



                                    <div class="text-center">
                                        <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">CONFIRMAR</button>
                                        <br><br>

                                    </div>
                                    <?php echo form_close(); ?>


                                </div><!--//auth-form-container-->	

                            </div>
                            <footer class="app-auth-footer">

                                <div class="container text-center py-3">

                                    <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
                                    <small class="copyright">Desenvolvido com <i class="fas fa-heart" style="color: #fb866a;"></i> por <a class="app-link" href="" target="_blank">Sigplus / <?php echo $company; ?></a> </small>

                                </div>
                            </footer><!--//app-auth-footer-->	
                        </div><!--//flex-column-->   
                    </div>
                    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
                        <div class="auth-background-holder" style="">
                        </div>
                        <!--<div class="auth-background-mask"></div>-->
                        <!--<div class="auth-background-overlay p-3 p-lg-5">
                            <div class="d-flex flex-column align-content-end h-100">
                                <div class="h-100"></div>
                                <div class="overlay-content p-3 p-lg-4 rounded">
                                    <h5 class="mb-3 overlay-title"><a href=""><?php echo $portal_message; ?></a></h5>
                                    <div></div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div><!--//row-->
            </body>
            <script>
                function master(check_change) {
                    if (check_change.checked == true) {
                        document.getElementById('type_login').value = '1';

                    } else {
                        document.getElementById('type_login').value = '';
                    }

                }
            </script>

        </html>

    <?php } else { ?>
        <html lang="en"> 
            <head>
                <title><?php echo $company; ?></title>

                <!-- Meta -->
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <meta name="description" content="Portal - Vsocial">
                <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
                <link rel="icon" type="image/png" href="<?php echo base_url(); ?>uploads/company/<?php echo $icon; ?>">

                <link rel="manifest" href="<?php echo base_url(); ?>manifest.json">
                <script defer="defer" src="<?php echo base_url(); ?>main.js"></script>

                <!-- FontAwesome JS-->
                <script defer src="<?php echo base_url(); ?>assets/portal/plugins/fontawesome/js/all.min.js"></script>

                <!-- App CSS -->  
                <link id="theme-style" rel="stylesheet" href="<?php echo base_url(); ?>assets/portal/css/portal.css">

            </head> 
            <?php
//registro de seguranca
            ?> 
            <body class="app app-login p-0">    	
                <div class="row g-0 app-auth-wrapper">
                    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
                        <div class="d-flex flex-column align-content-end">

                            <?php if ($_GET['master'] == 1) { ?>
                                <div class="app-auth-body mx-auto">	
                                    <div class="app-auth-branding  mt-3">
                                        <a  href="index.php"><img class="logo-icon me-1" style="max-width: 100%;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a>
                                    </div>
                                    <h2 class=" text-center mb-1 mt-3">ACESSO MASTER</h2>
                                    <br>
                                    <h4 class="auth-heading text-center mb-2">Você solicitou acesso por <?php
                                        ;
                                        echo $client->company;
                                        ?>. </h4>



                                    <div class="alert alert-info" role="alert">
                                        Por favor, Informe as seguintes informações
                                    </div>



                                    <div class="auth-form-container text-start">
                                        <?php echo form_open(('portal/signin/login/' . $hash), array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>


                                        <input type="hidden" name="company" value="<?php echo $dominio; ?>">
                                        <div class="input-group input-group-outline mb-1 mb-3">
                                            <input id="user" name="user" type="text" class="form-control" placeholder="INFORME USUÁRIO" required="required" >
                                        </div>
                                        <div class="input-group input-group-outline mb-1 mb-3">
                                            <input id="password" name="password" type="password" class="form-control" placeholder="INFORME SENHA" required="required" >

                                        </div>
                                        <?php if ($_GET['error'] == 1) { ?>

                                            <span class="alert-text text-danger mb-3">

                                                FALHA NO LOGIN! VERIFIQUE USUÁRIO E SENHA.

                                            </span>


                                        <?php } ?>



                                        <div class="text-center mb-0">
                                            <button type="submit" class="btn btn-primary w-100 theme-btn mx-auto text-white">CONFIRMAR</button>

                                          
                                        </div>
                                        
                                        <?php echo form_close(); ?>
                                        <div class="text-center">

                                            <a href="<?php echo base_url(); ?>Authentication/login" class="btn btn-warning w-100 theme-btn mx-auto text-white" style="margin-top: 10px;">VOLTAR</a>

                                        </div>


                                    </div><!--//auth-form-container-->	

                                </div><!--//auth-body-->
                            <?php } else { ?>
                                <div class="app-auth-body mx-auto">	
                                    <div class="app-auth-branding mb-3  mt-3 ">
                                        <a  href="index.php"><img class="logo-icon me-1" style="max-width: 100%;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a>
                                    </div>
                                    <h4 class="font-weight-bolder"> </h4>
                                    <h1 class=" text-center mb-1">Seja bem vindo, <?php
                                        $nome = explode(' ', $client->company);
                                        echo $nome[0];
                                        ?>!</h1>
                                    <br>
                                    <h2 class="auth-heading text-center mb-2">Insira o Token para iniciar.</h2>


                                    <?php if ($msg) { ?>
                                        <div class="alert alert-info" role="alert">
                                            <?php echo $msg; ?>
                                        </div>
                                    <?php } ?>

                                    <div class="auth-form-container text-start">
                                        <?php echo form_open(('portal/signin/login/' . $hash), array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>


                                        <input type="hidden" name="company" value="<?php echo $dominio; ?>">
                                        <?php if ($_GET['error'] == 1) { ?>

                                            <span class="alert-text text-danger mb-3">

                                                TOKEN INCORRETO. TENTE NOVAMENTE!

                                            </span>


                                        <?php } ?>
                                        <div class="input-group input-group-outline mb-1 mb-3">
                                            <label class="sr-only">Token</label>
                                            <input id="token" name="token" type="password" class="form-control" placeholder="Token" required="required" maxlength="6" minlength="6">

                                        </div><!--//form-group-->



                                        <div class="text-center mb-0">
                                            <button type="submit" class="btn btn-primary w-100 theme-btn mx-auto text-white">CONFIRMAR</button>

                                          
                                        </div>
                                        
                                        <?php echo form_close(); ?>
                                        <div class="text-center">

                                            <a href="<?php echo base_url(); ?>Authentication/login" class="btn btn-warning w-100 theme-btn mx-auto text-white" style="margin-top: 10px;">VOLTAR</a>

                                        </div>


                                    </div><!--//auth-form-container-->	

                                </div><!--//auth-body-->
                            <?php } ?>
                            <footer class="app-auth-footer">
                                <div class="container text-center py-3">
                                    <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
                                    <small class="copyright">Desenvolvido com <i class="fas fa-heart" style="color: #fb866a;"></i> por <a class="app-link" href="" target="_blank">Sigplus / <?php echo $company; ?></a> </small>

                                </div>
                            </footer><!--//app-auth-footer-->	
                        </div><!--//flex-column-->   
                    </div>
                    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
                        <div class="auth-background-holder" style="">
                        </div>
                        <!--<div class="auth-background-mask"></div>-->
                        <!--<div class="auth-background-overlay p-3 p-lg-5">
                            <div class="d-flex flex-column align-content-end h-100">
                                <div class="h-100"></div>
                                <div class="overlay-content p-3 p-lg-4 rounded">
                                    <h5 class="mb-3 overlay-title"><a href=""><?php echo $portal_message; ?></a></h5>
                                    <div></div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div><!--//row-->
            </body>
        </html> 
    <?php } ?>
<?php } else { ?>
    <?php
    echo theme_head_view();
    get_template_part($navigationEnabled ? 'navigation' : '');
    ?>
    <div id="wrapper">
        <div id="content">
            <div class="container">
                <div class="row">
                    <?php get_template_part('alerts'); ?>
                </div>
            </div>
            <?php if (isset($knowledge_base_search)) { ?>
                <?php get_template_part('knowledge_base/search'); ?>
            <?php } ?>
            <div class="container">
                <?php hooks()->do_action('customers_content_container_start'); ?>
                <div class="row">
                    <?php
                    /**
                     * Don't show calendar for invoices, estimates, proposals etc.. views where no navigation is included or in kb area
                     */
                    if (is_client_logged_in() && $subMenuEnabled && !isset($knowledge_base_search)) {
                        ?>
                        <ul class="submenu customer-top-submenu">
                            <?php hooks()->do_action('before_customers_area_sub_menu_start'); ?>
                            <li class="customers-top-submenu-files"><a href="<?php echo site_url('clients/files'); ?>"><i class="fa fa-file" aria-hidden="true"></i> <?php echo _l('customer_profile_files'); ?></a></li>
                            <li class="customers-top-submenu-calendar"><a href="<?php echo site_url('clients/calendar'); ?>"><i class="fa fa-calendar-minus-o" aria-hidden="true"></i> <?php echo _l('calendar'); ?></a></li>
                            <?php hooks()->do_action('after_customers_area_sub_menu_end'); ?>
                        </ul>
                        <div class="clearfix"></div>
                    <?php } ?>
                    <?php echo theme_template_view(); ?>
                </div>
            </div>
        </div>
        <?php
        echo theme_footer_view();
        ?>
    </div>
    <?php
    /* Always have app_customers_footer() just before the closing </body>  */
    app_customers_footer();
    /**
     * Check for any alerts stored in session
     */
    app_js_alerts();
    ?>
    </body>
    </html>
<?php } ?>
