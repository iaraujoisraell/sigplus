<div class="col-md-12">
    <hr />
    <h4>Lista de Alterações</h4>
</div>
<div class="col-md-12">

    <div class="mtop15" >
        <table class="table  scroll-responsive" >
            <thead>
                <tr>
                    <th>
                        <?php echo 'Data'; ?>
                    </th>
                    <th >
                        <?php echo 'Usuário'; ?>
                    </th>
                    <th >
                        <?php echo 'Campo Modificado'; ?>
                    </th>
                    <th >
                        <?php echo 'Informação Antiga'; ?>
                    </th>
                    <th >
                        <?php echo 'Informação Nova'; ?>
                    </th>



                </tr>
            </thead>
            <tbody>
                <?php foreach ($changes as $change) { ?>
                    <tr>
                        <td >
                            <label class='label label-info'><?php echo date("d/m/Y", strtotime($change['date_created'])); ?></label>

                        </td>

                        <td>
                            <label class='label label-info'><?php echo $change['firstname'] . ' ' . $change['lastname']; ?></label>

                        </td>

                        <td>
                            <?php echo $change['nome']; ?>
                            
                        </td>
                        <td>
                                        <?php
                                        if($change['old'] != ''){
                                        if ($change['type'] == 'multiselect' || $change['type'] == 'select') {
                                            $values = explode(',', $change['old']);
                                            $this->load->model('Registro_ocorrencia_model');
                                            for ($i = 0; $i < count($values); $i++) {
                                                $row = $this->Registro_ocorrencia_model->get_option($values[$i]);
                                                $values[$i] = $row->option;
                                            }
                                            echo implode(', ', $values);
                                        } elseif ($change['type'] == 'setores') {
                                            if ($change['old']) {
                                                echo get_departamento_nome($change['old']);
                                            }
                                        } elseif ($change['type'] == 'funcionarios') {
                                            if ($change['new']) {
                                                echo get_staff_full_name($change['old']);
                                            }
                                        } else {
                                            echo $change['old'];
                                        }
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        if($change['new'] != ''){
                                        if ($change['type'] == 'multiselect' || $change['type'] == 'select') {
                                            $values = explode(',', $change['new']);
                                            $this->load->model('Registro_ocorrencia_model');
                                            for ($i = 0; $i < count($values); $i++) {
                                                $row = $this->Registro_ocorrencia_model->get_option($values[$i]);
                                                $values[$i] = $row->option;
                                            }
                                            echo implode(', ', $values);
                                        } elseif ($change['type'] == 'setores') {
                                           
                                            if ($change['new']) {
                                                echo get_departamento_nome($change['new']);
                                            }
                                        } elseif ($change['type'] == 'funcionarios') {
                                            if ($change['new']) {
                                                echo get_staff_full_name($change['new']);
                                            }
                                        } else {
                                            echo $change['new'];
                                        }
                                        }
                                        ?>
                                    </td>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>