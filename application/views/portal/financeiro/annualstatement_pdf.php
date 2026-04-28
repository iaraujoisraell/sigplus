<html>
    <head>
    <tittle>Histórico de Pagamentos#<?php echo $ro->id; ?></tittle>
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
                    <img style="height: 70px;" src="<?php echo $base64;         ?>">
                <td>
                <td>

                </td>
                <td style="text-align: left;"> 
                    Declaração Anual de Quitação de Débitos
                    <p>
                        <strong>Nome: </strong><?php echo $client->company; ?> (n° carteirinha: <?php echo $client->numero_carteirinha; ?>)
                    </p>
                    <p>
                        <strong>Data: </strong><?php echo date('d/m/Y'); ?> 
                    </p>
                </td>
            </tr>

        </table>

    </header>

    <footer>
        Declaração Anual de Quitação de Débitos - Portal do cliente (<?php echo $company_name; ?>)
        <p><?php echo date('d/m/Y H:i:s'); ?> - <?php echo $client->company; ?></p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main >
        <br>
        <?php //print_r($client);?><!-- <br><br> -->
        <br>
        Prezado (a) Beneficiário/Usuário <?php echo $client->company; ?> <br>
        Cadastro sob nº <?php echo $client->cd_pessoa; ?><br>
        Inscrito no CPF/MF sob nº <?php echo $client->vat; ?><br>
        <?php echo $client->address; ?>, <?php echo $client->endereco_numero; ?><br>
        <?php echo $client->zip; ?> <?php echo $client->state; ?>
        <br><!-- comment -->
        <br>
        <p>Pela presente a UNIMED DE MANAUS COOPERATIVA DE TRABALHO MEDICO LTDA, entidade inscrita no CNPJ sob 
            nº 04.612.990/0001-70, inscrita na ANS sob nº 311961, estabelecida na Av. Constantino Nery nº 1678, Bairro São 
            Geraldo Manaus-Am, CEP 69050-000, em respeito à legislação vigente em nosso país, declara para os devidos fins 
            deireito, a quitação das faturas abaixo indicadas referentes aos serviços prestados ao 
            beneficiário/usuário acima mencionado, durante o período de 01/01/<?php echo date('Y');?> a 31/12/<?php echo date('Y');?>.
        </p>
        <p>
            Fica expressamente ressalvado o direito à cobrança de eventuais débitos existentes em 
            razão de parcelamento de dívida, débitos discutidos judicialmente e as exceções 
            previstas na Lei Federal 12.007/2009.
        </p>
        <p>
            Esta declaração substitui, para fins de comprovação de pagamento, cumprimento e 
            suspensão da exigibilidade das obrigações contratuais contraídas pelo Beneficiário / 
            Usuário, os recibos de quitações das faturas vencidas, pagas e/ou acordadas no 
            período abaixo discriminado.</p>
        <table class="table" border="1" style="text-align: center;">
            <thead >
                <tr class="clear">
                    <th>Mes</th>
                    <th>Situação</th>

                </tr>
            </thead>
            <tbody >

                <?php
                foreach ($info as $dado) {
                    // print_r($dado); exit;
                    ?>
                    <tr >
                        <td><?php echo $dado['DT_REF']; ?></td>
                        <td><?php echo $dado['DS_MSG']; ?></td>


                    </tr>
                    <?php
                }
                ?>



            </tbody>
        </table>
        <p>
            Atenciosamente, <br><?php echo $company_full_name; ?>.</p>
    </main>
</body>
</html>