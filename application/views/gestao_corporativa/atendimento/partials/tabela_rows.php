<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$portal = isset($portal) ? (int) $portal : 0;
$colspan = $portal ? 8 : 10;
?>

<?php if (empty($rows)) : ?>
    <tr>
        <td colspan="<?php echo $colspan; ?>" class="text-center text-muted">
            Nenhum registro encontrado.
        </td>
    </tr>
<?php else : ?>
    <?php foreach ($rows as $row) :
        $rel_id = $row['id'];

        // PROTOCOLO + indicadores de RO/Workflow
        $protocolo = '<a href="' . site_url('gestao_corporativa/Atendimento/view/' . $rel_id) . '">' . $row['protocolo'] . '</a>';
        $protocolo .= '<p class="text-danger" style="margin: 0;">';
        if (!empty($row['ro_count']) && $row['ro_count'] > 0) {
            $protocolo .= '<i class="fa fa-exclamation-circle" aria-hidden="true"></i> REGISTROS (' . $row['ro_count'] . ')';
        }
        if (!empty($row['ro_count']) && $row['ro_count'] > 0 && !empty($row['workflow_count']) && $row['workflow_count'] > 0) {
            $protocolo .= '<br>';
        }
        if (!empty($row['workflow_count']) && $row['workflow_count'] > 0) {
            $protocolo .= '<i class="fa fa-circle-o-notch" aria-hidden="true"></i> WORKFLOW\'S (' . $row['workflow_count'] . ')';
        }
        $protocolo .= '</p>';

        // REQUISIÇÕES (RO + WF)
        $req = '';
        if (!empty($row['ro_itens'])) {
            foreach ($row['ro_itens'] as $r) {
                $req .= '<a href="' . base_url('gestao_corporativa/Registro_ocorrencia/registro/' . base64_encode(openssl_encrypt($r['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus'))) . '">RO #' . $r['id'] . '</a><br>';
            }
        }
        if (!empty($row['workflow_itens'])) {
            foreach ($row['workflow_itens'] as $wf) {
                $req .= '<a href="' . base_url('gestao_corporativa/workflow/pdf/' . $wf['id']) . '">WF #' . $wf['id'] . '</a><br>';
            }
        }

        // INFORMAÇÕES CHAVE
        $informacoes = '<p style="margin: 0;">';
        if (!empty($row['campos_itens'])) {
            foreach ($row['campos_itens'] as $info) {
                $informacoes .= $info['nome_campo'] . ': ' . $info['value'] . '<br>';
            }
        }
        $informacoes .= '</p>';

        // CLIENTE
        $cliente = '<a href="' . admin_url('clients/client/' . $row['userid']) . '?intranet=intranet">'
            . $row['company'] . ' (' . $row['numero_carteirinha'] . ')</a>';

        // CONTATO
        $contato = strtoupper($row['contato']) . '<br>' . $row['email'];

        // COLABORADOR (nome completo)
        $colaborador_nome = trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? ''));
        $colaborador = '<font style="font-size: 12px">' . ($colaborador_nome !== '' ? $colaborador_nome : '—') . '</font>';

        // CADASTRO (só data)
        $cadastro = '<font style="font-size: 12px">' . _dt($row['date_created']) . '</font>';
    ?>
        <tr class="has-row-options">
            <td>#<?php echo $rel_id; ?></td>
            <td><?php echo $protocolo; ?></td>
            <td><?php echo $req; ?></td>

            <?php if (!$portal) : ?>
                <td><?php echo strtoupper($row['canal']); ?></td>
            <?php endif; ?>

            <td><?php echo $cliente; ?></td>

            <?php if (!$portal) : ?>
                <td><?php echo strtoupper($row['categoria']); ?></td>
            <?php endif; ?>

            <td><?php echo $contato; ?></td>
            <td><?php echo $informacoes; ?></td>
            <td><?php echo $colaborador; ?></td>
            <td><?php echo $cadastro; ?></td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>