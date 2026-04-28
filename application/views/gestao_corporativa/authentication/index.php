<!DOCTYPE html>

<html lang="en">

    <head>
        <title>Unimed Manaus</title>
        <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 10]>
                    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
                    <![endif]-->
        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description"
              content="Elite Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
        <meta name="keywords"
              content="admin templates, bootstrap admin templates, bootstrap 4, dashboard, dashboard templets, sass admin templets, html admin templates, responsive, bootstrap admin templates free download,premium bootstrap admin templates, Elite Able, Elite Able bootstrap admin template">
        <meta name="author" content="Codedthemes" />

        <!-- Favicon icon -->
        <link rel="icon" href="https://sigplus.app.br/uploads/company/<?php echo $logo; ?>" type="image/x-icon">
        <!-- fontawesome icon -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/login_intranet/fonts/fontawesome/css/fontawesome-all.min.css">
        <!-- animation css -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/login_intranet/plugins/animation/css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/login_intranet/css/style.css">

    </head>


    <body>
        <div class="auth-wrapper">
            <div class="auth-content container">
                <div class="card">
                    <div class="row align-items-center">
                        <div class="col-md-6 " >
                            <div class="card-body">
                                <img style="height: 50px; width: 100px;" src="https://sigplus.app.br/uploads/company/<?php echo $logo; ?>" alt="" class="img-fluid mb-4">
                                <h4 class="mb-3 f-w-400">ENTRAR <?php echo strtoupper($company_name); ?></h4>
                                <?php echo form_open(site_url('gestao_corporativa/authentication/login'), array('onsubmit' => 'loading();')); ?>


                                <div class="alert alert-danger" role="alert" id="msg" style="display: none;"></div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-user"></i></span>
                                    </div>
                                    <input type="text" id="login" name="login" class="form-control" placeholder="Usuário" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                    </div>
                                    <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
                                </div>
                                <button type="submit" class="btn shadow-2 mb-4 w-100 " style="color: white; background-color: green; text-align: center"  id="entrar">Entrar</button>
                                <?php echo form_close(); ?>
                                <div class="saprator"><span>INTRANET</span></div>
                                <div class="btn-group">
                                    <button class="btn  btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-plus"></i>Registro de Ocorrência
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php foreach ($registro_categorias as $registro) { ?>
                                            <a class="dropdown-item" target="_blanck" href="https://sigplus.app.br/Registro_ocorrencia/index/<?php echo $registro['hash']; ?>"><?php echo $registro['titulo']; ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-none d-md-block">
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <?php
                                    $i = 1;
                                    foreach ($banners as $banner) {
                                        ?>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i; ?>" class="<?php
                                        if ($i == 1) {
                                            echo 'active';
                                        }
                                        ?>"></li>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                </ol>
                                <div class="carousel-inner">
                                    <?php
                                    $i = 1;
                                    foreach ($banners as $banner) {
                                        ?>
                                        <div class="carousel-item <?php
                                        if ($i == 1) {
                                            echo 'active';
                                        }
                                        ?>">
                                            <img class="d-block w-100" style=" height: 80vh;" src="https://sigplus.app.br/assets/intranet/login/banners/<?php echo $banner; ?>" alt="<?php echo $banner; ?>">
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                    ?>

                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Pr�ximo</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Anterior</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer mt-5">
                    <p class="text-center"> UNIMED MANAUS </p>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script>

<?php
if (isset($_GET['ANS'])) {
    $result = $_GET['ANS'];
    ?>

                if ('<?php echo $result; ?>' == 'ERROR') {
                    document.getElementById("msg").innerHTML = "É necessário um registro no Sigplus.";
                    document.getElementById("msg").style.display = 'block';
                } else if ('<?php echo $result; ?>' == 'ERROR_UNIMED') {
                    document.getElementById("msg").innerHTML = "Usuário ou senha incorreto(a).";
                    document.getElementById("msg").style.display = 'block';
                }

<?php } ?>


            function loading()
            {
                document.getElementById("entrar").disabled = true;
                document.getElementById("entrar").innerHTML = 'Carregando...';

            }
        </script>
    </body>

</html>