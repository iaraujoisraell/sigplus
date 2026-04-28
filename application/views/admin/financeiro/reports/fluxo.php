<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<style>
 body {
 font-family:'Open Sans';
 background:#f1f1f1;
 }
 h3 {
 margin-top: 7px;
 font-size: 16px;
 }

 .install-row.install-steps {
 margin-bottom:15px;
 box-shadow: 0px 0px 1px #d6d6d6;
 }

 .control-label {
 font-size:13px;
 font-weight:600;
 }
 .padding-10 {
 padding:10px;
 }
 .mbot15 {
 margin-bottom:15px;
 }
 .bg-default {
 background: #03a9f4;
 border:1px solid #03a9f4;
 color:#fff;
 }
 .bg-success {
 border: 1px solid #dff0d8;
 }
 .bg-not-passed {
 border:1px solid #f1f1f1;
 border-radius:2px;
 }
 .bg-not-passed {
 border-right:0px;
 }
 .bg-not-passed.finish {
 border-right:1px solid #f1f1f1 !important;
 }
 .bg-not-passed h5 {
 font-weight:normal;
 color:#6b6b6b;
 }
 .form-control {
 box-shadow:none;
 }
 .bold {
 font-weight:600;
 }
 .col-xs-5ths,
 .col-sm-5ths,
 .col-md-5ths,
 .col-lg-5ths {
 position: relative;
 min-height: 1px;
 padding-right: 15px;
 padding-left: 15px;
 }
 .col-xs-5ths {
 width: 20%;
 float: left;
 }
 b {
 font-weight:600;
 }
 .bootstrap-select .btn-default {
 background: #fff !important;
 border: 1px solid #d6d6d6 !important;
 box-shadow: none;
 color: #494949 !important;
 padding: 6px 12px;
 }
 
 
 /*
 */
 body {
color: #6a6c6f;
background-color: #f1f3f6;
margin-top: 30px;
}
.container {
// max-width: 960px;
width: 100%;
}
.panel-default>.panel-heading {
color: #333;
background-color: #fff;
border-color: #e4e5e7;
padding: 0;
-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
}
.panel-default>.panel-heading a {
display: block;
padding: 30px 15px ;
}
.panel-default>.panel-heading a:after {
content: "";
position: relative;
top: 1px;
display: inline-block;
font-family: 'Glyphicons Halflings';
font-style: normal;
font-weight: 400;
line-height: 1;
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;
float: right;
transition: transform .25s linear;
-webkit-transition: -webkit-transform .25s linear;
}
.panel-default>.panel-heading a[aria-expanded="true"] {
background-color: #eee;
}
.panel-default>.panel-heading a[aria-expanded="true"]:after {
content: "\2212";
-webkit-transform: rotate(180deg);
transform: rotate(180deg);
}
.panel-default>.panel-heading a[aria-expanded="false"]:after {
content: "\002b";
-webkit-transform: rotate(90deg);
transform: rotate(90deg);
}
.accordion-option {
width: 100%;
float: left;
clear: both;
margin: 15px 0;
}
.accordion-option .title {
font-size: 20px;
font-weight: bold;
float: left;
padding: 0;
margin: 0;
}
.accordion-option .toggle-accordion {
float: right;
font-size: 16px;
color: #6a6c6f;
}
.accordion-option .toggle-accordion:before {
content: "Expand All";
}
.accordion-option .toggle-accordion.active:before {
content: "Collapse All";
}
</style>

<script> 
    $(document).ready(function() {
        $(".toggle-accordion").on("click", function() {
        var accordionId = $(this).attr("accordion-id"),
        numPanelOpen = $(accordionId + ' .collapse.in').length;
        $(this).toggleClass("active");
        if (numPanelOpen == 0) {
        openAllPanels(accordionId);
        } else {
        closeAllPanels(accordionId);
        }
        })
        openAllPanels = function(aId) {
        console.log("setAllPanelOpen");
        $(aId + ' .panel-collapse:not(".in")').collapse('show');
        }
        closeAllPanels = function(aId) {
        console.log("setAllPanelclose");
        $(aId + ' .panel-collapse.in').collapse('hide');
        }
        });
