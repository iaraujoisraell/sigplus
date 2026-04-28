<html>
    <head>
    <tittle>CI#<?php echo $ci->id; ?></tittle>
    <style>
        /** Define the margins of your page **/
        @page {
            margin: 100px 50px;
        }

        .pagenum:before {

            content: counter(page);



        }


        header {
            height: 110px;
            width: 100%;
            position: fixed;
            top: -90px;
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
    <header>
        <table border="1" >
            <tr>
                <td rowspan="4" style="text-align: center;"><img style="height: 70px;" src="<?php echo $base64; ?>"></td>
                <td rowspan="4" style="text-align: center;"> COMUNICAÇÃO INTERNA </td>
                <td colspan="3">  Padrão n°: REG-MKT-001</td>
                <!--<td> Linha 1 Céclula 2</td> -->

            </tr>
            <tr>
                <td colspan="3">  Estabelecido em: 12/07/2022</td>
            </tr>
            <tr>
                <td colspan="3">  N° Revisão: 00</td>
            </tr>
            <tr>
                <td colspan="3">  Página: <span class="pagenum"></span></td>
            </tr>
        </table>
    </header>

    <footer>
        Relatório CI <?php
        if ($ci->codigo) {
            echo $ci->codigo;
        } else {
            echo 'CI #' . $CI->id;
        }
        ?> gerado em <?php echo date('d/m/Y H:i:s'); ?>, por <?php echo get_staff_full_name(); ?>
        <p>Portal Colaborativo Sigplus - <?php echo $company_name; ?></p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <br>
        <table border="1" style="">

            <tr>
                <td style="padding: 10px; width: 200px;"> ASSUNTO:</td>
                <td style="padding: 10px; text-transform: uppercase;"> <?php echo $ci->titulo; ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; width: 200px;"> CÓDIGO:</td>
                <td style="padding: 10px; text-transform: uppercase;"> CI <?php
                    if ($ci->codigo) {
                        echo $ci->codigo;
                    } else {
                        echo '#' . $CI->id;
                    }
                    ?></td>
            </tr>
        </table>

        <table border="1" style="border: 0px; ">

            <tr>
                <td style="border: 0px; width: 50%; "> 
                    <p>POR: <?php echo get_staff_full_name($ci->user_create); ?> – <?php echo date("d/m/Y H:i:s", strtotime($ci->dt_created)); ?></p>
                    <?php
                    $this->load->model('Comunicado_model');
                    $sends = $this->Comunicado_model->get_comunicado_send($ci->id);
                    ?>
                    <?php
                    $this->load->model('Comunicado_model');
                    $sends_cc = $this->Comunicado_model->get_comunicado_send($ci->id, '1');

                    if ($ci->docs) {
                        ?>

                        <table border="1" style="border: 0px; ">

                            <tr>
                                <td> 
                                    Anexos
                                </td>
                                <td>
                                    <?php
                                    $docs = explode(",", $ci->docs);
                                    if (count($docs) > 0):
                                        ?>
                                        <?PHP
                                        foreach ($docs as $doc):
                                            echo '<p style="margin-left: 10px;">' . $doc . '</p>';
                                            ?>

                                        <?php endforeach; ?>
                                        <?php
                                    endif;
                                    ?>
                                </td>

                            </tr>

                        </table>
                    <?php } ?>
                </td>

            </tr>

        </table>





        <hr>
        <div class="alert alert-light alert-dismissible">
            <p >DESCRIÇÃO:</p>
            <p><?php echo $ci->descricao; ?></p>
        </div>
        <?php if (count($sends) > 0) { ?>
            <table class="table" border="1" style="text-align: center;">
                <thead >
                    <tr class="not-clear">
                        <th colspan="<?php
                        if ($ci->retorno == 1) {
                            echo '4';
                        } else {
                            echo '3';
                        }
                        ?>">Destinatários do Comunicado</th>
                    </tr>
                    <tr>
                        <th>Colaborador</th>
                        <th>Visualização</th>
                        <th>Ciente</th>
                        <?php if ($ci->retorno == 1) { ?>
                            <th>Resposta</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody >
                    <?php
                    $i = 0;
                    foreach ($sends as $send) {
                        ?>
                        <tr class="<?php
                        if ($i % 2 == 0) {
                            echo 'clear';
                        }
                        ?>">
                            <td><?php echo $send['firstname'] . ' ' . $send['lastname']; ?></td>
                            <td><?php
                                if ($send['dt_ciente']) {
                                    echo date("d/m/Y H:i:s", strtotime($send['dt_ciente']));
                                }
                                ?></td>
                            <td><?php
                                if ($send['dt_read']) {
                                    echo date("d/m/Y H:i:s", strtotime($send['dt_read']));
                                }
                                ?></td>
                            <?php if ($ci->retorno == 1) { ?>
                                <td><?php echo $send['retorno']; ?></td>
                            <?php } ?>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>
        <br>
        <?php if (count($sends_cc) > 0) { ?>
            <table class="table" border="1" style="text-align: center;">
                <thead >
                    <tr class="not-clear">
                        <th colspan="3">Destinatários em cópia</th>
                    </tr>
                    <tr>
                        <th>Colaborador</th>
                        <th>Visualização</th>
                        <th>Ciente</th>
                    </tr>
                </thead>
                <tbody >
                    <?php
                    $i = 0;
                    foreach ($sends_cc as $send) {
                        ?>
                        <tr class="<?php
                        if ($i % 2 == 0) {
                            echo 'clear';
                        }
                        ?>">
                            <td><?php echo $send['firstname'] . ' ' . $send['lastname']; ?></td>
                            <td><?php
                                if ($send['dt_ciente']) {
                                    echo date("d/m/Y H:i:s", strtotime($send['dt_ciente']));
                                }
                                ?></td>
                            <td><?php
                                if ($send['dt_read']) {
                                    echo date("d/m/Y H:i:s", strtotime($send['dt_read']));
                                }
                                ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>




    </main>
</body>
</html>