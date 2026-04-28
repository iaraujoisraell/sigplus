
<div class="mtop20">
    <div class="row">
        <div class="col-md-12">
            <span class="bold">
                <span class="label label-default mright5 inline-block mbot10"><i class="fa fa-search" aria-hidden="true"></i></span>
                Filtros</span> (POR CATEGORIA)
            <hr style="margin: 0 !important;"/>
        </div>
        <div class="col-md-12 ">
            <br>
            <?php $this->load->view('gestao_corporativa/categorias_campos/retorno_categoria', array('just_type' => array('select', 'multiselect', 'date', 'setores', 'funcionarios', 'list'), 'campos' => $campos_, 'just_campos' => true, 'without_required' => true)); ?>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <span class="bold">
                <span class="label label-default mright5 inline-block mbot10"><i class="fa fa-table" aria-hidden="true"></i></span>
                Ítens da tabela</span> (POR CATEGORIA)
            <hr style="margin: 0 !important;"/>
        </div>

        <div class="col-md-12">

            <div class="row">
                <div class="col-md-4">
                    <br>
                    <select id="current" name="current" class="selectpicker" data-width="100%" data-none-selected-text="Setor Atual" data-live-search="true">
                        <option value=""></option><!-- comment -->
                        <?php foreach ($departments as $department) { ?>
                            <option value="<?php echo $department['setor']; ?>"><?php echo get_departamento_nome($department['setor']); ?></option>

                        <?php } ?>
                    </select>

                </div>
                <div class="col-md-4">
                    <br>
                    <select id="campos_system" name="campos_system[]" class="selectpicker" data-width="100%" data-none-selected-text="Informações do Sistema" data-live-search="true" multiple>
                        <option value=""></option><!-- comment -->
                        <option value="tbl_intranet_workflow.date_prazo;Prazo Interno">Prazo Interno</option>
                        <option value="tbl_intranet_workflow.date_prazo_client;Prazo Cliente">Prazo do Cliente</option>
                        <option value="tbl_intranet_workflow.date_end;Data Finalização">Data de Finalização</option>
                        <option value="tbl_intranet_workflow.date_end;Data Finalização Cliente">Data de Finalização Cliente</option>
                    </select>

                </div>
                <div class="col-md-4">
                    <br>
                    <select id="campos_cat" name="campos_cat[]" class="selectpicker" data-width="100%" data-none-selected-text="Informações Formulário Inicial" data-live-search="true" multiple>
                        <option value=""></option><!-- comment -->
                        <?php
                        foreach ($campos as $camp) {
                            if ($camp['type'] != 'separador') {
                                echo "<option value='" . $camp['id'] . ";" . $camp['nome'] . ";" . $camp['type'] . "'>" . $camp['nome'] . "</option>";
                            }
                        }
                        ?>

                    </select>

                </div>
                <div class="col-md-12">
                    <br>
                    <select id="campos" name="campos[]" class="selectpicker" data-width="100%" data-none-selected-text="Informações do Processo" data-live-search="true" multiple>
                        <option value=""></option><!-- comment -->
                        <?php foreach ($departments as $dep) { ?>

                            <?php
                            foreach ($dep['campos'] as $camp) {
                                if ($camp['type'] != 'separador') {
                                    echo "<option title='' value='" . $camp['id'] . ";" . $camp['nome'] . ";" . $dep['setor'] . ";" . $camp['type'] . "' >[" . get_departamento_nome($dep['setor']) . "] " . $camp['nome'] . "</option>";
                                }
                            }
                            ?>


                        <?php } ?>
                    </select>

                </div>
            </div>
        </div>
    </div>
</div>

