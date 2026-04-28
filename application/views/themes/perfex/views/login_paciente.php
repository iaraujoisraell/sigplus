<?php //echo "ENTROU"; exit;
//$url = $_SERVER[HTTP_HOST]; 
//$dominio = str_replace('.sigplus.app.br', '', $url);
$dominio = 'unimedmanaus';

$this->load->model('Company_model');

$row = $this->Company_model->get_company($dominio);

$this->load->model('Categorias_campos_model');

$result = $this->Categorias_campos_model->get_categorias('atendimento', false, $row->empresa_id, true);

$logo = get_company_option($dominio, 'company_logo');
$company = get_company_option($dominio, 'companyname');
$portal_message = get_company_option($dominio, 'portal_message');
$portal_image = get_company_option($dominio, 'portal_image');
?>
<!DOCTYPE html>
<html lang="en">
<?php if ($category) {
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
                            <!--   <div class="app-auth-branding mb-3 "><a href=""><img class="logo-icon me-1" style="max-height: 60px;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a></div>-->

                            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../../../pages/dashboards/analytics.html">
                                <!-- <img class="logo-icon me-1" style="max-height: 60px;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo">--><?php echo $company; ?>
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
                                                <?php //echo "aqui"; 
                                                ?>
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

                            <div id="div_login">

                                <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column me-auto ms-auto me-lg-auto ms-lg-5">
                                    <div class="card card-plain">


                                        <div class="card-header text-center">
                                            <div class="app-auth-branding mb-3 "><a href=""><img class="logo-icon me-1" style="max-height: 60px;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a></div>
                                            <h4 class="font-weight-bolder"><?php echo $category['p_salutation']; ?></h4>
                                            <p class="mb-0"><?php echo $category['p_msg']; ?></p>
                                        </div>


                                        <div class="card-body">

                                            <?php echo form_open("portal/Signin_paciente/valida", array("id" => "reset-password-form", "class" => "general-form", "role" => "form")); //echo 'aqui'; 
                                            ?>


                                            <label for="cpf">Informe suas credenciais</label>

                                            <div class="input-group input-group-outline ">
                                                <div class="col-md-12">

                                                    <?php if ($login_invalido == 1) { ?>
                                                        <label style="color: red;">Login ou Senha Incorretos.</label>
                                                    <?php } ?>
                                                    <input name="cpf" id="cpf" class="form-control" type="text" placeholder="CPF" />
                                                </div>
                                                <div class="col-md-12">
                                                    <input name="password" id="password" class="form-control" type="password" placeholder="SENHA" />
                                                </div>
                                                <div>
                                                    <br>
                                                    <a href="<?php echo base_url("auth/esqueci_senha")?>">Esqueci minha senha.</a>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-xs bg-gradient-success w-100 mt-4 mb-0" onclick="">ACESSAR</button>
                                                <button type="button" class="btn btn-xs bg-gradient-info w-100 mt-4 mb-0" onclick="exibe_formulario()">CRIAR CADASTRO</button>
                                                <!--  <button type="button" class="btn btn-xs bg-gradient-info w-100 mt-4 mb-0" onclick="enviarComVariavel()">ACESSO RÁPIDO AO BOLETO</button>
                                             -->
                                            </div>




                                            <?php echo form_close(); ?>


                                            <p class="mt-2 text-center" style="font-size: 12px;"><?php echo $category['p_msg2']; ?></p>

                                        </div>

                                        <p class="mt-2 text-center" style="font-size: 12px;"><?php echo $category['p_msg2']; ?></p>

                                    </div>
                                </div>
                                <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                                    <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('<?php if ($category['id'] == '272') {
                                                                                                                                                                                                    echo 'https://unimedmanaus.sigplus.app.br/assets/portal/images/background/background-2.png';
                                                                                                                                                                                                } else if ($category['id'] == '18') {
                                                                                                                                                                                                    echo 'https://unimedmanaus.sigplus.app.br/assets/portal/images/background/background-4.jpg';
                                                                                                                                                                                                } else {
                                                                                                                                                                                                    echo $portal_image;
                                                                                                                                                                                                } ?>'); background-size: cover;"></div>
                                </div>
                            </div>


                            <div id="div_form" style="display: none;">


                                <div class="col-md-12 ">
                                    <div class="card card-plain">

                                        <div class="card-header text-center">
                                            <div class="app-auth-branding mb-3 "><a href=""><img class="logo-icon me-1" style="max-height: 60px;" src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" alt="logo"></a></div>
                                            <h4 class="font-weight-bolder"><?php echo $category['p_salutation']; ?></h4>
                                            <p class="mb-0"><?php echo $category['p_msg']; ?></p>
                                        </div>

                                        <div class="card-body">

                                            <?php echo form_open("portal/Signin_paciente/add_cadastro", array("class" => "general-form", "role" => "form")); //echo 'aqui'; 
                                            ?>




                                            <div class="input-group input-group-outline ">

                                                <div class="col-md-5">
                                                    <label for="">Preencha o formulário de cadastro</label>
                                                    <div class="col-md-12">
                                                        <input name="nome_completo" id="nome_completo" class="form-control" type="text" placeholder="NOME COMPLETO" required />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <input id="cpf" name="cpf" type="text" class="form-control" placeholder="CPF" onkeyup="cpfCheck(this)" maxlength="18" onkeydown="javascript: fMasc( this, mCPF );" value="<?php echo $client->vat; ?>" required>
                                                        <span id="cpfResponse"></span>


                                                    </div>
                                                    <div class="col-md-12">

                                                        <label for="">Data de Nascimento</label>
                                                        <input name="dt_nascimento" id="dt_nascimento" class="form-control" type="date" placeholder="DATA DE NASCIMENTO" required />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <input name="telefone" id="telefone" class="form-control" type="text" placeholder="TELEFONE" required />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <input name="email" id="email" class="form-control" type="text" placeholder="E-MAIL" required />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <label for="">Escolha a modalidade de atendimento</label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <select name="plano" id="plano" class="form-control" onchange="exibe_convenio();" required>
                                                            <option value="">SELECIONE ...</option>
                                                            <option value="0">PARTICULAR</option>
                                                            <option value="1">CONVÊNIO</option>
                                                        </select>
                                                    </div>

                                                    <div id="div_convenio" class="col-md-12" style="display: none;">

                                                        <hr>
                                                        <div class="col-md-12" id="">
                                                            <input name="convenio" id="convenio" class="form-control" type="text" placeholder="CONVENIO" />
                                                            <input name="carteirinha" id="carteirinha" class="form-control" type="text" placeholder="CARTEIRINHA" />
                                                        </div>



                                                    </div>


                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-5">


                                                    <label for="">Preencha o seu endereço.</label>
                                                    <input name="zip" id="zip" class="form-control" type="text" placeholder="CEP" required />

                                                    <input name="address" id="address" class="form-control" type="text" placeholder="LOGRADOURO" required />

                                                    <input name="endereco_bairro" id="endereco_bairro" class="form-control" type="text" placeholder="BAIRRO" required />

                                                    <input name="endereco_numero" id="endereco_numero" class="form-control" type="text" placeholder="NUMERO" required />

                                                    <input name="city" id="city" class="form-control" type="text" placeholder="CIDADE" required />

                                                    <input name="state" id="state" class="form-control" type="text" placeholder="ESTADO" required />
                                                    <div class="col-md-12">
                                                        <hr>
                                                        <label for="senha">Cadastre sua senha.</label>
                                                    </div>

                                                    <div id="mensagemErro" style="display: none;">
                                                        <label style="color: red;">(A senha precisa ter no mínimo 6 caracteres com pelo menos uma letra e um número)</label>
                                                    </div>


                                                    <div class="col-md-12">
                                                        <input name="senha" id="senha" class="form-control" type="password" placeholder="SENHA" oninput="validarSenha();" required />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <input name="senha_conf" id="senha_conf" class="form-control" type="password" placeholder="CONFIRMA SENHA" oninput="confirma_senha();" required />

                                                    </div>
                                                    <div id="mensagemErroConfirma" style="display: none;">
                                                        <label style="color: red;">(Senha não confere.)</label>
                                                    </div>



                                                </div>

                                                <div class="col-md-5">
                                                    <button type="button" class="btn btn-xs bg-gradient-info w-100 mt-4 mb-0" onclick="exibe_formulario()">VOLTAR</button>
                                                </div>
                                                <div class="col-md-2"></div>

                                                <div class="col-md-5">
                                                    <button type="submit" class="btn btn-xs bg-gradient-success w-100 mt-4 mb-0" onclick="">CADASTRAR</button>
                                                </div>





                                            </div>


                                            <div class="text-center">



                                            </div>




                                            <?php echo form_close();
                                            ?>


                                            <p class="mt-2 text-center" style="font-size: 12px;"><?php echo $category['p_msg2']; ?></p>

                                        </div>




                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>

    <script>
        function exibe_formulario() {

            // alert("aqui");exit;
            // Obtém o elemento div pelo id
            var div_form = document.getElementById("div_form");
            var div_login = document.getElementById("div_login");

            // Verifica o estado de exibição da div
            if (div_form.style.display === "none") {
                // Se a div estiver oculta, exibe-a
                div_form.style.display = "block";
                div_login.style.display = "none";
            } else {
                // Se a div estiver visível, a oculta
                div_form.style.display = "none";
                div_login.style.display = "block";
            }

        }

        function exibe_convenio() {



            var div_convenio = document.getElementById("div_convenio");

            var item_selecionado = document.getElementById("plano").value;

            //  alert(item_selecionado);

            if (item_selecionado == 1) {
                // Se a div estiver oculta, exibe-a
                div_convenio.style.display = "block";

            } else {
                // Se a div estiver visível, a oculta
                div_convenio.style.display = "none";
            }


        }


        function validarSenha() {
            // alert("aqui");
            // Obtém o campo de senha e a mensagem de erro
            var senha = document.getElementById("senha").value;
            var mensagemErro = document.getElementById("mensagemErro");
            // var botaoSubmit = document.getElementById("botaoSubmit");

            // Expressão regular para garantir pelo menos 1 letra e 1 número
            var regex = /^(?=.*[a-zA-Z])(?=.*\d)/;

            // Verifica se a senha atende aos requisitos
            if (regex.test(senha) && senha.length >= 6) {

                // Se a senha é válida, limpa a mensagem de erro e ativa o botão
                mensagemErro.style.display = "none"; // Limpa a mensagem de erro
                // botaoSubmit.disabled = false; // Ativa o botão de enviar
            } else {
                // Se a senha não é válida, exibe a mensagem de erro e desativa o botão
                mensagemErro.style.display = "block";
                //   botaoSubmit.disabled = true; // Desativa o botão de enviar
            }
        }

        function confirma_senha() {

            var senha = document.getElementById("senha").value;
            var senhaConfirma = document.getElementById("senha_conf").value;

            var mensagemErro = document.getElementById("mensagemErroConfirma");

            if (senhaConfirma != senha) {
                mensagemErro.style.display = "block";
            } else {
                mensagemErro.style.display = "none";
            }

        }



        function submit() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('portal/Signin_paciente/valida'); ?>",
                data: {
                    cpf: document.getElementById('cpf').value,
                    password: document.getElementById('password').value
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    alert('aqui');
                }
            });
        }


        function is_cpf(c) {

            if ((c = c.replace(/[^\d]/g, "")).length != 11)
                return false

            if (c == "00000000000")
                return false;

            var r;
            var s = 0;

            for (i = 1; i <= 9; i++)
                s = s + parseInt(c[i - 1]) * (11 - i);

            r = (s * 10) % 11;

            if ((r == 10) || (r == 11))
                r = 0;

            if (r != parseInt(c[9]))
                return false;

            s = 0;

            for (i = 1; i <= 10; i++)
                s = s + parseInt(c[i - 1]) * (12 - i);

            r = (s * 10) % 11;

            if ((r == 10) || (r == 11))
                r = 0;

            if (r != parseInt(c[10]))
                return false;

            return true;
        }


        function fMasc(objeto, mascara) {
            obj = objeto
            masc = mascara
            setTimeout("fMascEx()", 1)
        }

        function fMascEx() {
            obj.value = masc(obj.value)
        }

        function mCPF(cpf) {
            cpf = cpf.replace(/\D/g, "")
            cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2")
            cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2")
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
            return cpf
        }

        cpfCheck = function(el) {

           /* $.ajax({
                type: "POST",
                url: "<?php echo base_url('portal/Signin_paciente/valida_cadastro'); ?>",
                data: {
                    cpf: el
                },
                success: function(data) {

                    if (data.alert == 'tem_cadastro') {
                        document.getElementById('cpfResponse').innerHTML = '<span style="color:red">CPF Ja cadastrado.</span>';
                    } else if (data.alert == 'nao_tem_cadastro') {
                        document.getElementById('cpfResponse').innerHTML = is_cpf(el.value) ? '<span style="color:green">válido</span>' : '<span style="color:red">inválido</span>';
                        if (el.value == '') document.getElementById('cpfResponse').innerHTML = '';
                    }

                    document.getElementById('cpfResponse').innerHTML = is_cpf(el.value) ? '<span style="color:green">válido</span>' : '<span style="color:red">inválido</span>';
                    if (el.value == '') document.getElementById('cpfResponse').innerHTML = '';
                },
                error: function() {
                    document.getElementById('cpfResponse').innerHTML = '<span style="color:red">CPF Ja cadastrado.</span>';

                }
            });*/
            
                        document.getElementById('cpfResponse').innerHTML = is_cpf(el.value) ? '<span style="color:green">válido</span>' : '<span style="color:red">inválido</span>';
                        if (el.value == '') document.getElementById('cpfResponse').innerHTML = '';
        }
    </script>



    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#address").val("");
                $("#endereco_bairro").val("");
                $("#city").val("");
                $("#state").val("");
            }

            //Quando o campo cep perde o foco.
            $("#zip").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if (validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#address").val("...");
                        $("#endereco_bairro").val("...");
                        $("#city").val("...");
                        $("#state").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#address").val(dados.logradouro);
                                $("#endereco_bairro").val(dados.bairro);
                                $("#city").val(dados.localidade);
                                $("#state").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });
    </script>


<?php exit;
} ?>


</html>