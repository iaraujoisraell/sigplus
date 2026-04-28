<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
    <h4 class="customer-profile-group-heading">Registros de Ocorrência</h4>
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
                            ASSUNTO
                        </th>
                        <th>
                            PROTOCOLO VINCULADO
                        </th>
                        <th>
                            CATEGORIA
                        </th>
                        <th>
                            STATUS
                        </th>
                        <th>
                            SETOR RESPONSÁVEL
                        </th>
                        <th>
                            VALIDADE
                        </th>
                        <th>
                            DATA
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ros as $ro) { ?>
                        <tr>
                            <td>
                                <a target="_blank"  href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia/registro/'.openssl_encrypt($ro['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus'));?>">#<?php echo $ro['id']; ?></a>

                            </td>
                            <td>
                                <?php echo $ro['subject'];  ?>
                            </td>
                            <td>
                                <a target="_blank" href="<?php echo base_url('gestao_corporativa/Atendimento/view/'.$ro['atendimento_id']);?>"><?php echo $ro['protocolo']; ?></a>
                            </td>
                            <td>
                                <?php if($ro['titulo']) {echo $ro['titulo']; } else { echo 'Portal do Cliente';} ?>
                            </td>
                            <td>
                                <?php 
                                $status = get_ro_status($ro['status']);
                                ?>
                                <span class="label inline-block" style="border:1px solid <?php echo $status['color'] ?>; color: <?php echo $status['color']; ?>"><?php echo ($status['label']); ?></span>
                            </td>
                            
                            <td>
                                <?php echo get_departamento_nome($ro['responsavel']); ?>
                            </td>
                            <td>
                                <?php if($ro['validade']) {echo date('d/m/Y',strtotime($ro['validade'])); }  ?>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($ro['date_created'])); ?> - <?php echo get_staff_full_name($ro['user_created']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>


