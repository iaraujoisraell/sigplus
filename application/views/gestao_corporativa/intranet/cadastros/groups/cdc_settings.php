
<h4 class="customer-profile-group-heading"><?php echo 'Controle Documental Contínuo - Configurações'; ?></h4>

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
                
            </ul>
        </div>
    </div>
    <div class="tab-content mtop15">
        <div role="tabpanel" class="tab-pane active" id="categorias">
            <?php
            $data['rel_type'] = 'cdc';
            $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);
            ?>

        </div>
        
    </div>

</div>
<div id="modal_wrapper"></div>




<?php init_tail(); ?>


