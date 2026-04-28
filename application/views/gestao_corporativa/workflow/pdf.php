<html>
    <head>
    <tittle>WF#<?php echo $workflow->id; ?></tittle>
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
                <td style="text-align: center; max-width: 200px;" rowspan="2"><img style="height: 70px;" src="<?php echo $base64; ?>"></td>
                <td style="text-align: center;"> <?php echo $categoria->titulo; ?> </td>


            </tr>
            <tr>
                
                <td style="text-align: center;"> Workflow #<?php echo $workflow->id; ?> </td>

<!--<td> Linha 1 Céclula 2</td> -->

            </tr>
        </table>
    </header>

    <footer>
        WF #<?php echo $workflow->id; ?> gerado em <?php echo date('d/m/Y H:i:s'); ?> por <?php echo get_staff_full_name(); ?>
        <p>Portal Colaborativo Sigplus - <?php echo $company_name; ?></p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>


        <?php if ($workflow->registro_atendimento_id) { ?>
            <table border="1" style="">
                <tr>
                    <td style="padding-left: 5px;"> Protocolo:</td>
                    <td style="padding-left: 5px;"> <?php echo $atendimento->protocolo; ?> ( Atendimento #<?php echo $atendimento->id; ?> )</td>
                </tr>
            </table> 
            <br>
        <?php } ?>

        <table border="1" style="">

            <tr>
                <td style="padding-left: 5px; width: 200px;"> Solicitante:</td>
                <td style="padding-left: 5px;"> <?php
                    if ($workflow->user_created) {
                        echo get_staff_full_name($workflow->user_created) . ' (Colaborador)';
                    } else {
                        echo get_company_name($workflow->client_id) . ' (Via Portal do Cliente)';
                    }
                    ?></td>
            </tr>
            <tr>
                <td style="padding-left: 5px;"> Data de solicitação:</td>
                <td style="padding-left: 5px;"> <?php echo date("d/m/Y", strtotime($workflow->date_created)); ?></td>
            </tr>
            <tr>
                <td style="padding-left: 5px;"> Data de prazo:</td>
                <td style="padding-left: 5px;"> <?php
                    if ($workflow->date_prazo) {
                        echo _d($workflow->date_prazo);
                    } else {
                        echo 'Não Informado';
                    }
                    ?></td>
            </tr>
            <tr>
                <td style="padding-left: 5px;"> Data de Conclusão:</td>
                <td style="padding-left: 5px;"> <?php
                    if ($workflow->date_end) {
                        echo date("d/m/Y", strtotime($workflow->date_end));
                    } else {
                        echo 'Não Concluído';
                    }
                    ?></td>
            </tr>
            <?php if ($in_department == true) { ?>
                <tr>
                    <td style="padding-left: 5px;"> Status:</td>
                    <?php $status = get_ro_status($workflow->status); ?>
                    <td style="padding-left: 5px;color: <?php echo $status['color']; ?>;"> <?php echo $status['label']; ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php if (is_array($info_client)) { ?>
            <br>
            <table class="table" border="1">
                <tbody >
                    <tr class="clear">
                        <td colspan="2"><strong>Cliente Vinculado</strong></td>
                    </tr>
                    <tr class="">
                        <td>NOME/CARETEIRINHA:</td>
                        <td><?php echo $info_client['NOME_ABREV'] . ' - ' . $info_client['NUMERO_CARTEIRINHA']; ?></td>
                    </tr>
                    <tr class="">
                        <td>EMAIL/TELEFONE:</td>
                        <td><?php echo $info_client['EMAIL'] . ' - ' . $info_client['TELEFONE']; ?></td>
                    </tr>
                    <tr class="">
                        <td>CONTRATANTE:</td>
                        <td><?php echo $info_client['CONTRATANTE']; ?> - <?php echo $info_client['CPF_CONTRATANTE']; ?></td>
                    </tr>
                    <tr class="">
                        <td>CPF:</td>
                        <td><?php echo $info_client['CPF']; ?></td>
                    </tr>
                    <tr class="">
                        <td>DATA DE NASCIMENTO:</td>
                        <td><?php echo $info_client['DATADENASCIMENTO']; ?></td>
                    </tr>
                    <tr class="">
                        <td>TITULAR:</td>
                        <td><?php echo $info_client['TITULAR']; ?></td>
                    </tr>
                    <tr class="">
                        <td>SITUAÇÃO:</td>
                        <td><?php echo $info_client['SITUACAO']; ?></td>
                    </tr>

                </tbody>
            </table>
        <?php } ?>
        <br><!-- comment -->
        <table class="table" border="1">
            <thead >
                <tr class="not-clear">
                    <th colspan="2">Formulário Inicial da Solicitação</th>
                </tr>
            </thead>
            <tbody >
                <?php
                $i = 0;
                foreach ($campos as $campo) {
                    ?>
                    <?php
                    if ($campo['tipo_campo'] != 'separador') {
                        ?>
                        <tr class="<?php
                        if ($i % 2 == 0) {
                            echo 'clear';
                        }
                        ?>">

                            <td><?php echo($campo['nome_campo']); ?></td>
                            <td><?php
                                echo get_value('workflow', $campo['value'], $campo['tipo_campo'], false);
                                ?></td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>

        <?php if (count($campos_external) > 0) { ?>
            <br>
            <table class="table" border="1">
                <tbody >
                    <tr class="clear">
                        <td colspan="2"><strong>SIC (Solicitações de Informações Complementares)</strong></td>
                    </tr>
                    <tr class="clear">
                        <td><strong>Solicitação</strong></td>
                        <td><strong>Resposta</strong></td>
                    </tr>

                    <?php foreach ($campos_external as $campo) { ?>
                        <tr class="">
                            <td><?php echo($campo['nome_campo']); ?></td>
                            <td><?php
                                echo get_value('workflow', $campo['value'], $campo['tipo_campo'], false);
                                ?></td>
                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        <?php } ?>
        <?php if (count($internals) > 0) { ?>
            <br>
            <table class="table" border="1">
                <tbody >
                    <tr class="clear">
                        <td colspan="5"><strong>SPI (Solicitações de Parecer Interno)</strong></td>
                    </tr>
                    <tr class="clear">
                        <td><strong>De</strong></td>
                        <td><strong>Para</strong></td>
                        <td><strong>Solicitação</strong></td>
                        <td><strong>Status</strong></td>
                        <td><strong>Resposta</strong></td>
                    </tr>

                    <?php foreach ($internals as $internal) { ?>
                        <tr class="">
                            <td><?php echo get_staff_full_name($internal['user_created']); ?></td>
                            <td><?php echo get_staff_full_name($internal['staffid']); ?></td>
                            <td><?php echo $internal['description']; ?></td>
                            <td><?php if ($internal['status'] == 0) { ?>
                                                  AGUARDANDO
                                                <?php } else { ?>
                                                    RESPONDIDO
                                                <?php } ?><br></td>
                            <td><?php
                            $campos = $this->Categorias_campos_model->get_values($internal['id'], 'internal_request_workflow');
                            foreach($campos as $campo){
                                
                                echo $campo['nome_campo'].': '.get_value('workflow', $campo['value'], $campo['tipo_campo']).'</br>';
                            }
                                ?></td>
                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        <?php } ?>

        <?php if (count($sms) > 0) { ?>
            <br>
            <table class="table" border="1">
                <tbody >
                    <tr class="clear">
                        <td colspan="4"><strong>SMS WORKFLOW</strong></td>
                    </tr>
                    <tr class="clear">
                        <td><strong>Data</strong></td>
                        <td><strong>Mensagem</strong></td>
                        <td><strong>Destino</strong></td>
                        <td><strong>Situação</strong></td>
                    </tr>

                    <?php foreach ($sms as $s) { ?>
                        <tr class="">
                            <td><?php echo _dt($s['data_registro']) . '<br>' . $s['firstname'] . ' ' . $s['lastname']; ?></td>
                            <td> <?php echo $s['mensagem']; ?></td>
                            <td><<?php echo $s['phone_destino']; ?></td>
                            <td><?php if ($s['status'] == 0) { 
                                                echo  'AGUARDANDO';
                                                 } elseif ($s['status'] == 1) {
                                                    echo 'ENVIADO';
                                                  } else  { 
                                                   echo 'NÃO ENVIADO';
                                             } ?><br></td>
                        
                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        <?php } ?>

        <?php if (count($email) > 0) { ?>
            <br>
            <table class="table" border="1">
                <tbody >
                    <tr class="clear">
                        <td colspan="4"><strong>EMAILS WORKFLOW</strong></td>
                    </tr>
                    <tr class="clear">
                        <td><strong>Data</strong></td>
                        <td><strong>Mensagem</strong></td>
                        <td><strong>Destino</strong></td>
                        <td><strong>Situação</strong></td>
                    </tr>

                    <?php foreach ($email as $e) { ?>
                        <tr class="">
                            <td><?php echo _dt($e['data_registro']) . '<br>' . $e['firstname'] . ' ' . $e['lastname']; ?></td>
                            <td><?php echo $e['mensagem']; ?></td>
                            <td><?php echo $e['email_destino']; ?></td>
                            <td><?php if (!$e['enviado']) { 
                                                echo  'AGUARDANDO';
                                                 } elseif ($e['enviado'] == 'S') {
                                                    echo 'ENVIADO';
                                                  } elseif ($e['enviado'] == 'N') {
                                                   echo 'NÃO ENVIADO';
                                             } ?><br></td>
                        
                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        <?php } ?>
        <?php //if ($in_department == true) { ?>
            <br>
            <?php if (count($fluxos) > 0 and $fluxos[0]['atribuido_a'] != '') { ?>


                <?php foreach ($fluxos as $fluxo) { ?>
                    <hr>
                    <div style="margin-left: 20px;"><?php echo $fluxo['fluxo_sequencia']; ?>° - <?php echo $fluxo['setor_name']; ?> (<?php
                        if ($fluxo['atribuido_a']) {
                            echo get_staff_full_name($fluxo['atribuido_a']);
                        } else {
                            echo 'Aguardando';
                        }
                        ?>)</div>
                    <hr>
                    <table  style="border: 0px; ">

                        <tr>
                            <td style="padding: 5px;">Recebido: <?php echo date("d/m/Y", strtotime($fluxo['date_created'])); ?></td>
                            <td style="padding: 5px; ">Previsão: <?php echo date("d/m/Y", strtotime($fluxo['data_prazo'])); ?></td>
                            <td style="padding: 5px;">Assumido: <?php
                                if ($fluxo['data_assumido']) {
                                    echo date("d/m/Y", strtotime($fluxo['data_assumido']));
                                } else {
                                    echo 'Aguardando';
                                }
                                ?></td>
                            <td style="padding: 5px; ">Concluído: <?php
                                if ($fluxo['data_concluido']) {
                                    echo date("d/m/Y", strtotime($fluxo['data_concluido']));
                                } else {
                                    echo 'Não Concluído';
                                }
                                ?></td>
                        </tr>

                    </table>
                    <?php if (count($fluxo['values']) > 0) { ?>
                        <table class="table" border="1">
                            <tbody >
                                <?php
                                $i = 0;
                                foreach ($fluxo['values'] as $campo) {
                                    ?>
                                    <?php
                                    if ($campo['tipo_campo'] != 'separador') {
                                        ?>
                                        <tr class="<?php
                                        if ($i % 2 == 0) {
                                            echo 'clear';
                                        }
                                        ?>">

                                            <td><?php echo($campo['nome_campo']); ?></td>
                                            <td><?php
                                                echo get_value('workflow', $campo['value'], $campo['tipo_campo'], false);
                                                ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <?php
                    }
                }
                ?>
            <?php } ?>
            <br>
        <?php //} ?>










    </main>
</body>
</html>