<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
// Carrega dependências usadas pela navbar da intranet caso a sessão não tenha
if (!isset($cont_msg_n_lidas)) $cont_msg_n_lidas = 0;
if (!isset($count_approbations)) $count_approbations = 0;
?>

<style>
    /* ============================================================
       Esconde layout original do Perfex e aplica visual da intranet
       só em projetos vistos com nossas abas corporativas
    ============================================================ */
    body #header,
    body aside#menu,
    body aside.sidebar,
    body #setup-menu-wrapper,
    body #setup-menu-item,
    body .start-timer,
    body .header-bottom { display: none !important; }

    body #wrapper {
        margin-left: 0 !important;
        padding-top: 0 !important;
        background: #f3f2ef;
    }

    body { padding-top: 0 !important; }

    body .content { padding: 18px; }

    body #wrapper > .content {
        max-width: 1280px;
        margin: 0 auto;
    }

    .sig-corp-page { background: #f3f2ef; min-height: calc(100vh - 80px); }

    /* navbar intranet stick on top */
    .sig-navbar-wrap { position: sticky; top: 0; z-index: 1050; }
</style>

<?php $this->load->view('gestao_corporativa/intranet/includes/navbar'); ?>
<div class="sig-corp-page"></div>
