<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Esqueci minha senha</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
  <div class="card shadow p-4" style="width:400px;">
    <h4 class="text-center mb-4">Esqueci minha senha</h4>

    <?php if($this->session->flashdata('sucesso')): ?>
      <div class="alert alert-success"><?= $this->session->flashdata('sucesso'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('erro')): ?>
      <div class="alert alert-danger"><?= $this->session->flashdata('erro'); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('auth/enviar_link_redefinicao') ?>">
       <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
         value="<?= $this->security->get_csrf_hash(); ?>" />
      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Enviar link</button>
    </form>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script>
    $('#cpf').mask('000.000.000-00');
  </script>
</body>
</html>
