<html>
    <head>
        <style>
            @page {
                margin: 100px 50px;
            }

            .pagenum:before {

                content: counter(page);



            }


            header {
                height: 10px;
                width: 100%;
                position: fixed;
                top: -80px;
                left: 0;
                right: 0;
                margin: auto;
            }

            footer {
                position: fixed;
                bottom: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
                color: #818182;
                width: 100%;

                text-align: right;
            }


            main{
                position: relative;
                margin: auto;
            }

            .direct-chat-infos {
                display: block;
                font-size: 0.875rem;
                margin-bottom: 2px;
            }

            .direct-chat-name {
                font-weight: 600;
            }

            .direct-chat-timestamp {
                color: #697582;
            }

            .direct-chat-messages {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
                height: 250px;
                overflow: auto;
                padding: 10px;
            }



            .direct-chat-text {
                border-radius: 0.3rem;
                background-color: #d2d6de;
                border: 1px solid #d2d6de;
                color: #444;
                padding: 5px 10px;
                position: relative;
            }

            .direct-chat-text::after, .direct-chat-text::before {
                border: solid transparent;
                border-right-color: #d2d6de;
                content: " ";
                height: 0;
                pointer-events: none;
                position: absolute;
                right: 100%;
                top: 15px;
                width: 0;
            }


            .direct-chat-text::before {
                border-width: 6px;
                margin-top: -6px;
            }

            .pagenum:before {

                content: counter(page);
            }

            table{
                width: 100%;

                border-collapse: collapse;
            }

            th{
                border-collapse: collapse;
            }



            .impar{

                background-color: rgb(240,248,255);

            }

            p{
                font: 14px/15px sans-serif;
            }

            .alert {
                position: relative;
                margin-bottom: 1rem;
                border: 1px solid transparent;
                border-radius: 0.25rem;
            }


            .alert-success {
                color: #155724;
                background-color: #d4edda;
                border-color: #c3e6cb;
            }

            .alert-cinza {
                background-color: #F5FFFA;
                font: 14px/15px sans-serif;
            }

            .alert-light {
                font: 14px/15px sans-serif;
                background-color: #fefefe;
                border-color: #fdfdfe;
            }

            .clear {
                background-color: #F8F8FF;
            }
            .not-clear {
                background-color: #66CDAA;
            }

        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header >
            <table  style="text-align: center; border-radius: 0px; ">
                <tr>
                    <td>
                        <img style="height: 70px;" src="<?php echo $base64; ?>">
                    <td>
                    <td>

                    </td>
                    <td style="text-align: left;"> 
                        IMPOSTO DE RENDA PESSOA FÍSICA <?php echo $ano; ?>
                        <p>
                            <strong>Nome: </strong><?php echo $info_principal->NM_CLIENTE; ?> (n° carteirinha: <?php echo $client->numero_carteirinha; ?>)<br><!-- comment -->

                            <strong>Endereço: </strong><?php echo $info_principal->DS_RUA; ?>, <?php echo $info_principal->DS_COMPLEMENTO; ?> <br> 
                            <strong>CPF: </strong><?php echo $info_principal->CD_CPF_CNPJ; ?> <br>
                            <strong>Codigo Titular: </strong><?php echo $info_principal->CD_USUARIO_PLANO; ?>
                        </p>
                    </td>
                </tr>

            </table>

        </header>



        <!-- Wrap the content of your PDF inside a main tag -->
        <main style="margin-top: 10px;">
            <br>
            <br>
            <p>
                <strong><?php echo $info_principal->NM_FANTASIA ?></strong> <br>
                <strong>CNPJ: </strong><?php echo $info_principal->CNPJ; ?> <br>
                <strong>Endereço: </strong><?php echo $info_principal->DS_ENDERECO; ?>, <?php echo $info_principal->NR_ENDERECO; ?> - <?php echo $info_principal->CD_CEP; ?> <?php echo $info_principal->DS_MUNICIPIO; ?> <?php echo $info_principal->SG_ESTADO; ?><br><!-- comment -->
                <strong>Fone: </strong><?php echo $info_principal->FONE; ?> <br>
                <strong>Site: </strong><?php echo $info_principal->DS_SITE_INTERNET; ?> <br>
            </p>


            <table class="table" border="1" style="text-align: center;">
                <thead >
                    <tr class="not-clear">
                        <th colspan="5">RELATÓRIO</th>
                    </tr>
                    <tr class="clear">
                        <th>Referência</th>
                        <th>Carteirinha</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Titularidade</th>
                    </tr>
                </thead>
                <tbody >


                    <?php foreach ($info_meses as $info) { ?>
                        <tr class="text-center">

                            <td >

                                <?php echo $info->COMPETENCIA; ?>
                            </td>

                            <td>

                                <?php echo $info->CARTEIRA; ?>
                            </td>

                            <td>

                                <?php echo $info->NOME; ?>
                            </td>
                            <td >
                                <?php echo $info->VALOR; ?>

                            </td>

                            <td >

                                <span class="badge badge-<?php
                                                                    if ($info->TITULARIDADE == 'Dependente') {
                                                                        echo 'warning';
                                                                    } elseif ($info->TITULARIDADE == 'Titular') {
                                                                        echo 'info';
                                                                    }
                                                                    ?>"><?php echo $info->TITULARIDADE; ?></span>

                            </td>

                        </tr>


                        <?php
                        //$valor = $valor + $item['VL_COPARTICIPACAO'];
                    }
                    ?>




                </tbody>
            </table>
            <br>
            <table class="table" border="1" style="text-align: center;">
                <thead >
                    <tr class="not-clear">
                        <th colspan="4">RESUMO INDIVIDUAL</th>
                    </tr>
                    <tr class="clear">
                        <th>Carteirinha</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Titularidade</th>
                    </tr>
                </thead>
                <tbody >


                     <?php foreach ($info_total as $info) { ?>
                        <tr class="text-center">

                            <td >

                                <<?php echo $info->CARTEIRA; ?>
                            </td>

                            <td>

                                <?php echo $info->NOME; ?>
                            </td>

                            <td>

                                <?php echo $info->VALOR; ?>
                            </td>

                            <td >

                               <?php echo $info->TITULARIDADE; ?>
                            </td>

                        </tr>
                        


                        <?php
                        //$valor = $valor + $item['VL_COPARTICIPACAO'];
                    }
                    ?>
                         <tr class="text-center">

                            <td colspan="2">

                                VALOR TOTAL
                            </td>

                            <td colspan="2">

                                <?php echo $info_total_gastos->TOTAL; ?>
                            </td>


                        </tr>




                </tbody>
            </table>
        </main>
        <footer>
            IMPOSTO DE RENDA PESSOA FÍSICA - Portal do cliente 
            <p><?php echo date('d/m/Y H:i:s'); ?> - <?php echo $client->company; ?></p>
        </footer>
    </body>
</html>