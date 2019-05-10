<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $Settings->site_name; ?></title>
   
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <link href="<?= $assets ?>styles/theme.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/style.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/helpers/login.css" rel="stylesheet"/>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/respond.min.js"></script>
    <![endif]-->
    <style>
    body {
  //  background-image: url('<?= $assets ?>login/lib/images/background.png');
    background-repeat: no-repeat;
    }
    
    
    #logoempresa{

                width: 350px;
                height: 70px;
                margin: auto;
               background-image:url(<?= $assets ?>login/lib/images/sig.png);
                background-repeat:no-repeat; 
                background-size:100% 100%;
                -webkit-background-size: 100% 100%;
                -o-background-size: 100% 100%;
                -khtml-background-size: 100% 100%;
                -moz-background-size: 100% 100%;
                
            }
            
            
    #logo_sig{

                width: 110%;
                height: 350px;
                
                background-image:url(<?= $assets ?>login/lib/images/logo_login.jpeg);
                background-repeat:no-repeat; 
                background-size:100% 100%;
                -webkit-background-size: 100% 100%;
                -o-background-size: 100% 100%;
                -khtml-background-size: 100% 100%;
                -moz-background-size: 100% 100%;
                
            }        
    </style>
    <style type="text/css">
#box{
    /*definimos a largura do box*/
    width:350px;
    /* definimos a altura do box */
    height:100%;
    /* definimos a cor de fundo do box */
    background-color: lightsteelblue;
    /* definimos o quão arredondado irá ficar nosso box */
    border-radius: 10px 30px 30px 10px;
    
    margin-left: 20px;
    
    margin-top: 60px;
    }
</style>
</head>

