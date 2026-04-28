


<?php
$continue = isset($_GET['continue']) ? $_GET['continue'] : '';
$ip = isset($_GET['ip']) ? $_GET['ip'] : '';
$ap_mac = isset($_GET['ap_mac']) ? $_GET['ap_mac'] : '';
$mac = isset($_GET['mac']) ? $_GET['mac'] : '';
$radio = isset($_GET['radio']) ? $_GET['radio'] : '';
$ssid = isset($_GET['ssid']) ? $_GET['ssid'] : '';
$ts = isset($_GET['ts']) ? $_GET['ts'] : '';
$redirect_uri = isset($_GET['redirect_uri']) ? $_GET['redirect_uri'] : '';
$user_hash = isset($_GET['user_hash']) ? $_GET['user_hash'] : '';
$redirect_url = "http://10.0.0.1:2061/cp/itbcaptive.cgi?ts=$ts&user_hash=$user_hash";

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>
            <?php echo $company; ?>
        </title>



        <style>
            .async-hide {
                opacity: 0 !important
            }
        </style>
    </head>
    <body class="bg-gray-200">


        <a href="wifi_2.php"></a>
        <main class="main-content  mt-0">

            <div class="container mb-4">
                <div class="text-center">
                    <a type="submit" class="btn bg-gradient-dark w-100 mt-3 mb-0" href="<?php echo $redirect_url; ?>">Acessar Direto</a>
                    <a type="submit" class="btn bg-gradient-dark w-100 mt-3 mb-0" href="<?php echo base_url('authentication/wifi_olds'); ?>">Acessar por função</a>
                </div>
            </div>
        </main>


       

       

</body>
</html>