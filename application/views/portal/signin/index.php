<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>V-social</title>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Portal - Vsocial">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
    <link rel="icon" type="image/png" href="../../assets/portal/images/favicon_v.png">
    
    <link rel="manifest" href="../../manifest.json">
    <script defer="defer" src="../../main.js"></script>
        
    <!-- FontAwesome JS-->
    <script defer src="../../assets/portal/plugins/fontawesome/js/all.min.js"></script>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="../../assets/portal/css/portal.css">
    
    <script>
    function ValidaCPF(){	
            var RegraValida=document.getElementById("RegraValida").value; 
            var cpfValido = /^(([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2})|([0-9]{11}))$/;	 
            if (cpfValido.test(RegraValida) == true)	{ 
            console.log("CPF Válido");	
            } else	{	 
            console.log("CPF Inválido");	
            }
        }
      function fMasc(objeto,mascara) {
    obj=objeto
    masc=mascara
    setTimeout("fMascEx()",1)
    }

      function fMascEx() {
    obj.value=masc(obj.value)
    }

       function mCPF(cpf){
    cpf=cpf.replace(/\D/g,"")
    cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
    cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
    cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    return cpf
    }  
    function mCNPJ(cnpj) {
                cnpj = cnpj.replace(/\D/g, "")
                cnpj = cnpj.replace(/^(\d{2})(\d)/, "$1.$2")
                cnpj = cnpj.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
                cnpj = cnpj.replace(/\.(\d{3})(\d)/, ".$1/$2")
                cnpj = cnpj.replace(/(\d{4})(\d)/, "$1-$2")
                return cnpj
            }
</script>    
</head> 
<?php 

