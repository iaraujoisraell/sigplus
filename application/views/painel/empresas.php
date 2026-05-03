<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('painel/_layout', ['title' => 'Empresas']); ?>

<div class="saas-page">
    <div class="saas-h">
        <h1><i class="fa fa-building"></i> Empresas <span style="color:#94a3b8;font-weight:400;">· <?php echo count($empresas); ?></span></h1>
        <a href="<?php echo base_url('painel/empresa_add'); ?>" class="saas-btn saas-btn-primary"><i class="fa fa-plus"></i> Nova empresa</a>
    </div>

    <form method="get" class="saas-card">
        <div class="saas-card-b" style="display:grid;grid-template-columns:1fr 200px 200px auto;gap:10px;align-items:end;">
            <div>
                <label style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;display:block;margin-bottom:4px;">Buscar</label>
                <input type="text" name="busca" value="<?php echo html_escape($filtros['busca'] ?? ''); ?>" placeholder="empresa, CNPJ, e-mail" style="width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;">
            </div>
            <div>
                <label style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;display:block;margin-bottom:4px;">Status</label>
                <select name="active" style="width:100%;border:1px solid #d0d5dd;border-radius:6px;padding:7px 10px;font-size:13px;">
                    <option value="">— todos —</option>
                    <option value="1" <?php echo $filtros['active'] === '1' ? 'selected' : ''; ?>>Ativas</option>
                    <option value="0" <?php echo $filtros['active'] === '0' ? 'selected' : ''; ?>>Inativas</option>
                </select>
            </div>
            <div>
                <label style="font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;display:block;margin-bottom:4px;">&nbsp;</label>
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:400;text-transform:none;color:#475569;cursor:pointer;">
                    <input type="checkbox" name="mostrar_excluidas" value="1" <?php echo !empty($filtros['mostrar_excluidas']) ? 'checked' : ''; ?>> Mostrar excluídas
                </label>
            </div>
            <button type="submit" class="saas-btn saas-btn-default"><i class="fa fa-filter"></i> Filtrar</button>
        </div>
    </form>

    <div class="saas-card">
        <?php if (empty($empresas)): ?>
            <div class="empty"><i class="fa fa-building"></i> Nenhuma empresa encontrada.</div>
        <?php else: ?>
            <table class="saas-table">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Empresa</th>
                        <th>CNPJ</th>
                        <th>Contato</th>
                        <th>Cadastrada em</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Staff</th>
                        <th style="text-align:center;">Atividade</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empresas as $e): ?>
                        <tr style="<?php echo (int) $e['deleted'] === 1 ? 'opacity:.4;' : ''; ?>">
                            <td><strong><?php echo (int) $e['id']; ?></strong></td>
                            <td>
                                <a href="<?php echo base_url('painel/empresa/' . (int) $e['id']); ?>" style="font-weight:600;"><?php echo html_escape($e['company']); ?></a>
                                <?php if ($e['email']): ?><br><small style="color:#94a3b8;"><?php echo html_escape($e['email']); ?></small><?php endif; ?>
                            </td>
                            <td><small><?php echo html_escape($e['cnpj'] ?? '—'); ?></small></td>
                            <td><small><?php echo html_escape($e['fone'] ?? '—'); ?></small></td>
                            <td><small><?php echo $e['dt_cadastro'] ? date('d/m/Y', strtotime($e['dt_cadastro'])) : '—'; ?></small></td>
                            <td style="text-align:center;">
                                <?php if ((int) $e['active'] === 1): ?>
                                    <span class="pill ativo">Ativa</span>
                                <?php else: ?>
                                    <span class="pill inativo">Inativa</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:center;font-size:12px;">
                                <strong><?php echo (int) $e['total_staff']; ?></strong>
                                <?php if ((int) $e['total_admins'] > 0): ?><br><small style="color:#94a3b8;"><?php echo (int) $e['total_admins']; ?> admin</small><?php endif; ?>
                            </td>
                            <td style="text-align:center;font-size:11px;color:#64748b;">
                                <?php echo (int) $e['total_projects']; ?> proj · <?php echo (int) $e['total_atas']; ?> atas · <?php echo (int) $e['total_docs']; ?> docs
                            </td>
                            <td style="text-align:right;white-space:nowrap;">
                                <a href="<?php echo base_url('painel/empresa/' . (int) $e['id']); ?>" class="saas-btn saas-btn-default saas-btn-sm" title="Detalhes"><i class="fa fa-eye"></i></a>
                                <a href="<?php echo base_url('painel/empresa_edit/' . (int) $e['id']); ?>" class="saas-btn saas-btn-default saas-btn-sm" title="Editar"><i class="fa fa-pencil-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
