<head>

    <?php
    $logo = get_company_option('', 'company_logo');
    $icon = get_company_option('', 'favicon');
    $company = get_company_option('', 'companyname');
    ?>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo $this->security->get_csrf_hash(); ?>" />
    <meta name="description" content="Portal do cliente - <?php echo htmlspecialchars($company, ENT_QUOTES); ?>" />

    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/portal/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>uploads/company/<?php echo $icon; ?>">
    <title><?php echo $tittle; ?></title>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="<?php echo base_url(); ?>assets/portal/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/portal/css/nucleo-svg.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="<?php echo base_url(); ?>assets/portal/css/material-dashboard.min.css?v=3.0.6" rel="stylesheet" />

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <script>
        function save_service(key) {
            var caminho = "<?php echo base_url("portal/profile/save_service/") ?>";
            $.ajax({
                type: "POST",
                url: caminho,
                data: {
                    key: key,
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                }
            });
        }
    </script>

    <style>
        table {
            text-align: center;
        }
    </style>
</head>
