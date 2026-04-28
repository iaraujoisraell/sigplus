
<h4 class="customer-profile-group-heading"><?php echo 'Registro de Atendimento - Configurações'; ?></h4>

<div class="mtop15">
    <div class="horizontal-scrollable-tabs">
        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
        <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                <li role="presentation" class="active">
                    <a href="#categorias" aria-controls="categorias" role="tab" data-toggle="tab">
                        Categorias
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#tipos_fast" aria-controls="tipos_fast" role="tab" data-toggle="tab">
                        Tipos de Solicitações Rápidas
                    </a>
                </li>
                <li role="presentation" class="">
                    <a href="#autosservicos" aria-controls="autosservicos" role="tab" data-toggle="tab">
                        Autosserviços Vinculados Portal
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content mtop15">
        <div role="tabpanel" class="tab-pane active" id="categorias">
            <?php 
            $data['rel_type'] = 'atendimento';
            $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);?>
        </div>
        <div role="tabpane2" class="tab-pane" id="tipos_fast">
            <?php 
            $data['rel_type'] = 'ra_atendimento_rapido';
            $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);?>
        </div>
        <div role="tabpane2" class="tab-pane" id="autosservicos">
            <?php 
            $data['rel_type'] = 'autosservico';
            $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);?>
        </div>
    </div>

</div>
<div id="modal_wrapper"></div>




<?php init_tail(); ?>

