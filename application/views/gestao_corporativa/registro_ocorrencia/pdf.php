<html>
    <head>
    <tittle>RO#<?php echo $ro->id; ?></tittle>
    <style>
        /** Define the margins of your page **/
        @page {
            margin: 100px 50px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
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
            margin-top: 50px;
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
    </style>
</head>
<body>
    <!-- Define header and footer blocks before your content -->
    <header>
        <table border="1" style="text-align: center;">
            <tr>
                <td rowspan="4"><img style="height: 70px;" src="<?php echo $base64; ?>"></td>
                <td rowspan="4"> REGISTRO DE OCORRÊNCIA </td>
                <td colspan="3"> Padrão N°: <?php echo $ro->head_1; ?></td>
                <!--<td> Linha 1 Céclula 2</td> -->

            </tr>
            <tr>
                <td colspan="3"> Estabelecido em: <?php echo $ro->head_2; ?></td>
            </tr>
            <tr>
                <td colspan="3"> Revisão: <?php echo $ro->head_3; ?></td>
            </tr>
            <tr>
                <td colspan="3"> Pagina: <span class="pagenum"></span></td>
            </tr>
        </table>
    </header>

    <footer>
        Relatório registrado em <?php echo date('Y-m-d H:i:s'); ?>, por <?php echo get_staff_full_name(); ?>
        <p>Portal Colaborativo Sigplus - <?php echo $company_name; ?></p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <table border="1" style="">

            <tr>
                <td style="padding: 10px; width: 200px;"> CATEGORIA:</td>
                <td style="padding: 10px; text-transform: uppercase;"> <?php echo $ro->titulo; ?></td>
            </tr>
            <tr>
                <td style="padding: 10px; width: 200px;"> SETOR RESPONSÁVEL:</td>
                <td style="padding: 10px; text-transform: uppercase;"> <?php echo $ro->name; ?></td>
            </tr>
        </table>

        <br>
        <p style="font-weight: bold; font-size: 15px;">#<?php echo $ro->id; ?> – <?php echo $ro->subject; ?></p><!-- comment -->
        <table border="1" style="border: 0px; ">

            <tr>
                <td style="border: 0px; width: 50%; "> 
                    <p>Notificante: <?php
                        if ($ro->anonimo == 1) {
                            echo 'ANÔNIMO';
                        } else {
                            echo $ro->firstname . ' ' . $ro->lastname;
                        }
                        ?> – <?php echo $ro->date_created; ?></p>
                    <p>Responsável: <?php echo get_staff_full_name($ro->atribuido_a); ?> - <?php echo $ro->date_atribuido_a; ?></p>
                    <?php $status = get_ro_status($ro->status); ?>
                    <p>Estágio: <span class="label inline-block" style="border:1px solid <?php echo $status['color']; ?>; color:<?php echo $status['color']; ?>;"><?php echo $status['label']; ?></span></p>
                </td>
                <td style="border: 0px; width: 50%;"> 
                    <p>Data do ocorrido: <?php echo $ro->date; ?></p>
                    <p>Validade: <?php echo $ro->validade; ?></p>
                    <p>Prioridade: <?php if ($ro->priority == 1) { ?>
                            Baixa
                        <?php } elseif ($ro->priority == 2) { ?>
                            Média
                        <?php } else { ?>
                            Alta
                        <?php } ?></p>
                </td>
            </tr>

        </table>
        <div class="alert alert-light alert-dismissible">
            <p style="font-weight: bold; font-size: 15px;">Relato Detalhado:</p>
            <p><?php echo $ro->report; ?></p>
        </div>

        <hr>
        <?php
        $quantidade = count($ro_values_notificante);
        if ($quantidade % 2 != 0) {
            $quantidade++;
        }
        $dividindo = intdiv($quantidade, 2);
        $ro_values_notificante = array_chunk($ro_values_notificante, $dividindo);
        ?>
        <p style="text-transform: uppercase; font-size: 15px; margin-top: 5px; font-weight: bold;">FORMULÁRIO DE ABERTURA</p>

        <table border="1" style="border: 0px; ">

            <tr>
                <td style="border: 0px; width: 50%;"> 
                    <?php foreach ($ro_values_notificante[0] as $campo) { ?>
                        <?php
                        if ($campo['tipo_campo'] == 'separador') {
                            //echo '<h6 style="padding-left: 10px;"><strong>' . strtoupper($campo['nome_campo']) . '</strong></h6> ';
                        } else {
                            ?>
                            <p class="text-muted col-md-6" style="margin-top: 5px; text-transform: uppercase;">
                                <?php echo $campo['nome_campo']; ?>: <?php
                                if ($campo['tipo_campo'] == 'multiselect' || $campo['tipo_campo'] == 'select') {
                                    $values = explode(',', $campo['value']);
                                    $this->load->model('Registro_ocorrencia_model');
                                    for ($i = 0; $i < count($values); $i++) {
                                        $row = $this->Registro_ocorrencia_model->get_option($values[$i]);
                                        $values[$i] = $row->option;
                                    }
                                    echo implode(', ', $values);
                                } elseif ($campo['tipo_campo'] == 'setores') {

                                    if ($campo['value']) {
                                        echo get_departamento_nome($campo['value']);
                                    }
                                } elseif ($campo['tipo_campo'] == 'funcionarios') {
                                    if ($campo['value']) {
                                        echo get_staff_full_name($campo['value']);
                                    }
                                } else {
                                    echo $campo['value'];
                                }
                            }
                            ?>
                        </p>
                    <?php } ?>
                </td>
                <td style="border: 0px; width: 50%;"> 
                    <?php foreach ($ro_values_notificante[1] as $campo) { ?>
                        <?php
                        if ($campo['tipo_campo'] == 'separador') {
                            //echo '<h6 style="padding-left: 10px;"><strong>' . strtoupper($campo['nome_campo']) . '</strong></h6>';
                        } else {
                            ?>
                            <p class="text-muted col-md-6" style="margin-top: 5px; text-transform: uppercase;">
                                <?php echo $campo['nome_campo']; ?>: <?php
                                if ($campo['tipo_campo'] == 'multiselect' || $campo['tipo_campo'] == 'select') {
                                    $values = explode(',', $campo['value']);
                                    $this->load->model('Registro_ocorrencia_model');
                                    for ($i = 0; $i < count($values); $i++) {
                                        $row = $this->Registro_ocorrencia_model->get_option($values[$i]);
                                        $values[$i] = $row->option;
                                    }
                                    echo implode(', ', $values);
                                } elseif ($campo['tipo_campo'] == 'setores') {

                                    if ($campo['value']) {
                                        echo get_departamento_nome($campo['value']);
                                    }
                                } elseif ($campo['tipo_campo'] == 'funcionarios') {
                                    if ($campo['value']) {
                                        echo get_staff_full_name($campo['value']);
                                    }
                                } else {
                                    echo $campo['value'];
                                }
                            }
                            ?>
                        </p>
                    <?php } ?>
                </td>
            </tr>

        </table>
        <?php if ($atendimento == true) { ?>

            <hr>

            <p style="text-transform: uppercase; font-size: 15px; margin-top: 5px;">#ATENDIMENTO VINCULADO<br>
                PROTOCOLO: <?php echo $ra->protocolo; ?><br>
                CATEGORIA DO RA: <?php echo $ra->titulo; ?><br>
                CONTATO(CELULAR): <?php echo $ra->contato; ?><br>
                EMAIL: <?php echo $ra->email; ?></p>
            <?php if (is_array($info_client)) { ?>
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
        <?php } ?>

        <!--
        <?php foreach ($atuantes as $atuante) {
            ?>
            <hr>
            <p style="text-transform: uppercase; font-size: 15px; margin-top: 5px;"><?php echo $atuante['titulo']; ?> <span style="font-size: 10px;">(prazo: <?php echo $atuante['prazo']; ?>)</span></p>
            <?php
            $quantidade = count($atuante['campos']);
            if ($quantidade > 0) {
                if ($quantidade % 2 != 0) {
                    $quantidade++;
                }
                $dividindo = intdiv($quantidade, 2);
                $atuante['campos'] = array_chunk($atuante['campos'], $dividindo);
                ?>

                    <table border="1" style="border: 0px; ">

                        <tr>
                            <td style="border: 0px; width: 50%;"> 
                <?php foreach ($atuante['campos'][0] as $campo) { ?>
                    <?php
                    if ($campo['tipo_campo'] == 'separador') {
                        // echo '<h6 style="padding-left: 10px;"><strong>' . strtoupper($campo['nome_campo']) . '</strong></h6> ';
                    } else {
                        ?>
                                                <p class="text-muted col-md-6" style="margin-top: 5px; text-transform: uppercase;">
                        <?php echo $campo['nome_campo']; ?>: <?php
                        if ($campo['tipo_campo'] == 'multiselect' || $campo['tipo_campo'] == 'select') {
                            $values = explode(',', $campo['value']);
                            $this->load->model('Registro_ocorrencia_model');
                            for ($i = 0; $i < count($values); $i++) {
                                $row = $this->Registro_ocorrencia_model->get_option($values[$i]);
                                $values[$i] = $row->option;
                            }
                            echo implode(', ', $values);
                        } elseif ($campo['tipo_campo'] == 'setores') {

                            if ($campo['value']) {
                                echo get_departamento_nome($campo['value']);
                            }
                        } elseif ($campo['tipo_campo'] == 'funcionarios') {
                            if ($campo['value']) {
                                echo get_staff_full_name($campo['value']);
                            }
                        } else {
                            echo $campo['value'];
                        }
                    }
                    ?>
                                        </p>
                <?php } ?>
                            </td>
                            <td style="border: 0px; width: 50%;"> 
                <?php foreach ($atuante['campos'][1] as $campo) { ?>
                    <?php
                    if ($campo['tipo_campo'] == 'separador') {
                        // echo '<h6 style="padding-left: 10px;"><strong>' . strtoupper($campo['nome_campo']) . '</strong></h6> ';
                    } else {
                        ?>
                                                <p class="text-muted col-md-6" style="margin-top: 5px; text-transform: uppercase;">
                        <?php echo $campo['nome_campo']; ?>: <?php
                        if ($campo['tipo_campo'] == 'multiselect' || $campo['tipo_campo'] == 'select') {
                            $values = explode(',', $campo['value']);
                            $this->load->model('Registro_ocorrencia_model');
                            for ($i = 0; $i < count($values); $i++) {
                                $row = $this->Registro_ocorrencia_model->get_option($values[$i]);
                                $values[$i] = $row->option;
                            }
                            echo implode(', ', $values);
                        } elseif ($campo['tipo_campo'] == 'setores') {

                            if ($campo['value']) {
                                echo get_departamento_nome($campo['value']);
                            }
                        } elseif ($campo['tipo_campo'] == 'funcionarios') {
                            if ($campo['value']) {
                                echo get_staff_full_name($campo['value']);
                            }
                        } else {
                            echo $campo['value'];
                        }
                    }
                    ?>
                                        </p>
                <?php } ?>
                            </td>
                        </tr>

                    </table>
            <?php } ?>
        <?php } ?>
        <?php if (count($answers) > 0) { ?>

            <hr>
            <p style="text-transform: uppercase; font-size: 15px; margin-top: 5px;">Respostas</p>

            <div class="direct-chat-messages">

            <?php foreach ($answers as $answer) { ?>
                        <div class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left"><?php echo get_staff_full_name($answer['user_created']); ?></span>
                                <span class="direct-chat-timestamp float-right"><?php echo $answer['data_created']; ?></span>
                            </div>
                            <div class="direct-chat-text">
                <?php echo $answer['note']; ?>
                            </div>

                        </div>
            <?php } ?>


            </div>
        <?php } ?>-->
    </main>
</body>
</html>