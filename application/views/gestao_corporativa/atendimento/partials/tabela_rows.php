<?php if (!empty($rows)) { ?>
    <?php foreach ($rows as $aRow) { ?>
        <tr>
            <td>#<?php echo $aRow['id']; ?></td>

            <td>
                <a href="<?php echo site_url('gestao_corporativa/Atendimento/view/' . $aRow['id']); ?>">
                    <?php echo $aRow['protocolo']; ?>
                </a>

                <?php if ((int) $aRow['ro_count'] > 0 || (int) $aRow['workflow_count'] > 0) { ?>
                    <p class="text-danger mtop5 mbot0">
                        <?php if ((int) $aRow['ro_count'] > 0) { ?>
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                            REGISTROS (<?php echo $aRow['ro_count']; ?>)
                        <?php } ?>

                        <?php if ((int) $aRow['ro_count'] > 0 && (int) $aRow['workflow_count'] > 0) { ?>
                            <br>
                        <?php } ?>

                        <?php if ((int) $aRow['workflow_count'] > 0) { ?>
                            <i class="fa fa-circle-o-notch" aria-hidden="true"></i>
                            WORKFLOW'S (<?php echo $aRow['workflow_count']; ?>)
                        <?php } ?>
                    </p>
                <?php } ?>
            </td>

            <td>
                <?php if (!empty($aRow['ro_itens'])) { ?>
                    <?php foreach ($aRow['ro_itens'] as $r) { ?>
                        <a href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($r['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus'))); ?>">
                            RO #<?php echo $r['id']; ?>
                        </a><br>
                    <?php } ?>
                <?php } ?>

                <?php if (!empty($aRow['workflow_itens'])) { ?>
                    <?php foreach ($aRow['workflow_itens'] as $wf) { ?>
                        <a href="<?php echo base_url('gestao_corporativa/workflow/pdf/' . $wf['id']); ?>">
                            WF #<?php echo $wf['id']; ?>
                        </a><br>
                    <?php } ?>
                <?php } ?>
            </td>

            <?php if (empty($portal)) { ?>
                <td><?php echo strtoupper((string) $aRow['canal']); ?></td>
            <?php } ?>

            <td>
                <a href="<?php echo admin_url('clients/client/' . $aRow['userid']) . '?intranet=intranet'; ?>">
                    <?php echo $aRow['company']; ?> (<?php echo $aRow['numero_carteirinha']; ?>)
                </a>
            </td>

            <td>
                <?php
                $origem = $aRow['origem_carteirinha'];

                if ($origem == 'UNIMED MANAUS') {
                    echo '<span class="label label-success">UNIMED MANAUS</span>';
                } elseif ($origem == 'UNIMED INTERCÂMBIO') {
                    echo '<span class="label label-info">UNIMED INTERCÂMBIO</span>';
                } else {
                    echo '<span class="label label-default">AVULSO</span>';
                }
                ?>
            </td>

            <?php if (empty($portal)) { ?>
                <td><?php echo strtoupper((string) $aRow['categoria']); ?></td>
            <?php } ?>

            <td>
                <?php echo strtoupper((string) $aRow['contato']); ?><br>
                <?php echo $aRow['email']; ?>
            </td>

            <td>
                <?php if (!empty($aRow['campos_itens'])) { ?>
                    <?php foreach ($aRow['campos_itens'] as $info) { ?>
                        <?php echo $info['nome_campo']; ?>: <?php echo $info['value']; ?><br>
                    <?php } ?>
                <?php } ?>
            </td>

            <td>
                <span style="font-size:12px;">
                    <?php echo $aRow['firstname']; ?><br>
                    <?php echo _dt($aRow['date_created']); ?>
                </span>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="<?php echo empty($portal) ? 10 : 8; ?>" class="text-center text-muted">
            Nenhum registro encontrado.
        </td>
    </tr>
<?php } ?>