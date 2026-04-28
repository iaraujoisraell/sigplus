<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <title>Redefinir Senha</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">
  <div class="card shadow p-4" style="width:400px;">
    <h4 class="text-center mb-4">Redefinir Senha</h4>
    <?php $CI = &get_instance(); ?>
    <form method="post" action="<?= base_url('auth/salvar_nova_senha') ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
        value="<?= $this->security->get_csrf_hash(); ?>" />

      <input type="hidden" name="token" value="<?= $token ?>">

      <div class="mb-3">
        <label for="senha" class="form-label">Nova senha</label>


       <!-- <input type="password" class="form-control" name="senha" required>-->

        <div id="mensagemErro" style="display: none;">
          <label style="color: red;">(A senha precisa ter no mínimo 6 caracteres com pelo menos uma letra e um número)</label>
        </div>


        <div class="col-md-12">
          <input name="senha" id="senha" class="form-control" type="password" placeholder="SENHA" oninput="validarSenha();" required />
        </div>
        <div class="col-md-12">
          <input name="senha_conf" id="senha_conf" class="form-control" type="password" placeholder="CONFIRMA SENHA" oninput="confirma_senha();" required />

        </div>
        <div id="mensagemErroConfirma" style="display: none;">
          <label style="color: red;">(Senha não confere.)</label>
        </div>


      </div>
      <button type="submit" class="btn btn-success w-100">Salvar nova senha</button>
    </form>
  </div>
</body>

</html>

<script>
  function confirma_senha() {

    var senha = document.getElementById("senha").value;
    var senhaConfirma = document.getElementById("senha_conf").value;

    var mensagemErro = document.getElementById("mensagemErroConfirma");

    if (senhaConfirma != senha) {
      mensagemErro.style.display = "block";
    } else {
      mensagemErro.style.display = "none";
    }

  }


  function validarSenha() {
    // alert("aqui");
    // Obtém o campo de senha e a mensagem de erro
    var senha = document.getElementById("senha").value;
    var mensagemErro = document.getElementById("mensagemErro");
    // var botaoSubmit = document.getElementById("botaoSubmit");

    // Expressão regular para garantir pelo menos 1 letra e 1 número
    var regex = /^(?=.*[a-zA-Z])(?=.*\d)/;

    // Verifica se a senha atende aos requisitos
    if (regex.test(senha) && senha.length >= 6) {

      // Se a senha é válida, limpa a mensagem de erro e ativa o botão
      mensagemErro.style.display = "none"; // Limpa a mensagem de erro
      // botaoSubmit.disabled = false; // Ativa o botão de enviar
    } else {
      // Se a senha não é válida, exibe a mensagem de erro e desativa o botão
      mensagemErro.style.display = "block";
      //   botaoSubmit.disabled = true; // Desativa o botão de enviar
    }
  }
</script>