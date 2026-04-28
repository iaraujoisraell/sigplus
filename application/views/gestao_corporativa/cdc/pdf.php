<html>

<head>
    <tittle>CDC#<?php echo $cdc->id; ?></tittle>
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

        main {
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

        .direct-chat-text::after,
        .direct-chat-text::before {
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

        table {
            width: 100%;

            border-collapse: collapse;
        }

        th {
            border-collapse: collapse;
        }



        .impar {

            background-color: rgb(240, 248, 255);

        }

        p {
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
        <br>
        <table border="1" style="text-align: center;">
            <tr>
                <td rowspan="3"><img style="height: 60px;" src="<?php echo $base64; ?>"></td>
                <td rowspan="3">Controle Documental</td>
                <td colspan="3"><?php echo $cdc->codigo; ?></td>
                <!--<td> Linha 1 Céclula 2</td> -->

            </tr>
            <tr>
                <td colspan="3"> Data: <?php echo date('d/m/Y H:i:s'); ?></td>
            </tr>
            <tr>
                <td colspan="3"> Versão: <?php echo $cdc->numero_versao; ?>
            </tr>
        </table>
    </header>

    <!-- <footer>
         Relatório gerado em <?php echo date('Y-m-d H:i:s'); ?> por <?php echo get_staff_full_name(); ?>
         <p>Portal Colaborativo Sigplus - <?php echo $company_name; ?></p>
     </footer>-->

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <br>
        <?php //print_r($cdc);
        ?>
        <table border="1" style="">

            <tr>
                <td style="padding-left: 5px;">Categoria:</td>
                <td style="padding-left: 5px;"> <?php echo $categoria->titulo;
                                                ?></td>
                <td style="padding-left: 5px;"> Departamento:</td>
                <td style="padding-left: 5px;"> <?php echo get_departamento_nome($cdc->setor_id);
                                                ?></td>

            </tr>
        </table>
        <br><!-- comment -->
        <table border="1" style="">

            <tr>
                <td style="padding-left: 5px;">Titulo:</td>
                <td style="padding-left: 5px;"> <?php echo $cdc->titulo; ?></td>
            </tr>
            <!-- <tr>
                <td style="padding-left: 5px;"> Objetivo:</td>
                <td style="padding-left: 5px;"> <?php echo $cdc->descricao; ?></td>

            </tr>-->
            <tr>
                <td style="padding-left: 5px;"> Validade:</td>
                <td style="padding-left: 5px;"> <?php $dataObj = new DateTime(date('Y-m-d'));

                                                $dataObj->modify("+" . $cdc->validity . " months");

                                                echo $dataObj->format('d/m/Y'); ?></td>

            </tr>
            <?php if (count($cdcs) > 0) { ?>
                <tr>
                    <td style="padding-left: 5px;"> Documentos Vinculados:</td>
                    <td style="padding-left: 5px;">
                        <ul>

                            <?php foreach ($cdcs as $doc) { ?>
                                <li>CDC #<?php echo $doc['codigo']; ?></li>
                            <?php } ?>

                        </ul>
                    </td>

                </tr>

            <?php } ?>
        </table>


        <br>
        Informações do Documento:
        <div style="border: 1px solid black;">
            <p><?php echo $cdc->descricao; ?></p>
        </div>
        <br>

         Assinaturas:   

        <?php if ($todos) {  ?>
            <div class="activity-feed" style="border: 1px solid black;">
                <?php foreach ($todos as $um) { //print_r($um); 
                ?>

                    <div class="feed-item row col-md-12" data-sale-activity-id="">
                        <div class="date">
                            <span class="text-has-action" data-toggle="tooltip" data-title="">
                                <?php echo $um['fluxo_nome']; ?> (<span class="bold"><?php echo get_staff_full_name($um['staff_id']); ?></span>):

                                <?php
                                  echo '<span class="text-muted" style="color: #70ffa5" >DOCUMENTO AVALIDADO</span>' . '<br><span class="posttime">' . _d($um['dt_aprovacao']) . '</span><br>';
                                ?>

                            </span>
                        </div>

                    </div>

                <?php
                }
                ?>
            </div>
        <?php }
        ?>


        <?php if (count($campos) > 0) { ?>
            <br>
            <table class="table" border="1">
                <thead>
                    <tr class="clear">
                        <th colspan="2">Informações Adicionais</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($campos as $campo) {
                    ?>
                        <?php
                        if ($campo['tipo_campo'] != 'separador') {
                        ?>
                            <tr class="">

                                <td><?php echo ($campo['nome_campo']); ?></td>
                                <td><?php
                                    echo get_value('cdc', $campo['value'], $campo['tipo_campo'], false);
                                    ?></td>
                            </tr>
                    <?php
                            $i++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>

        <br>
        <?php if ($versions) { ?>
            <hr>
            <div style="margin-left: 20px;"> Versões Anteriores</div>
            <hr>
            <br>
            <?php foreach ($versions as $version) { ?>

                <table class="table" border="1">
                    <thead>
                        <tr class="not-clear">
                            <th><?php echo $version['codigo']; ?> - <?php echo $version['titulo']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="clear">

                            <td>
                                <ul>
                                    <li>Versão: <?php echo $version['numero_versao']; ?></h15>
                                    </li>
                                    <li>Publicação: <?php echo _d($version['data_publicacao']); ?></li>

                                </ul>
                            </td>

                        </tr>

                    </tbody>
                </table>
                <br>
                <!--<ul>
                    
                        <li>WF #<?php echo $wf['id']; ?> - <?php echo $wf['titulo']; ?> <br><h15><?php echo _d($wf['date_created']); ?></h15></li>
                    <
                </ul>-->
            <?php } ?>



        <?php } ?>

    </main>
</body>

</html>