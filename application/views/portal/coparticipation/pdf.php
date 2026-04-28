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
                    RELATÓRIO DE COPARTICIPAÇÃO
                    <p>
                        <strong>Nome: </strong><?php echo $client->company; ?> (n° carteirinha: <?php echo $client->numero_carteirinha; ?>)
                    </p>
                    <p>
                        <strong>Competência: </strong><?php echo $competencia; ?> 
                    </p>
                </td>
            </tr>

        </table>

    </header>

    

    <!-- Wrap the content of your PDF inside a main tag -->
    <main style="margin-top: 10px;">
      <br>
        <p>

            <strong>Pagador: </strong><?php echo $principal['PAGADOR']; ?> (Contrato: <?php echo $principal['CONTRATO']; ?>) <br>
            <strong>Competência: </strong><?php echo $competencia; ?>     <strong>Valor Total: </strong> R$ <?php echo $total; ?> 
        </p>
        <?php foreach ($info as $carteirinha) { ?>
         
            <table class="table" border="1" style="text-align: center;">
                <thead >
                    <tr class="not-clear">
                        <th colspan="5">Beneficiário: <?php echo $carteirinha[0]['NOME_BENEFICIARIO']; ?> (<?php echo $carteirinha[0]['CARTEIRINHA']; ?>)</th>
                    </tr>
                    <tr class="clear">
                        <th>Item</th>
                        <th>Prestador</th>
                        <th>Data</th>
                        <th>ValorBase</th>
                        <th>Participação</th>
                    </tr>
                </thead>
                <tbody >


                    <?php
                    $valor_base = 0;
                    $valor_cop = 0;
                    foreach ($carteirinha as $item) {
                        ?>
                        <?php $valor_base = $valor_base + str_replace(",", ".", $item['VL_BASE']); ?>
                        <?php $valor_cop = $valor_cop + str_replace(",", ".", $item['VL_COPARTICIPACAO']); ?>

                        <tr class="text-center">

                            <td >

                                <?php echo $item['ITEM']; ?>
                            </td>

                            <td>

                                <?php echo $item['PRESTADOR']; ?>
                            </td>

                            <td>


                                <?php
                                echo $item['DATA_ITEM'];
                                ?>
                            </td>
                            <td >

                                <span class="badge badge-warning">R$ <?php echo $item['VL_BASE']; ?></span>

                            </td>

                            <td >

                                <span class="badge badge-success">R$ <?php echo $item['VL_COPARTICIPACAO']; ?></span>

                            </td>

                        </tr>


                        <?php
                        //$valor = $valor + $item['VL_COPARTICIPACAO'];
                    }
                    ?>
                    <tr >
                        <th  colspan="3" style="text-align: right;">

                            Valor total:  
                        </th>


                        <th >

                            <span class="badge badge-info">R$ <?php echo number_format($valor_base, 2, ',', '.'); ?></span>
                        </th>

                        <th>

                            <span class="badge badge-info">R$ <?php echo number_format($valor_cop, 2, ',', '.'); ?></span>
                        </th>
                    </tr>




                </tbody>
            </table>
        <?php } ?>
    </main>
    <footer>
        RELATÓRIO DE COPARTICIPAÇÃO - Portal do cliente (<?php echo $company_name; ?>)
        <p><?php echo date('d/m/Y H:i:s'); ?> - <?php echo $client->company; ?></p>
    </footer>
</body>
</html>