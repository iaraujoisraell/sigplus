<html>
    <head>
    <tittle></tittle>
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
            bottom: -90px;
            left: 0px;
            right: 0px;
            height: 20px;
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
                    <img style="height: 60px;" src="<?php echo $base64; ?>">
                <td>
                <td>

                </td>
                <td style="text-align: left;"> 
                    RELATÓRIO DE PERDAS POR COOPERADO
                    <p>
                        <strong>Dr(a): </strong><?php echo $client->company; ?> (CRM: <?php echo $client->numero_carteirinha; ?>)
                        <br>
                        <strong>CPF: </strong><?php echo $client->vat; ?>
                        <br>
                        <strong>CÓDIGO: </strong><?php echo $client->cd_pessoa; ?>
                    </p>
                </td>
            </tr>

        </table>
    </header>



    <!-- Wrap the content of your PDF inside a main tag -->
    <main style="margin-top: 10px;">
        <?php foreach ($financial['detailed'] as $title) { ?>

            <table class="table" border="1" style="text-align: center; margin-top: 10px;">
                <thead >
                    <tr class="not-clear">
                        <th colspan="14">PERDAS <?php echo $title['TITULO']; ?>
                    </tr>
                    <tr class="clear">
                        <th colspan="14">  <p><?php echo $title['TITULO2']; ?></p>
                        </th>
                    </tr>
                    <tr class="clear">
                        <th>ANO</th>
                        <?php foreach ($years as $year) { ?>
                            <th><?php echo $year; ?></th>
                        <?php } ?>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody collator_sort_with_sort_keys=text-center>


                    <?php foreach ($title['ANOS'] as $info) { ?>
                        <tr>
                            <td class="text-sm font-weight-normal"><?php echo $info['ANO']; ?></td>
                            <?php foreach ($years as $year) { ?>
                                <td class="text-sm font-weight-normal"><?php echo $info[$year]; ?></td>
                            <?php } ?>
                            <td class="text-sm font-weight-normal">
                                <span class="badge badge-warning"><?php echo $info['TOTAL']; ?></span>
                            </td>

                        </tr>
                    <?php } ?>





                </tbody>
                <tfoot class="clear">
                    <tr >
                        <th  colspan="14">
                            <?php if ($financial['general'][$title['TITULO']] == '0') {
                                ?>
                                SALDO QUITADO
                            <?php
                            } elseif ($financial['general'][$title['TITULO']] < '0') {
                                $receive = str_replace('-', '', $financial['general'][$title['TITULO']]);
                                $_receive = $_receive + $receive;
                                ?>
                                SALDO A RECEBER: <?php echo formatarParaReais($receive); ?>

                            <?php
                            } else {
                                $pay = str_replace('-', '', $financial['general'][$title['TITULO']]);
                                $_pay = $_pay + $pay;
                                ?>
                                SALDO A PAGAR: <?php echo formatarParaReais($pay); ?>
    <?php } ?>





                        </th>
                    </tr>
                </tfoot>
            </table>


<?php } ?>

        <table style="margin-top: 20px; padding: 20px;" >
            <thead>
                <tr>
                    <th>SALDO A PAGAR</th>
                    <th>SALDO A RECEBER</th>
                    <th>SALDO FINAL</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
                <tr>
                    <td>
                        <?php echo formatarParaReais($_pay); ?>
                    </td>
                    <td>
                        <?php echo formatarParaReais($_receive); ?>
                    </td>
                    <td>
<?php echo formatarParaReais($financial['general']['TOTAL']); ?>
                    </td>

                </tr>

            </tbody>
        </table>
    </main>
    <footer>
        RELATÓRIO DE PERDAS - Portal  (<?php echo $company_name; ?>) <?php echo date('d/m/Y H:i:s'); ?> - <?php echo $client->company; ?>
    </footer>
</body>
</html>
<?php

function formatarParaReais($numero) {
    // Converte a string para um número float
    $numero_float = (float) $numero;

    // Formata o número para o formato brasileiro de moeda
    $numero_formatado = number_format($numero_float, 2, ',', '.');

    // Adiciona o símbolo de R$ ao início da string
    return 'R$ ' . $numero_formatado;
}
?>