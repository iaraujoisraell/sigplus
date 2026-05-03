<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('painel/_layout', ['title' => !empty($empresa) ? 'Editar empresa' : 'Nova empresa']); ?>

<?php $e = $empresa ?? []; ?>

<div class="saas-page">
    <div class="saas-h">
        <h1>
            <a href="<?php echo base_url('painel'); ?>" style="color:#64748b;text-decoration:none;"><i class="fa fa-arrow-left"></i></a>
            <i class="fa fa-building"></i>
            <?php echo !empty($empresa) ? 'Editar empresa #' . (int) $empresa['id'] : 'Nova empresa'; ?>
        </h1>
    </div>

    <?php echo form_open('painel/empresa_save', ['class' => 'saas-form']); ?>
        <?php if (!empty($empresa)): ?><input type="hidden" name="id" value="<?php echo (int) $empresa['id']; ?>"><?php endif; ?>

        <div class="saas-card">
            <div class="saas-card-h">Dados da empresa</div>
            <div class="saas-card-b">
                <div class="row cols-2">
                    <div>
                        <label>Razão social <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="company" required maxlength="191" value="<?php echo html_escape($e['company'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Nome fantasia / completo</label>
                        <input type="text" name="company_full_name" maxlength="200" value="<?php echo html_escape($e['company_full_name'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row cols-3">
                    <div>
                        <label>CNPJ</label>
                        <input type="text" name="cnpj" value="<?php echo html_escape($e['cnpj'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>E-mail principal</label>
                        <input type="email" name="email" value="<?php echo html_escape($e['email'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Website</label>
                        <input type="text" name="website" value="<?php echo html_escape($e['website'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row cols-3">
                    <div>
                        <label>Telefone</label>
                        <input type="text" name="fone" value="<?php echo html_escape($e['fone'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Telefone 2</label>
                        <input type="text" name="fone2" value="<?php echo html_escape($e['fone2'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Idioma padrão</label>
                        <select name="default_language">
                            <option value="portuguese_br" <?php echo ($e['default_language'] ?? 'portuguese_br') === 'portuguese_br' ? 'selected' : ''; ?>>Português (BR)</option>
                            <option value="english" <?php echo ($e['default_language'] ?? '') === 'english' ? 'selected' : ''; ?>>English</option>
                            <option value="spanish" <?php echo ($e['default_language'] ?? '') === 'spanish' ? 'selected' : ''; ?>>Español</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="saas-card">
            <div class="saas-card-h">Endereço</div>
            <div class="saas-card-b">
                <div class="row cols-3">
                    <div style="grid-column:span 2;">
                        <label>Logradouro</label>
                        <input type="text" name="address" value="<?php echo html_escape($e['address'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Número</label>
                        <input type="text" name="endereco_numero" value="<?php echo html_escape($e['endereco_numero'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row cols-3">
                    <div>
                        <label>Complemento</label>
                        <input type="text" name="endereco_compemento" value="<?php echo html_escape($e['endereco_compemento'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Bairro</label>
                        <input type="text" name="endereco_bairro" value="<?php echo html_escape($e['endereco_bairro'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>CEP</label>
                        <input type="text" name="zip" value="<?php echo html_escape($e['zip'] ?? ''); ?>">
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label>Cidade</label>
                        <input type="text" name="city" value="<?php echo html_escape($e['city'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Estado</label>
                        <input type="text" name="state" value="<?php echo html_escape($e['state'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="saas-card">
            <div class="saas-card-h">Status</div>
            <div class="saas-card-b">
                <label style="display:flex;align-items:center;gap:6px;font-weight:400;text-transform:none;font-size:14px;">
                    <input type="checkbox" name="active" value="1" <?php echo (!empty($e) && (int) $e['active'] === 0) ? '' : 'checked'; ?>> Empresa ativa
                </label>
            </div>
        </div>

        <?php if (empty($empresa)): ?>
            <div class="saas-card" style="border-left:3px solid #0a66c2;">
                <div class="saas-card-h">👤 Admin inicial <small style="font-weight:400;color:#94a3b8;text-transform:none;letter-spacing:normal;">— pode pular e criar depois</small></div>
                <div class="saas-card-b">
                    <div class="row cols-2">
                        <div>
                            <label>Nome</label>
                            <input type="text" name="admin_firstname">
                        </div>
                        <div>
                            <label>Sobrenome</label>
                            <input type="text" name="admin_lastname">
                        </div>
                    </div>
                    <div class="row cols-2">
                        <div>
                            <label>E-mail</label>
                            <input type="email" name="admin_email">
                        </div>
                        <div>
                            <label>Senha <small style="font-weight:400;color:#94a3b8;text-transform:none;">(deixe vazio pra gerar automaticamente)</small></label>
                            <input type="text" name="admin_password" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <a href="<?php echo base_url('painel'); ?>" class="saas-btn saas-btn-default">Cancelar</a>
            <button type="submit" class="saas-btn saas-btn-primary"><i class="fa fa-save"></i> Salvar</button>
        </div>
    <?php echo form_close(); ?>
</div>
</body>
</html>
