<html>

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <?php

    $this->load->model('Documento_model');



    $data = $this->Documento_model->get_conteudo($documento);

    $fluxos = $this->Documento_model->get_fluxos_by_docid($documento);

    //print_r($fluxos); exit;

    //echo $data->cabecalho;

    //echo $data->conteudo;

    //echo $data->rodape;

    ?>



    <style>



        @page {





        }

        

        .header{

            width: 100%;

            text-align: center;

            position: fixed;

            top: 0px;

            border-bottom: 1px solid black;

            background-color: white;

        }



        .footer {

            width: 100%;

            text-align: center;

            position: fixed;

            bottom: 0px;

            border-top: 1px solid black;

            background-color: white;

        }

        .conteudo {

            border-top: 1px solid black;

            border-bottom: 1px solid black;

            border-left: 1px solid black;

            border-right: 1px solid black;

        }

        .pagenum:before {

            content: counter(page);



        }

        table{

            text-align: center;

            width: 100%;

            border-collapse: collapse;

            margin: auto;



        }



        td{

            border-top: 1px solid black;

            border-bottom: 1px solid black;

            border-left: 1px solid black;

            border-right: 1px solid black;

            padding: 5px;

            text-align: center;



        }



        .impar{

            background-color: rgb(240,248,255);

        }



    </style>

    <body>

        <div class="footer">

            <?php

            if ($data->rodape) {

                echo $data->rodape;

            } else {

                ?> Página <span class="pagenum"></span> <?php } ?>

        </div>

        

        

        <div class="body" style="overflow: hidden; width: 100%;">

            <div class="cabecalho">

                <?php

                echo $data->cabecalho;

                //echo $data->rodape;

                ?>

            </div>

            <div class="ob">

                <div style=" margin-bottom: 10px;">

                    <table>

                        <tbody>



                            <tr class="">

                                <td >

                                    <h5>

                                        <?php echo $data->codigo ?>

                                    </h5>

                                </td>

                                <td>

                                        <?php echo $data->titulo ?>

                                    

                                </td>



                            </tr>

                            <tr >

                                <td>

                                    <h5>

                                        OBJETIVO:

                                    </h5>

                                </td>

                                <td style="text-align: justify; ">

                                        <?php echo $data->descricao ?>

                                 

                                </td>



                            </tr>





                        </tbody>







                    </table>



                </div>

            </div>



            <div class="conteudo" style="padding: 10px;" style="width: 100%;">

                <?php

                echo $data->conteudo;

                //echo $data->rodape;

                ?>

            </div>



            <div style=" margin-top: 10px;">

                <table>

                    <thead>

                        <tr>



                            <?php foreach ($fluxos as $fluxo): ?>

                                <td><?php echo $fluxo['fluxo_nome']; ?></td>

                            <?php endforeach; ?>

                        </tr>

                    </thead>

                    <tbody>



                        <tr class="impar">



                            <?php foreach ($fluxos as $fluxo): ?>

                                <td>

                                    <?php echo $fluxo['firstname'] . ' ' . $fluxo['lastname']; ?> - <?php echo $fluxo['name']; ?>



                                    <p>

                                        <?php

                                        if ($fluxo['ip']) {

                                            echo $fluxo['ip'];

                                        } else {

                                            echo 'IP não identificado';

                                        }

                                        ?>

                                    </p>

                                    <p>

                                        <?php

                                        if ($fluxo['dt_aprovacao']) {

                                            echo date("d/m/Y H:i:s", strtotime($fluxo['dt_aprovacao']));

                                        } else {

                                            echo 'Sem data';

                                        }

                                        ?>

                                    </p>

                                </td>

                            <?php endforeach; ?>



                        </tr>





                    </tbody>







                </table>



            </div>

            <h4 style="text-align: center;">Data de publicação: <?php

                if ($docuemnto->data_publicacao) {

                    echo $docuemnto->data_publicacao;

                } else {

                    echo 'Ainda não publicado';

                }

                ?></h4>



        </div>



    </body>

    <div class="header">



        </div>

</html>



