<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row" style="padding-top:10px;">
    <div class="col-md-12">
        <?php $this->load->view('gestao_corporativa/indicadores/_lista_vinculada', [
            'vinculo_tipo' => 'project',
            'vinculo_id'   => (int) $project->id,
            'card_title'   => 'Indicadores do projeto',
        ]); ?>
    </div>
</div>
