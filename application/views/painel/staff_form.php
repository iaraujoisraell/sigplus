<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('painel/_layout', ['title' => 'Novo usuário']); ?>

<div class="saas-page">
    <?php if ($msg): ?>
        <div class="alert ok"><i class="fa fa-info-circle"></i> <?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="saas-h">
        <h1>
            <a href="<?php echo base_url('painel/empresa/' . (int) $empresa['id']); ?>" style="color:#64748b;text-decoration:none;"><i class="fa fa-arrow-left"></i></a>
            <i class="fa fa-user-plus"></i> Novo usuário · <?php echo html_escape($empresa['company']); ?>
        </h1>
    </div>

    <?php echo form_open('painel/staff_add/' . (int) $empresa['id'], ['class' => 'saas-form']); ?>
        <div class="saas-card">
            <div class="saas-card-h">Dados</div>
            <div class="saas-card-b">
                <div class="row cols-2">
                    <div>
                        <label>Nome <span style="color:#dc2626;">*</span></label>
                        <input type="text" name="firstname" required>
                    </div>
                    <div>
                        <label>Sobrenome</label>
                        <input type="text" name="lastname">
                    </div>
                </div>
                <div class="row cols-2">
                    <div>
                        <label>E-mail <span style="color:#dc2626;">*</span></label>
                        <input type="email" name="email" required>
                    </div>
                    <div>
                        <label>Senha <small style="font-weight:400;color:#94a3b8;text-transform:none;">(deixe vazio pra gerar)</small></label>
                        <input type="text" name="password" autocomplete="new-password">
                    </div>
                </div>
                <label style="display:flex;align-items:center;gap:6px;font-weight:400;text-transform:none;font-size:14px;margin-top:6px;">
                    <input type="checkbox" name="admin" value="1"> Conceder permissão de administrador
                </label>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:8px;">
            <a href="<?php echo base_url('painel/empresa/' . (int) $empresa['id']); ?>" class="saas-btn saas-btn-default">Cancelar</a>
            <button type="submit" class="saas-btn saas-btn-primary"><i class="fa fa-user-plus"></i> Criar usuário</button>
        </div>
    <?php echo form_close(); ?>
</div>
</body>
</html>
