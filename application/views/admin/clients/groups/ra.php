<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
    <h4 class="customer-profile-group-heading">Registros de Atendimento</h4>
    <div class="col-md-12">


        <div class="clearfix"></div>
        <div class="mtop15">
            <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                <thead>
                    <tr>
                        <th >
                            ID
                        </th>
                        <th>
                            PROTOCOLO
                        </th>
                        <th>
                            CATEGORIA
                        </th>
                        <th>
                            CANAL
                        </th>
                        <th>
                            DATA
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ras as $ra) { ?>
                        <tr>
                            <td>
                                <a target="_blank" href="<?php echo base_url('gestao_corporativa/Atendimento/view/'.$ra['id']);?>">#<?php echo $ra['id']; ?></a>

                            </td>
                            <td>
                                <a target="_blank" href="<?php echo base_url('gestao_corporativa/Atendimento/view/'.$ra['id']);?>"><?php echo $ra['protocolo']; ?></a>
                            </td>
                            <td>
                                <?php if($ra['titulo']) {echo $ra['titulo']; } else { echo 'Portal do Cliente';} ?>
                            </td>
                            <td>
                                <?php if($ra['canal']) {echo $ra['canal']; } else { echo 'Portal do Cliente';} ?>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($ra['date_created'])); ?> - <?php echo get_staff_full_name($ra['user_created']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>


