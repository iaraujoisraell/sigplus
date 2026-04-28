<?php
$qtd_wf = count($campo_wf);
$qtd_cat = count($campo_cat);
$qtd_all = count($campos_);
$qtd_ = 3;
if ($categoria->ra == 1) {
    $qtd_ = $qtd_ + 2;
}
$total = $qtd_wf + $qtd_cat + $qtd_all + $qtd_;
$form_start = $qtd_wf + $qtd_cat;
$form_end = $qtd_all;
?>
<div class="panel_s">
    <div class="panel-body">

        <div class="row">
            <div class="col-md-12">
                <!--<a href="#" onclick="make_expense_pdf_export(); return false;" class="btn btn-default pull-left mright10 "><i class="fa fa-file-pdf-o"></i></a>-->
                <a download="CAT<?php echo $categoria->id . uniqid($categoria->id); ?>.xls"
                    class="btn btn-default pull-left mright10 " href="#"
                    onclick="return ExcellentExport.excel(this, 'expenses-report-table', 'Expenses Report <?php echo $current_year; ?>');"><i
                        class="fa fa-file-excel-o"></i></a>
            </div>
        </div>




        <div class="table-responsive">
            <table class="table table-bordered table-hover expenses-report" id="expenses-report-table">
                <thead>
                    <tr>
                        <th class="bold" colspan="<?php echo $total; ?>" style="text-align: center;">
                            <?php echo $categoria->titulo; ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="" colspan="<?php echo $qtd_ + $qtd_wf; ?>" style="text-align: center;">SISTEMA</th>
                        <?php if ($qtd_cat > 0) { ?>
                            <th class="" colspan="<?php echo $qtd_cat; ?>" style="text-align: center;">FORMULÁRIO
                                SOLICITANTE</th>
                        <?php } ?>
                        <?php foreach ($deps_qtd as $dep) { ?>
                            <th class="" colspan="<?php echo $dep['qtd']; ?>" style="text-align: center;">
                                <?php echo get_departamento_nome($dep['dep']); ?>
                            </th>
                        <?php } ?>
                    </tr>
                    <tr>

                        <th class="bold">ID</th>
                        <th class="bold">STATUS</th>
                        <th class="bold">FLUXO ATUAL</th>
                        <?php if ($categoria->ra == 1) { ?>
                            <th class="bold">CLIENTE</th>
                            <th class="bold">CARTEIRINHA</th>
                        <?php } ?>
                        <?php foreach ($campo_wf as $camp) { ?>
                            <th class="bold">
                                <?php echo $camp['label']; ?>
                            </th>
                        <?php } ?>
                        <?php
                        //$this->load->model('Categorias_campos_model');
                        foreach ($campo_cat as $camp) {
                            //echo $camp['column']; exit;
                            //$_campo = $this->Categorias_campos_model->get_campo($camp['column']);
                            ?>
                            <th class="bold">
                                <?php echo $camp['label']; ?>
                            </th>
                        <?php } ?>
                        <?php
                        //$this->load->model('Categorias_campos_model');
                        foreach ($campos_ as $camp) {
                            if (!is_array($camp)) {
                                $camp = explode(';', $camp);
                                $camp['column'] = $camp[0];
                                $camp['label'] = $camp[1];
                            }
                            //$_campo = $this->Categorias_campos_model->get_campo($camp['column']);
                            ?>
                            <th class="bold">
                                <?php echo $camp['label']; ?>
                            </th>
                        <?php } ?>

                    </tr>
                </thead>
                <tbody>

                    <?php

                    function removerAcentos($str)
                    {
                        // Função para remover acentos, caracteres especiais e converter para ISO-8859-1
                        $acentos = array(
                            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
                            'é' => 'e', 'è' => 'e', 'ê' => 'e',
                            'í' => 'i', 'ì' => 'i', 'î' => 'i',
                            'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o',
                            'ú' => 'u', 'ù' => 'u', 'û' => 'u',
                            'ç' => 'c',
                            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A',
                            'É' => 'E', 'È' => 'E', 'Ê' => 'E',
                            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I',
                            'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O',
                            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U',
                            'Ç' => 'C'
                        );

                        $str = strtr($str, $acentos);
                        $str = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $str);
                        $str = preg_replace('/[^a-zA-Z0-9]/', '', $str); // Remove caracteres especiais
                    
                        return $str;
                    }
                    foreach ($workflows as $wf) {
                        if ($wf['value']) {
                            $value = '{' . $wf['value'] . '}';
                            $arrayPHP = json_decode($value, true);
                            // Remover acentos das chaves do arrayPHP
                            $arrayPHP = array_combine(array_map('removerAcentos', array_keys($arrayPHP)), $arrayPHP);
                            //print_r($arrayPHP); //exit;
                        } else {
                            $arrayPHP = [];
                        }

                        if ($wf['value2']) {
                            $value2 = '{' . $wf['value2'] . '}';
                            $arrayPHP2 = json_decode($value2, true);
                        } else {
                            $arrayPHP2 = [];
                        }
                        ?>
                        <tr class="">
                            <td class=""> #
                                <?php echo $wf['id']; ?>
                            </td>
                            <?php $status = get_ro_status($wf['status']); ?>

                            <td class="">
                                <div class="label mtop5 single-ticket-status-label"
                                    style="background: <?php echo $status['color']; ?>">
                                    <?php echo $status['label']; ?>
                                </div>
                            </td>
                            <td class="" style="text-align: center;">
                                <?php
                                if ($wf['fluxo_sequencia']) {
                                    echo $wf['fluxo_sequencia'] . '° FLUXO';
                                    echo '<br>';
                                    echo get_departamento_nome($wf['department_id']);
                                    echo '<br>';
                                    echo '(' . get_staff_full_name($wf['atribuido_a']) . ')';
                                }
                                ?>
                            </td>
                            <?php if ($categoria->ra == 1) { ?>
                                <td class="">
                                    <?php echo $wf['company']; ?> (
                                    <?php if ($wf['dt_nascimento'] && $wf['dt_nascimento'] != '000-00-00') {
                                        echo _d($wf['dt_nascimento']);
                                    } ?>)
                                </td>
                                <td class="">
                                    <?php echo $wf['numero_carteirinha']; ?>
                                </td>
                            <?php } ?>
                            <?php
                            foreach ($campo_wf as $camp) {
                                $column = explode('.', $camp['column']);
                                $column = $column[1];
                                ?>
                                <td>
                                    <?php
                                    $timestamp = strtotime($wf[$column]);

                                    if ($timestamp !== false && $timestamp != -1) {
                                        echo _d($wf[$column]);
                                    } else {
                                        echo $wf[$column];
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                            <?php
                            //$this->load->model('Categorias_campos_model');
                            foreach ($campo_cat as $camp) {
                                //echo $camp['column']; exit;
                                // $_value = $this->Categorias_campos_model->get_campo_value($camp['column'], $wf['id']);
                                // print_r($_value); exit;
                                ?>
                                <th class="bold bg-odd">
                                    <?php echo get_value('workflow', $arrayPHP2[str_replace(' ', '', $camp['column'])], $camp['type']); ?>
                                </th>
                            <?php } ?>
                            <?php
                            //$this->load->model('Categorias_campos_model');
                            foreach ($campos_ as $camp) {
                                //echo $camp['column']; exit;
                                //$_value = $this->Categorias_campos_model->get_campo_value_similar($camp['label'], $wf['id'], $camp['dep']);
                                // print_r($_value); exit;
                                ?>
                                <th class="bold">
                                <?php //echo removerAcentos(str_replace(' ', '', $camp['label']) . $camp['dep']);
                                //print_r($arrayPHP);
                                echo get_value('workflow', $arrayPHP[removerAcentos(str_replace(' ', '', $camp['label']) . $camp['dep'])], $camp['type']); ?>
                                </th>
                            <?php } ?>


                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


    </div>
</div>