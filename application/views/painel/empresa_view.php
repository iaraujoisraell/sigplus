<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('painel/_layout', ['title' => $empresa['company']]); ?>

<div class="saas-page">
    <?php if ($msg): ?>
        <div class="alert ok"><i class="fa fa-info-circle"></i> <?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="saas-h">
        <h1>
            <a href="<?php echo base_url('painel'); ?>" style="color:#64748b;text-decoration:none;"><i class="fa fa-arrow-left"></i></a>
            <i class="fa fa-building"></i>
            <?php echo html_escape($empresa['company']); ?>
            <?php if ((int) $empresa['active'] === 1): ?>
                <span class="pill ativo" style="font-size:11px;">Ativa</span>
            <?php else: ?>
                <span class="pill inativo" style="font-size:11px;">Inativa</span>
            <?php endif; ?>
        </h1>
        <div style="display:flex;gap:6px;">
            <a href="<?php echo base_url('painel/empresa_edit/' . (int) $empresa['id']); ?>" class="saas-btn saas-btn-default"><i class="fa fa-pencil-alt"></i> Editar</a>
            <a href="<?php echo base_url('painel/empresa_ativar/' . (int) $empresa['id']); ?>" class="saas-btn <?php echo (int) $empresa['active'] === 1 ? 'saas-btn-warn' : 'saas-btn-success'; ?>" onclick="return confirm('<?php echo (int) $empresa['active'] === 1 ? 'Inativar' : 'Ativar'; ?> empresa?')">
                <i class="fa fa-power-off"></i> <?php echo (int) $empresa['active'] === 1 ? 'Inativar' : 'Ativar'; ?>
            </a>
        </div>
    </div>

    <div class="saas-card">
        <div class="saas-card-h">Dados</div>
        <div class="saas-card-b" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;font-size:13px;">
            <div><strong style="color:#64748b;font-size:11px;text-transform:uppercase;">CNPJ</strong><br><?php echo html_escape($empresa['cnpj'] ?? '—'); ?></div>
            <div><strong style="color:#64748b;font-size:11px;text-transform:uppercase;">E-mail</strong><br><?php echo html_escape($empresa['email'] ?? '—'); ?></div>
            <div><strong style="color:#64748b;font-size:11px;text-transform:uppercase;">Telefone</strong><br><?php echo html_escape($empresa['fone'] ?? '—'); ?></div>
            <div><strong style="color:#64748b;font-size:11px;text-transform:uppercase;">Cidade/UF</strong><br><?php echo html_escape(($empresa['city'] ?? '—') . ' / ' . ($empresa['state'] ?? '—')); ?></div>
        </div>
    </div>

    <div class="saas-card">
        <div class="saas-card-h">
            <span><i class="fa fa-users"></i> Usuários (staff) — <?php echo count($staff); ?></span>
            <a href="<?php echo base_url('painel/staff_add/' . (int) $empresa['id']); ?>" class="saas-btn saas-btn-primary saas-btn-sm"><i class="fa fa-user-plus"></i> Novo usuário</a>
        </div>
        <?php if (empty($staff)): ?>
            <div class="empty"><i class="fa fa-user-slash"></i> Sem usuários ainda.</div>
        <?php else: ?>
            <table class="saas-table">
                <thead>
                    <tr>
                        <th>#</th><th>Nome</th><th>E-mail</th><th style="text-align:center;">Tipo</th><th style="text-align:center;">Status</th><th>Último login</th><th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $s): ?>
                        <tr>
                            <td><?php echo (int) $s['staffid']; ?></td>
                            <td><?php echo html_escape($s['firstname'] . ' ' . $s['lastname']); ?></td>
                            <td><?php echo html_escape($s['email']); ?></td>
                            <td style="text-align:center;">
                                <?php if ((int) $s['admin'] === 1): ?>
                                    <span class="pill admin">Admin</span>
                                <?php else: ?>
                                    <small style="color:#94a3b8;">Staff</small>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;">
                                <?php if ((int) $s['active'] === 1): ?>
                                    <span class="pill ativo">Ativo</span>
                                <?php else: ?>
                                    <span class="pill inativo">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td><small style="color:#64748b;"><?php echo $s['last_login'] ? date('d/m/Y H:i', strtotime($s['last_login'])) : '—'; ?></small></td>
                            <td style="text-align:right;white-space:nowrap;">
                                <a href="<?php echo base_url('painel/entrar_como/' . (int) $s['staffid']); ?>" class="saas-btn saas-btn-success saas-btn-sm" title="Logar como este usuário"><i class="fa fa-sign-in-alt"></i> Entrar</a>
                                <a href="<?php echo base_url('painel/staff_reset_senha/' . (int) $s['staffid']); ?>" class="saas-btn saas-btn-default saas-btn-sm" title="Resetar senha" onclick="return confirm('Resetar a senha desse usuário?')"><i class="fa fa-key"></i></a>
                                <a href="<?php echo base_url('painel/staff_toggle/' . (int) $s['staffid']); ?>" class="saas-btn saas-btn-default saas-btn-sm" title="Ativar/inativar"><i class="fa fa-power-off"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="saas-card">
        <div class="saas-card-h">Acesso direto</div>
        <div class="saas-card-b" style="font-size:13px;color:#475569;">
            <p><strong>URL do painel da empresa:</strong> <a href="<?php echo base_url('admin'); ?>" target="_blank"><?php echo base_url('admin'); ?></a></p>
            <p style="color:#94a3b8;font-size:12px;">Os usuários da empresa fazem login normalmente em <code>/admin</code>. O <code>empresa_id</code> é determinado automaticamente pelo e-mail/staff.</p>
        </div>
    </div>
</div>
</body>
</html>