</script>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
              <h1>
                Fluxo de Caixa - <?php echo $monthName; ?>
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
                <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
                <li class="active"><a href="">Relatório de Fluxo</a></li>
              </ol>
            </section>

            <div class="panel_s">
                <div class="panel-body">

                    <?php if($export_not_supported){ ?>
                    <p class="text-danger">Exporting not support in IE. To export this data please try another browser</p>
                    <?php } ?>

                    <a download="fluxo-report-<?php echo $monthName; ?>.xls" class="btn btn-default pull-left mright10<?php if($export_not_supported){echo ' disabled';} ?>" href="#" onclick="return ExcellentExport.excel(this, 'fluxo-table', 'Fluxo Report <?php echo $current_year; ?>');"><i class="fa fa-file-excel-o"></i></a>

                    <?php if(count($invoices_years) > 0 ){ ?>
                    <select class="selectpicker" name="expense_year" onchange="filter_expenses();" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <?php foreach($invoices_years as $year) { ?>
                        <option value="<?php echo $year['year']; ?>"<?php if($year['year'] == $current_year){echo 'selected';} ?>>
                            <?php echo $year['year']; ?>
                        </option>
                        <?php } ?>
                    </select>
                    <?php } ?>
                    <?php
                    $_currency = $base_currency;
                    if(is_using_multiple_currencies(db_prefix().'expenses')){ ?>
                    <div data-toggle="tooltip" class="pull-left mright5" title="<?php echo _l('report_expenses_base_currency_select_explanation'); ?>">
                        <select class="selectpicker" name="currencies" onchange="filter_expenses();"  data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" >
                            <?php foreach($currencies as $c) {
                                $selected = '';
                                if(!$this->input->get('currency')){
                                    if($c['id'] == $base_currency->id){
                                        $selected = 'selected';
                                        $_currency = $base_currency;
                                    }
                                } else {
                                    if($this->input->get('currency') == $c['id']){
                                        $selected = 'selected';
                                        $_currency = get_currency($c['id']);
                                    }
                                }
                                ?>
                                <option value="<?php echo $c['id']; ?>" <?php echo $selected; ?>>
                                    <?php echo $c['name']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php } ?>
                    </div>
                </div>

            <div class="container">
                <div class="accordion-option">

                    <a href="javascript:void(0)" class="toggle-accordion active" accordion-id="#accordion"></a>
                </div>
                <div class="clearfix"></div>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <?php
                                        
                                        $saldo_inicial = $this->Financeiro_model->get_saldo_inicial($mes, $ano);
                                        foreach ($saldo_inicial as $saldo){
                                            $tipo = $saldo['tipo'];
                                        if($tipo == 'E'){
                                            $t_ipo = '+';
                                            $vlr_entrada += $saldo['valor'];
                                        }else if($tipo == 'S'){
                                            $t_ipo = '-';
                                            $vlr_saida += $saldo['valor'];
                                        }
                                            $soma_valor_transacao = $vlr_entrada - $vlr_saida;
                                        }
                                        
                                    ?>
                                    <table style="width: 100%" >
                                        <th style="width: 80%"><?php echo 'Movimentos Bancários'; ?></th>                                      
                                        <th ><?php echo app_format_money($soma_valor_transacao, ' R$ '); ?></th>
                                    </table>

                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                               
                                <table style="width: 100%" class="table table-fluxo scroll-responsive">
                                    <thead>
                                        <tr>

                                          <th style="width: 50%"><?php echo 'Descrição'; ?></th>
                                          <th><?php echo 'Valor'; ?></th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                       
                                 <?php
                                    $soma_valor_transacao = 0;
                                    $vlr_entrada = 0;
                                    $vlr_saida = 0;
                                    $saldo_inicial = $this->Financeiro_model->get_saldo_inicial($mes, $ano);
                                    foreach ($saldo_inicial as $saldo){
                                        $tipo = $saldo['tipo'];
                                        if($tipo == 'E'){
                                            $t_ipo = '+';
                                            $vlr_entrada += $saldo['valor'];
                                        }else if($tipo == 'S'){
                                            $t_ipo = '-';
                                            $vlr_saida += $saldo['valor'];
                                        }
                                        
                                ?>
                                <tr>
                                    <th style="width: 65%"><?php echo $saldo['descricao']. ' ['. $saldo['banco'].'] '; ?></th>                                      
                                    <th ><?php echo '('.$t_ipo.') '.   app_format_money($saldo['valor'], ' R$ '); ?></th>
                                </tr>
                                <?php }
                                
                                $soma_valor_transacao = $vlr_entrada - $vlr_saida;
                                ?>
                                </tbody>
                                <thead>
                                <tr>

                                  <th style="width: 50%">Total</th>
                                  <th><?php echo app_format_money($soma_valor_transacao, ' R$ '); ?></th>
                                </tr>
                              </thead>
                              </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    <table style="width: 100%" >
                                        <th style="width: 80%"><?php echo 'Entradas'; ?></th>                                      
                                        <th ><?php echo app_format_money($total_categorias_entradas->valor, ' R$ '); ?></th>
                                    </table>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">

                                <?php // CATEGORIAS ENTRADAS ?>
                                    <?php
                                    foreach ($categorias_entradas as $categoria){
                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne_saida">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion_categoria<?php echo $categoria['id']; ?>" href="#categoria_entrada<?php echo $categoria['id']; ?>" aria-expanded="true" aria-controls="categoria_entrada<?php echo $categoria['id']; ?>">

                                                    <table style="width: 100%" >
                                                        <th style="width: 80%"><?php echo $categoria['categoria']; ?></th>                                      
                                                        <th ><?php echo app_format_money($categoria['valor'], ' R$ '); ?></th>
                                                    </table>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="categoria_entrada<?php echo $categoria['id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne<?php echo $categoria['id']; ?>">
                                            <div class="panel-body">
                                                <?php // PLANO DE CONTAS ?>
                                                <?php

                                                $planos_contas_entradas = $this->Financeiro_model->get_plano_conta_entrada_fluxo_by_categoria($categoria['id'], $mes, $ano);
                                                foreach ($planos_contas_entradas as $planos){
                                                ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingOne_saida">
                                                        <h4 class="panel-title">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion_planos<?php echo $planos['id']; ?>" href="#plano_entrada<?php echo $planos['id']; ?>" aria-expanded="true" aria-controls="plano_entrada<?php echo $planos['id']; ?>">
                                                                <table style="width: 100%" >
                                                                    <th style="width: 80%"><?php echo $planos['plano']; ?></th>                                      
                                                                    <th ><?php echo app_format_money($planos['valor'], ' R$ '); ?></th>
                                                                </table>

                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="plano_entrada<?php echo $planos['id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_plano<?php echo $categoria['id']; ?>">
                                                        <div class="panel-body">
                                                            <table class="table table-fluxo scroll-responsive">
                                                             <thead>
                                                                <tr>
                                                                  <th style="width: 15%"><?php echo 'Data Recebimento'; ?></th> 
                                                                  <th style="width: 15%"><?php echo 'Origem'; ?></th> 
                                                                  <th style="width: 50%"><?php echo 'Descrição'; ?></th>
                                                                  <th><?php echo 'Valor'; ?></th>
                                                                </tr>
                                                              </thead>
                                                              <tbody>
                                                                  <?php
                                                                  $soma_valor_entrada_plano = 0;
                                                                  $lancamentos_entradas = $this->Financeiro_model->get_lancamentos_entradas_fluxo_by_plano_id($planos['id'], $mes, $ano);
                                                                  foreach ($lancamentos_entradas as $entrada){
                                                                      $soma_valor_entrada_plano += $entrada['valor'];
                                                                  ?>
                                                                  <tr>
                                                                  <th style="width: 15%"><?php echo _d($entrada['date']); ?></th>  
                                                                  <th style="width: 15%"><?php echo $entrada['company']; ?></th>
                                                                  <th><?php echo $entrada['descricao']; ?></th>
                                                                  <th><?php echo app_format_money($entrada['valor'], ' R$ '); ?></th>
                                                                  </tr>
                                                                  <?php } ?>
                                                              </tbody>
                                                              <thead>
                                                                <tr>
                                                                  <th style="width: 15%"><?php echo 'Total'; ?></th>    
                                                                  <th style="width: 15%"></th>    
                                                                  <th style="width: 50%"></th>
                                                                  <th><?php echo app_format_money($soma_valor_entrada_plano, ' R$ '); ?></th>
                                                                </tr>
                                                              </thead>

                                                          </table>  


                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <?php // FIM PLANO DE CONTAS ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php // FIM CATEGORIAS ?>

                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingThree">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_saidas" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">

                                    <table style="width: 100%" >
                                        <th style="width: 80%"><?php echo 'Saídas'; ?></th>                                      
                                        <th ><?php echo app_format_money($total_categorias->valor, ' R$ '); ?></th>
                                    </table>

                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
                            <div class="panel-body">
                                <div class="panel-group" id="accordion_saida" role="tablist" aria-multiselectable="true">
                                    <?php // CATEGORIAS ?>
                                    <?php
                                    foreach ($categorias as $categoria){
                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne_saida">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion_categoria_saidas<?php echo $categoria['id']; ?>" href="#categoria_saida<?php echo $categoria['id']; ?>" aria-expanded="true" aria-controls="categoria_saida<?php echo $categoria['id']; ?>">

                                                    <table style="width: 100%" >
                                                        <th style="width: 80%"><?php echo $categoria['categoria']; ?></th>                                      
                                                        <th ><?php echo app_format_money($categoria['valor'], ' R$ '); ?></th>
                                                    </table>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="categoria_saida<?php echo $categoria['id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <?php // PLANO DE CONTAS ?>
                                                <?php
                                                $planos_contas = $this->Financeiro_model->get_plano_conta_saida_fluxo_by_categoria($categoria['id'], $mes, $ano);
                                                foreach ($planos_contas as $planos){
                                                ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingOne_saida">
                                                        <h4 class="panel-title">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion_planos_saida<?php echo $planos['id']; ?>" href="#plano_saida<?php echo $planos['id']; ?>" aria-expanded="true" aria-controls="plano_saida<?php echo $planos['id']; ?>">
                                                                <table style="width: 100%" >
                                                                    <th style="width: 80%"><?php echo $planos['plano']; ?></th>                                      
                                                                    <th ><?php echo app_format_money($planos['valor'], ' R$ '); ?></th>
                                                                </table>

                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="plano_saida<?php echo $planos['id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                        <div class="panel-body">
                                                            <table class="table table-fluxo scroll-responsive">
                                                             <thead>
                                                                <tr>
                                                                  <th style="width: 15%"><?php echo 'Data'; ?></th>                                      
                                                                  <th style="width: 65%"><?php echo 'Descrição'; ?></th>
                                                                  <th><?php echo 'Valor'; ?></th>
                                                                </tr>
                                                              </thead>
                                                              <tbody>
                                                                  <?php
                                                                  $soma_valor_saida_plano = 0;
                                                                  $lancamentos_saidas = $this->Financeiro_model->get_lancamentos_saida_fluxo_by_plano_id($planos['id'], $mes, $ano);
                                                                  foreach ($lancamentos_saidas as $saida){
                                                                      $soma_valor_saida_plano += $saida['valor_parcela'];
                                                                  ?>
                                                                  <tr>
                                                                  <th style="width: 15%"><?php echo _d($saida['data_pagamento']); ?></th>                                      
                                                                  <th><?php echo $saida['complemento']; ?></th>
                                                                  <th><?php echo app_format_money($saida['valor_parcela'], ' R$ '); ?></th>
                                                                  </tr>
                                                                  <?php } ?>
                                                              </tbody>
                                                              <thead>
                                                                <tr>
                                                                  <th style="width: 15%"><?php echo 'Total'; ?></th>                                      
                                                                  <th style="width: 65%"></th>
                                                                  <th><?php echo app_format_money($soma_valor_saida_plano, ' R$ '); ?></th>
                                                                </tr>
                                                              </thead>

                                                          </table>  


                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <?php // FIM PLANO DE CONTAS ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php // FIM CATEGORIAS ?>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                    </div>
                    
                        
                        <div class="col-md-5 col-md-offset-7">
                          <table class="table text-right">
                             <tbody>
                                <tr >
                                   <td><span class="bold"><?php echo 'Saldo Inicial'; ?></span>
                                   </td>
                                   <td >
                                      <?php echo app_format_money($soma_valor_transacao, ' R$ '); ?>
                                   </td>
                                </tr>
                                <tr>
                                   <td><span class="bold"><?php echo 'Entradas'; ?></span>
                                   </td>
                                   <td >
                                      <?php echo app_format_money($total_categorias_entradas->valor, ' R$ '); ?>
                                   </td>
                                </tr>
                                   <tr>
                                      <td><span class="bold"><?php echo 'Saídas'; ?></span></td>
                                      <td>
                                          <?php echo ' - '.app_format_money($total_categorias->valor, ' R$ '); ?>
                                      </td>
                                   </tr>
                                   <?php
                                   if(!$soma_valor_transacao){
                                       $soma_valor_transacao = 0;
                                   }
                                   $t_entrada = $total_categorias_entradas->valor;
                                   $t_saida = $total_categorias->valor;
                                   
                                   $t_saldo = ($soma_valor_transacao + $t_entrada - $t_saida);
                                   ?>
                                   <tr>
                                      <td><span class="bold"><?php echo 'Saldo'; ?></span></td>
                                      <td>
                                         <?php echo  app_format_money($t_saldo, ' R$ '); ?>
                                      </td>
                                   </tr>
                                 </tbody>
                          </table>
                       </div>
                </div>
            </div> 

        </div>
    </div>
</div>
       
<?php init_tail(); ?>


</body>
</html>
