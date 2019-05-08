
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title> Controle de Projetos TI UNIMED MANAUS </title>
       
        <link rel="stylesheet" type="text/css" href="<?= $assets ?>login/lib/css/style.css"></link>
        <link rel="shortcut icon" style="width: 64px; height: 64px;" href="<?= $assets ?>login/images/indice.jpg" type="image/x-icon" />
        <style>
            *{

                margin: 0px;
                padding: 0px;
                font-family: Fineness Regular;
            }



            @font-face {
                font-family: Fineness Regular;
                src: url(<?= $assets ?>login/lib/font/FinenessRegular.ttf);
            }



            a{

                text-decoration: none;  
                border: 0px;
                color: #51AEE2;
            }



            #contentstudo{


                height:450px;
                position: fixed;
                top:30%;
                width:100%;

            }

            #contentsmeio{

                width: 343px;
                height: 269px;
                margin: auto;   
                background-image:url(<?= $assets ?>login/lib/images/caixa.png);
                background-repeat:no-repeat; 

                background-size:100% 100%;
                -webkit-background-size: 100% 100%;
                -o-background-size: 100% 100%;
                -khtml-background-size: 100% 100%;
                -moz-background-size: 100% 100%;
            }


            #contentsfundo{



            }


            #logoempresa{

                width: 280px;
                height: 120px;
                margin: auto;
                background-image:url(<?= $assets ?>login/lib/images/UNIMED-MANAUS.jpg);
                background-repeat:no-repeat; 
                background-size:100% 100%;
                -webkit-background-size: 100% 100%;
                -o-background-size: 100% 100%;
                -khtml-background-size: 100% 100%;
                -moz-background-size: 100% 100%;
                margin-top: 0px;
            }




            #contentsbranco{

                width: 370px;
                height: 90px;

                margin-left: -1px;
                margin-top: 10px;

            }

            #nomeusuario{

                width: 80px;
                height: 32px;
                margin-left: 3px;
                margin-top: 3px;
                float: left;
                padding-left: 10px;
                line-height: 25px;



            }

            #inputusuario{

                width: 220px;
                margin-top: 5px;
                margin-left: 95px;


            }


            input{

                padding: 0px;   
                width: 220px;
                height: 47px;
                float: left;
                margin-top: 5px;

            }

            #senhausuario{
                padding: 5px;   
                width: 70px;
                height: 37px;
                margin-left: 3px;
                margin-top: 10px;
                float: left;
                padding-left: 21px;
                line-height: 36px;

            }

            #loginfundo{

                width: 326px;
                height: 60px;
                margin-top: 30px;

                float: left;

            }
            #botao1{

                width: 250px;
                height: 50px;
                margin: auto;


            }

            #botao{

                width: 50px;
                height: 30px;
                border-style: solid;

                /*    background-image:url(../../imagens/entrar-08.png);
                    background-repeat:no-repeat; */
            }








            #esquecesenha{


                width: 140px;
                float: left;
                margin-top: -10px;
                margin-left: 5px;
                font-size: 12px;
                color: #51AEE2;   


            }


            a:hover{

                color: #1B75BB;   
            }


            #tudofooter{

                width: 100%;
                margin: auto;
                height: 235px;
                margin-top: 38px;
                background-image:url(<?= $assets ?>login/lib/images/footer.png);
                background-repeat:repeat;   

            }    

            #footerinf{

                width: 960px;
                height: 30px;
                margin: auto;
                padding-top: 10px;

            }

            #textfooterpag{

                color: white;
                font-size: 17px;
                width: 200px;
                float: left;
                text-align: center;

            }

            .footersep{

                background-image:url(<?= $assets ?>login/lib/images/linhafooter-02.png);
                background-repeat:no-repeat;
                float: left;
                width: 5px;
                height: 25px;
            }

            #textfooterconta{

                color: white;
                font-size: 17px;
                width: 180px;
                text-align: center;
                float: left;

            }

            #textfooterprod{

                color: white;
                font-size: 17px;
                width: 180px;
                text-align: center;
                float: left;


            }
            #textfootercontro{

                color: white;
                font-size: 17px;
                width: 200px;
                text-align: center;
                float: left;

            }


            #textfooterveja{

                color: white;
                font-size: 17px;
                width: 180px;
                text-align: center;
                float: left;

            }


            #totalgerenciador{
                width: 100%;
                height: 100%;


                margin-top: 0px;
                /*border: 1px solid blue;*/
                background-image:url(<?= $assets ?>login/lib/images/West-Palm-Beach-Estate-Planning-Lawyer-1024x692.jpg);
                background-repeat:no-repeat;

                background-size:100% 100%;
                -webkit-background-size: 100% 100%;
                -o-background-size: 100% 100%;
                -khtml-background-size: 100% 100%;
                -moz-background-size: 100% 100%;
            }
            

        </style>
    </head>
    <body>



<!--sidebar end-->
<div id="totalgerenciador">
    <div id="logoempresa"></div>
    <br>
    <center>  <font style="color: green; margin-top: -20px;"> <h3>SISTEMA DE GERENCIAMENTO DE PROJETOS</h3> </font></center>
    <div id="contentstudo">
         
       
    <div id="contentsmeio">
     
      <div class="login-content" >
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
           
        <?php echo form_open("auth/login", 'class="login" data-toggle="validator"'); ?>
                    <br>
                    <div id="inputusuario">
                        <input style="font-family: verdana;" placeholder="LOGIN" type="text" name="identity">
                    </div>
                   
                    <div id="inputusuario">
                        <input  type="password" placeholder="SENHA"  name="password">
                    </div>
            
            <div id="loginfundo">
                <div id="botao1">
                    <input  type="image" src="<?= $assets ?>login/lib/images/login.png" style="width: 270px; height: 49px;" value="submit">
                </div>
              <?php echo form_close(); ?>
             
            </div>
  
    </div>
        </div>
</div>


</body>
</html>
