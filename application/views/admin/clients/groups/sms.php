<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
    <h4 class="customer-profile-group-heading">SMS</h4>
    <?php
    $dados['just_table'] = true;
    $dados['rel_type'] = 'client';
    $dados['rel_id'] = $client->userid;
    $dados['client_id'] = $client->userid;
            $dados['phonenumber'] = $client->phonenumber;
    $this->load->view('gestao_corporativa/Sms/index', $dados);
    ?>

<?php } ?>


