<!DOCTYPE html>
<!-- saved from url=(0037)https://app.br.sageone.com/signup/new -->
<html class="logged_out simple-signup js flexbox canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths no-ipad">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>
            Primeiro Acesso ao Sage One
        </title>
        <!--[if lt IE 9]><script src="/assets/sop_ui_components_ie-6e195bda9297c324d5f0be247b843c14.js" type="text/javascript"></script><![endif]-->
        <link href="<?= $assets ?>cadastro/application-a79cfc57e3863c0ae33e8f3b8842326d.css" media="screen" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="<?= $assets ?>cadastro/8eb3f727a2"></script>
       
        <link href="<?= $assets ?>images/icon.png" rel="shortcut icon" type="image/vnd.microsoft.icon">

        <script src="<?= $assets ?>js/jquery.maskedinput-1.1.4.pack.js" ></script>
        <script src="<?= $assets ?>js/jquery-1.2.6.pack.js" ></script>

        <script type="text/javascript">
            //<![CDATA[
            var I18n = I18n || {};
            I18n.defaultLocale = "pt-BR";
            I18n.locale = "pt-BR";

            //]]>
            
           function Mudarestado(el) {
        var display = document.getElementById(el).style.display;
        if(display == "none")
            document.getElementById(el).style.display = 'block';
        else
            document.getElementById(el).style.display = 'none';
    }
    
        </script>
    <script type="text/javascript">$(document).ready(function(){	$("#cpf").mask("999.999.999-99");});</script>
        
        <meta logout_url="/logout" name="heartbeat">
        <meta content="authenticity_token" name="csrf-param">
        <meta content="9x4s3foNlFG0KEEwAEFTXCeKvYPNJ5VexoGaAa6Pg1w=" name="csrf-token">


        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">


        <meta name="viewport" content="width=device-width, user-scalable=no">
    </head>
    <body>




        <div id="ui-root" class="logged_out">


            <section id="ui-body">
                <div id="ui-main">

                    <header>
                        <hgroup>
                            <h1>
                                <div class="text-center"><?php
                                    if ($Settings->logo2) {
                                        echo '<img width = 350px; height = 70px; src="' . base_url('assets/uploads/logos/logo1.png') . '" alt="' . $Settings->site_name . '" style="margin-bottom:10px;" />';
                                    }
                                    ?></div><br>
                                Cadastro de Acesso ao Gestor Fácil
                            </h1>
                        </hgroup>
                        <meta name="viewport" content="width=device-width, user-scalable=no">
                    </header>

                    <div class="UIForm" data-autofocus="false" data-base_action="/signup" data-delegate="Signup" data-resource="sop_authentication/signup" data-ui-widget="UIForm">
                        <form accept-charset="UTF-8" action="https://gestorfacil.online" class="new_signup" id="new_signup" method="post">
                            <div style="margin:0;padding:0;display:inline"><input name="utf8" type="hidden" value="✓">
                                <input name="authenticity_token" type="hidden" value="9x4s3foNlFG0KEEwAEFTXCeKvYPNJ5VexoGaAa6Pg1w="></div>
                            <div class="UIContainer signup_form" data-ui-widget="UIContainer">
                                <div class="UIRow" data-ui-widget="UIRow">
                                    <div class="column-set columns-1">
                                        <div class="column column-1">
                                            <fieldset class="UIFieldset" data-ui-widget="UIFieldset">
                                                
                                             
                                                
                                                <div class="UIRow" data-ui-widget="UIRow">
                                                    <div class="column-set columns-2">
                                                        <div class="column column-1">
                                                            <div  data-ui-widget="UITextbox">
                                                                <label for="signup_user_first_name">Primeiro Nome </label>
                                                                <span class="field">
                                                                    <input autocomplete="off" id="nome" name="nome" placeholder="Seu Primeiro Nome" size="30" type="text">
                                                                </span>
                                                            </div>
                                                        </div>   
                                                        <div class="column column-1">
                                                            <div  data-ui-widget="UITextbox">
                                                                <label for="signup_user_first_name">Sobrenome</label>
                                                                <span class="field">
                                                                    <input autocomplete="off" id="nome" name="sobrenome" placeholder="Seu Sobrenome" size="30" type="text">
                                                                </span>
                                                            </div>
                                                        </div>   
                                                    </div>
                                                </div>
                                                
                                                <div  class="UIRow" data-ui-widget="UIRow">
                                                    <div class="column-set columns-1">
                                                        <div class="column column-1">
                                                           
                                                                <label for="signup_user_first_name">Tipo de Pessoa</label>
                                                                <span class="field">
                                                                    <select onchange="if(value=='Fisica'){ Mudarestado('DivCPF');Mudarestado('DivCNPJ');}else if(value=='Juridica'){Mudarestado('DivCNPJ'); Mudarestado('DivCPF');}">
                                                                        <option value="Fisica" >Pessoa Física</option>
                                                                        <option value="Juridica">Pessoa Jurídica</option>
                                                                    </select>
                                                                   
                                                                </span>
                                                       
                                                        </div>                                                        
                                                    </div>
                                                </div>
                                                
                                                
                                                <div  id="DivCPF" class="UIRow" data-ui-widget="UIRow">
                                                    <div class="column-set columns-1">
                                                        <div class="column column-1">
                                                            <div  data-ui-widget="UITextbox">
                                                                <label for="signup_user_email">CPF</label><span class="field">
                                                                    <input autocomplete="off" id="cpf" name="cpf" placeholder="CPF" size="30" type="text" maxlength="100"></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div style="display: none" id="DivCNPJ"  class="UIRow" data-ui-widget="UIRow">
                                                    <div class="column-set columns-1">
                                                        <div class="column column-1">
                                                            <div class="UITextbox presence email presence email presence email presence email length length_maximum-100 presence email presence email length length_maximum-100" data-ui-widget="UITextbox">
                                                                <label for="signup_user_email">CNPJ</label><span class="field">
                                                                    <input autocomplete="off" id="signup_user_email" name="signup[user_email]" placeholder="CNPJ" size="30" type="text" maxlength="100"></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                
                                                <div class="UIRow" data-ui-widget="UIRow">
                                                    <div class="column-set columns-1">
                                                        <div class="column column-1">
                                                            <div class="UITextbox presence email presence email presence email presence email length length_maximum-100 presence email presence email length length_maximum-100" data-ui-widget="UITextbox">
                                                                <label for="signup_user_email">Email</label><span class="field">
                                                                    <input autocomplete="off" id="signup_user_email" name="signup[user_email]" placeholder="Informe o seu Email" size="30" type="text" maxlength="100"></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="UIRow" data-ui-widget="UIRow">
                                                    <div class="column-set columns-1">
                                                        <div class="column column-1">
                                                            <div class="UITextbox presence email presence email presence email presence email length length_maximum-100 presence email presence email length length_maximum-100" data-ui-widget="UITextbox">
                                                                <label for="signup_user_email">Confirme seu Email</label><span class="field">
                                                                    <input autocomplete="off" id="signup_user_email" name="signup[user_email]" placeholder="Confirme seu Email" size="30" type="text" maxlength="100"></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                             
                                            </fieldset></div>
                                    </div></div>
                                <div class="UIRow" data-ui-widget="UIRow"><div class="column-set columns-1">
                                        <div class="column column-1"><div class="UIContainer with-label" data-ui-widget="UIContainer">
                                                <div class="UICheckbox acceptance acceptance_accept-1 acceptance acceptance_accept-1 acceptance acceptance_accept-1" data-ui-widget="UICheckbox">
                                                    <span class="field"><input name="signup[terms_and_conditions]" type="hidden" value="0">
                                                        <input autocomplete="off" id="signup_terms_and_conditions" name="signup[terms_and_conditions]" type="checkbox" value="1">
                                                        <span class="checkbox-overlay"></span></span>
                                                    <label for="signup_terms_and_conditions">Li e aceito os <a href="" target="_blank">termos e condições de uso</a></label></div>
                                            </div></div></div></div></div>
                            <div class="UIContainer signup_actions" data-ui-widget="UIContainer" style="max-height: 220px;">

                                <div class="UIContainer primary_actions" data-ui-widget="UIContainer">
                                    <div class="UIRow" data-ui-widget="UIRow">
                                        <div class="column-set columns-1">
                                            <div class="column column-1">
                                                <span class="UIButton" data-ui-widget="UIButton">
                                                    <button autocomplete="off" class="sageid action primary" name="sageid" type="submit">Criar minha conta no Sage One</button></span></div>
                                            <div class="column column-2">
                                                <div class="UIContainer already_registered" data-ui-widget="UIContainer"><a href="https://app.br.sageone.com/login" class="UILink" data-ui-widget="UILink"><span>Já sou cliente. Acessar o Gestor Fácil</span></a></div></div>
                                        </div></div></div></div>
                        </form>
                    </div>
                </div>
            </section>
            <!-- .#ui-body -->


        </div>

        <!-- .#ui-root -->
        <div id="notification-container"></div>
        <script src="<?= $assets ?>cadastro/application-67e2fbab52bb03a892424ea84bca35d8.js.download" type="text/javascript"></script>




    </body><object id="cb281849-b868-aead-1afa-59afa994ada0" width="0" height="0" type="application/gas-events-uni"></object></html>