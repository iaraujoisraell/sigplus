<html>
    <head>
    <tittle>CDC#<?php echo $cdc->id; ?></tittle>
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
                <td ><img style="height: 70px;" src="<?php echo $base64; ?>"></td>
                <td> CDC <?php echo $cdc->id;?> </td>
                <td> CABEÇALHO </td>
                <!--<td> Linha 1 Céclula 2</td> -->

            </tr>
            
        </table>
    </header>

    <footer>
        TEXTO EXEMPLO TEXTO EXEMPLO TEXTO EXEMPLO
        <p>Portal Colaborativo Sigplus - <?php echo get_staff_full_name(); ?></p>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        

    </main>
</body>
</html>