//registro de seguranca
?> 
<body class="app app-login p-0">    	
    <div class="row g-0 app-auth-wrapper">
      <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
            <div class="d-flex flex-column align-content-end">
                <?php if($cpf != ''):?>
                    <div class="app-auth-body mx-auto">	
                            <div class="app-auth-branding mb-4"><a  href="index.php"><img class="logo-icon me-2" src="../../vsocial.png" alt="logo"></a></div>
                                <h1 class=" text-center mb-5">Seja bem vindo!</h1>
                                <br>
                                <h2 class="auth-heading text-center mb-5">Informe o CNPJ para iniciar.</h2>
                                 <?php if ($smg_erro) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <span class="fa fa-exclamation" aria-hidden="true"></span>
                                        <?php echo $smg_erro; ?>
                                    </div>
                                <?php } ?>
                                
                                <?php if ($smg_success) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <span class="fa fa-check" aria-hidden="true"></span>
                                        <?php echo $smg_success; ?>
                                    </div>
                                <?php } ?>
                                
                                <div class="auth-form-container text-start">
                                    <?php echo form_open("portal/signin/validaCNPJ", array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); ?>  

                                        <input type="hidden" name="vendedor" value="<?php echo $vendedor; ?>">
                                                <div class="email mb-3">
                                                        <label class="sr-only" for="signin-email">CNPJ</label>
                                                        <input style="height: 50px; font-size: 20px;" id="cnpj" name="cnpj" type="text" maxlength="18" onkeypress="javascript: fMasc( this, mCNPJ );" onblur="javascript: fMasc( this, mCNPJ );"  class="form-control signin-email" placeholder="INFORME O CNPJ" required="required" pattern=".{18,}">
                                                </div><!--//form-group-->

                                                <div class="text-center">
                                                        <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">CONFIRMAR</button>
                                                        <br><br>
                                                        <a style="color: #ffffff;" onclick="history.go(-1)" class="btn btn-info w-100 theme-btn mx-autow-100 theme-btn mx-auto">VOLTAR</a>
                                                        
                                                </div>
                                         <?php echo form_close(); ?>


                                </div><!--//auth-form-container-->	
                               
                    </div><!--//auth-body-->
                <?php else:?>
                    <div class="app-auth-body mx-auto">	
                            <div class="app-auth-branding mb-4"><a  href="index.php"><img class="logo-icon me-2" src="../../assets/portal/images/vsocial.png" alt="logo"></a></div>
                                <h1 class=" text-center mb-5">Seja bem vindo!!</h1>
                                <br>
                                <h2 class="auth-heading text-center mb-5">Informe seu CPF para iniciar.</h2>
                                 <?php if ($smg_erro) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <span class="fa fa-exclamation" aria-hidden="true"></span>
                                        <?php echo $smg_erro; ?>
                                    </div>
                                <?php } ?>
                                
                                <?php if ($smg_success) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <span class="fa fa-check" aria-hidden="true"></span>
                                        <?php echo $smg_success; ?>
                                    </div>
                                <?php } ?>
                                
                                <div class="auth-form-container text-start">
                                    <?php echo form_open("portal/signin/valida", array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); ?>  

                                        <input type="hidden" name="vendedor" value="<?php echo $vendedor; ?>">
                                                <div class="email mb-3">
                                                        <label class="sr-only" for="signin-email">CPF</label>
                                                        <input style="height: 50px; font-size: 20px;" id="cpf" name="cpf" type="text" maxlength="14" onkeypress="javascript: fMasc( this, mCPF );" onblur="javascript: fMasc( this, mCPF );"  class="form-control signin-email" placeholder="INFORME O CPF" required="required" pattern=".{14,}">
                                                        <div class="dropdown float-lg-end mb-2 ml-1">
                               <!--<a clas="" href="<?php echo base_url('portal/Signin/cnpj');?>">Entrar com CNPJ</a>-->
                            </div>
                                                        
                                                </div><!--//form-group-->
                                                

                                                <div class="text-center">
                                                        <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">CONFIRMAR</button>
                                                        <br><br>
                                                        <a style="color: #ffffff;" href="https://assinatura.vsocial.sigplus.app.br/index.php?id=5406cf4c644246f4e390d0ebf7bed3f0" class="btn btn-info w-100 theme-btn mx-autow-100 theme-btn mx-auto">QUERO O V-SOCIAL</a>
                                                        <br><br>
                                                        <a style="color: #ffffff;" href="<?php echo base_url('portal/Signin/cnpj');?>" class="btn btn-info w-100 theme-btn mx-autow-100 theme-btn mx-auto">ENTRAR COM CNPJ</a>
                                                </div>
                                         <?php echo form_close(); ?>


                                </div><!--//auth-form-container-->	
                               
                    </div><!--//auth-body-->
                <?php endif;?>
                    <footer class="app-auth-footer">
                            <div class="container text-center py-3">
                                 <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
                        <small class="copyright">Desenvolvido com <i class="fas fa-heart" style="color: #fb866a;"></i> por <a class="app-link" href="http://vsocial.com.br" target="_blank">Sigplus / Vsocial</a> </small>

                            </div>
                    </footer><!--//app-auth-footer-->	
            </div><!--//flex-column-->   
    </div>
      <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
          <div class="auth-background-holder" style="">
		    </div>
		    <div class="auth-background-mask"></div>
		    <div class="auth-background-overlay p-3 p-lg-5">
			    <div class="d-flex flex-column align-content-end h-100">
				    <div class="h-100"></div>
				    <div class="overlay-content p-3 p-lg-4 rounded">
					    <h5 class="mb-3 overlay-title"><a href="http://vsocial.com.br/">Descubra um mundo cheio de vantagens com o V-social!!</a></h5>
					    <div></div>
				    </div>
				</div>
		    </div> 
	    </div>
    </div><!--//row-->
</body>
<script src="../../../assets/js/core/popper.min.js"></script>
  <script src="../../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../../assets/js/plugins/smooth-scrollbar.min.js"></script>
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
  <script src="../../../assets/js/material-dashboard.min.js?v=3.0.0"></script>
</html> 