<body class="login-page"  >
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>

   
      

        
     <?php if ($Settings->mmode) { ?>
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= lang('site_is_offline') ?>
                        </div>
                    <?php }
                    if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
        <br><br><br><br><br>
    <div  class="row ">    
    <div   class=" container" >
        
        <div  class="col-md-12 ">
            <div  style="max-width: 800px; margin: 0 auto; " >
                <div  class="col-md-6 ">
                    <div  id="logo_sig" ></div>
                </div>  
                <div id="box"  class="col-md-6 ">
                   
                               <?php echo form_open("auth/login", 'class="login" data-toggle="validator"'); ?>
                            

                            <div class="textbox-wrap form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" required="required" class="form-control" name="identity"
                                           placeholder="<?= lang('login') ?>"/>
                                </div>
                            </div>
                            <div class="textbox-wrap form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="password" required="required" class="form-control " name="password"
                                           placeholder="<?= lang('pw') ?>"/>
                                </div>
                            </div>
                            <?php if ($Settings->captcha) { ?>
                                <div class="textbox-wrap form-group">

                                    <div class="row">
                                        <div class="col-sm-6 div-captcha-left">
                                            <span class="captcha-image"><?php echo $image; ?></span>
                                        </div>
                                        <div class="col-sm-6 div-captcha-right">
                                            <div class="input-group">
                                                <span class="input-group-addon"><a href="<?= base_url(); ?>auth/reload_captcha"
                                                                                   class="reload-captcha"><i
                                                            class="fa fa-refresh"></i></a></span>
                                                    <?php echo form_input($captcha); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            <?php } /* echo $recaptcha_html; 993877044 */ ?>

                            <div class="form-action clearfix">
                                <div class="checkbox pull-left">
                                    <div class="custom-checkbox">
                                        <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                                    </div>
                                    <span class="checkbox-text pull-left"><label 
                                            for="remember"><?= lang('remember_me') ?></label></span>
                                </div>
                                <button style="background-color: #323c50" type="submit" class="btn btn-success pull-right"><?= lang('login') ?> &nbsp; <i
                                        class="fa fa-sign-in"></i></button>
                            </div>
                            <?php echo form_close(); ?>
                        
                </div>
            </div>
         </div>    
        <div class="col-md-12 ">
            <div style="max-width: 800px; margin: 0 auto; " >
             <div class="login-form-links link2">
                        <h4 class="text-danger"><?= lang('forgot_your_password') ?></h4>
                        <span><?= lang('dont_worry') ?></span>
                        <a href="#forgot_password" class="text-danger forgot_password_link"><?= lang('click_here') ?></a>
                        <span><?= lang('para Redefinir') ?></span>
                    </div>
                    <?php if ($Settings->allow_reg) { ?>
                        <div class="login-form-links link1">
                            <h4 class="text-info"><?= lang('dont_have_account') ?></h4>
                            <span><?= lang('no_worry') ?></span>
                            <a href="#register" class="text-info register_link"><?= lang('click_here') ?></a>
                            <span><?= lang('to_register') ?></span>
                        </div>
                    <?php } ?>
            </div>
        </div>
        
    </div>
 
    <div id="forgot_password" style="display: none; margin-top: 200px;">
        <div class=" container">
            <div class="login-form-div">
                <div class="login-content">
                    <?php if ($error) { ?>
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $error; ?></ul>
                        </div>
                    <?php }
                    if ($message) { ?>
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <ul class="list-group"><?= $message; ?></ul>
                        </div>
                    <?php } ?>
                    <div class="div-title">
                        <h3 class="text-primary"><?= lang('forgot_password') ?></h3>
                    </div>
                    <?php echo form_open("auth/forgot_password", 'class="login" data-toggle="validator"'); ?>
                    <div class="textbox-wrap form-group">
                        <div class="input-group">
                            <span class="input-group-addon "><i class="fa fa-envelope"></i></span>
                            <input type="email" name="forgot_email" class="form-control "
                                   placeholder="<?= lang('email_address') ?>" required="required"/>
                        </div>
                    </div>
                    <div class="form-action clearfix">
                        <a class="btn btn-success pull-left login_link" href="#login"><i
                                class="fa fa-chevron-left"></i> <?= lang('back') ?>  </a>
                        <button type="submit" class="btn btn-primary pull-right"><?= lang('submit') ?> &nbsp;&nbsp; <i
                                class="fa fa-envelope"></i></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($Settings->allow_reg) { ?>
        <div id="register">
            <div class=" container">

                <div class="registration-form-div">
                    <form>
                        <div class="div-title reg-header">
                            <h3 class="text-primary"><?= lang('register_account_heading') ?></h3>

                        </div>
                        <div class="clearfix">
                            <div class="col-sm-6 registration-left-div">
                                <div class="reg-content">
                                    <div class="textbox-wrap form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control "
                                                   placeholder="<?= lang('first_name') ?>" required="required"/>
                                        </div>
                                    </div>
                                    <div class="textbox-wrap form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control "
                                                   placeholder="<?= lang('last_name') ?>" required="required"/>
                                        </div>
                                    </div>
                                    <div class="textbox-wrap form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-envelope"></i></span>
                                            <input type="email" class="form-control "
                                                   placeholder="<?= lang('email_address') ?>" required="required"/>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-sm-6 registration-right-div">
                                <div class="reg-content">
                                    <div class="textbox-wrap form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control "
                                                   placeholder="<?= lang('username') ?>" required="required"/>
                                        </div>
                                    </div>
                                    <div class="textbox-wrap form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-key"></i></span>
                                            <input type="password" class="form-control " placeholder="<?= lang('pw') ?>"
                                                   required="required"/>
                                        </div>
                                    </div>
                                    <div class="textbox-wrap form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon "><i class="fa fa-key"></i></span>
                                            <input type="password" class="form-control "
                                                   placeholder="<?= lang('confirm_password') ?>" required="required"/>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="registration-form-action clearfix">
                            <a href="#login" class="btn btn-success pull-left login_link">
                                <i class="fa fa-chevron-left"></i> <?= lang('back') ?>
                            </a>
                            <button type="submit" class="btn btn-primary pull-right"><?= lang('register_now') ?> <i
                                    class="fa fa-user"></i></button>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    <?php }?>

    <footer style="background-color: #323c50; position: fixed; bottom: 0px; right: 0px; width: 100%; ">


    <p style="text-align:center;">&copy; <?= date('Y') . " SIGPlus "  ?> (v<?= $Settings->version; ?>
        ) <?php 
            echo ' - Todos os Direitos Reservados';
        ?></p>
</footer>
    </div>    
<script src="<?= $assets ?>js/jquery.js"></script>
<script src="<?= $assets ?>js/bootstrap.min.js"></script>
<script src="<?= $assets ?>js/jquery.cookie.js"></script>
<script src="<?= $assets ?>js/login.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var hash = window.location.hash;
        if (hash && hash != '') {
            $("#login").hide();
            $(hash).show();
        }
    });
</script>
</body>
</html>
