<?php //print_r($dados); exit;
?>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">

<?php $this->load->view('gestao_corporativa/css_background'); ?>
<input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="staffid">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - GERADOR DE ARQUIVOS</h5>
            <!-- <div>
                <ol class="breadcrumb" style="background-color: white;">
                     <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>"><i class="fa fa-backward"></i> Histórico de Arquivos </a></li>
                </ol>
            </div>-->
        </div>

    </div>
    <div class="panel_s project-top-panel">
        <div class="panel-body _buttons">
            <a href="<?php echo base_url("gestao_corporativa/Gerador_arquivos/tabelas"); ?>" class="btn btn-warning" target="_blank">Controle de Tabelas</a> <br>
            <div class="row"><br>
                <?php echo form_open(base_url('gestao_corporativa/gerador_arquivos'), array('id' => 'caixas-form')); ?>


                <div class="col-md-2 form-group">
                    <label style="font-size: 15px;">Data de:</label>
                    <input type="date" name="data_de" id="data_de" class="form-control" value="<?= $data_de ?>">
                </div>

                <div class="col-md-2 form-group">
                    <label style="font-size: 15px;">Data ate:</label>
                    <input type="date" name="data_ate" id="data_ate" class="form-control" value="<?= $data_ate ?>">
                </div>

                <div class="col-md-3 form-group">
                    <label style="font-size: 15px;">Tabela:</label>

                    <!-- <select name="tabela" id="tabela" class="form-control">
                        <?php if ($tabela == 1) { ?>
                            <option value="1" selected>PRESTADORES</option>
                        <?php } ?>
                        <?php if ($tabela == 2) { ?>
                            <option value="2" selected>OPME</option>
                        <?php } ?>
                        <?php if ($tabela == 3) { ?>
                            <option value="3" selected>MAT/MED/NUT</option>
                        <?php } ?>
                        <option value="">Nada Selectionado</option>
                        <option value="1">PRESTADORES</option>
                        <option value="2">OPME/PRODUTOS</option>
                        <option value="3">MAT/MED/NUT</option>
                        <option value="4">MAT EXPEDIENTE</option>
                        <option value="5">MAT HIGIENE/LIMPEZA</option>
                        <option value="6">MAT EPI</option>
                        <option value="7">MAT USO/CONSUMO</option>
                    </select> -->

                    <select name="tabela" id="tabela" class="form-control">
                        <option value="">Nada Selecionado</option>
                        <?php foreach ($lista_tabelas as $tabelas) { ?>
                            <option value="<?= $tabelas['id'] ?>" <?php if ($tabela == $tabelas['id']) { ?> selected <?php } ?>><?= $tabelas['descricao'] ?></option>
                        <?php } ?>
                    </select>



                </div>


                <div class="col-md-2">
                    <label style="font-size: 15px;">Regra parcelamento:</label>
                    <select name="regra_parcelamento" id="regra_parcelamento" class="form-control">
                        <option value="">Nada Selecionado</option>
                        <option value="1">SIM</option>
                        <option value="2" selected>NAO</option>
                    </select>
                </div>




                <!-- 
                <div class="col-md-2 form-group">
                    <label style="font-size: 15px;">Carteirinha do Beneficiario</label>
                    <input type="text" name="carteirinha" id="carteirinha" class="form-control" value="">
                </div>                 
                -->


                <div class="col-md-1">
                    <label style="font-size: 15px;"></label>
                    <button type="submit" class="btn btn-info">BUSCAR DADOS</button>
                </div>
                <?php echo form_close(); ?>


            </div>
        </div>
        <br>


        <div class="row">
            <div class="col-md-12" id="small-table">
                <div class="panel_s">
                    <div class="">

                    </div>
                    <div class="panel-body">

                        <table class="table dt-table table-bordered scroll-responsive" data-order-col="" data-order-type="">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>
                                        Empresa
                                    </th>
                                    <th>
                                        Filial
                                    </th>
                                    <th>
                                        N doc refencia
                                    </th>
                                    <th>
                                        txt cab doc
                                    </th>
                                    <th>
                                        TP DE DOC
                                    </th>
                                    <th>
                                        N atribuicao
                                    </th>
                                    <th>
                                        Oper empresarial
                                    </th>
                                    <th>
                                        Dt no documento
                                    </th>
                                    <th>
                                        Dt lanc no documento
                                    </th>
                                    <th>
                                        Nivel tesouraria
                                    </th>
                                    <th>
                                        Local negocios
                                    </th>

                                    <th>
                                        Conta razao cont geral
                                    </th>
                                    <th>
                                        Texto do item 1
                                    </th>
                                    <th>
                                        Centro de custo
                                    </th>
                                    <th>
                                        Nr ordem
                                    </th>
                                    <th>
                                        Centro de lucro
                                    </th>
                                    <th>
                                        n_cliente
                                    </th>

                                    <th>
                                        chave_de_referencia_1
                                    </th>
                                    <th>
                                        chave_de_referencia_3
                                    </th>
                                    <th>
                                        chave_condicoes_pgto
                                    </th>
                                    <th>
                                        chave_bloqueio_pgto
                                    </th>
                                    <th>
                                        conta_do_razao_cont
                                    </th>
                                    <th>
                                        forma_de_pagamento
                                    </th>

                                    <th>
                                        texto_do_item_2
                                    </th>
                                    <th>
                                        divisao
                                    </th>
                                    <th>
                                        data_base_para_calculo
                                    </th>
                                    <th>
                                        N conta fornecedor
                                    </th>
                                    <th>
                                        Chave bloqueio de pgto
                                    </th>
                                    <th>
                                        Conta razao da cont
                                    </th>


                                    <th>
                                        Forma de pgto
                                    </th>
                                    <th>
                                        Texto do item 3
                                    </th>

                                    <th>
                                        Cod do razao esp
                                    </th>
                                    <th>
                                        Dt base para calculo
                                    </th>
                                    <th>
                                        Montante em moeda doc
                                    </th>
                                    <th>
                                        Codigo da moeda
                                    </th>
                                    <th>
                                        Montante base irf
                                    </th>
                                    <th>
                                        Montante irf
                                    </th>
                                    <th>
                                        Cod categoria irf
                                    </th>
                                    <th>
                                        Codigo de irf
                                    </th>
                                    <th>
                                        Fornecedor
                                    </th>



                            </thead>
                            <tbody>


                                <?php  foreach ($dados as $info) { ?>

                                    <?php if ($info['NOTAPARCELADA'] == 1) { ?>



                                        <!-- LINHA PADRÃO -->
                                        <tr>
                                            <td></td>
                                            <td>
                                                <?php echo $info['EMPRESA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['FILIAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['N_DOC_REFERENCIA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TXT_CAB_DOC']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TP_DE_DOC']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['N_ATRIBUICAO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['OPER_EMPRESARIAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['DT_NO_DOCUMENTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['DT_LANC_NO_DOCUMENTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['NIVEL_TESOURARIA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['LOCAL_NEGOCIOS']; ?>
                                            </td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td>
                                                <?php echo $info['NOME_FORNECEDOR']; ?>
                                            </td>
                                        </tr>


                                        <!-- SSEGUNDA LINHA PADRÃO-->
                                        <?php
                                        $soma_parcelas = 0;
                                       // print_r($info['DT_BASE_PARA_CALCULO']);
                                        for ($i = 0; $i < count($info['DT_BASE_PARA_CALCULO']); $i++) { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php echo $info['N_CONTA_FORNECEDOR']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['CHAVE_BLOQUEIO_DE_PGTO']; ?>
                                                </td>
                                                <td>
                                                    <?php //echo $info['CONTA_RAZAO_DA_CONT'];
                                                        echo '21301018'; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['FORMA_DE_PGTO']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['TEXTO_DO_ITEM_3']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['COD_DO_RAZAO_ESP']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['DT_BASE_PARA_CALCULO'][$i]; ?>
                                                </td>
                                                <td>

                                                    <?php
                                                    $montante_em_moeda_doc = str_replace(",", ".", $info['MONTANTE_EM_MOEDA_DOC'][$i]);

                                                    $soma_parcelas = $info['MONTANTE_EM_MOEDA_DOC'][$i] + $soma_parcelas;
                                                    ?>

                                                    <?php echo '-' . number_format($montante_em_moeda_doc, 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['CODIGO_DA_MOEDA']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $montante_base_irf = str_replace(",", ".", $info['MONTANTE_BASE_IRF']);
                                                    echo number_format($info['MONTANTE_BASE_IRF'], 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $montante_irf = str_replace(",", ".", $info['MONTANTE_IRF'][0]);
                                                    echo number_format($info['montante_irf'], 2, ',', '.');
                                                    ?>
                                                    <?php //echo $info->MONTANTE_IRF[0]; 
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php echo $info->COD_CATEGORIA_IRF[0]; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info->CODIGO_DE_IRF[0]; ?>
                                                </td>
                                                <td></td>
                                            </tr>

                                        <?php } ?>
                                        <!-- DEMAIS LINHAS -->
                                        <?php /*for ($i = 1; $i < count($info['MONTANTE_IRF']); $i++) { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php echo $info['COD_DO_RAZAO_ESP']; ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php echo number_format($info['MONTANTE_BASE_IRF'], 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php

                                                    $montante_irf = str_replace(",", ".", $info['MONTANTE_IRF'][0]);
                                                    echo number_format($info->montante_irf, 2, ',', '.');


                                                    //echo number_format($info->MONTANTE_IRF[$i], 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['COD_CATEGORIA_IRF'][$i]; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['CODIGO_DE_IRF'][$i]; ?>
                                                </td>
                                                <td></td>
                                            </tr>


                                        <?php } */ ?>


                                        <!-- ULTIMA LINHA PADRÃO  -->
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php echo $info['CONTA_RAZAO_CONT_GERAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TEXTO_DO_ITEM_1']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CENTRO_DE_CUSTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['NR_ORDEM']; ?>
                                            </td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td>

                                                <?php
                                                    $montante_em_moeda_doc = str_replace(",", ".", $soma_parcelas);
                                                ?>

                                                <?php echo number_format($montante_em_moeda_doc, 2, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CODIGO_DA_MOEDA']; ?>

                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>


                                        </tr>



                                    <?php } elseif ($info['IMPOSTOS'] == 1) { ?>



                                        <!-- LINHA PADRÃO -->
                                        <tr>
                                            <td></td>
                                            <td>
                                                <?php echo $info['EMPRESA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['FILIAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['N_DOC_REFERENCIA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TXT_CAB_DOC']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TP_DE_DOC']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['N_ATRIBUICAO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['OPER_EMPRESARIAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['DT_NO_DOCUMENTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['DT_LANC_NO_DOCUMENTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['NIVEL_TESOURARIA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['LOCAL_NEGOCIOS']; ?>
                                            </td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td>
                                                <?php echo $info['NOME_FORNECEDOR']; ?>
                                            </td>
                                        </tr>


                                        <!-- SSEGUNDA LINHA PADRÃO-->
                                        <?php
                                        $soma_parcelas = 0;
                                       // print_r($info['DT_BASE_PARA_CALCULO']);
                                        for ($i = 0; $i < count($info['DT_BASE_PARA_CALCULO']); $i++) { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                                <?php if($i == 0) {?>
                                                <td>
                                                    <?php echo $info['N_CONTA_FORNECEDOR']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['CHAVE_BLOQUEIO_DE_PGTO']; ?>
                                                </td>
                                                <td>
                                                    <?php //echo $info['CONTA_RAZAO_DA_CONT'];
                                                        echo '21301018'; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['FORMA_DE_PGTO']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['TEXTO_DO_ITEM_3']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['COD_DO_RAZAO_ESP']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['DT_BASE_PARA_CALCULO'][$i]; ?>
                                                </td>
                                                <td>

                                                    <?php
                                                    $montante_em_moeda_doc = str_replace(",", ".", $info['MONTANTE_EM_MOEDA_DOC'][$i]);

                                                    $soma_parcelas = $info['MONTANTE_EM_MOEDA_DOC'][$i] + $soma_parcelas;
                                                    ?>

                                                    <?php echo '-' . number_format($montante_em_moeda_doc, 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['CODIGO_DA_MOEDA']; ?>
                                                </td>

                                                <?php } else {?>

                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>

                                                    <?php }?>
                                                <td>
                                                    <?php
                                                    $montante_base_irf = str_replace(",", ".", $info['MONTANTE_BASE_IRF']);
                                                    echo number_format($montante_base_irf, 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $montante_irf = str_replace(",", ".", $info['MONTANTE_IRF'][$i]);
                                                    echo number_format($montante_irf, 2, ',', '.');
                                                    ?>
                                                    <?php //echo $info->MONTANTE_IRF[0]; 
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php echo $info['COD_CATEGORIA_IRF'][$i]; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['CODIGO_DE_IRF'][$i]; ?>
                                                </td>
                                                <td></td>
                                            </tr>

                                        <?php } ?>
                                        <!-- DEMAIS LINHAS -->
                                        <?php /*for ($i = 1; $i < count($info['MONTANTE_IRF']); $i++) { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php echo $info['COD_DO_RAZAO_ESP']; ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php echo number_format($info['MONTANTE_BASE_IRF'], 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php

                                                    $montante_irf = str_replace(",", ".", $info['MONTANTE_IRF'][0]);
                                                    echo number_format($info->montante_irf, 2, ',', '.');


                                                    //echo number_format($info->MONTANTE_IRF[$i], 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['COD_CATEGORIA_IRF'][$i]; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['CODIGO_DE_IRF'][$i]; ?>
                                                </td>
                                                <td></td>
                                            </tr>


                                        <?php } */ ?>


                                        <!-- ULTIMA LINHA PADRÃO  -->
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php echo $info['CONTA_RAZAO_CONT_GERAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TEXTO_DO_ITEM_1']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CENTRO_DE_CUSTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['NR_ORDEM']; ?>
                                            </td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td>

                                                <?php
                                                  //  $montante_em_moeda_doc = str_replace(",", ".", $soma_parcelas);
                                                ?>

                                                <?php echo number_format($montante_em_moeda_doc, 2, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CODIGO_DA_MOEDA']; ?>

                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>


                                        </tr>



                                    <?php } else { ?>

                                        <!-- LINHA PADRÃO -->
                                        <tr>
                                            <td></td>
                                            <td>
                                                <?php echo $info['EMPRESA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['FILIAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['N_DOC_REFERENCIA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TXT_CAB_DOC']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TP_DE_DOC']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['N_ATRIBUICAO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['OPER_EMPRESARIAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['DT_NO_DOCUMENTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['DT_LANC_NO_DOCUMENTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['NIVEL_TESOURARIA']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['LOCAL_NEGOCIOS']; ?>
                                            </td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td>
                                                <?php echo $info['NOME_FORNECEDOR']; ?>
                                            </td>
                                        </tr>


                                        <!-- SSEGUNDA LINHA PADRÃO-->
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php echo $info['N_CONTA_FORNECEDOR']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CHAVE_BLOQUEIO_DE_PGTO']; ?>
                                            </td>
                                            <td>
                                                <?php //echo $info['CONTA_RAZAO_DA_CONT']; 
                                                        echo '21301018';
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $info['FORMA_DE_PGTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TEXTO_DO_ITEM_3']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['COD_DO_RAZAO_ESP']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['DT_BASE_PARA_CALCULO']; ?>
                                            </td>
                                            <td>

                                                <?php
                                                $montante_em_moeda_doc = str_replace(",", ".", $info['MONTANTE_EM_MOEDA_DOC']);
                                                ?>

                                                <?php echo '-' . number_format($montante_em_moeda_doc, 2, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CODIGO_DA_MOEDA']; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $montante_base_irf = str_replace(",", ".", $info['MONTANTE_BASE_IRF']);
                                                echo number_format($info['MONTANTE_BASE_IRF'], 2, ',', '.'); ?>
                                            </td>
                                            <td>



                                                <?php
                                                $montante_irf = str_replace(",", ".", $info['MONTANTE_IRF'][0]);
                                                echo number_format($montante_irf, 2, ',', '.');

                                                //echo $info->MONTANTE_IRF[0]; 
                                                ?>
                                            </td>

                                            <td>
                                                <?php echo $info['COD_CATEGORIA_IRF'][0]; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CODIGO_DE_IRF'][0]; ?>
                                            </td>
                                            <td></td>
                                        </tr>

                                        <!-- DEMAIS LINHAS -->
                                        <?php for ($i = 1; $i < count($info['MONTANTE_IRF']); $i++) { ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php echo $info['COD_DO_RAZAO_ESP']; ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php echo number_format($info['MONTANTE_BASE_IRF'], 2, ',', '.'); ?>
                                                </td>
                                                <td>
                                                    <?php

                                                    $montante_irf = str_replace(",", ".", $info['MONTANTE_IRF'][$i]);
                                                    echo number_format($montante_irf, 2, ',', '.');

                                                    // echo number_format($info->MONTANTE_IRF[$i], 2, ',', '.'); 
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['COD_CATEGORIA_IRF'][$i]; ?>
                                                </td>
                                                <td>
                                                    <?php echo $info['CODIGO_DE_IRF'][$i]; ?>
                                                </td>
                                                <td></td>
                                            </tr>


                                        <?php } ?>


                                        <!-- ULTIMA LINHA PADRÃO  -->
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <?php echo $info['CONTA_RAZAO_CONT_GERAL']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['TEXTO_DO_ITEM_1']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CENTRO_DE_CUSTO']; ?>
                                            </td>
                                            <td>
                                                <?php echo $info['NR_ORDEM']; ?>
                                            </td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <td>

                                                <?php
                                                $montante_em_moeda_doc = str_replace(",", ".", $info['MONTANTE_EM_MOEDA_DOC']);
                                                ?>

                                                <?php echo number_format($montante_em_moeda_doc, 2, ',', '.'); ?>
                                            </td>
                                            <td>
                                                <?php echo $info['CODIGO_DA_MOEDA']; ?>

                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>


                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php // 
            ?>
        </div>


    </div>
</div>

<?php init_tail(); ?>



<div id="modal_wrapper"></div>



<script>
    function add_tabela() {

        // alert(id);
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/gerador_arquivos/modal", {
            slug: 'add_tabela',

        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#add_tabela').is(':hidden')) {
                $('#add_tabela').modal({
                    show: true,
                    backdrop: 'static'
                });
            }
        });


    }
</script>


</body>

</html>