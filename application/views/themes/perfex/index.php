<?php //echo "$hash"; exit;
//$url = $_SERVER[HTTP_HOST]; 
//$dominio = str_replace('.sigplus.app.br', '', $url);
$dominio = 'unimedmanaus';

$this->load->model('Company_model');

$row = $this->Company_model->get_company($dominio);

$this->load->model('Categorias_campos_model');

$result = $this->Categorias_campos_model->get_categorias('atendimento', false, $row->empresa_id, true);

if (!$category) {
    if (is_array($result) and !empty($result)) {
        $category = $result[0];
        unset($result[0]);
    }
} else {
    foreach ($result as $key => $value) {
        if ($value['id'] == $category['id']) {
            unset($result[$key]);
            break;
        }
    }
}


$logo = get_company_option($dominio, 'company_logo');
$company = get_company_option($dominio, 'companyname');
$portal_message = get_company_option($dominio, 'portal_message');
$portal_image = get_company_option($dominio, 'portal_image');
?>
<!DOCTYPE html>
<html lang="en">
<?php if ($category and $category['apicategoria_id']) {
?>

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

                                    <?php if ($_GET['master'] == 1) { ?>
                                        <div class="card-header text-center">
                                            <div class="app-auth-branding mb-3 "><a href=""><img style="max-height: 60px;" class="logo-icon me-1" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a></div>
                                            <h4 class=" text-center mb-1 mt-3">ACESSO MASTER</h4>
                                            <p class="mb-0">Você solicitou acesso por <span class="alert-text text-success"><?php echo $client->company; ?></span>. </p>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info" role="alert">
                                                Por favor, Informe as credenciais master
                                            </div>
                                            <?php echo form_open(('portal/signin/login/' . $hash), array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>


                                            <input type="hidden" name="company" value="<?php echo $dominio; ?>">
                                            <div class="input-group input-group-outline mb-1 mb-3">
                                                <input id="user" name="user" type="text" class="form-control" placeholder="INFORME USUÁRIO" required="required">
                                            </div>
                                            <div class="input-group input-group-outline mb-1 mb-3">
                                                <input id="password" name="password" type="password" class="form-control" placeholder="INFORME SENHA" required="required">

                                            </div>
                                            <?php if ($_GET['error'] == 1) { ?>

                                                <p style="color: red; font-size: 10px;">

                                                    FALHA NO LOGIN! TENTE NOVAMENTE!

                                                </p>


                                            <?php } ?>
                                            <div class="text-center mb-0">
                                                <button type="submit" class="btn btn-xs btn-success w-100 theme-btn mx-auto text-white">CONFIRMAR</button>


                                            </div>

                                            <?php echo form_close(); ?>
                                            <div class="text-center">

                                                <a href="<?php echo base_url(); ?>Authentication/login" class="btn btn-warning w-100 theme-btn mx-auto text-white" style="margin-top: 10px;">VOLTAR</a>

                                            </div>

                                        </div>


                                    <?php } elseif ($_GET['hash'] && $_GET['master'] != 1) { ?>
                                        <div class="card-header">
                                            <h4 class="font-weight-bolder">Olá <b><?php echo $client->company; ?></b>, Insira o Token para iniciar</h4>

                                        </div>
                                        <div class="card-body">
                                            <?php if ($msg) { ?>
                                                <?php echo $msg; ?>

                                            <?php } ?>
                                            <?php echo form_open(('portal/signin/login/' . $hash), array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>

                                            <input id="hash" name="hash" type="hidden" value="<?php echo $hash; ?>">

                                            <div class="input-group input-group-outline mb-1">
                                                <label class="sr-only" for="signin-password">Token</label>
                                                <input id="token" name="token" type="password" class="form-control signin-password" placeholder="Token" required="required" maxlength="6" minlength="6">
                                            </div>
                                            <?php if ($_GET['error'] == 1) { ?>

                                                <p style="color: red; font-size: 10px;">

                                                    TOKEN INCORRETO. TENTE NOVAMENTE!

                                                </p>


                                            <?php } ?>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-xs btn-success w-100 mt-4 mb-0 text-white">Entrar</button>
                                                <br><br>
                                                <a style="color: #ffffff;" href="<?php echo base_url(); ?>Authentication/login" class="btn btn-xs btn-warning w-100 theme-btn mx-autow-100 theme-btn mx-auto">VOLTAR</a>

                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>

                                    <?php } else { ?>
                                        <div class="form-check form-switch ">
                                            <input class="form-check-input" type="checkbox" id="check_change" onchange="master(this);">
                                            <label class="form-check-label" for="check_change">Acesso Master </label><br>
                                          <!--  <input class="form-check-input" type="checkbox" id="check_change_rapido" onchange="rapido(this);">
                                            <label class="form-check-label" for="check_acesso_rapido">Acesso Rápido (Sem Token) </label>-->
                                        </div>

                                        <div class="card-header text-center">
                                            <div class="app-auth-branding mb-3 "><a href=""><img class="logo-icon me-1" style="max-height: 60px;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a></div>
                                            <h4 class="font-weight-bolder"><?php echo $category['p_salutation']; ?></h4>
                                            <p class="mb-0"><?php echo $category['p_msg']; ?></p>
                                        </div>
                                        <div class="card-body">

                                        
                                            


                                             <?php /*echo form_open("portal/signin/valida", array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); //echo 'aqui'; ?>
                                            <?php //echo form_open(("portal/signin/login_2/". $hash), array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); //echo 'aqui'; ?>

                                          
                                            <!--<input id="hash" name="hash" type="hidden" value="<?php echo $hash; ?>">-->
                                            <input type="hidden" name="type_login" id="type_login"> 
                                            <input name="id" value="<?php echo $category['id']; ?>" type="hidden"><!-- comment -->
                                            <input name="apicategoria_id" value="<?php echo $category['apicategoria_id']; ?>" type="hidden"> 
                                            <?php
                                            $campos = $this->Categorias_campos_model->get_categoria_campos($category['apicategoria_id'], '', true, 'api');

                                          //  print_r($campos);
                                            $data['campos'] = $campos;
                                            $this->load->view('portal/categorias_campos/campos', $data);
                                            ?> <?php //echo "aqui"; ?>
                                            <?php if ($_GET['error'] == 2) { ?>
                                                <p style="color: red; font-size: 10px;">
                                                    Falha de acesso.
                                                </p>
                                            <?php } ?>
                                            <?php if ($_GET['error'] == 1) { ?>
                                                <p style="color: red; font-size: 10px;">
                                                    Registro não encontrado ou inativo!
                                                </p>
                                            <?php } ?>


                                            <div class="text-center">
                                                <button type="submit" class="btn btn-xs bg-gradient-success w-100 mt-4 mb-0">Acessar</button>
                                            </div>

                                            <?php echo form_close(); */?>

                                      
                                            


                                            <?php echo form_open("portal/signin/valida", array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); //echo 'aqui'; ?>
                                            <?php //echo form_open(("portal/signin/login_2/". $hash), array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); //echo 'aqui'; ?>
                                          
                                            <!--<input id="hash" name="hash" type="hidden" value="<?php echo $hash; ?>">-->
                                            <input type="hidden" name="type_login" id="type_login"> 
                                            <input name="id" value="<?php echo $category['id']; ?>" type="hidden"><!-- comment -->
                                            <input name="apicategoria_id" value="<?php echo $category['apicategoria_id']; ?>" type="hidden"> 
                                            <?php
                                            $campos = $this->Categorias_campos_model->get_categoria_campos($category['apicategoria_id'], '', true, 'api');

                                          //  print_r($campos);
                                            $data['campos'] = $campos;
                                            $this->load->view('portal/categorias_campos/campos', $data);
                                            ?> <?php //echo "aqui"; ?>
                                            <?php if ($_GET['error'] == 2) { ?>
                                                <p style="color: red; font-size: 10px;">
                                                    Falha de acesso.
                                                </p>
                                            <?php } ?>
                                            <?php if ($_GET['error'] == 1) { ?>
                                                <p style="color: red; font-size: 10px;">
                                                    Registro não encontrado ou inativo!
                                                </p>
                                            <?php } ?>


                                            <div class="text-center">
                                                <button type="submit" class="btn btn-xs bg-gradient-success w-100 mt-4 mb-0">ACESSAR MEU PORTAL (Completo)</button>
                                                <button type="button" class="btn btn-xs bg-gradient-info w-100 mt-4 mb-0" onclick="enviarComVariavel()">ACESSO RÁPIDO AO BOLETO</button>
                                                
                                            </div>

                                            <?php echo form_close(); ?>


                                            <p class="mt-2 text-center" style="font-size: 12px;"><?php echo $category['p_msg2']; ?></p>

                                        </div>
                                    <?php } ?>

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
<?php } ?>

</html>